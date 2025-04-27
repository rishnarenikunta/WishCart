<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WishCart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['username'];
$pass = $_POST['password'];

// Prepare statement to find the user
$sql = "SELECT * FROM User WHERE Username = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify password
    if (password_verify($pass, $row['Password'])) {
        $_SESSION['user_ID'] = $row['User_ID'];

        if (!isset($_SESSION['user_ID'])) {
            die("Error: Session failed.");
        }

        header("Location: shopping.php");
        exit();
    } else {
        echo "<script>alert('Invalid password. Please try again.'); window.location.href='index.html';</script>";
    }
} else {
    echo "<script>alert('Invalid username. Please try again.'); window.location.href='index.html';</script>";
}

// Clean up
$stmt->close();
$conn->close();
?>
