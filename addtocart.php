<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Initialize feedback message
$feedbackMessage = '';
$feedbackType = ''; // 'success' or 'error'

// Retrieve user from cookie
$user = null;
$email = null;
if (isset($_COOKIE['details'])) {
    $details = json_decode($_COOKIE['details'], true);
    if (is_array($details) && isset($details['user_name'])) {
        $user = filter_var($details['user_name'], FILTER_SANITIZE_STRING);
    } else {
        $feedbackMessage = 'Invalid cookie data. Please log in again.';
        $feedbackType = 'error';
    }
} else {
    $feedbackMessage = 'Please log in to access the cart.';
    $feedbackType = 'error';
}

// Proceed only if user is valid
if ($user && !$feedbackMessage) {
    // Database configuration
    $host = 'localhost';
    $user_db = 'root'; // Renamed to avoid conflict with $user
    $password = '';
    $database = 'php';

    // Connect to database
    $conn = mysqli_connect($host, $user_db, $password, $database);
    if (!$conn) {
        $feedbackMessage = 'Database connection failed. Please try again later.';
        $feedbackType = 'error';
    } else {
        // Retrieve email from database
        $sql = "SELECT email FROM registerpage WHERE Username = ?";
        $st = mysqli_prepare($conn, $sql);
        if ($st) {
            mysqli_stmt_bind_param($st, "s", $user);
            if (mysqli_stmt_execute($st)) {
                $res = mysqli_stmt_get_result($st);
                $r = mysqli_fetch_assoc($res);
                if ($r) {
                    $email = $r['email'];
                } else {
                    $feedbackMessage = 'User not found in database.';
                    $feedbackType = 'error';
                }
            } else {
                $feedbackMessage = 'Failed to fetch user email.';
                $feedbackType = 'error';
            }
            mysqli_stmt_close($st);
        } else {
            $feedbackMessage = 'Failed to prepare email query.';
            $feedbackType = 'error';
        }

        // Proceed with cart addition if email is retrieved
        if ($email && !$feedbackMessage) {
            // Verify CSRF token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $feedbackMessage = 'Invalid CSRF token. Please try again.';
                $feedbackType = 'error';
            } else {
                // Handle form submission
                if (isset($_POST['productName']) && isset($_POST['productPrice']) && isset($_POST['productImage'])) {
                    // Sanitize and validate inputs
                    $name = filter_var($_POST['productName'], FILTER_SANITIZE_STRING);
                    $price = floatval(preg_replace('/[^0-9.]/', '', $_POST['productPrice']));
                    $image = filter_var($_POST['productImage'], FILTER_SANITIZE_URL);

                    if ($name && $price !== false && $price >= 0 && $image) {
                        // Check if medicine exists in database for this user
                        $query = "SELECT item_id FROM cart_items WHERE name = ? AND user = ?";
                        $stmt = mysqli_prepare($conn, $query);
                        if (!$stmt) {
                            $feedbackMessage = 'Database query preparation failed. Please try again.';
                            $feedbackType = 'error';
                        } else {
                            mysqli_stmt_bind_param($stmt, "ss", $name, $user);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_assoc($result);
                            $item_id = $row['item_id'] ?? null;
                            mysqli_stmt_close($stmt);

                            if ($item_id === null) {
                                // Medicine doesn't exist, generate a new unique code
                                do {
                                    $code = mt_rand(100000, 999999);
                                    $check_query = "SELECT item_id FROM cart_items WHERE item_id = ?";
                                    $check_stmt = mysqli_prepare($conn, $check_query);
                                    mysqli_stmt_bind_param($check_stmt, "s", $code);
                                    mysqli_stmt_execute($check_stmt);
                                    $check_result = mysqli_stmt_get_result($check_stmt);
                                    $exists = mysqli_fetch_assoc($check_result) !== null;
                                    mysqli_stmt_close($check_stmt);
                                } while ($exists);

                                // Insert new medicine into database
                                $sql = "INSERT INTO cart_items (item_id, name, price, image, user, email) VALUES (?, ?, ?, ?, ?, ?)";
                                $stmt = mysqli_prepare($conn, $sql);
                                if (!$stmt) {
                                    $feedbackMessage = 'Database query preparation failed. Please try again.';
                                    $feedbackType = 'error';
                                } else {
                                    mysqli_stmt_bind_param($stmt, "ssdsss", $code, $name, $price, $image, $user, $email);
                                    if (!mysqli_stmt_execute($stmt)) {
                                        $feedbackMessage = 'Failed to add item to database. Please try again.';
                                        $feedbackType = 'error';
                                    } else {
                                        $item_id = $code;
                                        $feedbackMessage = 'Item added to cart successfully!';
                                        $feedbackType = 'success';
                                    }
                                    mysqli_stmt_close($stmt);
                                }
                            } else {
                                $feedbackMessage = 'Item added to cart successfully!';
                                $feedbackType = 'success';
                            }

                            // Add to session cart
                            if ($feedbackType === 'success') {
                                $itemExists = false;
                                foreach ($_SESSION['cart'] as &$item) {
                                    if ($item['name'] === $name && $item['user'] === $user) {
                                        $item['quantity'] = isset($item['quantity']) ? $item['quantity'] + 1 : 2;
                                        $item['price'] = floatval($item['price']);
                                        $itemExists = true;
                                        break;
                                    }
                                }

                                if (!$itemExists) {
                                    $_SESSION['cart'][] = [
                                        'item_id' => $item_id,
                                        'name' => $name,
                                        'price' => $price,
                                        'image' => $image,
                                        'quantity' => 1,
                                        'user' => $user,
                                        'email' => $email
                                    ];
                                }
                            }
                        }
                    } else {
                        $feedbackMessage = 'Invalid input data. Please check your inputs.';
                        $feedbackType = 'error';
                    }
                } else {
                    $feedbackMessage = 'Required fields are missing.';
                    $feedbackType = 'error';
                }
            }
        }
        // Close database connection only if opened
        if (isset($conn) && $conn) {
            mysqli_close($conn);
        }
    }
}

// Regenerate CSRF token for the next request
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - MediKart</title>
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
            max-height :300px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
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
        .feedback {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .feedback.success {
            background: #d4edda;
            color: #155724;
        }
        .feedback.error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Items in Your Cart</h1>
    <?php if ($feedbackMessage): ?>
        <div class="feedback <?php echo htmlspecialchars($feedbackType, ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($feedbackMessage, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>
    <?php if (empty($_SESSION['cart'])): ?>
        <p class="empty-cart">No items in cart.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>">
                <div>
                    <p><strong>Product:</strong> <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Price:</strong> $<?php echo htmlspecialchars(number_format(floatval($item['price']), 2), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <a href="shop.php" class="back-link">Continue Shopping</a>
</body>
</html>