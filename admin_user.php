<?php
    $page = 'admin';
    
    include_once('assets/php/_includes.php');
    if (!isset($user) || !$user->havePerm('ADMIN_PANEL')) header("Location:".MAIN_PATH);

    if(isset($_GET['a'])){
        switch ($_GET['a']) {

            case 'switchActive':
                if (isset($_GET['user_id'])){
                    $userToSwitch = new cUser();
                    $userToSwitch->loadById($_GET['user_id']);
                    $userToSwitch->switchActive();
                }
                break;
            case 'changeGrp':
                if (isset($_GET['grpSelect']) && isset($_GET['user_id'])){
                    $userToSwitch = new cUser();
                    $userToSwitch->loadById($_GET['user_id']);
                    $userToSwitch->changeGrp($_GET['grpSelect']);
                }
                break;
            
            default:
                break;
        }
        header("Location: ?");
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
                                <form class="form-select" method="get">
                                <input name="a" class="changeGrp" value="changeGrp">
                                <input name="user_id" class="changeGrp" value="'.$userFor->getId().'">
                                    '.$groupList->getSelect($userFor->getGroup()->getId()).'
                                </form>
                            </td>
                            <td class="align-middle"><i class="fas fa-circle text-'.$userFor->getActiveTextColor().'"></i> '.$userFor->isActiveText().'</td>
                            <td class="align-middle">
                                <!--<a href="#" class="settings h3" title="Settings" data-toggle="tooltip"><i class="fas fa-cog"></i></a>-->
                                <a href="?a=switchActive&user_id='.$userFor->getId().'" class="delete text-'.$icon_color.'" title="'.$icon_text.'" data-toggle="tooltip">
                                    <span class="h3"><i class="fas '.$icon.'"></i> </span>
                                </a>
                            </td>
                        </tr>
                        ';
                    }
                ?>
                <!--
                <tr>
                    <td class="align-middle">4</td>
                    <td class="align-middle">Yannis</td>
                    <td class="align-middle">
                        <select class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                            <option value="0">Aucun</option>
                            <option value="1">Auteur</option>
                            <option value="2">Admin</option>
                        </select>
                    </td>
                    <td class="align-middle"><i class="fas fa-circle text-warning"></i> Inactif</td>
                    <td class="align-middle">
                        <a href="#" class="settings h3" title="Settings" data-toggle="tooltip"><i class="fas fa-cog"></i></a>
                        <a href="#" class="delete h3 text-danger" title="Delete" data-toggle="tooltip"><i class="fas fa-times-circle"></i></a>
                    </td>
                </tr>
                -->
            </tbody>
        </table>
    </div>
  </div>
</body>
</html>