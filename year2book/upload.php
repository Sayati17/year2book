<?php
// Turn off error reporting for production
// Set secure session cookie attributes
session_set_cookie_params([
    'lifetime' => 3600, // adjust as needed
    'path' => '/',
    // 'domain' => 'yourdomain.com',
    'secure' => false, // if deployed set into true for https
    'httponly' => true,
    'samesite' => 'Strict',
]);
error_reporting(0);
ini_set('display_errors', 0);
session_start();
if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}
// Redirect if not logged in
if ($_SESSION['login'] == false) {
    header("Location: login_page.php");
    exit();
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate file format
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $uploadedFile = $_FILES['photo']['name'];
    $fileExtension = strtolower(pathinfo($uploadedFile, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        die("Invalid file format. Allowed formats are JPG, JPEG, PNG, and GIF.");
    }

    // Other input validations
    $nim = $_POST['nim'];
    $majority = $_POST['majority'];
    $yearofgraduation = $_POST['yearofgraduation'];
    $gender = $_POST['gender'];

    // Add more validation logic as needed...

    // If all validations pass, proceed with processing the form data
    // ...

    // For demonstration purposes, you can redirect back to the form after processing
    header("Location: upload.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Form</title>
    <link rel="stylesheet" href="./assets/upload.css" />
</head>

<?php
    if(isset($_SESSION['success_message'])){
        echo "<p>".$_SESSION['success_message']."</p>";
        unset($_SESSION['success_message']);
    }

?>

<body>
    <h2>Data Upload Form</h2>

    <form action="./controller/uploadController.php" method="post" enctype="multipart/form-data">
        <label for="photo">Photo:</label>
        <input id="photo" name="photo" type="file" accept="image/*" required><br>

        <label for="nim">NIM:</label>
        <input id="nim" type="text" name="nim" required><br>

        <label for="majority">Majority:</label>
        <input id="majority" type="text" name="majority" required><br>

        <label for="yearofgraduation">Year of Graduation:</label>
        <input id="yearofgraduation" type="text" name="yearofgraduation" required><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input name="upload" type="submit" value="Upload">
    </form>

    <br>

    <form action="./Home.php">
        <button type="submit">Back to Home</button>
    </form>
</body>

</html>
