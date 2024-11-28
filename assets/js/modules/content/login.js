export function loadLoginContent() {
    $('#content').html(`
      <div class="box login-form">
        <h2>Login</h2>
         <form id="login" action="../includes/src/formularios/formHandler.php" method="POST">
          <input type="text" name="username" placeholder="Username" required><br>
          <input type="password" name="password" placeholder="Password" required><br>
          <button type="submit" name="login_button" class="button">Login</button>
        </form>
        <p>¿No estás registrado?: <a href="javascript:void(0)" onclick="loadContent('register')">regístrate</a></p>
      </div>
    `);
  }