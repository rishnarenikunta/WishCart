<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $profile_picture = "default.jpg";
    $conn = new mysqli('localhost', 'root', '', 'WishCart');


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO User (Username, Password, Email, Profile_picture) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $profile_picture);

    if ($stmt->execute()) {
        header("Location: ./account.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
?>
