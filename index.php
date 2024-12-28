<?php
session_start(); // Memulai session
require_once 'db_connection.php'; // Sambungkan dengan database

// Menentukan halaman yang akan dimuat berdasarkan parameter 'page'
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Header keamanan
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self';");

// Memasukkan halaman yang sesuai berdasarkan parameter 'page'
switch ($page) {
    case 'register':
        include 'register.php'; // Halaman register
        break;

    case 'login':
        // Cek apakah sudah login, jika sudah, arahkan ke dashboard
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: index.php?page=dashboard&role=admin'); // Arahkan ke dashboard admin
                exit;
            } else {
                header('Location: index.php?page=dashboard&role=customer'); // Arahkan ke dashboard customer
                exit;
            }
        } else {
            include 'login.php'; // Halaman login
        }
        break;

    case 'dashboard':
        // Jika sudah login, arahkan ke dashboard sesuai role
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                include 'admin_dashboard.php'; // Halaman dashboard admin
            } else {
                include 'dashboard.php'; // Halaman dashboard customer
            }
        } else {
            header('Location: index.php?page=login'); // Jika belum login, arahkan ke login
            exit;
        }
        break;

    case 'menu':
        // Menampilkan halaman menu berdasarkan kategori
        if (isset($_GET['category'])) {
            // Ambil kategori dari URL
            $category = $_GET['category'];

            // Menu items untuk setiap kategori
            $menu_items = [
                'coffee' => [
                    [
                        'name' => 'Espresso',
                        'image' => 'images/espresso.jpeg',
                        'price' => 'Rp 25,000',
                        'description' => 'A strong, black coffee brewed by forcing steam through finely ground coffee beans.'
                    ],
                    [
                        'name' => 'Latte',
                        'image' => 'images/latte.jpeg',
                        'price' => 'Rp 35,000',
                        'description' => 'A coffee drink made with espresso and steamed milk, served with a thin layer of foam.'
                    ],
                    [
                        'name' => 'Cappuccino',
                        'image' => 'images/cappuccino.jpeg',
                        'price' => 'Rp 37,500',
                        'description' => 'A coffee drink made with espresso, steamed milk, and topped with frothy milk foam.'
                    ],
                    [
                        'name' => 'Mocha',
                        'image' => 'images/mocha.jpeg',
                        'price' => 'Rp 40,000',
                        'description' => 'A chocolate-flavored variant of a latte, typically made with espresso, steamed milk, and chocolate syrup.'
                    ]
                ],
                'non-coffee' => [
                    [
                        'name' => 'Matcha Latte',
                        'image' => 'images/matcha-latte.jpeg',
                        'price' => 'Rp 40,000',
                        'description' => 'A creamy green tea latte made with matcha powder, milk, and a touch of sweetness.'
                    ],
                    [
                        'name' => 'Tea',
                        'image' => 'images/tea.jpeg',
                        'price' => 'Rp 20,000',
                        'description' => 'A refreshing drink made by infusing tea leaves in hot water.'
                    ],
                    [
                        'name' => 'Smoothie',
                        'image' => 'images/smoothie.jpeg',
                        'price' => 'Rp 50,000',
                        'description' => 'A blended drink made with fruit, yogurt, and ice, perfect for a healthy refreshment.'
                    ]
                ],
                'snacks' => [
                    [
                        'name' => 'Cakes',
                        'image' => 'images/cakes.jpeg',
                        'price' => 'Rp 30,000',
                        'description' => 'A variety of delicious cakes served fresh from the oven.'
                    ],
                    [
                        'name' => 'Sandwiches',
                        'image' => 'images/sandwiches.jpeg',
                        'price' => 'Rp 45,000',
                        'description' => 'Freshly made sandwiches with a choice of fillings like chicken, ham, or cheese.'
                    ],
                    [
                        'name' => 'French Fries',
                        'image' => 'images/fries.jpeg',
                        'price' => 'Rp 25,000',
                        'description' => 'Crispy and golden French fries, served with your choice of dipping sauce.'
                    ],
                    [
                        'name' => 'Pasta',
                        'image' => 'images/pasta.jpeg',
                        'price' => 'Rp 60,000',
                        'description' => 'Pasta served with a choice of sauces, such as marinara, Alfredo, or pesto.'
                    ]
                ]
            ];

            // Menampilkan menu berdasarkan kategori
            if (array_key_exists($category, $menu_items)) {
                include 'menuu.php'; // Halaman untuk menampilkan menu kategori
            } else {
                echo "Kategori tidak ditemukan.";
            }
        } else {
            echo "Silakan pilih kategori menu.";
        }
        break;

    default:
        // Default ke halaman login jika parameter page tidak ditemukan
        include 'login.php';
        break;
}
?>
