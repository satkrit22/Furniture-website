<?php
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
// Get data from POST request
$name = $_POST['name'];    // First name
$lname = $_POST['lname'];    // Last name
$email = $_POST['email'];    // Email
$password = $_POST['password']; // Password 
// SQL query to insert data into the 'users' table
$sql = "INSERT INTO users (FirstName, LastName, Email, password) 
        VALUES ('$fname', '$lname', '$email', '$password')";
// Execute the query
if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
    // You can redirect or display a success message
    header("Location: login.html"); // Redirect to login page after successful sign-up
    exit(); // Ensure that no further code is executed after the redirect
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
// Close connection
mysqli_close($conn);
?>
