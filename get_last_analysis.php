<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "securoguard_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Optional: To filter by product URL if provided as a query param
$url = isset($_GET['url']) ? $_GET['url'] : null;
if ($url) {
    $stmt = $conn->prepare("SELECT * FROM search_history WHERE user_id = ? AND url = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("is", $user_id, $url);
} else {
    $stmt = $conn->prepare("SELECT * FROM search_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();
$conn->close();

if ($data) {
    echo json_encode(["status" => "success"] + $data);
} else {
    echo json_encode(["status" => "error", "message" => "No analysis found"]);
}
?>
