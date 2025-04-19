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
// Configuración de los niveles: cada nivel requiere más experiencia
const levels = Array.from({ length: 10 }, (_, i) => Math.floor(200 * Math.pow(1.7, i)));

// Función que calcula el nivel actual y la experiencia restante para el siguiente nivel
function calculateLevel(xp) {
  let currentLevel = 0; // Nivel inicial
  let xpForNextLevel = 0;

  for (let i = 0; i < levels.length; i++) {
    if (xp < levels[i]) {
      xpForNextLevel = levels[i];
      currentLevel = i + 1; // Niveles empiezan desde 1
      break;
    }
  }

  // Si XP excede o alcanza el máximo nivel
  if (xp >= levels[levels.length - 1]) {
    currentLevel = levels.length;
    xpForNextLevel = null; // Sin siguiente nivel
  }

  return { currentLevel, xpForNextLevel };
}
function rankImage(currentLevel) {
  // Usa .attr() para establecer el atributo src
  
  return `./assets/images/ranks/rank${currentLevel}.png`;
}

// Actualiza la barra de experiencia
function updateExperienceBar(xp = 0) {
  const { currentLevel, xpForNextLevel } = calculateLevel(xp);
  rankImage(currentLevel);
  console.log(levels);
  // Experiencia necesaria para el nivel actual
  const xpForCurrentLevel = currentLevel > 1 ? levels[currentLevel - 2] : 0;

  // Calcular progreso, evitando divisiones inválidas
  let progress = 100; // Por defecto, progreso completo para el nivel máximo
  if (xpForNextLevel !== null && xpForNextLevel > xpForCurrentLevel) {
    progress = ((xp - xpForCurrentLevel) / (xpForNextLevel - xpForCurrentLevel)) * 100;
  }

  // Actualizar el DOM
  document.getElementById("experience-bar").style.width = `${Math.min(progress, 100)}%`;
  document.getElementById("level-text").innerText = `Level: ${currentLevel}`;
  document.getElementById("xp-display").innerText = xpForNextLevel
    ? `${xp}/${xpForNextLevel}`
    : `${xp} (Max Level)`;
  
}
