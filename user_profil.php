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

    if ($user->getId() != null && $user->getId() != $profil_user->getId()){
      if (isset($_POST['btn_follow'])){
        $user->follow($profil_user);
      }
      if (isset($_POST['btn_unfollow'])){
        $user->unfollow($profil_user);
      }
    }

    $tabFollowing = $profil_user->getListFollowing(5);

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
              <h4><?php echo $profil_user->getPseudo(true,false); ?></h4>
              <p class="text-secondary mb-1"><?php echo $profil_user->getGroup()->getName(); ?></p>
              <p><i class="fas fa-circle text-<?php echo $profil_user->getActiveTextColor();?>"></i> <?php echo $profil_user->isActiveText();?></p>
              
              <form action="" method="post" enctype="multipart/form-data">
              <?php
              if ($user->getId() != null && $user->getId() != $profil_user->getId()){
                  if($user->isFollowing($profil_user)){
                    echo '
                      <button class="btn bg-light text-dark btn-outline-primary border-danger" name="btn_unfollow">
                        Ne plus suivre <i class="fas fa-heart text-danger"></i>
                      </button> 
                    ';
                  }
                  else{
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
      <?php if (count($tabFollowing)){
        echo '
        <div class="card mt-3">
          <div class="card-body ">
              <div class="text-center h5 font-weight-bold">Suit</div>
              <hr>
        ';
                foreach ($tabFollowing as $userFollow) {
                  echo '
                  <div class="mt-3">
                    <img class="rounded-circle z-depth-0" width="50" src="'.$userFollow->getProfilPictureLink().'" alt="image_user">
                    '.$userFollow->getPseudo().'
                  </div>
                  ';
                }
                if ($profil_user->countFollow() > 5){
                  echo '<div class="mt-3">Et d\'autres...</div>';
                }
        echo '
            </div>
        </div>
      ';}?>
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
                      <a class="text-dark" href="<?php echo $article->getLink();?>"><?php echo $article->getTitle();?></a>
                    </h3>
                    <div class="mb-1 text-muted"><?php echo $article->getFormatedDate();?></div>
                    <p class="card-text mb-auto">
                      <?php echo $article->getShortDescript();?>
                    </p>
                    <a href="<?php echo $article->getLink();?>">Lire la suite...</a>
                  </div>
                  <div class="col-4 overflow-hidden">
                    <img class="card-img-right" src="<?php echo $article->getPictureLink();?>">
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

