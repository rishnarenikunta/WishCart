<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$profile_picture = "default.jpg";

$conn = new mysqli('localhost', 'root', '', 'WishCart');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare insert query
$sql = "INSERT INTO User (Username, Password, Email, Profile_picture) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $password, $email, $profile_picture);

try {
    $stmt->execute();

    // Success - Set session and redirect
    $stmt->close();

    $sql = "SELECT User_ID FROM User WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $_SESSION['user_ID'] = $row['User_ID'];

    header("Location: ./index.html");
    exit();

} catch (mysqli_sql_exception $e) {
    // Duplicate error handling
    if (strpos($e->getMessage(), 'Username') !== false) {
        header("Location: signup.html?error=username");
    } elseif (strpos($e->getMessage(), 'Email') !== false) {
        header("Location: signup.html?error=email");
    } else {
        header("Location: signup.html?error=general");
    }
    exit();
}

$conn->close();
?>
