<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - AgriConnect</title>
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
            margin: 0;
            background: none;
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
            transform: none;
        }
        
        .nav-links a.active {
            color: #4CAF50;
            background: rgba(76, 175, 80, 0.08);
            font-weight: 600;
        }
        
        .contact-section {
            padding: 120px 0 80px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .contact-section:before {
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
        
        .contact-section:after {
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
        
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            align-items: center;
        }
        
        .contact-form-container {
            flex: 1;
            min-width: 320px;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .contact-form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .contact-info {
            flex: 1;
            min-width: 320px;
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
        
        .textarea-with-icon i {
            top: 24px;
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
            background-color: #388e3c;
        }
        
        .submit-btn:active {
            transform: translateY(0);
            box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
        }
        
        .contact-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
        }
        
        .contact-card .icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
            flex-shrink: 0;
        }
        
        .contact-card .details {
            flex-grow: 1;
        }
        
        .contact-card h3 {
            margin: 0 0 5px;
            color: #333;
            font-size: 1.1rem;
        }
        
        .contact-card p {
            margin: 0;
            color: #666;
            font-size: 0.95rem;
        }
        
        .social-contact {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .social-contact a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: white;
            color: #4CAF50;
            transition: all 0.3s ease;
            font-size: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            margin: 0;
            padding: 0;
        }
        
        .social-contact a:hover {
            transform: translateY(-5px);
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            color: white;
        }
        
        /* Map container */
        .map-container {
            height: 200px;
            border-radius: 16px;
            overflow: hidden;
            margin-top: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
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
            margin: 0;
            padding: 0;
            background: none;
        }
        
        .footer-links a:hover {
            opacity: 1;
            transform: translateX(5px);
            background: none;
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
            margin: 0;
            padding: 0;
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
            margin: 0;
            padding: 0;
            background: none;
        }
        
        .footer-legal a:hover {
            opacity: 1;
            background: none;
        }
        
        @media (max-width: 992px) {
            .contact-container {
                flex-direction: column;
            }
            
            .contact-info {
                order: -1;
                margin-bottom: 30px;
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .contact-section {
                padding: 100px 0 60px;
            }
            
            .contact-form-container {
                padding: 30px 20px;
            }
            
            .form-header h2 {
                font-size: 1.7rem;
            }
            
            .contact-card {
                padding: 20px;
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
            <a href="indexbuyer.php"><i class="fas fa-home"></i> Home</a>
            <a href="indexbuyer.php#services"><i class="fas fa-seedling"></i> Services</a>
            <a href="indexbuyer.php#about"><i class="fas fa-info-circle"></i> About</a>
            <a href="../php/conatctbuyer.php" class="active"><i class="fas fa-envelope"></i> Contact</a>
            <a href="buyer/dashboard.php"><i class="fas fa-plus-circle"></i> buy item</a>
            <!-- <a href="view_items.php"><i class="fas fa-list"></i> View Items</a> -->
        </div>
    </nav>

    <section class="contact-section">
        <div class="container">
            <div class="contact-container">
                <div class="contact-form-container">
                    <div class="form-header">
                        <h2>Get In Touch</h2>
                        <p>We'd love to hear from you! Send us a message.</p>
                    </div>
                    
                    <form action="php/contactbuyerr.php" method="POST" id="contactForm">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="name" name="name" class="form-control" required placeholder="Enter your name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" class="form-control" required placeholder="Enter your email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile Number</label>
                            <div class="input-with-icon">
                                <i class="fas fa-phone"></i>
                                <input type="text" id="mobile" name="mobile" class="form-control" required placeholder="Enter your mobile number">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <div class="input-with-icon">
                                <i class="fas fa-tag"></i>
                                <input type="text" id="subject" name="subject" class="form-control" required placeholder="Enter message subject">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <div class="input-with-icon textarea-with-icon">
                                <i class="fas fa-comment-alt"></i>
                                <textarea id="message" name="message" rows="5" class="form-control" required placeholder="Enter your message"></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
                
                <div class="contact-info">
                    <div class="contact-card">
                        <div class="icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="details">
                            <h3>Our Location</h3>
                            <p>123 Farming Street, Agricultural District</p>
                        </div>
                    </div>
                    
                    <div class="contact-card">
                        <div class="icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="details">
                            <h3>Phone Number</h3>
                            <p>+1 234 567 8901</p>
                        </div>
                    </div>
                    
                    <div class="contact-card">
                        <div class="icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="details">
                            <h3>Email Address</h3>
                            <p>info@agriconnect.com</p>
                        </div>
                    </div>
                    
                    <div class="social-contact">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                    
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387193.30599092975!2d-74.25986432970718!3d40.69714941680757!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1618555157674!5m2!1sen!2sin" allowfullscreen="" loading="lazy"></iframe>
                    </div>
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
                        <li><a href="conatctbuyer.php">Contact</a></li>
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
                <p>&copy; <script>document.write(new Date().getFullYear())</script> AgriConnect. All Rights Reserved.</p>
                <div class="footer-legal">
                    <a href="privacy-policy.html">Privacy Policy</a>
                    <a href="terms-of-service.html">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Form validation
        const form = document.getElementById('contactForm');
        form.addEventListener('submit', function(event) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();
            
            let isValid = true;
            
            if (name === '') {
                alert('Please enter your name');
                isValid = false;
            }
            
            if (email === '') {
                alert('Please enter your email');
                isValid = false;
            } else if (!isValidEmail(email)) {
                alert('Please enter a valid email address');
                isValid = false;
            }
            
            if (subject === '') {
                alert('Please enter a subject');
                isValid = false;
            }
            
            if (message === '') {
                alert('Please enter your message');
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
        
        // Email validation function
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</body>
</html>