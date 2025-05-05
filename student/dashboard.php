<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
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
            background: url('../images/admin-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            margin: 0;
            padding: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(41, 128, 185, 0.9) 0%, rgba(44, 62, 80, 0.9) 100%);
            z-index: -1;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }
        .welcome-section {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }
        .welcome-section::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #2980b9, #2c3e50);
            border-radius: 2px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            background: rgba(255, 255, 255, 1);
        }
        .feature-card h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .feature-card p {
            color: #666;
            font-size: 0.9rem;
        }
        .feature-card a {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(90deg, #2980b9, #2c3e50);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(41, 128, 185, 0.3);
        }
        .feature-card a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(41, 128, 185, 0.4);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-section">
            <h1><i class="fas fa-user-shield"></i> Welcome, student!</h1>
            <p class="subtitle">Library Management System Control Panel</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <h3><i class="fas fa-book fa-2x"></i> Search Books</h3>
                <p>Search books from the library collection</p>
                <a href="search_books.php"><i class="fas fa-book"></i> Manage Books</a>
            </div>

            <div class="feature-card">
                <h3><i class="fas fa-users fa-2x"></i> My Books </h3>
                <p>Shows the Book Taken </p>
                <a href="my_books.php"><i class="fas fa-users"></i> Manage Users</a>
            </div>

            

            <div class="feature-card">
                <h3><i class="fas fa-sign-out-alt fa-2x"></i> Logout</h3>
                <p>Securely exit your account</p>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</body>
</html>