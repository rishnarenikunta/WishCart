SELECT
    IFNULL(User_ID, '/N'),
    IFNULL(Profile_picture, '/N'),
    IFNULL(Username, '/N'),
    IFNULL(Password, '/N'),
    IFNULL(Balance, '/N'),
    IFNULL(Email, '/N')
INTO OUTFILE  './dat/dl_user.dat'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
FROM User;

SELECT
    IFNULL(User_ID, '/N'),
    IFNULL(Listing_ID, '/N'),
    IFNULL(Price, '/N'),
    IFNULL(Product_picture, '/N'),
    IFNULL(Listing_Name, '/N'),
    IFNULL(Listing_Description, '/N'),
    IFNULL(onSale, '/N'),
    IFNULL(isClosed, '/N')  
INTO OUTFILE  './dat/dl_listing.dat'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
FROM Listing;

SELECT
    IFNULL(User_ID, '/N'),
    IFNULL(Listing_ID, '/N')
INTO OUTFILE  './dat/dl_wishlist_items.dat'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
FROM Wishlist_Items;

SELECT
    IFNULL(Order_ID, '/N'),
    IFNULL(User_ID, '/N'),
    IFNULL(isClosed, '/N') 
INTO OUTFILE  './dat/dl_orders.dat'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
FROM Orders;

SELECT
    IFNULL(Order_ID, '/N'),
    IFNULL(Listing_ID, '/N'),
    IFNULL(Quantity, '/N') 
INTO OUTFILE  './dat/dl_order_items.dat'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
FROM Order_Items;