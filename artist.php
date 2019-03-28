<?php
    include("includes/includedFiles.php");


if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $artistId = $_GET['id'];
} else {
    header("Location: index.php");
}

$artist = new Artist($con, $artistId);
?>

<div class="entityInfo borderBottom">
    <div class="centerSection">
        <div class="artistInfo">
            <h1 class="artistName"><?php echo $artist->getName();?></h1>

            <div class="headerButtons">
                <button class="button green" onclick="playFirstSong()">Play</button>
            </div>
        </div>
    </div>
</div>

<!-- Start Track List -->
<div class="tracklistContainer borderBottom">
    <h2>Songs</h2>
	<ul class="tracklist">
		<?php
		$i = 1;
		$songIdArray = $artist->getSongIds();
		foreach($songIdArray as $songId) {
            if($i > 5) {
                break;
            }
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
						<img class="optionsButton" src="assets/images/icons/more.png">
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

<!-- End Track List -->

<div class="gridViewContainer">
    <h2>Albums</h2>
	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE artist='$artistId'");

		while($row = mysqli_fetch_array($albumQuery)) {
		?>
			<div class="gridViewItem">
				<span role="link" onclick="openPage('<?php echo 'album.php?id=' . $row['id']; ?>')">
					<img src="<?php echo $row['artworkPath']; ?>" alt="<?php echo $row['title']; ?>">
					<div class="gridViewInfo">
						<?php echo $row['title']; ?>
					</div>
				</span>
			</div>
		<?php
		}
	?>
</div>