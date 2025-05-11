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
    header('location:categories.php');
    exit();
}

$id = $_GET['id'];

// Get category data
$query = "SELECT * FROM categories WHERE id = $id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0){
    header('location:categories.php');
    exit();
}

$category = mysqli_fetch_assoc($result);

// Update category
if(isset($_POST['update_category'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    
    // Check if category name already exists
    $name_check = "SELECT * FROM categories WHERE name = '$name' AND id != $id";
    $name_result = mysqli_query($conn, $name_check);
    
    if(mysqli_num_rows($name_result) > 0){
        $error = "Category name already exists. Please use a different name.";
    } else {
        $update_query = "UPDATE categories SET name = '$name' WHERE id = $id";
        
        if(mysqli_query($conn, $update_query)){
            $success = "Category updated successfully";
            
            // Refresh category data
            $query = "SELECT * FROM categories WHERE id = $id";
            $result = mysqli_query($conn, $query);
            $category = mysqli_fetch_assoc($result);
        } else {
            $error = "Error updating category: " . mysqli_error($conn);
        }
    }
}

// Get products count
$count_query = "SELECT COUNT(*) as count FROM products WHERE category_id = " . $category['id'];
$count_result = mysqli_query($conn, $count_query);
$count_data = mysqli_fetch_assoc($count_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category | Admin Panel</title>
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

            <!-- Edit Category Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Edit Category</h2>
                    <a href="categories.php" class="btn btn-secondary">Back to Categories</a>
                </div>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo $category['name']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="created_at">Created Date</label>
                                <input type="text" id="created_at" class="form-control" value="<?php echo date('M d, Y', strtotime($category['created_at'])); ?>" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="products_count">Products Count</label>
                                <input type="text" id="products_count" class="form-control" value="<?php echo $count_data['count']; ?>" readonly>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="update_category" class="btn btn-primary">Update Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>