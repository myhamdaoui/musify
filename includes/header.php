<?php
include "includes/config.php";
include "includes/classes/User.php";
include "includes/classes/Artist.php";
include "includes/classes/Album.php";
include "includes/classes/Song.php";
include "includes/classes/Playlist.php";

if(isset($_SESSION['userLoggedIn'])) {
	$userLoggedIn = new User($con, $_SESSION['userLoggedIn']);

	echo "<script>userLoggedIn = '" . $userLoggedIn->getUsername() . "';</script>";
} else {
	header("Location: register.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Musify - Listen Free Music</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	

	<!-- CSS Custom File -->
	<link rel="stylesheet" href="assets/css/style.css">

	<!-- jQuery JS Library -->
	<script src="assets/js/jquery-3.3.1.js"></script>

	<!-- Custom JS File -->
	<script src="assets/js/script.js"></script>
</head>
<body>
	<div id="mainContainer">
		<div id="topContainer">
			<?php include("includes/navbarContainer.php") ?>
			<div id="mainViewContainer">
				<div id="mainContent">