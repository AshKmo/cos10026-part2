<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

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
	<?php include_once "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Tolstra - Manage EOIs">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, telecommunications, Internet, phone, about">

	<!-- set the page title -->
	<title>Tolstra - Manage EOIs</title>
</head>

<body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
		<section class="filter-bar">
		<div class="filter-options">
			<!-- Form to filter table by first name or last name, or both -->
			<form method="get" action="manage.php">
				<h4>Search EOIs by applicant name</h4>
				<div class="filter-group">
					<input type="text" name="fname" placeholder="First Name">
					<input type="text" name="lname" placeholder="Last Name">
					<button type="submit" name="filter" value="name">Search</button>
				</div>
			</form>

			<!-- Form to filter table rows by job reference number -->
			<form method="get" action="manage.php">
				<h4>Search or delete EOIs by job reference number</h4>
				<div class="filter-group">
					<label for="job_ref">Job Ref:
						<select name="job_ref" id="job_ref">
							<?php
								if($dbconn) {
									$query = "SELECT DISTINCT `job_id` FROM `job_descriptions`";
									$result = mysqli_query($dbconn, $query);
									if (mysqli_num_rows($result) > 0) {
										while ($row = mysqli_fetch_assoc($result)) {
											echo "<option value='" . $row['job_id'] . "'>" . $row['job_id'] . "</option>";
										}
									} else {
										echo "<option>No job references found</option>";
									}
								}
							?>
						</select>
					</label>
					<button type="submit" name="filter" value="ref">Search</button>
					<button type="submit" class="del-button" name="action" value="delete">Delete EOIs</button>

					<!-- below php block deletes selected EOIs and displays a confirmation message -->
					<?php
					if(isset($_GET['action']) && $_GET['action'] == "delete") {
						$ref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
						$query = "SELECT COUNT(*) as count FROM eoi WHERE jobReferenceNumber = '$ref'";
						$result = mysqli_query($dbconn, $query);
						$row = mysqli_fetch_assoc($result);

						if ($row['count'] > 0) {
							$query = "SELECT * FROM eoi WHERE jobReferenceNumber = '$ref'";
							$result = mysqli_query($dbconn, $query);

							if (mysqli_num_rows($result) > 0) {
								$query = "DELETE FROM eoi WHERE jobReferenceNumber = '$ref'";
								mysqli_query($dbconn, $query);
							}
							
							echo "<p class='deletion_message'>Successfully deleted " . $row['count'] . " EOIs.</p>";
						} else {
							echo "<p class='deletion_message'>There are no EOIs to delete.</p>";
						}

						
					}
					?>
				</div>
			</form>
		</div>

		<form method="get" action="manage.php">
			<div class="filter-group">
				<button type="submit" name="filter" value="all">Retrieve All EOIs</button>
			</div>
		</form>

		<!-- Form manages sorting controls. Enables sorting rows by any field, ascending or descending -->
		<form method="get" action="manage.php" class="sort-controls">
			<?php
			/* this php block is from StackOverflow https://stackoverflow.com/questions/9624803/php-get-all-url-variables in order to retain $_GET params after form submission */
			$excluded_keys = ['sort_by', 'sort_order', 'eoi_number', 'update', 'new_status'];
			foreach ($_GET as $key => $value) {
				if (!in_array($key, $excluded_keys)) {
					echo "<input type='hidden' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($value) . "'>";
				}
			}
			?>
			<label for="sort_by">Sort by:</label>
			<select name="sort_by" id="sort_by">
				<option value="EOInumber">EOI id</option>
				<option value="jobReferenceNumber" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'jobReferenceNumber') echo 'selected'; ?>>Job Ref</option>
				<option value="firstName" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'firstName') echo 'selected'; ?>>First Name</option>
				<option value="lastName" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'lastName') echo 'selected'; ?>>Last Name</option>
				<option value="town" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'town') echo 'selected'; ?>>Suburb</option>
				<option value="state" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'state') echo 'selected'; ?>>State</option>
				<option value="postcode" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'postcode') echo 'selected'; ?>>Postcode</option>
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
		<section class="manage_eoi">
			<h3>Manage EOIs</h3>
			<!-- mysql queries based on filter and sorting parameters -->
			<?php
			if($dbconn) {
				// sorting function
				function sortResult($unsortedQuery) {
					$tableColumns = ['EOInumber', 'jobReferenceNumber', 'firstName', 'lastName', 'town', 'state', 'postcode', 'email', 'phone', 'status'];

					if(isset($_GET['sort_by']) && in_array($_GET['sort_by'], $tableColumns)) {
						$sortBy = htmlspecialchars($_GET['sort_by']);
						if(isset($_GET['sort_order']) && $_GET['sort_order'] == "desc") {
							$sortedQuery = $unsortedQuery . " ORDER BY `$sortBy` DESC";
						} else {
							$sortedQuery = $unsortedQuery . " ORDER BY `$sortBy` ASC";
						}

						return $sortedQuery;
					}
					return $unsortedQuery;
				}

				// update eoi status logic
				if(isset($_GET['eoi_number']) && (isset($_GET['update']) && $_GET['update'] == 'status')) {
					if(isset($_GET['new_status']) && ($_GET['new_status'] == "new" || $_GET['new_status'] == "current" || $_GET['new_status'] == "final")) {
						$status_ref = mysqli_real_escape_string($dbconn, $_GET['new_status']);
						$eoi_ref = mysqli_real_escape_string($dbconn, $_GET['eoi_number']);
						$query = "UPDATE eoi SET Status = '$status_ref' WHERE EOInumber = $eoi_ref";
						
						mysqli_query($dbconn, $query);
					}
				}

				// filter logic
				if(isset($_GET['filter'])) {
					switch($_GET['filter']) {
						case 'ref':
							if(isset($_GET['job_ref'])) {
								$ref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
								$query = "SELECT * FROM eoi WHERE `jobReferenceNumber` = '$ref'";
							}

							break;
						case 'name':
							if(!empty($_GET['fname']) && empty($_GET['lname'])) {
								$ref = mysqli_real_escape_string($dbconn, $_GET['fname']);
								$query = "SELECT * FROM eoi WHERE `firstName` LIKE '%$ref%'";
							}
							elseif(!empty($_GET['lname']) && empty($_GET['fname'])) {
								$ref = mysqli_real_escape_string($dbconn, $_GET['lname']);
								$query = "SELECT * FROM eoi WHERE `lastName` LIKE '%$ref%'";
							}
							elseif(!empty($_GET['fname']) && !empty($_GET['lname'])) {
								$ref_fname = mysqli_real_escape_string($dbconn, $_GET['fname']);
								$ref_lname = mysqli_real_escape_string($dbconn, $_GET['lname']);
								$query = "SELECT * FROM eoi WHERE `firstName` LIKE '%$ref_fname%' AND `lastName` LIKE '%$ref_lname%'";
							}
							else {
								$query = "SELECT * FROM eoi";
							}

							break;
						default:
							$query = "SELECT * FROM eoi";

							break;
					}
				} else {
					$query = "SELECT * FROM eoi";
				}

				$sortedQuery = sortResult($query);
				$result = mysqli_query($dbconn, $sortedQuery);
			}
			?>
				<table class="manage_table">
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
				<!-- below php block populates the table -->
				<?php
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_assoc($result)) {
						$skills = json_decode($row['requiredTechnicalSkills']);
						echo "<tr>";
						echo "<td>" . $row['EOInumber'] . "</td>";
						echo "<td>" . $row['jobReferenceNumber'] . "</td>";
						echo "<td>" . $row['firstName'] . " " . $row['lastName'] . "</td>";
						echo "<td>" . $row['streetAddress'] . " " . $row['town'] . " " . $row['state'] . " " . $row['postcode'] . "</td>";
						echo "<td>" . $row['email'] . "</td>";
						echo "<td>" . $row['phone'] . "</td>";
						echo "<td>";
						foreach ($skills as $skill) { echo $skill->desc . "<br>"; }
						echo "</td>";
						echo "<td>" . $row['otherSkills'] . "</td>";
						echo "<td class='status'>";
						echo "<form method='get' action='manage.php'>";
						/* below logic is from StackOverflow https://stackoverflow.com/questions/9624803/php-get-all-url-variables in order to retain $_GET params after form submission */
						$excluded_keys = ['eoi_number', 'update', 'new_status'];
						foreach ($_GET as $key => $value) {
							if (!in_array($key, $excluded_keys)) {
								echo "<input type='hidden' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($value) . "'>";
							}
						}
						/* above logic is from StackOverflow https://stackoverflow.com/questions/9624803/php-get-all-url-variables in order to retain $_GET params after form submission */
						echo "<input type='hidden' name='eoi_number' value='" . $row['EOInumber'] . "'>";
						echo '<label class="hidden-label" for="new_status">Status</label>';
						echo "<select name='new_status'>";
						$statuses = ['new', 'current', 'final'];
						foreach ($statuses as $status) {
							$selected = ($row['status'] === $status) ? "selected" : "";
							echo "<option value='$status' $selected>$status</option>";
						}
						echo "</select> ";
						echo "<button type='submit' name='update' value='status'>Update</button>";
						echo "</form>";
						if(isset($_GET['eoi_number']) && $_GET['eoi_number'] == $row['EOInumber']) {
							if (isset($_GET['update']) && $_GET['update'] == 'status') {
								echo "<p class='update_text'>Updated!</p>";
							}
						}
						echo "</td>";
						echo "</tr>";
					}
				} else {
					echo "<tr>";
					echo "<td colspan='9'><h4>There are no eoi to display.</h4></td>";
					echo "</tr>";
				}
				?>

			</table>
		</section>
	</main>

	<!-- include the page footer -->
	<?php include_once "footer.inc"; ?>
</body>
</html>