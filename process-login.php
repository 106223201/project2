<?php
session_start();
require_once('settings.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login'])) {
    header("Location: login-register.php");
    exit();
}

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$username = trim($_POST['username_log']);
$password = $_POST['password_log'];

if (empty($username) || empty($password)) {
    $_SESSION['login_errors'] = ["Please enter both username and password."];
    header("Location: login-register.php");
    exit();
}

$ip_address = getUserIP();
$time = time() - 60;

$check_attempts_query = "SELECT * FROM login_attempts WHERE ip_address = ? AND time_count > ?";
$stmt_attempts = mysqli_prepare($conn, $check_attempts_query);
mysqli_stmt_bind_param($stmt_attempts, "si", $ip_address, $time);
mysqli_stmt_execute($stmt_attempts);
$result_attempts = mysqli_stmt_get_result($stmt_attempts);
$check_attempts = mysqli_num_rows($result_attempts);
mysqli_stmt_close($stmt_attempts);

if ($check_attempts >= 3) {
    $_SESSION['login_errors'] = ["Too many failed login attempts. Please try again after one minute."];
    mysqli_close($conn);
    header("Location: login-register.php");
    exit();
}

$query = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    $userData = mysqli_fetch_assoc($result);
    
    if (password_verify($password, $userData['password'])) {
        $delete_attempts = "DELETE FROM login_attempts WHERE ip_address = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_attempts);
        mysqli_stmt_bind_param($delete_stmt, "s", $ip_address);
        mysqli_stmt_execute($delete_stmt);
        mysqli_stmt_close($delete_stmt);
        
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $userData['user_id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['user_email'] = $userData['email'];
        $_SESSION['profile_picture'] = $userData['profile_picture'];
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        header("Location: index.php");
        exit();
    } else {
        $time = time();
        $time_remain = 3 - $check_attempts;
        
        if ($time_remain <= 0) {
            $_SESSION['login_errors'] = ["Too many failed login attempts. Please try again after one minute."];
        } else {
            $_SESSION['login_errors'] = ["Invalid password. You have $time_remain attempt(s) left."];
        }
        
        $log_query = "INSERT INTO login_attempts (ip_address, time_count) VALUES (?, ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        mysqli_stmt_bind_param($log_stmt, "si", $ip_address, $time);
        mysqli_stmt_execute($log_stmt);
        mysqli_stmt_close($log_stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        header("Location: login-register.php");
        exit();
    }
} else {
    $_SESSION['login_errors'] = ["Username not found. Please register first."];
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: login-register.php");
    exit();
}
?>