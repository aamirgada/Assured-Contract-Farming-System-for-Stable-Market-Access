<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Welcome</title>
    <style>
        .welcome-container {
            text-align: center;
            padding: 40px;
        }
        .user-info {
            margin: 20px 0;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
        }
        .logout-btn {
            background-color: #512da8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container welcome-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <div class="user-info">
            <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            <p>Role: <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
            <p>You have successfully logged in.</p>
        </div>
        <a href="logout.php"><button class="logout-btn">Logout</button></a>
    </div>
</body>
</html>