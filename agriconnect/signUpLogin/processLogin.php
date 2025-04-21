<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agriconnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "<script>alert('Connection failed: " . $conn->connect_error . "');</script>";
    die("Connection failed: " . $conn->connect_error);
}

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["password"]);
    $role = htmlspecialchars(trim($_POST["role"]));
    
    // echo "<script>alert('Processing login for email: " . $email . "');</script>";
    
    // Validate inputs
    $errors = [];
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
        echo "<script>alert('Error: Valid email is required');</script>";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
        echo "<script>alert('Error: Password is required');</script>";
    }
    
    if (empty($role) || !in_array($role, ['farmer', 'buyer'])) {
        $errors[] = "Valid role is required";
        echo "<script>alert('Error: Valid role is required');</script>";
    }
    
    // If there are validation errors, redirect back
    if (!empty($errors)) {
        $errorMessage = implode(", ", $errors);
        echo "<script>alert('Validation errors: " . $errorMessage . "');</script>";
        echo "<script>setTimeout(function() { window.location.href = 'login.html?error=" . urlencode($errorMessage) . "'; }, 2000);</script>";
        exit();
    }
    
    // echo "<script>alert('Checking if user exists in database...');</script>";
    
    // Check if user exists and verify password
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        // echo "<script>alert('User found in database');</script>";
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // echo "<script>alert('Password verified successfully');</script>";
            
            // Password is correct, start a new session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Changed from user_type to role
            
            // Redirect based on user role
            if ($user['role'] === 'farmer') {
                echo "<script>alert('Login successful! Redirecting to farmer dashboard...');</script>";
                echo "<script>setTimeout(function() { window.location.href = '../index.php'; }, 2000);</script>";
            } else if ($user['role'] === 'buyer') {
                echo "<script>alert('Login successful! Redirecting to buyer dashboard...');</script>";
                echo "<script>setTimeout(function() { window.location.href = '../indexbuyer.php'; }, 2000);</script>";
            } else {
                // Default redirect for other roles
                echo "<script>alert('Login successful! Redirecting to welcome page...');</script>";
                echo "<script>setTimeout(function() { window.location.href = 'welcome.php'; }, 2000);</script>";
            }
            exit();
        } else {
            // Password is incorrect
            echo "<script>alert('Invalid password');</script>";
            echo "<script>setTimeout(function() { window.location.href = 'login.html?error=Invalid email or password'; }, 2000);</script>";
            exit();
        }
    } else {
        // User does not exist
        echo "<script>alert('User not found with email: " . $email . "');</script>";
        echo "<script>setTimeout(function() { window.location.href = 'login.html?error=Invalid email or password'; }, 2000);</script>";
        exit();
    }
    
    $stmt->close();
    $conn->close();
}
?>