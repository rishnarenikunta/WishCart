<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Browsing</title>
    <link rel="stylesheet" href="styles.css" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body>
    <nav class="navbar">
        <div class="navLinks">
            <a href="./shopping.php">Catalog</a>
            <a href="./wishlist.html">Wishlist</a>
            <a href="./manageListings.html">Manage Listings</a>
            <a href="./order.html">Order</a>
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

    <div class="productGrid">
        <?php

        $conn = new mysqli('localhost', 'root', '', 'WishCart');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM Listing WHERE onSale = 1 AND isClosed = 0";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="productCard">';
                echo    '<a href="./product.html">';
                echo        '<img src="' . htmlspecialchars($row['Product_picture']) . '" alt="' . htmlspecialchars($row['Listing_Name']) . '" />';
                echo    '</a>';
                echo    '<div class="productInfo">';
                echo        '<span>' . htmlspecialchars($row['Listing_Name']) . '</span>';
                echo        '<span>$' . htmlspecialchars(number_format($row['Price'], 2)) . '</span>';
                echo    '</div>';
                echo    '<div class="productButtons">';
                echo        '<button class="cartButton">Add to Cart</button>';
                echo        '<button class="wishlistButton">Add to Wishlist</button>';
                echo    '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No products available.</p>";
        }

        $conn->close();
        ?>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("profileDropdown");
            dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
        }
    </script>
</body>
</html>
