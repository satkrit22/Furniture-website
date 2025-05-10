<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
    <div class="container">
        <a href="home.php" class="navbar-logo">
            <img src="images/shri_online_furniture-removebg-preview.png" alt="Shri Online Furniture Logo" style="height: 50px;">
        </a>
        <button class="toggle-button" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="navbar-links">
            <li><a href="home.php" class="nav-link"><i class="fas fa-home icon"></i>Home</a></li>
            <li><a href="shop.php" class="nav-link"><i class="fas fa-store icon"></i>Shop</a></li>
            <li><a href="profile.php" class="nav-link"><i class="fas fa-user icon"></i>Profile</a></li>
            <li><a href="cart.php" class="nav-link"><i class="fas fa-shopping-cart icon"></i>Cart 
                <?php 
                    // Check if the cart exists before trying to count it
                    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                ?>
                <span class="cart-count"><?php echo $cartCount; ?></span></a>
            </li>
            <li><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt icon"></i>Logout</a></li>
        </ul>
    </div>
</nav>

<style>
    /* Base styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    /* Navbar styles */
    .navbar {
        background-color: #2C3E50;
        padding: 10px 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }

    .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .navbar-logo img {
        height: 50px;
    }

    .navbar-links {
        list-style: none;
        display: flex;
        gap: 25px;
        margin: 0;
    }

    .navbar-links li {
        display: inline-block;
    }

    .nav-link {
        color: #fff;
        text-decoration: none;
        font-size: 18px;
        padding: 12px 15px;
        border-radius: 30px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        display: flex;
        align-items: center;
    }

    .nav-link:hover {
        background-color: #3498DB;
        transform: scale(1.05);
    }

    .icon {
        margin-right: 8px;
    }

    .cart-count {
        background-color: #E74C3C;
        color: white;
        border-radius: 50%;
        padding: 4px 10px;
        font-size: 14px;
        margin-left: 5px;
    }

    /* Mobile menu button */
    .toggle-button {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
    }

    /* Mobile menu styles */
    @media (max-width: 768px) {
        .navbar-links {
            display: none;
            width: 100%;
            background-color: #2C3E50;
            position: absolute;
            top: 60px;
            left: 0;
            right: 0;
            padding: 10px 0;
            flex-direction: column;
            gap: 0;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .navbar-links li {
            width: 100%;
        }

        .navbar.open .navbar-links {
            display: flex;
        }

        .toggle-button {
            display: block;
        }

        .nav-link {
            padding: 15px;
            font-size: 18px;
            text-align: center;
            border-radius: 0;
        }

        .nav-link:hover {
            background-color: #3498DB;
        }

        .cart-count {
            margin-left: 10px;
        }
    }

    /* Enhanced hover animation */
    .nav-link i {
        transition: transform 0.3s ease;
    }

    .nav-link:hover i {
        transform: rotate(10deg);
    }

    /* Add smooth transition to navbar background */
    .navbar:hover {
        background-color: #34495E;
    }
</style>

<script>
    // Toggle navbar visibility on mobile view
    const toggleButton = document.querySelector('.toggle-button');
    const navbar = document.querySelector('.navbar');

    toggleButton.addEventListener('click', () => {
        navbar.classList.toggle('open');
    });
</script>
