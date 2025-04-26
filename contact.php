<?php
if (isset($_COOKIE['login_details'])) {
    $user = json_decode($_COOKIE['login_details'], true);
    $conn = mysqli_connect("localhost", "root", "", "php");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT Email FROM registerpage WHERE Username='" . $user['user_name'] . "'";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $email = $row['Email'];
    } else {
        $email = ""; // fallback if no result
    }
} else {
    $user['user_name'] = ""; // fallback if no cookie
    $email = "";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact MediKart</title>
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

        .contact-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .contact-info h1 {
            font-size: 36px;
            color: #00c4b4;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .contact-info p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .contact-info .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .contact-info .info-item .icon {
            font-size: 20px;
            color: #00c4b4;
        }

        .contact-form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .contact-form h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: #00c4b4;
        }

        .contact-form button {
            padding: 12px 30px;
            background: #00e6cc;
            color: #fff;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .contact-form button:hover {
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

            .contact-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo">MediKart</div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="shop.html">Shop</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="logout.php">LogOut</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="contact-section">
        <div class="contact-info">
            <h1>Contact Us</h1>
            <p>We're here to assist you with your medicine delivery needs. Reach out to us anytime!</p>
            <div class="info-item">
                <span class="icon">📧</span>
                <p>support@medikart.com</p>
            </div>
            <div class="info-item">
                <span class="icon">📞</span>
                <p>+1-800-MEDI-KART</p>
            </div>
            <div class="info-item">
                <span class="icon">📍</span>
                <p>123 Health St, Wellness City, USA</p>
            </div>
        </div>
        <div class="contact-form">
            <h2>Send a Message</h2>
            <form action="contact.php" method="POST">
                <input type="text" name="name" placeholder="Your Name" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
                <input type="email" name="email" placeholder="Your Email" value="<?php echo htmlspecialchars($email); ?>" required>
                <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                <button type="submit">Send</button>
            </form>
        </div>
    </section>

    <footer>
        <p>© 2025 MediKart. All rights reserved.</p>
        <p><a href="contact.html">Contact Us</a> | <a href="#privacy">Privacy Policy</a></p>
    </footer>
</body>
</html>