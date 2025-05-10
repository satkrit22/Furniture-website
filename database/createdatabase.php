<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password);
$sql = "CREATE DATABASE furniture";
$result=mysqli_query($conn,$sql);
if(!$result) {
  echo "Database error";
} else {
  echo " creating database: ";
}
?>