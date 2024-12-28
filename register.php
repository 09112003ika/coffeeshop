<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email_or_phone = trim($_POST['email_or_phone']);
    $role = isset($_POST['role']) ? $_POST['role'] : 'customer'; // Default role is 'customer'

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email_or_phone)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (!filter_var($email_or_phone, FILTER_VALIDATE_EMAIL) && !preg_match('/^\+?[0-9]{10,15}$/', $email_or_phone)) {
        $error = "Invalid email or phone number!";
    } elseif (!in_array($role, ['admin', 'customer'])) {
        $error = "Invalid role selected!";
    } else {
        // Cek apakah email_or_phone sudah ada
        $sql_check_email_or_phone = "SELECT * FROM users WHERE email_or_phone = ?";
        $stmt_check_email_or_phone = $conn->prepare($sql_check_email_or_phone);
        $stmt_check_email_or_phone->bind_param('s', $email_or_phone);
        $stmt_check_email_or_phone->execute();
        $result_check_email_or_phone = $stmt_check_email_or_phone->get_result();

        if ($result_check_email_or_phone->num_rows > 0) {
            $error = "Email or phone number already registered!";
        } else {
            // Cek apakah username sudah ada
            $sql_check_username = "SELECT * FROM users WHERE username = ?";
            $stmt_check_username = $conn->prepare($sql_check_username);
            $stmt_check_username->bind_param('s', $username);
            $stmt_check_username->execute();
            $result_check_username = $stmt_check_username->get_result();

            if ($result_check_username->num_rows > 0) {
                $error = "Username is already taken!";
            } else {
                // Hash password dan simpan ke database
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $sql = "INSERT INTO users (username, password, email_or_phone, role) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssss', $username, $hashed_password, $email_or_phone, $role);

                if ($stmt->execute()) {
                    header('Location: index.php');
                    exit;
                } else {
                    $error = "Registration failed! Please try again.";
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="stylesRegister.css">
</head>
<body>
<div class="container">
    <h2>Register</h2>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <form method="POST">
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div>
            <label for="email_or_phone">Email or Phone</label>
            <input type="text" id="email_or_phone" name="email_or_phone" required>
        </div>
        <div>
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="customer" selected>Customer</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="index.php">Login here</a></p>
</div>
</body>
</html>
