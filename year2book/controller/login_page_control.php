<?php
    session_start();
    require "../connection/conn.php";

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
            die("Invalid CSRF Token");
        }
        if(!isset ($_SESSION['attempt'])){
            $_SESSION['attempt']=0;
        }
        if($_SESSION['attempt']==5){
            $_SESSION['error']='Attempt Limit Reached';
        }
        else{

       
        $username = $_POST['username'];
        $username = htmlspecialchars($username);
        $password = $_POST['password'];
        $password = htmlspecialchars($password);

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
                unset($_SESSION['attempt']);
                header("Location: ../Home.php");
                exit(); // Important to stop further execution after redirection
            } else {
                // Password is incorrect
                $_SESSION["error_message"] = "Password Incorrect";
                $_SESSION['attempt']+=1;
                if($_SESSION['attempt']==5){
                    $_SESSION['attempt_again']=time()+(2 * 60);
                }
                header("Location: ../login_page.php");
                exit(); // Important to stop further execution after redirection
            }
        } else {
            // User not found
            $_SESSION["error_message"] = "User Not Found!";
            $_SESSION['login'] = false;
            header("Location: ../login_page.php");
            exit(); // Important to stop further execution after redirection
        }
    }

        // $maxAttempts=3;
        // $lockoutDuration=60;


        // if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $maxAttempts && (time() - $_SESSION['last_login_attempt_time']) < $lockoutDuration){
        //     $_SESSION["error_message"]
        // }

        // $statement->close();
        // $conn->close();
    }
?>