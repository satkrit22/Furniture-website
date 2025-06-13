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

// Get user orders
$orders_query = "SELECT * FROM orders WHERE user_id = $id ORDER BY created_at DESC LIMIT 5";
$orders_result = mysqli_query($conn, $orders_query);

// Get total orders
$id = intval($_GET['id']); // sanitizing input

$total_orders_query = "SELECT COUNT(*) as total, SUM(total_price) as total_spent FROM orders WHERE user_id = $id";
$total_orders_result = mysqli_query($conn, $total_orders_query);
if (!$total_orders_result) {
    die("Total Orders Query Failed: " . mysqli_error($conn));
}
$total_orders_data = mysqli_fetch_assoc($total_orders_result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User | Admin Panel</title>
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

            <!-- View User Content -->
            <div class="content">
                <div class="content-header">
                    <h2>User Details</h2>
                    <div class="action-buttons">
                        <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary">Edit User</a>
                        <a href="users.php" class="btn btn-secondary">Back to Users</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>User Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="user-details">
                            <div class="detail-row">
                                <div class="detail-label">ID:</div>
                                <div class="detail-value"><?php echo $user['id']; ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Name:</div>
                                <div class="detail-value"><?php echo $user['name']; ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Email:</div>
                                <div class="detail-value"><?php echo $user['email']; ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Phone:</div>
                                <div class="detail-value"><?php echo $user['phone']; ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Registered Date:</div>
                                <div class="detail-value"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Total Orders:</div>
                                <div class="detail-value"><?php echo $total_orders_data['total']; ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Total Spent:</div>
                                <div class="detail-value">NPR. <?php echo number_format($total_orders_data['total_spent'] ?? 0, 2); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h3>Recent Orders</h3>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($orders_result) > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                                    <?php echo $order['status']; ?>
                                                </span>
                                            </td>
                                            <td>NPR. <?php echo number_format($order['total_price'], 2); ?></td>
                                            <td>
                                                <a href="view-order.php?id=<?php echo $order['id']; ?>" class="action-icon view">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <div class="view-all-link">
                                <a href="orders.php?user_id=<?php echo $user['id']; ?>">View All Orders</a>
                            </div>
                        <?php else: ?>
                            <p class="no-data">No orders found for this user.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>