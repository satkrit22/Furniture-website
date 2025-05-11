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

// Get product data
$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0){
    header('location:products.php');
    exit();
}

$product = mysqli_fetch_assoc($result);

// Get all categories
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = mysqli_query($conn, $categories_query);

// Update product
if(isset($_POST['update_product'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    
    // Handle image upload
    $image = $product['image']; // Default to current image
    
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0){
        $target_dir = "../uploads/";
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            $error = "File is not an image.";
        } else {
            // Check file size
            if ($_FILES["image"]["size"] > 5000000) { // 5MB
                $error = "Sorry, your file is too large.";
            } else {
                // Allow certain file formats
                if($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg" && $file_extension != "gif" ) {
                    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                } else {
                    // Upload file
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // Delete old image
                        if(file_exists("/Furniture-website/images/" . $product['image']) && $product['image'] != "default.jpg"){
                            unlink("/Furniture-website/images/" . $product['image']);
                        }
                        $image = $new_filename;
                    } else {
                        $error = "Sorry, there was an error uploading your file.";
                    }
                }
            }
        }
    }
    
    if(!isset($error)){
        $update_query = "UPDATE products SET 
                        name = '$name', 
                        description = '$description', 
                        price = '$price', 
                        stock = '$stock', 
                        category_id = '$category_id', 
                        image = '$image'
                        WHERE id = $id";
        
        if(mysqli_query($conn, $update_query)){
            $success = "Product updated successfully";
            
            // Refresh product data
            $query = "SELECT * FROM products WHERE id = $id";
            $result = mysqli_query($conn, $query);
            $product = mysqli_fetch_assoc($result);
        } else {
            $error = "Error updating product: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Admin Panel</title>
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

            <!-- Edit Product Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Edit Product</h2>
                    <a href="products.php" class="btn btn-secondary">Back to Products</a>
                </div>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo $product['name']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5" required><?php echo $product['description']; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="price">Price (NPR)</label>
                                <input type="number" name="price" id="price" class="form-control" value="<?php echo $product['price']; ?>" step="0.01" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" name="stock" id="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <?php while($category = mysqli_fetch_assoc($categories_result)): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo $category['name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="image">Product Image</label>
                                <div class="current-image">
                                    <img src="/Furniture-website/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="100">
                                    <p>Current Image</p>
                                </div>
                                <input type="file" name="image" id="image" class="form-control">
                                <small>Leave blank to keep current image. Only JPG, JPEG, PNG & GIF files are allowed.</small>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
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