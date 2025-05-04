<?php
session_start();

if (!isset($_GET['id'])) {
    header("Location: shopping.php");
    exit();
}

$listingId = $_GET['id'];

$conn = new mysqli('localhost', 'root', '', 'WishCart');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT l.*, u.Username as SellerName 
        FROM Listing l
        JOIN User u ON l.User_ID = u.User_ID
        WHERE l.Listing_ID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $listingId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: shopping.php");
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();
$conn->close();

$imagePath = "Uploads/" . $product['Product_picture'];
$imageSrc = file_exists($imagePath)
    ? "/wishcart/Uploads/" . htmlspecialchars($product['Product_picture'])
    : "/wishcart/Uploads/default.jpg";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['Listing_Name']); ?></title>
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

    <div class="productDetailContainer">
        <button onclick="window.history.back()" class="closeButton">×</button>
        <h1 class="productTitle"><?php echo htmlspecialchars($product['Listing_Name']); ?></h1>
        <div class="productContent">
            <img src="<?php echo $imageSrc; ?>" 
                 alt="<?php echo htmlspecialchars($product['Listing_Name']); ?>" 
                 class="productImage"
                 onerror="this.src='/wishcart/Uploads/default.jpg'" />

            <div class="productText">
                <p class="description"><?php echo htmlspecialchars($product['Listing_Description']); ?></p>
                <p class="price">Price: $<?php echo htmlspecialchars(number_format($product['Price'], 2)); ?></p>
                <p class="seller">Seller: <?php echo htmlspecialchars($product['SellerName']); ?></p>

                <div class="specificProductButton">
                    <form action="addToCart.php" method="POST" style="display:inline;">
                        <input type="hidden" name="listing_id" value="<?php echo htmlspecialchars($product['Listing_ID']); ?>">
                        <button type="submit" class="cartButtonProduct">Add to Cart</button>
                    </form>
                    <form action="addToWishlist.php" method="POST" style="display:inline;">
                        <input type="hidden" name="listing_id" value="<?php echo htmlspecialchars($product['Listing_ID']); ?>">
                        <button type="submit" class="wishlistButtonProduct">Add to Wishlist</button>
                    </form>
                </div>
            </div>
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
