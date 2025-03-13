<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seedtoseason";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

// Check if the form is submitted for login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query to check if the email and password exist in the database
    $sql = "SELECT Email, password FROM users WHERE Email = ? AND password =?";
    $stmt = $conn->prepare($sql);  // Prepare the SQL statement
    $stmt->bind_param("ss", $email, $password);  // Bind email and password parameters
    $stmt->execute();  // Execute the query
    $result = $stmt->get_result();  // Get the result set

    if ($result->num_rows > 0) {
        // If email and password match, store the session
        $d = $result->fetch_assoc();
        $_SESSION['email'] = $d['email'];  // Store email in session

        // Redirect to products page
        header('Location: products.html');
        exit();
    } else {
        // If email or password doesn't match, redirect to signup page
        echo "<script>alert('Email or Password is incorrect. Please try again.');</script>";
        header('Location: signup.html');
        exit();
    }

    $stmt->close();
}

$conn->close();
?>