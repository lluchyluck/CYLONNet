import { loadContent } from "./../contentHandler.js";

export function loadProfileContent(user) {
    if(user !== ''){
        $.ajax({
            url: `./includes/src/getters/get_profile.php?username=${user}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                try {
                    // Validamos que los datos esperados estén presentes
                    if (!data || !data.username || !data.email || !data.icon || !Array.isArray(data.missions)) {
                        throw new Error("La respuesta del servidor está incompleta o malformada.");
                    }

                    const username = data.username;
                    const email = data.email;
                    const icon = data.icon;
                    const missions = data.missions;
                    const xp = data.xp;
                    const level = calculateLevel(xp).currentLevel;                 
                    const levelImg = rankImage(level);

                    // Construimos el HTML principal
                    let profileHTML = `
                        <div class="box">
                            <h1 style="display: flex; align-items: center; gap: 10px;">
                                <div class="profile-pic-container">
                                    <img src="./assets/images/profile${icon}" style="width: 100px; height: 100px;" alt="Profile picture" class="profile-pic">
                                    <img id="rank-badge-user" class="rank-badge" src="${levelImg}" style="height: 70px; width: auto;">
                                </div>
                                <div>
                                    <span style="font-size: 20px; font-weight: bold;">${username}</span><br>
                                    <span style="font-size: 16px; color: #ccc;">${email}</span><br>
                                    <span style="font-size: 20px; font-weight: bold;">Rank: ${level}</span>
                                </div>
                            </h1>
                            <h2>Misiones completadas: ${missions.length}</h2>
                            <div id="missions-list">
                    `;

                    // Iteramos sobre el array de misiones
                    missions.forEach(mission => {
                        if (!mission.icon || !mission.name || !mission.tags) {
                            throw new Error("Una de las misiones tiene datos incompletos.");
                        }
                        // Generar estrellas según la dificultad
                        const maxStars = 5; // Número máximo de estrellas
                        const starIcon = '★'; // Ícono de estrella personalizado
                        const emptyStarIcon = '☆'; // Ícono para estrellas vacías
                        const filledStars = starIcon.repeat(mission.difficulty);
                        const emptyStars = emptyStarIcon.repeat(maxStars - mission.difficulty);
                        const difficultyStars = filledStars + emptyStars;

                        const tags = JSON.parse(mission.tags).tagnames.join(', ');
                        profileHTML += `
                        <div class="box" style="display: flex; align-items: center; gap: 20px; padding: 10px; border-radius: 10px; margin-bottom: 10px;">   
                            <div>
                                <img src="./assets/images/missions${mission.icon}" alt="${mission.name} icon" style="width: 100px; height: 100px;">
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <div><h3>${mission.name}</h3></div>
                                <div><strong>Tags:</strong> ${tags}</div>
                                <div><strong>Difficulty: </strong><span style="font-size: 28px;">${difficultyStars}</span></div>
                            </div>
                        </div>
                        `;
                    });

                    // Cerramos las etiquetas HTML
                    profileHTML += `
                        </div>
                    `;

                    // Insertamos el contenido en el contenedor
                    $('#content').html(profileHTML);

                } catch (error) {
                    console.error("Error procesando los datos del perfil:", error.message);
                    loadContent('login');
                }
            },
            error: function(xhr, status, error) {
                console.error(`Error en la solicitud AJAX: ${status} - ${error}`);
                $('#content').html("<p>No se pudo cargar el perfil. Por favor, revisa si estás registrado o tu conexión es inestable.</p>");
            }
        });
    }else{
        loadContent('home');
    }
}
