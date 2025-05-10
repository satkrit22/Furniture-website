<?php
/**
 * Cart Functions
 * 
 * This file contains functions for managing the shopping cart
 */

/**
 * Remove an item from the cart
 * 
 * @param int $index The index of the item to remove
 * @return bool True if successful, false otherwise
 */
function removeCartItem($index) {
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        // Reindex the array to avoid gaps
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        return true;
    }
    return false;
}

/**
 * Update the quantity of an item in the cart
 * 
 * @param int $index The index of the item to update
 * @param int $quantity The new quantity
 * @return array Result with success status and updated values
 */
function updateCartQuantity($index, $quantity) {
    // Validate quantity
    $quantity = (int) $quantity;
    if ($quantity < 1) {
        $quantity = 1;
    } elseif ($quantity > 99) {
        $quantity = 99;
    }
    
    if (isset($_SESSION['cart'][$index])) {
        // Update the quantity
        $_SESSION['cart'][$index]['quantity'] = $quantity;
        
        // Calculate the new subtotal for this item
        $itemSubtotal = $_SESSION['cart'][$index]['price'] * $quantity;
        
        // Calculate the new cart subtotal, tax, and total
        $cartSubtotal = calculateSubtotal($_SESSION['cart']);
        $tax = calculateTax($cartSubtotal);
        $total = $cartSubtotal + $tax;
        
        return [
            'success' => true,
            'subtotal' => number_format($itemSubtotal),
            'cart_subtotal' => number_format($cartSubtotal),
            'tax' => number_format($tax),
            'total' => number_format($total)
        ];
    }
    
    return ['success' => false];
}

/**
 * Calculate the subtotal of all items in the cart
 * 
 * @param array $cartItems The cart items
 * @return float The subtotal
 */
function calculateSubtotal($cartItems) {
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    return $subtotal;
}

/**
 * Calculate the tax based on the subtotal
 * 
 * @param float $subtotal The subtotal
 * @return float The tax amount
 */
function calculateTax($subtotal) {
    // 13% tax rate (can be adjusted as needed)
    $taxRate = 0.13;
    return $subtotal * $taxRate;
}

/**
 * Add an item to the cart
 * 
 * @param array $product The product to add
 * @param int $quantity The quantity to add
 * @return bool True if successful, false otherwise
 */
function addToCart($product, $quantity = 1) {
    // Validate quantity
    $quantity = (int) $quantity;
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    // Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if product already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product['id']) {
            // Update quantity
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    // If product not found, add it
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
    }
    
    return true;
}

/**
 * Get the total number of items in the cart
 * 
 * @return int The total number of items
 */
function getCartItemCount() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    
    return $count;
}

/**
 * Clear the cart
 * 
 * @return void
 */
function clearCart() {
    $_SESSION['cart'] = [];
}

/**
 * Save the cart to the database for a logged-in user
 * 
 * @param int $userId The user ID
 * @return bool True if successful, false otherwise
 */
function saveCartToDatabase($userId) {
    global $conn;
    
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return false;
    }
    
    // First, clear the user's existing cart in the database
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Then insert the current cart items
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, name, price, image, quantity) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($_SESSION['cart'] as $item) {
        $stmt->bind_param("iisisi", $userId, $item['id'], $item['name'], $item['price'], $item['image'], $item['quantity']);
        $stmt->execute();
    }
    
    return true;
}

/**
 * Load the cart from the database for a logged-in user
 * 
 * @param int $userId The user ID
 * @return bool True if successful, false otherwise
 */
function loadCartFromDatabase($userId) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT product_id, name, price, image, quantity FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['cart'] = [];
        
        while ($row = $result->fetch_assoc()) {
            $_SESSION['cart'][] = [
                'id' => $row['product_id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'image' => $row['image'],
                'quantity' => $row['quantity']
            ];
        }
        
        return true;
    }
    
    return false;
}
?>