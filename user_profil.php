<?php
    $page = 'index';
    
    include_once('assets/php/_includes.php');
    $profil_user = new cUser();
    if (isset($_GET['user_id']) && $profil_user->id_exist($_GET['user_id'])) {
      $profil_user->loadById($_GET['user_id']);
    }
    else if (isset($user) && $user->isConnected()){
      $profil_user = $user;
    }
    else redirect(MAIN_PATH);

include_once('assets/php/_includes.php');
$profil_user = new cUser();
if (isset($_GET['user_id']) && $profil_user->id_exist($_GET['user_id'])) {
  $profil_user->loadById($_GET['user_id']);
} else if (isset($user) && $user->isConnected()) {
  $profil_user = $user;
} else header("Location:" . MAIN_PATH);

if ($user->getId() != null && $user->getId() != $profil_user->getId()) {
  if (isset($_POST['btn_follow'])) {
    $user->follow($profil_user);
  }
  if (isset($_POST['btn_unfollow'])) {
    $user->unfollow($profil_user);
  }
}

$tabFollowing = $profil_user->getListFollowing(3);
$tabLikes = $profil_user->getListLikedArticles(3);

?>

<body>
  <div class="container mt-5">
    <div class="row gutters-sm">
      <div class="col-md-12 col-xl-4 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-column align-items-center text-center">
              <img src="<?php echo $profil_user->getProfilPictureLink(); ?>" alt="Admin" class="rounded-circle" width="150">
              <div class="mt-3">
                <h4><?php echo $profil_user->getPseudo(true, false); ?></h4>
                <p class="text-secondary mb-1"><?php echo $profil_user->getGroup()->getName(); ?></p>
                <p><i class="fas fa-circle text-<?php echo $profil_user->getActiveTextColor(); ?>"></i> <?php echo $profil_user->isActiveText(); ?></p>

                <form action="" method="post" enctype="multipart/form-data">
                  <?php
                  if ($user->getId() != null && $user->getId() != $profil_user->getId()) {
                    if ($user->isFollowing($profil_user)) {
                      echo '
                      <button class="btn bg-light text-dark btn-outline-primary border-danger" name="btn_unfollow">
                        Ne plus suivre <i class="fas fa-heart text-danger"></i>
                      </button> 
                    ';
                    } else {
                      echo '
                    <button class="btn btn-primary  text-dark bg-warning border-danger" name="btn_follow">
                      Suivre <i class="far fa-heart text-danger"></i>
                    </button> 
                  ';
                    }
                  }
                  ?>
                </form>
                <!--<button class="btn btn-outline-primary">Message</button>-->
              </div>
            </div>
          </div>
        </div>


        <!--follow-->
        <?php if (count($tabFollowing)) {
          echo '
        <div class="card mt-3">
          <div class="card-body ">
              <div class="text-center h5 font-weight-bold">Suit</div>
              <hr>
        ';
          foreach ($tabFollowing as $userFollow) {
            echo '
                  <div class="mt-3">
                    <img class="rounded-circle z-depth-0" width="50" src="' . $userFollow->getProfilPictureLink() . '" alt="image_user">
                    ' . $userFollow->getPseudo() . '
                  </div>
                  ';
          }
          if ($profil_user->countFollow() > 2) {
            echo '<a href=\'\' data-toggle="modal" data-target="#modalFollow"><div class="mt-3" >Voir toutes les personnes...</div></a>';
          }
          echo '
            </div>
        </div>
      ';
        } ?>

        <!--likes-->
        <?php if (count($tabLikes)) {
          echo '
     <div class="card mt-3">
       <div class="card-body ">
           <div class="text-center h5 font-weight-bold">Coups de cœurs&nbsp<i class="fas fa-heart"></i></div>
           <hr>
     ';
          for ($i = 0; $i <= count($tabLikes) - 1; $i++) {
            echo '
               <a href="' . $tabLikes[$i]->getLink() . '"><p class="card-title"><strong>' . $tabLikes[$i]->getTitle() . '</strong></p></a>
               <div class="mt-3">
                 <img class="rounded-circle z-depth-0" width="25" src="' . $tabLikes[$i]->getUser()->getProfilPictureLink() . '" alt="image_user"><small>
                 ' . $tabLikes[$i]->getUser()->getPseudo(false, false) . '</small>
               </div>
               
               ';
            if ($i !== count($tabLikes) - 1) {
              echo '<hr>';
            }
          }
          if ($profil_user->countLikes() > 1) {
            echo '<a href=\'\' data-toggle="modal" data-target="#modalLikes"><div class="mt-3" >Tout voir...</div></a>';
          }
          echo '
         </div>
     </div>
      ';
        } ?>
      </div>

      <!-- Modal auteurs -->
      <div class="modal fade" id="modalFollow" tabindex="-1" role="dialog" aria-labelledby="modalFollow" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Personnes suivies</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                <div class="row">
                  <?php
                  $tabFollowing = $profil_user->getListFollowing();
                  foreach ($tabFollowing as $userFollow) {
                    echo '
                 <div class="mt-3 col-6">
                   <img class="rounded-circle z-depth-0" width="50" src="' . $userFollow->getProfilPictureLink() . '" alt="image_user">
                   ' . $userFollow->getPseudo() . '
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

      <!-- Modal articles -->
      <div class="modal fade" id="modalLikes" tabindex="-1" role="dialog" aria-labelledby="modalLikes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Coups de cœurs</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                <div class="row">
                  <?php
                    $tabLikes = $profil_user->getListLikedArticles();
                  foreach ($tabLikes as $likedArticle) {
                  echo'
                    <div class="card card_article col-6" style="max-width: 18rem;">
                    <div class="card-header">
                      <img class="rounded-circle z-depth-0" width="40" src="' . $likedArticle->getUser()->getProfilPictureLink() . '" alt="image_user"><small>
                      ' . $likedArticle->getUser()->getPseudo(false, false) . '</small>
                    </div>
                      
                      <div class="card-body text-dark">
                        <a href="' . $likedArticle->getLink() . '"><p class="card-title"><strong>' . $likedArticle->getTitle() . '</strong></p></a>
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

      <!--articles-->
      <div class="col-md-12 col-xl-8">
        <div class="card mb-3">
          <div class="card-body">
            <h3>Articles</h3>
            <hr>
            <div class="row">
              <?php
              if (sizeof($profil_user->getArticles()) == 0) echo '<div class="ml-4">Cet utilisateur n\'a pas écrit d\'article</div>';
              foreach ($profil_user->getArticles() as $article) {
              ?>
                <div class="card card_article flex-md-row ml-4 mr-4 mt-4 box-shadow h-md-250">
                  <div class="card-body d-flex flex-column align-items-start col-8">
                    <h3 class="mb-0">
                      <a class="text-dark" href="<?php echo $article->getLink(); ?>"><?php echo $article->getTitle(); ?></a>
                    </h3>
                    <div class="mb-1 text-muted"><?php echo $article->getFormatedDate(); ?></div>
                    <p class="card-text mb-auto">
                      <?php echo $article->getShortDescript(); ?>
                    </p>
                    <a href="<?php echo $article->getLink(); ?>">Lire la suite...</a>
                  </div>
                  <div class="col-4 overflow-hidden">
                    <img class="card-img-right" src="<?php echo $article->getPictureLink(); ?>">
                  </div>
                </div>
              <?php
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<?php
include_once('assets/php/_footer.php');
?>

</html>