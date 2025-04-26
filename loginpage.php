<?php
$uname_value = ''; // Default empty

// Only autofill if user just registered
if (isset($_COOKIE['just_registered']) && isset($_COOKIE['user_details'])) {
    $cookieData = json_decode($_COOKIE['user_details'], true);
    if (isset($cookieData['u_name'])) {
        $uname_value = htmlspecialchars($cookieData['u_name']);
    }
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "php");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = $_POST['uname'];
    $pwd = $_POST['pwd'];

    $sql = "SELECT Password FROM registerpage WHERE Username='$uname'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            if ($pwd === $row['Password']) {
                echo "<script>
                    alert('Login Successful');
                    window.location.href = 'home1.html';
                </script>";

                $details = [
                    'user_name'=> $uname,
                ];
                setcookie('details',json_encode($details),time()+3600,"/");
            } else {
                echo "<script>
                    alert('Incorrect Password');
                    window.location.href = 'loginpage.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('Username not found');
                window.location.href = 'loginpage.php';
            </script>";
        }
    } else {
        echo "Query Error: " . $conn->error;
    }
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