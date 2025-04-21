<?php
// Start session
session_start();

// Database connection
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../signUpLogin/login.html");
    exit();
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $product_name = trim(htmlspecialchars($_POST['product_name'] ?? ''));
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $description = trim(htmlspecialchars($_POST['description'] ?? ''));
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $created_at = date('Y-m-d H:i:s');
    
    // Validate data
    $errors = [];
    
    if (empty($product_name)) {
        $errors[] = "Product name is required";
    }
    
    if ($price <= 0) {
        $errors[] = "Price must be greater than zero";
    }
    
    if ($quantity <= 0) {
        $errors[] = "Quantity must be greater than zero";
    }
    
    // If no errors, save to database
    if (empty($errors)) {
        try {
            // Insert query
            $stmt = $conn->prepare("INSERT INTO products (product_name, price, quantity, description, user_id, user_name, created_at) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            // Execute with parameters
            $result = $stmt->execute([$product_name, $price, $quantity, $description, $user_id, $user_name, $created_at]);
            
            if ($result) {
                // Show success message with a button to redirect to home page
                echo "
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Product Added</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            text-align: center;
                            padding: 50px;
                            background-color: #f9f9f9;
                        }
                        .success-container {
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: white;
                            padding: 30px;
                            border-radius: 8px;
                            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                        }
                        .success-message {
                            color: #388e3c;
                            margin-bottom: 20px;
                        }
                        .home-button {
                            background-color: #512da8;
                            color: white;
                            border: none;
                            padding: 12px 25px;
                            border-radius: 4px;
                            font-size: 16px;
                            cursor: pointer;
                            text-decoration: none;
                            display: inline-block;
                            transition: background-color 0.3s;
                            margin-right: 10px;
                        }
                        .home-button:hover {
                            background-color: #4527a0;
                        }
                        .add-more-button {
                            background-color: transparent;
                            border: 1px solid #512da8;
                            color: #512da8;
                            padding: 12px 25px;
                            border-radius: 4px;
                            font-size: 16px;
                            cursor: pointer;
                            text-decoration: none;
                            display: inline-block;
                            transition: background-color 0.3s;
                        }
                        .add-more-button:hover {
                            background-color: #f0f0f0;
                        }
                    </style>
                    <script>
                        // Automatic redirect after 5 seconds
                        setTimeout(function() {
                            window.location.href = '../index.php';
                        }, 5000);
                    </script>
                </head>
                <body>
                    <div class='success-container'>
                        <h2 class='success-message'>Product Added Successfully!</h2>
                        <p>Your product <strong>$product_name</strong> has been added to the database.</p>
                        <p>You will be redirected to the home page in 5 seconds...</p>
                        <div>
                            <a href='../index.php' class='home-button'>Return to Home Page</a>
                            <a href='../add_item.php' class='add-more-button'>Add Another Product</a>
                        </div>
                    </div>
                </body>
                </html>";
                exit();
            } else {
                throw new Exception("Failed to add product");
            }
        } catch (PDOException $e) {
            // Log error for debugging
            error_log("Add product error: " . $e->getMessage());
            
            // Show error alert and redirect
            echo "<script>
                alert('There was a problem adding your product. Please try again later.');
                window.location.href = '../add_item.php';
            </script>";
            exit();
        }
    } else {
        // Show validation errors alert and redirect
        $errorMsg = implode("\\n", $errors);
        echo "<script>
            alert('Please fix the following errors:\\n$errorMsg');
            window.location.href = '../add_item.php';
        </script>";
        exit();
    }
} else {
    // If not POST request, redirect to add item page
    header("Location: ../add_item.php");
    exit();
}
?>