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
    $user_id = $_POST['user_id'];
    $issue_date = date('Y-m-d'); // Current date

    try {
        // Check if the book is available
        $stmt = $pdo->prepare("SELECT quantity FROM books WHERE id = ? AND status = 'available'");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book && $book['quantity'] > 0) {
            // Issue the book
            $stmt = $pdo->prepare("INSERT INTO issue_books (book_id, user_id, issue_date) VALUES (?, ?, ?)");
            $stmt->execute([$book_id, $user_id, $issue_date]);

            // Decrease the quantity of the book
            $stmt = $pdo->prepare("UPDATE books SET quantity = quantity - 1 WHERE id = ?");
            $stmt->execute([$book_id]);

            echo "Book issued successfully!";
        } else {
            echo "Book is not available.";
        }
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
    <title>Issue Book</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input {
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: #f5f6fa;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input:focus {
            outline: none;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        button {
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
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
        <h1><i class="fas fa-book-medical"></i> Issue Book</h1>
        <form action="issue_book.php" method="POST">
            <input type="number" name="book_id" placeholder="Enter Book ID" required>
            <input type="number" name="user_id" placeholder="Enter User ID" required>
            <button type="submit">
                <i class="fas fa-check-circle"></i> Issue Book
            </button>
        </form>
        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</body>
</html>