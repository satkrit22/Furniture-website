<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ShriOnlineFurniture";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if file is uploaded
if (isset($_FILES['payment_screenshot']) && $_FILES['payment_screenshot']['error'] == 0) {
    $fileName = $_FILES['payment_screenshot']['name'];
    $fileTmpName = $_FILES['payment_screenshot']['tmp_name'];
    $fileType = $_FILES['payment_screenshot']['type'];
    $fileContent = file_get_contents($fileTmpName); // Read file content

    // Insert file into the database
    $sql = "INSERT INTO screenshots (screenshot_name, screenshot_data) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sb", $fileName, $fileContent); // "s" for string, "b" for blob
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "File uploaded successfully!";
    } else {
        echo "Error uploading file!";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No file uploaded or error during upload.";
}
?>
