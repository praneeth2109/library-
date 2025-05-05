<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../index.html');
    exit();
}

include '../includes/db.php';

$searchResults = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchTerm = $_POST['search_term'];

    try {
        // Search for books by title, author, or genre
        $stmt = $pdo->prepare("
            SELECT * FROM books 
            WHERE title LIKE ? OR author LIKE ? OR genre LIKE ?
        ");
        $searchTerm = "%$searchTerm%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Search Books</title>
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
            padding: 2rem;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1200px;
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            position: relative;
            padding-bottom: 1rem;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        form {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        input[type="text"] {
            flex: 1;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }

        button {
            padding: 1rem 2rem;
            background: linear-gradient(90deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: linear-gradient(90deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .availability {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .available {
            background-color: #d4edda;
            color: #155724;
        }

        .unavailable {
            background-color: #f8d7da;
            color: #721c24;
        }

        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #764ba2;
            transform: translateX(-5px);
        }

        .no-results {
            text-align: center;
            color: #666;
            font-size: 1.2rem;
            margin-top: 2rem;
        }

        .book-image {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1><i class="fas fa-search"></i> Search Books</h1>
        <form action="search_books.php" method="POST">
            <input type="text" name="search_term" placeholder="Search by title, author, or genre" required>
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>

        <?php if (!empty($searchResults)): ?>
            <h2>Search Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Published Year</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $book): ?>
                        <tr>
                            <td>
                                <img src="<?= isset($book['cover_image']) ? htmlspecialchars($book['cover_image']) : '../images/default-book.jpg' ?>" 
                                     alt="Book Cover" 
                                     class="book-image">
                            </td>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars($book['genre']) ?></td>
                            <td><?= htmlspecialchars($book['published_year']) ?></td>
                            <td>
                                <span class="availability <?= $book['quantity'] > 0 ? 'available' : 'unavailable' ?>">
                                    <?= $book['quantity'] > 0 ? 'Available' : 'Unavailable' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="no-results">
                <i class="fas fa-book-open"></i> No books found matching your search criteria.
            </p>
        <?php endif; ?>

        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</body>
</html>