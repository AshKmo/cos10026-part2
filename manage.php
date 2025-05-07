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
		<section id="filter-bar">
			<form method="get" action="manage.php">
				<!-- Name Filter -->
				<h4>Search EOIs by applicant name</h4>
				<div class="filter-group">
					<input type="text" name="fname" placeholder="First Name">
					<input type="text" name="lname" placeholder="Last Name">
					<button type="submit" name="filter" value="name">Search</button>
				</div>
			</form>
			
			<form method="get" action="manage.php">
				<!-- Job Ref Filter -->
				<h4>Search or delete EOIs by job reference number</h4>
				<div class="filter-group">
					<label for="job_ref">Job Ref:
					<select name="job_ref">
						<?php
							if($dbconn) {
								$query = "SELECT DISTINCT `Job Reference number` FROM eoi";
								$result = mysqli_query($dbconn, $query);

								if (mysqli_num_rows($result) > 0) {
									while ($row = mysqli_fetch_assoc($result)) {
										echo "<option value='" . $row['Job Reference number'] . "'>" . $row['Job Reference number'] . "</option>";
									}
								} else {
									echo "<option>No job references found</option>";
								}
							}
						?>
					</select></label>
					<button type="submit" name="filter" value="ref">Search</button>
					<button type="submit" name="action" value="delete">Delete EOIs</button>
				</div>
			</form>

			<form method="get" action="manage.php">
				<!-- List All -->
				<div class="filter-group">
					<button type="submit" name="filter" value="all">Retrieve All EOIs</button>
				</div>
			</form>
		</section>

		<section id="manage_eoi">
			<table>
				<tr>
					<th>ID</th>
					<th>Job Reference</th>
					<th>Name</th>
					<th>Address</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Skills</th>
					<th>Other skills</th>
					<th>Status</th>
				</tr>
			<?php
				if($dbconn) {
					if(isset($_GET['filter'])) {
						switch($_GET['filter']) {
							case 'all':
								$query = "SELECT * FROM eoi";
								$result = mysqli_query($dbconn, $query);

								include("manage_table.inc");
								
								break;
							case 'ref':
								if(isset($_GET['job_ref'])) {
									$ref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
									$query = "SELECT * FROM eoi WHERE `Job Reference number` = '$ref'";
									$result = mysqli_query($dbconn, $query);
									
									include("manage_table.inc");
								}
								break;
							case 'name':
								if(!empty($_GET['fname']) && empty($_GET['lname'])) {
									$ref = mysqli_real_escape_string($dbconn, $_GET['fname']);
									$query = "SELECT * FROM eoi WHERE `First name` = '$ref'";
									$result = mysqli_query($dbconn, $query);

									include("manage_table.inc");
								}
								if(!empty($_GET['lname']) && empty($_GET['fname'])) {
									$ref = mysqli_real_escape_string($dbconn, $_GET['lname']);
									$query = "SELECT * FROM eoi WHERE `Last name` = '$ref'";
									$result = mysqli_query($dbconn, $query);

									include("manage_table.inc");
								}
								if(!empty($_GET['fname']) && !empty($_GET['lname'])) {
									$ref_fname = mysqli_real_escape_string($dbconn, $_GET['fname']);
									$ref_lname = mysqli_real_escape_string($dbconn, $_GET['lname']);
									$query = "SELECT * FROM eoi WHERE `First name` = '$ref_fname' AND `Last name` = '$ref_lname'";
									$result = mysqli_query($dbconn, $query);

									include("manage_table.inc");
								}
								break;
						}
					}
					if(isset($_GET['action'])) {
						if($_GET['action'] == "delete") {
							$ref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
							$query = "SELECT * FROM eoi WHERE `Job Reference number` = '$ref'";
							$result = mysqli_query($dbconn, $query);

							if (mysqli_num_rows($result) > 0) {
								$query = "DELETE FROM eoi WHERE `Job Reference number` = '$ref'";
								mysqli_query($dbconn, $query);
							}
						}
					}
					
				}
			?>
			</table>
		</section>

		<section id="filter-bar">
			<form method="get" action="manage.php">
				<!-- Name Filter -->
				<h4>Search EOIs by applicant name</h4>
				<div class="filter-group">
					<input type="text" name="fname" placeholder="First Name">
					<input type="text" name="lname" placeholder="Last Name">
					<button type="submit" name="filter" value="name">Search</button>
				</div>
			</form>
			
			<form method="get" action="manage.php">
				<!-- Job Ref Filter -->
				<h4>Search or delete EOIs by job reference number</h4>
				<div class="filter-group">
					<label for="job_ref">Job Ref:
					<select name="job_ref">
						<?php
							if($dbconn) {
								$query = "SELECT DISTINCT `Job Reference number` FROM eoi";
								$result = mysqli_query($dbconn, $query);

								if (mysqli_num_rows($result) > 0) {
									while ($row = mysqli_fetch_assoc($result)) {
										echo "<option value='" . $row['Job Reference number'] . "'>" . $row['Job Reference number'] . "</option>";
									}
								} else {
									echo "<option>No job references found</option>";
								}
								mysqli_close($dbconn);
							}
						?>
					</select></label>
					<button type="submit" name="filter" value="ref">Search</button>
					<button type="submit" name="action" value="delete">Delete EOIs</button>
				</div>
			</form>

			<form method="get" action="manage.php">
				<!-- List All -->
				<div class="filter-group">
					<button type="submit" name="filter" value="all">Retrieve All EOIs</button>
				</div>
			</form>
		</section>
	</main>

	<!-- include the page footer -->
	<?php include "footer.inc"; ?>
</body>

</html>