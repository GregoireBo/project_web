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
    <div class="col-md-4 mb-3">
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
    <div class="col-md-8">
      <div class="card mb-3">
        <div class="card-body">
          <h3>Articles</h3>
          <hr>
          aaa
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>