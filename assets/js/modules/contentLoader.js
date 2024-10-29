function loadContent(page) {
  $('#content').empty();
  switch (page) {
    case 'home':
      loadHomeContent();
      break;
    case 'missions':
      loadMissionsContent();
      break;
    case 'login':
      loadLoginContent();
      break;
    case 'register':
      loadRegisterContent();
      break;
    case 'developer':
      selectDeveloperContent();
      break;
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
      </div>
      <div class="mission-popup-content">
        <span class="close-popup">&times;</span>
        <h2>${title}</h2>
        <h3>Mission Details:</h3>
        <p>${description}</p>
        <button class="button start-mission">Start Mission</button>
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
      </form>F
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
                  <input type="file" id="icon_select" name="image" accept=".png,.jpg,.jpeg,.gif" required><br><br>

                  <label for="docker_file">Selecciona un archivo Docker:</label><br>
                  <input type="file" id="docker_file" name="docker_file" accept=".dockerfile,.tar,.zip"><br><br>
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

    </div>
  `);
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