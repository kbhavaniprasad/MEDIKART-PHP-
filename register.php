<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = filter_var($_POST['uname'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_STRING);
    $pwd = $_POST['pwd'] ?? '';

    if (!$uname || !$email || !$phone || !$pwd) {
        echo "<script>alert('All fields are required.'); window.location.href = 'register.html';</script>";
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href = 'register.html';</script>";
        exit;
    }

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        echo "<script>alert('Database connection failed: " . addslashes($conn->connect_error) . "'); window.location.href = 'register.html';</script>";
        exit;
    }

    // Check for existing username or email
    $stmt = $conn->prepare("SELECT username FROM registerpage WHERE username = ? OR email = ?");
    if ($stmt === false) {
        echo "<script>alert('Prepare failed: " . addslashes($conn->error) . "'); window.location.href = 'register.html';</script>";
        $conn->close();
        exit;
    }
    $stmt->bind_param("ss", $uname, $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        echo "<script>alert('Username or email already exists.'); window.location.href = 'register.html';</script>";
        $conn->close();
        exit;
    }
    $stmt->close();

    // Insert new user
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO registerpage (username, email, phone, password) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        echo "<script>alert('Prepare failed: " . addslashes($conn->error) . "'); window.location.href = 'register.html';</script>";
        $conn->close();
        exit;
    }
    $stmt->bind_param("ssss", $uname, $email, $phone, $hashedPwd);
    if ($stmt->execute()) {
        $details = ['u_name' => $uname, 'email' => $email];
        setcookie('user_details', json_encode($details), time() + 3600, '/');
        setcookie('just_registered', 'true', time() + 60, '/');
        echo "<script>alert('Registration Successful! Please log in.'); window.location.href = 'loginpage.php';</script>";
    } else {
        echo "<script>alert('Error: " . addslashes($stmt->error) . "'); window.location.href = 'register.html';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>