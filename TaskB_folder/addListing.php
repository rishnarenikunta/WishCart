<?php

    session_start();

    $userId = $_SESSION['User_Id'];   
     
    $name = $_POST['name']
    $description = $_POST['description']
    $price = $_POST['price']
    $picture = $_POST['picture']

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "WishCart";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    

    $sql = ""
?>