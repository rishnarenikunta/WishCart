<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();

    $_SESSION['User_ID'] = 1;
    $userId = $_SESSION['User_ID'];
    $listingName = $_POST['listing_name'];
    $description = $_POST['listing_description'];
    $price = $_POST['price'];
    $product_picture = "default.jpg";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "WishCart";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "INSERT INTO Listing (User_ID, Price, Product_picture, Listing_Name, Listing_Description) 
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idsss", $userId, $price, $product_picture, $listingName, $description);

    if ($stmt->execute()) {
        echo "Listing added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();

    ?>