<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part1/jobs.php -->

<!-- the page language is set to English -->

<?php
	session_start();
	function recursive_ol($list_items) {
		// takes an array of items and if any of them have subitems, add a subitem into the HTML for it
		echo "<ol>";
		foreach ($list_items as $item) {
			echo "<li>";
			// check if there are any subitems
			if (isset($item->children)) {
				// if so, add all subitems in a new list
				echo $item->desc;
				echo "<ol>";
				foreach ($item->children as $subitem) {
					echo "<li>" . $subitem ."</li>";
            	}
				echo "</ol>";
        	} else {
            	echo $item->desc;
       		}
			echo "</li>";
		}
		echo "</ol>";
	}

	function recursive_ul($list_items) {
		// same as recursive_ol but for unordered lists (probably could've just used one function for both and have the list type be another entry field but ah well)
		echo "<ul>";
		foreach ($list_items as $item) {
			echo "<li>";
			if (isset($item->children)) {
				echo $item->desc;
				echo "<ul>";
				foreach ($item->children as $subitem) {
					echo "<li>" . $subitem ."</li>";
            	}
				echo "</ul>";
        	} else {
            	echo $item->desc;
       		}
			echo "</li>";
		}
		echo "</ul>";
	}
?>
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include_once "meta.inc"; ?>

	<!-- Set page description -->
	<meta name="description" content="Available job positions at Tolstra">

	<!-- Set keywords -->
	<meta name="keywords" content="Tolstra, jobs, positions">

	<!-- Set the page title -->
	<title>Job Positions</title>
</head>

<body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
		<!-- Form main title -->
		<h1 id="jobs-title">Available Jobs</h1>
		<!-- Anchor tag sends user to the application page -->
		<p>Thank you for considering a position at Tolstra. <a title="Applications" href="apply.php#">Visit the
				application form</a> to apply for any listed position.</p>
		<hr>

			<?php
				// connect to the database
				require_once "settings.php";
				$dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);
				if ($dbconn) {
					// select all jobs from the database
					$query = "SELECT * FROM job_descriptions";
					$result = mysqli_query($dbconn, $query);
					// make an entry into the jobs table for each job in the database
					if ($result) {
						while ($row = mysqli_fetch_assoc($result)) {
							// display the details for each job in the correct notation
							$id = strtolower(str_replace(" ", "-", $row["job_id"]));
							echo "<section class=\"jobs-dropdown\">";
							echo "<input type=\"checkbox\" id=\"jobs-" . $id . "-dropdown\">";
							echo "<h2 class=\"jobs-dropdown-title\"><label for=\"jobs-" . $id . "-dropdown\"><em class=\"arrow\"></em><strong> " . $row['position'] . "</strong></label></h2>";
							echo "<section class=\"jobs-dropdown-content\">";

							echo "<aside class=\"jobs-aside\">";
							echo "<strong>Job ID: </strong>" . $row["job_id"] . "<br><br>";
							echo "<strong>You will report to:</strong><br>";
							echo $row["report_to_title"];
							echo " (current is " . $row["report_to_name"] . ")";
							echo "</aside>";

							echo "<p class=\"jobs-description\">" . $row["description"] . "</p>";

							echo "<h3>You will be expected to:</h3>";

							// decode the json data from the database into arrays to make it usable in recursive_ol
							$expectations = json_decode($row["expectations"]);
							recursive_ol($expectations);

							echo "<h3 class=\"jobs-prereqs-title\">Prerequisites:</h3>";
							echo "<section class=\"jobs-prerequisites\">";

							echo "<div>";
							echo "<h4>Essential</h4>";
							$essential_prereqs = json_decode($row["essential_prereqs"]);
							recursive_ul($essential_prereqs);
							echo "</div>";

							echo "<div>";
							echo "<h4>Preferable</h4>";
							$preferable_prereqs = json_decode($row["preferable_prereqs"]);
							recursive_ul($preferable_prereqs);
							echo "</div>";

							echo "</section>";

							echo "<section class=\"jobs-extra-details\">";
							echo "<div>";
							echo "<h3>Expected Salary Range:</h3>";
							echo "<p>" . $row["salary_range"] . "</p>";
							echo "</div>";
							echo "<div>";
							echo "<h3>Employment Type:</h3>";
							echo "<p>" . $row["employment_type"] . "</p>";
							echo "</div>";
							echo "<div>";
							echo "<h3>Location:</h3>";
							echo "<p>" . $row["location"] . "</p>";
							echo "</div>";
							echo "</section>";

							echo "</section>";
							echo "</section>";
						}
					} else {
						echo "<p>There are currently no available job positions.</p>";
					}
				} else {
					echo "<p>Connection to our network failed! Please try again later.</p>";
				}
			?>
	</main>

	<!-- include the page footer -->
	<?php include_once "footer.inc"; ?>
</body>

</html>