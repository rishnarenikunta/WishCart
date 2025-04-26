<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_ID'])) {
    $_SESSION['user_ID'] = 1;
}

$userId = $_SESSION['user_ID'];
$listingId = $_POST['listing_id'];

$conn = new mysqli('localhost', 'root', '', 'WishCart');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT IGNORE INTO Wishlist_Items (User_ID, Listing_ID) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $listingId);

if ($stmt->execute()) {
    header("Location: wishlist.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>