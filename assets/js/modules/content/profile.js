import { loadContent } from "./../contentHandler.js";

export function loadProfileContent(user) {
    if(user !== ''){
        $.ajax({
            url: `./../includes/src/getters/get_profile.php?username=${user}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                try {
                    // Validamos que los datos esperados estén presentes
                    if (!data || !data.username || !data.email || !data.icon || !Array.isArray(data.missions)) {
                        throw new Error("La respuesta del servidor está incompleta o malformada.");
                    }

                    const username = data.username;
                    const email = data.email;
                    const icon = data.icon;
                    const missions = data.missions;

                    // Construimos el HTML principal
                    let profileHTML = `
                        <div class="box">
                            <h1 style="display: flex; align-items: center; gap: 10px;">
                                <img src="./../assets/images/profile${icon}" style="width: 100px; height: 100px;" alt="Profile picture" class="profile-pic">
                                <div>
                                    <span style="font-size: 20px; font-weight: bold;">${username}</span><br>
                                    <span style="font-size: 16px; color: #ccc;">${email}</span>
                                </div>
                            </h1>
                            <h2>Misiones completadas: ${missions.length}</h2>
                            <div id="missions-list">
                    `;

                    // Iteramos sobre el array de misiones
                    missions.forEach(mission => {
                        if (!mission.icon || !mission.name || !mission.tags) {
                            throw new Error("Una de las misiones tiene datos incompletos.");
                        }

                        const tags = JSON.parse(mission.tags).tagnames.join(', ');
                        profileHTML += `
                        <div class="box" style="display: flex; align-items: center; gap: 20px; padding: 10px; border-radius: 10px; margin-bottom: 10px;">   
                            <div>
                                <img src="/CYLONNet/assets/images/missions${mission.icon}" alt="${mission.name} icon" style="width: 100px; height: 100px;">
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <h3>${mission.name}</h3>
                                <p><strong>Tags:</strong> ${tags}</p>
                            </div>
                        </div>
                        `;
                    });

                    // Cerramos las etiquetas HTML
                    profileHTML += `
                        </div>
                    `;

                    // Insertamos el contenido en el contenedor
                    $('#content').html(profileHTML);

                } catch (error) {
                    console.error("Error procesando los datos del perfil:", error.message);
                    loadContent('login');
                }
            },
            error: function(xhr, status, error) {
                console.error(`Error en la solicitud AJAX: ${status} - ${error}`);
                $('#content').html("<p>No se pudo cargar el perfil. Por favor, revisa tu conexión e intenta de nuevo.</p>");
            }
        });
    }else{
        loadContent('home');
    }
}
