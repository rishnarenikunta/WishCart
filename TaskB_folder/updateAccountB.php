<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    $servername = "localhost";
    $usernameDB = "root";
    $passwordDB = "";
    $dbname = "WishCart";

    $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $p_username = $_POST['p_username'];
        $p_password = $_POST['p_password'];

        $n_username = $_POST['n_username'];
        $n_password = $_POST['n_password'];
        $sql = "UPDATE User SET Username = ?, `Password` = ? WHERE Username =? AND `Password` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $n_username, $n_password, $p_username, $p_password);

        if ($stmt->execute()) {
             header("Location: shopping.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $conn->close();
?>