<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furniture";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    number VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    method VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    total_products TEXT NOT NULL,
    total_price INT NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB;";

if ($conn->query($query) === TRUE) {
    echo "<script>alert('Orders table created successfully'); window.location.href = '/Furniture-website/home.html';</script>";
} else {
    echo "Error creating orders table: " . $conn->error;
}

$conn->close();
?>
