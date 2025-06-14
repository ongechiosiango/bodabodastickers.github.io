<?php
session_start();
include('db-conn.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['sticker_image'])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["sticker_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["sticker_image"]["tmp_name"]);
    if ($check === false) {
        $error = "File is not an image.";
    } elseif ($_FILES["sticker_image"]["size"] > 500000) {
        $error = "Sorry, your file is too large.";
    } elseif (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    } elseif (move_uploaded_file($_FILES["sticker_image"]["tmp_name"], $target_file)) {
        // Save the file path in database or use it directly
        $success = "The file " . htmlspecialchars(basename($_FILES["sticker_image"]["name"])) . " has been uploaded.";
    } else {
        $error = "Sorry, there was an error uploading your file.";
    }
}

header("Location: admin-dashboard.php");
exit();
?>