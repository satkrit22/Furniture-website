<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Delete product
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    
    // Get product image
    $image_query = "SELECT image FROM products WHERE id = $id";
    $image_result = mysqli_query($conn, $image_query);
    $image_data = mysqli_fetch_assoc($image_result);
    
    // Delete product
    $query = "DELETE FROM products WHERE id = $id";
    
    if(mysqli_query($conn, $query)){
        // Delete product image
        if(file_exists("../uploads/" . $image_data['image'])){
            unlink("../uploads/" . $image_data['image']);
        }
        
        $success = "Product deleted successfully";
    } else {
        $error = "Error deleting product: " . mysqli_error($conn);
    }
}

// Filter by category
$category_filter = "";
if(isset($_GET['category']) && $_GET['category'] != ""){
    $category_id = $_GET['category'];
    $category_filter = "WHERE p.category_id = $category_id";
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          $category_filter 
          ORDER BY p.id DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $query);

// Get total records
$total_query = "SELECT COUNT(*) as total FROM products p $category_filter";
$total_result = mysqli_query($conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_data['total'] / $limit);

// Get all categories for filter
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = mysqli_query($conn, $categories_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Admin Panel</title>
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

            <!-- Products Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Products Management</h2>
                    <a href="add-product.php" class="btn btn-primary">Add New Product</a>
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
                                    <label for="category">Filter by Category:</label>
                                    <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Categories</option>
                                        <?php while($category = mysqli_fetch_assoc($categories_result)): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo $category['name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(mysqli_num_rows($result) > 0){
                                    while($product = mysqli_fetch_assoc($result)){
                                        echo "<tr>";
                                        echo "<td>" . $product['id'] . "</td>";
                                        echo "<td><img src='../uploads/" . $product['image'] . "' alt='" . $product['name'] . "' width='50'></td>";
                                        echo "<td>" . $product['name'] . "</td>";
                                        echo "<td>NPR." . number_format($product['price'], 2) . "</td>";
                                        echo "<td>" . $product['stock'] . "</td>";
                                        echo "<td>" . $product['category_name'] . "</td>";
                                        echo "<td class='table-actions'>";
                                        echo "<a href='view-product.php?id=" . $product['id'] . "' class='action-icon view'><i class='fas fa-eye'></i></a>";
                                        echo "<a href='edit-product.php?id=" . $product['id'] . "' class='action-icon edit'><i class='fas fa-edit'></i></a>";
                                        echo "<a href='products.php?delete=" . $product['id'] . "' class='action-icon delete'><i class='fas fa-trash'></i></a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='no-data'>No products found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <?php if($total_pages > 1): ?>
                            <div class="pagination">
                                <?php if($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?>"><i class="fas fa-chevron-left"></i></a>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                                <?php endfor; ?>
                                
                                <?php if($page < $total_pages): ?>
                                    <a href="?page=<?php echo $page + 1; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?>"><i class="fas fa-chevron-right"></i></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>