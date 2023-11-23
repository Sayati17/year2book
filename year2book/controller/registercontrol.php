<?php
    session_start();
    require "../connection/conn.php";

    function doregister($username, $email, $password){
        global $conn;
        $query = "INSERT INTO users (username, email, password) VALUES (?,?,?);";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss",$username,$email,$password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($stmt->affected_rows > 0){
            return 1;
        }else{
            return 0;
        }
    }

    function checkdup($email){
        global $conn;
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            return 1;
        }else{
            return 0;
        }
    }

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $_SESSION["error_message"] = "Invalid Email Format";
                header("Location: ../register.php");
                exit();
            }

            if(strlen($password) < 8){
                $_SESSION["error_message"] = "Password Must be at least 8 Characters";
                header("Location: ../register.php");
                exit();
            }

            $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
            $checkdup = checkdup($email);
            if($checkdup){
                $_SESSION["error_message"] = "Email Already Exist";
                header("Location: ../register.php");
                exit();
            }else{
                $register = doregister($username, $email, $hashed_pass);
                if($register){
                    header("Location: ../login_page.php");
                }else{
                    header("Location: ../register.php");
                }
            }
        }
    }
?>