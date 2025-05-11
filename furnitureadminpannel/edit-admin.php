<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Check if user is super_admin
if($_SESSION['admin_role'] !== 'super_admin'){
    header('location:index.php');
    exit();
}

// Check if ID is provided
if(!isset($_GET['id'])){
    header('location:admin-users.php');
    exit();
}

$admin_id = $_GET['id'];

// Prevent editing yourself through this page
if($admin_id == $_SESSION['admin_id']){
    header('location:settings.php');
    exit();
}

// Get admin data
$query = "SELECT * FROM admins WHERE id = $admin_id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0){
    header('location:admin-users.php');
    exit();
}

$admin = mysqli_fetch_assoc($result);

// Process form submission
if(isset($_POST['submit'])){
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $role = sanitize($_POST['role']);
    
    // Check if email already exists
    $check_query = "SELECT * FROM admins WHERE email = '$email' AND id != $admin_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0){
        $error = "Email already exists";
    } else {
        // Update admin
        $query = "UPDATE admins SET name = '$name', email = '$email', phone = '$phone', role = '$role' WHERE id = $admin_id";
        
        if(mysqli_query($conn, $query)){
            $success = "Admin user updated successfully";
            
            // Refresh admin data
            $query = "SELECT * FROM admins WHERE id = $admin_id";
            $result = mysqli_query($conn, $query);
            $admin = mysqli_fetch_assoc($result);
        } else {
            $error = "Error updating admin user: " . mysqli_error($conn);
        }
    }
}

// Process password change
if(isset($_POST['change_password'])){
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    // Update password
    $query = "UPDATE admins SET password = '$new_password' WHERE id = $admin_id";
    
    if(mysqli_query($conn, $query)){
        $password_success = "Password changed successfully";
    } else {
        $password_error = "Error changing password: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin | Admin Panel</title>
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

            <!-- Edit Admin Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Edit Admin: <?php echo $admin['name']; ?></h2>
                    <a href="admin-users.php" class="btn btn-primary">Back to Admin Users</a>
                </div>
                
                <div class="admin-edit-container">
                    <div class="card">
                        <div class="card-header">
                            <h3>Admin Information</h3>
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
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select id="role" name="role" class="form-select" required>
                                                <option value="admin" <?php echo $admin['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                <option value="editor" <?php echo $admin['role'] == 'editor' ? 'selected' : ''; ?>>Editor</option>
                                                <option value="super_admin" <?php echo $admin['role'] == 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-primary">Update Admin</button>
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
                                    <button type="submit" name="change_password" class="btn btn-primary" onclick="return validatePassword()">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
        function validatePassword() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                alert('Passwords do not match');
                return false;
            }
            
            return true;
        }
    </script>
    <style>
        .admin-edit-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        @media (min-width: 768px) {
            .admin-edit-container {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</body>
</html>