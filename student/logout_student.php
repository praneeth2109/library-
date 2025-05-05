<?php
// Function to handle student logout
function studentLogout() {
    // Start the session
    session_start();

    // Destroy all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the student login page
    header('Location: ../index.html');
    exit();
}