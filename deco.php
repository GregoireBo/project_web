<?php
    include_once('assets/php/_includes.php');

    if (isset($user)){
        $user->deconnect();
    }
    redirect(MAIN_PATH);
?>
