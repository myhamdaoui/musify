<?php 

// Query to retreive 10 random songs from the DB
$songQuery = mysqli_query($con, "SELECT * FROM songs ORDER BY RAND() LIMIT 10");

// Result array
$resultArray = array();

// Loop over the result of the query
while($row = mysqli_fetch_array($songQuery)) {
	// push IDs into the array
	array_push($resultArray, $row['id']);
}

// Convert that array into a json array ("String")
$jsonArray = json_encode($resultArray);
?>

<script>
$(document).ready(function() {
	// Retreive the parsed array of ids into a JS array
	var newPlaylist = '<?php echo $jsonArray; ?>';
	newPlaylist = JSON.parse(newPlaylist);

	// Create Audio Object
	audioElement = new Audio();

	// Set the track to the first song in the playlist
	// Set autoplay to false
	setTrack(newPlaylist[0], newPlaylist, false);

	// Prevent selection on the playing bar [to improve the UX]
	$("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(event) {
		event.preventDefault();
	});

	// Play button click listener
	$(".controlButton.play").click(function() {
		playSong();
	});

	// Pause button click listener
	$(".controlButton.pause").click(function() {
		pauseSong();
	});

	// New button click listener
	$(".controlButton.next").click(function() {
		nextSong();
	});

	// Repeat Button
	$(".controlButton.repeat").click(function() {
		setRepeat();
	});

	$(".controlButton.previous").click(function() {
		prevSong();
	});

	$(".controlButton.volume").click(function() {
		setMute();
	});

	$(".controlButton.shuffle").click(function() {
		setShuffle();
	});

	// Music listeners
	audioElement.audio.addEventListener("canplay", function() {
		$(".remaining").text(formatTime(this.duration));
		$(".current").text(formatTime(this.currentTime));
	});

	audioElement.audio.addEventListener("timeupdate", function() {
		if(this.duration) {
			updateTimeProgressBar(this);
		}
	});

	// Volume listener
	audioElement.audio.addEventListener("volumechange", function() {
		updateVolumeProgressBar(this);
	});

	// Song end Listenr
	audioElement.audio.addEventListener("ended", function() {
		nextSong();
	});

	// Mouse listeners to move the progress of the music
	$(".playbackBar .progressBar").mousedown(function() {
		mouseDown = true;
		setTimeFromOffset(event, this);
	});

	$(".playbackBar .progressBar").mousemove(function(event) {
		if(mouseDown) {
			// Set time of song depending on position of mouse
			setTimeFromOffset(event, this);
		}
	});

	$(".playbackBar .progressBar").mouseup(function(event) {
		// Set time of song depending on position of mouse
		mouseDown = false;
	});


	/*!
	 * Mouse moves the volume bar
	 */

	$(".volumeBar .progressBar").mousedown(function() {
		mouseDown = true;
		setVolumeFromOffset(event, this);
	});

	$(".volumeBar .progressBar").mousemove(function(event) {
		if(mouseDown) {
			// Set time of song depending on position of mouse
			setVolumeFromOffset(event, this);
		}
	});

	$(".volumeBar .progressBar").mouseup(function(event) {
		// Set time of song depending on position of mouse
		mouseDown = false;
	});



	// Set the volume when page loads
	updateVolumeProgressBar(audioElement.audio);

	// mute the sound
	function setMute() {
		audioElement.audio.muted = !audioElement.audio.muted;
		var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png"
		$(".volume img").attr("src", "assets/images/icons/" + imageName);
	}

	// shuffle
	function setShuffle() {
		shuffle = !shuffle;
		var imageName = shuffle ? "shuffle-active.png" : "shuffle.png"
		$(".shuffle img").attr("src", "assets/images/icons/" + imageName);

		// shuffle logic
		if(shuffle) {
			// randomize playlist
			shuffleArray(shufflePlaylist);
			currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
		} else {
			// go back to regular playlist
			currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
		}
	}

	// suffle array function
	function shuffleArray(a) {
		var j, x, i;
		for(i = a.length; i; i--) {
			j = Math.floor(Math.random() * i);
			x = a[i - 1];
			a[i - 1] = a[j];
			a[j] = x;
		}
	}

	// Go to the previous song
	function prevSong() {
		if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
			audioElement.setTime(0);
		} else {
			setTrack(currentPlaylist[--currentIndex], currentPlaylist, true);
		}
	}

	// Go to the next song
	function nextSong() {
		if(repeat) {
			audioElement.setTime(0);
			playSong();
			return;
		}
		if(currentIndex == currentPlaylist.length - 1) {
			currentIndex = 0;
		} else {
			currentIndex++;
		}
		var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
		setTrack(trackToPlay, currentPlaylist, true);
	}

	// Set Repeat Mode
	function setRepeat() {
		repeat = !repeat;
		var imageName = repeat ? "repeat-active.png" : "repeat.png";
		$(".repeat img").attr("src", "assets/images/icons/" + imageName);
	}

	// Set a new Song to Play
	function setTrack(trackId, newPlaylist, play) {
		"use strict"
		if(newPlaylist != currentPlaylist) {
			currentPlaylist = newPlaylist;
			shufflePlaylist = currentPlaylist.slice();
			shuffleArray(shufflePlaylist);
		}

		if(shuffle) {
			currentIndex = shufflePlaylist.indexOf(trackId);
		} else {
			currentIndex = currentPlaylist.indexOf(trackId);     
		}
		// Pause the current song
		pauseSong();
		// Ajax call to get song info from DB
		$.post("includes/handlers/ajax/getSongJson.php", {songId: trackId}, function(data) {
			// parse the JSON Object
			var track = JSON.parse(data);
			//currentIndex = currentPlaylist.indexOf(trackId);

			// Ajax call to get Artist info from DB
			$.post("includes/handlers/ajax/getArtistJson.php", {artistId: track.artist}, function(data) {
				var artist = JSON.parse(data);
				$(".trackInfo .artistName span").text(artist.name);
			});

			// Ajax call to get Album Info from DB
			$.post("includes/handlers/ajax/getAlbumJson.php", {albumId: track.album}, function(data) {
				var album = JSON.parse(data);
				$(".albumArtwork").attr("src", album.artworkPath);
			});

			// Set the src of the audio Element to the current track
			audioElement.setTrack(track);

			// Set the title of track in the now playing bar 
			$(".trackName span").text(track.title);

			if(play) {
				playSong();
			}

			console.log(audioElement.currentlyPlaying.title + ": " + audioElement.currentlyPlaying.id);
			console.log('**current: '  + currentPlaylist);
			console.log('**shuffled: ' + shufflePlaylist);
			console.log("\n");
		});
	}

	/*!
	* Play & Pause User Click Listener
	*/

	function playSong() {
		if(audioElement.audio.currentTime == 0) {
			$.post("includes/handlers/ajax/updatePlays.php",  {songId: audioElement.currentlyPlaying.id});
		}

		// toggle play & pause buttons
		$(".play").hide();
		$(".pause").show();

		// play the music
		audioElement.play();
	}

	function pauseSong() {
		// toggle play & pause buttons
		$(".pause").hide();
		$(".play").show();

		// pause the music
		audioElement.pause();
	}


	// EXPOSE TO THE GLOBAL SCOPE [WINDOW]
	window.setTrack = setTrack;
});

</script>

<div id="nowPlayingBarContainer">
	<div id="nowPlayingBar">
		<div id="nowPlayingLeft">
			<div class="content">
				<span role="link" tabindex="0" onclick="openPage('album.php?id=' + audioElement.currentlyPlaying.album)" class="albumLink">
					<img class="albumArtwork" src="assets/images/artwork/clearday.jpg" alt="album-img">
				</span>

				<div class="trackInfo">
					<span role="link" tabindex="0" onclick="openPage('album.php?id=' + audioElement.currentlyPlaying.album)" class="trackName">
						<span></span>
					</span>
					<span class="artistName">
						<span role="link" tabindex="0" onclick="openPage('artist.php?id=' + audioElement.currentlyPlaying.artist)"></span>
					</span>
				</div>
			</div>
		</div>
		<div id="nowPlayingCenter">
			<div class="content playerControls">
				<div class="buttons">
					<button class="controlButton shuffle" title="Shuffle">
						<img src="assets/images/icons/shuffle.png" alt="shuffle">
					</button>

					<button class="controlButton previous" title="Previous">
						<img src="assets/images/icons/previous.png" alt="previous">
					</button>

					<button class="controlButton play" title="Play">
						<img src="assets/images/icons/play.png" alt="play">
					</button>

					<button class="controlButton pause" title="Pause" style="display: none">
						<img src="assets/images/icons/pause.png" alt="pause">
					</button>

					<button class="controlButton next" title="Next">
						<img src="assets/images/icons/next.png" alt="next">
					</button>

					<button class="controlButton repeat" title="Repeat">
						<img src="assets/images/icons/repeat.png" alt="repeat">
					</button>
				</div>

				<!-- Prograss bar -->

				<div class="playbackBar">
					<span class="progressTime current"></span>
					<div class="progressBar">
						<div class="progressBarBg">
							<div class="progress">

							</div>
						</div>
					</div>
					<span class="progressTime remaining"></span>
				</div>
			</div>
		</div>
		<div id="nowPlayingRight">
			<div class="volumeBar">
				<button class="controlButton volume" title="Volume button">
					<img src="assets/images/icons/volume.png" alt="volume">
				</button>
				<div class="progressBar">
					<div class="progressBarBg">
						<div class="progress">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>