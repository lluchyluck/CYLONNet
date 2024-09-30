function login(username, email) {
  currentUser = { username, email };
  $('#username').text(username);
  $('#dropdown-username').text(username);
  $('#dropdown-email').text(email);
  $('#profile-pic').attr('src', 'https://cylonnet.bsg/images/logged-in-profile.svg');
  loadContent('home');
}

function logout() {
  currentUser = null;
  $('#username').text('Guest');
  $('#dropdown-username').text('Guest');
  $('#dropdown-email').text('Not logged in');
  $('#profile-pic').attr('src', 'https://cylonnet.bsg/images/default-profile.svg');
  loadContent('home');
}
