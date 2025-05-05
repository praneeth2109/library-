<?php
session_start();

// Check if the user is logged in and is a librarian
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    header('Location: ../index.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f6fa;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-container {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 28px;
            position: relative;
            padding-bottom: 15px;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: #3498db;
            border-radius: 2px;
        }

        nav ul {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        nav ul li a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: white;
            color: #2c3e50;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        nav ul li a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #3498db, #2980b9);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        nav ul li a:hover::before {
            opacity: 1;
        }

        nav ul li a:hover {
            transform: translateY(-5px);
            color: white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        nav ul li a i {
            margin-right: 10px;
            font-size: 20px;
            position: relative;
            z-index: 2;
        }

        nav ul li a span {
            position: relative;
            z-index: 2;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                margin: 10px;
                padding: 20px;
            }
            h1 {
                font-size: 24px;
            }
            nav ul {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1><i class="fas fa-book-reader"></i> Welcome, Librarian!</h1>
        <nav>
            <ul>
                <li><a href="issue_book.php"><i class="fas fa-book-medical"></i><span>Issue Book</span></a></li>
                <li><a href="return_book.php"><i class="fas fa-book-return"></i><span>Return Book</span></a></li>
                <li><a href="mark_damaged.php"><i class="fas fa-exclamation-triangle"></i><span>Mark Damaged</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            </ul>
        </nav>
    </div>
</body>
</html>