<?php
session_start();
require_once 'config.php';

$uname_value = '';
if (isset($_COOKIE['just_registered']) && isset($_COOKIE['user_details'])) {
    $cookieData = json_decode($_COOKIE['user_details'], true);
    if (isset($cookieData['u_name'])) {
        $uname_value = htmlspecialchars($cookieData['u_name']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = filter_var($_POST['uname'] ?? '', FILTER_SANITIZE_STRING);
    $pwd = $_POST['pwd'] ?? '';

    if (!$uname || !$pwd) {
        echo "<script>alert('All fields are required.'); window.location.href = 'loginpage.php';</script>";
        exit;
    }

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        echo "<script>alert('Database connection failed: " . addslashes($conn->connect_error) . "'); window.location.href = 'loginpage.php';</script>";
        exit;
    }

    // Verify user credentials
    $stmt = $conn->prepare("SELECT username, email, password FROM registerpage WHERE username = ?");
    if ($stmt === false) {
        echo "<script>alert('Prepare failed: " . addslashes($conn->error) . "'); window.location.href = 'loginpage.php';</script>";
        $conn->close();
        exit;
    }
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($pwd, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['cart'] = [];

        // Load cart items
        $stmt = $conn->prepare("SELECT product_id, name, price, image, quantity FROM cart_items WHERE user = ?");
        if ($stmt === false) {
            // Log the error and continue without cart items
            error_log("Prepare failed for cart_items: " . $conn->error);
            // Optionally, initialize an empty cart and proceed
        } else {
            $stmt->bind_param("s", $uname);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($item = $result->fetch_assoc()) {
                $_SESSION['cart'][] = [
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                    'quantity' => $item['quantity'],
                    'user' => $uname,
                    'email' => $user['email']
                ];
            }
            $stmt->close();
        }

        // Set cookie for compatibility
        $details = ['user_name' => $uname];
        setcookie('details', json_encode($details), time() + 3600, '/');
        echo "<script>alert('Login Successful'); window.location.href = 'shop.php';</script>";
    } else {
        echo "<script>alert('Invalid username or password.'); window.location.href = 'loginpage.php';</script>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Login Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(45deg, #1a1a2e, #16213e);
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 400px;
        }

        .login-container h2 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .input-group {
            position: relative;
            margin-bottom: 30px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 25px;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .input-group label {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .input-group input:focus,
        .input-group input:valid {
            background: rgba(255, 255, 255, 0.3);
            outline: none;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        .input-group input:focus + label,
        .input-group input:valid + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #00d4ff;
            background: #16213e;
            padding: 0 5px;
            border-radius: 5px;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #00d4ff;
            border: none;
            border-radius: 25px;
            color: #16213e;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.4);
            background: #00c4ef;
        }

        .login-btn:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #00d4ff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="loginpage.php" method="POST">
            <div class="input-group">
                <input type="text" name="uname" value="<?php echo $uname_value; ?>"  required>
                <label>Username</label>
            </div>
            <div class="input-group">
                <input type="password" name="pwd" maxlength="8" required>
                <label>Password</label>
            </div>
            <button type="submit" class="login-btn">Sign In</button>
            <div class="forgot-password">
                <a href="register.html">Need an account? Register</a>
            </div>
        </form>
    </div>

   
    <script>
        window.onload = function() {
            const unameField = document.querySelector('input[name="uname"]');
            const pwdField = document.querySelector('input[name="pwd"]');
            if (unameField.value !== '') {
                pwdField.focus();
            }
        };
    </script>
</body>
</html>