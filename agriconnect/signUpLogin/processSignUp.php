<?php
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

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["password"]);
    $role = htmlspecialchars(trim($_POST["role"]));
    
    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }
    
    if (empty($role) || !in_array($role, ['farmer', 'buyer'])) {
        $errors[] = "Please select a valid role";
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Email already exists. Please use a different email or login.";
    }
    
    // If there are errors, show alert and redirect back with error messages
    if (!empty($errors)) {
        $errorMessage = implode(", ", $errors);
        echo "<script>alert('Registration Error: " . $errorMessage . "');</script>";
        echo "<script>setTimeout(function() { window.location.href = 'signup.html?error=" . urlencode($errorMessage) . "'; }, 1000);</script>";
        exit();
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user data into database
    // Using user_type column as per database schema
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
    
    if ($stmt->execute()) {
        // Show success alert and redirect to login page
        echo "<script>alert('Registration successful! You will now be redirected to the login page.');</script>";
        echo "<script>setTimeout(function() { window.location.href = 'login.html?signup=success'; }, 1000);</script>";
    } else {
        // Show error alert and redirect back to signup page
        echo "<script>alert('Registration failed: " . $conn->error . "');</script>";
        echo "<script>setTimeout(function() { window.location.href = 'signup.html?error=Registration failed: " . urlencode($conn->error) . "'; }, 1000);</script>";
    }
    
    $stmt->close();
    $conn->close();
    exit();
}
?>