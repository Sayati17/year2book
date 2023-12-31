<?php
    session_start();
    require "../connection/conn.php";

    function generateRandomID(){
        return 'user_' . time() . '_' . rand(1000,9999);
    }

    function doregister($id,$username, $email, $password){
        global $conn;
        $query = "INSERT INTO users (id,username, email, password) VALUES (?,?,?,?);";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss",$id,$username,$email,$password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($stmt->affected_rows > 0){
            return 1;
        }else{
            return 0;
        }
    }

    function checkdup($username){
        global $conn;
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            return 1;
        }else{
            return 0;
        }
    }

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
            die("Invalid CSRF Token");
        }
        if(isset($_POST['submit'])){
            $id = generateRandomID();
            $username = $_POST['username'];
            $username = htmlspecialchars($username);
            $email = $_POST['email'];
            $email = htmlspecialchars($email);
            $password = $_POST['password'];
            $password = htmlspecialchars($password);

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
            $checkdup = checkdup($username);
            if($checkdup){
                $_SESSION["error_message"] = "User Already Exist";
                header("Location: ../register.php");
                exit();
            }else{
                $register = doregister($id,$username, $email, $hashed_pass);
                if($register){
                    header("Location: ../login_page.php");
                }else{
                    header("Location: ../register.php");
                }
            }
        }
    }
?>
