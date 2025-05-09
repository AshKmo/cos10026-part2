<?php
session_start();
?>

<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part1/apply.php -->

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Apply for a position at Tolstra">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, jobs, application">

	<!-- set the page title -->
	<title>Applications</title>
</head>

<body>
	<!-- include the page header -->
	<?php include "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
		<h1>Apply for a position</h1>

		<p>Tolstra is always committed to providing premium employment opportunities to those who need them the most.
			Please submit your application below, and welcome aboard!</p>

		<?php
		if (isset($_SESSION["APPLY_FORM_ERROR_MESSAGE"])) {
			echo '<hr><p id="apply-error-message">' . ($_SESSION["APPLY_FORM_ERROR_MESSAGE"]) . '</p>';
		}
		?>

		<!-- HTML form containing all fields for submission -->
		<form class="apply-form" method="POST" action="process_eoi.php">
			<fieldset class="apply-fieldset">
				<legend>Personal details</legend>

				<div id="apply-personal-text-fields-container">
					<p>
						<label for="apply-first-name">First name: </label>
						<!-- pattern created using RegExr's regular expression parser and cheat sheet -->
						<!-- availabe at https://regexr.com/ -->
						<input class="apply-input" type="text" name="first-name" id="apply-first-name"
							pattern="[a-zA-Z]{1,20}" size="20" maxlength="20" placeholder="Your first name here"
							required>
					</p>

					<p>
						<label for="apply-last-name">Last name: </label>
						<!-- pattern created using RegExr's regular expression parser and cheat sheet -->
						<!-- availabe at https://regexr.com/ -->
						<input class="apply-input" type="text" name="last-name" id="apply-last-name"
							pattern="[a-zA-Z]{1,20}" size="20" maxlength="20" placeholder="Your last name here"
							required>
					</p>

					<p>
						<label for="apply-email">Email address: </label>
						<!-- pattern created using RegExr's regular expression parser and cheat sheet -->
						<!-- availabe at https://regexr.com/ -->
						<input class="apply-input" type="text" name="email" id="apply-email" placeholder="asdf@asdf.com"
							pattern="\w+@([\w\-]+\.)+[\w\-]{2,}" required>
					</p>

					<p>
						<label for="apply-phone">Phone number: </label>
						<!-- pattern created using RegExr's regular expression parser and cheat sheet -->
						<!-- availabe at https://regexr.com/ -->
						<input class="apply-input" type="text" name="phone" id="apply-phone" placeholder="0000 000 000"
							maxlength="12" pattern="[0-9 ]{8,12}" required>
					</p>

					<p>
						<label for="apply-address">Street address: </label>
						<input class="apply-input" type="text" id="apply-address" name="address" size="40"
							maxlength="40" placeholder="Your street address here" required>
					</p>

					<p>
						<label for="apply-town">Suburb/town: </label>
						<input class="apply-input" type="text" id="apply-town" name="town" size="40" maxlength="40"
							placeholder="Your suburb or town name here" required>
					</p>
				</div>

				<p>
					<label for="apply-state">State/territory: </label>
					<select name="state" id="apply-state" required>
						<option value="">Please select</option>
						<option value="VIC">VIC</option>
						<option value="NSW">NSW</option>
						<option value="QLD">QLD</option>
						<option value="NT">NT</option>
						<option value="WA">WA</option>
						<option value="SA">SA</option>
						<option value="TAS">TAS</option>
						<option value="ACT">ACT</option>
					</select>
				</p>

				<p>
					<label for="apply-postcode">Postcode: </label>
					<!-- pattern created using RegExr's regular expression parser and cheat sheet -->
					<!-- availabe at https://regexr.com/ -->
					<input class="apply-input" type="text" name="postcode" id="apply-postcode" maxlength="4"
						minlength="4" size="4" placeholder="0000" pattern="[0-9]{4}" required>
				</p>

				<p>
					<label for="apply-date-of-birth">Date of birth: </label>
					<input class="apply-input" type="text" name="date-of-birth" id="apply-date-of-birth"
						placeholder="dd/mm/yyyy" size="10" maxlength="10" pattern="[0-9]{2}\/[0-9]{2}\/[0-9]{4}"
						required>
				</p>

				<!-- radio fieldset for gender selection -->
				<fieldset class="apply-fieldset" id="apply-fieldset-gender">
					<legend>Gender</legend>

					<p>
						<input class="apply-input" type="radio" name="gender" value="male" id="apply-gender_male"
							required>
						<label for="apply-gender_male">Male</label>
					</p>

					<p>
						<input class="apply-input" type="radio" name="gender" value="female" id="apply-gender_female"
							required>
						<label for="apply-gender_female">Female</label>
					</p>

					<p>
						<input class="apply-input" type="radio" name="gender" value="other" id="apply-gender_other"
							required>
						<label for="apply-gender_other">Other</label>
					</p>

					<p id="apply-specific-gender-paragraph">
						<label for="apply-specific-gender">Description: </label>
						<input class="apply-input" type="text" name="specific-gender" id="apply-specific-gender"
							placeholder="Please describe your gender here" size="30">
					</p>

					<p>
						<input class="apply-input" type="radio" name="gender" value="unspecified"
							id="apply-gender_unspecified" required checked>
						<label for="apply-gender_unspecified">Prefer not to say</label>
					</p>
				</fieldset>
			</fieldset>

			<fieldset class="apply-fieldset" id="apply-employment-details">
				<legend>Employment details</legend>

				<!-- set all the options for the job selection box and create the selection box -->
				<p>
					<label for="apply-job-reference-number">Position of interest: </label>
					<select name="job-reference-number" id="apply-job-reference-number" required>
						<option value="">Please select</option>
						<option value="IT427">IT Support Technician (IT427)</option>
						<option value="DA193">Data Analyst (DA193)</option>
						<option value="BC279">Blockchain Developer (BC279)</option>
						<option value="QA666">QA Tester (QA666)</option>
					</select>
				</p>

				<!-- a series of checkboxes for each required technical skill for each job -->
				<!-- the relevant checkboxes for each job are shown only when that job is selected above -->
				<!-- this is done by applying a class to each checkbox indicating the job to which it is assigned -->
				<!-- although this means that there might be values sent to the server that are not necessarily relevant for the selected job, the server should (in practice) be able to ignore them based on which job was selected -->
				<fieldset class="apply-fieldset">
					<legend>Required technical skills:</legend>

					<div class="apply-checkbox-set-container">
						<div class="apply-checkbox-container apply-checkbox-set-support apply-checkbox-set-analyst">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_sql" value="sql" checked>
							<label for="apply-required-technical-skills_sql">Understanding of SQL</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-support">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_python" value="python" checked>
							<label for="apply-required-technical-skills_python">Understanding of Python</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-support">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_networks" value="networks" checked>
							<label for="apply-required-technical-skills_networks">Understanding of networks</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-analyst">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_excel" value="excel" checked>
							<label for="apply-required-technical-skills_excel">Proficiency in Microsoft Excel</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-analyst">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_vistools" value="vistools" checked>
							<label for="apply-required-technical-skills_vistools">Experience with data visualisation
								tools</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-analyst">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_problem_solving" value="problem_solving" checked>
							<label for="apply-required-technical-skills_problem_solving">Strong analytical and
								problem-solving skills</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-analyst">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_bachelor_data" value="bachelor_data" checked>
							<label for="apply-required-technical-skills_bachelor_data">Bachelor's degree in Data
								Science</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-analyst">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_bachelor_stats" value="bachelor_stats" checked>
							<label for="apply-required-technical-skills_bachelor_stats">Bachelor's degree in
								Statistics</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-qa">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_qa_testing" value="qa_testing" checked>
							<label for="apply-required-technical-skills_qa_testing">Proven experience in software
								quality assurance or testing</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-qa">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_qa_methodologies" value="qa_methodologies" checked>
							<label for="apply-required-technical-skills_qa_methodologies">Strong understanding of QA
								methodologies, tools, and processes</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-qa">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_test_cases" value="test_cases" checked>
							<label for="apply-required-technical-skills_test_cases">Ability to write comprehensive test
								plans and test cases</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-qa">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_bug_tracking" value="bug_tracking" checked>
							<label for="apply-required-technical-skills_bug_tracking">Familiarity with bug tracking
								tools</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-qa">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_sdlc" value="sdlc" checked>
							<label for="apply-required-technical-skills_sdlc">Understanding of the Software Development
								Life Cycle</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-blockchain">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_smart_contract" value="smart_contract" checked>
							<label for="apply-required-technical-skills_smart_contract">Proficiency in a smart contract
								language</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-blockchain">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_architecture" value="architecture" checked>
							<label for="apply-required-technical-skills_architecture">Strong understanding of blockchain
								architecture and principles</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-blockchain">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_blockchain_dev_tools" value="blockchain_dev_tools"
								checked>
							<label for="apply-required-technical-skills_blockchain_dev_tools">Familiarity with
								blockchain development tools</label>
						</div>

						<div class="apply-checkbox-container apply-checkbox-set-blockchain">
							<input class="apply-input" type="checkbox" name="required-technical-skills[]"
								id="apply-required-technical-skills_backend" value="backend" checked>
							<label for="apply-required-technical-skills_backend">Proficiency in a backend programming
								language</label>
						</div>
					</div>
				</fieldset>

				<div>
					<label for="apply-other-skills">Other skills:</label>
					<textarea id="apply-other-skills" name="other-skills"
						placeholder="Enter any other skills you'd like to share here..."></textarea>
				</div>
			</fieldset>

			<!-- add the buttons to submit and reset the form -->
			<input type="submit" value="Apply" class="apply-fancy-button">
			<input type="reset" value="Reset" class="apply-fancy-button apply-fancy-button-bad">
		</form>
	</main>

	<!-- include the page footer -->
	<?php include "footer.inc"; ?>
</body>

</html>