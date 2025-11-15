<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Login</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        form {
            width: 300px; padding: 20px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        input { width: 100%; padding: 8px; margin: 10px 0; }
        button { width: 100%; padding: 10px; cursor: pointer; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2>Manager Login</h2>
<p class="error"><?php $login_error ?></p>

<form method="post">
    <label>Username:</label>
    <input name="username" required>

    <label>Password:</label>
    <input name="password" type="password" required>

    <button name="login_btn">Login</button>
</form>

</body>
</html>

<?php
session_start();
require_once("settings.php");

// Kết nối DB
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$login_error = "";

// Khi người dùng bấm nút Login
if (isset($_POST["login_btn"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $password_hashed = md5($password); // Mã hóa MD5 giống như lúc tạo tài khoản

    // Lấy thông tin manager
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Kiểm tra nếu tài khoản bị khóa
        if ($user["locked_until"] != null) {
            $locked = strtotime($user["locked_until"]);
            $now = time();

            if ($now < $locked) {
                $remaining = $locked - $now;
                $minutes = ceil($remaining / 60);
                $login_error = "Tài khoản bị khóa. Thử lại sau $minutes phút.";
            } else {
                // Mở khóa khi đã hết thời gian
                $reset = "UPDATE users SET attempts = 0, locked_until = NULL WHERE username='$username'";
                mysqli_query($conn, $reset);
            }
        }

        // Kiểm tra mật khẩu
        if ($password_hashed === $user["password"]) {
            // Đăng nhập thành công → Reset attempts
            $reset = "UPDATE users SET attempts = 0, locked_until = NULL WHERE username='$username'";
            mysqli_query($conn, $reset);

            $_SESSION["manager_logged_in"] = true;
            $_SESSION["username"] = $username;

            header("Location: manage.php");
            exit();
        } else {
            // Sai password → tăng attempts
            $attempts = $user["attempts"] + 1;

            if ($attempts >= 3) {
                $lock_time = date("Y-m-d H:i:s", strtotime("+5 minutes")); // khóa 5 phút
                $update = "UPDATE users SET attempts=3, locked_until='$lock_time' WHERE username='$username'";
                mysqli_query($conn, $update);

                $login_error = "Sai mật khẩu 3 lần. Tài khoản bị khóa trong 5 phút.";
            } else {
                $update = "UPDATE users SET attempts=$attempts WHERE username='$username'";
                mysqli_query($conn, $update);

                $remaining = 3 - $attempts;
                $login_error = "Sai mật khẩu. Bạn còn $remaining lần thử.";
            }
        }

    } else {
        $login_error = "Tên đăng nhập không tồn tại.";
    }
}
?>
