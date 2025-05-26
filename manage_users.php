<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['privilege'])) {
    if (isset($_SESSION['username'])) {
        header('Location: manage.php');
        exit;
    } else {
        header('Location: login.php');
        exit;
    }
}

require_once("settings.php");
$dbconn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$dbconn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validates and inserts the new user info into the database
    if (isset($_POST['type']) && $_POST['type'] == 'new_user') {
        $input_username = trim($_POST['username']);
        $input_password = trim($_POST['password']);
        $input_privilege = $_POST['privilege'];
        $hashed_password = password_hash($input_password, PASSWORD_DEFAULT);

        $query = "SELECT * FROM users WHERE username = '$input_username'";

        $result = mysqli_query($dbconn, $query);

        if (mysqli_fetch_assoc($result)) {
            $_SESSION['new_status'] = "User already exists.";
        } else {
            // Password strength check. Inspired from: https://stackoverflow.com/questions/8141125/regex-for-password-php
            $uppercase = preg_match('@[A-Z]@', $input_password);
            $lowercase = preg_match('@[a-z]@', $input_password);
            //$number    = preg_match('@[0-9]@', $input_password);
            if (!$uppercase || !$lowercase || /*!$number ||*/ strlen($input_password) < 8){
                $_SESSION['new_status'] = "Failed to create user. Password must contain at least 8 characters, an uppercase letter, and a lowercase letter.";

                header('Location: manage_users.php');
                exit;
            } else {
                $stmt = $dbconn -> prepare("INSERT INTO users (username, password, privilege) VALUES (?, ?, ?)");
                $stmt -> bind_param("sss", $input_username, $hashed_password, $input_privilege);
                $stmt -> execute();
                $_SESSION['new_status'] = "User created.";
            }
        }
        header('Location: manage_users.php');
        exit;
    
    // User Deletion
    } elseif (isset($_POST['type']) && $_POST['type'] == 'delete_user') {
        $delete_username = $_POST['user'];
        $query = "DELETE FROM users WHERE username= '$delete_username'";
        if ($dbconn -> query($query) === TRUE) {
            $_SESSION['del_status'] = "User deleted.";
        } else {
            $_SESSION['del_status'] = "Unable to delete user.";
        }
        header('Location: manage_users.php');
        exit;
    }
}

?>
<!DOCTYPE html>

<!-- this page can be accessed on Github Pages at https://ashkmo.github.io/cos10026-part2/manage.php -->

<!-- the page language is set to English -->
<html lang="en">

<head>
	<!-- include some common meta tags shared between all regular pages -->
	<?php include_once "meta.inc"; ?>

	<!-- set the page description -->
	<meta name="description" content="Tolstra - User Manager">

	<!-- set keywords for SEO -->
	<meta name="keywords" content="Tolstra, telecommunications, Internet, phone, about">

	<!-- set the page title -->
	<title>Tolstra - User Manager</title>
</head>

<body>
	<!-- include the page header -->
	<?php include_once "header.inc" ?>

	<!-- define the main body content of the page -->
	<main>
        

		<section id="user-add">
            <!-- Simple form based off the login form that creates a new user -->
			<h1>Add User</h1>
            <form action="manage_users.php" method="POST">
                <input type="hidden" name="type" value="new_user">

                <label for="username">Username: </label><br>
                <input type="text" id="username" name="username" placeholder="username" required>

                <br><br>

                <label for="username">Password: </label><br>
                <input type="password" id="password" name="password" placeholder="password" required>

                <br><br>

                <input type="radio" id="staff" value="staff" name="privilege" checked required>
                <label for="staff">Staff</label>

                <input type="radio" id="manager" value="manager" name="privilege">
                <label for="manager">Manager</label>

                <?php
				if (isset($_SESSION['new_status'])) {
					echo "<section id=new_status>";
					echo '<p>' . $_SESSION['new_status'] . '</p>';
					echo "</section>";
					unset($_SESSION['new_status']);
				} else {
                    echo "<br><br>";    
                }
				?>

                <input type="submit" value="Add User">
            </form>
        </section>

        <br><hr><br>

        <!-- Dynamic User Management Form -->
        <section id=user-manage>
            <h1>User Management</h1>
            <table>
                <tr>
                    <th>Username</th>
                    <th class='user-manage-privilege'>Privilege</th>
                    <th class='user-manage-manage'>Manage</th>
                    
                    <?php
                    $query = "SELECT username, privilege FROM users";
                    $result = $dbconn -> query($query);

                    if ($result -> num_rows > 0) {
                        while ($row = $result -> fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['username'] . "</td>";
                            echo "<td>" . $row['privilege'] . "</td>";
                            
                            // Delete User Button / Form
                            echo "<td>";
                            echo "<form action='manage_users.php' method='POST'>";
                            echo "<input type='hidden' name='type' value='delete_user'>";
                            echo "<input type='hidden' name='user' value='" . $row['username'] . "'>";
                            echo "<input type='submit' class='users-manage-del-button' value='Delete'>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
            </table>

            <?php
			if (isset($_SESSION['del_status'])) {
			    echo "<section id=del_status>";
				echo '<p>' . $_SESSION['del_status'] . '</p>';
				echo "</section>";
				unset($_SESSION['del_status']);
			}
			?>
            
		</section>
    </main>

	<?php include_once "footer.inc"; ?>
</body>