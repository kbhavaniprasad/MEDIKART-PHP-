<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Please log in to access the shop.'); window.location.href = 'loginpage.php';</script>";
    exit;
}

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch products
$products = [];
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo "<script>alert('Database connection failed: " . addslashes($conn->connect_error) . "'); window.location.href = 'shop.php';</script>";
    exit;
}
$result = $conn->query("SELECT id, name, price, image FROM products");
if ($result === false) {
    echo "<script>alert('Failed to fetch products: " . addslashes($conn->error) . "'); window.location.href = 'shop.php';</script>";
    $conn->close();
    exit;
}
$products = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - MediKart</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f7fa;
            color: #333;
            line-height: 1.6;
        }

        header {
            background: linear-gradient(90deg, #00c4b4, #00e6cc);
            padding: 20px 40px;
            position: sticky;
            top: 0;
            z-index: 1000;
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
            text-transform: uppercase;
            letter-spacing: 1px;
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
            font-weight: 500;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #f0f8ff;
        }

        .shop-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .shop-section h1 {
            font-size: 36px;
            color: #00c4b4;
            text-align: center;
            margin-bottom: 40px;
            text-transform: uppercase;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 230, 204, 0.2);
        }

        .product-card img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .product-card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-card p {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .product-card .price {
            font-size: 16px;
            font-weight: 600;
            color: #00c4b4;
            margin-bottom: 15px;
        }

        .product-card button {
            padding: 10px 20px;
            background: #00e6cc;
            color: #fff;
            border: none;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .product-card button:hover {
            background: #00c4b4;
            transform: translateY(-3px);
        }

        footer {
            background: #1a2a44;
            color: #fff;
            padding: 40px 20px;
            text-align: center;
        }

        footer p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        footer a {
            color: #00e6cc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #00c4b4;
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 20px;
            }

            nav ul {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }

        .search input {
            width: 200px;
            height: 40px;
            border-radius: 10px;
            border: 2px solid #000;
            padding: 10px 40px 10px 15px;
            font-size: 16px;
        }

        .header h1 {
            display: inline-block;
            margin: 0;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 500px;
            padding: 20px;
        }

        #txtHint {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #000;
            pointer-events: none;
        }

        .search {
            position: relative;
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
                    <li><a href="cart.php">Cart</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="shop-section">
        <div class="header">
            <h1>Shop Medicines</h1>
            <div class="search">
                <input type="text" name="search" id="search" onkeyup="showmedicine(this.value)" placeholder="Search Here">
                <span class="search-icon">🔍</span>
            </div>
        </div>
        <div id="txtHint"></div>
        <div class="products">
            <?php if (empty($products)): ?>
                <p>No products available.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['name']); ?> description</p>
                        <div class="price">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></div>
                        <button class="add-to-cart-btn" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>" data-price="<?php echo $product['price']; ?>" data-image="<?php echo htmlspecialchars($product['image']); ?>">Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <script>
        const csrfToken = '<?php echo $_SESSION['csrf_token']; ?>';

        function showmedicine(str) {
            const txtHint = document.getElementById("txtHint");
            if (str.length === 0) {
                txtHint.innerHTML = "";
                return;
            }
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        txtHint.innerHTML = this.responseText;
                    } else {
                        txtHint.innerHTML = "Error fetching results.";
                    }
                }
            };
            xmlhttp.open("GET", "medicine.php?q=" + encodeURIComponent(str), true);
            xmlhttp.send();
        }

        document.addEventListener("DOMContentLoaded", function () {
            const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");
            addToCartButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const productId = button.getAttribute('data-id');
                    const productName = button.getAttribute('data-name');
                    const productPrice = button.getAttribute('data-price');
                    const productImage = button.getAttribute('data-image');

                    const formData = new FormData();
                    formData.append('productId', productId);
                    formData.append('productName', productName);
                    formData.append('productPrice', productPrice);
                    formData.append('productImage', productImage);
                    formData.append('csrf_token', csrfToken);

                    fetch('addtocart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message);
                        if (data.type === 'success') {
                            window.location.href = 'cart.php';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while adding to cart: ' + error.message);
                    });

                    button.textContent = "Added!";
                    button.disabled = true;
                    setTimeout(() => {
                        button.textContent = "Add to Cart";
                        button.disabled = false;
                    }, 1500);
                });
            });
        });
    </script>

    <footer>
        <p>© 2025 MediKart. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="#privacy">Privacy Policy</a></p>
    </footer>
</body>
</html>