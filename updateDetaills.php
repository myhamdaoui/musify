<?php

/**
 * page-name: Update detaills Page
 * description: You can update email and password
 * @version: 1.0
 * @author: HAMDAOUI Mohammed-Yassin
 */

include("includes/includedFiles.php");

?>

<div class="userDetails">
    <div class="container borderBottom">
        <h2>Email</h2>
        <input 
            type="email" 
            class="email" 
            name="email" 
            placeholder="Email adress..." 
            value="<?php echo $userLoggedIn->getEmail();?>"
            autocomplete="off"
        >

        <span class="message"></span>
        <button 
            class="button"
            onclick="updateEmail('email')"
        >Save</button>
    </div>

    <div class="container borderBottom">
        <h2>Password</h2>
        <input 
            type="password" 
            class="oldpassword" 
            name="oldpassword" 
            placeholder="Current password..." 
            autocomplete="new-password"
        >
        <input 
            type="password" 
            class="newpassword1" 
            name="newpassword1" 
            placeholder="new password..." 
        >

        <input 
            type="password" 
            class="newpassword2" 
            name="newpassword2" 
            placeholder="new password..." 
        >

        <span class="message"></span>
        <button 
            class="button"
            onclick="updatePassword('oldpassword', 'newpassword1', 'newpassword2')"
        >Save</button>
    </div>
</div>