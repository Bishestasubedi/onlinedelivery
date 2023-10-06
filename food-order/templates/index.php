<?php
$host = "localhost"; 
$dbname = "foodOrder"; 
$username = "root"; 
$password = "root"; 

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Connected successfully";
?>

<?php
// Start session
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
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

// Handle the checkout
if (isset($_POST['checkout'])) {
    // Insert cart data into the cart table
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        
        // Perform an INSERT query to add the item to the cart table
        $insert_query = "INSERT INTO cart (product_id, quantity) VALUES (?, ?)";
        $stmt = $mysqli->prepare($insert_query);
        $stmt->bind_param("ii", $product_id, $quantity);
        $stmt->execute();
    }

    // Clear the session cart after successful checkout
    $_SESSION['cart'] = [];
}

// Initialize product data
$productData = [];

// Handle product search
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];

    // Perform a SELECT query to retrieve products matching the search query
    $search_query = '%' . $search_query . '%'; // Add wildcard characters
    $search_query = $mysqli->real_escape_string($search_query);
    
    $select_query = "SELECT * FROM products WHERE product_name LIKE ?";
    $stmt = $mysqli->prepare($select_query);
    $stmt->bind_param("s", $search_query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and store the matching products in $productData
    while ($row = $result->fetch_assoc()) {
        $productData[] = $row;
    }

    $stmt->close();
} else {
    // If no search query is specified, fetch all products
    $select_all_query = "SELECT * FROM products";
    $result = $mysqli->query($select_all_query);

    // Fetch and store all products in $productData
    while ($row = $result->fetch_assoc()) {
        $productData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Shopping Cart</title>
    <link rel="stylesheet" href="./index.css">
</head>
<body>
    <h1>Simple Shopping Cart</h1>

    <!-- Product search form -->
    <form method="post">
        <label for="search_query">Search for products:</label>
        <input type="text" name="search_query" id="search_query">
        <input type="submit" name="search" value="Search">
    </form>

    <!-- Display search results -->
    <div>
        <?php
        if (isset($search_query)) {
            echo "<h2>Search Results</h2>";
            // Display only the products that match the search query
            foreach ($productData as $product) {
                echo "<p>Product: " . $product['Pizza'] . " - Price: $" . $product['product_price'] . "</p>";
                // Display the product image
                echo "<img src='" . $product['images/menu_pizza.jpg'] . "' alt='" . $product['product_name'] . "' width='150'>";
            }
        } else {
            echo "<h2>All Products</h2>";
            // Display all products
            foreach ($productData as $product) {
                echo "<p>Product: " . $product['product_name'] . " - Price: $" . $product['product_price'] . "</p>";
                // Display the product image
                echo "<img src='" . $product['product_image'] . "' alt='" . $product['product_name'] . "' width='150'>";
            }
        }
        ?>
    </div>

    <!-- Cart -->
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach((card, index) => {
        card.style.transitionDelay = index * 0.1 + 's';
        card.style.opacity = '0';
        setTimeout(() => {
            card.style.opacity = '1';
        }, 100);
    });
    });

     </script>
</body>
</html>
