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
									$jquery = "SELECT DISTINCT `Job Reference number` FROM eoi";
									$jresult = mysqli_query($dbconn, $jquery);
									if (mysqli_num_rows($jresult) > 0) {
										while ($jrow = mysqli_fetch_assoc($jresult)) {
											echo "<option value='" . $jrow['Job Reference number'] . "'>" . $jrow['Job Reference number'] . "</option>";
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
						$dref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
						$dquery = "SELECT COUNT(*) as count FROM eoi WHERE `Job Reference number` = '$dref'";
						$dresult = mysqli_query($dbconn, $dquery);
						$drow = mysqli_fetch_assoc($dresult);

						if ($drow['count'] > 0) {
							echo "<p id='deletion_message'>Successfully deleted " . mysqli_fetch_assoc($dresult)['count'] . " EOIs.</p>";
						} else {
							echo "<p id='deletion_message'>There are no EOIs to delete.</p>";
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

		<form method="get" action="manage.php" class="sort-controls">
			<?php
			/* this php block is from StackOverflow https://stackoverflow.com/questions/9624803/php-get-all-url-variables in order to retain $_GET params after form submission */
			$excluded_keys = ['sort_by', 'sort_order'];
			foreach ($_GET as $key => $value) {
				if (!in_array($key, $excluded_keys)) {
					echo "<input type='hidden' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($value) . "'>";
				}
			}
			?>
			<label for="sort_by">Sort by:</label>
			<select name="sort_by" id="sort_by">
				<option value="EOInumber">EOI id</option>
				<option value="First name" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'First name') echo 'selected'; ?>>First Name</option>
				<option value="Last name" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'Last name') echo 'selected'; ?>>Last Name</option>
				<option value="Job Reference number" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'Job Reference number') echo 'selected'; ?>>Job Ref</option>
				<option value="Email" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'Email') echo 'selected'; ?>>Email</option>
				<option value="Phone" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'Phone') echo 'selected'; ?>>Phone</option>
				<option value="Status" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'Status') echo 'selected'; ?>>Status</option>
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

		<!-- mysql queries based on filter and sorting parameters -->
		<?php
		if($dbconn) {
			$ref;
			$query;

			// sorting function
			function sortResult($unsortedQuery) {
				$tableColumns = ['EOInumber', 'Job Reference number', 'First name', 'Last name', 'Email', 'Phone', 'Status'];

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
				if(isset($_GET['new_status']) && ($_GET['new_status'] == "New" || $_GET['new_status'] == "Current" || $_GET['new_status'] == "Final")) {
					$status_ref = mysqli_real_escape_string($dbconn, $_GET['new_status']);
					$eoi_ref = mysqli_real_escape_string($dbconn, $_GET['eoi_number']);
					$query = "UPDATE eoi SET Status = '$status_ref' WHERE EOInumber = $eoi_ref";
					
					mysqli_query($dbconn, $query);
				}
			}

			// delete eois logic
			if(isset($_GET['action']) && $_GET['action'] == "delete") {
				$ref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
				$query = "SELECT * FROM eoi WHERE `Job Reference number` = '$ref'";
				$result = mysqli_query($dbconn, $query);

				if (mysqli_num_rows($result) > 0) {
					$query = "DELETE FROM eoi WHERE `Job Reference number` = '$ref'";
					mysqli_query($dbconn, $query);
				}
			}

			// filter logic
			if(isset($_GET['filter'])) {
				switch($_GET['filter']) {
					case 'ref':
						if(isset($_GET['job_ref'])) {
							$ref = mysqli_real_escape_string($dbconn, $_GET['job_ref']);
							$query = "SELECT * FROM eoi WHERE `Job Reference number` = '$ref'";
							$sortedQuery = sortResult($query);
							$result = mysqli_query($dbconn, $sortedQuery);
						}

						break;
					case 'name':
						if(!empty($_GET['fname']) && empty($_GET['lname'])) {
							$ref = mysqli_real_escape_string($dbconn, $_GET['fname']);
							$query = "SELECT * FROM eoi WHERE `First name` = '$ref'";
							$sortedQuery = sortResult($query);
							$result = mysqli_query($dbconn, $sortedQuery);
						}
						if(!empty($_GET['lname']) && empty($_GET['fname'])) {
							$ref = mysqli_real_escape_string($dbconn, $_GET['lname']);
							$query = "SELECT * FROM eoi WHERE `Last name` = '$ref'";
							$sortedQuery = sortResult($query);
							$result = mysqli_query($dbconn, $sortedQuery);
						}
						if(!empty($_GET['fname']) && !empty($_GET['lname'])) {
							$ref_fname = mysqli_real_escape_string($dbconn, $_GET['fname']);
							$ref_lname = mysqli_real_escape_string($dbconn, $_GET['lname']);
							$query = "SELECT * FROM eoi WHERE `First name` = '$ref_fname' AND `Last name` = '$ref_lname'";
							$sortedQuery = sortResult($query);
							$result = mysqli_query($dbconn, $sortedQuery);
						}

						break;
					default:
						$query = "SELECT * FROM eoi";
						$sortedQuery = sortResult($query);
						$result = mysqli_query($dbconn, $sortedQuery);

						break;
				}
			} else {
				$query = "SELECT * FROM eoi";
				$sortedQuery = sortResult($query);
				$result = mysqli_query($dbconn, $sortedQuery);
			}
		}
		?>
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
			<!-- below php block populates the table -->
			<?php
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
					echo "<td class='status'>";
					echo "<form method='get' action='manage.php'>";
					echo "<input type='hidden' name='eoi_number' value='" . $row['EOInumber'] . "'>";
					echo "<select name='new_status' id='new_status'>";
					$statuses = ['New', 'Current', 'Final'];
					foreach ($statuses as $status) {
						$selected = ($row['Status'] === $status) ? "selected" : "";
						echo "<option value='$status' $selected>$status</option>";
					}
					echo "</select> ";
					echo "<button type='submit' name='update' value='status'>Update</button>";
					echo "</form>";
					if((isset($_GET['eoi_number']) && $_GET['eoi_number'] == $row['EOInumber']) && (isset($_GET['update']) && $_GET['update'] == 'status') && isset($_GET['new_status'])) {
						echo "<p id='update_text'>Updated!</p>";
					}
					echo "</td>";
					echo "</tr>";
				}
			} else {
				echo "<tr>";
				echo "<td colspan='9'>There are no eoi to display.</td>";
				echo "</tr>";
			}
			?>

			</table>
		</section>
	</main>

	<!-- include the page footer -->
	<?php include "footer.inc"; ?>
</body>

</html>