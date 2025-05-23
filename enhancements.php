<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part1/ -->

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include_once "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Tolstra Webpage Enhancements">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, website, enhancements">

	<!-- set the page title -->
	<title>Tolstra Home</title>
</head>

<body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>

		<h1>Webpage Enhancements</h1>

		<p>Tolstra is proud to announce that a number of additional enhancements have been added to our website, including...</p>
		<ul>
			<li>The EOI records can now be displayed in the management console ordered by the value of a specific field and in a specific direction (ascending/descending)</li>
			<li>A manager registration page was created to allow for new users to be added to the management console and job creation pages</li>
			<li>A manager login page was created and the management console now requires a valid username and password to be submitted to its database prior to use</li>
			<li>The manager login page will now prevent a user from logging in after they have unsuccessfully attempted to do so three consecutive times</li>
			<li>A job creation and editing page was added to allow managers to change the positions offered by the company and for which applicants can submit expressions of interest</li>
		</ul>

	</main>

	<!-- include the page footer -->
	<?php include_once "footer.inc"; ?>
</body>

</html>