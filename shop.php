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

// Initialize the cart session array if not already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// If the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch products with category name and stock (You can adjust the query as per your needs)
$query = "
    SELECT products.*, categories.name AS category_name
    FROM products
    JOIN categories ON products.category_id = categories.id
";

// Check if there's a search query and filter results accordingly
$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    // Filter products that start with the search letter
    $query .= " WHERE products.name LIKE '" . $conn->real_escape_string($searchQuery) . "%'";
}

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error fetching products: " . mysqli_error($conn));
}

// Handle Add to Cart functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_id = (int)$_POST['product_id'];  // Casting to integer to prevent any issues
    $quantity = 1; // Default to 1

    // Check the stock of the product before adding it to the cart
    $stockQuery = "SELECT stock FROM products WHERE id = $product_id";
    $stockResult = mysqli_query($conn, $stockQuery);
    if ($stockResult) {
        $stock = mysqli_fetch_assoc($stockResult)['stock'];

        // If there is stock available, update the stock and add to cart
        if ($stock >= $quantity) {
            // Check if the product is already in the cart
            $product_in_cart = false;
            $cart_index = -1;
            
            foreach ($_SESSION['cart'] as $index => $cart_item) {
                if ($cart_item['id'] == $product_id) {
                    $product_in_cart = true;
                    $cart_index = $index;
                    break;
                }
            }

            if (!$product_in_cart) {
                // Add the product to the cart session array
                $_SESSION['cart'][] = [
                    'name' => $product_name, 
                    'price' => $product_price, 
                    'id' => $product_id,
                    'quantity' => $quantity
                ];

                // Decrease the stock by the quantity
                $updateStockQuery = "UPDATE products SET stock = stock - $quantity WHERE id = $product_id";
                mysqli_query($conn, $updateStockQuery);

                // Set flag to show success message
                $_SESSION['product_added'] = true;
            } else {
                // If the product is already in the cart, show a message
                $_SESSION['product_already_in_cart'] = true;
            }
        } else {
            // If no stock is available, set an error message
            $_SESSION['product_added_error'] = true;
        }
    } else {
        die("Error checking stock: " . mysqli_error($conn));
    }

    // Redirect back to the shop page to reflect changes
    header("Location: shop.php");
    exit();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shri Online Furniture - Shop</title>
    <link rel="stylesheet" href="css/shop.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #4e4e4e;
        }
        .navbar img {
            height: 80px;
        }
        .navbar ul {
            display: flex;
            list-style: none;
            gap: 20px;
            margin-right: 80px;
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

        .cart-btn {
            padding: 8px 15px;
            background-color: rgb(225, 142, 49);
            color: #fff;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            margin-top: 10px;
            width: 100%;
            border-radius: 5px;
            text-align: center;
        }

        .cart-btn:hover {
            background-color: rgb(156, 97, 42);
        }

        .cart-count {
            position: absolute;
            top: -10px;
            right: 40px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            font-size: 12px;
            margin-left: 10px;
        }

        .success-message {
            color: green;
            font-weight: bold;
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4e4e4e;
            color: white;
            padding: 10px 20px;
            z-index: 9999;
            border-radius: 5px;
            display: none;
        }

        .featured-deals-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .featured-deals-item {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 7px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 580px;
            position: relative;
        }

        .featured-deals-item .image img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .featured-deals-item .info {
            padding: 10px;
            text-align: center;
            flex-grow: 1;
        }

        .featured-deals-item .name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .featured-deals-item .description {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .featured-deals-item .price {
            font-size: 16px;
            color: #4e4e4e;
            margin-bottom: 15px;
        }

        .featured-deals-item .stock {
            font-size: 14px;
            color: #4e4e4e;
            margin-bottom: 15px;
        }

        .featured-deals-item .button-container {
            margin-top: auto;
            display: flex;
            justify-content: center;
        }

        .search-container {
            margin-left: auto;
        }

        .search-container form {
            display: flex;
            align-items: center;
        }

        .search-container input[type="text"] {
            padding: 5px;
            border: none;
            border-radius: 5px 0 0 5px;
        }

        .search-container button {
            padding: 5px 10px;
            border: none;
            background-color: #4e4e4e;
            color: white;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }
        
        .error-message {
            color: red;
            font-weight: bold;
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            z-index: 9999;
            border-radius: 5px;
            display: none;
        }
        
        /* Mobile First Responsive Design */
        @media (max-width: 480px) {
            .navbar {
                flex-direction: column;
                padding: 10px;
            }
            
            .navbar img {
                height: 60px;
                margin-bottom: 10px;
            }
            
            .navbar ul {
                flex-wrap: wrap;
                justify-content: center;
                margin-right: 0;
                gap: 10px;
            }
            
            .navbar ul li a {
                font-size: 14px;
                padding: 5px;
            }
            
            .featured-deals-content {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 0 10px;
            }
            
            .featured-deals-item {
                height: auto;
                min-height: 400px;
            }
            
            .search-container {
                margin: 10px 0;
                width: 100%;
            }
            
            .search-container form {
                width: 100%;
            }
            
            .search-container input[type="text"] {
                flex: 1;
                padding: 8px;
            }
        }

        @media (min-width: 481px) and (max-width: 768px) {
            .navbar ul {
                gap: 15px;
                margin-right: 20px;
            }
            
            .navbar ul li a {
                font-size: 16px;
            }
            
            .featured-deals-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            
            .featured-deals-item {
                height: auto;
                min-height: 450px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .featured-deals-content {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1025px) {
            .featured-deals-content {
                grid-template-columns: repeat(4, 1fr);
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
        <li class="search-container">
            <form id="searchForm">
                <input type="text" placeholder="Search by first letter..." name="search" id="searchInput">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </li>
    </ul>
</nav>

<!-- Success Message -->
<?php if (isset($_SESSION['product_added']) && $_SESSION['product_added']) { ?>
    <div class="success-message" id="successMessage">Product added to cart successfully!</div>
    <?php unset($_SESSION['product_added']); ?>
<?php } ?>

<!-- Error Message for Out of Stock -->
<?php if (isset($_SESSION['product_added_error']) && $_SESSION['product_added_error']) { ?>
    <div class="error-message" id="errorMessage">Sorry, this product is out of stock!</div>
    <?php unset($_SESSION['product_added_error']); ?>
<?php } ?>

<!-- Product Section -->
<section class="featured-deals py">
    <div class="container">
        <div class="section-title text-center">
            <h2>Check Our Products</h2>
            <p class="lead">Here you can check out our products with fair prices on Shri.</p>
            <div class="line"></div>
        </div>

        <div class="featured-deals-content" id="productList">
            <!-- Display products here dynamically using PHP -->
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                        <div class="featured-deals-item">
                            <div class="image">
                                <img src="images/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">
                            </div>
                            <div class="info">
                                <p class="name"><strong>' . htmlspecialchars($row['name']) . '</strong></p>
                                <p class="description">' . htmlspecialchars(mb_strimwidth($row['description'], 0, 100, "...")) . '</p>
                                <div class="price">
                                    <span class="text-brown"><strong>NRP ' . number_format($row['price']) . '</strong></span>
                                </div>
                                <div class="stock">
                                    <span class="text-brown"><strong>Stock: ' . htmlspecialchars($row['stock']) . '</strong></span>
                                </div>
                                <div class="button-container">
                                    <form method="post">
                                        <input type="hidden" name="product_name" value="' . htmlspecialchars($row['name']) . '">
                                        <input type="hidden" name="product_price" value="' . $row['price'] . '">
                                        <input type="hidden" name="product_id" value="' . $row['id'] . '">
                                        <button type="submit" name="add_to_cart" class="cart-btn" ' . ($row['stock'] <= 0 ? 'disabled' : '') . '>
                                            ' . ($row['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart') . '
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>';
                }
            } else {
                echo '<p>No products found matching your search.</p>';
            }
            ?>
        </div>
    </div>
</section>

<!-- jQuery & Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Show success message after adding to cart
        <?php if (isset($_SESSION['product_added'])) { ?>
            $('#successMessage').fadeIn().delay(2000).fadeOut();
        <?php } ?>

        // Show error message for out of stock
        <?php if (isset($_SESSION['product_added_error'])) { ?>
            $('#errorMessage').fadeIn().delay(2000).fadeOut();
        <?php } ?>

        // Search functionality using AJAX
        $('#searchForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting normally

            var searchQuery = $('#searchInput').val(); // Get the search input value

            $.ajax({
                url: window.location.href, // Submit the form data to the same page
                type: 'GET',
                data: { search: searchQuery },
                success: function (response) {
                    var productList = $(response).find('#productList').html();
                    $('#productList').html(productList);
                }
            });
        });
    });
</script>

</body>
</html>
