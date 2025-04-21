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

// Get payments for this farmer
try {
    $stmt = $conn->prepare("SELECT p.*, c.crop_name, u.fullname as buyer_name 
                           FROM payments p 
                           JOIN contracts c ON p.contract_id = c.id
                           JOIN users u ON c.buyer_id = u.id 
                           WHERE p.farmer_id = ? 
                           ORDER BY p.payment_date DESC");
    $stmt->execute([$user_id]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $payments = [];
    error_log("Error fetching payments: " . $e->getMessage());
}

// Calculate total earnings
$totalEarnings = 0;
$pendingPayments = 0;
foreach ($payments as $payment) {
    if ($payment['status'] === 'completed') {
        $totalEarnings += $payment['amount'];
    } else if ($payment['status'] === 'pending') {
        $pendingPayments += $payment['amount'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Farmer Dashboard - AgriConnect</title>
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
        
        /* Payment specific styles */
        .payment-summary {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .summary-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
            display: flex;
            flex-direction: column;
        }
        
        .summary-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #512da8;
        }
        
        .payments-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .payments-table th {
            background-color: #f5f5f5;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #ddd;
        }
        
        .payments-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .payments-table tr:last-child td {
            border-bottom: none;
        }
        
        .payment-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-completed {
            background-color: #e8f5e9;
            color: #388e3c;
        }
        
        .status-pending {
            background-color: #fff8e1;
            color: #ff8f00;
        }
        
        .status-failed {
            background-color: #ffebee;
            color: #d32f2f;
        }
        
        .empty-payments {
            text-align: center;
            padding: 30px;
            color: #666;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
                <a href="contracts.php"><i class="fas fa-file-contract"></i> Contracts</a>
                <a href="payments.php" class="active"><i class="fas fa-money-bill-wave"></i> Payments</a>
                <!-- <a href="profile.php"><i class="fas fa-user"></i> Profile</a> -->
                <!-- <a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> -->
            </div>
        </nav>

        <main class="dashboard-main">
            <header class="dashboard-header">
                <h1>Your Payments</h1>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($user_email); ?></span>
                    <div class="user-avatar" style="width: 35px; height: 35px; border-radius: 50%; background-color: #512da8; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        <?php echo substr(htmlspecialchars($user_name), 0, 1); ?>
                    </div>
                </div>
            </header>

            <div class="dashboard-content">
                <div class="payment-summary">
                    <div class="summary-card">
                        <div class="summary-title">Total Earnings</div>
                        <div class="summary-amount">₹<?php echo number_format($totalEarnings, 2); ?></div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-title">Pending Payments</div>
                        <div class="summary-amount">₹<?php echo number_format($pendingPayments, 2); ?></div>
                    </div>
                </div>
                
                <?php if (count($payments) > 0): ?>
                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Contract</th>
                                <th>Buyer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo isset($payment['payment_date']) ? date('d M Y', strtotime($payment['payment_date'])) : 'N/A'; ?></td>
                                    <td><?php echo htmlspecialchars($payment['crop_name'] ?? 'Unknown Contract'); ?></td>
                                    <td><?php echo htmlspecialchars($payment['buyer_name'] ?? 'Unknown Buyer'); ?></td>
                                    <td>₹<?php echo number_format($payment['amount'] ?? 0, 2); ?></td>
                                    <td>
                                        <?php 
                                            $statusClass = '';
                                            switch ($payment['status'] ?? 'pending') {
                                                case 'completed':
                                                    $statusClass = 'status-completed';
                                                    break;
                                                case 'failed':
                                                    $statusClass = 'status-failed';
                                                    break;
                                                default:
                                                    $statusClass = 'status-pending';
                                            }
                                        ?>
                                        <span class="payment-status <?php echo $statusClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($payment['status'] ?? 'Pending')); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-payments">
                        <i class="fas fa-money-bill-wave" style="font-size: 3rem; color: #ddd; margin-bottom: 15px;"></i>
                        <h3>No Payments Found</h3>
                        <p>You don't have any payment records yet. Completed contracts will generate payments here.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="../js/dashboard.js"></script>
</body>
</html>