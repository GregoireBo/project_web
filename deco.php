<?php
    include_once('assets/php/_includes.php');

    if (isset($user)){
        $user->deconnect();
    }
    else header("Location:".MAIN_PATH);
?>