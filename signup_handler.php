<?php
session_start();
$conn = new mysqli("localhost", "root", "", "securoguard_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if user already exists
$check = $conn->prepare("SELECT * FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();
if ($result->num_rows > 0) {
    echo "Email already exists.";
    exit();
}

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);
$stmt->execute();

// Store session
$_SESSION['user_id'] = $stmt->insert_id;
$_SESSION['email'] = $email;
$_SESSION['name'] = $name;

header("Location: index.php");
exit();
?>
