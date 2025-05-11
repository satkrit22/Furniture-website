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

// Process form submission
if(isset($_POST['submit'])){
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = sanitize($_POST['role']);
    
    // Check if email already exists
    $check_query = "SELECT * FROM admins WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0){
        $error = "Email already exists";
    } else {
        // Insert admin
        $query = "INSERT INTO admins (name, email, phone, password, role) VALUES ('$name', '$email', '$phone', '$password', '$role')";
        
        if(mysqli_query($conn, $query)){
            $success = "Admin user added successfully";
        } else {
            $error = "Error adding admin user: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin | Admin Panel</title>
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

            <!-- Add Admin Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Add New Admin</h2>
                    <a href="admin-users.php" class="btn btn-primary">Back to Admin Users</a>
                </div>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Admin Information</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" id="phone" name="phone" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <input type="password" id="password" name="password" class="form-control" required>
                                            <span onclick="togglePassword('password')" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select id="role" name="role" class="form-select" required>
                                    <option value="admin">Admin</option>
                                    <option value="editor">Editor</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                                <small class="form-text">
                                    <strong>Super Admin:</strong> Full access to all features including admin user management.<br>
                                    <strong>Admin:</strong> Access to all features except admin user management.<br>
                                    <strong>Editor:</strong> Can only view and edit content, cannot delete or add new items.
                                </small>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Add Admin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <style>
        .form-text {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: var(--gray-color);
            line-height: 1.5;
        }
    </style>
</body>
</html>