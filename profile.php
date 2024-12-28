<?php
session_start();

// Cek apakah pengguna sudah login dan memiliki peran 'customer'
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header('Location: index.php?page=login');
    exit;
}

require_once 'db_connection.php'; // Include file koneksi database

// Ambil data pengguna dari session
$user_id = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];
$email_or_phone = $_SESSION['user']['email_or_phone'];

// Variabel untuk menampilkan pesan error atau sukses
$message = '';

// Proses pembaruan profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username']);
    $new_email_or_phone = trim($_POST['email_or_phone']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi input
    if (empty($new_username) || empty($new_email_or_phone)) {
        $message = "Username and email/phone are required!";
    } elseif (!filter_var($new_email_or_phone, FILTER_VALIDATE_EMAIL) && !preg_match('/^\+?[0-9]{10,15}$/', $new_email_or_phone)) {
        $message = "Invalid email or phone number!";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Update password jika ada perubahan
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $sql_update = "UPDATE users SET username = ?, email_or_phone = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param('sssi', $new_username, $new_email_or_phone, $hashed_password, $user_id);
        } else {
            $sql_update = "UPDATE users SET username = ?, email_or_phone = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param('ssi', $new_username, $new_email_or_phone, $user_id);
        }

        // Eksekusi query pembaruan
        if ($stmt->execute()) {
            $_SESSION['user']['username'] = $new_username;
            $_SESSION['user']['email_or_phone'] = $new_email_or_phone;
            if (!empty($new_password)) {
                $_SESSION['user']['password'] = $new_password;
            }
            $message = "Profile updated successfully!";
        } else {
            $message = "Failed to update profile. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <link rel="stylesheet" href="stylesDashboard.css">
</head>
<body>
<nav class="navbar">
    <a href="#" class="logo">Coffee Shop</a>
    <div>
        <a href="index.php?page=menuu">Menu</a>
        <a href="index.php?page=my-orders">My Orders</a>
        <a href="index.php?page=profile">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="hero">
    <h1>Manage Your Profile, <?php echo htmlspecialchars($username); ?>!</h1>
</div>

<?php if ($message) { echo "<p class='message'>$message</p>"; } ?>

<section class="profile-form">
    <h2>Update Your Information</h2>
    <form method="POST">
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div>
            <label for="email_or_phone">Email or Phone</label>
            <input type="text" id="email_or_phone" name="email_or_phone" value="<?php echo htmlspecialchars($email_or_phone); ?>" required>
        </div>
        <div>
            <label for="password">New Password (Leave blank if you don't want to change it)</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>
        <button type="submit">Update Profile</button>
    </form>
</section>

<footer>
    <p>&copy; 2024 Coffee Shop. All rights reserved.</p>
</footer>
</body>
</html>
