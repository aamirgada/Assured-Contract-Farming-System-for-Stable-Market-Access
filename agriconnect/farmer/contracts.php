<?php
session_start();
require_once '../php/config.php';

// Check if user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'farmer') {
    // Redirect to login page with error message
    header("Location: ../signUpLogin/login.html");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Farmer';
$user_email = $_SESSION['user_email'] ?? '';

// Get contracts for this farmer
try {
    $stmt = $conn->prepare("SELECT c.*, u.fullname as buyer_name 
                           FROM contracts c 
                           JOIN users u ON c.buyer_id = u.id 
                           WHERE c.farmer_id = ? 
                           ORDER BY c.created_at DESC");
    $stmt->execute([$user_id]);
    $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $contracts = [];
    error_log("Error fetching contracts: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contracts - Farmer Dashboard - AgriConnect</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom styles to remove vertical scrolling */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .dashboard-container {
            height: 100vh;
            display: flex;
            margin: 0;
            padding: 0;
        }
        .dashboard-nav {
            padding-top: 0;
            margin-top: 0;
        }
        .nav-header {
            padding: 8px;
            margin: 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .dashboard-main {
            overflow-y: auto;
            max-height: 100vh;
            padding-top: 0;
            margin-top: 0;
        }
        .dashboard-header {
            padding: 10px 20px;
            margin-top: 0;
        }
        .dashboard-header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .dashboard-content {
            padding: 10px;
        }
        
        /* Contract specific styles */
        .contracts-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .contract-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
        }
        
        .contract-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .contract-title {
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .contract-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .status-active {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .status-pending {
            background-color: #fff8e1;
            color: #ff8f00;
        }
        
        .status-completed {
            background-color: #e8f5e9;
            color: #388e3c;
        }
        
        .status-cancelled {
            background-color: #ffebee;
            color: #d32f2f;
        }
        
        .contract-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .detail-item {
            margin-bottom: 8px;
        }
        
        .detail-label {
            font-size: 0.8rem;
            color: #666;
        }
        
        .detail-value {
            font-weight: 500;
        }
        
        .contract-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
        }
        
        .btn-primary {
            background-color: #512da8;
            color: white;
        }
        
        .btn-secondary {
            background-color: #f5f5f5;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .empty-contracts {
            text-align: center;
            padding: 30px;
            color: #666;
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">
    <div class="dashboard-container" style="margin: 0; padding: 0;">
        <nav class="dashboard-nav" style="margin: 0; padding: 0;">
            <div class="nav-header" style="padding: 8px; margin: 0; display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 1.2rem; font-weight: bold;">AgriConnect</span>
                <a href="../index.php" style="text-decoration: none; color: #512da8; font-size: 0.9rem; background-color: #f5f5f5; padding: 5px 10px; border-radius: 4px; display: flex; align-items: center;">
                    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Home
                </a>
            </div>
            <div class="nav-links">
                <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="contracts.php" class="active"><i class="fas fa-file-contract"></i> Contracts</a>
                <a href="payments.php"><i class="fas fa-money-bill-wave"></i> Payments</a>
                <!-- <a href="profile.php"><i class="fas fa-user"></i> Profile</a> -->
                <!-- <a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> -->
            </div>
        </nav>

        <main class="dashboard-main">
            <header class="dashboard-header">
                <h1>Your Contracts</h1>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($user_email); ?></span>
                    <div class="user-avatar" style="width: 35px; height: 35px; border-radius: 50%; background-color: #512da8; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        <?php echo substr(htmlspecialchars($user_name), 0, 1); ?>
                    </div>
                </div>
            </header>

            <div class="dashboard-content">
                <div class="contracts-list">
                    <?php if (count($contracts) > 0): ?>
                        <?php foreach ($contracts as $contract): ?>
                            <div class="contract-card">
                                <div class="contract-header">
                                    <div class="contract-title">
                                        <?php echo htmlspecialchars($contract['crop_name'] ?? 'Contract'); ?> - 
                                        <?php echo htmlspecialchars($contract['contract_id'] ?? '#'.rand(1000, 9999)); ?>
                                    </div>
                                    <?php 
                                        $statusClass = '';
                                        switch ($contract['status'] ?? 'pending') {
                                            case 'active':
                                                $statusClass = 'status-active';
                                                break;
                                            case 'completed':
                                                $statusClass = 'status-completed';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'status-cancelled';
                                                break;
                                            default:
                                                $statusClass = 'status-pending';
                                        }
                                    ?>
                                    <div class="contract-status <?php echo $statusClass; ?>">
                                        <?php echo ucfirst(htmlspecialchars($contract['status'] ?? 'Pending')); ?>
                                    </div>
                                </div>
                                <div class="contract-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Buyer</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($contract['buyer_name'] ?? 'Unknown Buyer'); ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Quantity</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($contract['quantity'] ?? '0'); ?> kg</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Price per Unit</div>
                                        <div class="detail-value">₹<?php echo htmlspecialchars($contract['price_per_unit'] ?? '0'); ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Total Value</div>
                                        <div class="detail-value">₹<?php echo htmlspecialchars(($contract['quantity'] ?? 0) * ($contract['price_per_unit'] ?? 0)); ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Start Date</div>
                                        <div class="detail-value"><?php echo isset($contract['start_date']) ? date('d M Y', strtotime($contract['start_date'])) : 'Not set'; ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">End Date</div>
                                        <div class="detail-value"><?php echo isset($contract['end_date']) ? date('d M Y', strtotime($contract['end_date'])) : 'Not set'; ?></div>
                                    </div>
                                </div>
                                <div class="contract-actions">
                                    <button class="btn btn-secondary">View Details</button>
                                    <?php if (($contract['status'] ?? '') === 'pending'): ?>
                                        <button class="btn btn-primary">Accept Contract</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-contracts">
                            <i class="fas fa-file-contract" style="font-size: 3rem; color: #ddd; margin-bottom: 15px;"></i>
                            <h3>No Contracts Found</h3>
                            <p>You don't have any contracts yet. New contract offers will appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/dashboard.js"></script>
</body>
</html>