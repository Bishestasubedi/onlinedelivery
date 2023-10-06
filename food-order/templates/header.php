<body>
    <header> 
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="#" title="Logo">
                    <img src="images/logo1.png" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-right">
                <ul>
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="#category1">Categories</a>
                    </li>
                    <li>
                        <a href="#food1">Foods</a>
                    </li>
                    <li>
                        <a href="pages/login.php">Login</a>
                    </li>
                    <li>
                        <a href="pages/signup.php">Sign up</a>
                    </li>

                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
<!-- fOOD sEARCH Section Starts Here -->
<section class="food-search text-center" id="foodsearch">
        <div class="container">
            
            <form action="food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->
</header>