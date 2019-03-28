<?php

/**
 * page-name: Search Page
 * description: You can search for Albums | Songs | Artists, add Songs to playlists
 * @version: 1.0
 * @author: HAMDAOUI Mohammed-Yassin
 */

include("includes/includedFiles.php");
if(isset($_GET['term'])) {
    $term = urldecode($_GET['term']);
} else {
    $term = "";
}
?>

<div class="searchContainer">
    <h4>Search for an artist, album or song</h4>
    <input type="text" class="searchInput" value="<?php echo $term ?>" placeholder="Start typing...">
</div>

<script>
(function() {
	$(".searchInput").focus();
	var valInput = $(".searchInput").val();
	$(".searchInput").val("").val(valInput);
	$(".searchInput").keyup(function() {
		clearTimeout(timer);

		// code executed after 1 second(s)
		timer = setTimeout(function() {
			var val = $(".searchInput").val();
			openPage("search.php?term=" + val);
		}, 1000);
	});
}());


</script>

<?php 
if($term != "") {
?>

<!-- Start Track List -->
<div class="tracklistContainer borderBottom">
    <h2>Songs</h2>
	<ul class="tracklist">
		<?php
        $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10");
        if(!mysqli_num_rows($songsQuery)) {
            echo "<span class='noResults'>No songs found matching '" . $term . "' !</span>";
        }

		$i = 1;
		$songIdArray = array();
		while($row = mysqli_fetch_array($songsQuery)) {
            
            if($i > 10) {
                break;
            }

            array_push($songIdArray, $row['id']);

			// Create a Song Instance
			$song = new Song($con, $row['id']);
			$artist = $song->getArtist();
			?>

				<li class="tracklistRow" data="<?php echo $row['id']; ?>">
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
				var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
                tempPlaylist = JSON.parse(tempSongIds);
                
				$(".tracklist .play").click(function() {
					setTrack($(this).parent().parent().attr("data"), tempPlaylist, true);
				});
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

<!-- End Track List -->

<!-- Start Artists List -->
<div class="artistsContainer borderBottom">
    <h2>ARTISTS</h2>
    <?php 
    $artistsQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

    if(!mysqli_num_rows($artistsQuery)) {
        echo "<span class='noResults'>No artist found matching '" . $term . "' !</span>";
    }
	$i = 0;
    while($row = mysqli_fetch_array($artistsQuery)) {
		$artistFound = new Artist($con, $row['id']);
    ?>
        <div class="searchResultRow">
            <div class="artistName">
                <span role="link", tabindex="0", onclick="openPage('artist.php?id=' + '<?php echo $row["id"];?>')"><?php echo ++$i . ". " . $artistFound->getName();?></span>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<!-- End Artists List -->

<!-- Start Albums List -->
<div class="gridViewContainer borderBottom">
    <h2>Albums</h2>
	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%'");

		if(!mysqli_num_rows($albumQuery)) {
			echo "<span class='noResults'>No album found matching '" . $term . "' !</span>";
		}

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
<!-- End Albums List -->

<?php
}
?>
