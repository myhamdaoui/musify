<div id="navbarContainer">
	<nav class="navbar">
		<!-- LOGO -->
		<span role="link" tabindex="0" onclick="openPage('index.php')" class="logo navItemLink">
			<img src="assets/images/icons/logo.png" alt="logo-img">
		</span>

		<!-- SEARCH -->
		<div class="group search">
			<div class="navItem"> 
				<span role="link" tabindex="0" onclick="openPage('search.php')" class="navItemLink">
					search
				</span>
				<img class="serach-icon" src="assets/images/icons/search.png" alt="search-icon">
			</div>
		</div>

		<!-- MENU -->
		<div class="group">
			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">
					Browse
				</span>
			</div>

			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('yourmusic.php')" class="navItemLink">
					Your Music
				</span>
			</div>

			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('settings.php')" class="navItemLink">
					<?php echo $userLoggedIn->getUsername();?>
				</span>
			</div>										
		</div>
	</nav>
</div>