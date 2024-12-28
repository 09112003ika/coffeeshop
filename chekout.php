<?php
session_start();
require_once 'db_connection.php';

// Menangani form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $quantity = $_POST['quantity']; // Jumlah item yang dibeli

    // Ambil stok item
    $query = "SELECT stock FROM menu WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item['stock'] >= $quantity) {
        // Jika stok mencukupi, proses pemesanan
        $new_stock = $item['stock'] - $quantity;
        $query = "UPDATE menu SET stock = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $new_stock, $id);
        $stmt->execute();

        // Simpan informasi pemesanan dalam sesi atau basis data (sesuai kebutuhan)
        $_SESSION['order'][] = [
            'item_id' => $id,
            'quantity' => $quantity,
        ];

        // Redirect ke halaman sukses atau ringkasan
        header("Location: order_success.php");
        exit;
    } else {
        // Jika stok tidak mencukupi
        echo "Stok tidak cukup.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Coffee Shop</title>
</head>
<body>
    <h1>Checkout</h1>
    <form action="checkout.php" method="POST">
        <label for="id">Menu Item ID:</label>
        <input type="text" name="id" id="id" required>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" required min="1">
        <button type="submit">Checkout</button>
    </form>
</body>
</html>
