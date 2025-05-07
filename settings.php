<?php
$host = "localhost";
$user = "root";
$pwd = "";
$sql_db = "tolstra";

$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$dbconn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>