<?php

include("../../config.php");

if(isset($_POST['playlistId']) && isset($_POST['songId'])) {
    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];

    // check if the song is not already in the playlist
    $query = mysqli_query($con, "SELECT * FROM playlistsongs WHERE songid='$songId' AND playlistid='$playlistId'");
    $query2 = mysqli_query($con, "SELECT MAX(playlistorder) FROM playlistsongs WHERE playlistid='$playlistId'");
    $playlistOrder = mysqli_fetch_array($query2)['MAX(playlistorder)'] + 1;
    if(!mysqli_num_rows($query)) {
        mysqli_query($con, "INSERT INTO playlistsongs VALUES('', '$songId', '$playlistId', '$playlistOrder')");
    }
}


?>