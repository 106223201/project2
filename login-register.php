<?php 
    require_once('settings.php');

    session_start();
    $msg_log="";
    $msg_reg="";

    $conn = mysqli_connect($host, $user, $pwd, $sql_db);

    function getUserIP() {
        // Get real visitor IP address
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
    //  Registration Process
    if (isset($_POST['signup'])) {
        $email = sanitizeInput($_POST['email_reg']);
        $name = sanitizeInput($_POST['username_reg']);
        $password = sanitizeInput($_POST['password_reg']);

        $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
        $emailResult = mysqli_query($conn, $checkEmailQuery);

        if (mysqli_num_rows($emailResult) > 0) {
            $msg_reg = "Email is already registered. Please use a different email.";
        }
        else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO users (username, password, email) VALUES ('$name', '$hashedPassword', '$email')";
            $insertResult = mysqli_query($conn, $insertQuery);
            if ($insertResult) {
                $msg_reg = "Registration successful! You can now log in.";
            }
        }
    }

    // Login Process
    if (isset($_POST['login'])) {
        $ip_address = getUserIP();
        $time = time()-60;
        $check_attempts_query = "SELECT * FROM login_attempts WHERE ip_address='$ip_address' AND time_count > $time ";
        $check_attempts = mysqli_query($conn, $check_attempts_query);
        $check_attempts = mysqli_num_rows($check_attempts);
        if ($check_attempts == 3) {
            $msg_log = "Too many failed login attempts. Please try again after one minute.";
        } else {
            $username = sanitizeInput($_POST['username_log']);
            $password = sanitizeInput($_POST['password_log']);

            $query = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 1) {
                $userData = mysqli_fetch_assoc($result);
                if ($userData['password'] === $password) {
                    $delete_attempts = "DELETE FROM login_attempts WHERE ip_address='$ip_address'";
                    mysqli_query($conn, $delete_attempts);
                    $_SESSION['username'] = $username;
                    header("Location: index.php");
                }
                elseif ($userData['password'] !== $password) {
                    $time = time();
                    $time_remain = 3 - $check_attempts;
                    $msg_log = "Invalid password. You have $time_remain attempt(s) left.";
                    if ($time_remain == 0) {
                        $msg_log = "Too many failed login attempts. Please try again after one minute.";
                    }
                    else {
                        $msg_log = "Invalid password. You have $time_remain attempt(s) left.";
                    }
                    mysqli_query($conn, "INSERT INTO login_attempts(id, ip_address, time_count) VALUES (NULL,'$ip_address','$time')");
                }
            
            } else {
                $msg_log = "Username not found. Please register first.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="keywords" content="HTML, Form, Login, Register">
    <meta name="author" content="Luu Tri Khoa Tung">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles/login-register.css">
    <title>Login - Register Page</title>
    <a href="index.php"><img class="logo" src ="images/logo.png" alt ="logo"></a>
    <br><br>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="post" action="" novalidate="novalidate">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registeration</span>
                <input type="text" name="username_reg" placeholder="Name" required pattern="[A-Za-z]{1,20}">
                <input type="email" name="email_reg" placeholder="Email" required pattern="^[A-Za-z0-9]+([._-][A-Za-z0-9]+)*@[A-Za-z0-9]+([.-][A-Za-z0-9]+)*\.[a-z]{2,}$">
                <input type="password" name="password_reg" placeholder="Password" required pattern=".{8,}">
                <button name="signup">Sign Up</button><br>
                <p style="color:red; font-weight:bold"><?php echo $msg_reg; ?></p>
            </form>
        </div>
        
        <div class="form-container sign-in">
            <form method="post" action="" novalidate="novalidate">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your username and password</span>
                <input type="input" name="username_log" placeholder="Username" required pattern="[A-Za-z]{1,20}">
                <input type="password" name="password_log" placeholder="Password" required pattern=".{8,}">
                <a href="#">Forgot Your Password?</a>
                <button name="login">Sign In</button><br>

                <p style="color:red; font-weight:bold"><?php echo $msg_log; ?></p>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="login-register.js"></script>
</body>

<br><br>
    <p> &copy; 2025 ePass Software. All rights reserved.</p>

    </html>