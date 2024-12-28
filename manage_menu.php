<?php
session_start();
require_once 'db_connection.php'; // Memanggil file koneksi database

// Cek apakah user sudah login dan memiliki peran admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}

// Menangani form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Validasi input
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $image = trim($_POST['image']);
    $stock = trim($_POST['stock']); // Input stok

    if ($action === 'add') {
        // Tambahkan item menu baru
        $query = "INSERT INTO menu (name, category, price, description, image, stock) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdssi", $name, $category, $price, $description, $image, $stock);
        $stmt->execute();
    } elseif ($action === 'update' && isset($_POST['id'])) {
        // Perbarui item menu yang ada
        $id = $_POST['id'];
        $query = "UPDATE menu SET name = ?, category = ?, price = ?, description = ?, image = ?, stock = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdssii", $name, $category, $price, $description, $image, $stock, $id);
        $stmt->execute();
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        // Hapus item menu
        $id = $_POST['id'];
        $query = "DELETE FROM menu WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header('Location: manage_menu.php');
    exit;
}

// Ambil data menu dari database
$query = "SELECT * FROM menu";
$result = $conn->query($query);
$menu_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - Coffee Shop</title>
    <link rel="stylesheet" href="manage_menu.css">
</head>
<body>
<nav class="navbar">
    <a href="#" class="logo">Coffee Shop Admin</a>
    <div>
        <a href="index.php?page=dashboard">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<section class="admin-section">
    <h1>Manage Menu</h1>

    <form action="manage_menu.php" method="POST" class="menu-form">
        <input type="hidden" name="action" value="add">
        <h2>Add New Menu Item</h2>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="coffee">Coffee</option>
            <option value="non-coffee">Non-Coffee</option>
            <option value="snacks">Snacks</option>
        </select>

        <label for="price">Price (in IDR):</label>
        <input type="number" name="price" id="price" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="image">Image URL:</label>
        <input type="text" name="image" id="image" required>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" required>

        <button type="submit">Add Item</button>
    </form>

    <h2>Existing Menu Items</h2>
    <table class="menu-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Description</th>
                <th>Image</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menu_items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['category']); ?></td>
                <td><?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($item['description']); ?></td>
                <td>
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                         width="50" 
                         onerror="this.onerror=null; this.src='default-image.jpg';">
                </td>
                <td><?php echo $item['stock']; ?></td>
                <td>
                    <form action="manage_menu.php" method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                    </form>
                    <button onclick="populateForm(<?php echo htmlspecialchars(json_encode($item)); ?>)">Edit</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<script>
function populateForm(item) {
    document.querySelector('form.menu-form input[name="action"]').value = 'update';
    document.querySelector('form.menu-form input[name="id"]').value = item.id;
    document.querySelector('form.menu-form input[name="name"]').value = item.name;
    document.querySelector('form.menu-form select[name="category"]').value = item.category;
    document.querySelector('form.menu-form input[name="price"]').value = item.price;
    document.querySelector('form.menu-form textarea[name="description"]').value = item.description;
    document.querySelector('form.menu-form input[name="image"]').value = item.image;
    document.querySelector('form.menu-form input[name="stock"]').value = item.stock;
}
</script>
</body>
</html>
