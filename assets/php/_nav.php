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
function sendSearchReq(){
    var text = document.getElementById("search").value;
    document.location.href="<?=MAIN_PATH?>search/"+text;
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
                    <input class="form-control mr-sm-2" type="search" placeholder="Recherche" id="search"
                    name="search" id="search" aria-label="Recherche" value="<?=$textSearch?>" >
                    <button class="btn btn-outline-danger my-2 my-sm-0" type="submit" onclick="sendSearchReq()">
                        <i class="fas fa-search"></i>
                    </button>
                    </form>
                </li>
                <?php 
                
                    if (isset($user) && $user->isConnected()){
                        echo '
                        <li class="nav-item avatar dropleft">
                            <a class="nav-link dropdown-toggle text-secondary p-0" href="#" id="navDropNotifs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="far fa-bell pr-2"></i>
                            </a>
                            <a class="nav-link dropdown-toggle text-secondary p-0" href="#"id="navDropUser"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            
                                <img src="'.$user->getProfilPictureLink().'" class="rounded-circle z-depth-0"
                                height="35" alt="image utilisateur">
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navDropUser">
                                <a class="dropdown-item" href="'.MAIN_PATH.'profil">Profil</a>
                                <a class="dropdown-item" href="'.MAIN_PATH.'edit_profil">Modifier le profil</a>
                                <a class="dropdown-item" href="'.MAIN_PATH.'deco.php">Déconnexion</a>
                            </div>
                        </li>
                        ';
                    }
                    else{
                        echo '
                        <li class="nav-item">
                            <a class="nav-link p-1 ml-2 mt-1 text-light btn btn-danger" href="'.MAIN_PATH.'login">
                                Connexion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-1 ml-2 mt-1 text-light btn btn-danger" href="'.MAIN_PATH.'register">
                                Inscription
                            </a>
                        </li>
                        ';
                    }

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

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Articles aimés</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <?php
                            if (isset($user) && $user->isConnected()) {
                                // On récupère les articles
                                foreach ($tabLikes as $likedArticle) {
                            ?>
                                    <div class="card card_article col-5 offset-1 mb-3" style="height: 24rem">
                                        <!-- Card content -->
                                        <div class="card-header d-flex flex-row bg-transparent overflow-hidden">
                                            <!-- Avatar -->
                                            <img src="<?= $likedArticle->getUser()->getProfilPictureLink() ?>" class="rounded-circle mr-3" height="50px" width="50px" alt="avatar">
                                            <!-- Content -->
                                            <h5 class="card-title font-weight-bold mb-2"><?= $likedArticle->getTitle() ?></h5>
                                        </div>
                                        <!-- Card content -->
                                        <div class="card-body overflow-hidden">
                                            <!-- Text -->
                                            <p id="card-text collapseContent" class="card-text"><?= $likedArticle->getShortDescript() ?></p>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <small class="text-muted">
                                                <i class="far fa-user pr-2"></i><?= $likedArticle->getUser()->getPseudo(false, true) ?> |
                                                <i class="far fa-clock pr-2"></i><?= $likedArticle->getFormatedDate() ?>
                                            </small>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="<?= $likedArticle->getLink() ?>" class="btn btn-warning btn-sm">Lire la suite...</a>
                                        </div>
                                    </div>
                            <?php
                                }
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