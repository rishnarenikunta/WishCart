<?php


// Start session
session_start();

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


$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
   
    $_SESSION['username'] = $user;
    header("Location: account.php");
    exit();
} else {
    
    echo "<script>alert('Invalid username or password'); window.location.href='index.html';</script>";
}

$stmt->close();
$conn->close();
?>