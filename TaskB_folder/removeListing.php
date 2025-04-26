<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();

    if (!isset($_SESSION['user_ID'])) {
        $_SESSION['user_ID'] = 1; 
    }

    $userId = $_SESSION['user_ID'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "WishCart";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['listing_id'])) {
        $listingId = $_POST['listing_id'];

        $sql = "DELETE FROM Listing WHERE Listing_ID = ? AND User_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $listingId, $userId);

        if ($stmt->execute()) {
            // Redirect to manageListings.php after successful deletion
            header("Location: manageListings.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $conn->close();
?>