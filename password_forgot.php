<?php include 'includes/session.php'; ?>
<link rel="stylesheet" href="includes/css/login.css">

<body class="hold-transition login-page">
  <div class="login-box">
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
    <div class="login-box-body">


      <br>


      <h2>Restablecer contraseña</h2>
    </div>

    <div class="container" id="container">
      <div class="form-container sign-up-container">


      </div>
      <div class="form-container sign-in-container">
        <form action="reset.php" method="POST">

          <input type="email" class="form-control" name="email" placeholder="Email" required>
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="reset"><i class="fa fa-mail-forward"></i> Send</button>


          <a href="index.php" class="text-center">Home</a><br>
          <a href="signup.php" class="text-center">Register a new membership</a><br>
        </form>

      </div>
      <div class="overlay-container">
        <div class="overlay">
          <div class="overlay-panel overlay-left">
            <h1>Welcome Back!</h1>
            <p>To keep connected with us please login with your personal info</p>
            <button class="ghost" id="signIn">Sign In</button>
          </div>
          <div class="overlay-panel overlay-right">
            <h1>Hola, Amigo!</h1>
            <p class="login-box-msg">Ingresa tu email para recuperar tu password</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'includes/scripts.php' ?>
</body>

</html>