<?php
// Start session to access user data
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Redirect to login if not logged in
if (!$isLoggedIn) {
    header("Location: signUpLogin/login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item - AgriConnect</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .navbar {
            background: #fff;
            padding: 15px 5%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo span {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 6px;
            position: relative;
        }
        
        .nav-links a:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            transition: width 0.3s ease;
            border-radius: 10px;
        }
        
        .nav-links a:hover:after,
        .nav-links a.active:after {
            width: 100%;
        }
        
        .nav-links a i {
            font-size: 16px;
        }
        
        .nav-links a:hover {
            color: #4CAF50;
            background: rgba(76, 175, 80, 0.05);
        }
        
        .nav-links a.active {
            color: #4CAF50;
            background: rgba(76, 175, 80, 0.08);
            font-weight: 600;
        }
        
        .add-item-section {
            padding: 120px 0 80px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .add-item-section:before {
            content: '';
            position: absolute;
            top: -300px;
            right: -300px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: rgba(76, 175, 80, 0.03);
            z-index: 0;
        }
        
        .add-item-section:after {
            content: '';
            position: absolute;
            bottom: -300px;
            left: -300px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: rgba(76, 175, 80, 0.03);
            z-index: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }
        
        .add-item-container {
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            align-items: center;
        }
        
        .add-item-form {
            flex: 1;
            min-width: 320px;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .add-item-form:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .add-item-image {
            flex: 1;
            min-width: 320px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        .add-item-image img {
            max-width: 100%;
            height: auto;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.15));
        }
        
        .form-header {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .form-header h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .form-header h2:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            border-radius: 10px;
        }
        
        .form-header p {
            color: #666;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }
        
        .form-control {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f9f9fa;
        }
        
        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
            outline: none;
            background-color: #fff;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 14px;
            color: #888;
        }
        
        .input-with-icon input,
        .input-with-icon textarea {
            padding-left: 40px;
        }
        
        .textarea-with-counter {
            position: relative;
        }
        
        .char-counter {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 0.8rem;
            color: #888;
        }
        
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
        }
        
        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .form-footer a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .form-footer a:hover {
            color: #2E7D32;
            text-decoration: underline;
        }
        
        .form-tips {
            background: rgba(76, 175, 80, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        
        .form-tips h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #2E7D32;
        }
        
        .form-tips ul {
            padding-left: 20px;
        }
        
        .form-tips li {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        
        /* Footer styles */
        .footer {
            background-color: #2E7D32;
            color: white;
            padding: 0;
            font-size: 0.95rem;
        }
        
        .footer-content {
            padding: 70px 0;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }
        
        .footer-logo span {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            display: block;
        }
        
        .footer-desc {
            margin-bottom: 25px;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .footer-section h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }
        
        .footer-section h3:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 3px;
            background: white;
            border-radius: 10px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.9;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .footer-links a:hover {
            opacity: 1;
            transform: translateX(5px);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            color: white;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: white;
            color: #2E7D32;
            transform: translateY(-5px);
        }
        
        .footer-bottom {
            background: rgba(0,0,0,0.2);
            padding: 20px 0;
            font-size: 0.9rem;
        }
        
        .footer-bottom .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer-legal {
            display: flex;
            gap: 20px;
        }
        
        .footer-legal a {
            color: white;
            text-decoration: none;
            opacity: 0.9;
            transition: all 0.3s ease;
        }
        
        .footer-legal a:hover {
            opacity: 1;
        }
        
        @media (max-width: 992px) {
            .add-item-container {
                flex-direction: column;
            }
            
            .add-item-image {
                order: -1;
                margin-bottom: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .add-item-section {
                padding: 100px 0 60px;
            }
            
            .add-item-form {
                padding: 30px 20px;
            }
            
            .form-header h2 {
                font-size: 1.7rem;
            }
            
            .footer-content {
                padding: 50px 0;
            }
            
            .footer-bottom .footer-container {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .footer-legal {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <span>AgriConnect</span>
        </div>
        <div class="nav-links">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="index.php#services"><i class="fas fa-seedling"></i> Services</a>
            <a href="index.php#about"><i class="fas fa-info-circle"></i> About</a>
            <a href="contact_us.html"><i class="fas fa-envelope"></i> Contact</a>
            <a href="add_item.php" class="active"><i class="fas fa-plus-circle"></i> Add Item</a>
            <a href="view_items.php"><i class="fas fa-list"></i> View Items</a>
        </div>
    </nav>

    <section class="add-item-section">
        <div class="container">
            <div class="add-item-container">
                <div class="add-item-form">
                    <div class="form-header">
                        <h2>Add New Product</h2>
                        <p>List your agricultural products for buyers</p>
                    </div>
                    
                    <form action="php/process_item.php" method="POST" id="addItemForm">
                        <div class="form-group">
                            <label for="product_name">Product Name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-box"></i>
                                <input type="text" id="product_name" name="product_name" class="form-control" required placeholder="Enter product name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price (₹)</label>
                            <div class="input-with-icon">
                                <i class="fas fa-rupee-sign"></i>
                                <input type="number" id="price" name="price" step="0.01" min="0" class="form-control" required placeholder="Enter price">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="quantity">Available Quantity</label>
                            <div class="input-with-icon">
                                <i class="fas fa-cubes"></i>
                                <input type="number" id="quantity" name="quantity" min="1" class="form-control" required placeholder="Enter quantity">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <div class="textarea-with-counter">
                                <div class="input-with-icon">
                                    <i class="fas fa-align-left"></i>
                                    <textarea id="description" name="description" rows="4" class="form-control" maxlength="500" placeholder="Enter product description"></textarea>
                                </div>
                                <div class="char-counter"><span id="charCount">0</span>/500</div>
                            </div>
                        </div>
                        
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-plus-circle"></i> Add Product
                        </button>
                    </form>
                    
                    <div class="form-footer">
                        Want to see your products? <a href="view_items.php">View All Products</a>
                    </div>
                    
                    <div class="form-tips">
                        <h3><i class="fas fa-lightbulb"></i> Tips for Good Listings</h3>
                        <ul>
                            <li>Use clear and descriptive product names</li>
                            <li>Set competitive prices based on market rates</li>
                            <li>Specify accurate available quantities</li>
                            <li>Include details like quality, size, and harvesting date in the description</li>
                        </ul>
                    </div>
                </div>
                
                <div class="add-item-image">
                    <img src="images/tractor.svg" alt="Add Product" onerror="this.onerror=null; this.src='images/hero-image.svg';">
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-container">
                <div class="footer-section">
                    <div class="footer-logo">
                        <span>AgriConnect</span>
                    </div>
                    <p class="footer-desc">Bridging farmers and buyers through secure contract farming and empowering agricultural communities.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php#services">Services</a></li>
                        <li><a href="index.php#about">About Us</a></li>
                        <li><a href="contact_us.html">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Services</h3>
                    <ul class="footer-links">
                        <li><a href="services/contracts.html">Secure Contracts</a></li>
                        <li><a href="services/payments.html">Guaranteed Payments</a></li>
                        <li><a href="services/market-analysis.html">Market Analysis</a></li>
                        <li><a href="services/community.html">Community Support</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Farming Street, Agricultural District</li>
                        <li><i class="fas fa-phone"></i> +1 234 567 8901</li>
                        <li><i class="fas fa-envelope"></i> info@agriconnect.com</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-container">
                <p>&copy; <?php echo date('Y'); ?> AgriConnect. All Rights Reserved.</p>
                <div class="footer-legal">
                    <a href="privacy-policy.html">Privacy Policy</a>
                    <a href="terms-of-service.html">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Character counter for description
        const descriptionTextarea = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        
        descriptionTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength;
            
            // Change color when approaching max length
            if (currentLength > 400) {
                charCount.style.color = '#ff6b6b';
            } else {
                charCount.style.color = '#888';
            }
        });
        
        // Form validation
        const form = document.getElementById('addItemForm');
        form.addEventListener('submit', function(event) {
            const productName = document.getElementById('product_name').value.trim();
            const price = parseFloat(document.getElementById('price').value);
            const quantity = parseInt(document.getElementById('quantity').value);
            
            let isValid = true;
            
            if (productName === '') {
                alert('Please enter a product name');
                isValid = false;
            }
            
            if (isNaN(price) || price <= 0) {
                alert('Please enter a valid price greater than zero');
                isValid = false;
            }
            
            if (isNaN(quantity) || quantity <= 0) {
                alert('Please enter a valid quantity greater than zero');
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>