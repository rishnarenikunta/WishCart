<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();

    if (!isset($_SESSION['user_ID'])) {
        die("Error: User not logged in.");
    }

    $userId = $_SESSION['user_ID'];

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

        $sql = "UPDATE User SET Username = ?, Email = ?, Password = ? WHERE User_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $password, $userId);

        if ($stmt->execute()) {
             header("Location: shopping.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $conn->close();
?>
