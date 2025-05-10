/**
 * Cart functionality for Shri Furniture
 * Handles cart interactions, animations, and AJAX operations
 */

document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const quantityButtons = document.querySelectorAll('.quantity-btn');
    const removeButtons = document.querySelectorAll('.remove-item-btn');
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navbar = document.querySelector('.navbar');
    
    // Handle quantity buttons
    if (quantityButtons.length > 0) {
        quantityButtons.forEach(button => {
            button.addEventListener('click', function() {
                const action = this.dataset.action;
                const input = this.parentNode.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                
                if (action === 'increase') {
                    input.value = currentValue + 1;
                } else if (action === 'decrease' && currentValue > 1) {
                    input.value = currentValue - 1;
                }
                
                // Trigger change event to update quantity
                const event = new Event('change');
                input.dispatchEvent(event);
            });
        });
    }
    
    // Handle quantity change
    if (quantityInputs.length > 0) {
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const index = this.dataset.index;
                const quantity = parseInt(this.value);
                const cartItem = this.closest('.cart-item');
                const updateStatus = document.querySelector(`.update-status[data-index="${index}"]`);
                
                // Validate quantity
                if (isNaN(quantity) || quantity < 1) {
                    this.value = 1;
                    return;
                }
                
                // Visual feedback
                cartItem.classList.add('quantity-updating');
                
                // AJAX request to update quantity
                const formData = new FormData();
                formData.append('update_qty', true);
                formData.append('index', index);
                formData.append('quantity', quantity);
                
                fetch('cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Update subtotal display
                        const subtotalElement = cartItem.querySelector('.item-subtotal');
                        if (subtotalElement) {
                            subtotalElement.textContent = `NRP ${numberWithCommas(data.newSubtotal)}`;
                        }
                        
                        // Update total in order summary
                        const cartTotalElements = document.querySelectorAll('.summary-row.total span:last-child');
                        if (cartTotalElements.length > 0) {
                            cartTotalElements.forEach(element => {
                                // We need to recalculate with tax
                                const total = data.cartTotal * 1.13; // Including 13% tax
                                element.textContent = `NRP ${numberWithCommas(Math.round(total))}`;
                            });
                        }
                        
                        // Show update status message
                        if (updateStatus) {
                            updateStatus.textContent = 'Updated';
                            updateStatus.classList.add('visible');
                            
                            // Hide message after a delay
                            setTimeout(() => {
                                updateStatus.classList.remove('visible');
                            }, 2000);
                        }
                    } else {
                        console.error('Error updating quantity:', data.message);
                    }
                    
                    // Remove animation class
                    setTimeout(() => {
                        cartItem.classList.remove('quantity-updating');
                    }, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    cartItem.classList.remove('quantity-updating');
                });
            });
        });
    }
    
    // Handle remove buttons with confirmation
    if (removeButtons.length > 0) {
        removeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const index = this.dataset.index;
                const cartItem = this.closest('.cart-item');
                
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    // Add removing animation
                    cartItem.classList.add('removing');
                    
                    // Redirect after animation
                    setTimeout(() => {
                        window.location.href = `cart.php?remove=${index}`;
                    }, 300);
                }
            });
        });
    }
    
    // Mobile menu toggle
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            navbar.classList.toggle('open');
        });
    }
    
    // Utility function to format numbers with commas
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    // Add animation classes to elements on page load
    const cartItems = document.querySelectorAll('.cart-item');
    const orderSummary = document.querySelector('.order-summary');
    const emptyCart = document.querySelector('.empty-cart');
    
    if (cartItems.length > 0) {
        cartItems.forEach((item, index) => {
            setTimeout(() => {
                item.classList.add('fade-in');
            }, 100 * index);
        });
    }
    
    if (orderSummary) {
        setTimeout(() => {
            orderSummary.classList.add('slide-up');
        }, 300);
    }
    
    if (emptyCart) {
        emptyCart.classList.add('fade-in');
    }
});