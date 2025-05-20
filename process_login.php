<?php
session_start();
require_once("settings.php");
$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$dbconn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_username = trim($_POST['username']);
    $input_password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$input_username' AND password = '$input_password'";

    $match = mysqli_query($dbconn, $query);

    if ($user = mysqli_fetch_assoc($match)) {
        $_SESSION['username'] = $user['username'];

        header('Location: index.php');
    } else {
        $_SESSION['error'] = "Username or password not found. Please try again.";
        header('Location: login.php');
    }
}
?>