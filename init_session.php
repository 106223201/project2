<?php
/**
 * Session Initialization File
 * Include this at the top of every page that needs session access
 */

// Configure session settings before starting
if (session_status() === PHP_SESSION_NONE) {
    // Session cookie parameters
    ini_set('session.cookie_lifetime', 86400); // 24 hours
    ini_set('session.cookie_httponly', 1); // Prevent JavaScript access
    ini_set('session.use_only_cookies', 1); // Only use cookies
    
    // Start the session
    session_start();
    
    // Regenerate session ID periodically for security
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) {
        // Regenerate ID every 30 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

// Helper function to check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Helper function to get current user ID
function getCurrentUserId() {
    return isUserLoggedIn() ? $_SESSION['user_id'] : null;
}

// Helper function to get current username
function getCurrentUsername() {
    return isUserLoggedIn() ? $_SESSION['username'] : null;
}

// Helper function to require login
function requireLogin($redirect = 'login-register.php') {
    if (!isUserLoggedIn()) {
        header("Location: $redirect");
        exit();
    }
}
?>