<?php
// Start session to access user data
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriConnect - Assured Contract Farming System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="images/logo.svg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
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
        
        .user-info-nav {
            display: flex;
            align-items: center;
            margin-left: 20px;
            position: relative;
        }
        
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(46, 125, 50, 0.3);
            transition: transform 0.3s;
        }
        
        .user-avatar:hover {
            transform: translateY(-2px);
        }
        
        .user-dropdown {
            position: relative;
            cursor: pointer;
        }
        
        .user-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 240px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            z-index: 1;
            border-radius: 12px;
            margin-top: 12px;
            animation: fadeInUp 0.3s;
        }
        
        .show-dropdown {
            display: block;
        }
        
        .user-dropdown-content a {
            color: #333;
            padding: 15px 20px;
            text-decoration: none;
            display: block;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            border-radius: 0;
        }
        
        .user-dropdown-content a:after {
            display: none;
        }
        
        .user-dropdown-content a:hover {
            background-color: #f5f5f5;
            color: #4CAF50;
        }
        
        .user-dropdown-content a:first-child {
            border-radius: 12px 12px 0 0;
        }
        
        .user-dropdown-content a:last-child {
            border-radius: 0 0 12px 12px;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 16px;
        }
        
        .user-role {
            font-size: 13px;
            color: #666;
            text-transform: capitalize;
            font-weight: 500;
        }
        
        /* Hero Section */
        .hero {
            padding: 180px 0 80px;
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(245,247,250,0.95) 100%), url('images/agriculture-bg.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero:before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(76, 175, 80, 0.05);
            z-index: 0;
        }
        
        .hero:after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(76, 175, 80, 0.03);
            z-index: 0;
        }
        
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
            position: relative;
            z-index: 1;
            padding: 0 20px;
        }
        
        .hero-text {
            flex: 1;
            text-align: left;
        }
        
        .hero-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        .hero-image:before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(76, 175, 80, 0.1);
            z-index: -1;
            animation: pulse 3s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.7; }
            50% { transform: scale(1.05); opacity: 0.4; }
            100% { transform: scale(0.95); opacity: 0.7; }
        }
        
        .hero-image img {
            max-width: 90%;
            height: auto;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.15));
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            font-weight: 700;
            color: #333;
            position: relative;
            display: inline-block;
        }
        
        .hero h1:after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 80px;
            height: 5px;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            border-radius: 10px;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 2.5rem;
            max-width: 90%;
            line-height: 1.6;
        }
        
        .hero-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 2.5rem;
        }
        
        .stat-item {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.3s ease;
            width: 30%;
        }
        
        .stat-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            color: #4CAF50;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #555;
            font-weight: 500;
        }
        
        .cta-buttons {
            display: flex;
            gap: 15px;
            margin-top: 2.5rem;
        }
        
        .cta-primary, .cta-secondary {
            padding: 15px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .cta-primary {
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            color: white;
            box-shadow: 0 8px 15px rgba(76, 175, 80, 0.3);
        }
        
        .cta-secondary {
            border: 2px solid #4CAF50;
            color: #4CAF50;
        }
        
        .cta-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(76, 175, 80, 0.4);
        }
        
        .cta-secondary:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(76, 175, 80, 0.15);
            background: rgba(76, 175, 80, 0.05);
        }
        
        .cta-primary:before, .cta-secondary:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.5s;
        }
        
        .cta-primary:hover:before, .cta-secondary:hover:before {
            transform: scaleX(1);
            transform-origin: left;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        
        @media (max-width: 992px) {
            .hero {
                padding: 150px 0 60px;
            }
            
            .hero-content {
                flex-direction: column;
                text-align: center;
            }
            
            .hero-text {
                text-align: center;
                order: 2;
            }
            
            .hero-image {
                order: 1;
                margin-bottom: 40px;
            }
            
            .hero h1:after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .hero-subtitle {
                max-width: 100%;
            }
            
            .hero-stats {
                justify-content: center;
            }
            
            .cta-buttons {
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 3%;
            }
            
            .nav-links {
                gap: 5px;
            }
            
            .nav-links a {
                padding: 6px 10px;
                font-size: 14px;
            }
            
            .logo span {
                font-size: 1.5rem;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .stat-item {
                padding: 15px;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .cta-primary, .cta-secondary {
                padding: 12px 25px;
                font-size: 15px;
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
            <a href="#home" class="active"><i class="fas fa-home"></i> Home</a>
            <a href="#services"><i class="fas fa-seedling"></i> Services</a>
            <a href="#about"><i class="fas fa-info-circle"></i> About</a>
            <a href="conatctbuyer.php"><i class="fas fa-envelope"></i> Contact</a>
            <a href="buyer/dashboard.php"><i class="fas fa-plus-circle"></i> Buy Item</a>
            <!-- <a href="view_items.php"><i class="fas fa-list"></i> View Items</a> -->
            
            <?php if ($isLoggedIn): ?>
            <div class="user-info-nav">
                <div class="user-dropdown">
                    <div class="user-avatar" onclick="toggleDropdown()">
                        <?php echo substr($_SESSION['user_name'], 0, 1); ?>
                    </div>
                    <div id="userDropdown" class="user-dropdown-content">
                        <div style="padding: 15px 20px; border-bottom: 1px solid #eee;">
                            <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                            <div class="user-role"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
                            <div style="font-size: 13px; color: #777; margin-top: 3px;"><?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
                        </div>
                        <?php if ($_SESSION['role'] === 'farmer'): ?>
                            <a href="farmer/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <?php elseif ($_SESSION['role'] === 'buyer'): ?>
                            <a href="buyer/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <?php endif; ?>
                        <a href="signUpLogin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="auth-buttons">
                <a href="signUpLogin/login.html" class="login-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="signUpLogin/signup.html" class="signup-btn"><i class="fas fa-user-plus"></i> Sign Up</a>
            </div>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero" id="home">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Welcome to AgriConnect</h1>
                <p class="hero-subtitle">Bridging farmers and buyers through secure contract farming and empowering agricultural communities.</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">10,000+</span>
                        <span class="stat-label">Active Farmers</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Trusted Buyers</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">95%</span>
                        <span class="stat-label">Success Rate</span>
                    </div>
                </div>
                <div class="cta-buttons">
                    <!-- <a href="signUpLogin/signup.html" class="cta-primary"><i class="fas fa-user-plus"></i> Get Started</a> -->
                    <!-- <a href="#learn-more" class="cta-secondary"><i class="fas fa-arrow-down"></i> Learn More</a> -->
                </div>
            </div>
            <div class="hero-image">
                <img src="images/hero-image.svg" alt="Agricultural Illustration">
            </div>
        </div>
    </header>

    <section class="services" id="services">
        <div class="section-container">
            <div class="section-header">
        <h2>Our Services</h2>
                <p>Comprehensive solutions for farmers and buyers to connect, trade, and grow together</p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                <i class="fas fa-handshake"></i>
                    </div>
                <h3>Secure Contracts</h3>
                    <p>Legally binding agreements that protect both farmers and buyers with transparent terms.</p>
                    <a href="contacts.php" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                <i class="fas fa-money-bill-wave"></i>
                    </div>
                <h3>Guaranteed Payments</h3>
                    <p>Timely and secure payment processing with escrow protection and transaction history.</p>
                    <a href="secure.php" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                <i class="fas fa-chart-line"></i>
                    </div>
                <h3>Market Analysis</h3>
                    <p>Real-time market trends, pricing insights, and demand forecasts to make informed decisions.</p>
                    <a href="market.php" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                <i class="fas fa-users"></i>
                    </div>
                <h3>Community Support</h3>
                    <p>Connect with other farmers and buyers to share knowledge, experiences and best practices.</p>
                    <a href="comunity.php" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        </div>
        
        <style>
            .services {
                padding: 100px 0;
                background-color: #fff;
                position: relative;
                overflow: hidden;
            }
            
            .services:before {
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
            
            .section-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
                position: relative;
                z-index: 1;
            }
            
            .section-header {
                text-align: center;
                margin-bottom: 60px;
            }
            
            .section-header h2 {
                font-size: 2.5rem;
                color: #333;
                margin-bottom: 15px;
                position: relative;
                display: inline-block;
            }
            
            .section-header h2:after {
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
                font-size: 1.1rem;
                color: #666;
                max-width: 700px;
                margin: 0 auto;
            }
            
            .services-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 30px;
            }
            
            .service-card {
                background: #fff;
                border-radius: 16px;
                padding: 40px 30px;
                text-align: center;
                transition: all 0.3s ease;
                position: relative;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                border: 1px solid #f1f1f1;
                height: 100%;
                display: flex;
                flex-direction: column;
            }
            
            .service-card:hover {
                transform: translateY(-15px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                border-color: #e0e0e0;
            }
            
            .service-card:before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 0;
                background: linear-gradient(to top, rgba(76, 175, 80, 0.05), transparent);
                transition: height 0.3s ease;
                border-radius: 0 0 16px 16px;
                z-index: -1;
            }
            
            .service-card:hover:before {
                height: 50%;
            }
            
            .service-icon {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.2));
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 25px;
                transition: all 0.3s ease;
            }
            
            .service-card:hover .service-icon {
                background: linear-gradient(135deg, #4CAF50, #2E7D32);
                transform: scale(1.1);
                box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
            }
            
            .service-icon i {
                font-size: 30px;
                color: #4CAF50;
                transition: all 0.3s ease;
            }
            
            .service-card:hover .service-icon i {
                color: white;
            }
            
            .service-card h3 {
                font-size: 1.4rem;
                margin-bottom: 20px;
                color: #333;
                font-weight: 600;
            }
            
            .service-card p {
                font-size: 0.95rem;
                color: #666;
                line-height: 1.6;
                margin-bottom: 25px;
                flex-grow: 1;
            }
            
            .service-link {
                display: inline-flex;
                align-items: center;
                text-decoration: none;
                color: #4CAF50;
                font-weight: 600;
                font-size: 0.95rem;
                transition: all 0.3s ease;
                gap: 8px;
            }
            
            .service-link i {
                transition: transform 0.3s ease;
            }
            
            .service-link:hover {
                color: #2E7D32;
            }
            
            .service-link:hover i {
                transform: translateX(5px);
            }
            
            @media (max-width: 768px) {
                .services {
                    padding: 70px 0;
                }
                
                .section-header h2 {
                    font-size: 2rem;
                }
                
                .service-card {
                    padding: 30px 20px;
                }
            }
        </style>
    </section>

    <section class="about" id="about">
        <div class="section-container">
            <div class="section-header">
            <h2>About AgriConnect</h2>
                <p>Connecting farmers and buyers in a secure ecosystem for sustainable agriculture</p>
            </div>
            
            <div class="about-wrapper">
                <div class="about-content">
                    <div class="about-text">
                        <p class="about-intro">AgriConnect is a revolutionary platform that brings together farmers and buyers in a secure and transparent environment. Our mission is to ensure stable income for farmers through guaranteed contracts and timely payments while providing buyers with quality produce.</p>
                        
                        <div class="about-highlights">
                            <div class="highlight-item">
                                <div class="highlight-icon">
                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="highlight-content">
                    <h3>Secure Platform</h3>
                                    <p>Advanced security measures to protect all transactions and contracts</p>
                </div>
                            </div>
                            
                            <div class="highlight-item">
                                <div class="highlight-icon">
                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="highlight-content">
                    <h3>Market Insights</h3>
                    <p>Real-time market analysis and price trends for better decision making</p>
                </div>
                            </div>
                            
                            <div class="highlight-item">
                                <div class="highlight-icon">
                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="highlight-content">
                    <h3>Trusted Network</h3>
                    <p>Verified farmers and buyers ensuring quality transactions</p>
                                </div>
                            </div>
                </div>
            </div>

                    <div class="about-image">
                        <img src="images/tractor.svg" alt="About AgriConnect" onerror="this.onerror=null; this.src='images/hero-image.svg';">
                    </div>
                </div>
                
                <div class="about-stats-container">
                <div class="stat-card">
                    <span class="stat-number">10,000+</span>
                    <span class="stat-label">Active Farmers</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Trusted Buyers</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">95%</span>
                    <span class="stat-label">Success Rate</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Support Available</span>
                </div>
            </div>

                <div class="about-cta">
                    <h3>Ready to get started with AgriConnect?</h3>
                    <p>Join our growing community of farmers and buyers today</p>
                    <div class="cta-buttons">
                        <!-- <a href="signUpLogin/signup.html" class="cta-primary"><i class="fas fa-user-plus"></i> Sign Up Now</a> -->
                        <a href="conatctbuyer.php" class="cta-secondary"><i class="fas fa-question-circle"></i> Ask Questions</a>
            </div>
                    </div>
                    </div>
                    </div>
        
        <style>
            .about {
                padding: 100px 0;
                background-color: #f8f9fa;
                position: relative;
                overflow: hidden;
            }
            
            .about:before {
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
            
            .about-wrapper {
                position: relative;
                z-index: 1;
            }
            
            .about-content {
                display: flex;
                align-items: center;
                gap: 50px;
                margin-bottom: 60px;
            }
            
            .about-text {
                flex: 1;
            }
            
            .about-image {
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .about-image img {
                max-width: 100%;
                height: auto;
                animation: float 6s ease-in-out infinite;
                filter: drop-shadow(0 10px 15px rgba(0,0,0,0.15));
            }
            
            .about-intro {
                font-size: 1.1rem;
                color: #333;
                line-height: 1.7;
                margin-bottom: 40px;
            }
            
            .about-highlights {
                display: flex;
                flex-direction: column;
                gap: 25px;
            }
            
            .highlight-item {
                display: flex;
                align-items: center;
                gap: 20px;
                padding: 20px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
                transition: all 0.3s ease;
            }
            
            .highlight-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            }
            
            .highlight-icon {
                width: 60px;
                height: 60px;
                border-radius: 12px;
                background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.2));
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            
            .highlight-icon i {
                font-size: 24px;
                color: #4CAF50;
            }
            
            .highlight-content h3 {
                font-size: 1.1rem;
                margin-bottom: 8px;
                color: #333;
                font-weight: 600;
            }
            
            .highlight-content p {
                font-size: 0.95rem;
                color: #666;
                line-height: 1.5;
            }
            
            .about-stats-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 30px;
                margin-bottom: 60px;
            }
            
            .stat-card {
                background: white;
                padding: 30px 20px;
                border-radius: 12px;
                text-align: center;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
                transition: all 0.3s ease;
            }
            
            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            }
            
            .stat-card .stat-number {
                display: block;
                font-size: 2.5rem;
                font-weight: 700;
                color: #4CAF50;
                margin-bottom: 10px;
            }
            
            .stat-card .stat-label {
                font-size: 1rem;
                color: #555;
                font-weight: 500;
            }
            
            .about-cta {
                background: linear-gradient(45deg, #4CAF50, #2E7D32);
                padding: 50px;
                border-radius: 16px;
                text-align: center;
                color: white;
                box-shadow: 0 15px 30px rgba(76, 175, 80, 0.3);
            }
            
            .about-cta h3 {
                font-size: 1.8rem;
                margin-bottom: 15px;
                font-weight: 600;
            }
            
            .about-cta p {
                font-size: 1.1rem;
                margin-bottom: 30px;
                opacity: 0.9;
            }
            
            .about-cta .cta-buttons {
                display: flex;
                justify-content: center;
                gap: 20px;
            }
            
            .about-cta .cta-primary {
                background: white;
                color: #4CAF50;
                padding: 15px 30px;
                border-radius: 30px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }
            
            .about-cta .cta-secondary {
                background: rgba(255,255,255,0.2);
                color: white;
                padding: 15px 30px;
                border-radius: 30px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }
            
            .about-cta .cta-primary:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
            
            .about-cta .cta-secondary:hover {
                transform: translateY(-5px);
                background: rgba(255,255,255,0.3);
            }
            
            @media (max-width: 992px) {
                .about-content {
                    flex-direction: column;
                }
                
                .about-image {
                    order: -1;
                    margin-bottom: 30px;
                }
                
                .about-cta {
                    padding: 30px;
                }
                
                .about-cta h3 {
                    font-size: 1.5rem;
                }
                
                .about-cta p {
                    font-size: 1rem;
                }
                
                .about-cta .cta-buttons {
                    flex-direction: column;
                    align-items: center;
                    gap: 15px;
                }
            }
            
            @media (max-width: 768px) {
                .about {
                    padding: 70px 0;
                }
                
                .highlight-item {
                    flex-direction: column;
                    text-align: center;
                }
                
                .about-stats-container {
                    grid-template-columns: repeat(2, 1fr);
                }
                
                .stat-card .stat-number {
                    font-size: 2rem;
                }
            }
            
            @media (max-width: 576px) {
                .about-stats-container {
                    grid-template-columns: 1fr;
                }
            }
        </style>
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
                        <li><a href="#home">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="contact_us.html">Contact</a></li>
                    </ul>
            </div>
                
            <div class="footer-section">
                    <h3>Services</h3>
                    <ul class="footer-links">
                        <li><a href="contacts.php">Secure Contracts</a></li>
                        <li><a href="secure.php">Guaranteed Payments</a></li>
                        <li><a href="market.php">Market Analysis</a></li>
                        <li><a href="comunity.php">Community Support</a></li>
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
        
        <style>
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
            
            .contact-info {
                list-style: none;
                padding: 0;
            }
            
            .contact-info li {
                margin-bottom: 12px;
                display: flex;
                align-items: flex-start;
                gap: 10px;
                opacity: 0.9;
            }
            
            .contact-info li i {
                margin-top: 5px;
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
            
            @media (max-width: 768px) {
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
    </footer>

<script>
        // User dropdown toggle
    function toggleDropdown() {
        document.getElementById("userDropdown").classList.toggle("show-dropdown");
    }
    
        // Close the dropdown if clicked outside
    window.onclick = function(event) {
        if (!event.target.matches('.user-avatar')) {
            var dropdowns = document.getElementsByClassName("user-dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show-dropdown')) {
                    openDropdown.classList.remove('show-dropdown');
                }
            }
        }
    }
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
                
                // Update active state in navbar
                document.querySelectorAll('.nav-links a').forEach(navLink => {
                    navLink.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
        
        // Update active menu item based on scroll position
        window.addEventListener('scroll', function() {
            let scrollPosition = window.scrollY + 100;
            
            document.querySelectorAll('section').forEach(section => {
                let sectionTop = section.offsetTop;
                let sectionHeight = section.offsetHeight;
                let sectionId = section.getAttribute('id');
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    document.querySelectorAll('.nav-links a').forEach(navLink => {
                        navLink.classList.remove('active');
                        if (navLink.getAttribute('href') === '#' + sectionId) {
                            navLink.classList.add('active');
                        }
                    });
                }
            });
        });
</script>
</body>
</html>