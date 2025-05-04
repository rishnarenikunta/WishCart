<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_ID'])) {
    die("Error: User not logged in.");
}

$userId = $_SESSION['user_ID'];

$conn = new mysqli("localhost", "root", "", "WishCart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "UPDATE User SET Username = ?, Email = ?, Password = ? WHERE User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $password, $userId);

    try {
        if ($stmt->execute()) {
            header("Location: shopping.php");
            exit();
        } else {
            throw new Exception($stmt->error);
        }
    } catch (mysqli_sql_exception $e) {
        if (str_contains($e->getMessage(), "Duplicate entry")) {
            if (str_contains($e->getMessage(), "Email")) {
                header("Location: account.html?error=email");
            } elseif (str_contains($e->getMessage(), "Username")) {
                header("Location: account.html?error=username");
            } else {
                header("Location: account.html?error=duplicate");
            }
            exit();
            }  
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
