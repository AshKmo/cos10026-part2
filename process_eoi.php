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

$field_name = "first-name";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[a-zA-Z]{1,20}$/', $value),
    "First name must consist of between 1 and 20 English alphabet characters."
);
$field_values[$field_name] = sanitise($value);

$field_name = "last-name";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[a-zA-Z]{1,20}$/', $value),
    "Last name must consist of between 1 and 20 English alphabet characters."
);
$field_values[$field_name] = sanitise($value);

$field_name = "email";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^\w+@([\w\-]+\.)+[\w\-]{2,}$/', $value),
    "Email field must contain a valid email address."
);
$field_values[$field_name] = sanitise($value);

$field_name = "phone";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[0-9 ]{8,12}$/', $value),
    "Phone number must consist of 8-12 digits or spaces."
);
$field_values[$field_name] = sanitise($value);

$field_name = "address";
$value = $_POST[$field_name];
check_field(
    isset($value) && strlen($value) > 0 && strlen($value) <= 40,
    "Street address must be between 1 and 40 characters in length."
);
$field_values[$field_name] = sanitise($value);

$field_name = "town";
$value = $_POST[$field_name];
check_field(
    isset($value) && strlen($value) > 0 && strlen($value) <= 40,
    "Suburb/town name must be between 1 and 40 characters in length."
);
$field_values[$field_name] = sanitise($value);

$field_name = "state";
$value = $_POST[$field_name];
check_field(
    isset($value) && in_array($value, ["VIC", "NSW", "TAS", "NT", "SA", "WA", "ACT", "QLD"]),
    "You must select a valid Australian state or territory of residence."
);
$field_values[$field_name] = sanitise($value);

$field_name = "postcode";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[0-9]{4}$/', $value) && check_postcode($field_values["state"], $value),
    "Postcode must be a valid 4-digit postcode and must be valid for the selected state/territory."
);
$field_values[$field_name] = sanitise($value);

$field_name = "date-of-birth";
$value = $_POST[$field_name];
check_field(
    isset($value) && preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/', $value),
    "Date of birth must be in dd/mm/yyyy format."
);
$field_values[$field_name] = sanitise($value);

$field_name = "gender";
$value = $_POST[$field_name];
check_field(
    isset($value) && in_array($value, ["male", "female", "other", "unspecified"]),
    "Please select a gender from the options provided."
);
$field_values[$field_name] = sanitise($value);

$field_name = "specific-gender";
$value = $_POST[$field_name];
check_field(
    isset($value) && !($field_values["gender"] === "other" && strlen(sanitise($value)) === 0),
    "Please provide a gender description."
);
$field_values[$field_name] = sanitise($value);

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
    $final_array = [];

    $required_skills = json_decode($job[10]);

    foreach ($required_skills as $skill) {
        // compare the submitted skill IDs to the calculated IDs of the real skills themselves
        if (in_array(hash("md5", $skill), $skills)) {
            array_push($final_array, $skill);
        }
    }

    return $final_array;
}

$field_name = "job-reference-number";
$value = $_POST[$field_name];

$job = get_job_from_id($jobs, $value);

check_field(
    isset($value) && $job,
    "Please select a valid job from the options provided."
);
$field_values[$field_name] = sanitise($value);

$field_name = "required-technical-skills";
$value = $_POST[$field_name];
check_field(
    isset($value),
    "Please select valid technical skills for the job to which you will be applying."
);
$field_values[$field_name] = json_encode(extract_relevant_skills($job, $value));

$field_name = "other-skills";
$value = $_POST[$field_name];
check_field(
    isset($value),
    "Please provide a value for the Other Skills field."
);
$field_values[$field_name] = sanitise($value);

// create the EOI table if it does not yet exist
mysqli_query($conn, '
    create table if not exists eoi (
        EOInumber integer auto_increment primary key,
        jobReferenceNumber varchar(5),
        firstName varchar(20),
        lastName varchar(20),
        streetAddress varchar(40),
        town varchar(40),
        state enum("VIC", "NSW", "ACT", "NT", "SA", "WA", "TAS", "QLD"),
        postcode varchar(4),
        email text,
        phone varchar(12),
        requiredTechnicalSkills text,
        otherSkills text,
        status enum("new", "current", "final") default "new"
    )
');

// prepare a query that inserts an entry into the database based on the submitted values
// this automatically removes the risk of SQL injection by properly escaping each value before adding it to the statement
$stmt = $conn->prepare('insert into eoi values (0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "new")');
$stmt->bind_param(
    'sssssssssss',
    $field_values["job-reference-number"],
    $field_values["first-name"],
    $field_values["last-name"],
    $field_values["address"],
    $field_values["town"],
    $field_values["state"],
    $field_values["postcode"],
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

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">

    <!-- include common meta tags -->
    <?php include("meta.inc") ?>

    <title>Thanks for applying</title>
</head>

<body>
    <main>
        <h1>Application submitted</h1>
        <p>Thank you for your application. It has been stored in the database with ID <?php echo $conn->insert_id ?>.
            <a href="index.php">Please click here to return to the home page</a>.
        </p>
    </main>
</body>

</html>