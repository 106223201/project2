<?php
session_start();
require_once('settings.php');

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

// Get and sanitize inputs
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];

// Validate username
if (empty($username)) {
    $errors[] = "Username is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $username)) {
    $errors[] = "Username must contain only letters and be 1-20 characters.";
} else {

// Check if username exists
    $check_query = "SELECT username FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Username already exists. Please choose another.";
    }
    mysqli_stmt_close($stmt);
}

// Validate email
if (empty($email)) {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
} else {
    // Check if email exists
    $check_query = "SELECT email FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Email is already registered. Please use another email or login.";
    }
    mysqli_stmt_close($stmt);
}

// Validate password
if (empty($password)) {
    $errors[] = "Password is required.";
} elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
}

// If there are errors, show them
if (!empty($errors)) {
    $_SESSION['registration_errors'] = $errors;
    $_SESSION['form_username'] = $username;
    $_SESSION['form_email'] = $email;
    echo "<script>
        alert('Email or username is already registered. Please use a different email or username.');
        window.location.href = 'login-register.php';
        </script>";
    exit();
}

// Hash password and insert user
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

if (mysqli_stmt_execute($stmt)) {
    $user_id = mysqli_insert_id($conn);
    
    // Auto-login after registration
    $_SESSION['user_logged_in'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['user_email'] = $email;
    
    $_SESSION['success_message'] = "Account created successfully! Welcome, $username!";
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo "<script>
            alert('Registration successful! Welcome, $username!');
            window.location.href = 'login-register.php';
            </script>";
    
    exit();
} else {
    $_SESSION['registration_errors'] = ["Registration failed. Please try again."];
    echo "<script>
        alert('Email or username is already registered. Please use a different email or username.');
        window.location.href = 'login-register.php';
        </script>";

    exit();
}
?>


// if (isset($_POST['signup'])) {
//     $email = $_POST['email'];
//     $name = $_POST['username'];
//     $password = $_POST['password'];

//     $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
//     $emailResult = mysqli_query($conn, $checkEmailQuery);

//     if (mysqli_num_rows($emailResult) > 0) {
//         echo "<script>
//                 alert('Email is already registered. Please use a different email.');
//                 window.location.href = 'login-register.php';
//               </script>";
//     } 
//     else {
//         $insertQuery = "INSERT INTO users (username, password, email) VALUES ('$name', '$password', '$email')";
//         $insertResult = mysqli_query($conn, $insertQuery);
//         if ($insertResult) {
//             echo "<script>
//                     alert('Registration successful! You can now log in.');
//                     window.location.href = 'login-register.php';
//                   </script>";
//         }
//     }
//     echo "Registration functionality is currently disabled for testing purposes.";
//     echo mysqli_num_rows($emailResult);
// }

    
<!-- ?> -->