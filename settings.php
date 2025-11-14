<?php
$host = "localhost";
$user = "root";
$pwd = "";
$sql_db = "project2_db";
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if(!$conn) { die("Database connection failed"); }