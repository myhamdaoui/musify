<?php

/**
 * page-name: Yourmusic Page
 * description: You can create | delete | view playlists and Add music to other playlists
 * @version: 1.0
 * @author: HAMDAOUI Mohammed-Yassin
 */
    
include("includes/includedFiles.php");
?>
<div class="playlistsContainer">
    <div class="gridViewContainer">
        <h2>Playlists</h2>
        <div class="buttonItems">
            <button class="button green" onclick="createPlaylist()">New Playlist</button>
        </div>

        <!-- Start Playlists List -->
        <?php
            $username = $userLoggedIn->getUsername();
            $playlistsQuery = mysqli_query($con, "SELECT * FROM playlists WHERE owner='$username'");

            if(!mysqli_num_rows($playlistsQuery)) {
                echo "<span class='noResults'>You don't have any playlists !</span>";
            }

            while($row = mysqli_fetch_array($playlistsQuery)) {
                $playlist = new Playlist($con, $row);
            ?>
                <div 
                    role="link"
                    tabindex="0"
                    onclick="openPage('playlist.php?id=' + '<?php echo $playlist->getId();?>')" 
                    class="gridViewItem"
                >
                    <div class="playlistImage">
                        <img src="assets/images/icons/playlist.png" alt="<?php echo $playlist->getName(); ?>">
                    </div>
                    <div class="gridViewInfo">
                        <?php echo $playlist->getName(); ?>
                    </div>
                </div>
            <?php
            }
        ?>
        <!-- End Playlists List -->
    </div>
</div>
