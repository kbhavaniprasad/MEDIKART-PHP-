<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    echo "<script>alert('Please log in to view your cart.'); window.location.href = 'loginpage.php';</script>";
    exit;
}

$cartItems = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - MediKart</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fa;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        header {
            background: linear-gradient(90deg, #00c4b4, #00e6cc);
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
        }
        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }
        nav ul li a:hover {
            color: #f0f8ff;
        }
        .cart-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .cart-section h1 {
            font-size: 36px;
            color: #00c4b4;
            text-align: center;
            margin-bottom: 40px;
        }
        .cart-item {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            max-height: 300px;
        }
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }
        .cart-item h3 {
            font-size: 18px;
            color: #333;
        }
        .cart-item p {
            font-size: 14px;
            color: #666;
        }
        .cart-item .price {
            font-size: 16px;
            color: #00c4b4;
            font-weight: 600;
        }
        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #00c4b4;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            color: #00e6cc;
        }
        footer {
            background: #1a2a44;
            color: #fff;
            padding: 40px 20px;
            text-align: center;
        }
        footer a {
            color: #00e6cc;
            text-decoration: none;
        }
        footer a:hover {
            color: #00c4b4;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo">MediKart</div>
            <nav>
                <ul>
                    <li><a href="home1.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="logout.php">LogOut</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="cart-section">
        <h1>Your Cart</h1>
        <?php if (empty($cartItems)): ?>
            <p class="empty-cart">Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div>
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="price">Price: $<?php echo number_format($item['price'], 2); ?></p>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <a href="shop.php" class="back-link">Continue Shopping</a>
    </section>

    <footer>
        <p>© 2025 MediKart. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="#privacy">Privacy Policy</a></p>
    </footer>
</body>
</html>