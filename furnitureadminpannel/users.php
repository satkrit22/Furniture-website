<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Delete user
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $query = "DELETE FROM users WHERE id = $id";
    
    if(mysqli_query($conn, $query)){
        $success = "User deleted successfully";
    } else {
        $error = "Error deleting user: " . mysqli_error($conn);
    }
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT * FROM users ORDER BY id DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $query);

// Get total records
$total_query = "SELECT COUNT(*) as total FROM users";
$total_result = mysqli_query($conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_data['total'] / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users | Admin Panel</title>
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

            <!-- Users Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Users Management</h2>
                    <a href="add-user.php" class="btn btn-primary">Add New User</a>
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
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Registered Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(mysqli_num_rows($result) > 0){
                                    while($user = mysqli_fetch_assoc($result)){
                                        echo "<tr>";
                                        echo "<td>" . $user['id'] . "</td>";
                                        echo "<td>" . $user['name'] . "</td>";
                                        echo "<td>" . $user['email'] . "</td>";
                                        echo "<td>" . $user['phone'] . "</td>";
                                        echo "<td>" . date('M d, Y', strtotime($user['created_at'])) . "</td>";
                                        echo "<td class='table-actions'>";
                                        echo "<a href='view-user.php?id=" . $user['id'] . "' class='action-icon view'><i class='fas fa-eye'></i></a>";
                                        echo "<a href='edit-user.php?id=" . $user['id'] . "' class='action-icon edit'><i class='fas fa-edit'></i></a>";
                                        echo "<a href='users.php?delete=" . $user['id'] . "' class='action-icon delete'><i class='fas fa-trash'></i></a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='no-data'>No users found</td></tr>";
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