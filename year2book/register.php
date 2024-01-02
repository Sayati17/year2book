<?php
  session_start();
  if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Register Page</title>
    <link rel="stylesheet" href="./assets/register.css" />
  </head>
  <body>
    <div class="container">
      <div class="register">
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
        <form action="controller/registercontrol.php" method="POST">
          <h1>Register</h1>
          <hr />
          <label for="">Username</label>
          <input type="text" id="username" name="username" placeholder="Input Your Username" required />
          <label for="">Email Address</label>
          <input
            type="email"
            placeholder="Input Your Email" id="email" name="email"
            required
          />
          <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="off" placeholder="Password">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          <!-- <label for="">Confirm Password</label>
          <input type="password" placeholder="Confirm Your Password" /> -->
          <button class="submit" name="submit" type="submit">Save</button>
          <button class="back">
            <a href="./login_page.php">Back</a>
          </button>
        </form>
      </div>
      </div>
      
    </div>
  </body>
</html>
