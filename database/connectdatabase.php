<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furniture";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

?>
