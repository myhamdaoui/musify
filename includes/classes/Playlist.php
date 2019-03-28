<?php
class Playlist {
    private $con;
    private $id;
    private $name;
    private $owner;

    public function __construct($con, $data) {
        $this->con = $con;
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->owner = $data['owner'];
    }

    public function getName() {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getNumberOfSongs() {
        $query = mysqli_query($this->con, "SELECT count(*) FROM playlistsongs WHERE playlistid='$this->id'");
        return mysqli_fetch_array($query)['count(*)'];
    }

    public function getSongIds() {
        $query = mysqli_query($this->con, "SELECT songId FROM playlistsongs WHERE playlistid='$this->id' ORDER BY playlistOrder ASC");
        $songIds = array();
        while($row = mysqli_fetch_array($query)) {
            array_push($songIds, $row['songId']);
        }

        return $songIds;
    }

    public static function getPlaylistsDropdown($con, $username) {
        $dropdown = '<select class="item playlist">
                        <option value="0" class="addtoMsg">Add to playlist</option>';
        $query = mysqli_query($con, "SELECT id, name FROM playlists WHERE owner='$username'");
        while($row = mysqli_fetch_array($query)) {
            $id = $row['id'];
            $name = $row['name'];
            $dropdown = $dropdown . "<option value='$id'>$name</option>";
        }


        return $dropdown . "</select>";
    }
}
?>