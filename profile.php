<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "furniture"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Get user information
$user_id = $_SESSION['id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    
    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    // If no errors, update profile
    if (empty($errors)) {
        $query = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $phone, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['profile_success'] = "Profile updated successfully!";
            header("Location: profile.php");
            exit;
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    $errors = [];
    
    if (empty($current_password)) {
        $errors[] = "Current password is required";
    }
    
    if (empty($new_password)) {
        $errors[] = "New password is required";
    } elseif (strlen($new_password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($new_password != $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // If no errors, verify current password and update
    if (empty($errors)) {
        // Verify current password
        $query = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        
        if (password_verify($current_password, $user_data['password'])) {
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password
            $query = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $hashed_password, $user_id);
            
            if ($stmt->execute()) {
                $_SESSION['password_success'] = "Password changed successfully!";
                header("Location: profile.php");
                exit;
            } else {
                $errors[] = "Something went wrong. Please try again.";
            }
        } else {
            $errors[] = "Current password is incorrect";
        }
    }
}

// Get user orders
$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
$orders = [];

while ($row = $orders_result->fetch_assoc()) {
    $orders[] = $row;
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shri Online Furniture - Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #4e4e4e;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar img {
            height: 80px;
        }
        
        .navbar ul {
            display: flex;
            list-style: none;
            gap: 20px;
            margin-right: 20px;
        }
        
        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .navbar ul li a .icon {
            margin-right: 8px;
        }
        
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            font-size: 12px;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .section-title h2 {
            font-size: 32px;
            color: #4e4e4e;
            margin-bottom: 10px;
        }
        
        .section-title .lead {
            font-size: 18px;
            color: #666;
        }
        
        .section-title .line {
            height: 3px;
            width: 100px;
            background-color: rgb(225, 142, 49);
            margin: 15px auto;
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        
        .profile-sidebar {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            overflow: hidden;
        }
        
        .profile-image i {
            font-size: 80px;
            color: #ccc;
        }
        
        .profile-info {
            text-align: center;
        }
        
        .profile-info h3 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #4e4e4e;
        }
        
        .profile-info p {
            color: #666;
            margin-bottom: 5px;
        }
        
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 20px;
            text-align: center;
        }
        
        .stat-item {
            background-color: #f8f8f8;
            padding: 15px;
            border-radius: 8px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: rgb(225, 142, 49);
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        
        .profile-content {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .profile-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
        }
        
        .profile-tab {
            padding: 15px 20px;
            cursor: pointer;
            color: #666;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .profile-tab.active {
            color: rgb(225, 142, 49);
            border-bottom: 3px solid rgb(225, 142, 49);
        }
        
        .profile-tab:hover {
            background-color: #f8f8f8;
        }
        
        .tab-content {
            padding: 20px;
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #4e4e4e;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: rgb(225, 142, 49);
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .btn-primary {
            background-color: rgb(225, 142, 49);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: rgb(156, 97, 42);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .order-card {
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .order-header {
            background-color: #f8f8f8;
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-id {
            font-weight: bold;
            color: #4e4e4e;
        }
        
        .order-date {
            color: #666;
            font-size: 14px;
        }
        
        .order-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .order-body {
            padding: 15px;
        }
        
        .order-products {
            margin-bottom: 15px;
        }
        
        .order-total {
            font-weight: bold;
            color: #4e4e4e;
            font-size: 18px;
            text-align: right;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        .no-orders {
            text-align: center;
            padding: 30px;
            color: #666;
        }
        
        .no-orders i {
            font-size: 50px;
            color: #ddd;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            
            .navbar ul {
                gap: 10px;
                margin-right: 0;
            }
            
            .navbar ul li a {
                font-size: 14px;
            }
            
            .navbar ul li a .icon {
                margin-right: 4px;
            }
            
            .profile-tabs {
                flex-wrap: wrap;
            }
            
            .profile-tab {
                flex: 1 0 50%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a href="shop.php">
        <img src="images/shri_online_furniture-removebg-preview.png" alt="Logo">
    </a>
    <ul>
        <li><a href="home.html" class="nav-link"><i class="fas fa-home icon"></i>Home</a></li>
        <li><a href="shop.php" class="nav-link"><i class="fas fa-store icon"></i>Shop</a></li>
        <li><a href="profile.php" class="nav-link"><i class="fas fa-user icon"></i>Profile</a></li>
        <li><a href="cart.php" class="nav-link"><i class="fas fa-shopping-cart icon"></i>Cart<span class="cart-count"><?php echo count($_SESSION['cart']); ?></span></a></li>
        <li><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt icon"></i>Logout</a></li>
    </ul>
</nav>

<div class="container">
    <div class="section-title">
        <h2>My Profile</h2>
        <p class="lead">Manage your account and view your orders</p>
        <div class="line"></div>
    </div>
    
    <?php if (isset($_SESSION['profile_success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['profile_success']; ?>
            <?php unset($_SESSION['profile_success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['password_success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['password_success']; ?>
            <?php unset($_SESSION['password_success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['order_success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['order_success']; ?>
            <?php unset($_SESSION['order_success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="profile-grid">
        <div class="profile-sidebar">
            <div class="profile-image">
                <i class="fas fa-user"></i>
            </div>
            
            <div class="profile-info">
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
                <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($user['phone']); ?></p>
            </div>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($orders); ?></div>
                    <div class="stat-label">Orders</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo date('d M Y', strtotime($user['created_at'])); ?></div>
                    <div class="stat-label">Joined</div>
                </div>
            </div>
        </div>
        
        <div class="profile-content">
            <div class="profile-tabs">
                <div class="profile-tab active" data-tab="profile">Profile Information</div>
                <div class="profile-tab" data-tab="password">Change Password</div>
                <div class="profile-tab" data-tab="orders">My Orders</div>
            </div>
            
            <div class="tab-content active" id="profile">
                <form method="post" action="profile.php">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                                        
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
            
            <div class="tab-content" id="password">
                <form method="post" action="profile.php">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </form>
            </div>
            
            <div class="tab-content" id="orders">
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">Order #<?php echo $order['id']; ?></span>
                                <span class="order-date"><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></span>
                                <span class="order-status status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
                            </div>
                            
                            <div class="order-body">
                                <div class="order-products">
                                    <?php echo nl2br(htmlspecialchars($order['total_products'])); ?>
                                </div>
                                
                                <div class="order-total">
                                    Total: NRP <?php echo number_format($order['total_price']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-orders">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>No orders yet</h3>
                        <p>You haven't placed any orders yet.</p>
                        <a href="shop.php" class="btn btn-primary">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.profile-tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // Auto-hide alerts after 3 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 3000);
    });
</script>

</body>
</html>