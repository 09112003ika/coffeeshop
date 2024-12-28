<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header('Location: index.php?page=login');
    exit;
}

// Mengambil nama pengguna dari session dan memastikan itu adalah string
$username = htmlspecialchars($_SESSION['user']['username']); // Pastikan 'username' adalah kunci yang benar
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="stylesDashboard.css">
</head>
<body>
<nav class="navbar">
    <a href="#" class="logo">Coffee Shop</a>
    <div>
        <a href="index.php?page=menuu">Menu</a>
        <a href="index.php?page=my-orders">My Orders</a>
        <a href="index.php?page=profile">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="hero">
        <h1>Welcome, <?php echo $username; ?>!</h1>
        <p>Your perfect place to enjoy coffee and snacks awaits.</p>
    </div>

    <section class="about-section">
    <div class="about-image">
        <img src="about-coffee.jpeg" alt="About Coffee Shop">
    </div>
    <div class="about-content">
        <h2>Welcome to Coffee Shop, where every cup of coffee tells a story and every visit becomes a cherished memory.</h2>
        <p>
            We pride ourselves on crafting the finest coffee experiences by combining premium-quality beans, expertly roasted to perfection, 
            with a warm and inviting atmosphere. Our mission is to create a space where people from all walks of life can come together to connect, 
            share ideas, and enjoy the little moments that make life special.
        </p>
        <p>
            At Coffee Shop, we are committed to sustainability, sourcing our ingredients responsibly to ensure that every cup of coffee and every bite you take 
            supports a better future for our planet. Whether you're stopping by for a morning pick-me-up, a midday treat, or a relaxing evening, 
            we are here to make your experience exceptional. With a passion for innovation and dedication to quality, Coffee Shop is more than just a café; 
            it’s a community hub where everyone is welcome.
        </p>
    </div>
</section>



<!-- Menu Section -->
<section class="menu">
    <h2>Our Menu</h2>
    <div class="menu-grid">
        <div class="menu-item">
            <a href="menuu.php?category=coffee">
                <img src="imagescoffee.jpeg" alt="Coffee">
                <h3>Coffee</h3>
                <p>Espresso, Latte, Cappuccino, Mocha</p>
            </a>
        </div>
        <div class="menu-item">
            <a href="menuu.php?category=non-coffee">
                <img src="non-coffe.jpeg" alt="Non-Coffee">
                <h3>Non-Coffee</h3>
                <p>Matcha Latte, Tea, Smoothies</p>
            </a>
        </div>
        <div class="menu-item">
            <a href="menuu.php?category=snacks">
                <img src="snack-food.jpeg" alt="Snacks">
                <h3>Snacks & Food</h3>
                <p>Cakes, Sandwiches, French Fries, Pasta</p>
            </a>
        </div>
    </div>
</section>

<!-- Additional Offerings Section -->
<section class="additional-offerings">
    <h2>Take Us With You</h2>
    <div class="offerings-container">
        <div class="offering-item">
            <h3>Catering</h3>
            <p>You can bring Coffee Toffee to your office events or personal celebrations.</p>
        </div>
        <div class="offering-item">
            <h3>Coffee Pack</h3>
            <p>In 200g packages, you can enjoy Coffee Toffee's specialty Arabica coffee at home.</p>
        </div>
        <div class="offering-item">
            <h3>Merchandise</h3>
            <p>Various merchandise options to show your love (or obsession) for coffee.</p>
        </div>
        <div class="offering-item">
            <h3>Hampers</h3>
            <p>Send a box containing coffee beans, their favorite drink, and a coffee mug to someone special.</p>
        </div>
    </div>
</section>



<!-- Story Section -->
<div class="story-section">
    <h2>Our Story</h2>
    <div class="story-container">
        <div class="story-text">
            <p>Founded in 2020, Coffee Shop was born out of a shared love for the art of coffee-making and the joy of bringing people together. What started as a small dream to create a warm and welcoming space has now grown into a thriving community hub where everyone is welcome. We believe that coffee is more than just a drink; it’s an experience, a connection, and a ritual that brings people closer.</p>

            <p>Our journey began with a commitment to sourcing the finest beans from sustainable farms around the world. We partner with passionate growers who share our dedication to quality and ethical practices. Each cup of coffee we serve is a testament to the hard work, care, and craftsmanship that goes into every bean.</p>

            <p>At Coffee Shop, we aim to create a space where creativity flourishes and relationships are nurtured. Whether you're here to savor a rich espresso, enjoy a comforting latte, or indulge in our delicious snacks and meals, we strive to make every visit unforgettable.</p>
        </div>
        <div class="story-image">
            <img src="coffe-story.jpeg" alt="Our Story Image">
        </div>
    </div>
</div>

</section>

<!-- Gallery Section -->
<section class="gallery">
    <h2>Gallery</h2>
    <div class="gallery-images">
        <img src="galery.jpeg" alt="Coffe">
        <img src="coffee (1).jpeg" alt="Coffee">
        <img src="snack-food.jpeg" alt="Snacks">
        <img src="interior.jpeg" alt="Interior">
        <img src="customer.jpeg" alt="Customers enjoying">
        <img src="coffee shop.jpeg" alt="Customers enjoying2">

        <img src="Barista.jpeg" alt="Barista">
        <img src="barista2.jpeg" alt="Barista at work">
        <img src="outdoor.jpeg" alt="Outdoor">
        <img src="Tiramisu.jpeg" alt="Delicious desserts">
        <img src="barista3.jpeg" alt="Group of happy customers">
        <img src="cust.jpeg" alt="Coffee machine in action">
        
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Coffee Shop. All rights reserved.</p>
</footer>
</body>
</html>
