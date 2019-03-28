<!-- Start Menu Options -->
<div class="optionsMenu">
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
    <?php if(isset($playlistOption)) { ?>
    <span class="item removeFromPlaylist">Remove from playlist</span>
    <?php } ?>
</div>
<!-- End Menu Options -->