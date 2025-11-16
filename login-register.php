<?php
session_start();

$registration_errors = isset($_SESSION['registration_errors']) ? $_SESSION['registration_errors'] : [];
$login_errors = isset($_SESSION['login_errors']) ? $_SESSION['login_errors'] : [];
$form_username = isset($_SESSION['form_username']) ? $_SESSION['form_username'] : '';
$form_email = isset($_SESSION['form_email']) ? $_SESSION['form_email'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';

unset($_SESSION['registration_errors']);
unset($_SESSION['login_errors']);
unset($_SESSION['form_username']);
unset($_SESSION['form_email']);
unset($_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="HTML, Form, Login, Register">
    <meta name="author" content="Luu Tri Khoa Tung">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles/login-register.css">
    <title>Login - Register Page</title>
    <style>
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #f5c6cb;
            font-size: 14px;
        }
        .error-message ul {
            margin: 5px 0 0 20px;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #c3e6cb;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <a href="index.php"><img class="logo" src="images/logo.png" alt="logo"></a>
    <br><br>
    
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="post" action="process-register.php">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                
                <?php if (!empty($registration_errors)): ?>
                    <div class="error-message">
                        <ul>
                            <?php foreach ($registration_errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <input type="text" name="username_reg" placeholder="Username" 
                       value="<?php echo htmlspecialchars($form_username); ?>" required>
                <input type="email" name="email_reg" placeholder="Email" 
                       value="<?php echo htmlspecialchars($form_email); ?>" required>
                <input type="password" name="password_reg" placeholder="Password (min 8 characters)" required>
                <button name="signup">Sign Up</button>
            </form>
        </div>
        
        <div class="form-container sign-in">
            <form method="post" action="process-login.php">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your username and password</span>
                
                <?php if (!empty($login_errors)): ?>
                    <div class="error-message">
                        <ul>
                            <?php foreach ($login_errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success_message)): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>
                
                <input type="text" name="username_log" placeholder="Username" required>
                <input type="password" name="password_log" placeholder="Password" required>
                <a href="#">Forgot Your Password?</a>
                <button name="login">Sign In</button>
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
    
    <?php if (!empty($registration_errors)): ?>
    <script>
        document.getElementById('container').classList.add('active');
    </script>
    <?php endif; ?>
    
    <br><br>
    <p>&copy; 2025 ePass Software. All rights reserved.</p>
</body>
</html>