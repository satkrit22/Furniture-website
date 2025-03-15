<?php
    $servername="localhost";
    $username="root";
    $password = "";
    $dbname = "ShriOnlineFurniture";

    $conn = mysqli_connect($servername,$username,$password,$dbname);

    $query = "INSERT INTO admins (username, password_hash)
VALUES ('admin', '<?= password_hash('your_secure_password', PASSWORD_DEFAULT) ?>')"; 
if(mysqli_query($conn,$query)){
        echo "<script>alert('Data successfully enterd');</script>";
                }
?>