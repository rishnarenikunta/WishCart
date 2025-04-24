USE WishCart;

-- Ensure user table exists, prevents future errors
CREATE TABLE IF NOT EXISTS User (
    User_ID INT NOT NULL AUTO_INCREMENT,
    Profile_picture VARCHAR(255) NOT NULL,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Balance FLOAT NOT NULL DEFAULT 0.0,
    Email VARCHAR(100) NOT NULL UNIQUE,
    CONSTRAINT userPK PRIMARY KEY (User_ID)
); 

-- Staging table for User to paste our data into
CREATE TEMPORARY TABLE Stage_User LIKE User;

-- Load file
LOAD DATA LOCAL INFILE './dat/ul_user.dat'
INTO TABLE Stage_User
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(User_ID, Profile_picture, Username, Password, Balance, Email);

--Insert into our real user table
INSERT INTO User (User_ID, Profile_picture, Username, Password, Balance, Email)
SELECT User_ID, Profile_picture, Username, Password, Balance, Email
FROM Stage_User AS new
ON DUPLICATE KEY UPDATE
  Profile_picture = new.Profile_picture,
  Username = new.Username, 
  Password = new.Password, 
  Balance = new.Balance, 
  Email = new.Email;

-- Listing
CREATE TABLE IF NOT EXISTS Listing (
    Listing_ID INT NOT NULL AUTO_INCREMENT,
    User_ID INT NOT NULL,
    Price FLOAT NOT NULL,
    Product_picture VARCHAR(255) NOT NULL,
    Listing_Name VARCHAR(100) NOT NULL,
    Listing_Description TEXT NOT NULL,
    onSale BOOLEAN NOT NULL DEFAULT TRUE,
    isClosed BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT listingPK PRIMARY KEY (Listing_ID),
    CONSTRAINT listingFK FOREIGN KEY (User_ID) REFERENCES User(User_ID) ON DELETE CASCADE
);

CREATE TEMPORARY TABLE Stage_Listing LIKE Listing;

LOAD DATA LOCAL INFILE './dat/ul_listing.dat'
INTO TABLE Stage_Listing
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(Listing_ID, User_ID, Price, Product_picture, Listing_Name, Listing_Description, onSale, isClosed);

INSERT INTO Listing (Listing_ID, User_ID, Price, Product_picture, Listing_Name, Listing_Description, onSale, isClosed)
SELECT Listing_ID, User_ID, Price, Product_picture, Listing_Name, Listing_Description, onSale, isClosed
FROM Stage_Listing AS new
ON DUPLICATE KEY UPDATE
  Price = new.Price, 
  Product_picture = new.Product_picture, 
  Listing_Name = new.Listing_Name, 
  Listing_Description = new.Listing_Description, 
  onSale = new.onSale, 
  isClosed = new.isClosed;

-- Wishlist Items 
CREATE TABLE IF NOT EXISTS Wishlist_Items (
    User_ID INT NOT NULL,
    Listing_ID INT NOT NULL,
    CONSTRAINT wishlistPK PRIMARY KEY (User_ID, Listing_ID),
    CONSTRAINT wishlistFK_User FOREIGN KEY (User_ID) REFERENCES User(User_ID) ON DELETE CASCADE,
    CONSTRAINT wishlistFK_Listing FOREIGN KEY (Listing_ID) REFERENCES Listing(Listing_ID) ON DELETE CASCADE
);

CREATE TEMPORARY TABLE Stage_Wishlist_Items LIKE Wishlist_Items;

LOAD DATA LOCAL INFILE './dat/ul_wishlist_items.dat'
INTO TABLE Stage_Wishlist_Items
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(User_ID, Listing_ID);

INSERT IGNORE INTO Wishlist_Items (User_ID, Listing_ID)
SELECT User_ID, Listing_ID
FROM Stage_Wishlist_Items;

-- Orders 
CREATE TABLE IF NOT EXISTS Orders (
    Order_ID INT NOT NULL AUTO_INCREMENT,
    User_ID INT NOT NULL,
    isClosed BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT orderPK PRIMARY KEY (Order_ID),
    CONSTRAINT orderFK_User FOREIGN KEY (User_ID) REFERENCES User(User_ID) ON DELETE CASCADE
);

CREATE TEMPORARY TABLE Stage_Orders LIKE Orders;

LOAD DATA LOCAL INFILE './dat/ul_orders.dat'
INTO TABLE Stage_Orders
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(Order_ID, User_ID, isClosed);

INSERT INTO Orders (Order_ID, User_ID, isClosed)
SELECT Order_ID, User_ID, isClosed
FROM Stage_Orders AS new
ON DUPLICATE KEY UPDATE
  isClosed = new.isClosed;

-- Order Items
CREATE TABLE IF NOT EXISTS Order_Items (
    Order_ID INT NOT NULL,
    Listing_ID INT NOT NULL,
    Quantity INT NOT NULL CHECK (Quantity > 0),
    CONSTRAINT orderItemsPK PRIMARY KEY (Order_ID, Listing_ID),
    CONSTRAINT orderItemsFK_Order FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID) ON DELETE CASCADE,
    CONSTRAINT orderItemsFK_Listing FOREIGN KEY (Listing_ID) REFERENCES Listing(Listing_ID) ON DELETE CASCADE
);

CREATE TEMPORARY TABLE Stage_Order_Items LIKE Order_Items;

LOAD DATA LOCAL INFILE './dat/ul_order_items.dat'
INTO TABLE Stage_Order_Items
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(Order_ID, Listing_ID, Quantity);

INSERT INTO Order_Items (Order_ID, Listing_ID, Quantity)
SELECT Order_ID, Listing_ID, Quantity
FROM Stage_Order_Items AS new
ON DUPLICATE KEY UPDATE
  Quantity = new.Quantity;
