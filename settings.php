<?php

/**
 * page-name: Settings Page
 * description: You can go to the update page OR log out from your account
 * @version: 1.0
 * @author: HAMDAOUI Mohammed-Yassin
 */

include("includes/includedFiles.php");
?>

<div class="entityInfo">
    <div class="centerSection">
        <div class="userInfo">
            <h1><?php echo $userLoggedIn->getFullName(); ?></h1>
        </div>
    </div>

    <div class="buttonItems">
        <button 
            class="button"
            onclick="openPage('updateDetaills.php')"
        >User details</button>
        <button 
            class="button"
            onclick="logout()"
        >Log out</button>
    </div>
</div>