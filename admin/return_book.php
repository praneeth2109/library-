<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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
            $fine = $date_diff * 10; // Assuming fine is $10 per day
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
            max-width: 600px;
            width: 100%;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 2rem;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        label {
            font-weight: bold;
            color: #2c3e50;
        }
        input {
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
        }
        button {
            background: #3498db;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #2980b9;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Return Book</h1>
        <form action="return_book.php" method="POST">
            <label for="issue_id">Issue ID:</label>
            <input type="number" name="issue_id" id="issue_id" placeholder="Enter Issue ID" required>
            
            <button type="submit">Return Book</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>