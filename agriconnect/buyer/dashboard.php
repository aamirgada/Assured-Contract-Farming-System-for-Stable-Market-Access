<?php
// Start the session if not already started
session_start();
require_once '../php/config.php';

// Check if user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    // Redirect to login page
    header("Location: ../signUpLogin/login.html");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$user = [
    'id' => $_SESSION['user_id'],
    'fullname' => $_SESSION['user_name'],
    'email' => $_SESSION['user_email']
];

// Get buyer profile
try {
    $stmt = $conn->prepare("SELECT * FROM buyer_profiles WHERE user_id = ?");
    $stmt->execute([$user['id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $profile = null;
}

// Get all available products
try {
    // Updated query to match your products table structure
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $products = [];
    error_log("Error fetching products: " . $e->getMessage());
}

// Get purchased products
try {
    $stmt = $conn->prepare("SELECT p.*, pu.purchase_date, pu.quantity as purchased_quantity, pu.shipping_address, pu.contact_phone 
                           FROM purchases pu
                           JOIN products p ON pu.product_id = p.id
                           WHERE pu.buyer_id = ?
                           ORDER BY pu.purchase_date DESC");
    $stmt->execute([$user['id']]);
    $purchased = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $purchased = [];
    error_log("Error fetching purchased products: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard - AgriConnect</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .dashboard-content {
            padding: 20px;
        }
        
        .section-title {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
            color: #333;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .product-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        
        .product-header {
            background-color: #512da8;
            color: white;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .product-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
        
        .product-body {
            padding: 15px;
        }
        
        .product-detail {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .product-detail i {
            color: #512da8;
            margin-right: 8px;
            width: 18px;
            text-align: center;
        }
        
        .buy-btn {
            display: block;
            width: 100%;
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 15px;
            transition: background-color 0.3s;
        }
        
        .buy-btn:hover {
            background-color: #388e3c;
        }
        
        .buy-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        
        .purchased-label {
            display: block;
            width: 100%;
            background-color: #9e9e9e;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            font-weight: 600;
            margin-top: 15px;
        }
        
        .tab-container {
            margin-bottom: 20px;
        }
        
        .tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 600;
        }
        
        .tab.active {
            border-bottom-color: #512da8;
            color: #512da8;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .no-products {
            text-align: center;
            padding: 40px;
            background-color: #f9f9f9;
            border-radius: 8px;
            color: #757575;
        }
        
        .no-products i {
            font-size: 48px;
            color: #bdbdbd;
            margin-bottom: 15px;
        }
        .user-initial {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #512da8;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <nav class="dashboard-nav">
            <div class="nav-header">
                <!-- <img src="../images/logo.svg" alt="AgriConnect Logo"> -->
                <span>AgriConnect</span>
            </div>
            <div class="nav-links">
                <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                <!-- <a href="contracts.php"><i class="fas fa-file-contract"></i> Contracts</a>
                <a href="payments.php"><i class="fas fa-money-bill-wave"></i> Payments</a>
                <a href="farmers.php"><i class="fas fa-users"></i> Farmers</a>
                <a href="profile.php"><i class="fas fa-user"></i> Profile</a> -->
                <a href="../indexbuyer.php" class="active"><i class="fas fa-sign-out-alt"></i> Home </a>
                <a href="../signUpLogin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>

        <main class="dashboard-main">
            <header class="dashboard-header">
                <h1>Welcome, <?php echo htmlspecialchars($user['fullname']); ?></h1>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($user['email']); ?></span>
                    <?php if ($profile && !empty($profile['profile_image'])): ?>
                        <img src="<?php echo $profile['profile_image']; ?>" alt="Profile" class="user-avatar">
                    <?php else: ?>
                        <div class="user-initial"><?php echo strtoupper(substr($user['fullname'], 0, 1)); ?></div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="dashboard-content">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="message <?php echo $_SESSION['message_type'] ?? 'info'; ?>">
                        <?php 
                            echo htmlspecialchars($_SESSION['message']); 
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="tab-container">
                    <div class="tabs">
                        <div class="tab active" onclick="openTab(event, 'available-products')">Available Products</div>
                        <div class="tab" onclick="openTab(event, 'purchased-products')">My Purchases</div>
                    </div>
                </div>
                
                <div id="available-products" class="tab-content active">
                    <h2 class="section-title">Available Products</h2>
                    
                    <?php if (empty($products)): ?>
                        <div class="no-products">
                            <i class="fas fa-box-open"></i>
                            <h3>No Products Available</h3>
                            <p>There are currently no products listed for sale.</p>
                        </div>
                    <?php else: ?>
                        <div class="products-grid">
                            <?php foreach ($products as $product): 
                                // Check if product is already purchased by this buyer
                                $isPurchased = false;
                                foreach ($purchased as $purchase) {
                                    if ($purchase['id'] == $product['id']) {
                                        $isPurchased = true;
                                        break;
                                    }
                                }
                            ?>
                                <div class="product-card">
                                    <div class="product-header">
                                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                                    </div>
                                    <div class="product-body">
                                        <div class="product-detail">
                                            <i class="fas fa-user"></i>
                                            <span>Seller: <?php echo htmlspecialchars($product['user_name']); ?></span>
                                        </div>
                                        <div class="product-detail">
                                            <i class="fas fa-rupee-sign"></i>
                                            <span>Price: ₹<?php echo number_format($product['price'], 2); ?></span>
                                        </div>
                                        <div class="product-detail">
                                            <i class="fas fa-cubes"></i>
                                            <span>Quantity: <?php echo htmlspecialchars($product['quantity']); ?></span>
                                        </div>
                                        <div class="product-detail">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>Listed: <?php echo date('M d, Y', strtotime($product['created_at'])); ?></span>
                                        </div>
                                        <?php if (!empty($product['description'])): ?>
                                            <div class="product-detail">
                                                <i class="fas fa-info-circle"></i>
                                                <span><?php echo htmlspecialchars($product['description']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($product['quantity'] <= 0): ?>
                                            <div class="purchased-label">Out of Stock</div>
                                        <?php else: ?>
                                            <button type="button" class="buy-btn" onclick="openPurchaseModal(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['product_name']); ?>', <?php echo htmlspecialchars($product['quantity']); ?>)">Buy Now</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div id="purchased-products" class="tab-content">
                    <h2 class="section-title">My Purchases</h2>
                    
                    <?php if (empty($purchased)): ?>
                        <div class="no-products">
                            <i class="fas fa-shopping-cart"></i>
                            <h3>No Purchases Yet</h3>
                            <p>You haven't purchased any products yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="products-grid">
                            <?php foreach ($purchased as $product): ?>
                                <div class="product-card">
                                    <div class="product-header">
                                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                                    </div>
                                    <div class="product-body">
                                        <div class="product-detail">
                                            <i class="fas fa-user"></i>
                                            <span>Seller: <?php echo htmlspecialchars($product['user_name']); ?></span>
                                        </div>
                                        <div class="product-detail">
                                            <i class="fas fa-rupee-sign"></i>
                                            <span>Price: ₹<?php echo number_format($product['price'], 2); ?></span>
                                        </div>
                                        <div class="product-detail">
                                            <i class="fas fa-cubes"></i>
                                            <span>Quantity: <?php echo htmlspecialchars($product['purchased_quantity']); ?></span>
                                        </div>
                                        <div class="product-detail">
                                            <i class="fas fa-shopping-bag"></i>
                                            <span>Purchased: <?php echo date('M d, Y', strtotime($product['purchase_date'])); ?></span>
                                        </div>
                                        <div class="product-detail">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>Shipping Address: <?php echo htmlspecialchars($product['shipping_address']); ?></span>
                                        </div>
                                        <div class="product-detail">
                                            <i class="fas fa-phone"></i>
                                            <span>Contact Phone: <?php echo htmlspecialchars($product['contact_phone']); ?></span>
                                        </div>
                                        <?php if (!empty($product['description'])): ?>
                                            <div class="product-detail">
                                                <i class="fas fa-info-circle"></i>
                                                <span><?php echo htmlspecialchars($product['description']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <div id="purchase-modal" class="modal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
        <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <span class="close" onclick="closePurchaseModal()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 id="modal-product-name" style="margin-top: 0; color: #512da8;"></h2>
            
            <form id="purchase-form" action="process_purchase.php" method="POST">
                <input type="hidden" id="modal-product-id" name="product_id">
                
                <div style="margin-bottom: 15px;">
                    <label for="quantity" style="display: block; margin-bottom: 5px; font-weight: 600;">
                        <i class="fas fa-shopping-basket"></i> Quantity:
                    </label>
                    <input 
                        type="number" 
                        id="modal-quantity" 
                        name="quantity" 
                        value="1" 
                        min="1"
                        max="1"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                        required
                    >
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label for="shipping_address" style="display: block; margin-bottom: 5px; font-weight: 600;">
                        <i class="fas fa-map-marker-alt"></i> Shipping Address:
                    </label>
                    <textarea 
                        id="shipping_address" 
                        name="shipping_address" 
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-height: 80px;"
                        required
                        placeholder="Enter your complete shipping address"
                    ></textarea>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label for="contact_phone" style="display: block; margin-bottom: 5px; font-weight: 600;">
                        <i class="fas fa-phone"></i> Contact Phone:
                    </label>
                    <input 
                        type="tel" 
                        id="contact_phone" 
                        name="contact_phone" 
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                        required
                        placeholder="Enter your phone number"
                        pattern="[0-9]{10}"
                        title="Please enter a 10-digit phone number"
                    >
                </div>
                
                <button type="submit" class="buy-btn" style="margin-top: 10px;">Complete Purchase</button>
            </form>
        </div>
    </div>

    <!-- Purchase Confirmation Modal -->
    <div id="confirmation-modal" class="modal" style="display: none; position: fixed; z-index: 2; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
        <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); position: relative; animation: slideIn 0.5s ease;">
            <span class="close" onclick="closeConfirmationModal()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            
            <div class="confirmation-animation" style="text-align: center; margin-bottom: 20px;">
                <div class="checkmark-circle" style="width: 80px; height: 80px; position: relative; display: inline-block; vertical-align: top; margin-bottom: 20px;">
                    <div class="background" style="width: 80px; height: 80px; border-radius: 50%; background: #4caf50; position: absolute;"></div>
                    <div class="checkmark" style="border-right: 5px solid white; border-top: 5px solid white; width: 35px; height: 70px; position: absolute; left: 25px; top: 2px; transform-origin: left top; animation: checkmarkAnimation 0.5s ease forwards 0.3s; transform: scaleX(-1) rotate(135deg); opacity: 0;"></div>
                </div>
                <h2 style="color: #4caf50; margin-top: 0;">Purchase Successful!</h2>
            </div>
            
            <div class="purchase-details" style="background-color: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; animation: fadeIn 0.5s ease 0.5s forwards; opacity: 0;">
                <h3 style="margin-top: 0; color: #512da8; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px;">Purchase Details</h3>
                
                <div class="detail-row" style="display: flex; margin-bottom: 10px;">
                    <div class="detail-label" style="font-weight: 600; width: 40%; color: #666;">Product:</div>
                    <div class="detail-value" id="conf-product-name"></div>
                </div>
                
                <div class="detail-row" style="display: flex; margin-bottom: 10px;">
                    <div class="detail-label" style="font-weight: 600; width: 40%; color: #666;">Seller:</div>
                    <div class="detail-value" id="conf-seller-name"></div>
                </div>
                
                <div class="detail-row" style="display: flex; margin-bottom: 10px;">
                    <div class="detail-label" style="font-weight: 600; width: 40%; color: #666;">Unit Price:</div>
                    <div class="detail-value" id="conf-price"></div>
                </div>
                
                <div class="detail-row" style="display: flex; margin-bottom: 10px;">
                    <div class="detail-label" style="font-weight: 600; width: 40%; color: #666;">Quantity:</div>
                    <div class="detail-value" id="conf-quantity"></div>
                </div>
                
                <div class="detail-row" style="display: flex; margin-bottom: 10px; font-size: 18px; font-weight: bold; color: #512da8;">
                    <div class="detail-label" style="font-weight: 600; width: 40%; color: #666;">Total:</div>
                    <div class="detail-value" id="conf-total"></div>
                </div>
                
                <div class="detail-row" style="display: flex; margin-bottom: 10px;">
                    <div class="detail-label" style="font-weight: 600; width: 40%; color: #666;">Purchase Date:</div>
                    <div class="detail-value" id="conf-date"></div>
                </div>
                
                <div class="shipping-info" style="margin-top: 15px; border-top: 1px solid #ddd; padding-top: 15px;">
                    <h4 style="margin-top: 0; color: #512da8;">Shipping Information</h4>
                    
                    <div class="detail-row" style="display: flex; margin-bottom: 10px;">
                        <div class="detail-label" style="font-weight: 600; width: 40%; color: #666;">Address:</div>
                        <div class="detail-value" id="conf-address"></div>
                    </div>
                    
                    <div class="detail-row" style="display: flex; margin-bottom: 10px;">
                        <div class="detail-label" style="font-weight: 600; width: 40%; color: #666;">Phone:</div>
                        <div class="detail-value" id="conf-phone"></div>
                    </div>
                </div>
            </div>
            
            <button onclick="closeConfirmationModal()" class="buy-btn" style="background-color: #512da8; animation: fadeIn 0.5s ease 0.7s forwards; opacity: 0;">Continue Shopping</button>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        @keyframes checkmarkAnimation {
            0% { height: 0; width: 0; opacity: 0; }
            100% { height: 70px; width: 35px; opacity: 1; }
        }
    </style>

    <script>
        function openTab(evt, tabName) {
            // Hide all tab content
            var tabcontent = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            
            // Remove active class from all tabs
            var tabs = document.getElementsByClassName("tab");
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove("active");
            }
            
            // Show the current tab and add active class
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
        
        function openPurchaseModal(productId, productName, maxQuantity) {
            document.getElementById('modal-product-id').value = productId;
            document.getElementById('modal-product-name').textContent = productName;
            
            const quantityInput = document.getElementById('modal-quantity');
            quantityInput.max = maxQuantity;
            
            document.getElementById('purchase-modal').style.display = 'block';
        }
        
        function closePurchaseModal() {
            document.getElementById('purchase-modal').style.display = 'none';
            document.getElementById('purchase-form').reset();
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('purchase-modal');
            if (event.target == modal) {
                closePurchaseModal();
            }
            
            const confirmModal = document.getElementById('confirmation-modal');
            if (event.target == confirmModal) {
                closeConfirmationModal();
            }
        }
        
        // Function to close confirmation modal
        function closeConfirmationModal() {
            document.getElementById('confirmation-modal').style.display = 'none';
        }
        
        // Function to show purchase confirmation with details
        function showPurchaseConfirmation(details) {
            // Set confirmation details
            document.getElementById('conf-product-name').textContent = details.product_name;
            document.getElementById('conf-seller-name').textContent = details.seller_name;
            document.getElementById('conf-price').textContent = '₹' + parseFloat(details.price).toFixed(2);
            document.getElementById('conf-quantity').textContent = details.quantity;
            document.getElementById('conf-total').textContent = '₹' + parseFloat(details.total).toFixed(2);
            document.getElementById('conf-date').textContent = new Date(details.purchase_date).toLocaleDateString('en-IN');
            document.getElementById('conf-address').textContent = details.shipping_address;
            document.getElementById('conf-phone').textContent = details.contact_phone;
            
            // Show the modal
            document.getElementById('confirmation-modal').style.display = 'block';
        }
        
        // Check for purchase success on page load
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['purchase_success']) && $_SESSION['purchase_success']): ?>
                // Show confirmation modal with purchase details
                showPurchaseConfirmation(<?php echo json_encode($_SESSION['purchase_details']); ?>);
                
                // Clear the session variables
                <?php 
                    unset($_SESSION['purchase_success']);
                    unset($_SESSION['purchase_details']);
                ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>