
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
	function check_field($condition, $message) {
		if ($condition) {
			return;
		}
		$_SESSION["success"] = false;
		$_SESSION["message"] = $message;
		header("Location: jobs_manage.php");
		exit();
	}

	function regex_main($max_length){
		return '/^[A-Za-z0-9.,!?;:\'"_\-()\[\]{}\/\\\\@#$%^&*+=<>|~`]{1,' . $max_length . '}$/';
	}

	function list_conversion($field_name, $message) {
		$list_primary = [];
		for ($i = 0; $i < 50; $i++) {
			if (isset($_POST[$field_name . "-" . $i]) && $_POST[$field_name . "-" . $i] != "") {
				check_field(
					isset($_POST[$field_name . "-" . $i]) && preg_match(regex_main(300), $_POST[$field_name . "-" . $i]),
					$message . " Failed at ". $field_name . " " . $i + 1
				);
				if (isset($_POST[$field_name . "-" . $i . "-sub"])) {
					$sub = true;
				} else {
					$sub = false;
				}
				$list_primary[] = [$_POST[$field_name . "-" . $i], $sub];
			}
		}

		$last_entry = -1;
		$list_final = [];
		for ($i = 0; $i < count($list_primary); $i++) {
			if ($list_primary[$i][1] == false) {
				$last_entry += 1;
				$list_final[$last_entry] = [];
				$list_final[$last_entry]['desc'] = $list_primary[$i][0];
			} else {
				if (!isset($job_details[$field_name][$last_entry]['children'])) {
					$list_final[$last_entry]['children'] = [];
				}
				$list_final[$last_entry]['children'][] = $list_primary[$i][0];
			}
		}
		return json_encode($list_final);
	}

	$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);

	if (!$dbconn) {
    	echo "Database connection failed: " . mysqli_connect_error();
    	exit();
	}

	if ($_POST['type'] == 'create_job') {

		$query = "SELECT job_id FROM job_descriptions";
		$result = mysqli_query($dbconn, $query);
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
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				check_field($row["job_id"] != $value, 'Job with the same position ID already exists.');
			}
		}
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

		$job_details["expectations"] = list_conversion("expectation", "All expectations must consist of between 1 and 300 English alphabet characters.");
		$job_details["essential_prereqs"] = list_conversion("essential-prereq", "All essential prerequisites must consist of between 1 and 300 English alphabet characters.");
		$job_details["preferable_prereqs"] = list_conversion("preferable-prereq", "All preferable prerequisites must consist of between 1 and 300 English alphabet characters.");

		mysqli_query($dbconn, '
			create table if not exists job_descriptions (
				job_index integer auto_increment primary key,
				position varchar(40),
				job_id varchar(5),
				report_to_title varchar(40),
				report_to_name varchar(50),
				description varchar(600),
				salary_range varchar(30),
				employment_type varchar(30),
				location varchar(70),
				expectations longtext,
				essential_prereqs longtext,
				preferable_prereqs longtext
			)'
		);

		// prepare a query that inserts an entry into the database based on the submitted values
		// this automatically removes the risk of SQL injection by properly escaping each value before adding it to the statement
		$stmt = $dbconn->prepare('insert into job_descriptions values (0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		$stmt->bind_param(
			'sssssssssss',
			$job_details["position"],
			$job_details["job_id"],
			$job_details["report_to_title"],
			$job_details["report_to_name"],
			$job_details["description"],
			$job_details["salary_range"],
			$job_details["employment_type"],
			$job_details["location"],
			$job_details["expectations"],
			$job_details["essential_prereqs"],
			$job_details["preferable_prereqs"],
		);
		if (!$stmt->execute()) {
			$_SESSION["success"] = false;
    		$_SESSION["message"] = "Database query failed.";
		} else {
			$_SESSION["success"] = true;
    		$_SESSION["message"] = "Job successfully added.";
		}

	} elseif ($_POST['type'] == 'delete_job') {

		$delete_id = sanitise($_POST['job_id']);
        $query = "DELETE FROM `job_descriptions` WHERE `job_id` = '" . $delete_id . "'";
		if ($dbconn -> query($query) === TRUE) {
			$_SESSION["success"] = true;
			$_SESSION["message"] = "Job deleted.";
		} else {
			$_SESSION["success"] = false;
			$_SESSION["message"] = "Unable to delete job.";
		}
	}
	header('Location: jobs_manage.php');
	exit();
?>