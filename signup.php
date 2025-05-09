<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change to your database username
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
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirm_password = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";
    
    // Validate form data
    $errors = [];
    
    // Validate name (only letters, spaces, and hyphens)
    if (!preg_match("/^[a-zA-Z\s-]+$/", $name)) {
        $errors[] = "Name should contain only letters, spaces, and hyphens.";
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    // Validate phone (10 digits)
    if (!preg_match("/^\d{10}$/", $phone)) {
        $errors[] = "Phone number should be 10 digits.";
    }
    
    // Validate password (at least 8 characters)
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    
    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Check if email already exists
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            echo "Email already exists. Please use a different email or login.";
            $check_stmt->close();
        } else {
            $check_stmt->close();
            
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Prepare an insert statement
            $sql = "INSERT INTO users (name, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())";
            
            if ($stmt = $conn->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
                
                // Execute the prepared statement
                if ($stmt->execute()) {
                    // Redirect to login page
                    header("location: login.php?registered=true");
                    exit();
                } else {
                    echo "Something went wrong. Please try again later.";
                }
                
                // Close statement
                $stmt->close();
            }
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}

// Close connection
$conn->close();
?>