<?php
    $page = 'admin';
    
    include_once('assets/php/_includes.php');
    if (!isset($user) || !$user->havePerm('ADMIN_PANEL')) redirect(MAIN_PATH);

    if(isset($_POST['a'])){
        switch ($_POST['a']) {

            case 'switchActive':
                if (isset($_POST['user_id'])){
                    $userToSwitch = new cUser();
                    $userToSwitch->loadById($_POST['user_id']);
                    $userToSwitch->switchActive();
                }
                break;
            case 'changeGrp':
                if (isset($_POST['grpSelect']) && isset($_POST['user_id'])){
                    $userToSwitch = new cUser();
                    $userToSwitch->loadById($_POST['user_id']);
                    $userToSwitch->changeGrp($_POST['grpSelect']);
                }
                break;
            
            default:
                break;
        }
    }


    $userList = new cUser_List();
    $userList->loadAll();

    $groupList = new cGroup_List();
    $groupList->loadAll();
?>


<body>
<div class="container">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-5">
                    <h2>Gestion des utilisateurs</h2>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pseudo</th>						
                    <th>Groupe</th>
                    <th>État du compte</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($userList->getUsers() as $userFor) {
                        if ($userFor->isActive()) {
                            $icon = 'fa-times-circle';
                            $icon_color = 'danger';
                            $icon_text = 'Désactiver';
                        }
                        else {
                            $icon = 'fa-check-circle';
                            $icon_color = 'success';
                            $icon_text = 'Activer';
                        }
                        echo'
                        <tr>
                            <td class="align-middle">'.$userFor->getId().'</td>
                            <td class="align-middle"><img src="'.$userFor->getProfilPictureLink().'" class="rounded-circle z-depth-0 mr-2" alt="avatar image" height="35">'.$userFor->getPseudo().'</td>
                            <td class="align-middle">
                                <form class="form-select" method="post">
                                    <input name="a" class="changeGrp" value="changeGrp">
                                    <input name="user_id" class="changeGrp" value="'.$userFor->getId().'">
                                        '.$groupList->getSelect($userFor->getGroup()->getId()).'
                                </form>
                            </td>
                            <td class="align-middle"><i class="fas fa-circle text-'.$userFor->getActiveTextColor().'"></i> '.$userFor->isActiveText().'</td>
                            <td class="align-middle">
                                <form class="form-select" method="post">
                                    <input name="a" class="switchActive d-none" value="switchActive">
                                    <input name="user_id" class="switchActive d-none" value="'.$userFor->getId().'">
                                    <button class="btn  rounded-circle text-'.$icon_color.'" type="submit">
                                        <span class="h3"><i class="fas '.$icon.'"></i> </span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </div>
  </div>
</body>
</html>