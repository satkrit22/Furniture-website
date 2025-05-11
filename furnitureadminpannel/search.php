<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Check if search query is provided
if(!isset($_GET['query']) || empty($_GET['query'])){
    header('location:index.php');
    exit();
}

$query = sanitize($_GET['query']);

// Search users
$users_query = "SELECT * FROM users WHERE name LIKE '%$query%' OR email LIKE '%$query%' OR phone LIKE '%$query%' LIMIT 5";
$users_result = mysqli_query($conn, $users_query);

// Search products
$products_query = "SELECT p.*, c.name as category_name 
                  FROM products p 
                  JOIN categories c ON p.category_id = c.id 
                  WHERE p.name LIKE '%$query%' OR p.description LIKE '%$query%' LIMIT 5";
$products_result = mysqli_query($conn, $products_query);

// Search categories
$categories_query = "SELECT * FROM categories WHERE name LIKE '%$query%' LIMIT 5";
$categories_result = mysqli_query($conn, $categories_query);

// Search orders
$orders_query = "SELECT o.*, u.name as customer_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id LIKE '%$query%' OR u.name LIKE '%$query%' OR u.email LIKE '%$query%' LIMIT 5";
$orders_result = mysqli_query($conn, $orders_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Admin Panel</title>
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

            <!-- Search Results Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Search Results for "<?php echo $query; ?>"</h2>
                </div>
                
                <div class="search-results">
                    <!-- Users Results -->
                    <div class="card">
                        <div class="card-header">
                            <div class="header-with-link">
                                <h3>Users</h3>
                                <?php if(mysqli_num_rows($users_result) > 0): ?>
                                    <a href="users.php?search=<?php echo $query; ?>" class="view-all">View All</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if(mysqli_num_rows($users_result) > 0): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($user = mysqli_fetch_assoc($users_result)): ?>
                                            <tr>
                                                <td><?php echo $user['id']; ?></td>
                                                <td><?php echo $user['name']; ?></td>
                                                <td><?php echo $user['email']; ?></td>
                                                <td><?php echo $user['phone']; ?></td>
                                                <td class="table-actions">
                                                    <a href="view-user.php?id=<?php echo $user['id']; ?>" class="action-icon view"><i class="fas fa-eye"></i></a>
                                                    <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="action-icon edit"><i class="fas fa-edit"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="no-results">No users found matching your search.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Products Results -->
                    <div class="card">
                        <div class="card-header">
                            <div class="header-with-link">
                                <h3>Products</h3>
                                <?php if(mysqli_num_rows($products_result) > 0): ?>
                                    <a href="products.php?search=<?php echo $query; ?>" class="view-all">View All</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if(mysqli_num_rows($products_result) > 0): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Category</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($product = mysqli_fetch_assoc($products_result)): ?>
                                            <tr>
                                                <td><?php echo $product['id']; ?></td>
                                                <td><img src="../uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50"></td>
                                                <td><?php echo $product['name']; ?></td>
                                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                                <td><?php echo $product['category_name']; ?></td>
                                                <td class="table-actions">
                                                    <a href="view-product.php?id=<?php echo $product['id']; ?>" class="action-icon view"><i class="fas fa-eye"></i></a>
                                                    <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="action-icon edit"><i class="fas fa-edit"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="no-results">No products found matching your search.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Categories Results -->
                    <div class="card">
                        <div class="card-header">
                            <div class="header-with-link">
                                <h3>Categories</h3>
                                <?php if(mysqli_num_rows($categories_result) > 0): ?>
                                    <a href="categories.php?search=<?php echo $query; ?>" class="view-all">View All</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if(mysqli_num_rows($categories_result) > 0): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Created Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($category = mysqli_fetch_assoc($categories_result)): ?>
                                            <tr>
                                                <td><?php echo $category['id']; ?></td>
                                                <td><?php echo $category['name']; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($category['created_at'])); ?></td>
                                                <td class="table-actions">
                                                    <a href="edit-category.php?id=<?php echo $category['id']; ?>" class="action-icon edit"><i class="fas fa-edit"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="no-results">No categories found matching your search.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Orders Results -->
                    <div class="card">
                        <div class="card-header">
                            <div class="header-with-link">
                                <h3>Orders</h3>
                                <?php if(mysqli_num_rows($orders_result) > 0): ?>
                                    <a href="orders.php?search=<?php echo $query; ?>" class="view-all">View All</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if(mysqli_num_rows($orders_result) > 0): ?>
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
                                        <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
                                            <tr>
                                                <td>#<?php echo $order['id']; ?></td>
                                                <td><?php echo $order['customer_name']; ?></td>
                                                <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                                <td><span class="status <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                                <td class="table-actions">
                                                    <a href="view-order.php?id=<?php echo $order['id']; ?>" class="action-icon view"><i class="fas fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="no-results">No orders found matching your search.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <style>
        .search-results {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .header-with-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .view-all {
            font-size: 14px;
            color: var(--primary-color);
        }
        
        .no-results {
            padding: 20px;
            text-align: center;
            color: var(--gray-color);
        }
        
        @media (max-width: 768px) {
            .search-results {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>