<?php
session_start();
// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Furniture Store</title>
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

            <!-- Dashboard Content -->
            <div class="dashboard">
                <h2>Dashboard Overview</h2>
                
                <div class="stats-container">
                    <?php
                    // Database connection
                    include 'includes/config.php';
                    
                    // Count users
                    $user_query = "SELECT COUNT(*) as total_users FROM users";
                    $user_result = mysqli_query($conn, $user_query);
                    $user_data = mysqli_fetch_assoc($user_result);
                    
                    // Count products
                    $product_query = "SELECT COUNT(*) as total_products FROM products";
                    $product_result = mysqli_query($conn, $product_query);
                    $product_data = mysqli_fetch_assoc($product_result);
                    
                    // Count categories
                    $category_query = "SELECT COUNT(*) as total_categories FROM categories";
                    $category_result = mysqli_query($conn, $category_query);
                    $category_data = mysqli_fetch_assoc($category_result);
                    
                    // Count orders
                    $order_query = "SELECT COUNT(*) as total_orders FROM orders";
                    $order_result = mysqli_query($conn, $order_query);
                    $order_data = mysqli_fetch_assoc($order_result);
                    
                    // Calculate total revenue
                    $revenue_query = "SELECT SUM(total_price) as total_revenue FROM orders WHERE status='completed'";
                    $revenue_result = mysqli_query($conn, $revenue_query);
                    $revenue_data = mysqli_fetch_assoc($revenue_result);
                    ?>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Users</h3>
                            <p><?php echo $user_data['total_users']; ?></p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-couch"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Products</h3>
                            <p><?php echo $product_data['total_products']; ?></p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Categories</h3>
                            <p><?php echo $category_data['total_categories']; ?></p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Orders</h3>
                            <p><?php echo $order_data['total_orders']; ?></p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Revenue</h3>
                            <p>NPR.<?php echo number_format($revenue_data['total_revenue'] ?? 0, 2); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="recent-section">
                    <div class="recent-orders">
                        <h3>Recent Orders</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Get recent orders
                                $recent_orders_query = "SELECT o.id, u.name as customer, o.total_price, o.status, o.created_at 
                                                      FROM orders o 
                                                      JOIN users u ON o.user_id = u.id 
                                                      ORDER BY o.created_at DESC LIMIT 5";
                                $recent_orders_result = mysqli_query($conn, $recent_orders_query);
                                
                                while($order = mysqli_fetch_assoc($recent_orders_result)) {
                                    echo "<tr>";
                                    echo "<td>#" . $order['id'] . "</td>";
                                    echo "<td>" . $order['customer'] . "</td>";
                                    echo "<td>NPR." . number_format($order['total_price'], 2) . "</td>";
                                    echo "<td><span class='status " . $order['status'] . "'>" . ucfirst($order['status']) . "</span></td>";
                                    echo "<td>" . date('M d, Y', strtotime($order['created_at'])) . "</td>";
                                    echo "</tr>";
                                }
                                
                                if(mysqli_num_rows($recent_orders_result) == 0) {
                                    echo "<tr><td colspan='5' class='no-data'>No recent orders</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <a href="orders.php" class="view-all">View All Orders</a>
                    </div>
                    
                    <div class="recent-products">
                        <h3>Low Stock Products</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Get low stock products
                                $low_stock_query = "SELECT p.id, p.name, p.price, p.stock, c.name as category 
                                                  FROM products p 
                                                  JOIN categories c ON p.category_id = c.id 
                                                  WHERE p.stock < 10 
                                                  ORDER BY p.stock ASC LIMIT 5";
                                $low_stock_result = mysqli_query($conn, $low_stock_query);
                                
                                while($product = mysqli_fetch_assoc($low_stock_result)) {
                                    echo "<tr>";
                                    echo "<td>" . $product['name'] . "</td>";
                                    echo "<td>NPR." . number_format($product['price'], 2) . "</td>";
                                    echo "<td>" . $product['stock'] . "</td>";
                                    echo "<td>" . $product['category'] . "</td>";
                                    echo "</tr>";
                                }
                                
                                if(mysqli_num_rows($low_stock_result) == 0) {
                                    echo "<tr><td colspan='4' class='no-data'>No low stock products</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <a href="products.php" class="view-all">View All Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>