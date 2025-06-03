<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Get admin info
$query = "SELECT * FROM users WHERE id = $admin_id";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

// Process form submission
if(isset($_POST['update_profile'])){
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    
    // Check if email already exists
    $check_query = "SELECT * FROM users WHERE email = '$email' AND id != $admin_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0){
        $error = "Email already exists";
    } else {
        // Update profile
        $query = "UPDATE users SET name = '$name', email = '$email', phone = '$phone' WHERE id = $admin_id";
        
        if(mysqli_query($conn, $query)){
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_email'] = $email;
            $success = "Profile updated successfully";
        } else {
            $error = "Error updating profile: " . mysqli_error($conn);
        }
    }
}

// Process password change
if(isset($_POST['change_password'])){
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    if(!password_verify($current_password, $admin['password'])){
        $password_error = "Current password is incorrect";
    } else if($new_password != $confirm_password){
        $password_error = "New passwords do not match";
    } else {
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = '$hashed_password' WHERE id = $admin_id";
        
        if(mysqli_query($conn, $query)){
            $password_success = "Password changed successfully";
        } else {
            $password_error = "Error changing password: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Admin Panel</title>
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

            <!-- Settings Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Settings</h2>
                </div>
                
                <div class="settings-container">
                    <div class="card">
                        <div class="card-header">
                            <h3>Profile Settings</h3>
                        </div>
                        <div class="card-body">
                            <?php if(isset($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php endif; ?>
                            
                            <?php if(isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <form action="" method="POST">
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <input type="text" id="name" name="name" class="form-control" value="<?php echo $admin['name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" id="email" name="email" class="form-control" value="<?php echo $admin['email']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" id="phone" name="phone" class="form-control" value="<?php echo $admin['phone']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Change Password</h3>
                        </div>
                        <div class="card-body">
                            <?php if(isset($password_success)): ?>
                                <div class="alert alert-success"><?php echo $password_success; ?></div>
                            <?php endif; ?>
                            
                            <?php if(isset($password_error)): ?>
                                <div class="alert alert-danger"><?php echo $password_error; ?></div>
                            <?php endif; ?>
                            
                            <form action="" method="POST">
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                                        <span onclick="togglePassword('current_password')" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <div class="input-group">
                                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                                                <span onclick="togglePassword('new_password')" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm New Password</label>
                                            <div class="input-group">
                                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                                <span onclick="togglePassword('confirm_password')" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>