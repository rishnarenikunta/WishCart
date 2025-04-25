<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();

    $_SESSION['User_ID'] = 1;  // Simulating login session
    $userId = $_SESSION['User_ID'];

    $servername = "localhost";
    $usernameDB = "root";
    $passwordDB = "";
    $dbname = "WishCart";

    $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "UPDATE Users SET Username = ?, Email = ?, Password = ? WHERE User_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $password, $userId);

        if ($stmt->execute()) {
            echo "Account updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $conn->close();
?>
