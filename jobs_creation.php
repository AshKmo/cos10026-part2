<!DOCTYPE html>

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
            <h1 id="job-mod-title">Job Modification</h1>            
            <form class="job-creation-form" method="POST" action="process_job_creation.php">
				<fieldset class="job-creation-fieldset">
					<legend>Job Info:</legend>
					<div>
						<p>
							<label for="jobs-creation-title">Job Title:</label>
							<input class="jobs-creation-input" type="text" name="position" id="jobs-creation-position" pattern="[a-zA-Z]{1,20}" size="20" maxlength="40" placeholder="Job Title Here" required>
						</p>

						<p>
							<label for="jobs-creation-id">Job ID:</label>
							<input class="jobs-creation-input" type="text" name="job_id" id="jobs-creation-id" pattern="[a-zA-Z]{1,20}" size="20" maxlength="5" placeholder="Job ID Here" required>
						</p>

						<p>
							<label for="jobs-creation-id">Job Description</label>
							<textarea id="apply-other-skills" name="description" placeholder="Enter job description"></textarea>
						</p>
						<br>

						<p>
							<label for="jobs-creation-id">Salary range:</label>
							<input class="jobs-creation-input" type="text" name="salary_range" id="jobs-creation-salary" size="20" maxlength="30" placeholder="$0 to $999,999,999" required>
						</p>

						<p>
							<label for="jobs-creation-id">Employment type:</label>
							<input class="jobs-creation-input" type="text" name="employment_type" id="jobs-creation-employ-type" pattern="[a-zA-Z]{1,20}" size="20" maxlength="30" placeholder="Full time, part time, etc" required>
						</p>

						<p>
							<label for="jobs-creation-id">Office Location:</label>
							<input class="jobs-creation-input" type="text" name="location" id="jobs-creation-location" size="20" maxlength="70" placeholder="Location Here" required>
						</p>
						<br>
						<p>
							<label for="jobs-creation-id">Name of Superior:</label>
							<input class="jobs-creation-input" type="text" name="report_to_name" id="jobs-creation-superior" pattern="[a-zA-Z]{1,20}" size="20" maxlength="50" placeholder="Superior Name Here" required>
						</p>

						<p>
							<label for="jobs-creation-id">Title of Superior:</label>
							<input class="jobs-creation-input" type="text" name="report_to_title" id="jobs-creation-superior-title" pattern="[a-zA-Z]{1,20}" size="20" maxlength="40" placeholder="Superior Title Here" required>
						</p>
					</div>
				</fieldset>
				<fieldset>
					<legend>Expectations</legend>
					<div>
						<ol>
							<?php
								for ($i = 0; $i < 50; $i++) {
									echo "<li class=\"jobs-creation-expectations\">";
									echo "<input type=\"text\" name=\"expectation-" . $i ."\" size=\"50\" maxlength=\"200\" placeholder=\"Expectation " . $i+1 ."\">";
									#echo "<br>";
									#echo "<input type=\"checkbox\" id=\"jobs-creation-" . $i . "-checkbox\">";
									#echo "<label for=\"jobs-creation-" . $i . "-checkbox\"\>Subitem?</label>";
									echo "</li>";
								}
							?>
						</ol>
					</div>
				</fieldset>
			</form>
        </main>
    </body>
</html>