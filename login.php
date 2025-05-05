<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    try {
        // Use MySQL's PASSWORD() function in the query for password validation
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ? AND password = PASSWORD(?)");
        $stmt->execute([$email, $role, $password]);

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the appropriate dashboard
            if ($user['role'] === 'admin') {
                header('Location: admin/dashboard.php');
            } elseif ($user['role'] === 'librarian') {
                header('Location: librarian/dashboard.php');
            } elseif ($user['role'] === 'student') {
                header('Location: student/dashboard.php');
            }
            exit();
        } else {
            // Invalid credentials
            echo "Invalid email, password, or role.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>