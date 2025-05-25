<!DOCTYPE html>
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['privilege'])) {
    if (isset($_SESSION['username'])) {
        header('Location: job_manage.php');
        exit;
    } else {
        header('Location: login.php');
        exit;
    }
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
	    <meta name="description" content="About Tolstra">

	    <!-- set keywords for SEO -->
	    <meta name="keywords" content="Tolstra, telecommunications, Internet, phone, about">

	    <!-- set the page title -->
	    <title>About Tolstra</title>
    </head>

    <body>
	<!-- include the page header -->
	<?php include "header.inc" ?>

	<!-- define the main body content of the page -->
	    <main>
            <h1 id="jobs-manage-title">Job Management</h1>
			
			<?php
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

			<table>
                <tr>
                    <th class="jobs-manage-table-position">Job Name</th>
                    <th class="jobs-manage-table-id">Job ID</th>
                    <th class="jobs-manage-table-manage">Manage</th>
                    
                    <?php
                    $query = "SELECT position, job_id FROM job_descriptions";
                    $result = $dbconn -> query($query);

                    if ($result -> num_rows > 0) {
                        while ($row = $result -> fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['position'] . "</td>";
                            echo "<td>" . $row['job_id'] . "</td>";
                            
                            // Delete User Button / Form
                            echo "<td>";
                            echo "<form action='process_jobs.php' method='POST'>";
                            echo "<input type='hidden' name='type' value='delete_job'>";
                            echo "<input type='hidden' name='job_id' value='" . $row['job_id'] . "'>";
                            echo "<input type='submit' class='apply-fancy-button-bad' value='Delete'>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
            </table>

			<br>
			<hr>
			<br>

			<fieldset class="jobs-manage-fieldset">
				<legend id="jobs-manage-create-job"><strong>Create Job</strong></legend>
				<form class="jobs-manage-form" method="POST" action="process_jobs.php">
					<input type="hidden" name="type" value="create_job">
					<fieldset class="jobs-manage-fieldset">
						<legend>Job Info:</legend>
						<div>
							<p>
								<label for="jobs-manage-title">Job Title:</label>
								<input class="jobs-manage-input" type="text" name="position" id="jobs-manage-position" size="20" maxlength="40" placeholder="Job Title Here" required>
							</p>

							<p>
								<label for="jobs-manage-id">Job ID:</label>
								<input class="jobs-manage-input" type="text" name="job_id" id="jobs-manage-id" size="20" maxlength="5" placeholder="Job ID Here" required>
							</p>

							<p>
								<label for="jobs-manage-id">Job Description</label>
								<textarea id="apply-other-skills" name="description" placeholder="Enter job description" required></textarea>
							</p>
							<br>

							<p>
								<label for="jobs-manage-id">Salary range:</label>
								<input class="jobs-manage-input" type="text" name="salary_range" id="jobs-manage-salary" size="20" maxlength="30" placeholder="$0 to $999,999,999" required>
							</p>

							<p>
								<label for="jobs-manage-id">Employment type:</label>
								<input class="jobs-manage-input" type="text" name="employment_type" id="jobs-manage-employ-type" size="20" maxlength="30" placeholder="Full time, part time, etc" required>
							</p>

							<p>
								<label for="jobs-manage-id">Office Location:</label>
								<input class="jobs-manage-input" type="text" name="location" id="jobs-manage-location" size="20" maxlength="70" placeholder="Location Here" required>
							</p>
							<br>
							<p>
								<label for="jobs-manage-id">Name of Superior:</label>
								<input class="jobs-manage-input" type="text" name="report_to_name" id="jobs-manage-superior" size="20" maxlength="50" placeholder="Superior Name Here" required>
							</p>

							<p>
								<label for="jobs-manage-id">Title of Superior:</label>
								<input class="jobs-manage-input" type="text" name="report_to_title" id="jobs-manage-superior-title" size="20" maxlength="40" placeholder="Superior Title Here" required>
							</p>
						</div>
					</fieldset>
					<br>
					<fieldset class="jobs-manage-fieldset">
						<legend>Expectations</legend>
						<div>
							<ol>
								<?php
									for ($i = 0; $i < 50; $i++) {
										echo "<li class=\"jobs-manage-list\">";
										echo "<input type=\"text\" name=\"expectation-" . $i ."\" size=\"50\" maxlength=\"200\" placeholder=\"Expectation " . $i+1 ."\" class=\"jobs-manage-list-input\">";
										if ($i != 0) {
											echo "<br>";
											echo "<input type=\"checkbox\" id=\"jobs-manage-expectation-" . $i . "-checkbox\" name=\"expectation-" . $i . "-sub\" value=\"true\">";
											echo "<label for=\"jobs-manage-expectation-" . $i . "-checkbox\"\>Subitem?</label>";
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
								<?php
									for ($i = 0; $i < 50; $i++) {
										echo "<li class=\"jobs-manage-list\">";
										echo "<input type=\"text\" name=\"essential-prereq-" . $i ."\" size=\"50\" maxlength=\"200\" placeholder=\"Expectation " . $i+1 ."\" class=\"jobs-manage-list-input\">";									
										if ($i != 0) {
											echo "<br>";
											echo "<input type=\"checkbox\" id=\"jobs-manage-essential-prereq-" . $i . "-checkbox\" name=\"essential-prereq-" . $i . "-sub\" value=\"true\">";
											echo "<label for=\"jobs-manage-essential-prereq-" . $i . "-checkbox\"\>Subitem?</label>";
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
								<?php
									for ($i = 0; $i < 50; $i++) {
										echo "<li class=\"jobs-manage-list\">";
										echo "<input type=\"text\" name=\"preferable-prereq-" . $i ."\" size=\"50\" maxlength=\"200\" placeholder=\"Expectation " . $i+1 ."\" class=\"jobs-manage-list-input\">";
										if ($i != 0) {
											echo "<br>";
											echo "<input type=\"checkbox\" id=\"jobs-manage-preferable-prereq-" . $i . "-checkbox\" name=\"preferable-prereq-" . $i . "-sub\" value=\"true\">";
											echo "<label for=\"jobs-manage-preferable-prereq-" . $i . "-checkbox\"\>Subitem?</label>";
										}
										echo "</li>";
									}
								?>
							</ul>
						</div>
					</fieldset>

					<input id="jobs-manage-confirm" type="submit" value="Create Job">
				</form>
			</fieldset>
        </main>
    </body>
</html>