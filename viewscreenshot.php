<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "payment_screenshots";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the screenshot data from the database
$sql = "SELECT screenshot_name, screenshot_data FROM screenshots WHERE id = 1"; // Example: fetching screenshot with ID = 1
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the file data
    $row = $result->fetch_assoc();
    $fileName = $row['screenshot_name'];
    $fileData = $row['screenshot_data'];

    // Set headers to display image
    header("Content-Type: image/jpeg"); // Adjust content type based on your file type
    echo $fileData;
} else {
    echo "No file found!";
}

$conn->close();
?>
