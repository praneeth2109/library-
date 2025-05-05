<?php
session_start();

// Check if the user is logged in and is a librarian
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    header('Location: ../index.html');
    exit();
}

include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = $_POST['issue_id'];

    try {
        // Step 1: Fetch the book ID and due date from the issue record
        $stmt = $pdo->prepare("SELECT book_id, due_date FROM issue_books WHERE id = ?");
        $stmt->execute([$issue_id]);
        $issue = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$issue) {
            echo "Invalid issue ID.";
            exit();
        }

        $book_id = $issue['book_id'];
        $due_date = $issue['due_date'];
        $current_date = date('Y-m-d');

        // Step 2: Calculate fine (if any)
        $fine = 0;
        if ($current_date > $due_date) {
            $date_diff = (strtotime($current_date) - strtotime($due_date)) / (60 * 60 * 24); // Difference in days
            $fine = $date_diff * 5; // Assuming fine is $5 per day
        }

        // Step 3: Update the book's status back to available
        $stmt = $pdo->prepare("UPDATE books SET status = 'available' WHERE id = ?");
        $stmt->execute([$book_id]);

        // Step 4: Mark the issue record as returned and update fine
        $stmt = $pdo->prepare("UPDATE issue_books SET return_date = ?, fine = ?, status = 'returned' WHERE id = ?");
        $stmt->execute([$current_date, $fine, $issue_id]);

        echo "Book returned successfully!";
        if ($fine > 0) {
            echo " Fine incurred: $" . $fine;
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
    <title>Return Book | Library Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 2rem;
        }

        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #45a049;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #666;
            text-decoration: none;
        }

        .back-link:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1><i class="fas fa-book"></i> Return Book</h1>
        <form action="return_book.php" method="POST">
            <div class="form-group">
                <label for="issue_id"><i class="fas fa-hashtag"></i> Issue ID</label>
                <input type="number" name="issue_id" id="issue_id" placeholder="Enter the issue ID" required>
            </div>
            
            <button type="submit"><i class="fas fa-check"></i> Process Return</button>
        </form>
        <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>