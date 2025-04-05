<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

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

// Get total users count
$sql_users = "SELECT COUNT(*) as total FROM users";
$result_users = $conn->query($sql_users);
$row_users = $result_users->fetch_assoc();
$total_users = $row_users['total'];

// Get total content count (example: posts)
$sql_posts = "SELECT COUNT(*) as total FROM posts";
$result_posts = $conn->query($sql_posts);
$row_posts = $result_posts->fetch_assoc();
$total_posts = $row_posts['total'];

// Get recent users
$sql_recent = "SELECT id, username, role, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$result_recent = $conn->query($sql_recent);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding-top: 20px;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .sidebar h2 {
            padding: 0 20px;
            margin-bottom: 30px;
        }
        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .menu li a:hover, .menu li a.active {
            background-color: #4CAF50;
        }
        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            flex: 1;
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .stat-card h3 {
            margin-top: 0;
            color: #333;
        }
        .stat-card .number {
            font-size: 30px;
            font-weight: bold;
            color: #4CAF50;
        }
        .recent-container {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .recent-container h3 {
            margin-top: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .logout {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }
        .logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul class="menu">
                <li><a href="#" class="active">Dashboard</a></li>
                <li><a href="users.php">User Management</a></li>
                <li><a href="posts.php">Content Management</a></li>
                <li><a href="settings.php">Site Settings</a></li>
            </ul>
        </div>
        <div class="content">
            <div class="header">
                <h1>Dashboard</h1>
                <a href="logout.php" class="logout">Logout</a>
            </div>
            
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <div class="number"><?php echo $total_users; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Posts</h3>
                    <div class="number"><?php echo $total_posts; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Active Users</h3>
                    <div class="number">24</div>
                </div>
            </div>
            
            <div class="recent-container">
                <h3>Recent Users</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result_recent->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>