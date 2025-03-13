<?php
    $servername="localhost";
    $username="root";
    $password = "";
    $dbname = "ShriOnlineFurniture";

    $conn = mysqli_connect($servername,$username,$password,$dbname);

    $query = "CREATE TABLE users(
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name varchar(100) NOT NULL,,
    Email varchar(100) NOT NULL,
    password varchar(100) NOT NULL)"; 
if(mysqli_query($conn,$query)){
        echo "<script>alert('Data successfully enterd');</script>";
                }
?>