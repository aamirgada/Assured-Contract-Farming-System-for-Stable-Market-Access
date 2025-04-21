<?php
// Start session to access user data
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Redirect to login if not logged in
if (!$isLoggedIn) {
    header("Location: signUpLogin/login.html");
    exit();
}

// Database connection
require_once 'php/config.php';

// Get user's items from database
$user_id = $_SESSION['user_id'];
$items = [];
$error_message = '';

try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error retrieving items: " . $e->getMessage();
    error_log($error_message);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Items - AgriConnect</title>
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
        
        .items-section {
            padding: 120px 0 80px;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .items-section:before {
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
        
        .items-section:after {
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
        
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-header h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .section-header h1:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            border-radius: 10px;
        }
        
        .section-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .items-count {
            font-size: 1.1rem;
            color: #666;
            background: white;
            padding: 8px 15px;
            border-radius: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        
        .items-count span {
            font-weight: 600;
            color: #4CAF50;
        }
        
        .add-btn {
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
        }
        
        .add-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(76, 175, 80, 0.4);
        }
        
        .error-message {
            background-color: #fee;
            color: #e74c3c;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #e74c3c;
        }
        
        .error-message i {
            font-size: 1.5rem;
        }
        
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }
        
        .item-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .item-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        .item-header {
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            color: white;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .item-header:before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 80%);
            opacity: 0.3;
        }
        
        .item-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            position: relative;
        }
        
        .item-body {
            padding: 25px;
        }
        
        .item-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: #4CAF50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .item-price i {
            font-size: 1.4rem;
        }
        
        .item-detail {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #666;
        }
        
        .item-detail i {
            color: #4CAF50;
            font-size: 1.1rem;
            width: 25px;
            margin-right: 10px;
        }
        
        .item-detail strong {
            color: #333;
            margin-right: 5px;
        }
        
        .item-description {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .item-description h4 {
            font-size: 1rem;
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .item-description p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        .no-items {
            background: white;
            border-radius: 16px;
            padding: 50px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        
        .no-items-icon {
            width: 120px;
            height: 120px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }
        
        .no-items-icon i {
            font-size: 50px;
            color: #bbb;
        }
        
        .no-items h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
        }
        
        .no-items p {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.05rem;
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
            .section-header h1 {
                font-size: 2rem;
            }
            
            .items-header {
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .items-section {
                padding: 100px 0 60px;
            }
            
            .section-header h1 {
                font-size: 1.8rem;
            }
            
            .section-header p {
                font-size: 1rem;
            }
            
            .items-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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
            <a href="add_item.php"><i class="fas fa-plus-circle"></i> Add Item</a>
            <a href="view_items.php" class="active"><i class="fas fa-list"></i> View Items</a>
        </div>
    </nav>

    <section class="items-section">
        <div class="container">
            <div class="section-header">
                <h1>My Products</h1>
                <p>Manage your agricultural products listed on AgriConnect</p>
    </div>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <div><?php echo $error_message; ?></div>
            </div>
        <?php endif; ?>
        
        <?php if (empty($items)): ?>
            <div class="no-items">
                    <div class="no-items-icon">
                <i class="fas fa-box-open"></i>
                    </div>
                <h2>No Products Found</h2>
                    <p>You haven't added any products yet. Start listing your agricultural products now.</p>
                    <a href="add_item.php" class="add-btn"><i class="fas fa-plus-circle"></i> Add Your First Product</a>
            </div>
        <?php else: ?>
                <div class="items-header">
                    <div class="items-count">
                        You have <span><?php echo count($items); ?></span> product<?php echo count($items) > 1 ? 's' : ''; ?> listed
                    </div>
                    <a href="add_item.php" class="add-btn"><i class="fas fa-plus-circle"></i> Add New Product</a>
                </div>
                
            <div class="items-grid">
                <?php foreach ($items as $item): ?>
                    <div class="item-card">
                        <div class="item-header">
                            <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                        </div>
                        <div class="item-body">
                                <div class="item-price">
                                    <i class="fas fa-rupee-sign"></i> <?php echo number_format($item['price'], 2); ?>
                            </div>
                                
                            <div class="item-detail">
                                <i class="fas fa-cubes"></i>
                                    <strong>Quantity:</strong> <?php echo htmlspecialchars($item['quantity']); ?> units
                            </div>
                                
                            <div class="item-detail">
                                <i class="fas fa-calendar-alt"></i>
                                    <strong>Listed on:</strong> <?php echo date('M d, Y', strtotime($item['created_at'])); ?>
                            </div>
                                
                            <?php if (!empty($item['description'])): ?>
                                <div class="item-description">
                                        <h4><i class="fas fa-align-left"></i> Description</h4>
                                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
        // Removing the confirmDelete function as it's no longer needed
    </script>
</body>
</html>