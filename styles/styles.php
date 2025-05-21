<?php
header("Content-Type: text/css");

require_once "../settings.php";

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    echo "Database connection failed: " . mysqli_connect_error();
    exit();
}
?>

/*
filename: styles.css
author: Ash, Hayden, Tim, Max
created: 2025-03-28
last modified: 2025-04-14
description: Stylesheet for all pages
 */

/*************************************/
/* non page-specific styles (global) */
/*************************************/

:root {
	--nav-footer-background-color: #333333;
}

* {
	font-family: Arial, sans-serif;
}

h1, h2, h3, h4, h5, h6 {
	color: rgb(9, 121, 159);
}

body {
	background-image: linear-gradient(to bottom, #efefef, #e3ebee);
	background-size: cover;
	background-attachment: fixed;
	margin: 0;
	padding: 0;
	font-size: 16px;
	color: #333;
}

/* navigation menu */
nav {
	height: 20px;
	display: flex;
	align-items: center;
	background: var(--nav-footer-background-color);
	padding: 10px;
	margin: 20px 0 20px 0;
	max-width: 1100px;
	border-radius: 0px 10px 10px 0px;
	position: relative;
	overflow: visible;
	box-shadow: 4px 4px 3px #bcbcbc;
	font-weight: bold;
}
.nav-logo {
	position: absolute;
	top: -28px;
	left: 10px;
	height: 100px;
	z-index: 10; 
}
nav ul {
	list-style: none;
	display: flex;
	gap: 30px;
	flex-wrap: wrap;
	justify-content: center;
	flex-grow: 1;
}
nav ul li {
	display: inline;
	transform: scale(1, 1);
	transition: transform 0.15s ease;
}

nav ul li:hover {
	transform: scale(1.05, 1.05);
}

nav ul li a {
	color: white;
	text-decoration: none;
	font-size: 18px;
	padding: 10px;
	transition: color 0.25s ease;
}
nav ul li a:hover {
	color: #00BFFF;
}

main {
	padding: 20px;
	width: 80%;
	min-height: 1000px;
	margin: auto;
	padding-left: 50px;
	padding-right: 50px;
	background: white;
	box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
	border-radius: 5px 5px 0 0;
	overflow: hidden;
}

section {
	margin-bottom: 20px;
}

table {
	border-collapse: collapse;
	border: 1px solid #ccc;
}
th {
	background: var(--nav-footer-background-color);
	color: white;
	text-align: center;
	font-weight: bold;
	padding: 10px;
	border: 1px solid #ccc;
}
td {
	background-color: #efefef;
	border: 1px solid #ccc;
}
table:not(.manage_table) td:hover {
	background-image: linear-gradient(to bottom right, #efefef, #e3ebee);
}

footer {
	padding: 20px;
	width: 80%;
	margin: auto;
	padding-left: 50px;
	padding-right: 50px;
	background: var(--nav-footer-background-color);
	border-radius: 0 0 5px 5px;
	color: white;
	text-align: center;
}

footer a {
	color: #0fc3ff;
}

/* add a little icon for external links to indicate that they are, in fact, external */
a[target="_blank"]::after {
	content: "";
	display: inline-block;
	background-image: url("images/external-link.svg");
	background-size: contain;
	width: 12px;
	height: 12px;
	margin-left: 2px;
}

dl {
	padding: 15px;
	border-radius: 5px;
}

/******************************/
/* index.php specific styles */
/******************************/

/* Make the benefits have a banner look */
#index-benefits {
	border-radius: 15px;
	width: 100%;
	background-color: #333;
	display: flex;
	justify-content: space-around;
	/* The code or content below was created using GenAI. GenAI prompt: "create a repeating pattern with #333333 as the background color, with a waves of rgb(9, 121, 159)" */
	background-image: url("images/banner-pattern.png");
	background-repeat: repeat-x;
	background-size: 30%;
}

#index-benefits figure {
	display: flex;
	flex-direction: column;
	align-items: center;
}

#index-benefits figure img {
	max-width: 100px;
	border-radius: 25px;
}

#index-benefits figure figcaption {
	color: white;
	text-align: center;
}

/* Table showing pricing, using Tim's css */
#index-pricing {
	max-width: 45%;
	float: left;
	padding-right: 100px;
	width: 100%;
	flex: 1;
}

/* Have the vision description float to the right on bigger screens */
#index-vision {
	float: right;
	max-width: 45%;
}

/*****************************/
/* jobs.php specific styles */
/*       Author: Max         */
/*****************************/

/* Setting the font of the jobs main title, as required */
#jobs-title {
	font: bold 2em verdana, sans-serif;
}

/* Setting correct colour for subtitles within the job details */
.jobs-dropdown-content h3, .jobs-dropdown-content h4, .jobs-dropdown-content strong {
	color: #09799f;
}

/* Job ID - sets it to the right of the screen, with a width of 25% of that job's container size */
.jobs-aside {
	text-align: right;
	padding: 3%;
	width: 25%;
	float: right;
	/*I really hate this border but it's required for the CSS :( */
	border: 2px solid #d4dee2;
	padding: 10px;
	border-radius: 10px;
	margin-right: 10px;
}

/* Puts a border around the bottom-right corner what would otherwise be empty space and rotates it 45 deg to create an arrow */
.arrow {
	border: solid black;
	border-width: 0 4px 4px 0;
	display: inline-block;
	/* Padding gives the arrow actual size instead of being a dot due to it not containing any text */
	padding: 0.25em;
	margin: 0 10px;
	transform: rotate(-45deg);
	transition: transform 0.4s ease;
}

/* Set appropriate formating and styling for the job dropdowns */
.jobs-dropdown {
	width: 100%;
	border: 1px solid #ccc;
	border-radius: 5px;
	overflow: hidden;
	background-color: #f4f8fa;
	margin: 20px 0px;
}

/* When the title is clicked on, the arrow will rotate 90 deg down (starts at -45 deg) to point down */
.jobs-dropdown input:checked ~ .jobs-dropdown-title .arrow {
	transform: rotate(45deg);
}

/* The job titles get slightly bigger and become more blue when hovered over */
.jobs-dropdown-title:hover {
	color: rgb(51, 160, 255);
	font-size: 1.6em;
}

/* Removes the checkbox from appearing next to the job title (required to look nice due to the textbook being a checkbox input in order to have the expanding description) */
.jobs-dropdown input {
	display: none;
}

/* Add formatting to each job titles. Bottom padding is not needed as it is already added from somewhere which I cannot for the life of me find */
.jobs-dropdown-title {
	font-size: 1.5em;
	padding-top: 20px;
	padding-left: 15px;
	display: block;
	cursor: pointer;
	color: rgb(9, 121, 159);
	transition: color 0.2s ease, font-size 0.2s ease;
	margin: 0;
	padding: 0;
}

.jobs-dropdown-title label {
	display: inline-block;
	padding-left: 15px;
	padding-top: 20px;
	width: 100%;
	cursor: pointer;
}

/* When the input (the job title) is clicked on, max height is set to an arbitrarily high value to fit all the text (auto doesnt work :( ) */
.jobs-dropdown input:checked ~ .jobs-dropdown-content {
	max-height: 2000px;
	/* fade out, then shrink */    
}

/* Base formatting for the unexpanded job details. Max height set to 0 to get the 'unexpanded' effect */
.jobs-dropdown-content {
	max-height: 0px;
	overflow: hidden;
	transition: max-height 0.4s ease;
	padding: 0px 20px;
	margin-left: 2%;
}

/* Removes the space that would otherwise be below the 'Prerequisites:' subtitle */
.jobs-prereqs-title {
	margin-bottom: 0px;
}

/* Restricts the width of the job description (only particularly relevant when using a shrunk screen) */
.jobs-description {
	width: 70%;
}

/* Sets the style of the 'container' for the essential and preferable prerequisites; gives them flex style to sit side by side */
.jobs-prerequisites {
	width: 80%;
	display: flex;
	justify-content: center;
	gap: 20%;
	padding-top: 0px;
	margin-top: 0px;
    margin-bottom: 0;
}

/* Not entirely sure why this works but after some experimentation I found that if you set the width below 30% or use auto then everything looks bad so... */
.jobs-prerequisites div {
	width: 40%;
}

.jobs-extra-details {
	display: flex;
	justify-content: space-between;
	margin: 0px;
}

.jobs-extra-details div {
	width: 100%;
}

/* Thanks to W3Schools (link: https://www.w3schools.com/howto/howto_css_arrows.asp) for teaching extra CSS, including how to make arrows */

/******************************/
/* apply.php specific styles */
/******************************/
/* make each fieldset have a rounded, solid border */
.apply-fieldset {
	border-radius: 10px;
	border: solid #B0B0B0 2px;
}

/* add a box shadow to all the top-level fieldsets */
.apply-form > .apply-fieldset {
	box-shadow: 8px 8px rgb(200, 200, 200);
}

/* make the fieldset legends more obvious */
.apply-fieldset legend {
	font-weight: bold;
}

/* make the legends of the top-level fieldsets slightly larger in font size */
.apply-form > .apply-fieldset > legend {
	font-size: 24px;
}

/* add a bit of spacing between the fieldsets */
.apply-fieldset:not(:last-child) {
	margin-bottom: 20px;
}

/* remove the ability to select the text on a label and increase its font size slightly */
.apply-form label, .apply-form p label {
	user-select: none;
	font-size: 110%;
}

/* bump the font size on all major form elements for easy readability */
.apply-form label, .apply-form input, .apply-form legend, .apply-form select {
	font-size: 20px;
}

/* make text inputs have only a grey bottom border */
.apply-input[type="text"] {
	outline: none;
	border: none;
	border-bottom: solid #404040 2px;
}

/* make text inputs have a blue bottom border when they are selected */
.apply-input[type="text"]:focus {
	border-bottom: solid #0073e6 2px;
}

/* initially hide all the checkboxes but ensure that they consume half the available width when displayed */
.apply-checkbox-container {
	display: none;
	flex-basis: 50%;
}

/* remove the padding from the bottom of the "Employment details" section so that there isn't a massive gap betweeen the end of the textarea and the end of the fieldset */
#apply-employment-details fieldset {
	padding-bottom: 0;
}

/* hide everything under the "Employment details" section except for the select box, but only if an option is not chosen */
#apply-employment-details:has(option[value=""]:checked) :is(fieldset, div) {
	display: none;
}

<?php
$stmt = $conn->prepare('select * from job_descriptions');

if (!$stmt->execute()) {
	echo "Database query failed.";
	exit();
}

$result = $stmt->get_result();

while ($job = $result->fetch_assoc()) {
	echo '
		#apply-employment-details:has(option[value="'.$job["job_id"].'"]:checked) .apply-checkbox-set-'.$job["job_id"].' {
			display: block;
		}
	';
}
?>

/* add some padding to the checkbox set container and wrap the contents into a nice set of columns */
.apply-checkbox-set-container {
	display: flex;
	flex-wrap: wrap;
	padding: 5px 5px 0px 5px;
}

/* make the checkboxes more spaced out */
.apply-checkbox-container {
	margin-bottom: 20px;
}

/* establish the default button styles with rounded corners, comfortable padding, an easily-readable font size and an appropriate cursor icon when hovered over */
.apply-fancy-button {
	background-color: #0073e6;
	color: white;
	border: none;
	border-radius: 10px;
	padding: 10px 20px 10px 20px;
	font-size: 24px;
	cursor: pointer;
	margin-right: 15px;
}

/* make the outline on the buttons more visible for keyboard users when they are focused */
.apply-fancy-button:focus {
	outline-offset: 4px;
}

/* make the buttons slightly darker when hovered over */
.apply-fancy-button:hover {
	background-color: #003B73;
}

/* set a different colour for buttons with a negative or destructive meaning */
.apply-fancy-button-bad {
	background-color: #FF0019;
}

/* make the buttons slightly darker when hovered over */
.apply-fancy-button-bad:hover {
	background-color: #88000C;
}

#apply-other-skills {
	/* add a darker rounded border to the textarea box, make it fill the width of the fieldset, and add inner padding so that the text nicely fits inside */
	width: 100%;
	height: 150px;
	box-sizing: border-box;
	border: solid #606060 2px;
	border-radius: 10px;
	padding: 10px;
	font-size: 16px;

	/* disable changes to the width of the textarea so that it doesn't collide with the edges of the main element */
	resize: vertical;
}

/* line up the text input fields */
#apply-personal-text-fields-container label {
	display: inline-block;
	width: 150px;
}

/* make all the text input fields the same width on desktop */
#apply-personal-text-fields-container input {
	width: 400px;
}

#apply-postcode {
	width: 46px;
}

#apply-date-of-birth {
	width: 110px;
}

#apply-specific-gender-paragraph {
	display: none;
}

#apply-fieldset-gender:has(input[value="other"]:checked) #apply-specific-gender-paragraph {
	display: initial;
}

#apply-error-message {
	color: red;
	font-weight: bold;
}

/******************************/
/* about.php specific styles */
/******************************/
/* About page custom container class which organises sections neatly */
.about_container {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	justify-content: space-between;
}

/* Team member contribution section */
#about_contributions dl {
	padding: 15px;
	border-radius: 5px;
}
#about_contributions dt {
	font-weight: bold;
	color: rgb(9, 121, 159);
}
#about_contributions dd {
	margin-bottom: 10px;
	padding-left: 10px;
}

/* Company summary section */
#about_summary {
	flex: 1 1 300px;
	max-width: 100%;
}

/* Team member interests table */
#about_interests table {
	width: 75%;
	margin: 10px auto;
}

#about_interests table td {
	padding-right: 10px;
}

#about_interests {
	padding-right: 100px;
	width: 100%;
	flex: 1;
}
#about_interests h2 {
	text-align: center;
}

/* Team information aside */
#about_group-info {
	max-width: 350px;
	padding-left: 100px;
}
#about_group-info li {
	margin-bottom: 5px;
	list-style-type: none;
}

/* Team photo aside */
#about_team-photo {
	float: right;
	clear: right;
}
#about_team-photo figure {
	text-align: right;
	float: right;
	margin: 20px 0;
}
#about_team-photo figure img {
	max-width: 350px;
	border-radius: 25px;
	border: solid black 3px;
	box-shadow: 10px 10px 5px lightgray;
}
#about_team-photo figcaption {
	font-style: italic;
	margin-top: 5px;
	color: #555;
}

/******************************/
/* manage.php specific styles */
/******************************/

#manage_eoi {
	width: 100%;
	/* overflow-x property below is to make table responsive to smaller screens is from W3Schools (https://www.w3schools.com/howto/howto_css_table_responsive.asp) */
	overflow-x: auto;
}

#manage_eoi table {
	margin: 10px auto;
	width: 100%;
}

#manage_eoi table td:not(.status) {
	text-align: center;
	padding: 0px 25px 0 25px;
}

#update_text {
	font-weight: bold;
	text-align: center;
	color: green;
	margin: 0;
}

#manage_eoi table th {
	padding: 5px;
}

#manage_eoi tr td:last-child {
    width: 1%;
    white-space: nowrap;
}

#manage_eoi tr:hover td {
	background-color: #e2e0e0;
}

#filter-bar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    padding: 10px;
    border-radius: 8px;
    position: relative;
}
#filter-options {
	flex: 1;
	min-width: 300px;
}

#filter-bar h4 {
    margin: 10px 5px 5px 5px;
}

.filter-group {
	padding: 5px;
    padding-right: 12px;
}

.filter-group input,
.filter-group select,
.filter-group button {
    padding: 4px 6px;
}

.sort-controls {
    display: flex;
    align-items: center;
    gap: 6px;
    position: absolute;
    bottom: 10px;
    right: 10px;
}

#deletion_message {
	color: #FF0019;
	display: inline;
	padding-left: 5px;
	font-weight: bold;
}

/******************************/
/* manage.php specific styles */
/******************************/

#user-manage table td {
	text-align: center;
	padding: 10px 25px 10px 25px;
}

#username,
#password {
	padding: 4px 6px;
}



/* these styles only apply to browsers with a viewport width smaller than 800 pixels, such as browsers on mobile devices */
/* mobile device criteria (modified) from https://www.w3schools.com/css/css_rwd_mediaqueries.asp */
@media only screen and (max-width: 800px) {
	/* make the page content and footer fill the page so that it's not squashed or hard to read */
	main, footer {
		box-sizing: border-box;
		width: 100%;
		padding-left: 10px;
		padding-right: 10px;
		border-radius: 0;
	}

	/* The nav css code for mobile devices below was generated using GenAI, with personal modifications added in.
	GenAI prompt: “{insert nav html and css here} I want to modify this code
	for a navigation menu so that when on mobile devices change it so that there is 2 columns, each containing two
	menu items. place the columns next to the logo with the center of the items level with the middle of the logo” */
	nav {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: flex-start;
		flex-wrap: nowrap;
		width: 100%;
		height: auto;
		padding: 25px;
		box-sizing: border-box;
		margin: auto;
		border-radius: 0px;
	}
	.nav-logo {
		position: relative;
		top: 0;
		left: 0;
		height: 80px;
	}
	nav ul {
		display: grid;
		grid-template-columns: repeat(2, auto);
		gap: 10px 20px;
		padding: 0;
		margin: 0;
		align-items: center;
	}
	nav ul li {
		display: block;
		margin: 0;
	}
	nav ul li a {
		display: block;
		padding: 5px 10px;
		font-size: 16px;
	}
	
	/* Make benefits banner images smaller to fit on smaller screen */
	#index-benefits figure {
		margin: 10px 5px 10px 5px;
	}
	
	#index-benefits figure img {
		max-width: 50px;
	}

	#index-benefits {
		background-size: 75%;
	}
	
	/* Vision and Pricing elements no longer float to either side on smaller screens and take up entire width,
	instead of just 45% */
	#index-vision {
		max-width: 100%;
	}
	
	#index-pricing {
		max-width: 100%;
	}

	/* make text inputs almost fill the line so they're easy to interact with on mobile */
	.apply-input[type="text"] {
		padding-top: 10px;
		width: 98% !important;
		margin-left: 2%;
	}
	
	/* make only one column of checkboxes so that the text isn't squished */
	.apply-checkbox-container {
		flex-basis: 100%;
	}
	
	/* make each button fill the line so that it's easier to press them */
	.apply-fancy-button {
		display: block;
		width: 100%;
		margin-top: 20px;
	}
	
	#about_group-info {
		padding-left: 0px;
	}

	#about_interests table {
		width: 100%;
	}

	/* make the jobs-aside aside elements full size and not floating when on mobile so it isn't squashed */
	.jobs-aside {
		float: initial;
		width: calc(100% - 30px);
		margin-top: 20px;
	}

	/* disable the flex styling when on mobile so that the content isn't squashed into two columns */
	.jobs-prerequisites, .jobs-extra-details {
		display: initial;
	}

	/* make the elements that would have been in a flexbox fill up the width of the container so they aren't squashed */
	.jobs-prerequisites div, .jobs-description {
		width: 100%;
	}

	.sort-controls {
        position: static;
        width: 100%;
        justify-content: flex-end;
        margin-top: 10px;
    }
}
