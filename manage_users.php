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
?>
<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part2/manage.php -->

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Tolstra - User Manager">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, telecommunications, Internet, phone, about">

	<!-- set the page title -->
	<title>Tolstra - User Manager</title>
</head>

<body>
	<!-- include the page header -->
	<?php include "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
		<section id="user-add">
			<h1>Add User</h1>
            <form action="manage_users.php" method="POST">
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" required>

                <br>

                <label for="username">Password: </label>
                <input type="text" id="password" name="password" required>

                <br>

                <?php
				if (isset($_SESSION['error'])) {
					echo "<section id=error>";
					echo '<p>' . $_SESSION['error'] . '</p>';
					echo "</section>";
					unset($_SESSION['error']);
				}
				?>

                <input type="submit">
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $input_username = trim($_POST['username']);
                $input_password = trim($_POST['password']);

                $query = "SELECT * FROM users WHERE username = '$input_username'";

                $match = mysqli_query($dbconn, $query);

                if (mysqli_fetch_assoc($match)) {
                    $_SESSION['error'] = "User already exists.";

                    header('Location: manage_users.php');
                } else {
                    $query = "INSERT INTO users (username, password)
                    VALUES ('$input_username', '$input_password')";
                    mysqli_query($dbconn, $query);
                }
            }
            ?>
		</section>
    </main>

	<?php include "footer.inc"; ?>
</body>