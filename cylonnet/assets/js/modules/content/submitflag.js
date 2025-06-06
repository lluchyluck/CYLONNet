export function loadSubmitFlagContent(options) {
    // Asumimos que 'options' es un array con un objeto que contiene 'id' y 'name'
    const missionId = options[0].id;
    const missionName = options[0].name;

    // Ahora se utiliza 'missionId' y 'missionName' en el HTML que se genera
    $('#content').html(`
        <div class="box login-form" style="width: 750px;">
            <h2>Validar flags para la misión: ${missionName}</h2>
            <h3>Validar user flag</h3>
            <form id="submitflag" action="./includes/src/formularios/formHandler.php" method="POST">
                <input type="hidden" name="missionId" value="${missionId}">
                <input type="hidden" name="type" value="user">
                <input type="text" name="flag" required><br>
                <button type="submit" name="flag_button" class="button">Validar user</button>
            </form>
            <h3>Validar root flag</h3>
            <form id="submitflag" action="./includes/src/formularios/formHandler.php" method="POST">
                <input type="hidden" name="missionId" value="${missionId}">
                <input type="hidden" name="type" value="root">
                <input type="text" name="flag" required><br>
                <button type="submit" name="flag_button" class="button">Validar root</button>
            </form>
        </div>
    `);
}
