<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="stylesDashboard-admin.css">
</head>
<body>
    <div class="container">
        <header class="navbar">
            <h1>Admin Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="manage_menu.php">Manage Menu</a></li>
                    <li><a href="manage_orders.php">Manage Orders</a></li>
                    <li><a href="print_transactions.php">Print Transactions</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <section class="hero">
            <h1>Welcome, <?php echo $_SESSION['user']['username']; ?>!</h1>
            <p>Manage your coffee shop easily with our admin tools.</p>
        </section>

        <!-- Content Section -->
        <section class="about-section">
            <div class="about-content">
                <h2>About the Dashboard</h2>
                <p>From here, you can manage all aspects of your coffee shop, including the menu, orders, and transactions.</p>
            </div>
            <div class="about-image">
                <img src="cakes.jpeg" alt="Dashboard Overview">
            </div>
        </section>
    </div>
</body>
</html>
