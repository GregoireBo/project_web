<?php
$active = 'active';
$iPage = isset($page);

$currentIndex = '';
$currentAdmin = '';
$currentArticles = '';
$currentCreateArticles = '';

$tabLikes = $user->getListLikedArticles();

if ($iPage && $page == 'index') $currentIndex = $active;
if ($iPage && $page == 'admin') $currentAdmin = $active;
if ($iPage && $page == 'articles') $currentArticles = $active;
if ($iPage && $page == 'create_article') $currentCreateArticles = $active;

if (!isset($textSearch)) $textSearch = '';

?>
<script>
    function sendSearchReq() {
        var text = document.getElementById("search").value;
        document.location.href = "<?= MAIN_PATH ?>search/" + text;
    }
</script>

<header>
    <nav class="mb-1 navbar navbar-expand-lg navbar-light bg-warning">
        <a class="navbar-brand" href="<?php echo MAIN_PATH; ?>">
            <img src="<?php echo MAIN_PATH; ?>assets/img/logo.png" width="30" height="30" class="d-inline-block align-top mr-2" alt="logo carotte">
            Santé et saveurs
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="dropdown">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav navbar-nav mr-auto">
                <li class="nav-item <?php echo $currentIndex; ?>">
                    <a class="nav-link" href="<?php echo MAIN_PATH ?>">
                        Accueil
                    </a>
                </li>
                <?php if (isset($user) && $user->canCreateArticle()) echo '
                <li class="nav-item dropdown ' . $currentCreateArticles . '">
                    <a class="nav-link" href="' . MAIN_PATH . 'create_article">
                        Ajouter un article
                    </a>
                </li>
                '; ?>
                <?php if (isset($user) && $user->havePerm('ADMIN_PANEL')) echo '
                <li class="nav-item dropdown ' . $currentAdmin . '">
                    <a class="nav-link dropdown-toggle" href="#" id="navDropAdmin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Admin 
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navDropAdmin">
                        <a class="dropdown-item" href="' . MAIN_PATH . 'admin_user">Utilisateurs</a>
                    </div>
                </li>
                '; ?>
            </ul>
            <ul class="nav navbar-nav ml-auto nav-flex-icons">
                <li class="nav-item avatar dropleft mr-5">
                    <form onsubmit="sendSearchReq();return false;" class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Recherche" id="search" name="search" id="search" aria-label="Recherche" value="<?= $textSearch ?>">
                        <button class="btn btn-outline-danger my-2 my-sm-0" type="submit" onclick="sendSearchReq()">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </li>
                <?php
                if (isset($user) && $user->isConnected()) {
                    $countArticlesFromFollowedUsers = $user->countArticlesFromFollowedUsers();
                ?>
                    <li class="nav-item avatar dropleft mr-5">
                        <button class="btn btn-outline-danger my-2 my-sm-0 my-2 my-lg-0" id="navDropArticlesFromFollowedUsers" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="submit">
                            <?php
                            if ($countArticlesFromFollowedUsers) {
                                $countNotifText = $countArticlesFromFollowedUsers;

                                if ($countArticlesFromFollowedUsers > 4) {
                                    $countNotifText = '...';
                                }
                                echo '<i class="fas fa-bell"></i>&nbsp&nbsp<span class="badge badge-light">' . $countNotifText . '</span>';
                            }
                            ?>
                        </button>

                        <div class="dropdown-menu" aria-labelledby="navDropArticlesFromFollowedUsers">
                            <?php
                            if ($countArticlesFromFollowedUsers) {
                                $tabArticlesFromFollowedUsers = $user->getListArticlesFromFollowedUsers(2, 5);
                                for ($i = 0; $i <= count($tabArticlesFromFollowedUsers) - 1; $i++) {
                                    $articleFromUser = $tabArticlesFromFollowedUsers[$i];
                                    echo '
                                    <a href="' . $articleFromUser->getLink() . '" class="dropdown-item"><p class="card-title"><strong>' . $articleFromUser->getTitle() . '</strong></p>
                                        <div class="mt-3">
                                        <img class="rounded-circle z-depth-0" width="25" src="' . $articleFromUser->getUser()->getProfilPictureLink() . '" alt="image_user"><small>
                                        ' . $articleFromUser->getUser()->getPseudo(false, false).' | <i class="far fa-clock"></i>'.$articleFromUser->getFormatedDate().'</small>
                                        
                                        </div>
                                    </a>
                                    ';

                                    if ($i !== count($tabArticlesFromFollowedUsers) - 1) {
                                        echo '<hr>';
                                    }
                                }
                                if ($countArticlesFromFollowedUsers > 1) {
                                    echo '<a href=\'\' data-backdrop="false" data-toggle="modal" data-target="#modalArticlesFromFollowed"><div class="mt-3 dropdown-item" >Tout voir...</div></a>';
                                }
                            }
                            ?>
                        </div>
                    </li>
                    <li class="nav-item avatar dropleft">
                        <a class="nav-link dropdown-toggle text-secondary p-0" href="#" id="navDropUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <img src="<?= $user->getProfilPictureLink() ?>" class="rounded-circle z-depth-0" height="35" alt="image utilisateur">
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navDropUser">
                            <a class="dropdown-item" href="<?= MAIN_PATH ?>profil">Profil</a>
                            <a class="dropdown-item" href="<?= MAIN_PATH ?>edit_profil">Modifier le profil</a>
                            <a class="dropdown-item" href="<?= MAIN_PATH ?>deco.php">Déconnexion</a>
                        </div>
                    </li>
                <?php
                } else {
                ?>
                    <li class="nav-item">
                        <a class="nav-link p-1 ml-2 mt-1 text-light btn btn-danger" href="<?= MAIN_PATH ?>login">
                            Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-1 ml-2 mt-1 text-light btn btn-danger" href="<?= MAIN_PATH ?>register">
                            Inscription
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </nav>

    <!-- Modal articles -->
    <div class="modal fade" id="modalArticlesFromFollowed" tabindex="-1" role="dialog" aria-labelledby="modalArticlesFromFollowed" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Articles récents de vos auteurs préférés</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <?php
                            $tabArticlesFromFollowedUsers = $user->getListArticlesFromFollowedUsers(2, 5);
                            foreach ($tabArticlesFromFollowedUsers as $articleFromFollowed) {
                                echo '
                                <div class="card card_article col-6" style="max-width: 18rem;">
                                    <div class="card-header bg-transparent">
                                        <img class="rounded-circle z-depth-0" width="40" src="' . $articleFromFollowed->getUser()->getProfilPictureLink() . '" alt="image_user"><small>
                                        ' . $articleFromFollowed->getUser()->getPseudo(false, false) . '</small>
                                    </div>
                
                                    <div class="card-body text-dark">
                                        <a href="' . $articleFromFollowed->getLink() . '"><p class="card-title"><strong>' . $articleFromFollowed->getTitle() . '</strong></p></a>
                                        <p class="card-text"></p>
                                    </div>
                                </div>
                                ';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

</header>