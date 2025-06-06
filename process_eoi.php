<?php
// start a session if one is not already active
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// import the database settings
require_once "settings.php";

// function to sanitise user input so that it's safe to store in the database and echo
function sanitise($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// function to check if a field has been submitted correctly and print an error message if it isn't
function check_field($condition, $message)
{
    if ($condition) {
        return;
    }

    $_SESSION["APPLY_FORM_ERROR_MESSAGE"] = $message;
    header("Location: apply.php");
    exit();
}

// function to validate postcodes based on a selected state
function check_postcode($state, $postcode)
{
    // postcode ranges taken from Wikipedia
    // https://en.wikipedia.org/wiki/Postcodes_in_Australia#Allocation
    return match ($state) {
        "NSW" => $postcode >= 1000 && $postcode <= 2599 || $postcode >= 2619 && $postcode <= 2899 || $postcode >= 2921 && $postcode <= 2999,
        "ACT" => $postcode >= 200 && $postcode <= 299 || $postcode >= 2600 && $postcode <= 2618 || $postcode >= 2900 && $postcode <= 2920,
        "VIC" => $postcode >= 3000 && $postcode <= 3996 || $postcode >= 8000 && $postcode <= 8999,
        "QLD" => $postcode >= 4000 && $postcode <= 4999 || $postcode >= 9000 && $postcode <= 9999,
        "SA" => $postcode >= 5000 && $postcode <= 5799 || $postcode >= 5800 && $postcode <= 5999,
        "WA" => $postcode >= 6000 && $postcode <= 6797 || $postcode >= 6800 && $postcode <= 6999,
        "TAS" => $postcode >= 7000 && $postcode <= 7799 || $postcode >= 7800 && $postcode <= 7999,
        "NT" => $postcode >= 800 && $postcode <= 899 || $postcode >= 900 && $postcode <= 999,
        default => false,
    };
}

// reject any requests that are not POST requests and print an error message to browser users
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo '<p>This page intended for internal processing only. <a href="index.php">Please click here to return to the home page</a>.</p>';
    exit();
}

// connect to the database
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

// complain if the database fails
if (!$conn) {
    echo "Database connection failed: " . mysqli_connect_error();
    exit();
}

// array to store the field values we accumulate from reading the POST body
$field_values = [];

// the following code checks the validity of each submitted field and either stores the valid values in $field_values or sets an error messasge for one that isn't

// names must be 1-20 English alphabet characters
// RegEx pattern created using RegExr's regular expression parser and cheat sheet availabe at https://regexr.com
$field_name = "first-name";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[a-zA-Z]{1,20}$/', $value),
    "First name must consist of between 1 and 20 English alphabet characters."
);
$field_values[$field_name] = sanitise($value);

// names must be 1-20 English alphabet characters
// RegEx pattern created using RegExr's regular expression parser and cheat sheet availabe at https://regexr.com
$field_name = "last-name";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[a-zA-Z]{1,20}$/', $value),
    "Last name must consist of between 1 and 20 English alphabet characters."
);
$field_values[$field_name] = sanitise($value);

// email addresses must start with a username, then an '@', then at least two parts of a domain name separated by a '.'. Finally, the TLD must be at least two letters long
// RegEx pattern created using RegExr's regular expression parser and cheat sheet availabe at https://regexr.com
$field_name = "email";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[\w.]+@([\w\-]+\.)+[\w\-]{2,}$/', $value),
    "Email field must contain a valid email address."
);
$field_values[$field_name] = sanitise($value);

// phone numbers must consist of 8-12 (inclusive) characters, each of which can either be a decimal digit or a space
// RegEx pattern created using RegExr's regular expression parser and cheat sheet availabe at https://regexr.com
$field_name = "phone";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[0-9 ]{8,12}$/', $value),
    "Phone number must consist of 8-12 digits or spaces."
);
$field_values[$field_name] = sanitise($value);

// address components must be between 1-40 characters in length (inclusive)
$field_name = "address";
$value = $_POST[$field_name];
check_field(
    isset($value) && strlen($value) > 0 && strlen($value) <= 40,
    "Street address must be between 1 and 40 characters in length."
);
$field_values[$field_name] = sanitise($value);

// address components must be between 1-40 characters in length (inclusive)
$field_name = "town";
$value = $_POST[$field_name];
check_field(
    isset($value) && strlen($value) > 0 && strlen($value) <= 40,
    "Suburb/town name must be between 1 and 40 characters in length."
);
$field_values[$field_name] = sanitise($value);

// the selected state value must be a valid Australian state code
$field_name = "state";
$value = $_POST[$field_name];
check_field(
    isset($value) && in_array($value, ["VIC", "NSW", "TAS", "NT", "SA", "WA", "ACT", "QLD"]),
    "You must select a valid Australian state or territory of residence."
);
$field_values[$field_name] = sanitise($value);

// postcodes consist of exactly 4 decimal digits and must match the selected state
$field_name = "postcode";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[0-9]{4}$/', $value) && check_postcode($field_values["state"], $value),
    "Postcode must be a valid 4-digit postcode and must be valid for the selected state/territory."
);
$field_values[$field_name] = sanitise($value);

// function to convert an Australian date into the ISO 8601 format
function aus_to_iso_date($date_string) {
    $date = DateTime::createFromFormat("d/m/Y", $date_string);

    // if the date is in the present or the future, return false so that the validation check fails
    if ((new DateTime())->getTimestamp() - $date->getTimestamp() <= 0) {
        return false;
    }

    // if any errors occur when processing the date, return false so that the validation check fails
    $errors = DateTime::getLastErrors();
    if ($errors !== false) {
        return false;
    }

    return $date->format("Y-m-d");
}

// dates of birth must be formatted as DD/MM/YYYY and must be a valid date
$field_name = "date-of-birth";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/', $value) && ($value = aus_to_iso_date($value)),
    "Date of birth must be a valid date in the past, written in dd/mm/yyyy format."
);
$field_values[$field_name] = sanitise($value);

// gender value must be one of the options given in the form
$field_name = "gender";
$value = $_POST[$field_name];
check_field(
    isset($value) && in_array($value, ["male", "female", "other", "unspecified"]),
    "Please select a gender from the options provided."
);
$field_values[$field_name] = sanitise($value);

// if "other" is selected, a valid gender description must be provided
$field_name = "specific-gender";
$value = $_POST[$field_name];
check_field(
    isset($value) && !($field_values["gender"] === "other" && strlen(sanitise($value)) === 0),
    "Please provide a gender description."
);

// update the "gender" field to the description provided if one was expected
if ($field_values["gender"] === "other") {
    $field_values["gender"] = sanitise($value);
}

// retrieve the complete set of job information from the job descriptions table
$jobs = mysqli_query($conn, "select * from job_descriptions");
if (!$jobs) {
    echo 'Job descriptions query failed.';
    exit();
}
$jobs = $jobs->fetch_all();

// function to retrieve a job record by its job ID
function get_job_from_id($jobs, $id)
{
    foreach ($jobs as $job) {
        if ($job[2] === $id) {
            return $job;
        }
    }

    return false;
}

// function to filter submitted technical skills based on which ones are available for the selected job
function extract_relevant_skills($job, $skills)
{
    // make a new array for the skills that are applicable
    $final_array = [];

    // convert the skills list from JSON into an iterable Array object
    $required_skills = json_decode($job[10]);

    foreach ($required_skills as $skill) {
        // compare the submitted skill IDs to the calculated IDs of the real skills themselves
        // even though md5 is somewhat insecure, that doesn't matter because we're only using it for unique identification of data that is already public
        // plus it generates hashes that are much smaller than sha256
        if (in_array(hash("md5", $skill->desc), $skills)) {
            array_push($final_array, $skill);
        }
    }

    return $final_array;
}

// determine the information associated with the selected job
$field_name = "job-reference-number";
$value = $_POST[$field_name];
$job = get_job_from_id($jobs, $value);

// if no valid job is found during the above query, the job ID value is invalid
check_field(
    isset($value) && $job,
    "Please select a valid job from the options provided."
);
$field_values[$field_name] = sanitise($value);

// the skills value must be an array of skills IDs, which will be filtered so that only the ones applicable for the selected job will be stored as part of the EOI
$field_name = "required-technical-skills";
$value = $_POST[$field_name];
check_field(
    isset($value) && is_array($value),
    "Please select valid technical skills for the job to which you will be applying."
);
$field_values[$field_name] = json_encode(extract_relevant_skills($job, $value));

// the "Other skills" field must be specified but does not have to contain any text
$field_name = "other-skills";
$value = $_POST[$field_name];
check_field(
    isset($value),
    "Please provide a value for the Other Skills field."
);
$field_values[$field_name] = sanitise($value);

// create the EOI table if it does not yet exist
mysqli_query($conn, "
    CREATE TABLE IF NOT EXISTS `eoi` (
        `EOInumber` int(11) NOT NULL,
        `jobReferenceNumber` varchar(5) DEFAULT NULL,
        `firstName` varchar(20) DEFAULT NULL,
        `lastName` varchar(20) DEFAULT NULL,
        `streetAddress` varchar(40) DEFAULT NULL,
        `town` varchar(40) DEFAULT NULL,
        `state` enum('VIC','NSW','ACT','NT','SA','WA','TAS','QLD') DEFAULT NULL,
        `postcode` varchar(4) DEFAULT NULL,
        `dateOfBirth` date DEFAULT NULL,
        `gender` text DEFAULT NULL,
        `email` text DEFAULT NULL,
        `phone` varchar(12) DEFAULT NULL,
        `requiredTechnicalSkills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
        `otherSkills` text DEFAULT NULL,
        `status` enum('new','current','final') DEFAULT 'new'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
");

// prepare a query that inserts an entry into the database based on the submitted values
// this automatically removes the risk of SQL injection by properly escaping each value before adding it to the statement
$stmt = $conn->prepare('insert into eoi values (0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "new")');
$stmt->bind_param(
    'sssssssssssss',
    $field_values["job-reference-number"],
    $field_values["first-name"],
    $field_values["last-name"],
    $field_values["address"],
    $field_values["town"],
    $field_values["state"],
    $field_values["postcode"],
    $field_values["date-of-birth"],
    $field_values["gender"],
    $field_values["email"],
    $field_values["phone"],
    $field_values["required-technical-skills"],
    $field_values["other-skills"],
);

// execute the statement and print an error message if it fails for some reason
if (!$stmt->execute()) {
    echo "Database query failed.";
    exit();
}
?>

<!-- print a lovely message for those lucky enough to avoid all the validation errors -->

<!DOCTYPE html>

<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include_once "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="About Tolstra">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, telecommunications, Internet, phone, about">

	<!-- set the page title -->
	<title>About Tolstra</title>
</head>

<body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
        <h1>Application submitted</h1>

        <p>Thank you for your application. It has been stored in the database with ID <?php echo $stmt->insert_id; ?>. You may now <a href="index.php">return to the home page</a>.</p>
	</main>

	<!-- include the page footer -->
	<?php include_once "footer.inc"; ?>
</body>

</html>