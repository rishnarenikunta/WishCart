<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();

    // Get the user ID from the session
    if (!isset($_SESSION['user_ID'])) {
        // If no user ID is set, redirect to login page
        header("Location: index.html");
        exit();
    }
    
    $userId = $_SESSION['user_ID']; // Note: 'user_ID' matches the case used in getlogin.php and signupsend.php
    $listingName = $_POST['listing_name'];
    $description = $_POST['listing_description'];
    $price = $_POST['price'];
    $product_picture = "bike.jpg";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "WishCart";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Verify that the user exists before adding the listing
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
    
    // Now insert the listing
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