<?php
    $servername="localhost";
    $username="root";
    $password = "";
    $dbname = "ShriOnlineFurniture";

    $conn = mysqli_connect($servername,$username,$password,$dbname);

    $query = "CREATE TABLE screenshots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    screenshot_name VARCHAR(255) NOT NULL,
    screenshot_data LONGBLOB NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);"; 
if(mysqli_query($conn,$query)){
        echo "<script>alert('Data successfully enterd');</script>";
                }
?>