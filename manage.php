<?php
	require_once("settings.php");
?>

<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part2/manage.php -->

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Tolstra - Manage EOIs">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, telecommunications, Internet, phone, about">

	<!-- set the page title -->
	<title>Tolstra - Manage EOIs</title>
</head>

<body>
	<!-- include the page header -->
	<?php include "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
		<section id="manage_form">
			<form method="get" action="manage.php?">
				<input type="submit" name="manage" value="all">
			</form>
		</section>
		<section id="manage_eoi">
			<table>
				<tr>
					<th>ID</th>
					<th>Job Reference</th>
					<th>First name</th>
					<th>Last name</th>
					<th>Address</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Skills</th>
					<th>Other skills</th>
				</tr>
			<?php
				if($dbconn) {
					if(isset($_GET['manage'])) {
						if($_GET['manage'] == "all") {
							$query = "SELECT * FROM eoi";
							$result = mysqli_query($dbconn, $query);

							if (mysqli_num_rows($result) > 0) {
								while ($row = mysqli_fetch_assoc($result)) {
									echo "<tr>";
									echo "<td>" . $row['EOInumber'] . "</td>";
									echo "<td>" . $row['Job Reference number'] . "</td>";
									echo "<td>" . $row['First name'] . "</td>";
									echo "<td>" . $row['Last name'] . "</td>";
									echo "<td>" . $row['Address'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['Phone'] . "</td>";
									echo "<td>" . $row['Skills'] . "</td>";
									echo "<td>" . $row['Other skills'] . "</td>";
									echo "</tr>";
								}
							} else {
								echo "<tr>";
								echo "<td>There are no eoi to display.</td>";
								echo "</tr>";
							}
						}
					}
					mysqli_close($dbconn);
				}
			?>
			</table>
		</section>
	</main>

	<!-- include the page footer -->
	<?php include "footer.inc"; ?>
</body>

</html>