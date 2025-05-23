<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part1/ -->

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include_once "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Tolstra home page">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, telecomunications, phone, Internet">

	<!-- set the page title -->
	<title>Tolstra Home</title>
</head>

<body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>

		<h1>Tolstra</h1>

		<div id="index-company-info">
			<p>
				Welcome to the home of <strong>Tolstra</strong>. We are a new and upcoming telecomunications company
				striving to give individuals and commercial businesses the very best experience in mobile and other
				commication services.
			</p>
		</div>

		<br>

		<!-- Company benefits banner -->
		<div id="index-benefits">
			<figure>
				<!-- The code or content below was created using GenAI. GenAI prompt: “generate an image of a headset with a clay style as an icon format on a white background” -->
				<img src="images/ringing_phone.png" alt="An image of a rigning clay phone">
				<figcaption><strong>Unlimited Calls!</strong></figcaption>
			</figure>
			<figure>
				<!-- The code or content below was created using GenAI. GenAI prompt: “generate an image of some coins with a clay style as an icon format on a white background” -->
				<img src="images/coins.png" alt="An image of an two clay coins">
				<figcaption><strong>Cheapest Plans Around!</strong></figcaption>
			</figure>
			<figure>
				<!-- The code or content below was created using GenAI. GenAI prompt: “generate an image of a headset with a clay style as an icon format on a white background” -->
				<img src="images/headset.png" alt="An image of a clay headset">
				<figcaption><strong>24/7 Support!</strong></figcaption>
			</figure>
		</div>

		<br>

		<!-- The vision of our company -->
		<div id="index-vision">
			<h2>Our Vision</h2>
			<p>
				Here at <strong>Tolstra</strong>, we have long had a vision of a seamless and interconnected world. We
				aim to try remove the perils long associated with setting up and managing telecomunications plans,
				having all the best value setups one would need, all in one place.
			</p>
			<br>
			<p>
				If you happen to share our vision and think you would make a valued member of our team, we are currently
				looking for new employees, and would love for you to join us. If this is you, please have a look at our
				<a title="Job Positions" href="jobs.php">current availabilities</a>!
			</p>
		</div>

		<!-- The pricing of the plans we offer -->
		<div id="index-pricing">
			<h2>Plan Pricing</h2>
			<table>
				<tr>
					<th>Price</th>
					<th>Features</th>
					<th>Data</th>
				</tr>
				<tr>
					<td>$12 / month</td>
					<td>
						<ul>
							<li>Unlimited calls and texts</li>
							<li>4G supported</li>
						</ul>
					</td>
					<td>18G</td>
				</tr>
				<tr>
					<td>$26 / month</td>
					<td>
						<ul>
							<li>Unlimited calls and texts</li>
							<li>5G supported</li>
						</ul>
					</td>
					<td>50G</td>
				</tr>
				<tr>
					<td>$50 / month</td>
					<td>
						<ul>
							<li>Unlimited calls and texts</li>
							<li>5G supported</li>
							<li>Overseas calls and texts included</li>
						</ul>
					</td>
					<td>100G</td>
				</tr>
			</table>
		</div>

	</main>

	<!-- include the page footer -->
	<?php include_once "footer.inc"; ?>
</body>

</html>