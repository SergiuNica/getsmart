<?php

if(!empty($site_user)){
   echo '<a href="?p='.$page.'&logout">Logout</a>';
    } else {
?>

<form method = "post" action ="?p=login">
    <input type="hidden" name="login">
    <div >
    Username: <input type="text" name="username">
    </div>
    <div >
    Password: <input type="password" name="pass">
    </div>
    <input type="submit" value="login">
</form>

<?php
}
?>