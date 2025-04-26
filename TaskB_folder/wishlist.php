<?php
session_start();

if (!isset($_SESSION['user_ID'])) {
    $_SESSION['user_ID'] = 1; 
}

$userId = $_SESSION['user_ID'];

$conn = new mysqli('localhost', 'root', '', 'WishCart');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all wishlist items for the current user with product details
$sql = "SELECT l.*, w.User_ID 
        FROM Wishlist_Items w
        JOIN Listing l ON w.Listing_ID = l.Listing_ID
        WHERE w.User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Wishlist</title>
    <link rel="stylesheet" href="styles.css" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body>
    <nav class="navbar">
        <div class="navLinks">
            <a href="shopping.php">Catalog</a>
            <a href="wishlist.php">Wishlist</a>
            <a href="manageListings.php">Manage Listings</a>
            <a href="order.php">Order</a>
        </div>
        <div class="profileDropdownWrapper">
            <span class="material-symbols-outlined profileIcon" onclick="toggleDropdown()">
                account_circle
            </span>
            <div id="profileDropdown" class="dropdownMenu">
                <a href="account.html">View Account</a>
                <a href="index.html">Log Out</a>
            </div>
        </div>
    </nav>
    <div class="wishlistDetailContainer">
        <button onclick="window.history.back()" class="closeButton">×</button>
        <h1 class="wishlistTitle">Wishlist</h1>
        <div class="wishlistTable">
            <div class="wishlistHeader">
                <span>Item</span>
                <span>Price</span>
                <span>Status</span>
                <span></span>
                <span></span>
            </div>
            
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="wishlistItemRow">';
                    echo '<span>' . htmlspecialchars($row['Listing_Name']) . '</span>';
                    echo '<span>$' . htmlspecialchars(number_format($row['Price'], 2)) . '</span>';
                    echo '<span>' . ($row['onSale'] ? 'Available' : 'Unavailable') . '</span>';
                    
                    // Add to cart button
                    echo '<form action="addToCart.php" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="listing_id" value="' . htmlspecialchars($row['Listing_ID']) . '">';
                    echo '<button type="submit" class="addBtn" aria-label="Add Item">+</button>';
                    echo '</form>';
                    
                    // Remove from wishlist button
                    echo '<form action="removeFromWishlist.php" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="listing_id" value="' . htmlspecialchars($row['Listing_ID']) . '">';
                    echo '<button type="submit" class="removeBtn" aria-label="Remove Item">×</button>';
                    echo '</form>';
                    
                    echo '</div>';
                }
            } else {
                echo '<p>Your wishlist is empty.</p>';
            }
            
            $stmt->close();
            $conn->close();
            ?>
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