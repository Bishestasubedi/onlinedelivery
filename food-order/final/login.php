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
        <h1>Login</h1>
        <form method="post" action="login_process.php">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <input type="submit" value="Log In">
        </form>
    </div>
</body>
</html>
