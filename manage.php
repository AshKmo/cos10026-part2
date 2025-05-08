<?php
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
		<div id="filter-options">
			<!-- Name Filter -->
			<form method="get" action="manage.php">
				<h4>Search EOIs by applicant name</h4>
				<div class="filter-group">
					<input type="text" name="fname" placeholder="First Name">
					<input type="text" name="lname" placeholder="Last Name">
					<button type="submit" name="filter" value="name">Search</button>
				</div>
			</form>

			<!-- Job Ref Filter -->
			<form method="get" action="manage.php">
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
						</select>
					</label>
					<button type="submit" name="filter" value="ref">Search</button>
					<button type="submit" class="apply-fancy-button-bad" name="action" value="delete">Delete EOIs</button>
					<?php
					if(isset($_GET['action']) && $_GET['action'] == "delete") {
						$ref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
						$query = "SELECT COUNT(*) as count FROM eoi WHERE `Job Reference number` = '$ref'";
						$result = mysqli_query($dbconn, $query);
						$row = mysqli_fetch_assoc($result);

						if ($row['count'] > 0) {
							echo "<p id='deletion_message'>Successfully deleted " . mysqli_fetch_assoc($result)['count'] . " EOIs.</p>";
						} else {
							echo "<p id='deletion_message'>There are no EOIs to delete.</p>";
						}
					}
					?>
				</div>
			</form>
		</div>

		<div style="align-self: flex-start;">
			<form method="get" action="manage.php">
				<div class="filter-group">
					<button type="submit" name="filter" value="all">Retrieve All EOIs</button>
				</div>
			</form>
		</div>

		<form method="get" action="manage.php" class="sort-controls">
			<label for="sort_by">Sort by:</label>
			<select name="sort_by" id="sort_by">
				<option value="eoi_id">EOI id</option>
				<option value="fname" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'fname') echo 'selected'; ?>>First Name</option>
				<option value="lname" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'lname') echo 'selected'; ?>>Last Name</option>
				<option value="job_ref" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'job_ref') echo 'selected'; ?>>Job Ref</option>
				<option value="email" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'email') echo 'selected'; ?>>Email</option>
				<option value="phone" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'phone') echo 'selected'; ?>>Phone</option>
				<option value="status" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'status') echo 'selected'; ?>>Status</option>
			</select>

			<label for="sort_order">Order:</label>
			<select name="sort_order" id="sort_order">
				<option value="asc">Ascending</option>
				<option value="desc" <?php if (isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc') echo 'selected'; ?>>Descending</option>
			</select>

			<button type="submit">Sort</button>
		</form>

		</section>
		<hr>
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
				$ref;
				$query;

				if(isset($_GET['action']) && $_GET['action'] == "delete") {
					$ref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
					$query = "SELECT * FROM eoi WHERE `Job Reference number` = '$ref'";
					$result = mysqli_query($dbconn, $query);

					if (mysqli_num_rows($result) > 0) {
						$query = "DELETE FROM eoi WHERE `Job Reference number` = '$ref'";
						mysqli_query($dbconn, $query);
					}
				}

				if(isset($_GET['filter'])) {
					switch($_GET['filter']) {
						case 'ref':
							if(isset($_GET['job_ref'])) {
								$ref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
								$query = "SELECT * FROM eoi WHERE `Job Reference number` = '$ref'";
								$result = mysqli_query($dbconn, $query);
								
								if(!isset($_GET['sort_by'])) {
									include_once("manage_table.inc");
								}
							}
							break;
						case 'name':
							if(!empty($_GET['fname']) && empty($_GET['lname'])) {
								$ref = mysqli_real_escape_string($dbconn, $_GET['fname']);
								$query = "SELECT * FROM eoi WHERE `First name` = '$ref'";
								$result = mysqli_query($dbconn, $query);

								if(!isset($_GET['sort_by'])) {
									include_once("manage_table.inc");
								}
							}
							if(!empty($_GET['lname']) && empty($_GET['fname'])) {
								$ref = mysqli_real_escape_string($dbconn, $_GET['lname']);
								$query = "SELECT * FROM eoi WHERE `Last name` = '$ref'";
								$result = mysqli_query($dbconn, $query);
								if(!isset($_GET['sort_by'])) {
									include_once("manage_table.inc");
								}
							}
							if(!empty($_GET['fname']) && !empty($_GET['lname'])) {
								$ref_fname = mysqli_real_escape_string($dbconn, $_GET['fname']);
								$ref_lname = mysqli_real_escape_string($dbconn, $_GET['lname']);
								$query = "SELECT * FROM eoi WHERE `First name` = '$ref_fname' AND `Last name` = '$ref_lname'";
								$result = mysqli_query($dbconn, $query);

								if(!isset($_GET['sort_by'])) {
									include_once("manage_table.inc");
								}
							}
							break;
						default:
							$query = "SELECT * FROM eoi";
							$result = mysqli_query($dbconn, $query);

							if(!isset($_GET['sort_by'])) {
								include_once("manage_table.inc");
							}
							break;
					}
				} else {
					$query = "SELECT * FROM eoi";
					$result = mysqli_query($dbconn, $query);

					if(!isset($_GET['sort_by'])) {
						include_once("manage_table.inc");
					}
				}
				if(isset($_GET['sort_by'])) {
					switch($_GET['sort_by']) {
						case 'eoi_id':
							if(isset($_GET['sort_order']) && $_GET['sort_order'] == "desc") {
								$query = $query . " ORDER BY `EOInumber` DESC";
							} else {
								$query = $query . " ORDER BY `EOInumber` ASC";
							}
							$result = mysqli_query($dbconn, $query);

							include_once("manage_table.inc");
							break;
						case 'job_ref':
							if(isset($_GET['sort_order']) && $_GET['sort_order'] == "desc") {
								$query = $query . " ORDER BY `Job Reference number` DESC";
							} else {
								$query = $query . " ORDER BY `Job Reference number` ASC";
							}
							$result = mysqli_query($dbconn, $query);

							include_once("manage_table.inc");
							break;
						case 'fname':
							if(isset($_GET['sort_order']) && $_GET['sort_order'] == "desc") {
								$query = $query . " ORDER BY `First name` DESC";
							} else {
								$query = $query . " ORDER BY `First name` ASC";
							}
							$result = mysqli_query($dbconn, $query);

							include_once("manage_table.inc");
							break;
						case 'lname':
							if(isset($_GET['sort_order']) && $_GET['sort_order'] == "desc") {
								$query = $query . " ORDER BY `Last name` DESC";
							} else {
								$query = $query . " ORDER BY `Last name` ASC";
							}
							$result = mysqli_query($dbconn, $query);

							include_once("manage_table.inc");
							break;
						case 'email':
							if(isset($_GET['sort_order']) && $_GET['sort_order'] == "desc") {
								$query = $query . " ORDER BY `Email` DESC";
							} else {
								$query = $query . " ORDER BY `Email` ASC";
							}
							$result = mysqli_query($dbconn, $query);

							include_once("manage_table.inc");
							break;
						case 'phone':
							if(isset($_GET['sort_order']) && $_GET['sort_order'] == "desc") {
								$query = $query . " ORDER BY `Phone` DESC";
							} else {
								$query = $query . " ORDER BY `Phone` ASC";
							}
							$result = mysqli_query($dbconn, $query);

							include_once("manage_table.inc");
							break;
						case 'status':
							if(isset($_GET['sort_order']) && $_GET['sort_order'] == "desc") {
								$query = $query . " ORDER BY `Status` DESC";
							} else {
								$query = $query . " ORDER BY `Status` ASC";
							}
							$result = mysqli_query($dbconn, $query);

							include_once("manage_table.inc");
							break;
						default:
							include_once("manage_table.inc");
							break;
					}
				}
			}
			?>
			</table>
		</section>
	</main>

	<!-- include the page footer -->
	<?php include "footer.inc"; ?>
</body>

</html>