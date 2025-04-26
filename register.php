<?php
// Get form data
$uname = $_POST['uname'];
$pwd = $_POST['pwd'];
$phone = $_POST['phone'];
$email = $_POST['email'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Insert data into the registerpage table
$sql = "INSERT INTO registerpage (Username, Email, PhoneNo, Password) VALUES ('$uname', '$email', '$phone', '$pwd')";

if (mysqli_query($conn, $sql)) {
    // After inserting user data into the database
    $details = [
        'u_name' => $uname,
        'email' => $email,
    ];
    setcookie("user_details", json_encode($details), time() + 3600, "/"); // 1 hour valid
    setcookie("just_registered", "true", time() + 60, "/"); // only 1 minute valid

    echo "<script>
        alert('Registration Successful! Please log in.');
        window.location.href = 'loginpage.php';
    </script>";
} else {
    $error_message = mysqli_error($conn);
    echo "<script>
        alert('Error: " . addslashes($error_message) . "');
        window.location.href = 'register.html';
    </script>";
}

// Close connection
mysqli_close($conn);
?>
