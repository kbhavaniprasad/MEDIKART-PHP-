<?php
require_once 'config.php';

$q = filter_var($_GET['q'] ?? '', FILTER_SANITIZE_STRING);
if (empty($q)) {
    echo "";
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo "Database connection failed.";
    exit;
}

$stmt = $conn->prepare("SELECT name FROM products WHERE name LIKE ?");
if ($stmt === false) {
    echo "Error preparing query.";
    $conn->close();
    exit;
}

$search = "%$q%";
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

$output = "";
while ($row = $result->fetch_assoc()) {
    $output .= "<p>" . htmlspecialchars($row['name']) . "</p>";
}

echo $output;

$stmt->close();
$conn->close();
?>