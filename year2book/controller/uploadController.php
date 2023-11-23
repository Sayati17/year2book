<?php
session_start();
require "../connection/conn.php";

function doUpload($photo, $nim, $majority, $yearofgraduation, $gender, $id)
{
    global $conn;

    // Check if the user has an existing record
    $check_query = "SELECT id FROM users WHERE id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $id);
    $check_stmt->execute();
    $existing_record = $check_stmt->get_result()->fetch_assoc();

    if ($existing_record) {
        // Update the existing record
        $update_query = "UPDATE users SET photo=?, nim=?, majority=?, yearofgraduation=?, gender=? WHERE id=?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssssi", $photo, $nim, $majority, $yearofgraduation, $gender, $id);
        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            return 1; // Update success
        } else {
            return 0; // Update failed
        }
    }

    return 0; // No existing record found
}

function uploadFile($photo, $target_directory)
{
    $new_photo_name = uniqid() . "_" . $photo['name'];

    if (move_uploaded_file($photo['tmp_name'], $target_directory . $new_photo_name)) {
        return $new_photo_name;
    }

    return false; // File upload failed
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['upload'])) {
        $photo = $_FILES['photo'];
        $nim = $_POST['nim'];
        $majority = $_POST['majority'];
        $yearofgraduation = $_POST['yearofgraduation'];
        $gender = $_POST['gender'];
        $id = $_SESSION['id'];

        $target_directory = "../storage/";

        // Check file size
        if ($photo['size'] > 20 * 1000 * 1000) {
            echo "File is too big!";
            $_SESSION['error_message'] = "File is too big!";
            exit(); // Terminate execution
        }

        // Perform file upload
        $uploaded_file = uploadFile($photo, $target_directory);

        if ($uploaded_file) {
            // Perform database update
            $upload = doUpload($uploaded_file, $nim, $majority, $yearofgraduation, $gender, $id);

            if ($upload) {
                header("Location: ../upload.php");
                echo "Upload Success!";
            } else {
                echo "Upload Failed.";
            }
        } else {
            echo "Upload Failed.";
        }
    }
}
