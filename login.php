<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php
    // Menampilkan pesan error jika ada
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']); // Clear error message setelah ditampilkan
    }
    ?>
    <form method="POST" action="validate_login.php">
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="index.php?page=register">Register here</a></p>
</div>
</body>
</html>
