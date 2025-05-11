<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Check if user is super_admin
if($_SESSION['admin_role'] !== 'super_admin'){
    header('location:index.php');
    exit();
}

// Delete admin
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    
    // Prevent deleting yourself
    if($id == $_SESSION['admin_id']){
        $error = "You cannot delete your own account";
    } else {
        $query = "DELETE FROM admins WHERE id = $id";
        
        if(mysqli_query($conn, $query)){
            $success = "Admin user deleted successfully";
        } else {
            $error = "Error deleting admin user: " . mysqli_error($conn);
        }
    }
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT * FROM admins ORDER BY id DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $query);

// Get total records
$total_query = "SELECT COUNT(*) as total FROM admins";
$total_result = mysqli_query($conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_data['total'] / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users | Admin Panel</title>
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

            <!-- Admin Users Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Admin Users Management</h2>
                    <a href="add-admin.php" class="btn btn-primary">Add New Admin</a>
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
                                    <th>Role</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(mysqli_num_rows($result) > 0){
                                    while($admin = mysqli_fetch_assoc($result)){
                                        echo "<tr>";
                                        echo "<td>" . $admin['id'] . "</td>";
                                        echo "<td>" . $admin['name'] . "</td>";
                                        echo "<td>" . $admin['email'] . "</td>";
                                        echo "<td><span class='badge " . $admin['role'] . "'>" . ucfirst(str_replace('_', ' ', $admin['role'])) . "</span></td>";
                                        echo "<td>" . ($admin['last_login'] ? date('M d, Y H:i', strtotime($admin['last_login'])) : 'Never') . "</td>";
                                        echo "<td class='table-actions'>";
                                        
                                        // Don't show edit/delete for your own account
                                        if($admin['id'] != $_SESSION['admin_id']){
                                            echo "<a href='edit-admin.php?id=" . $admin['id'] . "' class='action-icon edit'><i class='fas fa-edit'></i></a>";
                                            echo "<a href='admin-users.php?delete=" . $admin['id'] . "' class='action-icon delete'><i class='fas fa-trash'></i></a>";
                                        } else {
                                            echo "<span class='current-user'>Current User</span>";
                                        }
                                        
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='no-data'>No admin users found</td></tr>";
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
    <style>
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge.super_admin {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }
        
        .badge.admin {
            background-color: rgba(58, 110, 165, 0.1);
            color: var(--primary-color);
        }
        
        .badge.editor {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }
        
        .current-user {
            font-size: 12px;
            color: var(--gray-color);
            font-style: italic;
        }
    </style>
</body>
</html>