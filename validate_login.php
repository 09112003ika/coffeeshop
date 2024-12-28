<?php
session_start();
require_once 'db_connection.php'; // Sambungkan dengan database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Both username and password are required!';
        header('Location: index.php?page=login');
        exit;
    }

    // Cek apakah username ada di database
    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $_SESSION['error'] = 'Database error: Failed to prepare statement!';
        header('Location: index.php?page=login');
        exit;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = 'Invalid username or password!';
        $stmt->close();
        header('Location: index.php?page=login');
        exit;
    }

    // Validasi password
    $user = $result->fetch_assoc();
    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'Invalid username or password!';
        $stmt->close();
        header('Location: index.php?page=login');
        exit;
    }

    // Login berhasil
    $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role'], // role: 'admin' atau 'customer'
    ];

    $stmt->close();
    $conn->close();

    // Arahkan pengguna berdasarkan role
    if ($user['role'] === 'admin') {
        header('Location: admin_dashboard.php');
    } else {
        header('Location: dashboard.php');
    }
    exit;
} else {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: index.php?page=login');
    exit;
}
