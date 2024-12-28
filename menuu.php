<?php
session_start();
require_once 'db_connection.php'; // Sambungkan dengan database

// Cek apakah kategori tersedia di URL
if (!isset($_GET['category'])) {
    die("Category not specified.");
}

$category = $_GET['category'];
$search_name = '';
$search_price = '';

// Menangani pencarian berdasarkan nama dan harga
if (isset($_POST['search'])) {
    $search_name = $_POST['search_name'];
    $search_price = $_POST['search_price'];
}

// Query untuk mengambil menu berdasarkan kategori dan pencarian
$query = "SELECT id, name, image, price, description, stock FROM menu WHERE category = ?";

// Menambahkan kondisi untuk pencarian berdasarkan nama dan harga
if ($search_name) {
    $query .= " AND name LIKE ?";
}
if ($search_price) {
    $query .= " AND price <= ?";
}

$stmt = $conn->prepare($query);

// Bind parameter sesuai dengan pencarian
if ($search_name && $search_price) {
    $search_name = "%$search_name%"; // Untuk pencarian nama menggunakan LIKE
    $stmt->bind_param("sss", $category, $search_name, $search_price);
} elseif ($search_name) {
    $search_name = "%$search_name%";
    $stmt->bind_param("ss", $category, $search_name);
} elseif ($search_price) {
    $stmt->bind_param("si", $category, $search_price);
} else {
    $stmt->bind_param("s", $category);
}

$stmt->execute();
$result = $stmt->get_result();

// Ambil hasil sebagai array
$menu_items = $result->fetch_all(MYSQLI_ASSOC);

// Menambahkan item ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'], $_POST['quantity'])) {
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];

    // Memeriksa apakah item sudah ada di keranjang
    if (isset($_SESSION['cart'][$menu_id])) {
        $_SESSION['cart'][$menu_id]['quantity'] += $quantity; // Menambahkan jumlah ke item yang ada
    } else {
        // Menambahkan item baru ke keranjang
        foreach ($menu_items as $item) {
            if ($item['id'] == $menu_id) {
                $_SESSION['cart'][$menu_id] = [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $quantity,
                    'image' => $item['image']
                ];
                break;
            }
        }
    }

    header("Location: menuu.php?category=$category");
    exit;
}

// Checkout (Jika tombol checkout ditekan)
if (isset($_POST['checkout'])) {
    foreach ($_SESSION['cart'] as $menu_id => $item) {
        // Ambil stok menu yang dipilih
        $stmt = $conn->prepare("SELECT stock FROM menu WHERE id = ?");
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();
        $stmt->bind_result($stock);
        $stmt->fetch();
        $stmt->close();

        // Jika stok cukup
        if ($stock >= $item['quantity']) {
            // Kurangi stok
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

    // Kosongkan keranjang setelah checkout
    $_SESSION['cart'] = [];
    header("Location: generate_invoice.php"); // Redirect ke halaman invoice
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($category); ?> Menu - Coffee Shop</title>
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
    <h1><?php echo ucfirst($category); ?> Menu</h1>
</div>

<section class="menu">
    <h2><?php echo ucfirst($category); ?> Menu</h2>

    <!-- Form pencarian -->
    <form method="POST" action="menuu.php?category=<?php echo $category; ?>" class="search-form">
        <input type="text" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>" placeholder="Search by name...">
        <input type="number" name="search_price" value="<?php echo htmlspecialchars($search_price); ?>" placeholder="Max price..." min="0">
        <button type="submit" name="search">Search</button>
    </form>

    <?php if (isset($_SESSION['message'])): ?>
        <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <div class="menu-grid">
        <?php if (!empty($menu_items)): ?>
            <?php foreach ($menu_items as $item): ?>
                <div class="menu-item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="200" onerror="this.onerror=null; this.src='default-image.jpg';">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p><strong>Price:</strong> Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                    <p><strong>Stock:</strong> <?php echo $item['stock']; ?></p>

                    <!-- Tombol untuk menambahkan item ke keranjang -->
                    <form action="menuu.php?category=<?php echo $category; ?>" method="POST" class="add-to-cart-form">
                        <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                        <div class="quantity-container">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" min="1" max="<?php echo $item['stock']; ?>" required>
                        </div>
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No items available in this category.</p>
        <?php endif; ?>
    </div>

    <!-- Keranjang Belanja -->
    <div class="cart">
        <h2>Your Cart</h2>
        <?php if (!empty($_SESSION['cart'])): ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li>
                        <?php echo $item['name']; ?> - Rp <?php echo number_format($item['price'], 0, ',', '.'); ?> x <?php echo $item['quantity']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <form action="menuu.php?category=<?php echo $category; ?>" method="POST">
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
