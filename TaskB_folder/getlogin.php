<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "WishCart";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $user = $_POST['username'];
    $pass = $_POST['password'];


    $sql = "SELECT * FROM User WHERE Username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0 ) {
        $row = $result->fetch_assoc();
        if(password_verify($pass, $row['Password']))
        {
            $stmt->close(); 

            $sql = "SELECT User_ID from User where Username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user);

            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        
            $_SESSION['user_ID'] = $row['User_ID']; 
            
            if (!isset($_SESSION['user_ID'])) {
                die("Error: User not logged in.");
            }
        
            header("Location: account.html");
            exit();
        }
        else   
            echo "Error: Wrong Password";
    } else {
        
        echo "Error: " . $sql . "<br>" . $result->error;
    }

    $stmt->close();
    $conn->close();

?>