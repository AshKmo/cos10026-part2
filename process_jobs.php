
<?php
	session_start();

	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		header("Location: index.php");
		exit();
	}
	require_once("settings.php");

	// function created by Ash
	function sanitise($data)
	{
		return htmlspecialchars(stripslashes(trim($data)));
	}

	// function (created by Ash) to check if a field has been submitted correctly and print an error message if it isn't
	function check_field($condition, $message)
	{
		if ($condition) {
			return;
		}

		$_SESSION["JOBS_MANAGE_FORM_ERROR_MESSAGE"] = $message;
		header("Location: apply.php");
		exit();
	}

	function regex_main($max_length){
		return '/^[A-Za-z0-9.,!?;:\'"_\-()\[\]{}\/\\\\@#$%^&*+=<>|~`]{1,' . $max_length . '}$/';
	}

	$conn = mysqli_connect($host, $user, $pwd, $sql_db);

	if (!$conn) {
    	echo "Database connection failed: " . mysqli_connect_error();
    	exit();
	}

	$job_details = [];

	// validation structure made by Ash
	$field_name = "position";
	$value = $_POST[$field_name];
	check_field(
		isset($value) && preg_match(regex_main(40), $value),
		"Position name must consist of between 1 and 40 English alphabet characters."
	);
	$job_details[$field_name] = sanitise($value);

	// regex expression made by ChatGPT. Prompt: "i need a regex expression for exactly 5 characters that must be 2 upper or lowercase English letters followed by 3 numbers. can u help?"
	$field_name = "job_id";
	$value = $_POST[$field_name];
	check_field(
		isset($value) && preg_match('/^[A-Za-z]{2}[0-9]{3}$/', $value),
		"Job ID must consist of exactly 2 English alphabet characters followed by 3 English numbers."
	);
	$job_details[$field_name] = sanitise($value);

	$field_name = "report_to_title";
	$value = $_POST[$field_name];
	check_field(
		isset($value) && preg_match(regex_main(40), $value),
		"Title of Superior must consist of between 1 and 40 English alphabet characters."
	);
	$job_details[$field_name] = sanitise($value);

	$field_name = "report_to_name";
	$value = $_POST[$field_name];
	check_field(
		isset($value) && preg_match(regex_main(50), $value),
		"Name of Superior must consist of between 1 and 50 English alphabet characters."
	);
	$job_details[$field_name] = sanitise($value);

	$field_name = "description";
	$value = $_POST[$field_name];
	check_field(
		isset($value) && preg_match(regex_main(600), $value),
		"Description must consist of between 1 and 600 English alphabet characters."
	);
	$job_details[$field_name] = sanitise($value);

	$field_name = "salary_range";
	$value = $_POST[$field_name];
	check_field(
		isset($value) && preg_match(regex_main(30), $value),
		"Salary Range must consist of between 1 and 30 English alphabet characters."
	);
	$job_details[$field_name] = sanitise($value);

	$field_name = "employment_type";
	$value = $_POST[$field_name];
	check_field(
		isset($value) && preg_match(regex_main(30), $value),
		"Employment Type must consist of between 1 and 30 English alphabet characters."
	);
	$job_details[$field_name] = sanitise($value);

	$field_name = "location";
	$value = $_POST[$field_name];
	check_field(
		isset($value) && preg_match(regex_main(70), $value),
		"Location must consist of between 1 and 70 English alphabet characters."
	);
	$job_details[$field_name] = sanitise($value);

	$field_name = "expectation";
	$expectations = [];
	for ($i = 0; $i < 50; $i++) {
		if (isset($_POST[$field_name . "-" . $i])) {
			check_field(
				isset($value) && preg_match(regex_main(500), $value),
				"Location must consist of between 1 and 500 English alphabet characters."
			);
			$expectations [] = [$_POST[$field_name . "-" . $i], $_POST[$field_name . "-" . $i . "-sub"]] ;
		}
	}
?>