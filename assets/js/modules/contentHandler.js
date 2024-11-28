export async function loadContent(page) {
  const pageLoaders = {
    home: () => import('./content/home.js').then(module => module.loadHomeContent()),
    missions: () => import('./content/missions.js').then(module => module.loadMissionsContent()),
    login: () => import('./content/login.js').then(module => module.loadLoginContent()),
    register: () => import('./content/register.js').then(module => module.loadRegisterContent()),
    developer: () => import('./content/developer.js').then(module => module.selectDeveloperContent()),
  };

  $('#content').empty();
  const loaderFunction = pageLoaders[page];
  if (loaderFunction) {
    try {
      await loaderFunction();
    } catch (error) {
      console.error('Error al cargar la página:', error);
      $('#content').append('<p>Error al cargar el contenido.</p>');
    }
  } else {
    console.error(`No se encuentra la página requerida`);
    $('#content').append('<p>Página no encontrada, esto no es un bugbounty piratilla!!!.</p>');
  }
}


window.loadContent = loadContent;