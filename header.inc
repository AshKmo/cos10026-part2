<?php
// start a session if one is not already active
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<header>
    <nav>
        <!-- The code or content below was created using GenAI. GenAI prompt: “generate a logo using the letter T, coloured with #00BFFF, not too edgy and separate a triangle at the bottom” -->
        <a title="Tolstra Home" href="index.php"><img src="images/logo.png" alt="Tolstra Logo" class="nav-logo"></a>
        <ul>
            <?php
            if (isset($_SESSION['username'])) {
                if (isset($_SESSION['privilege'])) {
                    echo '<li><a title="Tolstra - User Manager" href="manage_users.php">Manage Users</a></li>';
                }
                echo '<li><a title="Tolstra - Manage EOIs" href="manage.php">Manage EOIs</a></li>';
                echo '<li><a title="Manage Jobs" href="jobs_manage.php">Manage Jobs</a></li>';
                echo '<li><a href="logout.php">Logout</a></li>';
            } else {
                echo '<li><a title="About Tolstra" href="about.php">About</a></li>';
                echo '<li><a title="Job Positions" href="jobs.php">Job Descriptions</a></li>';
                echo '<li><a title="Tolstra Job Applications" href="apply.php">Apply Here</a></li>';
                echo '<li><a title="Tolstra Webpage Enhancements" href="enhancements.php">Webpage Enhancements</a></li>';
                echo '<li><a title="Email: info@tolstra.com.au" href="mailto:info@tolstra.com.au">Contact Us</a></li>';
                echo '<li><a title="Tolstra - Staff Login" href="login.php">Login</a></li>';
            }
            ?>
        </ul>
    </nav>
</header>