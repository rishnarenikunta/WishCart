<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = $_POST['username'];
$password = $_POST['password'];

$conn = new mysqli("localhost", "root", "", "WishCart");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT User_ID, Username FROM User 
        WHERE Username = '$username' AND Password = '$password'";

$result = $conn->query($sql);

if ($result) {
    if ($row = $result->fetch_assoc()) {
        $_SESSION['user_ID'] = $row['User_ID'];
        header("Location: shopping.php");
        exit();
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href='indexA.html';</script>";
        exit();
    }
} else {
    echo "Query Error: " . $conn->error;
}

$conn->close();
?>
