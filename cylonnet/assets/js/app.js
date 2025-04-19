$(document).ready(function () {
  let currentUser = null;

  // Importar funciones de otros módulos
  loadContent('home'); // Cargar contenido inicial
  initializeMenu(); // Iniciar menú
  setupProfileEvents(); // Configurar eventos de perfil

  // Iniciar la animación de estrellas y texto
  animateStarfield();
  startTextAnimation();
});
