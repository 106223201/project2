<!-- Consult ClaudeAI on logging out of more advanced session logout. Did not implement the advanced logout time. -->
<!-- Prompt: Review my codes for logging out of manager account and suggest a preferable logout time. -->

<?php
require_once('init_session.php');
require_once('settings.php');

// Log logout activity if manager was logged in
if (isset($_SESSION['manager_logged_in']) && $_SESSION['manager_logged_in'] === true && isset($_SESSION['manager_id'])) {
    $conn = mysqli_connect($host, $user, $pwd, $sql_db);
    
    if ($conn) {
        $manager_id = $_SESSION['manager_id'];
        $log_query = "INSERT INTO manager_activity_log (manager_id, action_type, action_details, ip_address) 
                     VALUES (?, 'logout', 'Logged out', ?)";
        $stmt = mysqli_prepare($conn, $log_query);
        $ip_address = $_SERVER['REMOTE_ADDR'];
        mysqli_stmt_bind_param($stmt, "is", $manager_id, $ip_address);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}

// Clear manager session variables
unset($_SESSION['manager_logged_in']);
unset($_SESSION['manager_id']);
unset($_SESSION['manager_username']);
unset($_SESSION['manager_name']);

// Redirect to manager login page
header("Location: manager-login.php");
exit();
?>