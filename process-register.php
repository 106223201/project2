<?php
session_start();
require_once('settings.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['signup'])) {
    header("Location: login-register.php");
    exit();
}

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$errors = [];

$username = trim($_POST['username_reg']);
$email = trim($_POST['email_reg']);
$password = $_POST['password_reg'];

if (empty($username)) {
    $errors[] = "Username is required.";
} else {
    $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $checkUsernameQuery);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $usernameResult = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($usernameResult) > 0) {
        $errors[] = "Username is already taken. Please choose another.";
    }
    mysqli_stmt_close($stmt);
}

if (empty($email)) {
    $errors[] = "Email is required.";
} else {
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $checkEmailQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $emailResult = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($emailResult) > 0) {
        $errors[] = "Email is already registered.";
    }
    mysqli_stmt_close($stmt);
}

if (empty($password)) {
    $errors[] = "Password is required.";
} elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters.";
}

if (!empty($errors)) {
    $_SESSION['registration_errors'] = $errors;
    $_SESSION['form_username'] = $username;
    $_SESSION['form_email'] = $email;
    header("Location: login-register.php");
    exit();
}

// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

$insertQuery = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
$insertStmt = mysqli_prepare($conn, $insertQuery);
mysqli_stmt_bind_param($insertStmt, "sss", $username, $password, $email);

if (mysqli_stmt_execute($insertStmt)) {
    $_SESSION['success_message'] = "Registration successful! Please log in.";
    mysqli_stmt_close($insertStmt);
    mysqli_close($conn);
    header("Location: login-register.php?success=1");
    exit();
} else {
    $_SESSION['registration_errors'] = ["Registration failed. Please try again."];
    mysqli_stmt_close($insertStmt);
    mysqli_close($conn);
    header("Location: login-register.php");
    exit();
}
?>