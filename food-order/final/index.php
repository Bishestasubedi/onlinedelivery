<?php
$host = "localhost"; 
$dbname = "foodOrder"; 
$username = "root"; 
$password = "root"; 

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>

<?php
// Start session
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to search for products
function searchProducts($keyword) {
    global $mysqli;
    $search_query = "SELECT * FROM products WHERE product_name LIKE ?";
    $stmt = $mysqli->prepare($search_query);
    $keyword = "%" . $keyword . "%";
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to fetch all products
function getAllProducts() {
    global $mysqli;
    $select_all_query = "SELECT * FROM products";
    $result = $mysqli->query($select_all_query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add an item to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $quantity = $_POST['quantity'];

    $item = [
        'product_id' => $product_id,
        'product_name' => $product_name,
        'product_price' => $product_price,
        'quantity' => $quantity,
    ];

    $_SESSION['cart'][] = $item;
}

// Remove an item from the cart
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    unset($_SESSION['cart'][$index]);
}

// Update the quantity of an item in the cart
if (isset($_POST['update_cart'])) {
    $index = $_POST['item_index'];
    $quantity = $_POST['quantity'];

    if ($quantity > 0) {
        $_SESSION['cart'][$index]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$index]);
    }
}

// Checkout and save cart data to the database
if (isset($_POST['checkout'])) {
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        
        // Insert data into the database
        $insert_query = "INSERT INTO cart (product_id, quantity) VALUES (?, ?)";
        $stmt = $mysqli->prepare($insert_query);
        $stmt->bind_param("ii", $product_id, $quantity);
        $stmt->execute();
    }

    // Clear the cart after checkout
    $_SESSION['cart'] = [];
}

// Fetch all products initially or after a search
if (isset($_GET['search_keyword']) && !empty($_GET['search_keyword'])) {
    $search_keyword = $_GET['search_keyword'];
    $productData = searchProducts($search_keyword);
} else {
    $productData = getAllProducts();
}

// Calculate the total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $item_total = $item['product_price'] * $item['quantity'];
    $total += $item_total;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONLINE FOOD DELIVERY </title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <h1>ONLINE FOOD DELIVERY</h1>
    <?php
        session_start();
        
        // Check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            // User is logged in, display a logout button
            echo '<a href="logout.php">Logout</a>';
        } else {
            // User is not logged in
            // Display a login link or other content
        }
    ?>

    <div class="container">
        <!-- Product search form -->
        <form method="get">
            <label for="search_keyword">Search Products:</label>
            <input type="text" name="search_keyword" id="search_keyword" placeholder="Enter keywords">
            <input type="submit" value="Search">
        </form>
        
        <!-- Product list -->
        <div class="product-container">
            <?php
            // Display products with images, names, prices, and add to cart buttons
            foreach ($productData as $product) {
                echo "<div class='product-item'>";
                if (!empty($product['product_image'])) {
                    echo "<img src='" . $product['product_image'] . "' alt='" . $product['product_name'] . "'>";
                }
                echo "<br>";
                echo $product['product_name'] . " - Rs " . $product['product_price'];
                echo "<form method='post'>";
                echo "<input type='hidden' name='product_id' value='" . $product['product_id'] . "'>";
                echo "<input type='hidden' name='product_name' value='" . $product['product_name'] . "'>";
                echo "<input type='hidden' name='product_price' value='" . $product['product_price'] . "'>";
                echo "<input type='number' name='quantity' value='1' min='1'>";
                echo "<input type='submit' name='add_to_cart' value='Add to Cart'>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>
    <!-- Cart -->
    <h2>Shopping Cart</h2>
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $index => $item) {
            $item_total = $item['product_price'] * $item['quantity'];
            $total += $item_total;
        ?>
            <tr>
                <td><?= $item['product_name'] ?></td>
                <td><?= $item['product_price'] ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="item_index" value="<?= $index ?>">
                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                        <input type="submit" name="update_cart" value="Update">
                    </form>
                </td>
                <td><?= $item_total ?></td>
                <td><a href="?remove=<?= $index ?>">Remove</a></td>
            </tr>
        <?php } ?>
    </table>

    <p>Total: <?= $total ?></p>

    <!-- Checkout button -->
    <form method="post">
        <input type="submit" name="checkout" value="Checkout">
    </form>
        <?php
        // Check if the checkout was completed
        if (isset($_POST['checkout'])) {
            // Perform the checkout and save to the database (your existing code here)

            // Display a confirmation message with animation
            echo '<div class="confirmation-message">Thank you for your purchase! Your order has been confirmed.</div>';
            
            // Clear the cart after checkout
            $_SESSION['cart'] = [];
        }
        ?>
</body>
</html>
