<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Shopping Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../asset/css/login_register.css">
</head>
<body>
    <div class="container">
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
        <h1>Login</h1>
        <form method="post" action="../controllers/login_process.php">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <input type="submit" value="Log In">
        </form>
    </div>
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
