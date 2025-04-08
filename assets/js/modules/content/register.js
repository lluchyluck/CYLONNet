export function loadRegisterContent() {
    $('#content').html(`
      <div class="box register-form">
        <h2>Register</h2>
        <form id="register" action="./includes/src/formularios/formHandler.php" method="POST" enctype="multipart/form-data">
          <input type="text" name="username" placeholder="Username" required><br>
          <input type="email" name="email" placeholder="email" required><br>
          <input type="password" name="password" placeholder="Password" required><br>
          <input type="file" name="image" id="image" accept="image/*"><br><br>
          <button type="submit" name="register_button" class="button">Register</button>
        </form>
        <p>¿Ya estás registrado?: <a href="javascript:void(0)" onclick="loadContent('login')">login</a></p>
      </div>
    `);
  }