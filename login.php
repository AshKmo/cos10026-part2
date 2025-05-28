<?php
session_start();
?>
<!DOCTYPE html>

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include_once "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Tolstra - Staff Login">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, telecommunications, Internet, phone, login">

	<!-- set the page title -->
	<title>Tolstra - Staff Login</title>
</head>

<body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
		<?php
			function echo_login() {
				echo "
					<section id=\"login-ui\">
						<h1>Staff Login</h1>
							<form action=\"process_login.php\" method=\"POST\">
								<label for=\"username\">Username:</label><br>
								<input type=\"text\" id=\"username\" name=\"username\" placeholder=\"username\" required>

								<br><br>
								
								<label for=\"password\">Password:</label><br>
								<input type=\"password\" id=\"password\" name=\"password\" placeholder=\"password\" required>
				";
				// Display login error
				if (isset($_SESSION['error'])) {
					echo "<section id=error>";
					echo '<p>' . $_SESSION['error'] . '</p>';
					echo "</section>";
					unset($_SESSION['error']);
				} else {
					echo "<br><br>";
				}
				echo "
								<input type=\"submit\" value=\"Login\">
							</form>
						</section>
				";
			}
			if (isset($_SESSION["time_at_lockout"])) {
				if (time() - ($_SESSION["time_at_lockout"] + 300)  > 0) {
					unset($_SESSION["time_at_lockout"]);
					unset($_SESSION["attempts"]);
					echo_login();
				} else {
					if (4 - intdiv(time() - $_SESSION["time_at_lockout"], 60) != 0) {
						echo "<p>Maximum attempts have been reached. Please try again in " . (4 - intdiv(time() - $_SESSION["time_at_lockout"], 60)) . " minutes and " . (59 - (time() - $_SESSION["time_at_lockout"]) % 60) . " seconds.</p>";
					} else {
						echo "<p>Maximum attempts have been reached. Please try again in " . (59 - (time() - $_SESSION["time_at_lockout"]) % 60) . " seconds.</p>";
					}
				}
			} else {
				echo_login();
			}

		?>
		
    </main>

	<?php include_once "footer.inc"; ?>
</body>