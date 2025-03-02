import { loadProfileContent } from './profile.js';

export function loadLeaderBoardContent() {
    // Realizamos el llamado AJAX
    $.ajax({
        url: './../includes/src/getters/get_top5.php',  // URL de la API que devuelve los datos en formato JSON
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Procesamos la respuesta JSON y construimos el contenido dinámicamente
            let leaderboardContent = `
                <div class="center-container">
                    <h1>🏆 Mejores Cylons 🏆</h1>
                    <div class="leaderboard-container">
                        <div class="box">
                            <h2>Top 10 Cylons</h2>
                            <ol class="leaderboard-list">
            `;

            // Recorremos los 5 primeros usuarios y generamos las filas
            data.forEach((user, index) => {
                leaderboardContent += `
                    <li>
                        <div class="profile">
                            <img class="profile-pic" src="/CYLONNet/assets/images/profile${user.icon}" alt="${user.username}">
                            <span class="username" data-username="${user.username}">${user.username}</span>
                            <span class="xp">: ${user.xp}xp</span>
                        </div>
                    </li>
                `;
            });

            // Cerramos el HTML de la lista y el contenedor
            leaderboardContent += `
                            </ol>
                        </div>
                    </div>
                </div>
            `;

            // Actualizamos el contenido de #content con la nueva estructura generada
            $('#content').html(leaderboardContent);
        },
        error: function(xhr, status, error) {
            // En caso de error en la llamada AJAX
            console.error("Error al obtener el leaderboard:", error);
            $('#content').html('<p>Error al cargar el leaderboard.</p>');
        }
    });

    // Delegación de eventos para cargar el perfil al hacer clic sobre el nombre de usuario
    $(document).on('click', '.username', function() {
        const username = $(this).data('username'); // Obtener el username del atributo 'data-username'
        console.log(username); // Para verificar el valor
        loadProfileContent(username); // Llamar a la función con el username
    });
}
