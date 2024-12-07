
export function selectDeveloperContent() {
    $.ajax({
      url: '../includes/src/getters/get_developer.php', // Archivo PHP que devuelve las misiones
      method: 'GET',
      success: function (response) {
        if (response === "1") {
          loadDeveloperContent();
        } else {
          loadNotDeveloperContent();
        }
      },
      error: function () {
        alert('Error al cargar la pestaña de developer');
      }
    });
  }
  function showUploadProgressPopup() {
    // Crear el contenedor del popup
    const popup = document.createElement('div');
    popup.id = 'upload-progress-popup';
    popup.style.cssText = `
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.9);
    border: 2px solid #FFD700;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    color: #FFD700;
    font-family: 'Courier New', monospace;
    z-index: 1000;
  `;
  
    // Crear el círculo de progreso
    const circle = document.createElement('div');
    circle.style.cssText = `
    width: 100px;
    height: 100px;
    margin: 0 auto 20px;
    border-radius: 50%;
    border: 10px solid rgba(255, 215, 0, 0.3);
    border-top: 10px solid #FFD700;
    animation: spin 1s linear infinite;
  `;
    circle.id = 'progress-circle';
  
    // Crear el texto de progreso
    const progressText = document.createElement('div');
    progressText.id = 'progress-text';
    progressText.style.cssText = `
    font-size: 18px;
    font-weight: bold;
    color: #FFD700;
    margin-top: 10px;
  `;
    progressText.textContent = '0%';
    // Crear el texto de respuesta
    const responseText = document.createElement('div');
    responseText.id = 'response-text';
    responseText.style.cssText = `
    font-size: 14px;
    color: #FFD700;
    margin-top: 10px;
    `;
    // Agregar círculo y texto al popup
    popup.appendChild(circle);
    popup.appendChild(progressText);
    popup.appendChild(responseText);
  
    // Insertar el popup en el documento
    document.body.appendChild(popup);
  
    // Agregar animación CSS al documento
    const style = document.createElement('style');
    style.textContent = `
      @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(style);
  }
  
  function updateProgressPopup(currentChunk, totalChunks) {
    const progress = Math.round((currentChunk / totalChunks) * 100);
    const progressText = document.getElementById('progress-text');
    if (progressText) {
      progressText.textContent = `${progress}%`;
    }
  }
  
  function closeProgressPopup() {
    const popup = document.getElementById('upload-progress-popup');
    if (popup) {
      document.body.removeChild(popup);
    }
  }
  function updateTextPopup(message) {
    // Espera que el popup esté presente
    const responseTextElement = document.getElementById('response-text');
    if (responseTextElement) {
      responseTextElement.textContent = message;
    } else {
      // Retrasar y reintentar
      setTimeout(() => updateTextPopup(message), 100);
    }
  }
  
  
  
  // Configuración del tamaño de cada fragmento en bytes
  const CHUNK_SIZE = 20 * 1024 * 1024; // 10 MB
  
  async function uploadDockerFile(file, missionName, description, tags, difficulty, icon) {
    const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
    showUploadProgressPopup();
  
    for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
      const start = chunkIndex * CHUNK_SIZE;
      const end = Math.min(start + CHUNK_SIZE, file.size);
      const chunk = file.slice(start, end);
  
      // Crear un FormData para enviar el fragmento
      const formData = new FormData();
      formData.append('missionName', missionName);
      if (chunkIndex === 0) {
        formData.append('descripcion', description);
        formData.append('tags', tags);
        formData.append('difficulty', difficulty);
        formData.append('icon', icon);
      }
      formData.append('fileName', file.name);
      formData.append('chunkIndex', chunkIndex);
      formData.append('totalChunks', totalChunks);
      formData.append('file', chunk);
  
      try {
        await uploadChunk(formData, chunkIndex, totalChunks); // Espera a que se complete la subida
      } catch (error) {
        console.error(`Error al subir el fragmento ${chunkIndex + 1}: ${error}`);
        updateTextPopup(`ERROR: ${error}`);
        setTimeout(() => {
            closeProgressPopup();
          }, 10000);
        return; // Salir del bucle en caso de error
      }
    }
    updateTextPopup(`Archivo subido con éxito!!!`);
    setTimeout(() => {
      closeProgressPopup();
    }, 10000);
  }
  
  function uploadChunk(formData, chunkIndex, totalChunks) {
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', './../includes/src/upload.php', true);
  
      xhr.onload = function () {
        if (xhr.status === 200 && xhr.responseText === "OK") {
          console.log(`Fragmento ${chunkIndex + 1} de ${totalChunks} subido con éxito`);
          updateProgressPopup(chunkIndex + 1, totalChunks);
  
          if (chunkIndex + 1 === totalChunks) {
            closeProgressPopup();
          }
          resolve(); // Subida exitosa
        } else {
          updateTextPopup(`Fragmento ${chunkIndex + 1}: Error - ${xhr.responseText}`);
          reject(`${xhr.responseText}`);
        }
      };
  
      xhr.onerror = function () {
        reject('Error en la solicitud AJAX');
      };
  
      xhr.send(formData);
    });
  }
  
  
  function loadDeveloperContent() {
    $('#content').html(`
      <div class="box">
        <h2>Developer Panel</h2>
        <div class="box">
            
            <span class="toggle-label">
                <strong>Añadir Misión</strong>
                <span class="arrow">▼</span>
            </span>
            <br><br>
  
            <div class="toggle-content">
                <form id="add-mission" action="../includes/src/formularios/formHandler.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="mission_name" placeholder="Nombre de la misión" required><br>
                    <textarea name="mission_description" placeholder="Descripción de la misión" rows="4" required></textarea><br>
                    <textarea name="mission_tags" id="mission_tags" placeholder="Inserta tags con el select..." rows="2" readonly placeholder="Tags seleccionados" required></textarea>
                    <select class="tag-filter" id="tag-filter">
                      <option value="">All Tags</option>
                    </select><br>

                    <label for="diff_select">Selecciona Dificultad:</label><br>
                    <select name="mission_difficulty" class="difficulty-filter" id="difficulty-filter">
                      <option value="">Selecciona dificultad</option>
                      <option value="1">(*)Muy Facil</option>
                      <option value="2">(**)Facil</option>
                      <option value="3">(***)Medio</option>
                      <option value="4">(****)Dificil</option>
                      <option value="5">(*****)No lo consigue ni chema alonso</option>
                    </select><br><br>
                    
                    <label for="icon_select">Selecciona un icono:</label><br>
                    <input type="file" id="icon_select" name="icon_select" accept=".png,.jpg,.jpeg,.gif" required><br><br>
  
                    <label for="docker_file">Selecciona un archivo Docker:</label><br>
                    <input type="file" id="docker_file" name="docker_file" accept=".tar.gz" required><br><br>
                    <button type="submit" name="add_mission_button" class="button">Añadir Misión</button>
                </form>
            </div>
        </div>
  
        <!-- Formulario para añadir un administrador -->
        <div class="box">
            <!-- Toggle para añadir administrador -->
             <span class="toggle-label">
                <strong>Añadir Administrador</strong>
                <span class="arrow">▼</span>
            </span>
            <br><br>
            
            <!-- Imagen de administrador -->
            <div class="toggle-content" style="display: none;">
                <form id="add-admin" action="../includes/src/formularios/formHandler.php" method="POST">
                    <input type="text" name="username" placeholder="Username" required><br>
                    
                    <button type="submit" name="add_admin_button" class="button">Añadir Administrador</button>
                </form>
            </div>
        </div>
  
        <div class="box"> 
            <span class="toggle-label">
                <strong>Eliminar Misión</strong>
                <span class="arrow">▼</span>
            </span>
            <br><br>
  
            <div class="toggle-content">
                <form id="remove-mission" action="../includes/src/formularios/formHandler.php" method="POST">
                    <input type="text" name="mission_name" placeholder="Nombre de la misión" required><br>
                    <button type="submit" name="remove_mission_button" class="button">Eliminar Misión</button>
                </form>
            </div>
        </div>
  
  
        <div class="box"> 
            <span class="toggle-label">
                <strong>Eliminar Administrador</strong>
                <span class="arrow">▼</span>
            </span>
            <br><br>
  
            <div class="toggle-content">
                <form id="remove-admin" action="../includes/src/formularios/formHandler.php" method="POST">
                    <input type="text" name="admin_name" placeholder="Nombre de usuario del administrador" required><br>
                    <button type="submit" name="remove_admin_button" class="button">Eliminar Administrador</button>
                </form>
            </div>
        </div>
  
      </div>
    `);
    $('#add-mission').submit(function (event) {
      event.preventDefault();  // Evitar el envío del formulario de forma tradicional
  
      const missionName = $('input[name="mission_name"]').val();
      const dockerFileInput = $('#docker_file')[0];
      const description = $('textarea[name="mission_description"]').val();
      const tags = $('textarea[name="mission_tags"]').val();
      const difficulty = $('select[name="mission_difficulty"]').val();
      const iconData = $('#icon_select')[0]
  
      if (dockerFileInput.files.length === 0) {
        alert("Por favor, selecciona un archivo Docker.");
        return;
      }
      if (iconData.files.length === 0) {
        alert("Por favor, selecciona una imagen.");
        return;
      }
  
      const dockerFile = dockerFileInput.files[0];
      const image = iconData.files[0];
      // Subir el archivo Docker fragmentado
      uploadDockerFile(dockerFile, missionName,description,tags, difficulty,image);
    });
    $('.toggle-label').click(function() {
      const arrow = $(this).find('.arrow'); 
      const isVisible = $(this).siblings('.toggle-content').is(':visible'); 
  
      
      arrow.text(isVisible ? '▼' : '▲'); 
  
  
      $(this).siblings('.toggle-content').slideToggle();
    });
    $.ajax({
      url: '../includes/src/getters/get_tags.php', // Archivo PHP que devuelve las etiquetas
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
    $('.tag-filter').on('change', function() {
      const selectedTag = this.value;
      const textarea = $('#mission_tags')[0]; // Obtén el textarea como DOM element
  
      // Solo agrega la etiqueta si se ha seleccionado una válida
      if (selectedTag) {
          // Obtiene el contenido actual del textarea
          let currentTags = textarea.value;
  
          // Verifica si ya hay tags en el textarea
          if (currentTags) {
              currentTags += ', '; // Agrega una coma si ya hay tags
          }
  
          // Agrega la nueva etiqueta
          currentTags += selectedTag;
  
          // Actualiza el textarea con los nuevos tags
          textarea.value = currentTags;
  
          // Limpia el select después de agregar la etiqueta
          this.value = '';
      }
    });
  }
  
  
  function loadNotDeveloperContent() {
    $('#content').html(`
      <div class="box">
        <h2>No tienes los permisos adecuados para acceder a este panel!!!</h2>
        <p>¿Te gustaría crear y subir tus propios laboratorios?, contáctame: <strong>lucalzad@ucm.es.</strong></p>
        
      </div>
    `);
  }