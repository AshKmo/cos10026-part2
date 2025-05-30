<!DOCTYPE html>
<?php
	session_start();
?>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part1/about.php -->

<!-- the page language is set to English -->
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
		<h1>About Tolstra</h1>

		<div class="about_container">
			<!-- Brief summary of what the company Tolstra is all about -->
			<section id="about_summary">
				<h2>What is Tolstra</h2>
				<p>
					Tolstra is a telecommunications provider, founded in April 2025, for citizens of Olmania. Serving
					our loyal clients since 2025, we provide the highest level
					service with record keeping uptime of 100%.
				</p>
				<p>
					Founded by The Merge Masters, a group formed during class of COS100026 - Web Technology Project at
					Swinburne University.
				</p>

				<p>
					We are currently looking for skillful and driven individuals to join our company.
					<br><a title="Job Positions" href="jobs.php">Current open positions can be viewed here</a>.
					<br>If interested, you may also <a title="Applications" href="apply.php">submit your application
						here</a>.
				</p>
			</section>

			<!-- Group information including student ids, class and tutor name -->
			<aside id="about_group-info">
				<h2>Group Information</h2>
				<hr>
				<ul>
					<li><strong>Group Name:</strong> The Merge Masters - COS10026 Web Dev - Friday 2:30PM</li>
					<li><strong>Student IDs:</strong>
						<ul>
							<li>Ash - 105926680</li>
							<li>Hayden - 105863839</li>
							<li>Max - 105920996</li>
							<li>Tim - 106013868</li>
						</ul>
					</li>
					<li><strong>Tutor:</strong> Razeen Hashmi</li>
				</ul>
			</aside>
		</div>

		<div class="about_container">
			<!-- Contributions of each group member -->
			<section id="about_contributions">
				<h2>Team Contributions to the Project</h2>
				<dl>
					<dt>Ash</dt>
					<dd>Enhancements page, applications page, EOI processing page and database table</dd>
					<dt>Hayden</dt>
					<dd>Home page, management login page, registration and authentication</dd>
					<dt>Max</dt>
					<dd>Job descriptions page and database table, job creation page</dd>
					<dt>Tim</dt>
					<dd>About page, site-wide styling, EOI management page and related enhancements</dd>
				</dl>
			</section>

			<!-- Team photo -->
			<aside id="about_team-photo">
				<h2>Our Team</h2>
				<hr>
				<figure>
					<img src="images/team-photo.jpeg" alt="Team Photo">
					<figcaption>The Merge Masters</figcaption>
				</figure>
			</aside>

		</div>

		<section id="about_interests">
			<!-- Interests of each group member -->
			<h2>Team Interests</h2>
			<table>
				<caption>Interests of The Merge Masters</caption>
				<tr>
					<th>Member</th>
					<th>Interests</th>
				</tr>
				<tr>
					<th>Ash</th>
					<td>
						<ul>
							<li><strong>Demographic Info:</strong> University student, Arch Linux user (btw)</li>
							<li><strong>Hometown:</strong> Melbourne, VIC</li>
							<li><strong>Interests:</strong>
								<ul>
									<li>💻 Programming</li>
									<li>📖 Reading</li>
									<li>💭 Philosophy (occasionally)</li>
								</ul>
							</li>
							<li><strong>Favourite Media:</strong>
								<ul>
									<li>📖 Books: Hitchhiker's Guide to the Galaxy</li>
									<li>🎵 Music: Waterflame</li>
									<li>🎬 Films: Back to the Future, Star Wars</li>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>Hayden</th>
					<td>
						<ul>
							<li><strong>Demographic Info:</strong> University student, Also Arch Linux user (btw)</li>
							<li><strong>Hometown:</strong> Melbourne, VIC</li>
							<li><strong>Interests:</strong>
								<ul>
									<li>🎮 Gaming with friends</li>
									<li>🍴 Going out with friends</li>
									<li>⚙️ Creating game servers</li>
								</ul>
							</li>
							<li><strong>Favourite Media:</strong>
								<ul>
									<li>🎮 Games: Haste: Broken Worlds & Marvel Rivals</li>
									<li>📖 Books: Hitch Hiker's Guide to the Galaxy & Dirk Gently's Holistic Detective
										Agency</li>
									<li>🎵 Music: Indie Pop & Indie Rock</li>
									<li>🎬 Films: Invincible, Arcane, The Dark Knight</li>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>Max</th>
					<td>
						<ul>
							<li><strong>Demographic Info:</strong> University student, tech + gaming nerd</li>
							<li><strong>Hometown:</strong> Melbourne, VIC</li>
							<li><strong>Interests:</strong>
								<ul>
									<li>🎮 Gaming</li>
									<li>⚔️ Dungeons & Dragons</li>
									<li>💻 Programming</li>
								</ul>
							</li>
							<li><strong>Favourite Media:</strong>
								<ul>
									<li>🎮 Games: Fire Emblem Awakening</li>
									<li>🎵 Music: Video game stuff</li>
									<li>🎬 Films: Game of Thrones, Lord of the Rings</li>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>Tim</th>
					<td>
						<ul>
							<li><strong>Demographic Info:</strong> University student, tech enthusiast</li>
							<li><strong>Hometown:</strong> Melbourne, VIC</li>
							<li><strong>Interests:</strong>
								<ul>
									<li>🎮 Gaming</li>
									<li>🏉 Footy</li>
									<li>🍻 Going out for a drink and feed</li>
								</ul>
							</li>
							<li><strong>Favourite Media:</strong>
								<ul>
									<li>📖 Books: N/A</li>
									<li>🎵 Music: Hip Hop, 80's, Pop</li>
									<li>🎬 Films: Game of Thrones, Breaking Bad</li>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
			</table>
		</section>

	</main>

	<!-- include the page footer -->
	<?php include_once "footer.inc"; ?>
</body>

</html>