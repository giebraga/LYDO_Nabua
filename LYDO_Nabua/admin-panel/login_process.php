<?php
// Start session
session_start();

// Database connection
$db_host = 'localhost';
$db_user = 'root';  // Change to your database username
$db_pass = '';      // Change to your database password
$db_name = 'admin_panel';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    // Query to check if user exists
    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            header("Location: index.html?error=invalid");
            exit();
        }
    } else {
        // Username not found
        header("Location: index.html?error=invalid");
        exit();
    }
    
    $stmt->close();
}

$conn->close();
?>