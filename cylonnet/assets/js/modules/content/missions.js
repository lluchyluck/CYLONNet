import { loadContent } from '../contentHandler.js';
import { loadProfileContent } from './profile.js'; //dependecias de modulos

export function loadMissionsContent() {
    $('#content').html(`
      <div class="box">
        <h2>Misiones disponibles</h2>
         <div style="display: flex; gap: 10px;">
          <input type="text" id="mission-search" placeholder="Buscar misiones...">
          <select id="tag-filter">
            <option value="">Tags</option>
          </select>
        </div>
        <div id="mission-grid"></div>
      </div>
    `);
  
    // Cargar las misiones dinámicamente desde el servidor
    $.ajax({
      url: './includes/src/getters/get_missions.php', // Archivo PHP en el backend que devuelve las misiones
      method: 'GET',
      success: function (missions) {
        const missionGrid = $('#mission-grid');
        missionGrid.empty();
  
        missions.forEach(mission => {
  
          const tagsJSON = JSON.parse(mission.tags);
          const tagsArray = tagsJSON.tagnames;
          const tagsList = Array.isArray(tagsArray) ? tagsArray.join(', ') : 'No tags available';
          
          // Generar estrellas según la dificultad
          const maxStars = 5; // Número máximo de estrellas
          const starIcon = '★'; // Ícono de estrella personalizado
          const emptyStarIcon = '☆'; // Ícono para estrellas vacías
          const filledStars = starIcon.repeat(mission.difficulty);
          const emptyStars = emptyStarIcon.repeat(maxStars - mission.difficulty);
          const difficultyStars = filledStars + emptyStars;
  
          const missionBox = `
            <div class="mission-box">
              <img src="./assets/images/missions${mission.icon}" alt="${mission.name} icon" style="width: 100px; height: 100px; margin-right: 20px;"> 
              <div style="width:1900px">
                <h3>${mission.name}</h3>
                <p>${mission.description.split(' ').slice(0, 9).join(' ')}...</p>
                <h2 style="display: none;">${mission.description}</h2>
                <div class="tags-box">
                  <strong>Tags:</strong> <span>${tagsList}</span>
                </div>
                <div><strong>Dificultad: </strong><span style="font-size: 28px;">${difficultyStars}</span></div>
                <p><strong>Creador:</strong><span class="mission-username"  style="cursor: pointer; color: inherit; transition: color 0.3s ease;" onmouseover="this.style.filter='brightness(1.2)';" onmouseout="this.style.filter='brightness(1)';" data-username="${mission.username}">${mission.username}</span></p>
                <h4 style="display: none;">${mission.id}</h4>
              </div>
              <button class="button submit-flag">Validar Flag</button>
              <button class="button see-contract">Ver Contrato</button>
            </div>
          `;
          missionGrid.append(missionBox);
        });
        $('.mission-username').on('click', function() {
          const username = $(this).data('username'); // Obtener el username del atributo 'data-username'
          loadProfileContent(username); // Llamar a la función con el username
        });
        // Añadir eventos y lógica para los botones
        $('.see-contract').click(function () {
          const missionTitle = $(this).closest('.mission-box').find('h3').text();
          const missionDescription = $(this).closest('.mission-box').find('h2').text();
          const missionIcon = $(this).closest('.mission-box').find('img').attr('src');
          const missionId = $(this).closest('.mission-box').find('h4').text();
          showMissionPopup(missionTitle, missionDescription, missionIcon, missionId);
        });
        $('.submit-flag').click(function () {
          const missionId = $(this).closest('.mission-box').find('h4').text();
          const missionTitle = $(this).closest('.mission-box').find('h3').text();
          const options = [{id: missionId,name: missionTitle}];
          loadContent('submitflag', options);
        });
      },
      error: function () {
        alert('Error al cargar las misiones');
      }
    });
  
    // Cargar las etiquetas dinámicamente desde el servidor
    $.ajax({
      url: './includes/src/getters/get_tags.php', // Archivo PHP que devuelve las etiquetas
      method: 'GET',
      success: function (tags) {
        let tagList = tags;
        let tagSelect = $('#tag-filter');
        tagSelect.empty();
        tagSelect.append('<option value="">All Tags</option>');
  
        tagList.forEach(function (tag) {
          tagSelect.append('<option value="' + tag + '">' + tag + '</option>');
        });
      },
      error: function () {
        alert('Error al cargar las etiquetas');
      }
    });
    // Filtrar misiones en base a la etiqueta seleccionada
    $('#tag-filter').on('change', function () {
      const selectedTag = $(this).val().toLowerCase();
      $('.mission-box').each(function () {
        const missionText = $(this).text().toLowerCase();
        $(this).toggle(missionText.includes(selectedTag));
      });
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
  function showMissionPopup(title, descriptions, image, id) {
    // Si "descriptions" es un string, lo convertimos en un array separando por saltos de línea.
    if (typeof descriptions === 'string') {
      descriptions = descriptions.split('\n').filter(phrase => phrase.trim() !== "");
    }
    
    let currentIndex = 0;
    const popupHTML = `
      <div class="mission-popup">
        <div class="mission-container">
          <span class="close-popup">&times;</span>
          <div class="mission-image">
            <img src="${image}" alt="Personaje - Misión">
          </div>
          <div class="mission-popup-content" style="flex: 1; margin: 0;">
            <h2>${title}</h2>
            <div class="mission-textbox">
              <p class="mission-description">${descriptions[currentIndex]}</p>
            </div>
            <button class="button start-mission">Iniciar Misión</button>
          </div>
        </div>
      </div>
    `;
    
    $('body').append(popupHTML);
    $('.mission-popup').fadeIn();
    
    // Al hacer clic en el cuadro de texto, muestra la siguiente frase
    $('.mission-textbox').click(function(e) {
      e.stopPropagation();
      currentIndex++;
      if (currentIndex < descriptions.length) {
        $('.mission-description').fadeOut(200, function() {
          $(this).text(descriptions[currentIndex]).fadeIn(200);
        });
      }
    });
    
    // Cerrar la ventana emergente al hacer clic en la "X" o fuera del contenedor
    $('.close-popup, .mission-popup').click(function(e) {
      if (e.target === this) {
        $('.mission-popup').fadeOut(function() {
          $(this).remove();
        });
      }
    });
    
    // Iniciar la misión al hacer clic en el botón start mission
    $('.start-mission').click(function() {
      $.ajax({
        url: './includes/src/start_mission.php', 
        type: 'POST',
        data: { missionId: id },
        success: function(response) {
          alert(response);
          $('.mission-popup').fadeOut(function() {
            $(this).remove();
          });
        },
        error: function(xhr, status, error) {
          alert('Error al iniciar la misión: ' + error);
        }
      });
    });
  
  }