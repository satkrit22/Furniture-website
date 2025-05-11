<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])){
    header('location:users.php');
    exit();
}

$id = $_GET['id'];

// Get user data
$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0){
    header('location:users.php');
    exit();
}

$user = mysqli_fetch_assoc($result);

// Update user
if(isset($_POST['update_user'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Check if email already exists
    $email_check = "SELECT * FROM users WHERE email = '$email' AND id != $id";
    $email_result = mysqli_query($conn, $email_check);
    
    if(mysqli_num_rows($email_result) > 0){
        $error = "Email already exists. Please use a different email.";
    } else {
        // Update password if provided
        $password_query = "";
        if(!empty($_POST['password'])){
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $password_query = ", password = '$password'";
        }
        
        $update_query = "UPDATE users SET 
                        name = '$name', 
                        email = '$email', 
                        phone = '$phone'
                        $password_query
                        WHERE id = $id";
        
        if(mysqli_query($conn, $update_query)){
            $success = "User updated successfully";
            
            // Refresh user data
            $query = "SELECT * FROM users WHERE id = $id";
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);
        } else {
            $error = "Error updating user: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User | Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <?php include 'includes/header.php'; ?>

            <!-- Edit User Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Edit User</h2>
                    <a href="users.php" class="btn btn-secondary">Back to Users</a>
                </div>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo $user['phone']; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Password (Leave blank to keep current password)</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="created_at">Registered Date</label>
                                <input type="text" id="created_at" class="form-control" value="<?php echo date('M d, Y', strtotime($user['created_at'])); ?>" readonly>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>