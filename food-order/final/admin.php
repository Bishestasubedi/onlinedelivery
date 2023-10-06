<?php
$host = "localhost"; 
$dbname = "foodOrder"; 
$username = "root"; 
$password = "root"; 

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// echo "Connected successfully";
?>

<?php
// Start session
session_start();

// Function to handle image upload and return the image URL
function uploadImage() {
    // Check if a file was uploaded
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['product_image'];

        // Get the file name and extension
        $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);

        // Generate a unique file name to prevent overwriting
        $uniqueFileName = uniqid('product_image_') . '.' . $fileExtension;

        // Move the uploaded file to the current directory
        if (move_uploaded_file($uploadedFile['tmp_name'], $uniqueFileName)) {
            // Return the URL of the uploaded image (assuming it's in the same directory as the script)
            return $uniqueFileName;
        }
    }

    // If no file was uploaded or there was an error, return an empty string
    return '';
}

// CRUD operations for admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create') {
            // Handle product creation
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $product_image = uploadImage(); // Handle image upload and get the image URL

            $insert_query = "INSERT INTO products (product_name, product_price, product_image) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($insert_query);
            $stmt->bind_param("sds", $product_name, $product_price, $product_image);
            $stmt->execute();
        } elseif ($action === 'update') {
            // Handle product update
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $product_image = uploadImage(); // Handle image upload and get the image URL

            $update_query = "UPDATE products SET product_name = ?, product_price = ?, product_image = ? WHERE product_id = ?";
            $stmt = $mysqli->prepare($update_query);
            $stmt->bind_param("sdsi", $product_name, $product_price, $product_image, $product_id);
            $stmt->execute();
        } elseif ($action === 'delete') {
            // Handle product deletion
            $product_id = $_POST['product_id'];

            $delete_query = "DELETE FROM products WHERE product_id = ?";
            $stmt = $mysqli->prepare($delete_query);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
        }
    }
}

// Fetch all products
$select_all_query = "SELECT * FROM products";
$result = $mysqli->query($select_all_query);

// Fetch and store all products in $productData
$productData = [];
while ($row = $result->fetch_assoc()) {
    $productData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
    <h1>Admin Panel</h1>

    <!-- Product management forms -->
    <h2>Product Management</h2>

    <!-- Create product form -->
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" id="product_name" required>
        <label for="product_price">Product Price:</label>
        <input type="number" name="product_price" id="product_price" step="0.01" required>
        <label for="product_image">Product Image:</label>
        <input type="file" name="product_image" id="product_image" accept="image/*" required>
        <input type="submit" value="Create Product">
    </form>

    <!-- Update product form -->
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update">
        <label for="update_product_id">Product ID: </label>
        <input type="number" name="product_id" id="update_product_id" required>
        <label for="update_product_name">Product Name: </label>
        <input type="text" name="product_name" id="update_product_name" required>
        <label for="update_product_price">Product Price: </label>
        <input type="number" name="product_price" id="update_product_price" step="0.01" required>
        <label for="update_product_image">Product Image: </label>
        <input type="file" name="product_image" id="update_product_image" accept="image/*">
        <input type="submit" value="Update Product">
    </form>

    <!-- Delete product form -->
    <form method="post">
        <input type="hidden" name="action" value="delete">
        <label for="delete_product_id">Product ID:</label>
        <input type="number" name="product_id" id="delete_product_id" required>
        <input type="submit" value="Delete Product">
    </form>

    <!-- Product list -->
    <h2>Product List</h2>
    <ul>
        <?php
        // Display products with images, edit, and delete options
        foreach ($productData as $product) {
            echo "<li>";
            echo "ID: " . $product['product_id'] . " - Name: " . $product['product_name'] . " - Price: Rs " . $product['product_price'];
            if (!empty($product['product_image'])) {
                echo "<br><img src='" . $product['product_image'] . "' alt='Product Image' style='max-width: 200px;'>";
            }
            echo "</li>";
        }
        ?>
    </ul>
</body>
</html>
