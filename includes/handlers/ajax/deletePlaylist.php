<?php
include("../../config.php");

if(isset($_POST['playlistId'])) {
    $playlistId = $_POST['playlistId'];
    mysqli_query($con, "DELETE FROM playlistsongs WHERE playlistId='$playlistId'");
    mysqli_query($con, "DELETE FROM playlists WHERE id='$playlistId'");
} else {
    echo "No id passed into parameters";
}
?>