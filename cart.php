<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "furniture"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Initialize the cart session array if not already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Remove from Cart
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    if (isset($_SESSION['cart'][$index])) {
        // Get product ID and quantity before removing from cart
        $product_id = $_SESSION['cart'][$index]['id'];
        $quantity = isset($_SESSION['cart'][$index]['quantity']) ? $_SESSION['cart'][$index]['quantity'] : 1;
        
        // Return the quantity back to stock
        $updateStockQuery = "UPDATE products SET stock = stock + $quantity WHERE id = $product_id";
        mysqli_query($conn, $updateStockQuery);
        
        // Remove from cart
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
        
        // Set success message
        $_SESSION['cart_updated'] = "Item removed from cart successfully!";
        
        // Redirect to prevent form resubmission
        header("Location: cart.php");
        exit;
    }
}

// Handle Update Quantity
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $index => $new_quantity) {
        if (isset($_SESSION['cart'][$index])) {
            $product_id = $_SESSION['cart'][$index]['id'];
            $old_quantity = isset($_SESSION['cart'][$index]['quantity']) ? $_SESSION['cart'][$index]['quantity'] : 1;
            
            // Validate quantity
            $new_quantity = max(1, min(99, (int)$new_quantity));
            
            // Check if quantity changed
            if ($new_quantity != $old_quantity) {
                // Check if we need to get more from stock or return some to stock
                $quantity_difference = $old_quantity - $new_quantity;
                
                if ($quantity_difference > 0) {
                    // Return some items to stock
                    $updateStockQuery = "UPDATE products SET stock = stock + $quantity_difference WHERE id = $product_id";
                } else {
                    // Take more items from stock
                    $quantity_to_take = abs($quantity_difference);
                    
                    // Check if we have enough stock
                    $stockQuery = "SELECT stock FROM products WHERE id = $product_id";
                    $stockResult = mysqli_query($conn, $stockQuery);
                    $available_stock = mysqli_fetch_assoc($stockResult)['stock'];
                    
                    if ($available_stock < $quantity_to_take) {
                        // Not enough stock, set to maximum available
                        $new_quantity = $old_quantity + $available_stock;
                        $quantity_to_take = $available_stock;
                        $_SESSION['cart_error'] = "Some items are out of stock. Your cart has been adjusted.";
                    }
                    
                    $updateStockQuery = "UPDATE products SET stock = stock - $quantity_to_take WHERE id = $product_id";
                }
                
                // Update stock in database
                mysqli_query($conn, $updateStockQuery);
                
                // Update quantity in cart
                $_SESSION['cart'][$index]['quantity'] = $new_quantity;
            }
        }
    }
    
    // Set success message
    if (!isset($_SESSION['cart_error'])) {
        $_SESSION['cart_updated'] = "Cart updated successfully!";
    }
    
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit;
}

// Handle Clear Cart
if (isset($_POST['clear_cart'])) {
    // Return all items to stock
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
        
        $updateStockQuery = "UPDATE products SET stock = stock + $quantity WHERE id = $product_id";
        mysqli_query($conn, $updateStockQuery);
    }
    
    $_SESSION['cart'] = [];
    
    // Set success message
    $_SESSION['cart_updated'] = "Cart cleared successfully!";
    
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit;
}

// Handle Checkout
if (isset($_POST['checkout'])) {
    // Check if cart is empty
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart_error'] = "Your cart is empty. Add some products before checkout.";
        header("Location: cart.php");
        exit;
    }
    
    // Get user information
    $user_id = $_SESSION['id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Calculate total price
    $total_price = 0;
    $total_products = "";
    
    foreach ($_SESSION['cart'] as $item) {
        $price = isset($item['price']) ? $item['price'] : 0;
        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
        $total_price += $price * $quantity;
        $total_products .= $item['name'] . " (" . $quantity . "), ";
    }
    
    // Remove trailing comma
    $total_products = rtrim($total_products, ", ");
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert order into database
        $query = "INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, status) 
                VALUES (?, ?, ?, ?, 'Cash on Delivery', ?, ?, ?, 'pending')";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issssdi", $user_id, $user['name'], $user['phone'], $user['email'], $user['address'], $total_price, $total_price);
        $stmt->execute();
        
        $order_id = $conn->insert_id;
        
        // Insert order items
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['id'];
            $price = $item['price'];
            $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
            
            $query = "INSERT INTO order_items (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iidi", $order_id, $product_id, $price, $quantity);
            $stmt->execute();
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        // Clear cart after successful order
        $_SESSION['cart'] = [];
        
        // Set success message
        $_SESSION['order_success'] = "Order placed successfully! Thank you for shopping with us.";
        
        // Redirect to profile page
        header("Location: profile.php");
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        
        $_SESSION['cart_error'] = "Something went wrong. Please try again.";
        header("Location: cart.php");
        exit;
    }
}

// Calculate cart totals
$subtotal = 0;
$tax_rate = 0.13; // 13% tax
$shipping = 0; // Free shipping

foreach ($_SESSION['cart'] as $item) {
    $price = isset($item['price']) ? $item['price'] : 0;
    $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
    $subtotal += $price * $quantity;
}

$total = $subtotal;

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shri Online Furniture - Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #4e4e4e;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar img {
            height: 80px;
        }
        
        .navbar ul {
            display: flex;
            list-style: none;
            gap: 20px;
            margin-right: 20px;
        }
        
        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .navbar ul li a .icon {
            margin-right: 8px;
        }
        
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            font-size: 12px;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .section-title h2 {
            font-size: 32px;
            color: #4e4e4e;
            margin-bottom: 10px;
        }
        
        .section-title .lead {
            font-size: 18px;
            color: #666;
        }
        
        .section-title .line {
            height: 3px;
            width: 100px;
            background-color: rgb(225, 142, 49);
            margin: 15px auto;
        }
        
        .cart-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .cart-header {
            background-color: #f8f8f8;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .cart-header h3 {
            color: #4e4e4e;
            font-size: 20px;
        }
        
        .cart-count-badge {
            background-color: #4e4e4e;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .cart-items {
            padding: 0;
        }
        
        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr auto auto auto;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .cart-item-image img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .cart-item-details h4 {
            margin-bottom: 5px;
            font-size: 18px;
            color: #333;
        }
        
        .cart-item-price {
            color: #666;
            margin-bottom: 5px;
        }
        
        .cart-item-quantity {
            display: flex;
            align-items: center;
        }
        
        .quantity-input {
            width: 40px;
            text-align: center;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 2px 5px;
        }
        
        .action-buttons {
            display: flex;
        }
        
        .cart-item-remove {
            color: white;
            background-color: #e74c3c;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .cart-item-remove:hover {
            background-color: #c0392b;
        }
        
        .cart-actions {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #f8f8f8;
            border-top: 1px solid #eee;
        }
        
        .continue-shopping {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #4e4e4e;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .continue-shopping:hover {
            background-color: #333;
        }
        
        .update-cart {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .update-cart:hover {
            background-color: #2980b9;
        }
        
        .clear-cart {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-left: 10px;
        }
        
        .clear-cart:hover {
            background-color: #c0392b;
        }
        
        .cart-summary {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        
        .cart-summary h3 {
            color: #4e4e4e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .checkout-btn {
            display: flex;
            width: 100%;
            background-color: rgb(225, 142, 49);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        
        .checkout-btn:hover {
            background-color: rgb(156, 97, 42);
        }
        
        .empty-cart {
            text-align: center;
            padding: 50px 20px;
        }
        
        .empty-cart i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-cart h3 {
            font-size: 24px;
            color: #4e4e4e;
            margin-bottom: 10px;
        }
        
        .empty-cart p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .shop-now-btn {
            display: inline-block;
            background-color: rgb(225, 142, 49);
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .shop-now-btn:hover {
            background-color: rgb(156, 97, 42);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .cart-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
        }
        
        .quantity-btn {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            user-select: none;
        }
        
        .quantity-btn:hover {
            background-color: #e0e0e0;
        }
        
        @media (max-width: 768px) {
            .cart-grid {
                grid-template-columns: 1fr;
            }
            
            .cart-item {
                grid-template-columns: 80px 1fr auto;
                grid-template-rows: auto auto auto;
                gap: 10px;
            }
            
            .cart-item-image {
                grid-row: span 3;
            }
            
            .cart-item-details {
                grid-column: 2 / span 2;
            }
            
            .cart-item-quantity {
                grid-column: 2;
                grid-row: 2;
            }
            
            .cart-item-subtotal {
                grid-column: 3;
                grid-row: 2;
                text-align: right;
            }
            
            .cart-item-remove {
                grid-column: 2 / span 2;
                grid-row: 3;
                justify-content: center;
            }
            
            .navbar ul {
                gap: 10px;
                margin-right: 0;
            }
            
            .navbar ul li a {
                font-size: 14px;
            }
            
            .navbar ul li a .icon {
                margin-right: 4px;
            }
            
            .cart-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .action-buttons {
                width: 100%;
            }
            
            .update-cart, .clear-cart {
                flex: 1;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a href="shop.php">
        <img src="images/shri_online_furniture-removebg-preview.png" alt="Logo">
    </a>
    <ul>
        <li><a href="home.html" class="nav-link"><i class="fas fa-home icon"></i>Home</a></li>
        <li><a href="shop.php" class="nav-link"><i class="fas fa-store icon"></i>Shop</a></li>
        <li><a href="profile.php" class="nav-link"><i class="fas fa-user icon"></i>Profile</a></li>
        <li><a href="cart.php" class="nav-link"><i class="fas fa-shopping-cart icon"></i>Cart<span class="cart-count"><?php echo count($_SESSION['cart']); ?></span></a></li>
        <li><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt icon"></i>Logout</a></li>
    </ul>
</nav>

<div class="container">
    <div class="section-title">
        <h2>Your Shopping Cart</h2>
        <p class="lead">Review your items and proceed to checkout</p>
        <div class="line"></div>
    </div>
    
    <?php if (isset($_SESSION['cart_updated'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['cart_updated']; ?>
            <?php unset($_SESSION['cart_updated']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['cart_error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['cart_error']; ?>
            <?php unset($_SESSION['cart_error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="cart-grid">
            <div class="cart-container">
                <div class="cart-header">
                    <h3>Shopping Cart</h3>
                    <span class="cart-count-badge"><?php echo count($_SESSION['cart']); ?> Items</span>
                </div>
                
                <form method="post" action="cart.php" id="cartForm">
                    <div class="cart-items">
                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                            <div class="cart-item">
                                <div class="cart-item-details">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <p class="cart-item-price">NRP <?php echo number_format($item['price']); ?></p>
                                </div>
                                
                                <div class="cart-item-quantity">
                                    <div class="quantity-controls">
                                        <span class="quantity-btn decrease">-</span>
                                        <input type="number" name="quantity[<?php echo $index; ?>]" 
                                               value="<?php echo isset($item['quantity']) ? $item['quantity'] : 1; ?>" 
                                               min="1" max="99" class="quantity-input" readonly>
                                        <span class="quantity-btn increase">+</span>
                                    </div>
                                </div>
                                
                                <div class="cart-item-subtotal">
                                    NRP <?php echo number_format($item['price'] * (isset($item['quantity']) ? $item['quantity'] : 1)); ?>
                                </div>
                                
                                <a href="cart.php?remove=<?php echo $index; ?>" class="cart-item-remove">
                                    <i class="fas fa-trash"></i> Remove
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-actions">
                        <a href="shop.php" class="continue-shopping">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                        <div class="action-buttons">
                            <button type="submit" name="update_cart" class="update-cart">
                                <i class="fas fa-sync-alt"></i> Update Cart
                            </button>
                            <button type="submit" name="clear_cart" class="clear-cart">
                                <i class="fas fa-trash"></i> Clear Cart
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="cart-summary">
                <h3>Order Summary</h3>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>NRP <?php echo number_format($subtotal); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>NRP <?php echo number_format($total); ?></span>
                </div>
                
                <form method="post" action="checkout.php">
    <button type="submit" name="checkout" class="checkout-btn">
        <i class="fas fa-lock"></i> Proceed to Checkout
    </button>
</form>

            </div>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added any products to your cart yet.</p>
            <a href="shop.php" class="shop-now-btn">Shop Now</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hide alerts after 3 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 3000);
        
        // Quantity controls
        const decreaseBtns = document.querySelectorAll('.quantity-btn.decrease');
        const increaseBtns = document.querySelectorAll('.quantity-btn.increase');
        
        decreaseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.nextElementSibling;
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                }
            });
        });
        
        increaseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.previousElementSibling;
                let value = parseInt(input.value);
                if (value < 99) {
                    input.value = value + 1;
                }
            });
        });
    });
</script>

</body>
</html>
