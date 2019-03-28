/**
 * name: Musify
 * description: JS for the logic of the plateform
 * author: HAMDAOUI Mohammed-Yassin
 * version: 1.0
 */

var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist    = [];

var audioElement;

var mouseDown = false;

var currentIndex = 0;

var repeat = false;

var shuffle = false;

var userLoggedIn;

var timer;
// format time
function formatTime(seconds) {
	var time = Math.round(seconds);
	var minutes = Math.floor(time / 60);
	var seconds = time % 60;

	var extraZero = (seconds < 10) ? '0' : '';

	return minutes + ':' + extraZero +  seconds;
}

// Audio class
function Audio() {
	this.currentlyPlaying;
	this.audio = document.createElement('audio');

	this.setTrack = function(track) {
		this.currentlyPlaying = track;
		this.audio.src = track.path;
	}

	this.play = function() {
		this.audio.play();
	}

	this.pause = function() {
		this.audio.pause();
	}

	this.setTime = function(seconds) {
		this.audio.currentTime = seconds;
	}

	this.setVolume = function(level) {
		this.audio.volume = level;
	}
}

// Update progress bar time
function updateTimeProgressBar(audio) {
	$(".remaining").text(formatTime(audio.duration - audio.currentTime));
	$(".current").text(formatTime(audio.currentTime));
	var progress = audio.currentTime/audio.duration * 100;
	$(".playbackBar .progress").css("width", progress + "%");
}


// Set song time from the offset of the mouse
function setTimeFromOffset(mouse, progressBar) {
	var duration = audioElement.audio.duration; 
	var timePointed = mouse.offsetX * duration / $(progressBar).width();
	audioElement.setTime(timePointed);
}

// Set volume level from the offset of the mouse
function setVolumeFromOffset(mouse, progressBar) {
	var levelPointed = mouse.offsetX / $(progressBar).width();
	audioElement.setVolume(levelPointed);
	$(".volumeBar .progress").css("width", levelPointed * 100 + "%");
}

function updateVolumeProgressBar(audio) {
	var volume = audio.volume * 100;
	$(".volumeBar .progress").css("width", volume + "%");
}

/*!
 *
 *
 */

function openPage(url) {
	if(timer != null) {
		clearTimeout(timer);
	} 
	if(url.indexOf("?") == -1) {
		url += "?";
	}
	var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	$("#mainContent").load(encodedUrl);
	$("body").scrollTop(0);
	history.pushState(null, null, url);
}

function playFirstSong() {
	setTrack(tempPlaylist[0], tempPlaylist, true);
}

function createPlaylist() {
	var alert = prompt("Pleaser enter the name of your playlist");
	if(alert != null) {
		$.post("includes/handlers/ajax/createPlaylist.php", {name: alert, username: userLoggedIn})
		.done(function(data) {
			openPage("yourMusic.php");
		});
	}
}

function deletePlaylist(playlistId) {
	var prompt = confirm("Are you sure you wan't to delete this playlist");
	if(prompt) {
		$.post("includes/handlers/ajax/deletePlaylist.php", {playlistId: playlistId})
		.done(function(data) {
			openPage("yourMusic.php");
		});
	}
}

function addToPlaylist(playlistId, songId, selectBox) {
	// check if the song is not already in the playlist
	$.post("includes/handlers/ajax/addToPlaylist.php", {playlistId: playlistId, songId: songId})
	.done(function(data) {
		selectBox.children(".addtoMsg").text('Add to playlist');
		selectBox.val(0);
		selectBox.parent().hide();
	});
}

function logout() {
	$.post("includes/handlers/ajax/logout.php").done(function(data) {
		location.reload();
	});
}

function updateEmail(emailClass) {
	var emailValue = $("." + emailClass).val();
	$.post("includes/handlers/ajax/updateEmail.php", {email: emailValue, username: userLoggedIn})
	.done(function(response) {
		console.log(response);
		$("." + emailClass).nextAll(".message").text(response);
	});
} 

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
	var oldPassword = $("." + oldPasswordClass).val();
	var newPassword1 = $("." + newPasswordClass1).val();
	var newPassword2 = $("." + newPasswordClass2).val();

	$.post(
		"includes/handlers/ajax/updatePassword.php",
		{
			oldPassword: oldPassword,
			newPassword1: newPassword1,
			newPassword2: newPassword2,
			username: userLoggedIn
		})
	.done(function(response) {
		console.log(response);
		$("." + oldPasswordClass).nextAll(".message").text(response);
	});
} 