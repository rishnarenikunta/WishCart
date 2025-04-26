<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

echo "<pre>";
print_r($_POST);
echo "</pre>";

if (!isset($_SESSION['user_ID'])) {
    $_SESSION['user_ID'] = 1;
}

$orderId = $_POST['order_ID'];
$listingId = $_POST['listing_ID'];

$conn = new mysqli('localhost', 'root', '', 'WishCart');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM Order_Items WHERE Order_ID = ? AND Listing_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $orderId, $listingId);

if ($stmt->execute()) {
    header("Location: order.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>