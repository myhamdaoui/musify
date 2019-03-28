<?php 

/**
 * page-name: Playlist Page
 * description: You can play | delete songs from your playlist OR delete the playlist
 * @version: 1.0
 * @author: HAMDAOUI Mohammed-Yassin
 */

include("includes/includedFiles.php");

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$playlistId = $_GET['id'];

    $playlistQuery = mysqli_query($con, "SELECT * FROM Playlists WHERE id='$playlistId'");
	// Playlist instance
    $playlist = new Playlist($con, mysqli_fetch_array($playlistQuery));
	$owner = new User($con, $playlist->getOwner());
	
	// To show Delete option in the option menu
	$playlistOption = true;

?>
<!-- START MAIN CONTENT -->

<!-- Start Album info -->
<div class="entityInfo">
	<div class="leftSection">
        <div class="playlistImage">
            <img src="assets/images/icons/playlist.png" alt=<?php $playlist->getName();?>>
        </div>
	</div>

	<div class="rightSection">
		<h2><?php echo $playlist->getName(); ?></h2>
		<span>By <?php echo $playlist->getOwner(); ?></span>
        <span><?php echo $playlist->getNumberOfSongs(); ?> Songs</span>
        <button class="button" onclick="deletePlaylist(<?php echo $playlist->getId();?>)">Delete Playlist</button>
	</div>
</div>

<!-- End Album info -->

<!-- Start Album Track List -->
<div class="tracklistContainer">
	<ul class="tracklist">
		<?php
		$i = 1;
		$songIdArray = $playlist->getSongIds();
		foreach($songIdArray as $songId) {
			// Create a Song Instance
			$song = new Song($con, $songId);
			$artist = $song->getArtist();
			?>

				<li class="tracklistRow" data="<?php echo $songId; ?>">
					<div class="trackCount">
						<img class="play" src="assets/images/icons/play-white.png">
						<span class="trackNumber"><?php echo "$i." ?></span>
					</div>

					<div class="trackInfo">
						<span class="trackName"><?php echo $song->getTitle(); ?></span>

						<span class="artistName"><?php echo $artist->getName(); ?></span>
					</div>

					<div class="trackOptions">
						<img 
							class="optionsButton" 
							src="assets/images/icons/more.png"
						>
						<!-- Includ the menu options -->
						<?php include("includes/menuOption.php"); ?>
					</div>

					<div class="trackDuration">
						<span class="duration"><?php echo $song->getDuration(); ?></span>
					</div>
				</li>

			<?php
			$i++;
		}
		?>

		<script>
			$(document).ready(function() {
				$(".tracklist .play").click(function() {
					setTrack($(this).parent().parent().attr("data"), tempPlaylist, true);
				});
				var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
				tempPlaylist = JSON.parse(tempSongIds);

			});
		</script>
	</ul>
</div>

<script>
	$(".optionsButton").click(function() {
		$(".optionsMenu").hide();
		$(this).next().fadeIn();
	});

	$(".tracklistRow").mouseleave(function() {
		$(".optionsMenu").hide();
	});

	$(".playlist").on("change", function() {
		var songId = $(this).parent().parent().parent().attr("data");
		var playlistId = $(this).val();
		addToPlaylist(playlistId, songId, $(this));
	});

	$(".removeFromPlaylist").click(function() {
		var songId = $(this).parent().parent().parent().attr("data");
		var playlistId = '<?php echo $playlistId ?>';

		$.post("includes/handlers/ajax/removeFromPlaylist.php", {songId: songId, playlistId: playlistId}).done(function(data) {
			console.log(data + ": playlist remove ajax call");
			var oldScroll = $("body").scrollTop();
			openPage("playlist.php?id=" + playlistId);
			$("body").scrollTop(oldScroll);
		});
	});

</script>

<!-- End Album Track List -->

<!-- END MAIN CONTENT -->
<?php
} else {
	header("Location: index.php");
}

?>
					
