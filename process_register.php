<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = 'All fields are required!';
        header('Location: register.php');
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9]{5,20}$/', $username)) {
        $_SESSION['error'] = 'Username must be 5-20 characters long and contain only letters and numbers!';
        header('Location: register.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = 'Password must be at least 8 characters long!';
        header('Location: register.php');
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Passwords do not match!';
        header('Location: register.php');
        exit;
    }

    try {
        // Periksa apakah username sudah digunakan
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: Failed to prepare statement!');
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = 'Username is already taken!';
            header('Location: register.php');
            exit;
        }

        $stmt->close();

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Masukkan data pengguna baru
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: Failed to prepare statement!');
        }

        $stmt->bind_param('ss', $username, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Registration successful! You can now log in.';
            header('Location: index.php');
            exit;
        } else {
            throw new Exception('Registration failed. Please try again.');
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: register.php');
        exit;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
} else {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: register.php');
    exit;
}
