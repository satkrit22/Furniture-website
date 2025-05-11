<div class="sidebar">
    <div class="brand">
        <h2><i class="fas fa-couch"></i> Furniture Admin</h2>
    </div>
    
    <ul class="nav-links">
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <a href="index.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
            <a href="users.php">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </li>
        
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
            <a href="categories.php">
                <i class="fas fa-tags"></i>
                <span>Categories</span>
            </a>
        </li>
        
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
            <a href="products.php">
                <i class="fas fa-couch"></i>
                <span>Products</span>
            </a>
        </li>
        
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
            <a href="orders.php">
                <i class="fas fa-shopping-cart"></i>
                <span>Orders</span>
            </a>
        </li>
        
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
            <a href="reports.php">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
        </li>
        
        <?php if(isset($_SESSION['admin_role']) && $_SESSION['admin_role'] == 'super_admin'): ?>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin-users.php' || basename($_SERVER['PHP_SELF']) == 'add-admin.php' || basename($_SERVER['PHP_SELF']) == 'edit-admin.php' ? 'active' : ''; ?>">
            <a href="admin-users.php">
                <i class="fas fa-user-shield"></i>
                <span>Admin Users</span>
            </a>
        </li>
        <?php endif; ?>
        
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <a href="settings.php">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>
</div>