<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Shopping Cart</title>
    <link rel="stylesheet" type="text/css" href="login_register.css">
</head>
<body>
    <div class="container">
        <h1>Sign Up</h1>
        <form method="post" action="register_process.php">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            
            <input type="submit" value="Sign Up">
        </form>
    </div>
</body>
</html>
