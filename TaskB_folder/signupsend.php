<?php
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $conn = new mysqli('localhost', 'root', '', 'WishCart');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
?>