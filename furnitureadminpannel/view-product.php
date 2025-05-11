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
    header('location:products.php');
    exit();
}

$id = $_GET['id'];

// Get product data with category name
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          WHERE p.id = $id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0){
    header('location:products.php');
    exit();
}

$product = mysqli_fetch_assoc($result);

// Get order items for this product
$order_items_query = "SELECT oi.*, o.id as order_id, o.status, o.created_at 
                     FROM order_items oi 
                     JOIN orders o ON oi.order_id = o.id 
                     WHERE oi.product_id = $id 
                     ORDER BY o.created_at DESC 
                     LIMIT 5";
$order_items_result = mysqli_query($conn, $order_items_query);

// Get total sales
$total_sales_query = "SELECT SUM(quantity) as total_quantity, SUM(price * quantity) as total_sales 
                      FROM order_items 
                      WHERE product_id = $id";
$total_sales_result = mysqli_query($conn, $total_sales_query);
$total_sales_data = mysqli_fetch_assoc($total_sales_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product | Admin Panel</title>
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

            <!-- View Product Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Product Details</h2>
                    <div class="action-buttons">
                        <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Edit Product</a>
                        <a href="products.php" class="btn btn-secondary">Back to Products</a>
                    </div>
                </div>
                
                <div class="product-view">
                    <div class="product-image">
                        <img src="/Furniture-website/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="width: 100px; height: auto;">
  </div>
                    
                    <div class="product-info">
                        <div class="card">
                            <div class="card-header">
                                <h3>Product Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="product-details">
                                    <div class="detail-row">
                                        <div class="detail-label">ID:</div>
                                        <div class="detail-value"><?php echo $product['id']; ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Name:</div>
                                        <div class="detail-value"><?php echo $product['name']; ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Category:</div>
                                        <div class="detail-value"><?php echo $product['category_name']; ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Price:</div>
                                        <div class="detail-value">NPR. <?php echo number_format($product['price'], 2); ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Stock:</div>
                                        <div class="detail-value"><?php echo $product['stock']; ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Created Date:</div>
                                        <div class="detail-value"><?php echo date('M d, Y', strtotime($product['created_at'])); ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Total Sold:</div>
                                        <div class="detail-value"><?php echo $total_sales_data['total_quantity'] ?? 0; ?> units</div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Total Sales:</div>
                                        <div class="detail-value">NPR. <?php echo number_format($total_sales_data['total_sales'] ?? 0, 2); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-4">
                            <div class="card-header">
                                <h3>Product Description</h3>
                            </div>
                            <div class="card-body">
                                <div class="product-description">
                                    <?php echo nl2br($product['description']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h3>Recent Orders</h3>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($order_items_result) > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($item = mysqli_fetch_assoc($order_items_result)): ?>
                                        <tr>
                                            <td>#<?php echo $item['order_id']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo strtolower($item['status']); ?>">
                                                    <?php echo $item['status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td>NPR. <?php echo number_format($item['price'], 2); ?></td>
                                            <td>NPR. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                            <td>
                                                <a href="view-order.php?id=<?php echo $item['order_id']; ?>" class="action-icon view">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <div class="view-all-link">
                                <a href="orders.php?product_id=<?php echo $product['id']; ?>">View All Orders</a>
                            </div>
                        <?php else: ?>
                            <p class="no-data">No orders found for this product.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>