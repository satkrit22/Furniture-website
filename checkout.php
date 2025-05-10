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

// If cart is empty, redirect to cart page
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

// Initialize variables
$name = $user['name'] ?? '';
$email = $user['email'] ?? '';
$phone = $user['phone'] ?? '';
$address = $user['address'] ?? '';
$city = '';
$payment_method = 'cash_on_delivery';
$errors = [];
$inside_valley = false;
$delivery_charge = 0;

// List of areas inside Kathmandu Valley
$valley_areas = [
    'kathmandu', 'lalitpur', 'bhaktapur', 'kirtipur', 'thimi', 'patan',
    'balaju', 'baneshwor', 'budhanilkantha', 'chabahil', 'gongabu', 'jorpati',
    'kalanki', 'kalimati', 'kapan', 'kuleshwor', 'maharajgunj', 'naxal',
    'new baneshwor', 'old baneshwor', 'satdobato', 'swayambhu', 'thamel'
];

// Process checkout form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $payment_method = $_POST['payment_method'];
    
    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($address)) {
        $errors[] = "Address is required";
    }
    
    if (empty($city)) {
        $errors[] = "City is required";
    }
    
    // Check if delivery location is inside valley
    $inside_valley = false;
    foreach ($valley_areas as $area) {
        if (stripos($city, $area) !== false || stripos($address, $area) !== false) {
            $inside_valley = true;
            break;
        }
    }
    
    if (!$inside_valley) {
        $errors[] = "Sorry, we currently only deliver inside Kathmandu Valley.";
    }
    
    // If no errors, process the order
    if (empty($errors)) {
        // Calculate total price
        $subtotal = 0;
        $delivery_charge = $inside_valley ? 200 : 0; // 200 NPR for inside valley
        $total_products = "";
        
        foreach ($_SESSION['cart'] as $item) {
            $price = isset($item['price']) ? $item['price'] : 0;
            $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
            $subtotal += $price * $quantity;
            $total_products .= $item['name'] . " (" . $quantity . "), ";
        }
        
        // Remove trailing comma
        $total_products = rtrim($total_products, ", ");
        
        // Calculate total (without tax)
        $total = $subtotal + $delivery_charge;
        
        // Insert order into database
        $query = "INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $conn->prepare($query);
        $full_address = $address . ", " . $city;
        $stmt->bind_param("issssssi", $user_id, $name, $phone, $email, $payment_method, $full_address, $total_products, $total);
        
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;
            
            // Insert order items
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            
            foreach ($_SESSION['cart'] as $item) {
                $product_id = isset($item['id']) ? $item['id'] : 0;
                $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                $price = isset($item['price']) ? $item['price'] : 0;
                
                // Check if product exists in the database before inserting into order_items
                $product_check_query = "SELECT id FROM products WHERE id = ?";
                $product_check_stmt = $conn->prepare($product_check_query);
                $product_check_stmt->bind_param("i", $product_id);
                $product_check_stmt->execute();
                $product_check_stmt->store_result();
                
                if ($product_check_stmt->num_rows > 0) {
                    // Insert order item only if the product exists
                    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                    $stmt->execute();
                } else {
                    $errors[] = "Product ID " . $product_id . " not found.";
                }
            }
            
            // Clear cart after successful order
            $_SESSION['cart'] = [];
            
            // Set success message
            $_SESSION['order_success'] = "Order placed successfully! Your order ID is #" . $order_id;
            
            // Redirect to profile page
            header("Location: profile.php");
            exit;
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}

// Calculate cart totals
$subtotal = 0;

foreach ($_SESSION['cart'] as $item) {
    $price = isset($item['price']) ? $item['price'] : 0;
    $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
    $subtotal += $price * $quantity;
}

// Check if city is provided and determine if it's inside valley
if (!empty($city)) {
    foreach ($valley_areas as $area) {
        if (stripos($city, $area) !== false || stripos($address, $area) !== false) {
            $inside_valley = true;
            break;
        }
    }
}

// Set delivery charge based on location
$delivery_charge = $inside_valley ? 200 : 0;

// Calculate total (without tax)
$total = $subtotal + $delivery_charge;

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shri Online Furniture - Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="checkout.css">
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
        <h2>Checkout</h2>
        <p class="lead">Complete your order</p>
        <div class="line"></div>
    </div>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="delivery-notice">
        <i class="fas fa-info-circle"></i>
        <strong>Delivery Notice:</strong> We currently only deliver inside Kathmandu Valley with a delivery charge of NRP 200. Please ensure your delivery address is within the valley.
    </div>
    
    <div class="checkout-grid">
        <div class="checkout-form">
            <h3 class="form-title">Shipping Information</h3>
            
            <form method="post" action="checkout.php" id="checkout-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Street Address</label>
                    <input type="text" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($address); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="city">City/Area</label>
                    <input type="text" id="city" name="city" class="form-control" value="<?php echo htmlspecialchars($city); ?>" required>
                    <small id="city-help" style="color: #666; margin-top: 5px; display: block;">Enter your city or area (e.g., Kathmandu, Lalitpur, Bhaktapur)</small>
                </div>
                
                <div id="delivery-status" class="delivery-notice" style="display: none;"></div>
                
                <h3 class="form-title">Payment Method</h3>
                
                <div class="payment-methods">
                    <div class="payment-method active">
                        <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery" checked>
                        <i class="fas fa-money-bill-wave payment-method-icon"></i>
                        <label for="cash_on_delivery">Cash on Delivery</label>
                    </div>
                </div>
                
                <button type="submit" name="place_order" class="place-order-btn" id="place-order-btn">
                    <i class="fas fa-check-circle"></i> Place Order
                </button>
            </form>
        </div>
        
        <div class="order-summary">
            <h3 class="order-summary-title">Order Summary</h3>
            
            <div class="order-items">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="order-item">
                        <div class="order-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="order-item-quantity">x<?php echo isset($item['quantity']) ? $item['quantity'] : 1; ?></div>
                        <div class="order-item-price">NRP <?php echo number_format($item['price'] * (isset($item['quantity']) ? $item['quantity'] : 1)); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="summary-row">
                <span>Subtotal</span>
                <span>NRP <?php echo number_format($subtotal); ?></span>
            </div>
            
            <div class="summary-row" id="delivery-row">
                <span>Delivery</span>
                <span id="delivery-charge">NRP <?php echo number_format($delivery_charge); ?></span>
            </div>
            
            <div class="summary-row total">
                <span>Total</span>
                <span id="total-amount">NRP <?php echo number_format($total); ?></span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cityInput = document.getElementById('city');
        const addressInput = document.getElementById('address');
        const deliveryStatus = document.getElementById('delivery-status');
        const deliveryCharge = document.getElementById('delivery-charge');
        const totalAmount = document.getElementById('total-amount');
        const placeOrderBtn = document.getElementById('place-order-btn');
        
        // Valley areas for delivery
        const valleyAreas = [
            'kathmandu', 'lalitpur', 'bhaktapur', 'kirtipur', 'thimi', 'patan',
            'balaju', 'baneshwor', 'budhanilkantha', 'chabahil', 'gongabu', 'jorpati',
            'kalanki', 'kalimati', 'kapan', 'kuleshwor', 'maharajgunj', 'naxal',
            'new baneshwor', 'old baneshwor', 'satdobato', 'swayambhu', 'thamel'
        ];
        
        // Initial values
        const subtotal = <?php echo $subtotal; ?>;
        
        function checkDeliveryArea() {
            const city = cityInput.value.toLowerCase();
            const address = addressInput.value.toLowerCase();
            
            // Check if location is in valley
            let insideValley = false;
            for (const area of valleyAreas) {
                if (city.includes(area) || address.includes(area)) {
                    insideValley = true;
                    break;
                }
            }
            
            if (city.trim() === '') {
                deliveryStatus.style.display = 'none';
                placeOrderBtn.disabled = false;
                return;
            }
            
            if (insideValley) {
                deliveryStatus.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Delivery Available:</strong> Your location is within our delivery area.';
                deliveryStatus.className = 'delivery-notice';
                deliveryCharge.textContent = 'NRP 200';
                totalAmount.textContent = 'NRP ' + numberWithCommas(subtotal + 200);
                placeOrderBtn.disabled = false;
            } else {
                deliveryStatus.innerHTML = '<i class="fas fa-times-circle"></i> <strong>Delivery Unavailable:</strong> Sorry, we currently only deliver inside Kathmandu Valley.';
                deliveryStatus.className = 'delivery-notice error';
                deliveryCharge.textContent = 'Not Available';
                totalAmount.textContent = 'NRP ' + numberWithCommas(subtotal);
                placeOrderBtn.disabled = true;
            }
            
            deliveryStatus.style.display = 'block';
        }
        
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        
        // Add event listeners
        cityInput.addEventListener('input', checkDeliveryArea);
        addressInput.addEventListener('input', checkDeliveryArea);
        
        // Payment method selection
        const paymentMethods = document.querySelectorAll('.payment-method');
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                // Remove active class from all methods
                paymentMethods.forEach(m => m.classList.remove('active'));
                
                // Add active class to clicked method
                this.classList.add('active');
                
                // Select the radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });
        
        // Initial check
        checkDeliveryArea();
    });
</script>

</body>
</html>