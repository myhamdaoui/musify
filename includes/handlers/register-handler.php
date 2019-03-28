<?php
	/**
	 * Register form handler
	 */ 

	if(isset($_POST['register-button'])) {
		// User Name
		$username = sanitizeFormUsername($_POST['register-username']);
		// First Name
		$first_name = sanitizeFormString($_POST['first-name']);
		// Last Name
		$last_name = sanitizeFormUsername($_POST['last-name']);
		// Email
		$email = sanitizeFormString($_POST['email']);
		// Confirm Email
		$email_confirm = sanitizeFormString($_POST['confirm-email']);
		// Password
		$password = sanitizeFormPassword($_POST['register-password']);
		// Confirm Password
		$password_confirm = sanitizeFormPassword($_POST['confirm-password']);

		$wasSuccessful = $account->register($username, $first_name, $last_name, $email, $email_confirm, $password, $password_confirm);

		if($wasSuccessful) {
			$_SESSION['userLoggedIn'] = $username;
			header("Location: index.php");
		}

	}

	function sanitizeFormUsername($inputText) {
		$inputText = str_replace(" ", "", strip_tags($inputText));
		return $inputText;
	}

	function sanitizeFormString($inputText) {
		$inputText = str_replace(" ", "", strip_tags($inputText));
		$inputText = ucfirst(strtolower($inputText));
		return $inputText;
	}


	function sanitizeFormPassword($inputText) {
		$inputText = strip_tags($inputText);
		return $inputText;
	}
?>