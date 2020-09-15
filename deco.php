<?php
    include_once('assets/php/_includes.php');

    if (isset($user)){
        $user->deconnect();
    }
?>
<meta http-equiv="refresh" content="0;URL=<?=MAIN_PATH?>">