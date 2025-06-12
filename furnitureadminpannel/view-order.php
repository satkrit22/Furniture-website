<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Check if order ID is provided
if(!isset($_GET['id'])){
    header('location:orders.php');
    exit();
}

$order_id = $_GET['id'];

// Get order details
$query = "SELECT o.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          WHERE o.id = $order_id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0){
    header('location:orders.php');
    exit();
}

$order = mysqli_fetch_assoc($result);

// Get order items
$items_query = "SELECT oi.*, p.name as product_name, p.image as product_image, p.stock as current_stock
               FROM order_items oi 
               JOIN products p ON oi.product_id = p.id 
               WHERE oi.order_id = $order_id";
$items_result = mysqli_query($conn, $items_query);

// Handle status update
if(isset($_POST['update_status'])){
    $status = $_POST['status'];
    $old_status = $order['status'];
    
    // Update the order status
    $update_query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $order_id);
    
    if($stmt->execute()){
        // If order is cancelled, return items to stock
        if($status == 'cancelled' && $old_status != 'cancelled') {
            // Reset the items result pointer
            mysqli_data_seek($items_result, 0);
            
            while($item = mysqli_fetch_assoc($items_result)) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                
                // Return items to stock
                $updateStockQuery = "UPDATE products SET stock = stock + ? WHERE id = ?";
                $stockStmt = $conn->prepare($updateStockQuery);
                $stockStmt->bind_param("ii", $quantity, $product_id);
                $stockStmt->execute();
            }
            
            // Reset the items result pointer again for display
            mysqli_data_seek($items_result, 0);
        }
        
        $success = "Order status updated successfully";
        
        // Refresh order data
        $result = mysqli_query($conn, $query);
        $order = mysqli_fetch_assoc($result);
    } else {
        $error = "Error updating order status";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order | Admin Panel</title>
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

            <!-- View Order Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Order #<?php echo $order_id; ?> Details</h2>
                    <a href="orders.php" class="btn btn-primary">Back to Orders</a>
                </div>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="order-details">
                    <div class="card">
                        <div class="card-header">
                            <h3>Order Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="order-info">
                                <div class="order-info-item">
                                    <span class="label">Order ID:</span>
                                    <span class="value">#<?php echo $order['id']; ?></span>
                                </div>
                                <div class="order-info-item">
                                    <span class="label">Date:</span>
                                    <span class="value"><?php echo date('F d, Y h:i A', strtotime($order['created_at'])); ?></span>
                                </div>
                                <div class="order-info-item">
                                    <span class="label">Status:</span>
                                    <span class="value status <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
                                </div>
                                <div class="order-info-item">
                                    <span class="label">Payment Method:</span>
                                    <span class="value"><?php echo ucfirst($order['method']); ?></span>
                                </div>
                                <div class="order-info-item">
                                    <span class="label">Total Amount:</span>
                                    <span class="value">NPR.<?php echo number_format($order['total_price'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Customer Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="customer-info">
                                <div class="customer-info-item">
                                    <span class="label">Name:</span>
                                    <span class="value"><?php echo $order['customer_name']; ?></span>
                                </div>
                                <div class="customer-info-item">
                                    <span class="label">Email:</span>
                                    <span class="value"><?php echo $order['customer_email']; ?></span>
                                </div>
                                <div class="customer-info-item">
                                    <span class="label">Phone:</span>
                                    <span class="value"><?php echo $order['customer_phone']; ?></span>
                                </div>
                                <div class="customer-info-item">
                                    <span class="label">Shipping Address:</span>
                                    <span class="value"><?php echo $order['address']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Order Items</h3>
                        </div>
                        <div class="card-body">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Current Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    while($item = mysqli_fetch_assoc($items_result)){
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                        
                                        echo "<tr>";
                                        echo "<td class='product-cell'>";
                                        echo "<img src='/Furniture-website/images/" . $item['product_image'] . "' alt='" . $item['product_name'] . "' width='50'>";
                                        echo "<span>" . $item['product_name'] . "</span>";
                                        echo "</td>";
                                        echo "<td>NPR." . number_format($item['price'], 2) . "</td>";
                                        echo "<td>" . $item['quantity'] . "</td>";
                                        echo "<td>NPR." . number_format($subtotal, 2) . "</td>";
                                        echo "<td>" . $item['current_stock'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td><strong>NPR.<?php echo number_format($total, 2); ?></strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Update Order Status</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select id="status" name="status" class="form-select" required>
                                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group" style="margin-top: 24px;">
                                            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                        </div>
                                    </div>
                                </div>
                                <?php if($order['status'] != 'cancelled'): ?>
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Note: Cancelling an order will return all items to stock.
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <style>
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .order-details .card:nth-child(3),
        .order-details .card:nth-child(4) {
            grid-column: span 2;
        }
        
        .order-info,
        .customer-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .order-info-item,
        .customer-info-item {
            display: flex;
            flex-direction: column;
        }
        
        .label {
            font-size: 14px;
            color: var(--gray-color);
            margin-bottom: 5px;
        }
        
        .value {
            font-size: 16px;
            font-weight: 500;
        }
        
        .product-cell {
            display: flex;
            align-items: center;
        }
        
        .product-cell img {
            margin-right: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-top: 15px;
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
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        @media (max-width: 768px) {
            .order-details {
                grid-template-columns: 1fr;
            }
            
            .order-details .card:nth-child(3),
            .order-details .card:nth-child(4) {
                grid-column: span 1;
            }
        }
    </style>
</body>
</html>
