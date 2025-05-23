<?php
session_start();
?>
<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part2/manage.php -->

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include_once "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Tolstra - Staff Login">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, telecommunications, Internet, phone, about">

	<!-- set the page title -->
	<title>Tolstra - Staff Login</title>
</head>

<body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
		<section id="login-ui">
			<h1>Staff Login</h1>
			<form action="process_login.php" method="POST">
				<label for="username">Username:</label><br>
				<input type="text" id="username" name="username" placeholder="username" required>

				<br><br>
				
				<label for="password">Password:</label><br>
				<input type="password" id="password" name="password" placeholder="password" required>

				<?php
				if (isset($_SESSION['error'])) {
					echo "<section id=error>";
					echo '<p>' . $_SESSION['error'] . '</p>';
					echo "</section>";
					unset($_SESSION['error']);
				} else {
					echo "<br><br>";
				}
				?>

				<input type="submit" value="Login">
			</form>
		</section>
    </main>

	<?php include_once "footer.inc"; ?>
</body>