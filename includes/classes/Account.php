<?php 

	class Account {
		private $error_array;
		private $con;

		public function __construct($con) {
			$this->con = $con;
			$this->error_array = array();
		}

		public function register($un, $fn, $ln, $em1, $em2, $pw1, $pw2) {
			// Validate inputs
			$this->validateUsername($un);
			$this->validateFirstName($fn);
			$this->validateLastName($ln);
			$this->validateEmails($em1, $em2);
			$this->validatePasswords($pw1, $pw2);

			if(empty($this->error_array)) {
				//TODO Insert into db
				return $this->insertUserDetails($un, $fn, $ln, $em1, $pw1);
			} else {
				false;
			}
		}

		public function getError($error) {
			if(!in_array($error, $this->error_array)) {
				$error = "";
			}
			return "<span class='error-message'>" . $error . "</span>";

		}

		public function login($un, $pw) {
			$pw = md5($pw);
			$query = mysqli_query($this->con, "SELECT * FROM users WHERE username = '$un' AND password = '$pw'");
			if(mysqli_num_rows($query) == 1) {
				return true;
			} else {
				array_push($this->error_array, Constants::$loginFailed);
				return false;
			}
		}

		private function insertUserDetails($un, $fn, $ln, $em, $pw) {
			$encryptedPw = md5($pw);
			$profilePic = "assets/images/profile-pics/head_emerald.png";
			$date = date("Y-m-d");

			$result = mysqli_query($this->con, "INSERT INTO users VALUES(DEFAULT, '$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");
			return $result;
		}

		private function validateUsername($un) {
			// Check length
			if(strlen($un) > 25 || strlen($un) < 5) {
				array_push($this->error_array, Constants::$userNameCharacters);
				return;
			}

			//check if username exists
			$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username = '$un'");
			if(mysqli_num_rows($checkUsernameQuery) != 0) {
				array_push($this->error_array, Constants::$usernameTaken);
				return;
			}
 		}

		private function validateFirstName($fn) {
			// Check length
			if(strlen($fn) > 25 || strlen($fn) < 5) {
				array_push($this->error_array, Constants::$firstNameCharacters);
				return;
			}
		}

		private function validateLastName($ln) {
			// Check length
			if(strlen($ln) > 25 || strlen($ln) < 5) {
				array_push($this->error_array, Constants::$lastNameCharacters);
				return;
			}
		}

		private function validateEmails($em1, $em2) {
			if($em1 != $em2) {
				array_push($this->error_array, Constants::$emailDoNotMatch);
				return;
			}

			if(!filter_var($em1, FILTER_VALIDATE_EMAIL)) {
				array_push($this->error_array, Constants::$emailInvalid);
				return;
			}

			//Check that email hasn't already been used
			$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email = '$em1'");
			if(mysqli_num_rows($checkEmailQuery) != 0) {
				array_push($this->error_array, Constants::$emailTaken);
				return;
			}
		}

		private function validatePasswords($pw1, $pw2) {
			if($pw1 != $pw2) {
				array_push($this->error_array, Constants::$passwordsDoNotMatch);
				return;
			}

			if(preg_match('/[^A-Za-z0-9]/', $pw1)) {
				array_push($this->error_array, Constants::$passwordsNotAlphaNumeric);
				return;
			}

			// Check length
			if(strlen($pw1) > 30 || strlen($pw1) < 5) {
				array_push($this->error_array, Constants::$passwordCharacters);
				return;
			}
		}
	}

?>