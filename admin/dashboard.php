<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in or not an admin
    header('Location: ../index.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Library Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f5f6fa;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .welcome-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #3498db;
            border-radius: 10px;
            color: white;
        }

        .welcome-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .welcome-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .nav-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: #2c3e50;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .nav-card:hover {
            transform: translateY(-5px);
        }

        .nav-card i {
            font-size: 40px;
            margin-bottom: 15px;
            color: #3498db;
        }

        .nav-card h3 {
            margin: 10px 0;
            font-size: 18px;
        }

        .nav-card p {
            color: #7f8c8d;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                margin: 10px;
                padding: 15px;
            }
            .welcome-header {
                padding: 15px;
            }
            .welcome-header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!</h1>
            <p>Library Management System Dashboard</p>
        </div>
        
        <div class="nav-grid">
            <a href="add_book.php" class="nav-card">
                <i class="fas fa-book-medical"></i>
                <h3>Add Book</h3>
                <p>Add new books to the library collection</p>
            </a>
            
            <a href="add_user.php" class="nav-card">
                <i class="fas fa-user-plus"></i>
                <h3>Add User</h3>
                <p>Register new library members</p>
            </a>
            
            <a href="return_book.php" class="nav-card">
                <i class="fas fa-book-dead"></i>
                <h3>Return Book</h3>
                <p>Manage return Books</p>
            </a>
            
            <a href="remove_user.php" class="nav-card">
                <i class="fas fa-user-minus"></i>
                <h3>Remove User</h3>
                <p>Manage user accounts and access</p>
            </a>
            
            <a href="logout.php" class="nav-card">
                <i class="fas fa-sign-out-alt"></i>
                <h3>Logout</h3>
                <p>Secure logout from the system</p>
            </a>
        </div>
    </div>
</body>
</html>