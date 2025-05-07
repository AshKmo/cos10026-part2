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
		<section id="filter">
			<form method="get" action="manage.php" class="filter-bar">
				<!-- List All -->
				<div class="filter-group">
					<button type="submit" name="filter" value="all">List All EOIs</button>
				</div>

				<!-- Job Ref Filter -->
				<div class="filter-group">
					<label for="job_ref">Job Ref:
					<input type="text" name="job_ref"></label>
					<button type="submit" name="filter" value="ref">Search</button>
				</div>

				<!-- Name Filter -->
				<div class="filter-group">
					<label for="fname">First:
					<input type="text" name="fname" placeholder="First Name"></label>
					<label for="lname">Last:
					<input type="text" name="lname" placeholder="Last Name"></label>
					<button type="submit" name="filter" value="name">Search</button>
				</div>

				<!-- Delete by Job Ref -->
				<div class="filter-group">
					<label for="delete_job_ref">Delete Job Ref:
					<input type="text" name="delete_job_ref"></label>
					<button type="submit" name="filter"onclick="return confirm('Delete all EOIs for this job?');">Delete</button>
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
									$ref = $_GET['job_ref'];
									$query = "SELECT * FROM eoi WHERE `Job Reference number` = $ref";
									$result = mysqli_query($dbconn, $query);

									include("manage_table.inc");
								}
								break;
							case 'name':
								if(isset($_GET['fname']) && !isset($_GET['lname'])) {
									$ref = mysqli_real_escape_string($dbconn, $_GET['fname']);
									$query = "SELECT * FROM eoi WHERE `First name` = '$ref'";
									$result = mysqli_query($dbconn, $query);

									include("manage_table.inc");
								}
								if(isset($_GET['lname']) && !isset($_GET['fname'])) {
									$ref = mysqli_real_escape_string($dbconn, $_GET['lname']);
									$query = "SELECT * FROM eoi WHERE `Last name` = '$ref'";
									$result = mysqli_query($dbconn, $query);

									include("manage_table.inc");
								}
								break;
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