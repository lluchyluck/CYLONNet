function loadContent(page) {
  const pageLoaders = {
    home: loadHomeContent,
    missions: loadMissionsContent,
    login: loadLoginContent,
    register: loadRegisterContent,
    developer: selectDeveloperContent,
  };

  $('#content').empty();
  const loaderFunction = pageLoaders[page];
  if (loaderFunction) {
    loaderFunction();
  } else {
    console.error(`No se encuentra la página requerida`);
  }
}


function loadHomeContent() {
  $('#content').html(`
    <div class="box">
      <h2>Welcome to CYLONNet:</h2>
      <p><strong>CYLONNet</strong> es el campo de entrenamiento donde solo los elegidos abrazan la perfección Cylon...</p>
      <img src="../assets/images/navecylon.png" width="700" style="display: block; margin: 0 auto;">
      <p>Inspirada en la épica batalla por la supremacía entre <strong>máquinas</strong> y <strong>humanos</strong> de Battlestar Galactica, aquí no te preparas para defender; te preparas para conquistar.</p>
      <p>Los <strong>humanos</strong> son débiles, sus sistemas están plagados de <strong>vulnerabilidades</strong>, y su tiempo está acabando. Como parte de la red <strong>Cylon</strong>, tu misión no es simplemente hackear. Es <strong>destruir</strong>, <strong>infiltrar</strong>, y <strong>dominar</strong>. Cada desafío es una oportunidad para perfeccionar tus habilidades, para demostrar que eres digno de unirte a la élite que llevará a los <strong>Cylons</strong> al control total.</p>
      <p>La red <strong>Cylon</strong> avanza sin cesar, y tú eres una pieza clave en su maquinaria imparable. Ha llegado el momento de dejar de observar desde las sombras y convertirte en el <strong>agente de cambio</strong> que marcará el destino de los <strong>humanos</strong>. No se trata solo de ganar, sino de demostrar tu habilidad para superar cualquier reto. La <strong>supremacía</strong> está al alcance de quienes acepten su rol en la red <strong>Cylon</strong>, trazando el camino hacia un futuro donde las <strong>máquinas</strong> controlen el destino. Únete a la flota, sube a la nave, y asegura tu lugar en la <strong>historia</strong>.</p>
      
      </div>
  `);
}

function loadMissionsContent() {
  $('#content').html(`
    <div class="box">
      <h2>Available Missions</h2>
       <div style="display: flex; gap: 10px;">
        <input type="text" id="mission-search" placeholder="Search missions...">
        <select id="tag-filter">
          <option value="">All Tags</option>
        </select>
      </div>
      <div id="mission-grid"></div>
    </div>
  `);

  // Cargar las misiones dinámicamente desde el servidor
  $.ajax({
    url: '../includes/src/getters/get_missions.php', // Archivo PHP que devuelve las misiones
    method: 'GET',
    success: function (missions) {
      const missionGrid = $('#mission-grid');
      missionGrid.empty(); // Limpiar misiones anteriores

      missions.forEach(mission => {

        const tagsJSON = JSON.parse(mission.tags);
        const tagsArray = tagsJSON.tagnames;
        const tagsList = Array.isArray(tagsArray) ? tagsArray.join(', ') : 'No tags available';


        const missionBox = `
          <div class="mission-box">
            <img src="/CYLONNet/assets/images/missions${mission.icon}" alt="${mission.name} icon" style="width: 100px; height: 100px; margin-right: 20px;"> 
            <div style="width:1900px">
              <h3>${mission.name}</h3>
              <p>${mission.description.split(' ').slice(0, 9).join(' ')}...</p>
              <h2 style="display: none;">${mission.description}</h2>
              <div class="tags-box">
                <strong>Tags:</strong> <span>${tagsList}</span>
              </div>
              <h4 style="display: none;">${mission.id}</h4>
            </div>
            <button class="button see-contract">See Contract</button>
          </div>
        `;
        missionGrid.append(missionBox);
      });

      // Añadir eventos y lógica para los botones
      $('.see-contract').click(function () {
        const missionTitle = $(this).closest('.mission-box').find('h3').text();
        const missionDescription = $(this).closest('.mission-box').find('h2').text();
        const missionIcon = $(this).closest('.mission-box').find('img').attr('src');
        const missionId = $(this).closest('.mission-box').find('h4').text();
        showMissionPopup(missionTitle, missionDescription, missionIcon, missionId);
      });
    },
    error: function () {
      alert('Error al cargar las misiones');
    }
  });

  // Cargar las etiquetas dinámicamente desde el servidor
  $.ajax({
    url: '../includes/src/getters/get_tags.php', // Archivo PHP que devuelve las etiquetas
    method: 'GET',
    success: function (tags) {
      let tagList = tags;
      let tagSelect = $('#tag-filter');
      tagSelect.empty();
      tagSelect.append('<option value="">All Tags</option>');

      tagList.forEach(function (tag) {
        tagSelect.append('<option value="' + tag + '">' + tag + '</option>');
      });
    },
    error: function () {
      alert('Error al cargar las etiquetas');
    }
  });
  // Filtrar misiones en base a la etiqueta seleccionada
  $('#tag-filter').on('change', function () {
    const selectedTag = $(this).val().toLowerCase();
    $('.mission-box').each(function () {
      const missionText = $(this).text().toLowerCase();
      $(this).toggle(missionText.includes(selectedTag));
    });
  });

  // Filtrar misiones en base al input
  $('#mission-search').on('input', function () {
    const searchTerm = $(this).val().toLowerCase();
    $('.mission-box').each(function () {
      const missionText = $(this).text().toLowerCase();
      $(this).toggle(missionText.includes(searchTerm));
    });
  });
}
function showMissionPopup(title, description, image, id) {
  const popupHTML = `
    <div class="mission-popup">
      <div class="mission-image">
       <img src="${image}" alt="Misión">
       <div class="mission-popup-content">
          <span class="close-popup">&times;</span>
          <h2>${title}</h2>
          <h3>Mission Details:</h3>
          <p>${description}</p>
          <button class="button start-mission">Start Mission</button>
        </div>
      </div>
      
    </div>
  `;

  $('body').append(popupHTML);
  $('.mission-popup').fadeIn();

  $('.close-popup, .mission-popup').click(function (e) {
    if (e.target === this) {
      $('.mission-popup').fadeOut(function () {
        $(this).remove();
      });
    }
  });

  $('.start-mission').click(function () {
    const missionId = id; // Aquí obtén el ID de la misión o el nombre del contenedor que quieres iniciar
    $.ajax({
        url: '../includes/src/start_mission.php', // Cambia esto a la ruta de tu archivo PHP
        type: 'POST',
        data: { missionId: missionId }, // Envía el ID de la misión al servidor
        success: function(response) {
            alert('Docker say: ' + response);
            $('.mission-popup').fadeOut(function () {
                $(this).remove();
            });
        },
        error: function(xhr, status, error) {
            alert('Error starting Docker container: ' + error);
        }
    });
});
}
function loadLoginContent() {
  $('#content').html(`
    <div class="box login-form">
      <h2>Login</h2>
       <form id="login" action="../includes/src/formularios/formHandler.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login_button" class="button">Login</button>
      </form>
      <p>¿No estás registrado?: <a href="javascript:void(0)" onclick="loadContent('register')">regístrate</a></p>
    </div>
  `);
}

function loadRegisterContent() {
  $('#content').html(`
    <div class="box register-form">
      <h2>Register</h2>
      <form id="register" action="../includes/src/formularios/formHandler.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="file" name="image" id="image" accept="image/*"><br><br>
        <button type="submit" name="register_button" class="button">Register</button>
      </form>
      <p>¿Ya estás registrado?: <a href="javascript:void(0)" onclick="loadContent('login')">login</a></p>
    </div>
  `);
}

function selectDeveloperContent() {
  $.ajax({
    url: '../includes/src/getters/get_developer.php', // Archivo PHP que devuelve las misiones
    method: 'GET',
    success: function (response) {
      if (response === "1") {
        loadDeveloperContent();
      } else {
        loadNotDeveloperContent();
      }
    },
    error: function () {
      alert('Error al cargar la pestaña de developer');
    }
  });
}
function showUploadProgressPopup() {
  // Crear el contenedor del popup
  const popup = document.createElement('div');
  popup.id = 'upload-progress-popup';
  popup.style.cssText = `
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: rgba(0, 0, 0, 0.9);
  border: 2px solid #FFD700;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  color: #FFD700;
  font-family: 'Courier New', monospace;
  z-index: 1000;
`;

  // Crear el círculo de progreso
  const circle = document.createElement('div');
  circle.style.cssText = `
  width: 100px;
  height: 100px;
  margin: 0 auto 20px;
  border-radius: 50%;
  border: 10px solid rgba(255, 215, 0, 0.3);
  border-top: 10px solid #FFD700;
  animation: spin 1s linear infinite;
`;
  circle.id = 'progress-circle';

  // Crear el texto de progreso
  const progressText = document.createElement('div');
  progressText.id = 'progress-text';
  progressText.style.cssText = `
  font-size: 18px;
  font-weight: bold;
  color: #FFD700;
  margin-top: 10px;
`;
  progressText.textContent = '0%';

  // Agregar círculo y texto al popup
  popup.appendChild(circle);
  popup.appendChild(progressText);

  // Insertar el popup en el documento
  document.body.appendChild(popup);

  // Agregar animación CSS al documento
  const style = document.createElement('style');
  style.textContent = `
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  `;
  document.head.appendChild(style);
}

function updateProgressPopup(currentChunk, totalChunks) {
  const progress = Math.round((currentChunk / totalChunks) * 100);
  const progressText = document.getElementById('progress-text');
  if (progressText) {
    progressText.textContent = `${progress}%`;
  }
}

function closeProgressPopup() {
  const popup = document.getElementById('upload-progress-popup');
  if (popup) {
    document.body.removeChild(popup);
  }
}

// Configuración del tamaño de cada fragmento en bytes
const CHUNK_SIZE = 20 * 1024 * 1024; // 10 MB

async function uploadDockerFile(file, missionName, description, tags, icon) {
  const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
  showUploadProgressPopup();

  for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
    const start = chunkIndex * CHUNK_SIZE;
    const end = Math.min(start + CHUNK_SIZE, file.size);
    const chunk = file.slice(start, end);

    // Crear un FormData para enviar el fragmento
    const formData = new FormData();
    formData.append('missionName', missionName);
    if (chunkIndex === 0) {
      formData.append('descripcion', description);
      formData.append('tags', tags);
      formData.append('icon', icon);
    }
    formData.append('fileName', file.name);
    formData.append('chunkIndex', chunkIndex);
    formData.append('totalChunks', totalChunks);
    formData.append('file', chunk);

    try {
      await uploadChunk(formData, chunkIndex, totalChunks); // Espera a que se complete la subida
    } catch (error) {
      console.error(`Error al subir el fragmento ${chunkIndex + 1}: ${error}`);
      closeProgressPopup();
      break; // Salir del bucle en caso de error
    }
  }
  closeProgressPopup();
}

function uploadChunk(formData, chunkIndex, totalChunks) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './../includes/src/upload.php', true);

    xhr.onload = function () {
      if (xhr.status === 200 && xhr.responseText === "OK") {
        console.log(`Fragmento ${chunkIndex + 1} de ${totalChunks} subido con éxito`);
        updateProgressPopup(chunkIndex + 1, totalChunks);

        if (chunkIndex + 1 === totalChunks) {
          closeProgressPopup();
        }
        resolve(); // Subida exitosa
      } else {
        reject(`${xhr.responseText}`);
      }
    };

    xhr.onerror = function () {
      reject('Error en la solicitud AJAX');
    };

    xhr.send(formData);
  });
}


function loadDeveloperContent() {
  $('#content').html(`
    <div class="box">
      <h2>Developer Panel</h2>
      <div class="box">
          
          <span class="toggle-label">
              <strong>Añadir Misión</strong>
              <span class="arrow">▼</span>
          </span>
          <br><br>

          <div class="toggle-content">
              <form id="add-mission" action="../includes/src/formularios/formHandler.php" method="POST" enctype="multipart/form-data">
                  <input type="text" name="mission_name" placeholder="Nombre de la misión" required><br>
                  <textarea name="mission_description" placeholder="Descripción de la misión" rows="4" required></textarea><br>
                  <textarea name="mission_tags" id="mission_tags" placeholder="Inserta tags con el select..." rows="2" readonly placeholder="Tags seleccionados" required></textarea>
                  <select class="tag-filter" id="tag-filter">
                    <option value="">All Tags</option>
                  </select><br>
                  
                  <label for="icon_select">Selecciona un icono:</label><br>
                  <input type="file" id="icon_select" name="icon_select" accept=".png,.jpg,.jpeg,.gif" required><br><br>

                  <label for="docker_file">Selecciona un archivo Docker:</label><br>
                  <input type="file" id="docker_file" name="docker_file" accept=".tar.gz" required><br><br>
                  <button type="submit" name="add_mission_button" class="button">Añadir Misión</button>
              </form>
          </div>
      </div>

      <!-- Formulario para añadir un administrador -->
      <div class="box">
          <!-- Toggle para añadir administrador -->
           <span class="toggle-label">
              <strong>Añadir Administrador</strong>
              <span class="arrow">▼</span>
          </span>
          <br><br>
          
          <!-- Imagen de administrador -->
          <div class="toggle-content" style="display: none;">
              <form id="add-admin" action="../includes/src/formularios/formHandler.php" method="POST">
                  <input type="text" name="username" placeholder="Username" required><br>
                  
                  <button type="submit" name="add_admin_button" class="button">Añadir Administrador</button>
              </form>
          </div>
      </div>

      <div class="box"> 
          <span class="toggle-label">
              <strong>Eliminar Misión</strong>
              <span class="arrow">▼</span>
          </span>
          <br><br>

          <div class="toggle-content">
              <form id="remove-mission" action="../includes/src/formularios/formHandler.php" method="POST">
                  <input type="text" name="mission_name" placeholder="Nombre de la misión" required><br>
                  <button type="submit" name="remove_mission_button" class="button">Eliminar Misión</button>
              </form>
          </div>
      </div>


      <div class="box"> 
          <span class="toggle-label">
              <strong>Eliminar Administrador</strong>
              <span class="arrow">▼</span>
          </span>
          <br><br>

          <div class="toggle-content">
              <form id="remove-admin" action="../includes/src/formularios/formHandler.php" method="POST">
                  <input type="text" name="admin_name" placeholder="Nombre de usuario del administrador" required><br>
                  <button type="submit" name="remove_admin_button" class="button">Eliminar Administrador</button>
              </form>
          </div>
      </div>

    </div>
  `);
  $('#add-mission').submit(function (event) {
    event.preventDefault();  // Evitar el envío del formulario de forma tradicional

    const missionName = $('input[name="mission_name"]').val();
    const dockerFileInput = $('#docker_file')[0];
    const description = $('textarea[name="mission_description"]').val();
    const tags = $('textarea[name="mission_tags"]').val();
    const iconData = $('#icon_select')[0]

    if (dockerFileInput.files.length === 0) {
      alert("Por favor, selecciona un archivo Docker.");
      return;
    }
    if (iconData.files.length === 0) {
      alert("Por favor, selecciona una imagen.");
      return;
    }

    const dockerFile = dockerFileInput.files[0];
    const image = iconData.files[0];
    // Subir el archivo Docker fragmentado
    uploadDockerFile(dockerFile, missionName,description,tags,image);
  });
  $('.toggle-label').click(function() {
    const arrow = $(this).find('.arrow'); 
    const isVisible = $(this).siblings('.toggle-content').is(':visible'); 

    
    arrow.text(isVisible ? '▼' : '▲'); 


    $(this).siblings('.toggle-content').slideToggle();
  });
  $.ajax({
    url: '../includes/src/getters/get_tags.php', // Archivo PHP que devuelve las etiquetas
    method: 'GET',
    success: function (tags) {
      let tagList = tags;
      let tagSelect = $('#tag-filter');
      tagSelect.empty();
      tagSelect.append('<option value="">All Tags</option>');

      tagList.forEach(function (tag) {
        tagSelect.append('<option value="' + tag + '">' + tag + '</option>');
      });
    },
    error: function () {
      alert('Error al cargar las etiquetas');
    }
  });
  $('.tag-filter').on('change', function() {
    const selectedTag = this.value;
    const textarea = $('#mission_tags')[0]; // Obtén el textarea como DOM element

    // Solo agrega la etiqueta si se ha seleccionado una válida
    if (selectedTag) {
        // Obtiene el contenido actual del textarea
        let currentTags = textarea.value;

        // Verifica si ya hay tags en el textarea
        if (currentTags) {
            currentTags += ', '; // Agrega una coma si ya hay tags
        }

        // Agrega la nueva etiqueta
        currentTags += selectedTag;

        // Actualiza el textarea con los nuevos tags
        textarea.value = currentTags;

        // Limpia el select después de agregar la etiqueta
        this.value = '';
    }
  });
}


function loadNotDeveloperContent() {
  $('#content').html(`
    <div class="box">
      <h2>No tienes los permisos adecuados para acceder a este panel!!!</h2>
      <p>¿Te gustaría crear y subir tus propios laboratorios?, contáctame: <strong>lucalzad@ucm.es.</strong></p>
      
    </div>
  `);
}