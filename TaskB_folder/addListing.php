<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_ID'])) {
    header("Location: index.html");
    exit();
}

$userId = $_SESSION['user_ID'];
$listingName = $_POST['listing_name'] ?? '';
$description = $_POST['listing_description'] ?? '';
$price = $_POST['price'] ?? 0;
$product_picture = "default.jpg"; // Default image

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WishCart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file upload
$uploadDirectory = "Uploads/";
$allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
$maxFileSize = 5 * 1024 * 1024; // 5MB

if (isset($_FILES["product_picture"]) && $_FILES["product_picture"]["error"] == 0) {
    $filename = basename($_FILES["product_picture"]["name"]);
    $targetFilePath = $uploadDirectory . $filename;
    $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Validate file type and size
    if (in_array($fileType, $allowedTypes) && $_FILES["product_picture"]["size"] <= $maxFileSize) {
        if (move_uploaded_file($_FILES["product_picture"]["tmp_name"], $targetFilePath)) {
            $product_picture = $filename; // Store only the filename in the database
        } else {
            echo "Error: Failed to upload the file.";
            exit();
        }
    } else {
        echo "Error: Invalid file type or size. Only JPG, JPEG, PNG, GIF files under 5MB are allowed.";
        exit();
    }
}

// Verify that the user exists
$checkUser = "SELECT User_ID FROM User WHERE User_ID = ?";
$checkStmt = $conn->prepare($checkUser);
$checkStmt->bind_param("i", $userId);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows == 0) {
    echo "Error: User does not exist. Please log in again.";
    $checkStmt->close();
    $conn->close();
    exit();
}

$checkStmt->close();

// Insert the listing
$sql = "INSERT INTO Listing (User_ID, Price, Product_picture, Listing_Name, Listing_Description, onSale, isClosed) 
        VALUES (?, ?, ?, ?, ?, 1, 0)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("idsss", $userId, $price, $product_picture, $listingName, $description);

if ($stmt->execute()) {
    header("Location: manageListings.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>