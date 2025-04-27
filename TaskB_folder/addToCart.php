<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$userId = $_SESSION['user_ID'];
$listingId = $_POST['listing_id'];

$conn = new mysqli('localhost', 'root', '', 'WishCart');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Order_ID FROM Orders WHERE User_ID = ? AND isClosed = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $orderId = $row['Order_ID'];
} else {

    $insertOrder = "INSERT INTO Orders (User_ID) VALUES (?)";
    $stmt2 = $conn->prepare($insertOrder);
    $stmt2->bind_param("i", $userId);
    $stmt2->execute();
    $orderId = $stmt2->insert_id;
    $stmt2->close();
}

$checkItem = "SELECT Quantity FROM Order_Items WHERE Order_ID = ? AND Listing_ID = ?";
$stmt3 = $conn->prepare($checkItem);
$stmt3->bind_param("ii", $orderId, $listingId);
$stmt3->execute();
$result2 = $stmt3->get_result();

if ($result2->num_rows > 0) {
    $updateItem = "UPDATE Order_Items SET Quantity = Quantity + 1 WHERE Order_ID = ? AND Listing_ID = ?";
    $stmt4 = $conn->prepare($updateItem);
    $stmt4->bind_param("ii", $orderId, $listingId);
    $stmt4->execute();
    $stmt4->close();
} else {
    $insertItem = "INSERT INTO Order_Items (Order_ID, Listing_ID, Quantity) VALUES (?, ?, 1)";
    $stmt5 = $conn->prepare($insertItem);
    $stmt5->bind_param("ii", $orderId, $listingId);
    $stmt5->execute();
    $stmt5->close();
}

$stmt3->close();
$conn->close();

header("Location: shopping.php");
exit();
?>
