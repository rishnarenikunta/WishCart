<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Listings</title>
    <link rel="stylesheet" href="styles.css" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body>
    <nav class="navbar">
        <div class="navLinks">
            <a href="./shopping.php">Catalog</a>
            <a href="./wishlist.php">Wishlist</a>
            <a href="./manageListings.php">Manage Listings</a>
            <a href="./order.php">Order</a>
        </div>
        <div class="profileDropdownWrapper">
            <span class="material-symbols-outlined profileIcon" onclick="toggleDropdown()">
                account_circle
            </span>
            <div id="profileDropdown" class="dropdownMenu">
                <a href="./account.html">View Account</a>
                <a href="./index.html">Log Out</a>
            </div>
        </div>
    </nav>
    <div class="mngListingsDetailContainer">
        <button onclick="window.history.back()" class="closeButton">×</button>
        <h1 class="mngListingsTitle">Manage Listings</h1>
        <div class="mngListingsGrid">
            <?php
                // Start session to get user ID
                session_start();
    
                // For testing, use user ID 1 if not set
                if (!isset($_SESSION['user_ID'])) {
                    $_SESSION['user_ID'] = 1;
                }
    
                $userId = $_SESSION['user_ID']; // Changed from User_ID to user_ID to match getlogin.php and signupsend.php
    
                // Connect to database
                $conn = new mysqli('localhost', 'root', '', 'WishCart');
    
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
    
                // Get all listings for the current user
                $sql = "SELECT * FROM Listing WHERE User_ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
    
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $imagePath = "Uploads/" . $row['Product_picture'];
                        $imageSrc = file_exists($imagePath)
                        ? "/wishcart/Uploads/" . htmlspecialchars($row['Product_picture'])
                        : "/wishcart/Uploads/default.jpg";
                        echo '<div class="mngListingsCard">';
                        echo '<img src="' . $imageSrc . '" alt="' . htmlspecialchars($row['Listing_Name']) . '" onerror="this.src=\'/wishcart/Uploads/default.jpg\'" />';
                        echo '<div class="mngListingsInfo">';
                        echo '<span>' . htmlspecialchars($row['Listing_Name']) . '</span>';
                        echo '<span>$' . htmlspecialchars(number_format($row['Price'], 2)) . '</span>';
                        echo '</div>';
                        echo '<div class="mngListingsButtons">';
                        echo '<form action="removeListing.php" method="POST">';
                        echo '<input type="hidden" name="listing_id" value="' . htmlspecialchars($row['Listing_ID']) . '" />';
                        echo '<button type="submit" class="removeListingButton">Remove Listing</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>You have no listings yet.</p>';
                }
                
                $stmt->close();
                $conn->close();
            ?>
        </div>
        <div class="addListingsButton">
            <a href="./addListing.html">
                <button class="addListButton">Add Listings</button>
            </a>
        </div>
    </div>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("profileDropdown");
            dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
        }
    </script>
</body>

</html>