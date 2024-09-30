// animations.js

// Función para animar el campo estelar
function animateStarfield() {
  const canvas = document.getElementById('starfield');
  const ctx = canvas.getContext('2d');
  let width, height;

  // Función para ajustar el tamaño del canvas
  function setCanvasSize() {
    width = window.innerWidth;
    height = window.innerHeight;
    canvas.width = width;
    canvas.height = height;
  }

  setCanvasSize();
  window.addEventListener('resize', setCanvasSize); // Ajustar tamaño cuando se redimensiona la ventana

  // Crear estrellas
  const stars = [];
  const numStars = 1500;
  const maxDepth = 32;

  for (let i = 0; i < numStars; i++) {
    stars.push({
      x: Math.random() * width - width / 2,
      y: Math.random() * height - height / 2,
      z: Math.random() * maxDepth
    });
  }

  // Función para mover las estrellas
  function moveStars(distance) {
    stars.forEach(star => {
      star.z -= distance;
      if (star.z <= 0) {
        star.z = maxDepth;
        star.x = Math.random() * width - width / 2;
        star.y = Math.random() * height - height / 2;
      }
    });
  }

  // Función para dibujar las estrellas
  function drawStars() {
    const centerX = width / 2;
    const centerY = height / 2;

    // Limpiar el canvas
    ctx.fillStyle = 'black';
    ctx.fillRect(0, 0, width, height);

    // Dibujar cada estrella
    stars.forEach(star => {
      const x = (star.x / star.z) * maxDepth + centerX;
      const y = (star.y / star.z) * maxDepth + centerY;

      if (x >= 0 && x <= width && y >= 0 && y <= height) {
        const size = (1 - star.z / maxDepth) * 5;
        const brightness = 1.2 - star.z / maxDepth;

        ctx.fillStyle = `rgba(255, 215, 0, ${brightness})`; // Color de las estrellas
        ctx.beginPath();
        ctx.arc(x, y, size / 2, 0, Math.PI * 2);
        ctx.fill();
      }
    });
  }

  // Animación de las estrellas
  let lastTime = 0;
  function animate(currentTime) {
    if (!lastTime) lastTime = currentTime;
    const deltaTime = currentTime - lastTime;
    lastTime = currentTime;

    moveStars(0.1 * deltaTime / 16);
    drawStars();
    requestAnimationFrame(animate);
  }

  requestAnimationFrame(animate);
}

// Función para animar el texto aleatorio
function startTextAnimation() {
  const targetText = "CYLONNet";
  const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+[]{}|;:',.<>?/~`-=\\";
  const textElement = document.querySelector("#animated-text h1"); // Selecciona el <h1> dentro del div
  
  let iterations = 0;

  // Función para aleatorizar el texto
  function randomizeText() {
    let currentText = textElement.innerText.split('');
    
    // Cambia cada letra aleatoriamente hasta que se llegue a la letra correcta
    currentText = currentText.map((char, index) => {
      if (index < iterations) {
        return targetText[index]; // Mantén las letras correctas que ya están en su lugar
      }
      return letters[Math.floor(Math.random() * letters.length)]; // Letra aleatoria
    });
    
    textElement.innerText = currentText.join('');
    
    if (iterations < targetText.length) {
      iterations++; // Avanzar una letra en cada iteración
    } else {
      clearInterval(animationInterval); // Detener la animación cuando el texto esté completo
    }
  }
  
  // Intervalo para cambiar las letras cada 140 milisegundos
  const animationInterval = setInterval(randomizeText, 140);
}
