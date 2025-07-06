<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['url'])) {
    $badgeText = null;
    $url = $_POST['url'];
    $escaped_url = escapeshellarg($url);

    $command = "python code-files/app2.py $escaped_url 2>&1";
    $output = shell_exec($command);

    // Log raw output for debugging
    file_put_contents('debug_log.txt', "===OUTPUT===\n" . $output . "\n===END===\n");

    if ($output === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to execute Python script.'
        ]);
        exit();
    }

    // Clean output: extract the JSON from noisy output if needed
    $output = trim($output);
    preg_match('/\{.*\}/s', $output, $matches);
    if (isset($matches[0])) {
        $jsonString = $matches[0];
        $result = json_decode($jsonString, true);
    } else {
        $result = null;
    }

    if (!$result || !isset($result['avg_fake_confidence'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid response from Python script.',
            'debug' => $output
        ]);
        exit();
    }

    // BADGE LOGIC (optional, you can use label from Python if available)
    if ($result['avg_fake_confidence'] >= 0 && $result['avg_fake_confidence'] <= 40) {
        $badgeText = 'Likely Fake Review';
    } else if ($result['avg_fake_confidence'] > 40 && $result['avg_fake_confidence'] <= 70) {
        $badgeText = 'Suspicious Review';
    } else if ($result['avg_fake_confidence'] > 70 && $result['avg_fake_confidence'] <= 100) {
        $badgeText = 'Likely Real Review';
    } else {
        $badgeText = 'Unknown Confidence';
    }

    // --- Save to database ---
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'securoguard_db';
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed.'
        ]);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $label = $badgeText;
    $confidence = $result['avg_fake_confidence'];

    // $stmt = $conn->prepare("INSERT INTO search_history (user_id, url, label, confidence, created_at) VALUES (?, ?, ?, ?, NOW())");
    // $stmt->bind_param("isss", $user_id, $url, $label, $confidence);
    // $stmt->execute();
    // $stmt->close();
    // $conn->close();

    $stmt = $conn->prepare(
        'INSERT INTO search_history
   (user_id, url, label, confidence, total_reviews, fake_reviews, avg_rating, created_at)
   VALUES (?,?,?,?,?,?,?,NOW())'
    );
    $stmt->bind_param(
        'isssiii',
        $user_id,
        $url,
        $label,
        $confidence,
        $result['total_reviews'],
        $result['fake_reviews'],
        $result['avg_rating']
    );
    $stmt->execute();
    $history_id = $stmt->insert_id;  // 👈 naye report ka id frontend ko bhejne ke liye
    $stmt->close();

    // --- Merge all fields from Python result with your custom status ---
    $response = array_merge(
        ['status' => 'success'],
        $result  // this includes ALL fields returned by Python!
    );
    // Optionally, overwrite label with PHP badge (if you want)
    $response['label'] = $label;

    echo json_encode($response);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request.'
    ]);
}
?>
