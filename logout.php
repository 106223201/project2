<?php
// logout.php
session_start();
require_once('settings.php');

// Track logout activity if user was logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id'])) {
    $conn = mysqli_connect($host, $user, $pwd, $sql_db);
    
    if ($conn) {
        $user_id = $_SESSION['user_id'];
        $logout_query = "INSERT INTO user_activity (user_id, activity_type, activity_time) 
                        VALUES (?, 'logout', NOW())";
        $stmt = mysqli_prepare($conn, $logout_query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to home page with logout message
header("Location: index.php?logged_out=true");
exit();
?>
