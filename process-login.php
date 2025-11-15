<?php
    require_once('setting.php');
    session_start();
    $conn = mysqli_connect($host, $user, $pwd, $sql_db);

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            echo "<script>
                    alert('Login successful! Welcome, $username.');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Invalid username or password. Please try again.');
                    window.location.href = 'login-register.php';
                  </script>";
        }
    }
?>