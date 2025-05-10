<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Change to your database password
$dbname = "furniture"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }
    
    // Prepare a select statement
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $email);
        
        // Execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
            
            // Check if email exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($id, $name, $email, $hashed_password);
                
                if ($stmt->fetch()) {
                    // Verify password
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["name"] = $name;
                        $_SESSION["email"] = $email;
                        
                        // Redirect to shop page
                        header("location: shop.php");
                        exit();
                    } else {
                        // Password is not valid
                        echo "Invalid password";
                    }
                }
            } else {
                // Email doesn't exist
                echo "No account found with that email";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        
        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>
