<?php
	/*
	 * Login Form Handler
	 */
	if(isset($_POST['login-button'])) {
		$username = $_POST['login-username'];
		$password = $_POST['login-password'];

		$wasLogin = $account->login($username, $password);
	
		if($wasLogin) {
			$_SESSION['userLoggedIn'] = $username;
			header("Location: index.php");
		}
	}
?>