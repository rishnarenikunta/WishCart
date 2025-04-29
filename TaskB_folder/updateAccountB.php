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
        $sql = "UPDATE User SET Username = '$n_username', `Password` = '$n_password' WHERE Username = '$p_username' AND `Password` = '$p_password'";

        $stmt = $conn->query($sql);

        if ($stmt) {
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