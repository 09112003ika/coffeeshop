<?php
session_start();
require_once 'db_connection.php';

// Check if the user is an admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}

// Fetch all transactions/orders from the database
$sql = "SELECT orders.id, orders.menu_id, orders.quantity, orders.total_price, orders.created_at, menu.name FROM orders INNER JOIN menu ON orders.menu_id = menu.id";
$result = $conn->query($sql);

// Generate a report
if (isset($_GET['generate_report'])) {
    // Example of report generation (you can format it as needed)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="transactions_report.xls"');
    echo "<table border='1'>
            <tr>
                <th>Order ID</th>
                <th>Menu Item</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['total_price']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    echo "</table>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Transactions</title>
</head>
<body>
    <h1>Transaction Report</h1>

    <a href="index.php?page=print_transactions&generate_report=true">Generate Report</a>
    <h2>Recent Transactions</h2>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Menu Item</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= $row['quantity']; ?></td>
                <td><?= number_format($row['total_price'], 2); ?></td>
                <td><?= $row['created_at']; ?></td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>
