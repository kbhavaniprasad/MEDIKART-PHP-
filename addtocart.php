<!-- <?php
// Generate a 6-digit code
$code = mt_rand(100000, 999999);
echo "Generated Code: " . $code;


?> -->

<?php
session_start();

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: shop.php?error=invalid_csrf');
    exit;
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if (isset($_POST['productName']) && isset($_POST['productPrice'])) {
    $_SESSION['cart'][] = [
        'name' => $_POST['productName'],
        'price' => $_POST['productPrice']
    ];
}

// Regenerate CSRF token for the next request
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        h1 {
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
            margin-bottom: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .cart-item p {
            font-size: 16px;
            margin: 5px 0;
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
    </style>
</head>
<body>
    <h1>Items in Your Cart</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <p class="empty-cart">No items in cart.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="cart-item">
                <p><strong>Product:</strong> <?php echo htmlspecialchars($item['name']); ?></p>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($item['price']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <a href="shop.php" class="back-link">Continue Shopping</a>
</body>
</html>