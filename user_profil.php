<?php
    $page = 'index';
    
    include_once('assets/php/_includes.php');
    $profile_user = new cUser();
    if (isset($_GET['user_id']) && $profile_user->id_exist($_GET['user_id'])) {
      $profile_user->loadById($_GET['user_id']);
    }
    else if (isset($user) && $user->isConnected()){
      $profile_user = $user;
    }
    else header("Location:".MAIN_PATH);

?>


<body>
<div class="container mt-5">
  <div class="row gutters-sm">
    <div class="col-md-12 col-xl-4 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-column align-items-center text-center"> 
            <img src="<?php echo $profile_user->getProfilPictureLink(); ?>" alt="Admin" class="rounded-circle" width="150">
            <div class="mt-3">
              <h4><?php echo $profile_user->getPseudo(true,false); ?></h4>
              <p class="text-secondary mb-1"><?php echo $profile_user->getGroup()->getName(); ?></p>
              <p><i class="fas fa-circle text-<?php echo $profile_user->getActiveTextColor();?>"></i> <?php echo $profile_user->isActiveText();?></p>
              <button class="btn btn-primary">Follow</button> 
              <button class="btn btn-outline-primary">Message</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12 col-xl-8">
      <div class="card mb-3">
        <div class="card-body">
          <h3>Articles</h3>
          <hr>
          <div class="row">
            <?php
              if (sizeof($profile_user->getArticles()) == 0) echo '<div class="ml-4">Cet utilisateur n\'a pas écrit d\'article</div>';
              foreach ($profile_user->getArticles() as $article) {
                ?>
                <div class="card flex-md-row ml-4 mr-4 mt-4 box-shadow h-md-250">
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
</html>