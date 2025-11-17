<?php
require_once('init_session.php');
require_once('settings.php');

// Check if already logged in as manager
if (isset($_SESSION['manager_logged_in']) && $_SESSION['manager_logged_in'] === true) {
    header("Location: manage.php");
    exit();
}

$errors = [];
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_manager'])) {
    $conn = mysqli_connect($host, $user, $pwd, $sql_db);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Get and sanitize inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) {
        $errors[] = "Username must be 5-20 characters (letters, numbers, underscore only).";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($full_name)) {
        $errors[] = "Full name is required.";
    } elseif (strlen($full_name) < 3 || strlen($full_name) > 100) {
        $errors[] = "Full name must be between 3 and 100 characters.";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required.";
    } else {
        // Password strength validation
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }
        if (!preg_match("/[A-Z]/", $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match("/[a-z]/", $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match("/[0-9]/", $password)) {
            $errors[] = "Password must contain at least one number.";
        }
        if (!preg_match("/[@$!%*?&#]/", $password)) {
            $errors[] = "Password must contain at least one special character (@$!%*?&#).";
        }
    }
    
    if (empty($confirm_password)) {
        $errors[] = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    
    // Check if username or email already exists
    if (empty($errors)) {
        $check_query = "SELECT manager_id FROM managers WHERE username = ? OR email = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "ss", $username, $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $errors[] = "Username or email already exists.";
        }
        mysqli_stmt_close($check_stmt);
    }
    
    // If no errors, register the manager
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $insert_query = "INSERT INTO managers (username, email, full_name, password) VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "ssss", $username, $email, $full_name, $hashed_password);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $success_message = "Registration successful! You can now login.";
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
        mysqli_stmt_close($insert_stmt);
    }
    
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/manager-register.css">
    <title>Manager Registration - ePass</title>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Manager Registration</h1>
            <p>Create your HR manager account</p>
        </div>
        
        <div class="register-form">
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
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
            
            <form method="POST" action="manager-register.php">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" required
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" required
                           value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required>
                    <div class="password-requirements">
                        Password must contain:<br>
                        • At least 8 characters<br>
                        • One uppercase letter (A-Z)<br>
                        • One lowercase letter (a-z)<br>
                        • One number (0-9)<br>
                        • One special character (@$!%*?&#)
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" name="register_manager" class="register-btn">Register</button>
            </form>
            
            <div class="links">
                <a href="manager-login.php">Already have an account? Login</a>
            </div>
        </div>
    </div>
</body>
</html>