<?php
// Function to handle librarian logout
function librarianLogout() {
    // Start the session
    session_start();

    // Destroy all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the librarian login page
    header('Location: ../index.html');
    exit();
}