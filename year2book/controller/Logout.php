<?php
    session_start();
    if(isset($_SESSION['login']) == true){
        $_SESSION['login'] = false;
        session_destroy();
        header("Location: ../login_page.php");
    }
?>