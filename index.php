<?php
$page = 'index';
$no_nav = false;
include_once("assets/php/_includes.php");

// Pagination
$pageIndex = 1;
$limit = 10;

    // On récupère le num. de page
    if (isset($_GET['page_id'])) {
      $pageIndex = $_GET['page_id'];
    }
    
    $textSearch = '';
    if (isset($_GET['search']) && $_GET['search'] != '') $textSearch = $_GET['search'];

$offset = ($pageIndex - 1) * 10;

    $articleList = new cArticle_List();

    
    if ($textSearch != ''){
      $articleList->loadSearch($textSearch, $limit);
    }
    else{
      $articleList->loadAll($offset, $limit);
    }

$totalNumberArticles = $articleList->getTotalNumberArticles();
$totalPages = ceil($totalNumberArticles / 10);
?>
<html>
  <body>
  <div class="container mt-5">
    <div class="offset-1 h3 row">
      <?php
        if ($textSearch != ''){
          echo 'Résultats de recherche pour : '.$textSearch;
        }
      ?>
    </div>
  <div class="row">
  <?php
    foreach($articleList->getArticles() as $articleFor) {
    ?>
      <!-- Card -->
      <div class="card card_article col-5 offset-1 mb-3" style="height: 24rem">
      
        <!-- Card content -->
        <div class="card-header d-flex flex-row bg-transparent overflow-hidden">
          <!-- Avatar -->
          <img src="<?= $articleFor->getUser()->getProfilPictureLink() ?>" class="rounded-circle mr-3" height="50px" width="50px" alt="avatar">

          <!-- Content -->
            <h5 class="card-title font-weight-bold mb-2"><?= $articleFor->getTitle() ?></h5>
        </div>
      
        <!-- Card content -->
        <div class="card-body overflow-hidden">
                <!-- Text -->
            <p id="card-text collapseContent" class="card-text"><?= $articleFor->getShortDescript() ?></p>
        </div>


        <div class="card-footer bg-transparent">
        
        <small class="text-muted">
          <i class="far fa-user pr-2"></i><?= $articleFor->getUser()->getPseudo(false,true) ?> |
          <i class="far fa-clock pr-2"></i><?= $articleFor->getFormatedDate() ?>
          <?php
            if (($user->isConnected()) && ($user->hasLiked($articleFor))) {
              echo ' | <i class="fas fa-heart pr-3"></i>';
            }
          ?>
        </small>
        </div>

        <div class="card-footer bg-transparent">
          <a href="<?= $articleFor->getLink() ?>" class="btn btn-warning btn-sm">Lire la suite...</a>
        </div>
      </div>
      <?php
        }
      ?>
      </div>
      <?php

      $i = 1;
      // Si le nombre de pages dépasse 1
      if ($totalPages > 1) {
        // On positionne parmi les pages précédentes et suivantes
        $i = $pageIndex - 4;

        if ($i < 1) {
          $i = 1;
        }
        // Si le nombre de pages dépasse 10
        if ($totalPages > 10) {
          // On met une limite
          $totalPages = 10;
        }
      ?>
      <nav aria-label="nav">
        <ul class="pagination justify-content-center">
          <?php
          if ($textSearch == ''){
            for ($i; $i <= $totalPages; $i++) {
              ?>
                <li class="page-item <?php if ($pageIndex == $i){ echo 'disabled'; } ?>"><a class="page-link" href="<?= MAIN_PATH.$i ?>"><?= $i; ?></a></li>
              <?php
            }
          }
          ?>
        </ul>
      </nav>
      <?php
      }
      ?>
    </div>
  </body>
  <?php
   include_once('assets/php/_footer.php');
 ?>
</html>