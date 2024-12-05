function login(username, email) {
  currentUser = { username, email };
  $('#username').text(username);
  $('#dropdown-username').text(username);
  $('#dropdown-email').text(email);
  loadContent('home');
}

function logout() {
  currentUser = null;
  $('#username').text('Guest');
  $('#dropdown-username').text('Guest');
  $('#dropdown-email').text('Not logged in');
  loadContent('home');
}

function profile(username){
  loadContent('profile', username);
}
