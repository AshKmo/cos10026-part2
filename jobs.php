<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part1/jobs.php -->

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include "meta.inc"; ?>

	<!-- Set page description -->
	<meta name="description" content="Available job positions at Tolstra">

	<!-- Set keywords -->
	<meta name="keywords" content="Tolstra, jobs, positions">

	<!-- Set the page title -->
	<title>Job Positions</title>
</head>

<body>
	<!-- include the page header -->
	<?php include "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
		<!-- Form main title -->
		<h1 id="jobs-title">Available Jobs</h1>
		<!-- Anchor tag sends user to the application page -->
		<p>Thank you for considering a position at Tolstra. <a title="Applications" href="apply.php#">Visit the
				application form</a> to apply for any listed position.</p>
		<hr>

		<!-- Support Technician details -->
		<section class="jobs-dropdown">
			<?php
				require_once "settings.php";
				$dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);
				if ($dbconn) {
					$query = "SELECT * FROM job_descriptions";
					$result = mysqli_query($dbconn, $query);
					if ($result) {
						while ($row = mysqli_fetch_assoc($result)) {
							echo "<input type=\"checkbox\" id=\"jobs-" . $row["position"] . "-dropdown\">";
							echo "<h2 class=\"jobs-dropdown-title\"><label for=\"jobs-" . $row["position"] . "-dropdown\"\><em class=\"arrow\"></em><strong> " . $row["position"] . "</strong></label></h2>";
							echo "<section class=\"jobs-dropdown-content\">";

							echo "<aside class=\"jobs-aside\">";
							echo "<strong>Job ID: </strong>" . $row["job_id"] . "<br><br>";
							echo "<strong>You will report to:</strong><br>";
							echo $row["report_to_title"];
							echo "(current is " . $row["report_to_name"] . ")";
							echo "</aside>";

							echo "<p class=\"jobs-description\">" . $row["description"] . "</p>";

							echo "<h3>You will be expected to:</h3>";
							echo "<ol>";
							$expectations = json_decode($row["expectations"]);
							foreach ($expectations as $expect) {
								echo "<li>" . $expect . "</li>";
							}

							echo "</section>";
						}
					} else {
						echo "<p>There are currently no available job positions.</p>";
					}
				}
			?>
			<!-- Making the title into a clickable checkbox so that, when clicked, the CSS will modify the height of the content to display the job description -->
			<input type="checkbox" id="jobs-it-support-dropdown">
			<!-- the em tag is used to create an arrow through CSS; even without any text, the CSS creates a border on one corner, uses padding to give the arrow width, then rotates it to look like an arrow -->
			<h2 class="jobs-dropdown-title"><label for="jobs-it-support-dropdown"><em class="arrow"></em><strong> IT Support Technician</strong></label></h2>
			<section class="jobs-dropdown-content">

				<!-- Defining the content to appear on the side of the screen -->
				<aside class="jobs-aside">
					<strong>Job ID: </strong>IT427<br><br>
					<strong>You will report to:</strong><br>
					Assistant Network Administrator<br>
					(current is John Monash)
				</aside>

				<!-- Set the content that appears below the job's title -->
				<p class="jobs-description">As and IT Support Technician, you will assist staff and customers in any
					hardware and software dificulties that they may face. You will work and talk with many people around
					Australia, lending a hand and potentially teaching them about networking within their home or office
					environment.</p>

				<!-- Set the job expectations, with the title first before the rest of the content as an ordered list -->
				<h3>You will be expected to:</h3>
				<ol>
					<li>Understand the underlying systems through which our network operates</li>
					<li>Be able to give technical support to staff and clients who have questions</li>
					<li>Bugfix and assist with the management of our networks and devices should any issues arise</li>
				</ol>

				<!-- Define the prerequisites information, starting with the title -->
				<h3 class="jobs-prereqs-title">Prerequisites:</h3>
				<!-- Section with the "jobs-prerequisites" class is used to contain the essential and preferable prerequisites so that the CSS can style them to sit side-by-side with a flexbox -->
				<section class="jobs-prerequisites">
					<div>
						<h4>Essential</h4>
						<!-- Unordered list for all essential prerequisites -->
						<ul>
							<li>Understanding of SQL and Python</li>
							<li>
								Understanding of networks, including:
								<ul>
									<li>Understanding of how switches and routers operate</li>
									<li>Ability to configure CISCO-branded switches and routers</li>
									<li>Techniques for debugging issues</li>
								</ul>
							</li>
						</ul>
					</div>
					<div>
						<h4>Preferable</h4>
						<!-- Unordered list for all preferable prerequisites -->
						<ul>
							<li>Bachelor's degree in Computer Science or a related field (or equivalent practical
								experience)</li>
							<li>Clear communication skills and ability to explain complex concepts to others</li>
							<li>A friendly attitude and a lot of patience</li>
						</ul>
					</div>
				</section>

				<!-- Same as the prerequisites; section with "jobs-extra-details" class is used for formatting -->
				<section class="jobs-extra-details">
					<div>
						<h3>Expected Salary Range:</h3>
						<p>$90,000 to $105,000</p>
					</div>
					<div>
						<h3>Employment Type:</h3>
						<p>Full Time</p>
					</div>
					<div>
						<h3>Location:</h3>
						<p>Croydon, Melbourne</p>
					</div>
				</section>
			</section>
		</section>

		<!-- Data Analyst details -->
		<!-- Follows same structure as IT Support Technician; use that for reference -->
		<section class="jobs-dropdown">
			<input type="checkbox" id="jobs-data-analyst-dropdown">
			<h2 class="jobs-dropdown-title"><label for="jobs-data-analyst-dropdown"><em class="arrow"></em><strong> Data
						Analyst</strong></label></h2>
			<section class="jobs-dropdown-content">
				<aside class="jobs-aside">
					<strong>Job ID: </strong>DA193<br><br>
					<strong>You will report to:</strong><br>
					Chief Data Officer (CDO)<br>
					(current is Bill Gates)
				</aside>
				<p class="jobs-description">We're looking for a detail-oriented Data Analyst to help turn data into
					actionable insights that support business decisions and strategic goals. If you're someone with a
					keen eye for patters, a sharp mind for data and friendly attitude to communicate with your team,
					we'd love to have you on board.</p>

				<h3>You will be expected to:</h3>
				<ol>
					<li>Analyse large datasets to identify trends, patterns, and opportunities</li>
					<li>Create visual reports and dashboards to communicate findings</li>
					<li>Collaborate with cross-functional teams to support data-driven decisions</li>
					<li>Maintain data accuracy and integrity in reporting systems</li>
					<li>Provide regular performance and KPI reports to stakeholders</li>
				</ol>

				<h3 class="jobs-prereqs-title">Prerequisites:</h3>
				<div class="jobs-prerequisites">
					<div>
						<h4>Essential</h4>
						<ul>
							<li>Proficiency in SQL and Microsoft Excel</li>
							<li>Experience with data visualization tools</li>
							<li>Strong analytical and problem-solving skills</li>
							<li>Bachelor's degree in Data Science, Statistics, or a related field</li>
						</ul>
					</div>
					<div>
						<h4>Preferable</h4>
						<ul>
							<li>Experience with Python for data analysis</li>
							<li>Familiarity with cloud platforms like AWS or Azure</li>
							<li>Knowledge of machine learning basics</li>
							<li>Previous experience in a data analysis role</li>
						</ul>
					</div>
				</div>
				<section class="jobs-extra-details">
					<div>
						<h3>Expected Salary Range:</h3>
						<p>$90,000 to $105,000</p>
					</div>
					<div>
						<h3>Employment Type:</h3>
						<p>Full Time</p>
					</div>
					<div>
						<h3>Location:</h3>
						<p>Southbank, Melbourne</p>
					</div>
				</section>
			</section>
		</section>

		<!-- Blockchain Developer Details -->
		<!-- Follows same structure as IT Support Technician; use that for reference -->
		<section class="jobs-dropdown">
			<input type="checkbox" id="jobs-blockchain-developer-dropdown">
			<h2 class="jobs-dropdown-title"><label for="jobs-blockchain-developer-dropdown"><em
						class="arrow"></em><strong> Blockchain Developer</strong></label></h2>
			<section class="jobs-dropdown-content">
				<aside class="jobs-aside">
					<strong>Job ID: </strong>BC279<br><br>
					<strong>You will report to:</strong><br>
					Lead Software Developer<br>
					(current is Michael Scott)
				</aside>
				<!-- Description made by ChatGPT (prompt: Make a brief description for the job hiring page of a blockchain developer) -->
				<p class="jobs-description">We're looking for a skilled Blockchain Developer to join our team and help
					build secure, scalable, and innovative decentralized solutions. You'll work on smart contract
					development, blockchain integration, and contribute to cutting-edge projects in Web3, DeFi, or NFT
					ecosystems. If you're passionate about blockchain technology and enjoy solving complex technical
					challenges, we'd love to hear from you.</p>
				<!-- Expectations made by ChatGPT (same chat as previous, prompt: give a list of things that someone working in that job would be expected to perform) -->
				<h3>You will be expected to:</h3>
				<ol>
					<li>Build and maintain decentralized applications (dApps) that interact with blockchain protocols
					</li>
					<li>Integrate blockchain solutions with existing web or mobile applications using APIs and SDKs</li>
					<li>Write and test secure, efficient, and scalable code, primarily in languages like Solidity, Rust,
						or Go</li>
					<li>Collaborate with frontend and backend developers to ensure seamless integration of blockchain
						functionality</li>
					<li>Conduct smart contract audits and implement security best practices to safeguard against
						vulnerabilities</li>
				</ol>
				<!-- Prerequisites made by ChatGPT (same chat as previous, prompt: give a list of prerequisites required to apply for the job, separated into essential and preferable) -->
				<h3 class="jobs-prereqs-title">Prerequisites:</h3>
				<div class="jobs-prerequisites">
					<div>
						<h4>Essential</h4>
						<ul>
							<li>Proficiency in at least one smart contract language (e.g., Solidity, Rust, Vyper)</li>
							<li>Strong understanding of blockchain architecture and principles (e.g., consensus
								mechanisms, gas optimization, token standards like ERC-20, ERC-721)</li>
							<li>Familiarity with blockchain development tools (e.g., Hardhat, Truffle, Foundry,
								Metaplex)</li>
							<li>Proficiency in at least one backend programming language (JavaScript/TypeScript, Go,
								Python, etc.)</li>
						</ul>
					</div>
					<div>
						<h4>Preferable</h4>
						<ul>
							<li>Experience with Layer 2 solutions, sidechains, or cross-chain interoperability</li>
							<li>Familiarity with cloud platforms like AWS or Azure</li>
							<li>Background in DevOps or experience managing blockchain node infrastructure</li>
							<li>Bachelor's degree in Computer Science, Engineering, or a related field (or equivalent
								practical experience)</li>
						</ul>
					</div>
				</div>
				<section class="jobs-extra-details">
					<div>
						<h3>Expected Salary Range:</h3>
						<p>$105,000 to $120,000</p>
					</div>
					<div>
						<h3>Employment Type:</h3>
						<p>Full Time</p>
					</div>
					<div>
						<h3>Location:</h3>
						<p>Remote; office is in Montreal, Canada</p>
					</div>
				</section>
			</section>
		</section>

		<!-- QA Tester Details -->
		<!-- Follows same structure as IT Support Technician; use that for reference -->
		<section class="jobs-dropdown">
			<input type="checkbox" id="jobs-qa-tester-dropdown">
			<h2 class="jobs-dropdown-title"><label for="jobs-qa-tester-dropdown"><em class="arrow"></em><strong> QA
						Tester</strong></label></h2>
			<section class="jobs-dropdown-content">
				<aside class="jobs-aside">
					<strong>Job ID: </strong>QA666<br><br>
					<strong>You will report to:</strong><br>
					QA Manager<br>
					(current is Freddie Mercury)
				</aside>
				<!-- Description made by ChatGPT (prompt: Make a brief description for the job hiring page of a QA tester) -->
				<p class="jobs-description">We're looking for a detail-oriented QA Tester to join our team and ensure
					the quality and reliability of our products. In this role, you'll design and execute test plans,
					identify bugs, and collaborate with developers to resolve issues. If you have a keen eye for detail,
					a passion for quality, and a knack for breaking things (in a good way), we'd love to hear from you.
				</p>
				<!-- Expectations made by ChatGPT (same chat as previous, prompt: give a list of things that someone working in that job would be expected to perform) -->
				<h3>You will be expected to:</h3>
				<ol>
					<li>Develop and execute test plans, test cases, and test scripts based on product requirements</li>
					<li>Identify, document, and track bugs or issues using bug-tracking tools (e.g., Jira, Bugzilla)
					</li>
					<li>Collaborate with developers, designers, and product managers to ensure product quality</li>
					<li>Perform functional, regression, integration, and user acceptance testing</li>
				</ol>
				<!-- Prerequisites made by ChatGPT (same chat as previous, prompt: give a list of prerequisites required to apply for the job, separated into essential and preferable) -->
				<h3 class="jobs-prereqs-title">Prerequisites:</h3>
				<div class="jobs-prerequisites">
					<div>
						<h4>Essential</h4>
						<ul>
							<li>Proven experience in software quality assurance or testing</li>
							<li>Strong understanding of QA methodologies, tools, and processes</li>
							<li>Ability to write clear, concise, and comprehensive test plans and test cases</li>
							<li>Familiarity with bug tracking tools (e.g., Jira, Trello, Bugzilla)</li>
							<li>Basic knowledge of software development life cycle (SDLC)</li>
						</ul>
					</div>
					<div>
						<h4>Preferable</h4>
						<ul>
							<li>Experience with automated testing tools (e.g., Selenium, Cypress, TestComplete)</li>
							<li>Familiarity with scripting languages (e.g., Python, JavaScript, or Bash)</li>
							<li>Knowledge of Agile/Scrum development practices</li>
							<li>Experience testing across multiple platforms (e.g., web, mobile, desktop)</li>
						</ul>
					</div>
				</div>
				<section class="jobs-extra-details">
					<div>
						<h3>Expected Salary Range:</h3>
						<p>$80,000 - $90,000</p>
					</div>
					<div>
						<h3>Employment Type:</h3>
						<p>Part Time</p>
					</div>
					<div>
						<h3>Location:</h3>
						<p>Remote; office is in Melbourne, CBD</p>
					</div>
				</section>
			</section>
		</section>
	</main>

	<!-- include the page footer -->
	<?php include "footer.inc"; ?>
</body>

</html>