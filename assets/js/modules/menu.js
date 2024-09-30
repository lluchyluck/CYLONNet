function initializeMenu() {
  $('.menu a').click(function (e) {
    e.preventDefault();
    const page = $(this).data('page');
    loadContent(page);
  });
}

function setupProfileEvents() {
  $('#profile').click(function () {
    $('#profile-dropdown').toggleClass('show');
  });

  $('#logout-button').click(function () {
    logout();
    $('#profile-dropdown').removeClass('show');
  });

  $(document).click(function (event) {
    if (!$(event.target).closest('#profile, #profile-dropdown').length) {
      $('#profile-dropdown').removeClass('show');
    }
  });
}
