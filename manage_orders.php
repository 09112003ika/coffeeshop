<?php
session_start();
require_once 'db_connection.php'; // Sambungkan dengan database

// Memeriksa apakah user adalah admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}

// Fetch orders from the database
$sql = "SELECT orders.id, orders.menu_id, orders.quantity, orders.total_price, menu.name FROM orders INNER JOIN menu ON orders.menu_id = menu.id";
$result = $conn->query($sql);

// Handle order completion
if (isset($_GET['complete'])) {
    $order_id = $_GET['complete'];
    $sql_complete = "UPDATE orders SET status = 'completed' WHERE id = ?";
    $stmt = $conn->prepare($sql_complete);
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    header('Location: index.php?page=orders');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="manage_orders.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
</head>
<body>
    <h1>Manage Orders</h1>

    <h2>Pending Orders</h2>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Menu Item</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= $row['quantity']; ?></td>
                <td><?= number_format($row['total_price'], 2); ?></td>
                <td><a href="index.php?page=orders&complete=<?= $row['id']; ?>">Mark as Completed</a></td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>
