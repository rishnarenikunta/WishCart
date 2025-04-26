<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order</title>
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

    <?php
        session_start();
        $userId = $_SESSION['user_ID'];

        $conn = new mysqli("localhost", "root", "", "WishCart");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Example table structure: Orders(user_id, item_name, quantity, price)
        $sql = "SELECT item_name, quantity, price FROM Orders WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>
    
    <div class="orderDetailContainer">
        <button onclick="window.history.back()" class="closeButton">×</button>
        <h1 class="orderTitle">Order</h1>
        <div class="orderTable">
            <div class="orderHeader">
                <span>Item</span>
                <span>Amount</span>
                <span>Price</span>
            </div>
    
            <?php
            $total = 0;
            while ($row = $result->fetch_assoc()):
                $total += $row['Price'] * $row['Quantity'];
            ?>
            <div class="orderItemRow">
                <span><?= htmlspecialchars($row['Item_Name']) ?></span>
                <span><?= $row['Quantity'] ?></span>
                <span><?= number_format($row['Price'], 2) ?></span>
                <button class="removeBtn" aria-label="Remove Item">×</button>
            </div>
            <?php endwhile; ?>
        </div>
    
        <hr class="divider" />
    
        <div class="orderTotalRow">
            <span>Total</span>
            <span><?= number_format($total, 2) ?></span>
            <button class="checkoutBtn">Checkout</button>
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