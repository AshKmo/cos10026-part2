<?php
// start a session if one is not already active
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// import the database settings
require_once "settings.php";

// connect to the database
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

// complain if the database fails
if (!$conn) {
	echo "Database connection failed: " . mysqli_connect_error();
	exit();
}

// function to check if a string contains JSON-like content
function containsJson($string)
{
	$start = strpos($string, '[');
	if ($start === false)
		return false;
	$jsonPart = substr($string, $start);
	$decoded = json_decode($jsonPart, false);
	return json_last_error() === JSON_ERROR_NONE && is_array($decoded);
}

// function to extract, format and echo a JSON list from a string, alongside the rest of the string
function print_skill($skill)
{
	echo $skill->desc;
	echo "</label>";
	if (isset($skill->children)) {
		echo "<ul>";
		foreach ($skill->children as $subskill) {
			echo "<li>$subskill</li>";
		}
		echo "</ul>";
	}
}
?>

<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part1/apply.php -->

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include_once "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Apply for a position at Tolstra">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, jobs, application">

	<!-- set the page title -->
	<title>Applications</title>
</head>

<body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
		<h1>Apply for a position</h1>

		<p>Tolstra is always committed to providing premium employment opportunities to those who need them the most.
			Please submit your application below, and welcome aboard!</p>

		<?php
		// echo the error message if there is one and then delete it from server-side storage so it doesn't stick there forever
		if (isset($_SESSION["APPLY_FORM_ERROR_MESSAGE"])) {
			echo '<hr><p id="apply-error-message">' . ($_SESSION["APPLY_FORM_ERROR_MESSAGE"]) . '</p>';
		}
		unset($_SESSION["APPLY_FORM_ERROR_MESSAGE"]);
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

				<!-- state/territory selection box -->
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

				<!-- postcode input box -->
				<!-- the server will validate the postcode based on which state the user has selected -->
				<p>
					<label for="apply-postcode">Postcode: </label>
					<!-- pattern created using RegExr's regular expression parser and cheat sheet -->
					<!-- availabe at https://regexr.com/ -->
					<input class="apply-input" type="text" name="postcode" id="apply-postcode" maxlength="4"
						minlength="4" size="4" placeholder="0000" pattern="[0-9]{4}" required>
				</p>

				<p>
					<label for="apply-date-of-birth">Date of birth: </label>
					<!-- pattern created using RegExr's regular expression parser and cheat sheet -->
					<!-- availabe at https://regexr.com/ -->
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

						<?php
						$stmt = $conn->prepare('select * from job_descriptions');

						if (!$stmt->execute()) {
							echo "Database query failed.";
							exit();
						}

						$result = $stmt->get_result();

						while ($job = $result->fetch_assoc()) {
							echo '<option value="' . $job["job_id"] . '">' . $job["position"] . ' (' . $job["job_id"] . ')</option>';
						}
						?>
					</select>
				</p>

				<!-- produce a series of checkboxes for each required technical skill for each job -->
				<!-- the relevant checkboxes for each job are shown only when that job is selected above -->
				<!-- this is done by applying a class to each checkbox indicating the job to which it is assigned -->
				<!-- although this means that there might be values sent to the server that are not necessarily relevant for the selected job, the server will skip them -->
				<fieldset class="apply-fieldset">
					<legend>Required technical skills:</legend>

					<div class="apply-checkbox-set-container">
						<?php
						$stmt = $conn->prepare('select * from job_descriptions');

						if (!$stmt->execute()) {
							echo "Database query failed.";
							exit();
						}

						$result = $stmt->get_result();

						while ($job = $result->fetch_assoc()) {
							$skills = json_decode($job["essential_prereqs"]);

							foreach ($skills as $skill) {
								// this hash is only used to uniquely identify the skill amongst the others so it's ok to use a dodgy algorithm like md5
								$skill_id = hash("md5", $skill->desc);
								echo '
									<div class="apply-checkbox-container apply-checkbox-set-' . $job["job_id"] . '">
										<input class="apply-input" type="checkbox" name="required-technical-skills[]" id="apply-required-technical-skills_' . $skill_id . '" value="' . $skill_id . '" checked>
										<label for="apply-required-technical-skills_' . $skill_id . '">';

								print_skill($skill);

								echo '</div>';
							}
						}
						?>
					</div>
				</fieldset>

				<!-- textarea box for other skills the user may want to share -->
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
	<?php include_once "footer.inc"; ?>
</body>

</html>