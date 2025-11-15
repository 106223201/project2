<?php
require_once('settings.php');

session_start();

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (isset($_POST['signup'])) {
    $email = $_POST['email'];
    $name = $_POST['username'];
    $password = $_POST['password'];

    $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
    $emailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($emailResult) > 0) {
        echo "<script>
                alert('Email is already registered. Please use a different email.');
                window.location.href = 'login-register.php';
              </script>";
    } 
    else {
        $insertQuery = "INSERT INTO users (username, password, email) VALUES ('$name', '$password', '$email')";
        $insertResult = mysqli_query($conn, $insertQuery);
        if ($insertResult) {
            echo "<script>
                    alert('Registration successful! You can now log in.');
                    window.location.href = 'login-register.php';
                  </script>";
        }
    }
//     echo "Registration functionality is currently disabled for testing purposes.";
//     echo mysqli_num_rows($emailResult);
}

    
?>