<?php

include("../../config.php");

if(isset($_POST['email']) && isset($_POST['username'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Check IF email IS NOT empty
    if($email == "") {
        echo "You must provide an email";
        exit();
    } 

    // Check IF Email is Valid
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email is invalid";
        exit();
    }

    $emailCheckQuery = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");
    if(mysqli_num_rows($emailCheckQuery) > 0) {
        echo "Email is already in use";
        exit();
    }

    // Update the email
    $updateQuery = mysqli_query($con, "UPDATE users SET email='$email' WHERE username='$username'");
    echo "Update successful";
}

?>