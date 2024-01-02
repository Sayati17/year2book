<?php 
    // Set secure session cookie attributes
   session_set_cookie_params([
    'lifetime' => 3600, // adjust as needed
    'path' => '/',
    // 'domain' => 'yourdomain.com',
    'secure' => true, // if deployed set into true for https
    'httponly' => true,
    'samesite' => 'Strict',
]);

   // Start the session
   session_start();

   // Include the database connection file
   require "connection/conn.php";

   // Validate and sanitize the user ID
   $id = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;

   // Redirect to login page if user is not logged in
   if (!isset($_SESSION['login'])) {
       header("Location: login_page.php");
   } else {
       $username = $_SESSION['username'];
   }

   // Regenerate session ID
   session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/style.css">
    <title>Home</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="Home.php">year2book</a></p>
        </div>

        <div class="right-links">
            <a href="./upload.php"><button class="btn">Profile</button></a>
            <a href="./List.php"><button class="btn">List Mahasiswa</button></a>
            <a href="controller/Logout.php"><button class="btn">Log Out</button></a>
        </div>
    </div>
    <main>
       <div class="main-box top">
          <div class="top">
            <div class="box">
                <p>Hello <b><?php echo htmlspecialchars($username) ?></b>, Welcome...</p>
                <?php
                // Fetch user photo from the database
                $display = "SELECT photo FROM users WHERE id = ?;";
                $stmt = $conn->prepare($display);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Display user information and photo
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $photo = $row['photo'];
                    echo "<img src='./storage/{$photo}' style='width='150px' height='180px''>";
                }
                ?>
            </div>
          </div>
       </div>
    </main>

    <div class="About-us">
                <h2>About Us</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis consequatur necessitatibus id sequi perspiciatis earum quo molestias expedita quia facilis, assumenda aperiam blanditiis, porro, saepe commodi quae laborum incidunt libero?</p>
    </div>

    <div class="Contact-Person">
                <h3>Contact Us</h3>
                <p>Instagram: @year2book</p>
                <p>Line: year_2book</p>
    </div>
</body>
</html>
