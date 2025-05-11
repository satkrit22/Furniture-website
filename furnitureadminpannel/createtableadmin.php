<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furniture";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create admin table
$query = "CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    profile_image VARCHAR(255) DEFAULT 'default-avatar.png',
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'admin',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Admin table created successfully'); window.location.href = '/Furniture-website/admin/index.php'; </script>";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// Insert a default admin user
$admin_name = "Admin";
$admin_email = "admin@furniture.com";
// Password is "admin123" - hashed for security
$admin_password = password_hash("admin123", PASSWORD_DEFAULT);
$admin_phone = "1234567890";
$admin_role = "super_admin";

$insert_query = "INSERT INTO admins (name, email, password, phone, role) 
                VALUES ('$admin_name', '$admin_email', '$admin_password', '$admin_phone', '$admin_role')";

if (mysqli_query($conn, $insert_query)) {
    echo "<script>alert('Default admin user created successfully. Email: admin@furniture.com, Password: admin123'); </script>";
} else {
    echo "Error creating default admin: " . mysqli_error($conn);
}

mysqli_close($conn);
?>