<div class="header">
    <div class="menu-toggle">
        <i class="fas fa-bars"></i>
    </div>
    
    <div class="search-box">
        <form action="search.php" method="GET">
            <input type="text" name="query" placeholder="Search...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    
    <div class="header-right">
        <div class="notification">
            <i class="fas fa-bell"></i>
            <span class="badge">3</span>
        </div>
        
        <div class="admin-profile">
            <img src="img/admin-avatar.png" alt="Admin Avatar">
            <div class="admin-info">
                <h4><?php echo $_SESSION['admin_name']; ?></h4>
                <small>Administrator</small>
            </div>
            <div class="dropdown">
                <i class="fas fa-chevron-down"></i>
                <div class="dropdown-content">
                    <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>