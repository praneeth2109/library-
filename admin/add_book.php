<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../includes/db.php';

    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $published_year = $_POST['published_year'];
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];
    $shelf_location = $_POST['shelf_location'];

    try {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, genre, published_year, isbn, quantity, shelf_location) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $author, $genre, $published_year, $isbn, $quantity, $shelf_location]);
        echo "Book added successfully!";
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
    <title>Add New Book | Library Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f5f6fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .form-container {
            max-width: 600px;
            width: 100%;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #7f8c8d;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52,152,219,0.3);
        }

        .btn-submit {
            background: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        .btn-submit:hover {
            background: #2980b9;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .success-message {
            background: #2ecc71;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error-message {
            background: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1><i class="fas fa-book"></i> Add New Book</h1>
            <p>Fill in the details to add a new book to the library</p>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> Book added successfully!
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> Error adding book. Please try again.
            </div>
        <?php endif; ?>

        <form action="add_book.php" method="POST">
            <div class="form-group">
                <label for="title"><i class="fas fa-heading"></i> Book Title</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Enter book title" required>
            </div>

            <div class="form-group">
                <label for="author"><i class="fas fa-user"></i> Author</label>
                <input type="text" id="author" name="author" class="form-control" placeholder="Enter author name" required>
            </div>

            <div class="form-group">
                <label for="genre"><i class="fas fa-tags"></i> Genre</label>
                <input type="text" id="genre" name="genre" class="form-control" placeholder="Enter book genre" required>
            </div>

            <div class="form-group">
                <label for="published_year"><i class="fas fa-calendar"></i> Published Year</label>
                <input type="number" id="published_year" name="published_year" class="form-control" 
                       placeholder="Enter published year" min="1800" max="<?php echo date('Y'); ?>" required>
            </div>

            <div class="form-group">
                <label for="isbn"><i class="fas fa-barcode"></i> ISBN</label>
                <input type="text" id="isbn" name="isbn" class="form-control" placeholder="Enter ISBN number" required>
            </div>

            <div class="form-group">
                <label for="quantity"><i class="fas fa-copy"></i> Quantity</label>
                <input type="number" id="quantity" name="quantity" class="form-control" 
                       placeholder="Enter quantity" min="1" required>
            </div>

            <div class="form-group">
                <label for="shelf_location"><i class="fas fa-map-marker-alt"></i> Shelf Location</label>
                <input type="text" id="shelf_location" name="shelf_location" class="form-control" 
                       placeholder="Enter shelf location (e.g., A-12)" required>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-plus-circle"></i> Add Book
            </button>
        </form>

        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</body>
</html>