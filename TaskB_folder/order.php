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

    <?php
        session_start();
        $userId = $_SESSION['user_ID'];

        $conn = new mysqli("localhost", "root", "", "WishCart");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT Order_ID FROM Orders WHERE User_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result(); //Get each row and store it 
        $total = 0;
    ?>
    
    <?php
        $stmt->close(); 
        while ($row = $result->fetch_assoc()):
            $currOrderId = $row['Order_ID'];
    ?>
    <div class="orderContainer">
        <div class="orderHeader">
            <h3>Current Order</h3>
            <span>Item</span>
            <span>Amount</span>
            <span>Price</span>
        </div>

        <?php
        $sql = "SELECT Listing_ID, Quantity FROM Order_Items WHERE Order_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $currOrderId);
        $stmt->execute();
        $result2 = $stmt->get_result(); 

        while ($row2 = $result2->fetch_assoc()):
            $currListingId = $row2['Listing_ID'];
            $currQuantity = $row2['Quantity'];

            $stmt->close(); 

            $sql = "SELECT Listing_Name, Price FROM Listing WHERE Listing_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $currListingId);
            $stmt->execute();
            $result3 = $stmt->get_result(); 
            $itemSpecRow = $result3->fetch_assoc();

            $item_name = $itemSpecRow['Listing_Name'];
            $item_price = $itemSpecRow['Price'];

        ?>
            <div class="orderItemRow">
                <span><?= htmlspecialchars($item_name) ?></span>
                <span><?= $currQuantity ?></span>
                <?php  $total = $total + $item_price * $currQuantity ?>
                <span><?= number_format($item_price * $currQuantity, 2) ?></span>
                <form action="removeFromOrder.php" method="POST" style="display: inline;">
                    <input type="hidden" name="listing_ID" value="<?= htmlspecialchars($currListingId) ?>">
                    <input type="hidden" name="order_ID" value="<?= htmlspecialchars($currOrderId) ?>">
                    <button class="removeBtn" aria-label="Remove Item">×</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
        <hr class="divider" />
        <div class="orderTotalRow">
            <span>Total</span>
            <span><?= number_format($total, 2) ?></span>
            <form action="checkout.php" method="POST" style="display: inline;">
                <input type="hidden" name="order_ID" value="<?= htmlspecialchars($currOrderId) ?>">
                <button class="checkoutBtn">Checkout</button>
            </form>
        </div>
    <?php endwhile; ?>
        <hr class="divider" />
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("profileDropdown");
            dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
        }
    </script>

</body>

</html>