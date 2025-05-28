<!DOCTYPE html>
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

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part1/about.php -->

<!-- the page language is set to English -->
<html lang="en">

    <head>
	    <!-- include some common meta tags shared between all regular pages -->
	    <?php include "meta.inc"; ?>

	    <!-- set the page description -->
	    <meta name="description" content="Tolstra - Manage Jobs">

	    <!-- set keywords for SEO -->
	    <meta name="keywords" content="Tolstra, telecommunications, Internet, phone, about">

	    <!-- set the page title -->
	    <title>Tolstra - Manage Jobs</title>
    </head>

    <body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	    <main>
            <h1 id="jobs-manage-title">Job Management</h1>
			
			<?php
				// check if a job has just been added/removed, and add text accordingly
				if (isset($_SESSION["success"])) {
					if ($_SESSION["success"] === true) {
						echo "<p class=\"jobs-manage-success\">" . $_SESSION["message"] . "</p>";
					} else {
						echo "<p class=\"jobs-manage-fail\">Failed: " . $_SESSION["message"] . "</p>";
					}
					echo "<br>";
					unset($_SESSION["success"]);
					unset($_SESSION["message"]);
				}
			?>
			<div>
				<table id="jobs-manage-table">
					<!-- Create a table to contain all jobs -->
					<tr>
						<th class="jobs-manage-table-position">Job Name</th>
						<th class="jobs-manage-table-id">Job ID</th>
						<th class="jobs-manage-table-manage">Manage</th>
					</tr>
                    <?php
						// connect to the database and select the position titles and job IDs
						$query = "SELECT position, job_id FROM job_descriptions";
						$result = $dbconn -> query($query);

						if ($result -> num_rows > 0) {
							while ($row = $result -> fetch_assoc()) {
								// add a row into the database for each position
								echo "<tr>";
								echo "<td>" . $row['position'] . "</td>";
								echo "<td>" . $row['job_id'] . "</td>";
								
								// add a button in the final column which sends to the processing page to delete the job
								echo "<td>";
								echo "<form action='process_jobs.php' method='POST'>";
								echo "<input type='hidden' name='type' value='delete_job'>";
								echo "<input type='hidden' name='job_id' value='" . $row['job_id'] . "'>";
								echo "<input type='submit' class='del-button' value='Delete'>";
								echo "</form>";
								echo "</td>";
								echo "</tr>";
							}
						}
                    ?>
            	</table>
			</div>

			<br>
			<hr>
			<br>

			<fieldset class="jobs-manage-fieldset">
				<legend id="jobs-manage-create-job"><strong>Create Job</strong></legend>
				<br>
				<form class="jobs-manage-form" method="POST" action="process_jobs.php">
					<!-- Add a form to allow users to input new job details -->
					<input type="hidden" name="type" value="create_job">
					<fieldset class="jobs-manage-fieldset">
						<legend>Job Info:</legend>
						<!-- Add a text input for each job detail (most validation is done in the process_jobs page) -->
						<div>
							<p>
								<label for="jobs-manage-position">Job Title:</label>
								<input class="jobs-manage-input" type="text" name="position" id="jobs-manage-position" size="20" maxlength="40" placeholder="Job Title Here" required>
							</p>

							<p>
								<label for="jobs-manage-id">Job ID:</label>
								<input class="jobs-manage-input" type="text" name="job_id" id="jobs-manage-id" size="20" maxlength="5" placeholder="Job ID Here" required>
							</p>

							<p>
								<label for="jobs-manage-description">Job Description</label>
								<textarea id="jobs-manage-description" name="description" placeholder="Enter job description" required></textarea>
							</p>
							<br>

							<p>
								<label for="jobs-manage-salary">Salary range:</label>
								<input class="jobs-manage-input" type="text" name="salary_range" id="jobs-manage-salary" size="20" maxlength="30" placeholder="$0 to $999,999,999" required>
							</p>

							<p>
								<label for="jobs-manage-employ-type">Employment type:</label>
								<input class="jobs-manage-input" type="text" name="employment_type" id="jobs-manage-employ-type" size="20" maxlength="30" placeholder="Full time, part time, etc" required>
							</p>

							<p>
								<label for="jobs-manage-location">Office Location:</label>
								<input class="jobs-manage-input" type="text" name="location" id="jobs-manage-location" size="20" maxlength="70" placeholder="Location Here" required>
							</p>
							<br>
							<p>
								<label for="jobs-manage-superior">Name of Superior:</label>
								<input class="jobs-manage-input" type="text" name="report_to_name" id="jobs-manage-superior" size="20" maxlength="50" placeholder="Superior Name Here" required>
							</p>

							<p>
								<label for="jobs-manage-superior-title">Title of Superior:</label>
								<input class="jobs-manage-input" type="text" name="report_to_title" id="jobs-manage-superior-title" size="20" maxlength="40" placeholder="Superior Title Here" required>
							</p>
						</div>
					</fieldset>
					<br>
					<fieldset class="jobs-manage-fieldset">
						<legend>Expectations</legend>
						<div>
							<ol>
								<!-- Create 50 text inputs and 49 checkboxes to collect expectation data, of which all inputs with information will be displayed, alongside the input after the current one (done through CSS) -->
								<!-- The checkbox is used to determine if an entry is a subitem or not -->
								<?php
									for ($i = 0; $i < 50; $i++) {
										echo "<li class=\"jobs-manage-list\">";
										// each input has a unique name so that the process_jobs page can recognise them individually and collate them into an array to put into the database
										echo "<input type=\"text\" name=\"expectation-" . $i ."\" size=\"110\" maxlength=\"200\" placeholder=\"Expectation " . $i+1 ."\" class=\"jobs-manage-list-input\" id=\"expectation-" . $i ."\">";
										echo "<label for=\"expectation-" . $i ."\" class=\"jobs-manage-hiden-label\">Expectation " .  $i + 1 . "</label>";
										if ($i != 0) {
											echo "<br>";
											echo "<input type=\"checkbox\" name=\"expectation-" . $i . "-sub\" value=\"true\"  class=\"jobs-manage-list-checkbox\" id=\"jobs-manage-expectation-" . $i . "-checkbox\">";
											echo "<label for=\"jobs-manage-expectation-" . $i . "-checkbox\">Subitem?</label>";
										}
										echo "</li>";
									}
								?>
							</ol>
						</div>
					</fieldset>
					<br>
					<fieldset class="jobs-manage-fieldset">
						<legend>Essential Prerequisites</legend>
						<div>
							<ul>
								<!-- Same as expectations -->
								<?php
									for ($i = 0; $i < 50; $i++) {
										echo "<li class=\"jobs-manage-list\">";
										echo "<input type=\"text\" name=\"essential-prereq-" . $i ."\" size=\"110\" maxlength=\"200\" placeholder=\"Expectation " . $i+1 ."\" class=\"jobs-manage-list-input\" id=\"essential-prereq-" . $i ."\">";									
										echo "<label for=\"essential-prereq-" . $i ."\" class=\"jobs-manage-hiden-label\">Essential Prerequisite " .  $i + 1 . "</label>";
										if ($i != 0) {
											echo "<br>";
											echo "<input type=\"checkbox\" name=\"essential-prereq-" . $i . "-sub\" value=\"true\" class=\"jobs-manage-list-checkbox\" id=\"jobs-manage-essential-prereq-" . $i . "-checkbox\">";
											echo "<label for=\"jobs-manage-essential-prereq-" . $i . "-checkbox\">Subitem?</label>";
										}
										echo "</li>";
									}
								?>
							</ul>
						</div>
					</fieldset>
					<br>
					<fieldset class="jobs-manage-fieldset">
						<legend>Preferable Prerequisites</legend>
						<div>
							<ul>
								<!-- Same as expectations -->
								<?php
									for ($i = 0; $i < 50; $i++) {
										echo "<li class=\"jobs-manage-list\">";
										echo "<input type=\"text\" name=\"preferable-prereq-" . $i ."\" size=\"110\" maxlength=\"200\" placeholder=\"Expectation " . $i+1 ."\" class=\"jobs-manage-list-input\" id=\"preferable-prereq-" . $i ."\">";
										echo "<label for=\"preferable-prereq-" . $i ."\" class=\"jobs-manage-hiden-label\">Preferable Prerequisite " .  $i + 1 . "</label>";
										if ($i != 0) {
											echo "<br>";
											echo "<input type=\"checkbox\" name=\"preferable-prereq-" . $i . "-sub\" value=\"true\" class=\"jobs-manage-list-checkbox\" id=\"jobs-manage-preferable-prereq-" . $i . "-checkbox\">";
											echo "<label for=\"jobs-manage-preferable-prereq-" . $i . "-checkbox\">Subitem?</label>";
										}
										echo "</li>";
									}
								?>
							</ul>
						</div>
					</fieldset>
					<br>
					<input type="submit" value="Create Job" id="jobs-manage-create-submit">
				</form>
			</fieldset>
        </main>
    </body>
</html>