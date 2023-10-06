<?php
// Include necessary files
require_once('../includes/db.php');
require_once('../includes/functions.php');
// redirect_if_not_logged_in();

// Get the user's username from the session
session_start();
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <!-- <link rel="stylesheet" href="../assets/css/style.css"> -->
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
    <header>
        <!-- Navbar Section Starts Here -->
        <section class="navbar">
            <div class="container">
                <div class="logo">
                    <a href="#" title="Logo">
                        <img src="../images/logo1.png" alt="Restaurant Logo" class="img-responsive">
                    </a>
                </div>

                <div class="menu text-right">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#category1">Categories</a></li>
                        <li><a href="#food1">Foods</a></li>
                        <li>
                            <?php if (isset($username) && !empty($username)) : ?>
                                <a href="#">Logged in as <?php echo $username; ?></a>
                        </li>
                        <li><a href="logout.php" class="btn btn-danger">Logout</a></li>
                            <?php endif; ?>
                    </ul>
                </div>

                <div class="clearfix"></div>
            </div>
        </section>
        <!-- Navbar Section Ends Here -->

        <!-- Food Search Section Starts Here -->
        <section class="food-search text-center">
            <div class="container">
                <form action="food-search.html" method="POST">
                    <input type="search" name="search" placeholder="Search for Food.." required>
                    <input type="submit" name="submit" value="Search" class="btn btn-primary">
                </form>
            </div>
        </section>
        <!-- Food Search Section Ends Here -->
    </header>

     <!-- CAtegories Section Starts Here -->
     <section class="categories">
  <div class="container">
    <h2 class="text-center">Explore Foods</h2>

    <a href="category-foods.html">
      <div class="box-3 float-container">
        <img src="../images/pizza.jpg" alt="Pizza" class="img-responsive img-curve">
        <h3 class="float-text text-white">Pizza</h3>
      </div>
    </a>

    <a href="category-burger.html"> <!-- Provide a meaningful URL -->
      <div class="box-3 float-container">
        <img src="../images/burger.jpg" alt="Burger" class="img-responsive img-curve">
        <h3 class="float-text text-white">Burger</h3>
      </div>
    </a>

    <a href="category-momo.html"> <!-- Provide a meaningful URL -->
      <div class="box-3 float-container">
        <img src="../images/momo.jpg" alt="Momo" class="img-responsive img-curve">
        <h3 class="float-text text-white">Momo</h3>
      </div>
    </a>

    <div class="clearfix"></div>
  </div>
</section>

    <!-- Categories Section Ends Here -->

    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="../images/menu-pizza.jpg" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Chicken pizza</h4>
                    <p class="food-price">Rs.200</p>
                    <p class="food-detail">
                        Made with Italian Sauce, Chicken, and organic vegetables.
                    </p>
                    <br>

                    <a href="order.html" class="btn btn-primary">Add to cart</a>
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="../images/menu-burger.jpg" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Chicken Burger</h4>
                    <p class="food-price">Rs.200</p>
                    <p class="food-detail">
                        Made with Italian Sauce, Chicken, and organic vegetables.
                    </p>
                    <br>

                    <a href="order.html" class="btn btn-primary">Add to cart</a>
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="../images/menu-burger.jpg" alt="Chicke Hawain Burger" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Veg Burger</h4>
                    <p class="food-price">Rs.300</p>
                    <p class="food-detail">
                        Made with Tomato ketchup, Mozarella cheese, Mayonnaise and organic vegetables.
                    </p>
                    <br>

                    <a href="order.html" class="btn btn-primary">Add to cart</a>
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="../images/menu-pizza.jpg" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Pepperoni Pizza</h4>
                    <p class="food-price">RS.500</p>
                    <p class="food-detail">
                        Made with Italian Sauce, Chicken, Salami and organic vegetables.
                    </p>
                    <br>

                    <a href="order.html" class="btn btn-primary">Add to cart</a>
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="../images/menu-pizza.jpg" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Chicken pizza with extra cheese</h4>
                    <p class="food-price">Rs.700</p>
                    <p class="food-detail">
                        Made with Italian Sauce, Chicken, double mozarella cheese, Jalapeno and organice vegetables.
                    </p>
                    <br>

                    <a href="order.html" class="btn btn-primary">Add to cart</a>
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="../images/menu-momo.jpg" alt="Chicke Hawain Momo" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Chicken Steam Momo</h4>
                    <p class="food-price">Rs.200</p>
                    <p class="food-detail">
                        Made with Chicken, springed onion, and other desired spices.
                    </p>
                    <br>

                    <a href="order.html" class="btn btn-primary">Add to cart</a>
                </div>
            </div>


            <div class="clearfix"></div>

            

        </div>

    </section>

</body>
</html>