<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Update order status
if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $query = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    
    if(mysqli_query($conn, $query)){
        $success = "Order status updated successfully";
    } else {
        $error = "Error updating order status: " . mysqli_error($conn);
    }
}

// Filter by status
$status_filter = "";
if(isset($_GET['status']) && $_GET['status'] != ""){
    $status = $_GET['status'];
    $status_filter = "WHERE status = '$status'";
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT o.*, u.name as customer_name 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          $status_filter 
          ORDER BY o.created_at DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $query);

// Get total records
$total_query = "SELECT COUNT(*) as total FROM orders $status_filter";
$total_result = mysqli_query($conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_data['total'] / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Admin Panel</title>
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

            <!-- Orders Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Orders Management</h2>
                </div>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header">
                        <div class="filter-container">
                            <form action="" method="GET" class="filter-form">
                                <div class="form-group">
                                    <label for="status">Filter by Status:</label>
                                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Orders</option>
                                        <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'selected' : ''; ?>>Processing</option>
                                        <option value="completed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(mysqli_num_rows($result) > 0){
                                    while($order = mysqli_fetch_assoc($result)){
                                        echo "<tr>";
                                        echo "<td>#" . $order['id'] . "</td>";
                                        echo "<td>" . $order['customer_name'] . "</td>";
                                        echo "<td>NPR." . number_format($order['total_price'], 2) . "</td>";
                                        echo "<td><span class='status " . $order['status'] . "'>" . ucfirst($order['status']) . "</span></td>";
                                        echo "<td>" . date('M d, Y', strtotime($order['created_at'])) . "</td>";
                                        echo "<td class='table-actions'>";
                                        echo "<a href='view-order.php?id=" . $order['id'] . "' class='action-icon view'><i class='fas fa-eye'></i></a>";
                                        echo "<a href='#' class='action-icon edit' onclick='openStatusModal(" . $order['id'] . ", \"" . $order['status'] . "\")'><i class='fas fa-edit'></i></a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='no-data'>No orders found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <?php if($total_pages > 1): ?>
                            <div class="pagination">
                                <?php if($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>"><i class="fas fa-chevron-left"></i></a>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                                <?php endfor; ?>
                                
                                <?php if($page < $total_pages): ?>
                                    <a href="?page=<?php echo $page + 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?>"><i class="fas fa-chevron-right"></i></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Update Modal -->
    <div id="status-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Update Order Status</h3>
            <form action="" method="POST">
                <input type="hidden" id="order_id" name="order_id">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status_select" name="status" class="form-select" required>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
        // Status Modal
        const modal = document.getElementById('status-modal');
        const closeBtn = document.getElementsByClassName('close')[0];
        
        function openStatusModal(orderId, status) {
            document.getElementById('order_id').value = orderId;
            document.getElementById('status_select').value = status;
            modal.style.display = 'block';
        }
        
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
    </style>
</body>
</html>