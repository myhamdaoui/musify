<?php 

/**
 * page-name: Album Page
 * description: You can view the current album, play songs OR add them to a playlist
 * @version: 1.0
 * @author: HAMDAOUI Mohammed-Yassin
 */

include("includes/includedFiles.php"); 

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$albumId = $_GET['id'];

	// Album instance
	$album = new Album($con, $albumId);

	// Artist instance
	$artist = $album->getArtist();

?>
<!-- START MAIN CONTENT -->

<!-- Start Album info -->
<div class="entityInfo">
	<div class="leftSection">
		<img src="<?php echo $album->getArtworkPath(); ?>">
	</div>

	<div class="rightSection">
		<h2><?php echo $album->getTitle(); ?></h2>
		<span>By <?php echo $artist->getName(); ?></span>
		<span><?php echo $album->getNumberOfSongs(); ?> Songs</span>
	</div>
</div>

<!-- End Album info -->

<!-- Start Album Track List -->
<div class="tracklistContainer">
	<ul class="tracklist">
		<?php
		$i = 1;
		$songIdArray = $album->getSongIds();
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

</script>

<!-- End Album Track List -->

<!-- END MAIN CONTENT -->
<?php
} else {
	header("Location: index.php");
}

?>
					
