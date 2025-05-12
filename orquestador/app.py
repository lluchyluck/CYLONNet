# cylonnet/orchestrator/app.py

import os
import re
import subprocess
from flask import Flask, request, jsonify, abort
import docker
import threading
import time

app = Flask(__name__)
client = docker.DockerClient(base_url='unix://var/run/docker.sock')

NGINX_CONF_DIR = '/app/nginx/conf.d'
LABOS_DIR = '/app/sh/labos'
LAB_NET_PREFIJO = 'lab_'
TTL_LABORATORIO = 60  # minutos
# Permite letras, dígitos, guiones y guiones bajos en cada etiqueta
SUBDOMAIN_RE = re.compile(r'^[A-Za-z0-9](?:[A-Za-z0-9_\-]*[A-Za-z0-9])?(?:\.[A-Za-z0-9](?:[A-Za-z0-9_\-]*[A-Za-z0-9])?)*$')

@app.route('/estado', methods=['POST'])
def estado():
    data = request.get_json(force=True)
    if 'lab' not in data or not isinstance(data['lab'], str):
        abort(400, "Campo 'lab' obligatorio")
    name = data['lab']
    container_name = f"{name}_container"
    try:
        container = client.containers.get(container_name)
        if container.status == 'running':
            return jsonify(status='running'), 409  # 409 Conflict si está corriendo
        else:
            return jsonify(status='not running'), 200
    except docker.errors.NotFound:
        return jsonify(status='not running'), 200
    
def schedule_cleanup(container_name, image_id, net_name):
    def cleanup():
        conf_path = os.path.join(NGINX_CONF_DIR, f"{container_name}.cylonnet.conf")
        try:
            subprocess.run(f"docker rm -f {container_name}", shell=True, check=True)
            subprocess.run(f"docker rmi {image_id}", shell=True, check=True)
            subprocess.run(f"docker network rm {net_name}", shell=True, check=True)
            if os.path.exists(conf_path):
                os.remove(conf_path)
        except subprocess.CalledProcessError as e:
            app.logger.error(f"Error during cleanup: {e}")

    timer = threading.Timer(TTL_LABORATORIO * 60, cleanup)
    timer.start()


def make_nginx_conf(subdomain, target_ip):
    conf = f"""
server {{
    listen 80;
    server_name {subdomain};

    location / {{
        proxy_pass http://{target_ip}:80;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }}
}}
"""
    path = os.path.join(NGINX_CONF_DIR, f'{subdomain}.conf')
    with open(path, 'w') as f:
        f.write(conf)
    
    try:
        subprocess.run("docker exec cylonnet_web_1 nginx -s reload", shell=True, check=True)
    except subprocess.CalledProcessError as e:
        abort(500, "Error recargando la configuracion de nginx: " + str(e))

@app.route('/deploy', methods=['POST'])
def deploy():
    data = request.get_json(force=True)
    # 1) Validar campos
    for field in ('tar_path', 'uflag', 'rflag', 'lab'):
        if field not in data or not isinstance(data[field], str) or not data[field].strip():
            abort(400, f"Falta o es inválido el campo '{field}'")
    tar_path = data['tar_path']
    subdomain = data['lab']

    # 2) Validar subdominio
    if not SUBDOMAIN_RE.fullmatch(subdomain):
        abort(400, "Subdominio inválido")

    # 3) Validar tar_path
    abs_tar = os.path.abspath(tar_path)
    if not abs_tar.startswith(os.path.abspath(LABOS_DIR)):
        abort(400, "ruta de tar fuera de directorio permitido")
    if not os.path.isfile(abs_tar):
        abort(400, "ruta de tar inválida")

    # 4) Extraer nombre base
    basename = os.path.basename(abs_tar)
    if basename.endswith('.tar.gz'):
        name = basename[:-7]
    elif basename.endswith('.tar'):
        name = basename[:-4]
    else:
        name = os.path.splitext(basename)[0]

    container_name = f'{name}_container'

    # 5) Limpieza previa (se supone que el contenedor no está corriendo, se hace por si ocurre algun error)
    try:
        client.containers.get(container_name).remove(force=True)
    except docker.errors.NotFound:
        pass
    try:
        client.images.remove(name, force=True)
    except docker.errors.ImageNotFound:
        pass

    # 6) Cargar imagen
    with open(abs_tar, 'rb') as f:
        image = client.images.load(f.read())[0]

    # 7) Crear/red de laboratorio
    net_name = LAB_NET_PREFIJO + name
    try:
        net = client.networks.get(net_name)
    except docker.errors.NotFound:
        net = client.networks.create(
            net_name,
            driver='bridge',
            internal=False,
            attachable=True
        )
    # Conectar nginx y orquestador a la red privada para la comunicación
    for peer in ('cylonnet_web_1', 'cylonnet_orquestador_1'):
        try:
            net.connect(peer)
        except docker.errors.APIError as e:
            if 'already exists' not in str(e):
                raise

    # 8) Ejecutar contenedor
    container = client.containers.run(
        image.id,
        name=container_name,
        detach=True,
        labels={'lab': name},
        network=net.name
    )
    container.reload()
    timeout = 20  # Tiempo máximo de espera en segundos
    start_time = time.time()
    while container.status != 'running' and time.time() - start_time < timeout:
        time.sleep(1)
        container.reload()
    if container.status != 'running':
        app.logger.error(f"Estado inesperado de {container.name}: {container.status}, puede que la arquitectura del lab no sea compatible u otro error")
        abort(500, "El contenedor no está en estado running, puede que la arquitectura del lab no sea compatible u otro error")
    try:
        bridge = client.networks.get('bridge')
        bridge.connect(container)
    except docker.errors.APIError as e:
        if 'already exists' not in str(e):
            raise

    # 9) Crear flags
    container.exec_run('mkdir -p /flag /root/flag')
    container.exec_run(f"sh -c \"printf '%s' '{data['uflag']}' > /flag/uflag.txt\"")
    container.exec_run(f"sh -c \"printf '%s' '{data['rflag']}' > /root/flag/rflag.txt\"")

    # 10) Recargar attrs y obtener IP
    container.reload()
    ip = container.attrs['NetworkSettings']['Networks'][net.name]['IPAddress']
    if not ip:
        app.logger.error(f"No pude obtener IP de {container.name}: {container.attrs['NetworkSettings']}")
        abort(500, "No se pudo determinar la IP del contenedor")

    # 11) nginx conf & reload y schedule cleanup
    make_nginx_conf(subdomain, ip)
    schedule_cleanup(container.name, image.id, net.name)

    return jsonify({'status': 'ok', 'container': container.name, 'ip': ip, 'subdomain': subdomain}), 201

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=8000,debug=False)
