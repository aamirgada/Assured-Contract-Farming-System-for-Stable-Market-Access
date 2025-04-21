<?php
// Start the session if not already started
session_start();
require_once '../php/config.php';

// Check if user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    // Redirect to login page with error message
    $_SESSION['message'] = 'Please login to make a purchase';
    $_SESSION['message_type'] = 'error';
    header("Location: ../signUpLogin/login.html");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $buyer_id = $_SESSION['user_id'];
    $purchase_date = date('Y-m-d H:i:s');
    
    // Get quantity with a default of 1 if not provided
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    // Get shipping address and contact phone
    $shipping_address = isset($_POST['shipping_address']) ? trim($_POST['shipping_address']) : '';
    $contact_phone = isset($_POST['contact_phone']) ? trim($_POST['contact_phone']) : '';
    
    // Validate inputs
    if (empty($shipping_address)) {
        $_SESSION['message'] = 'Shipping address is required';
        $_SESSION['message_type'] = 'error';
        header("Location: dashboard.php");
        exit();
    }
    
    if (empty($contact_phone)) {
        $_SESSION['message'] = 'Contact phone is required';
        $_SESSION['message_type'] = 'error';
        header("Location: dashboard.php");
        exit();
    }
    
    // Validate quantity
    if ($quantity <= 0) {
        $_SESSION['message'] = 'Quantity must be greater than zero';
        $_SESSION['message_type'] = 'error';
        header("Location: dashboard.php");
        exit();
    }
    
    try {
        // Begin transaction
        $conn->beginTransaction();

        // First check if product exists and get current quantity
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? FOR UPDATE");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            throw new Exception("Product not found");
        }
        
        // Check if the requested quantity is available
        if ($quantity > $product['quantity']) {
            throw new Exception("Requested quantity is not available. Available quantity: " . $product['quantity']);
        }
        
        // Insert purchase record
        $stmt = $conn->prepare("INSERT INTO purchases (product_id, buyer_id, purchase_date, quantity, shipping_address, contact_phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$product_id, $buyer_id, $purchase_date, $quantity, $shipping_address, $contact_phone]);
        
        // Update product inventory
        $new_quantity = $product['quantity'] - $quantity;
        $stmt = $conn->prepare("UPDATE products SET quantity = ? WHERE id = ?");
        $stmt->execute([$new_quantity, $product_id]);
        
        // Commit transaction
        $conn->commit();
        
        // Store purchase details in session for confirmation display
        $_SESSION['purchase_success'] = true;
        $_SESSION['purchase_details'] = [
            'product_name' => $product['product_name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'total' => $product['price'] * $quantity,
            'shipping_address' => $shipping_address,
            'contact_phone' => $contact_phone,
            'purchase_date' => $purchase_date,
            'seller_name' => $product['user_name']
        ];
        
        // Redirect with success message
        $_SESSION['message'] = 'Product purchased successfully!';
        $_SESSION['message_type'] = 'success';
        header("Location: dashboard.php");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Purchase error: " . $e->getMessage());
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = 'error';
        header("Location: dashboard.php");
        exit();
    }
} else {
    // Redirect if accessed directly without form submission
    $_SESSION['message'] = 'Invalid request';
    $_SESSION['message_type'] = 'error';
    header("Location: dashboard.php");
    exit();
}
?>