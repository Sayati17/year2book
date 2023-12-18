<?php
    session_start();
    require "../connection/conn.php";

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $username = $_POST['username'];
        $password = $_POST['password'];

        // with prepared statement
        $query = "SELECT * FROM users WHERE username=?;";
        $statement = $conn->prepare($query);
        $statement->bind_param("s", $username);

        // with prepared statement
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Verify the entered password against the stored hashed password
            if (password_verify($password, $row['password'])) {
                // Password is correct

                $_SESSION["success_message"] = "Login Success";
                $_SESSION["id"] = $row['id'];
                $_SESSION['login'] = true;
                $_SESSION['username'] = $row['username'];

                header("Location: ../Home.php");
                exit(); // Important to stop further execution after redirection
            } else {
                // Password is incorrect
                $_SESSION["error_message"] = "Login Failed";
                header("Location: ../login_page.php");
                exit(); // Important to stop further execution after redirection
            }
        } else {
            // User not found
            $_SESSION["error_message"] = "Login Failed";
            $_SESSION['login'] = false;
            header("Location: ../login_page.php");
            exit(); // Important to stop further execution after redirection
        }

        // $statement->close();
        // $conn->close();
    }
?>
