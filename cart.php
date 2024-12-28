<?php
session_start();
require_once 'db_connection.php';

// Mengonfirmasi bahwa keranjang tidak kosong
if (empty($_SESSION['cart'])) {
    die("Your cart is empty.");
}

// Proses pembayaran (simple simulating pembayaran)
if (isset($_POST['checkout'])) {
    $total = 0;
    $order_details = []; // Menyimpan detail transaksi

    // Memperbarui stok dan menyiapkan detail transaksi
    foreach ($_SESSION['cart'] as $menu_id => $item) {
        $total += $item['price'] * $item['quantity'];
        $order_details[] = $item; // Menyimpan detail item yang dibeli

        // Update stok di database
        $stmt = $conn->prepare("SELECT stock FROM menu WHERE id = ?");
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();
        $stmt->bind_result($stock);
        $stmt->fetch();
        $stmt->close();

        // Kurangi stok jika cukup
        if ($stock >= $item['quantity']) {
            $new_stock = $stock - $item['quantity'];
            $stmt = $conn->prepare("UPDATE menu SET stock = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_stock, $menu_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $_SESSION['message'] = "Not enough stock for item: " . $item['name'];
            break;
        }
    }

    // Simulasi pembayaran selesai
    $_SESSION['cart'] = []; // Kosongkan keranjang setelah checkout
    $_SESSION['message'] = "Payment of Rp " . number_format($total, 0, ',', '.') . " was successful.";

    // Log transaksi (misalnya, simpan ke database transaksi)
    // Pastikan Anda menambahkan query untuk memasukkan transaksi ke tabel "transactions"
    // Proses pencetakan invoice bisa dilakukan di sini

    // Redirect untuk menampilkan hasil pembayaran
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Coffee Shop</title>
    <link rel="stylesheet" href="menu.css">
</head>
<body>
<nav class="navbar">
    <a href="#" class="logo">Coffee Shop</a>
    <div>
        <a href="menuu.php?category=coffee">Coffee</a>
        <a href="menuu.php?category=non-coffee">Non-Coffee</a>
        <a href="menuu.php?category=snacks">Snacks</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="hero">
    <h1>Your Cart</h1>
</div>

<section class="cart">
    <h2>Review Your Cart</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <div class="cart-items">
        <?php if (!empty($_SESSION['cart'])): ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li>
                        <?php echo $item['name']; ?> - Rp <?php echo number_format($item['price'], 0, ',', '.'); ?> x <?php echo $item['quantity']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <form action="cart.php" method="POST">
                <button type="submit" name="checkout">Checkout</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</section>

<footer>
    <p>&copy; 2024 Coffee Shop. All rights reserved.</p>
</footer>
</body>
</html>
