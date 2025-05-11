<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Delete category
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    
    // Check if category has products
    $check_query = "SELECT COUNT(*) as count FROM products WHERE category_id = $id";
    $check_result = mysqli_query($conn, $check_query);
    $check_data = mysqli_fetch_assoc($check_result);
    
    if($check_data['count'] > 0){
        $error = "Cannot delete category. It has products associated with it.";
    } else {
        $query = "DELETE FROM categories WHERE id = $id";
        
        if(mysqli_query($conn, $query)){
            $success = "Category deleted successfully";
        } else {
            $error = "Error deleting category: " . mysqli_error($conn);
        }
    }
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT * FROM categories ORDER BY id DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $query);

// Get total records
$total_query = "SELECT COUNT(*) as total FROM categories";
$total_result = mysqli_query($conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_data['total'] / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories | Admin Panel</title>
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

            <!-- Categories Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Categories Management</h2>
                    <a href="add-category.php" class="btn btn-primary">Add New Category</a>
                </div>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Created Date</th>
                                    <th>Products Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(mysqli_num_rows($result) > 0){
                                    while($category = mysqli_fetch_assoc($result)){
                                        // Get products count
                                        $count_query = "SELECT COUNT(*) as count FROM products WHERE category_id = " . $category['id'];
                                        $count_result = mysqli_query($conn, $count_query);
                                        $count_data = mysqli_fetch_assoc($count_result);
                                        
                                        echo "<tr>";
                                        echo "<td>" . $category['id'] . "</td>";
                                        echo "<td>" . $category['name'] . "</td>";
                                        echo "<td>" . date('M d, Y', strtotime($category['created_at'])) . "</td>";
                                        echo "<td>" . $count_data['count'] . "</td>";
                                        echo "<td class='table-actions'>";
                                        echo "<a href='edit-category.php?id=" . $category['id'] . "' class='action-icon edit'><i class='fas fa-edit'></i></a>";
                                        echo "<a href='categories.php?delete=" . $category['id'] . "' class='action-icon delete'><i class='fas fa-trash'></i></a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='no-data'>No categories found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <?php if($total_pages > 1): ?>
                            <div class="pagination">
                                <?php if($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>"><i class="fas fa-chevron-left"></i></a>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                                <?php endfor; ?>
                                
                                <?php if($page < $total_pages): ?>
                                    <a href="?page=<?php echo $page + 1; ?>"><i class="fas fa-chevron-right"></i></a>
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