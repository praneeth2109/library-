<?php
session_start();

// Check if the user is logged in and is a librarian
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    header('Location: ../index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../includes/db.php';

    $book_id = $_POST['book_id'];
    $remarks = $_POST['remarks'];
    $reported_by = $_SESSION['user_id'];
    $date_reported = date('Y-m-d');

    try {
        // Mark the book as damaged
        $stmt = $pdo->prepare("INSERT INTO damaged_books (book_id, reported_by, date_reported, remarks) VALUES (?, ?, ?, ?)");
        $stmt->execute([$book_id, $reported_by, $date_reported, $remarks]);

        // Update the status of the book
        $stmt = $pdo->prepare("UPDATE books SET status = 'damaged' WHERE id = ?");
        $stmt->execute([$book_id]);

        echo "Book marked as damaged!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Damaged Book</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2.2em;
            position: relative;
            padding-bottom: 15px;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
        }

        input, textarea {
            width: 100%;
            padding: 15px;
            padding-left: 45px;
            border: none;
            border-radius: 10px;
            background: #f5f6fa;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
            padding-left: 15px;
        }

        input:focus, textarea:focus {
            outline: none;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        button {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        button:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #667eea;
            transform: translateX(-5px);
        }

        .back-link i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1><i class="fas fa-exclamation-triangle"></i> Mark Book as Damaged</h1>
        <form action="mark_damaged.php" method="POST">
            <div class="input-group">
                <i class="fas fa-book"></i>
                <input type="number" name="book_id" placeholder="Enter Book ID" required>
            </div>
            <textarea name="remarks" placeholder="Enter damage details and remarks..." required></textarea>
            <button type="submit">
                <i class="fas fa-flag"></i> Mark as Damaged
            </button>
        </form>
        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</body>
</html>