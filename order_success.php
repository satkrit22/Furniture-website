<?php
session_start();
error_reporting(E_ERROR | E_PARSE);  // Suppress warnings and notices

// Redirect if no order number is set
if (!isset($_SESSION['order_number'])) {
    header("Location: index.php");
    exit();
}

$orderNumber = $_SESSION['order_number'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shri Online Furniture - Order Success</title>
    <link rel="stylesheet" href="css/shop.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .success-container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background-color: #2ecc71;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 24px;
        }

        p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .order-number {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .order-number p:first-child {
            color: #888;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .order-number p:last-child {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .continue-btn {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .continue-btn:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .success-container {
                width: 95%;
                padding: 20px;
                margin: 30px auto;
            }
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="success-container">
    <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M0 0h24v24H0z" fill="none"/>
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
        </svg>
    </div>
    
    <h2>Order Successful!</h2>
    
    <p>Thank you for your purchase. Your order has been received and is being processed.</p>
    
    <div class="order-number">
        <p>Order Number</p>
        <p><?php echo htmlspecialchars($orderNumber); ?></p>
    </div>
    
    <p>We've sent a confirmation email with your order details and tracking information.</p>
    
    <a href="index.php" class="continue-btn">Continue Shopping</a>
</div>

</body>
</html>