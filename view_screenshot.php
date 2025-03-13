<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "payment_screenshots";

// Get screenshot ID from URL parameter
$id = $_GET['id'] ?? null;

if ($id === null) {
    die("Screenshot ID is required.");
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the screenshot data from the database
$sql = "SELECT screenshot_data, screenshot_name FROM screenshots WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($screenshotData, $screenshotName);
$stmt->fetch();

if ($screenshotData) {
    // Set headers to display image
    header("Content-Type: image/jpeg");
    echo $screenshotData;
} else {
    echo "No screenshot found!";
}

$stmt->close();
$conn->close();
?>
