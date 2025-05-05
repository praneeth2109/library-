<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../index.html');
    exit();
}

include '../includes/db.php';

$issuedBooks = [];

try {
    // Get books issued to the logged-in student
    $stmt = $pdo->prepare("
        SELECT books.title, books.author, books.genre, issue_books.issue_date, issue_books.return_date
        FROM issue_books
        INNER JOIN books ON issue_books.book_id = books.id
        WHERE issue_books.user_id = ? AND issue_books.status = 'issued'
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $issuedBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Issued Books | Library Management System</title>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            max-width: 1000px;
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
            font-size: 2.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #3498db;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .no-books {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-size: 1.2rem;
        }
        a {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.8rem 1.5rem;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        a:hover {
            background: #2980b9;
        }
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .status-returned {
            background: #2ecc71;
            color: white;
        }
        .status-pending {
            background: #f1c40f;
            color: white;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1><i class="fas fa-book"></i> My Issued Books</h1>

        <?php if (!empty($issuedBooks)): ?>
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-book"></i> Title</th>
                        <th><i class="fas fa-user-edit"></i> Author</th>
                        <th><i class="fas fa-tags"></i> Genre</th>
                        <th><i class="fas fa-calendar-plus"></i> Issue Date</th>
                        <th><i class="fas fa-calendar-check"></i> Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($issuedBooks as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars($book['genre']) ?></td>
                            <td><?= htmlspecialchars($book['issue_date']) ?></td>
                            <td>
                                <?php if ($book['return_date']): ?>
                                    <span class="status-badge status-returned">
                                        <?= htmlspecialchars($book['return_date']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge status-pending">Not Returned</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-books">
                <i class="fas fa-book-open fa-3x" style="color: #3498db; margin-bottom: 1rem;"></i>
                <p>You have no books issued.</p>
            </div>
        <?php endif; ?>

        <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>