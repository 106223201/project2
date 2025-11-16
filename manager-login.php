<?php
require_once('init_session.php');
require_once('settings.php');

// Check if already logged in as manager
if (isset($_SESSION['manager_logged_in']) && $_SESSION['manager_logged_in'] === true) {
    header("Location: manage.php");
    exit();
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_manager'])) {
    $conn = mysqli_connect($host, $user, $pwd, $sql_db);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } else {
        // Check if account exists and get info
        $query = "SELECT * FROM managers WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Check if account is locked
            if ($row['locked_until'] && strtotime($row['locked_until']) > time()) {
                $minutes_left = ceil((strtotime($row['locked_until']) - time()) / 60);
                $error_message = "Account is locked due to multiple failed login attempts. Please try again in {$minutes_left} minute(s).";
            } else {
                // Verify password
                if (password_verify($password, $row['password'])) {
                    // Successful login - reset failed attempts
                    $reset_query = "UPDATE managers SET failed_attempts = 0, locked_until = NULL, last_login = NOW() WHERE manager_id = ?";
                    $reset_stmt = mysqli_prepare($conn, $reset_query);
                    mysqli_stmt_bind_param($reset_stmt, "i", $row['manager_id']);
                    mysqli_stmt_execute($reset_stmt);
                    mysqli_stmt_close($reset_stmt);
                    
                    // Set session variables
                    $_SESSION['manager_logged_in'] = true;
                    $_SESSION['manager_id'] = $row['manager_id'];
                    $_SESSION['manager_username'] = $row['username'];
                    $_SESSION['manager_name'] = $row['full_name'];
                    
                    // Log successful login
                    $log_query = "INSERT INTO manager_activity_log (manager_id, action_type, action_details, ip_address) VALUES (?, 'login', 'Successful login', ?)";
                    $log_stmt = mysqli_prepare($conn, $log_query);
                    $ip_address = $_SERVER['REMOTE_ADDR'];
                    mysqli_stmt_bind_param($log_stmt, "is", $row['manager_id'], $ip_address);
                    mysqli_stmt_execute($log_stmt);
                    mysqli_stmt_close($log_stmt);
                    
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    
                    header("Location: manage.php");
                    exit();
                } else {
                    // Failed login - increment failed attempts
                    $failed_attempts = $row['failed_attempts'] + 1;
                    $locked_until = null;
                    
                    // Lock account after 3 failed attempts for 15 minutes
                    if ($failed_attempts >= 3) {
                        $locked_until = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                        $error_message = "Too many failed login attempts. Your account has been locked for 15 minutes.";
                    } else {
                        $attempts_left = 3 - $failed_attempts;
                        $error_message = "Invalid username or password. {$attempts_left} attempt(s) remaining.";
                    }
                    
                    $update_query = "UPDATE managers SET failed_attempts = ?, locked_until = ? WHERE manager_id = ?";
                    $update_stmt = mysqli_prepare($conn, $update_query);
                    mysqli_stmt_bind_param($update_stmt, "isi", $failed_attempts, $locked_until, $row['manager_id']);
                    mysqli_stmt_execute($update_stmt);
                    mysqli_stmt_close($update_stmt);
                }
            }
        } else {
            $error_message = "Invalid username or password.";
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Manager Login - ePass</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #3c4c6bff 0%, #2e5dadff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        
        .login-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .login-header .icon {
            font-size: 60px;
            margin-bottom: 15px;
        }
        
        .login-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .login-header p {
            opacity: 0.9;
        }
        
        .login-form {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #1e3c72;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
            font-size: 0.95rem;
        }
        
        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 60, 114, 0.4);
        }
        
        .links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .links a {
            color: #1e3c72;
            text-decoration: none;
            font-weight: 600;
            display: block;
            margin: 10px 0;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        .home-link {
            color: #666 !important;
            font-weight: normal !important;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="icon">👔</div>
            <h1>Manager Login</h1>
            <p>HR Management Portal</p>
        </div>
        
        <div class="login-form">
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="manager-login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" name="login_manager" class="login-btn">Login</button>
            </form>
            
            <div class="links">
                <a href="manager-register.php">Don't have an account? Register</a>
                <a href="index.php" class="home-link">← Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>