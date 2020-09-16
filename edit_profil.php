<?php
    $page = 'edit_profil';
    include_once('assets/php/_includes.php');


if (!isset($user) || !$user->isConnected()) header("Location:".MAIN_PATH);

$textPass='';
$textGen='';
if (isset($_POST["pseudo"]) && isset($_POST["radio_pic"])){
    $pic = (int)str_replace('pic_','',$_POST["radio_pic"]);
    $response = $user->update($_POST["pseudo"], $pic);
    switch ($response) {
        case 'ok':
            redirect(MAIN_PATH.'profil');
            break;
        case 'pseudo_exist':
            $textGen = 'Le pseudo existe déjà';
            break;
        default:
            break;
    }
}

if (isset($_POST["current_pass"]) && isset($_POST["new_pass"]) && isset($_POST["new_pass2"])){
    $response = $user->changePassword($_POST["current_pass"],$_POST["new_pass"],$_POST["new_pass2"]);

    switch ($response) {
        case 'ok':
            $textPass = 'Mot de passe changé';
            break;
        case 'nok':
            $textPass = 'Erreur lors de la modification';
            break;
        case 'wrong_pass':
            $textPass = 'Mot de passe incorrect';
            break;
        case 'pass_not_same':
            $textPass = 'Les nouveaux mots de passe ne correspondent pas';
            break;
        default:
            break;
    }
}

?>



<body>
    <div class="container mt-5">
        <h2>Modifications générales</h2>
        <div class="text-danger"><?= $textGen ?></div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" name="pseudo" id="pseudo" value="<?= $user->getPseudo(false,false)?>"required>
            </div>
            <div class="form-group">
                <label for="radio_pic">Choisir une image de profil</label><br>
                <?php
                    foreach($user->getListPic() as $pic){
                        ?>
                        <label class="radio-inline">
                            <input type="radio" name="radio_pic" value="pic_<?= $pic?>"
                                <?php if ($user->getProfilPictureId() == $pic) echo 'checked';?>>
                                <img class="rounded-circle mr-5" width="100px"  
                                src="<?php echo MAIN_PATH;?>assets/img/profil_pic/<?= $pic?>.png"
                                >
                            </input>
                        </label>
                        <?php
                    }
                ?>
            </div>
            <button type="submit" class="btn btn-danger">Enregistrer</button>
        </form>
        
        <hr>
        <h2>Modifier le mot de passe</h2>
        <div class="text-danger"><?= $textPass ?></div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pseudo">Mot de passe actuel</label>
                <input type="password" class="form-control" name="current_pass" id="current_pass" required>
            </div>
            <div class="form-group">
                <label for="pseudo">Nouveau mot de passe</label>
                <input type="password" class="form-control" name="new_pass" id="new_pass" required>
            </div>
            <div class="form-group">
                <label for="pseudo">Répéter le nouveau mot de passe</label>
                <input type="password" class="form-control" name="new_pass2" id="new_pass2" required>
            </div>
            <button type="submit" class="btn btn-danger">Modifier</button>
        </form>
    </div>


</body>
</html>

<?php include_once('assets/php/_footer.php');?>