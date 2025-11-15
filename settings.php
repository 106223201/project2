<?php
    $host = 'localhost';
    $user = 'root';
    $pwd = '';
    $sql_db = 'project2_db';

    // PHP Data Objects (PDO) connection
    try {
        $conn = new PDO(
            "mysql:host=$host;dbname=$sql_db;charset=utf8mb4",
            $user,
            $pwd,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>
