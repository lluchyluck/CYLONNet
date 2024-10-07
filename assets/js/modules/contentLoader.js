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
      <input type="text" id="mission-search" placeholder="Search missions...">
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
        const missionBox = `
          <div class="mission-box">
            <div style="width:1900px">
              <h3>${mission.name}</h3>
              <p>${mission.description}</p>
            </div>
            <button class="button see-contract">See Contract</button>
          </div>
        `;
        missionGrid.append(missionBox);
      });

      // Añadir eventos y lógica para los botones
      $('.see-contract').click(function () {
        const missionTitle = $(this).closest('.mission-box').find('h3').text();
        const missionDescription = $(this).closest('.mission-box').find('p').text();
        showMissionPopup(missionTitle, missionDescription);
      });
    },
    error: function () {
      alert('Error al cargar las misiones');
    }
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
function showMissionPopup(title, description) {
  const popupHTML = `
    <div class="mission-popup">
      <div class="mission-popup-content">
        <span class="close-popup">&times;</span>
        <h2>${title}</h2>
        <p>${description}</p>
        <h3>Mission Details:</h3>
        <p>This is a detailed description of the mission, including objectives, risks, and potential rewards.</p>
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
    alert('Starting Docker container for the selected mission...');
    $('.mission-popup').fadeOut(function () {
      $(this).remove();
    });
  });
}
function loadLoginContent() {
  $('#content').html(`
    <div class="box login-form">
      <h2>Login</h2>
       <form id="login" action="../includes/src/formularios/procesar_formulario.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login_button" class="button">Login</button>
      </form>
    </div>
  `);
}

function loadRegisterContent() {
  $('#content').html(`
    <div class="box register-form">
      <h2>Register</h2>
      <form id="register" action="../includes/src/formularios/procesar_formulario.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="register_button" class="button">Register</button>
      </form>
    </div>
  `);
}
