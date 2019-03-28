<?php

/**
 * page-name: Register Page [Main Page for no logged in users]
 * description: You can log in | Create a new account
 * @version: 1.0
 * @author: HAMDAOUI Mohammed-Yassin
 */

// Account class 
include "includes/config.php";
include "includes/classes/Constants.php";
include "includes/classes/Account.php";

$account = new Account($con);

// Include register handler
include "includes/handlers/register-handler.php";

// Include login handler
include "includes/handlers/login-handler.php";

function getInputValue($name) {
	if(isset($_POST[$name])) {
		echo $_POST[$name];
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Musify!</title>
	<link rel="stylesheet" type="text/css" href="assets/css/register.css">
</head>
<body>
	<div id="background">
		<div id="loginContainer">
			<div id="input-container">

				<!-- LOGIN -->
				<form id="login-form" action="register.php" method="POST">
					<h2>Login to your account</h2>
					<div class="input-groupe">
						<?php echo $account->getError(Constants::$loginFailed);?>
						<label for="login-username">Username</label>
						<input id="login-username" type="text" name="login-username" placeholder="e.g. Mohammed Yassin" value="<?php getInputValue("login-username"); ?>" autocomplete="off" required>
					</div>
					<div class="input-groupe">
						<label for="login-password">Password</label>
						<input id="login-password" type="password" name="login-password" placeholder="Your password" required autocomplete="new-password">
					</div>

					<button type="submit" name="login-button">LOG IN</button>

					<div class="hasAccountText">
						<span class="hideLogin">Don't have an account yer? Sign up here.</span>
					</div>
				</form>

				<!-- REGISTER -->
				<form id="register-form" action="register.php" method="POST">
					<h2>Create your free account</h2>
					<div class="input-groupe">
						<?php echo $account->getError(Constants::$userNameCharacters);?>
						<?php echo $account->getError(Constants::$usernameTaken);?>
						<label for="register-username">Username</label>
						<input id="register-username" type="text" name="register-username" placeholder="Enter a username" value="<?php getInputValue("register-username")?>" autocomplete="off" required>
					</div>

					<div class="input-groupe">
						<?php echo $account->getError(Constants::$firstNameCharacters);?>
						<label for="first-name">First Name</label>
						<input id="first-name" type="text" name="first-name" placeholder="Enter your first name" value="<?php getInputValue("first-name")?>" autocomplete="off" required>
					</div>

					<div class="input-groupe">
						<?php echo $account->getError(Constants::$lastNameCharacters);?>
						<label for="last-name">Last Name</label>
						<input id="last-name" type="text" name="last-name" placeholder="Enter last name" value="<?php getInputValue("last-name")?>" autocomplete="off" required>
					</div>

					<div class="input-groupe">
						<?php echo $account->getError(Constants::$emailDoNotMatch);?>
						<?php echo $account->getError(Constants::$emailInvalid);?>
						<?php echo $account->getError(Constants::$emailTaken);?>
						<label for="email">Email</label>
						<input id="email" type="email" name="email" placeholder="Enter your email" value="<?php getInputValue("email")?>" autocomplete="off" required>
					</div>

					<div class="input-groupe">
						<label for="confirm-email">Confirm Email</label>
						<input id="confirm-email" type="email" name="confirm-email" placeholder="Confirm your email" value="<?php getInputValue("confirm-email")?>" required>
					</div>

					<div class="input-groupe">
						<?php echo $account->getError(Constants::$passwordsDoNotMatch);?>
						<?php echo $account->getError(Constants::$passwordsNotAlphaNumeric);?>
						<?php echo $account->getError(Constants::$passwordCharacters);?>
						<label for="register-password">Password</label>
						<input id="register-password" type="password" name="register-password" placeholder="Your Password" required autocomplete="new-password">
					</div>

					<div class="input-groupe">
						<label for="confirm-password">Confirm Password</label>
						<input id="confirm-password" type="password" name="confirm-password" placeholder="Your Password" required autocomplete="new-password">
					</div>

					<button type="submit" name="register-button">SIGN UP</button>
					<div class="hasAccountText">
						<span class="hideRegister">Already have an account? Log in here.</span>
					</div>
				</form>
			</div>
			<div class="login-text">
				<h1>Get great music, right now</h1>
				<h2>Listen to loads if songs for free</h2>
				<ul>
					<li>Discover music you'll fall in love with</li>
					<li>Create your own playlist</li>
					<li>Follow Artist to keep up to date</li>
				</ul>
			</div>
		</div>
	</div>

	<!-- jQuery JS Library -->
	<script src="assets/js/jquery-3.3.1.js"></script>
	<script src="assets/js/register.js"></script>

	<?php 
		if(isset($_POST['register-button'])) {
			echo '<script>	
					$(document).ready(function() {
						$("#login-form").hide();
						$("#register-form").show();
					});
				</script>';
		} else {
			echo '<script>	
					$(document).ready(function() {
						$("#login-form").show();
						$("#register-form").hide();
					});
				</script>';

		}
	?>
</body>
</html>