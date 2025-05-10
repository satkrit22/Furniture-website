<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furniture";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$query = "CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price INT NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
)";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Products table created successfully'); window.location.href = '/Furniture-website/home.html';</script>";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
?>
