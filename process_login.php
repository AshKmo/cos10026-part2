<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();
require_once("settings.php");
$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$dbconn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_username = trim($_POST['username']);
    $input_password = trim($_POST['password']);

    $stmt = $dbconn -> prepare("SELECT * FROM users WHERE username = ?");
    $stmt -> bind_param("s", $input_username);
    $stmt -> execute();

    $result = $stmt -> get_result();

    if ($user = $result -> fetch_assoc()) {
        if (password_verify($input_password, $user['password'])) {
            $_SESSION['username'] = $user['username'];

            if ($user['privilege'] == "manager") {
                $_SESSION['privilege'] = $user['privilege'];
                header('Location: manage_users.php');
                exit;
            } else {
                header('Location: index.php');
                exit;
            }
        }
    }

    $_SESSION['error'] = "Username or password not found. Please try again.";
    header('Location: login.php');
    exit;
}
?>