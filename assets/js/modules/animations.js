// animations.js

// Función para animar el campo estelar
function animateStarfield() {
  const canvas = document.getElementById('starfield');
  const ctx = canvas.getContext('2d');
  let width, height;

  // Ajusta el tamaño del canvas al tamaño de la ventana
  function setCanvasSize() {
    width = window.innerWidth;
    height = window.innerHeight;
    canvas.width = width;
    canvas.height = height;
  }

  setCanvasSize();
  window.addEventListener('resize', setCanvasSize); // Reajustar tamaño en redimensionamiento

  // Crear estrellas
  const stars = Array.from({ length: 2000 }, () => ({ //Cantidad de estrellas
    x: Math.random() * width - width / 2,
    y: Math.random() * height - height / 2,
    z: Math.random() * 32 // Profundidad máxima
  }));

  // Mover las estrellas
  function moveStars(distance) {
    stars.forEach(star => {
      star.z -= distance;
      if (star.z <= 0) {
        star.z = 32;
        star.x = Math.random() * width - width / 2;
        star.y = Math.random() * height - height / 2;
      }
    });
  }

  // Dibujar estrellas en el canvas
  function drawStars() {
    const centerX = width / 2;
    const centerY = height / 2;

    // Limpiar el canvas
    ctx.fillStyle = 'black';
    ctx.fillRect(0, 0, width, height);

    stars.forEach(star => {
      const x = (star.x / star.z) * 32 + centerX;
      const y = (star.y / star.z) * 32 + centerY;

      if (x >= 0 && x <= width && y >= 0 && y <= height) {
        const size = (1 - star.z / 32) * 5;
        const brightness = 1.4 - star.z / 32; //primer numero es el brillo

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
    const deltaTime = currentTime - lastTime || 0; // Asegura deltaTime no sea NaN
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
  const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*_+[]{}|:',.?~`-=\\";
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
