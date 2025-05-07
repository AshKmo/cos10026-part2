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
					<button type="submit" name="manage" value="all">List All</button>
				</div>

				<!-- Job Ref Filter -->
				<div class="filter-group">
					<label for="job_ref">Job Ref:
					<input type="text" name="job_ref"></label>
					<button type="submit" name="action" value="filter_by_job">Search</button>
				</div>

				<!-- Name Filter -->
				<div class="filter-group">
					<label for="first_name">First:
					<input type="text" name="first_name" placeholder="First Name"></label>
					<label for="last_name">Last:
					<input type="text" name="last_name" placeholder="Last Name"></label>
					<button type="submit" name="action" value="filter_by_name">Search</button>
				</div>

				<!-- Delete by Job Ref -->
				<div class="filter-group">
					<label for="delete_job_ref">Delete Job Ref:
					<input type="text" name="delete_job_ref"></label>
					<button type="submit" name="action" value="delete_by_job" onclick="return confirm('Delete all EOIs for this job?');">Delete</button>
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
					if(isset($_GET['manage'])) {
						if($_GET['manage'] == "all") {
							$query = "SELECT * FROM eoi";
							$result = mysqli_query($dbconn, $query);

							if (mysqli_num_rows($result) > 0) {
								while ($row = mysqli_fetch_assoc($result)) {
									echo "<tr>";
									echo "<td>" . $row['EOInumber'] . "</td>";
									echo "<td>" . $row['Job Reference number'] . "</td>";
									echo "<td>" . $row['First name'] . " " . $row['Last name'] . "</td>";
									echo "<td>" . $row['Address'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['Phone'] . "</td>";
									echo "<td>" . $row['Skills'] . "</td>";
									echo "<td>" . $row['Other skills'] . "</td>";
									echo "<td>";
									echo "<form method='post' action='manage.php'>";
									echo "<input type='hidden' name='eoi_number' value='" . $row['EOInumber'] . "'>";
									echo "<select name='new_status'>";
									$statuses = ['New', 'Current', 'Final'];
									foreach ($statuses as $status) {
										$selected = ($row['Status'] === $status) ? "selected" : "";
										echo "<option value='$status' $selected>$status</option>";
									}
									echo "</select> ";
									echo "</form>";
									echo "</td>";
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