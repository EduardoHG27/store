<?php include 'includes/session.php';
include("keys.php");
?>

<?php
if (isset($_SESSION['user'])) {
  header('location: cart_view.php');
}

if (isset($_SESSION['captcha'])) {
  $now = time();
  if ($now >= $_SESSION['captcha']) {
    unset($_SESSION['captcha']);
  }
}

?>
<script src='https://www.google.com/recaptcha/api.js?render=<?php echo SITE_KEY; ?>'></script>
<link rel="stylesheet" href="includes/css/login.css">




<div class="">
  <?php
  if (isset($_SESSION['error'])) {
    echo "
          <div class='tag is-danger is-large'>
            <p>" . $_SESSION['error'] . "</p> 
          </div>
        ";
    unset($_SESSION['error']);
  }

  if (isset($_SESSION['success'])) {
    echo "
          <div class='tag is-correct is-large'>
            <p>" . $_SESSION['success'] . "</p> 
          </div>
        ";
    unset($_SESSION['success']);
  }
  ?>

</div>

<?php include 'includes/scripts.php' ?>

<h2>Ecommerce: Tienda en linea</h2>
<div class="container" id="container">
  <div class="form-container sign-up-container">


    <form action="register.php" method="POST">
      <h1>Crea una Cuenta</h1>
      <div class="social-container">
        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
      </div>

      <input type="text" class="form-control" name="firstname" placeholder="Nombre" value="<?php echo (isset($_SESSION['firstname'])) ? $_SESSION['firstname'] : '' ?>" required>
      <input type="text" class="form-control" name="lastname" placeholder="Apellido" value="<?php echo (isset($_SESSION['lastname'])) ? $_SESSION['lastname'] : '' ?>" required>
      <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : '' ?>" required>
      <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
      <input type="password" class="form-control" name="repassword" placeholder="Repite contraseña" required>
      <input type="text" class="form-control" id="google-response-token" name="google-response-token" style="display: none" required>



      <button type="submit" class="btn btn-primary btn-block btn-flat" name="signup"><i class="fa fa-pencil"></i>Registrate</button>

    </form>


  </div>
  <div class="form-container sign-in-container">
    <form action="verify.php" method="POST">
      <h1>Acceder</h1>
      <div class="social-container">

      </div>

      <input type="email" class="form-control" name="email" placeholder="Email" required>
      <input type="password" class="form-control" name="password" placeholder="Password" required>
      <a href="password_forgot.php">Olvide mi contraseña</a><br>
      <a href="index1.php"><i class="fa fa-home"></i> Home</a>
      <button type="submit" class="btn btn-primary btn-block btn-flat" name="login"><i class="fa fa-sign-in"></i> Entrar</button>
    </form>

  </div>
  <div class="overlay-container">
    <div class="overlay">
      <div class="overlay-panel overlay-left">
        <h1>Bienvenido de nuevo!</h1>
        <p>Para seguir conectado con nosotros porfavor ingresa con tu informacion personal</p>
        <button class="ghost" id="signIn">Inicia Sesión</button>
      </div>
      <div class="overlay-panel overlay-right">
        <h1>Hello, Amigo!</h1>
        <p>Ingresa tu informacion personal para empezar el viaje con nosotros </p>
        <button class="ghost" id="signUp">Registrate</button>
      </div>
    </div>
  </div>
</div>

<footer>

</footer>

</html>

<script>
  grecaptcha.ready(function() {
    grecaptcha.execute('<?php echo SITE_KEY; ?>', {
        action: 'homepage'
      })
      .then(function(token) {

        $('#google-response-token').val(token);
      });
  });



  const signUpButton = document.getElementById('signUp');
  const signInButton = document.getElementById('signIn');
  const container = document.getElementById('container');

  signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
  });

  signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
  });
</script>