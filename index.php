
<?php

session_start();
// Check if the user is already logged in

include_once 'Utilitarios/Alerta.php'; // Include the database connection file

if (isset($_SESSION['User'])) {
    header("Location: Home/Home.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/Entrada.css?=<?php echo time(); ?>">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">

</head>
<body>
    <div class="container">
        <div class="login">
            <h1>Login</h1>
            <form action="Server/Porteiro.php" method="POST">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username"><br><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password"><br><br>
                <input type="hidden" name="login" value="login">
                <input type="submit" value="Login">
            </form>
        </div>
        <div class="register">
            <h1>Register</h1>
            <form action="Server/Porteiro.php" method="POST">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username"><br><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password"><br><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email"><br><br>
                <input type="hidden" name="register" value="register">
                <input type="submit" value="Register">
            </form>
        </div>
    </div>
</body>
</html>
