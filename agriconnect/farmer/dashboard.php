<?php
// Start the session before accessing session variables
session_start();

require_once '../php/config.php';

// Check if user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'farmer') {
    // Redirect to login page with error message
    // header("Location: ../signUpLogin/login.html");
    // exit();
}

// Get user data
$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? 'Farmer';
$user_email = $_SESSION['user_email'] ?? '';

// Get farmer profile from database
try {
    $stmt = $conn->prepare("SELECT * FROM farmer_profiles WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If profile doesn't exist, create an empty array
    if (!$profile) {
        $profile = [];
    }
} catch(PDOException $e) {
    $profile = [];
    // Log error for debugging
    error_log("Error fetching farmer profile: " . $e->getMessage());
}

// Get active contracts count
try {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM contracts WHERE farmer_id = ? AND status = 'active'");
    $stmt->execute([$user_id]);
    $active_contracts = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
} catch(PDOException $e) {
    $active_contracts = 0;
}

// Get total earnings
try {
    $stmt = $conn->prepare("SELECT SUM(amount) as total FROM payments WHERE farmer_id = ? AND status = 'completed'");
    $stmt->execute([$user_id]);
    $total_earnings = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
} catch(PDOException $e) {
    $total_earnings = 0;
}

// Get completed contracts count
try {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM contracts WHERE farmer_id = ? AND status = 'completed'");
    $stmt->execute([$user_id]);
    $completed_contracts = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
} catch(PDOException $e) {
    $completed_contracts = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard - AgriConnect</title>
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
        .stats-grid {
            margin-bottom: 15px;
        }
        .stat-card {
            padding: 12px;
        }
        .recent-activity h2 {
            margin-top: 0;
            margin-bottom: 10px;
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
                <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="contracts.php"><i class="fas fa-file-contract"></i> Contracts</a>
                <a href="payments.php"><i class="fas fa-money-bill-wave"></i> Payments</a>
                <!-- <a href="profile.php"><i class="fas fa-user"></i> Profile</a> -->
                <!-- <a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> -->
            </div>
        </nav>

        <main class="dashboard-main">
            <header class="dashboard-header">
                <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($user_email); ?></span>
                    <div class="user-avatar" style="width: 35px; height: 35px; border-radius: 50%; background-color: #512da8; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        <?php echo substr(htmlspecialchars($user_name), 0, 1); ?>
                    </div>
                </div>
            </header>

            <div class="dashboard-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-file-contract"></i>
                        <h3>Active Contracts</h3>
                        <p class="stat-number"><?php echo $active_contracts; ?></p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-money-bill-wave"></i>
                        <h3>Total Earnings</h3>
                        <p class="stat-number">₹<?php echo number_format($total_earnings, 2); ?></p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-check-circle"></i>
                        <h3>Completed Contracts</h3>
                        <p class="stat-number"><?php echo $completed_contracts; ?></p>
                    </div>
                </div>

                <div class="recent-activity">
                    <h2>Recent Activity</h2>
                    <div class="activity-list">
                        <?php
                        // Get recent activities
                        try {
                            $stmt = $conn->prepare("SELECT * FROM activities WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                            $stmt->execute([$user_id]);
                            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($activities) > 0) {
                                foreach ($activities as $activity) {
                                    echo '<div class="activity-item">';
                                    echo '<div class="activity-icon"><i class="' . htmlspecialchars($activity['icon'] ?? 'fas fa-info-circle') . '"></i></div>';
                                    echo '<div class="activity-details">';
                                    echo '<p>' . htmlspecialchars($activity['description']) . '</p>';
                                    echo '<span class="activity-time">' . htmlspecialchars(date('M d, Y H:i', strtotime($activity['created_at']))) . '</span>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p class="no-activity">No recent activity</p>';
                            }
                        } catch(PDOException $e) {
                            echo '<p class="no-activity">No recent activity</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/dashboard.js"></script>
</body>
</html>