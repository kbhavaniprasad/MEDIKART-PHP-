<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['type' => 'error', 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['type' => 'error', 'message' => 'Invalid CSRF token']);
    exit;
}

if (!isset($_SESSION['user'])) {
    echo json_encode(['type' => 'error', 'message' => 'You must be logged in']);
    exit;
}

$productId = filter_var($_POST['productId'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$productName = filter_var($_POST['productName'] ?? '', FILTER_SANITIZE_STRING);
$productPrice = filter_var($_POST['productPrice'] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$productImage = filter_var($_POST['productImage'] ?? '', FILTER_SANITIZE_URL);
$user = $_SESSION['user'];

if (!$productId || !$productName || !$productPrice || !$productImage) {
    echo json_encode(['type' => 'error', 'message' => 'Missing product details']);
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['type' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Check if product is already in cart
$stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE user = ? AND product_id = ?");
if ($stmt === false) {
    error_log("Prepare failed: " . $conn->error);
    echo json_encode(['type' => 'error', 'message' => 'Database query preparation failed']);
    $conn->close();
    exit;
}
$stmt->bind_param("si", $user, $productId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    // Update quantity in database
    $newQuantity = $row['quantity'] + 1;
    $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        echo json_encode(['type' => 'error', 'message' => 'Database query preparation failed']);
        $conn->close();
        exit;
    }
    $stmt->bind_param("ii", $newQuantity, $row['id']);
    // Update $_SESSION['cart']
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId && $item['user'] == $user) {
            $item['quantity'] = $newQuantity;
            break;
        }
    }
} else {
    // Insert new item into database
    $stmt = $conn->prepare("INSERT INTO cart_items (user, product_id, name, price, image, quantity) VALUES (?, ?, ?, ?, ?, 1)");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        echo json_encode(['type' => 'error', 'message' => 'Database query preparation failed']);
        $conn->close();
        exit;
    }
    $stmt->bind_param("sisds", $user, $productId, $productName, $productPrice, $productImage);
    // Add to $_SESSION['cart']
    $_SESSION['cart'][] = [
        'product_id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'image' => $productImage,
        'quantity' => 1,
        'user' => $user,
        'email' => $_SESSION['email'] ?? ''
    ];
}

if ($stmt->execute()) {
    echo json_encode(['type' => 'success', 'message' => 'Product added to cart']);
} else {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['type' => 'error', 'message' => 'Failed to add to cart: ' . addslashes($stmt->error)]);
}

$stmt->close();
$conn->close();
?>