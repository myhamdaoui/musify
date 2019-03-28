<?php

include("../../config.php");

if(isset($_POST['oldPassword']) && isset($_POST['newPassword1']) && isset($_POST['newPassword2'])) {
    $oldPassword  = $_POST['oldPassword'];
    $newPassword1 = $_POST['newPassword1'];
    $newPassword2 = $_POST['newPassword2'];
    $username     = $_POST['username'];

    // Check IF email IS NOT empty
    if($oldPassword == "" || $newPassword1 == "" || $newPassword2 == "") {
        echo "Please fill in all fields";
        exit();
    } 

    $oldMD5 = md5($oldPassword);

    $pwdCheckQuery = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password='$oldMD5'");

    if(mysqli_num_rows($pwdCheckQuery) != 1) {
        echo "Password is incorrect";
        exit();
    }
    
    if($newPassword1 != $newPassword2) {
        echo "Your new passwords do not match";
        exit();
    }

    if(preg_match('/[^A-Za-z0-9]/', $newPassword1)) {
        echo "Your password must only contain letters and/or numbers";
        exit();
    }

    if(strlen($newPassword1) > 30 || strlen($newPassword1) < 5) {
        echo "Your password must be between 5 and 30 characters";
        exit();
    }

    $newMD5 = md5($newPassword1);

    // Update the email
    $updateQuery = mysqli_query($con, "UPDATE users SET password='$newMD5' WHERE username='$username'");
    echo "Update successful";
}

?>