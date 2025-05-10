<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furniture";

$conn = mysqli_connect($servername, $username, $password, $dbname);
$query = "INSERT INTO categories (name) VALUES 
    ('ALL'),
    ('Kitchen'),
    ('Drawing Room')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Categories inserted successfully'); window.location.href = '/Furniture-website/home.html';</script>";
} else {
    echo "Error inserting categories: " . mysqli_error($conn);
}
?>
