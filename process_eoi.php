<?php
require_once("settings.php");

session_start();

function sanitise($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function check_field($condition, $message) {
    if ($condition) {
        return;
    }

    $_SESSION["APPLY_FORM_ERROR_MESSAGE"] = $message;
    header("Location: apply.php");
    exit();
}

function check_postcode($state, $postcode) {
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

$field_values = [];

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

// TODO: maybe update this based on values obtained from the jobs database table
// and rewrite apply.php so that it adds the relevent option tags and skills for each job from the database
$field_name = "job-reference-number";
$value = $_POST[$field_name];
check_field(
    isset($value) && in_array($value, ["IT427", "DA193", "BC279", "QA666"]),
    "Please select a valid job from the options provided."
);
$field_values[$field_name] = sanitise($value);

// TODO: maybe validate the field based on what jobs and skills are available in the jobs database table
$field_name = "required-technical-skills";
$value = $_POST[$field_name];
check_field(
    isset($value),
    "Please select valid technical skills for the job to which you will be applying."
);
$field_values[$field_name] = $value;

$field_name = "other-skills";
$value = $_POST[$field_name];
check_field(
    isset($value),
    "Please provide a value for the Other Skills field."
);
$field_values[$field_name] = sanitise($value);

echo "Wow, you made it!";

// TODO: database logic for adding the record to the table AND creating the table itself if it does not yet exist