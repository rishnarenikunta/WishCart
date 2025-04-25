<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();

    $_SESSION['User_ID'] = 1;
    $userId = $_SESSION['User_ID'];

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
            echo "Listing removed successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $conn->close();
?>
