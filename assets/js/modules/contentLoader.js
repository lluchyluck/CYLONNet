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
    </div>
  `);
}

function loadMissionsContent() {
  $('#content').html(`
    <div class="box">
      <h2>Available Missions</h2>
      <input type="text" id="mission-search" placeholder="Search missions...">
      <div id="mission-grid">
        <div class="mission-box">
          <div>
            <h3>Cylon Infiltration</h3>
            <p>Infiltrate a Cylon base and retrieve classified information.</p>
          </div>
          <button class="button see-contract">See Contract</button>
        </div>
        <div class="mission-box">
          <div>
            <h3>FTL Jump Hack</h3>
            <p>Hack into the FTL drive system to enable emergency jump.</p>
          </div>
          <button class="button see-contract">See Contract</button>
        </div>
        <div class="mission-box">
          <div>
            <h3>Resurrection Ship Takedown</h3>
            <p>Disable the Cylon's resurrection technology.</p>
          </div>
          <button class="button see-contract">See Contract</button>
        </div>
      </div>
    </div>
  `);

  // Añadir eventos y lógica específica de la sección "missions"
  $('.see-contract').click(function () {
    const missionTitle = $(this).closest('.mission-box').find('h3').text();
    const missionDescription = $(this).closest('.mission-box').find('p').text();
    showMissionPopup(missionTitle, missionDescription);
  });

  $('#mission-search').on('input', function () {
    const searchTerm = $(this).val().toLowerCase();
    $('.mission-box').each(function () {
      const missionText = $(this).text().toLowerCase();
      $(this).toggle(missionText.includes(searchTerm));
    });
  });
}

function loadLoginContent() {
  $('#content').html(`
    <div class="box login-form">
      <h2>Login</h2>
      <form id="login-form">
        <input type="text" placeholder="Username" required><br>
        <input type="password" placeholder="Password" required><br>
        <button type="submit" class="button">Login</button>
      </form>
    </div>
  `);

  $('#login-form').submit(function (e) {
    e.preventDefault();
    const username = $(this).find('input[type="text"]').val();
    const password = $(this).find('input[type="password"]').val();
    login(username, 'user@example.com');
  });
}

function loadRegisterContent() {
  $('#content').html(`
    <div class="box register-form">
      <h2>Register</h2>
      <form id="register-form">
        <input type="text" placeholder="Username" required><br>
        <input type="email" placeholder="Email" required><br>
        <input type="password" placeholder="Password" required><br>
        <button type="submit" class="button">Register</button>
      </form>
    </div>
  `);

  $('#register-form').submit(function (e) {
    e.preventDefault();
    const username = $(this).find('input[type="text"]').val();
    const email = $(this).find('input[type="email"]').val();
    const password = $(this).find('input[type="password"]').val();
    login(username, email);
  });
}
