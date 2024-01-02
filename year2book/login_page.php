<?php 
    session_start();
    $login = false;

    if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Login Page</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="./assets/login_page.css" />
  </head>
  <?php
    if(isset($_SESSION["error_message"])) {
?>
        <div class="alert alert-error">
            <?= $_SESSION["error_message"]; ?>
        </div>

<?php
        unset($_SESSION["error_message"]);
    }
?>
  <body>
    <div class="container">
      <div class="login">
        <form action="controller/login_page_control.php" method="post">
          <h1>Login</h1>
          <hr />
          <label for="">Username</label>
          <input type="text" placeholder="Username" name="username" />
          <label for="password">Password</label>
          <input type="password" id="password" name="password" autocomplete="off" placeholder=" Password">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          <button class="submit" type="submit" name="submit">Login</button>
          <p>
            Don't have an account? Click
            <a href="register.php">here</a>
          </p>
        </form>
      </div>
      <div class="image">
        <img src="./img/image.png" alt="image">
      </div>
    </div>
    <!-- Feather icons -->
    <script>
      feather.replace();
    </script>
  </body>
</html>
