<?php
session_start();

$host = "localhost"; 
$dbname = "testdb"; 
$username = "root"; 
$password = "root"; 

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

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
if (isset($_POST['add_to_cart']) && isset($_SESSION['user_id'])) {
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
    $item_indices = $_POST['item_index'];
    $quantities = $_POST['quantity'];

    foreach ($item_indices as $index => $item_index) {
        $quantity = $quantities[$index];
        if ($quantity > 0) {
            $_SESSION['cart'][$item_index]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$item_index]);
        }
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
    <title>Online Shopping</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your custom CSS file if needed -->
    <link rel="stylesheet" type="text/css" href="../css/index.css">
</head>
<body>
    <!-- Bootstrap Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container" id="cart">
            <!-- Your Logo -->
            <a class="navbar-brand" href="index.php"><img src="../images/redhat.png" class="logo" alt="Your Logo"></a>

            <!-- Navbar Toggle Button for Mobile -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#cart">Cart</a>
                    </li>
                    <?php
                    // Check if the user is logged in
                    if (isset($_SESSION['user_id'])) {
                        // User is logged in, display a logout button
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="../../app/controllers/logout.php">Logout</a>';
                        echo '</li>';
                    } else {
                        // User is not logged in, display login and signup links
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="../../app/auth/login.php">Login</a>';
                        echo '</li>';
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="../../app/auth/register.php">Sign Up</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Shopping cart -->
        <div class="row mt-5" id="cart">
            <div class="col-md-6" style="margin: 0 25%">
                <h2>Shopping Cart</h2>
                <?php
                if (count($_SESSION['cart']) > 0) {
                    echo "<form method='post'>";
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Product</th>";
                    echo "<th>Price</th>";
                    echo "<th>Quantity</th>";
                    echo "<th>Subtotal</th>";
                    echo "<th>Action</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($_SESSION['cart'] as $index => $item) {
                        echo "<tr>";
                        echo "<td>" . $item['product_name'] . "</td>";
                        echo "<td>$" . $item['product_price'] . "</td>";
                        echo "<td><input type='number' name='quantity[]' value='" . $item['quantity'] . "' min='1' class='form-control'></td>";
                        echo "<td>$" . ($item['product_price'] * $item['quantity']) . "</td>";
                        echo "<td><a href='?remove=" . $index . "' class='btn btn-danger btn-sm'>Remove</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    // Add hidden input fields for item_index
                    foreach ($_SESSION['cart'] as $index => $item) {
                        echo "<input type='hidden' name='item_index[]' value='$index'>";
                    }
                    echo "<button type='submit' name='update_cart' class='btn btn-primary btn-block'>Update Cart</button>";
                    echo "</form>";
                    echo "<p>Total: $" . $total . "</p>";
                } else {
                    echo "<p>Your cart is empty.</p>";
                }
                ?>
                
                <!-- Checkout button -->
                <form method="post">
                    <button type="submit" name="checkout" class="btn btn-success btn-block">Checkout</button>
                </form>
                <?php
                // Check if the checkout was completed
                if (isset($_POST['checkout'])) {
                    // Perform the checkout and save to the database (your existing code here)

                    // Display a confirmation message with animation
                    echo '<div class="alert alert-success mt-3" role="alert">Thank you for your purchase! Your order has been confirmed.</div>';
                    
                    // Clear the cart after checkout
                    $_SESSION['cart'] = [];
                }
                ?>
            </div>
        </div>
    </div>
    
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>