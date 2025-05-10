<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furniture";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$query = "CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    product_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Cart table created successfully'); window.location.href = '/Furniture-website/home.html'; </script>";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
?>
