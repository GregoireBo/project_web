<?php
    $page = 'display_article';
    
    include_once('assets/php/_includes.php');

    $article = new cArticle();
    if (isset($_GET['article_id'])) {
        $article->loadById($_GET['article_id']);
    }
    if ($article->getId() == null){
        header('Location: '.MAIN_PATH);
    }
    if (isset($_GET['a'])){
        switch ($_GET['a']) {
            case 'delete':
                if ($article->deleteArticle($user)){
                    header('Location: '.MAIN_PATH);
                }
                break;
            case 'edit' :
                if ($user->canEditArticle($article)){
                    header('Location: '.MAIN_PATH.'edit_article/'.$article->getId());
                }
                break;
            default:
                break;
        }

    }

    if ($user->getId() && $article->getId()) {
        if (isset($_POST['btn_like'])) {
          $user->like($article);
        }
        if (isset($_POST['btn_undoLike'])) {
          $user->undoLike($article);
        }
      }

    $btnLike = '';
    $btnEdit = '';
    $btnDelete = '';
    if (isset($user)){
        if ($user->canEditArticle($article)){
            $btnEdit = '<a class="btn btn-warning mr-2" href="'.$article->getId().'/edit"> Editer l\'article</a>';
        }
        if ($user->canDeleteArticle()){
            $btnDelete = '<a class="btn btn-danger mr-2" href="'.$article->getId().'/delete"> Supprimer l\'article</a>';
        }
        $btnLike .= '<form action="" method="post" enctype="multipart/form-data" class="mb-2">';
        if ($user->hasLiked($article)) {
            $btnLike .= '
            <button type="submit" class="btn btn-primary text-dark bg-warning border-danger" name="btn_undoLike">
            <i class="fas fa-heart text-danger"></i>
            </button> ';
          } else {
            $btnLike .= '
            <button type="submit" class="btn bg-light text-dark btn-outline-primary border-danger" name="btn_like">
            <i class="far fa-heart text-danger"></i>
            </button> ';
          }
        $btnLike .= '</form>';
    }

    $btns = $btnLike.$btnEdit.$btnDelete;
?>


<body>
    <div class="container mt-5">
        <img class="w-100" src="<?php echo $article->getPictureLink();?>" alt="image de l'article">
        <h1 class=><?php echo $article->getTitle();?></h1>
        <div class="mb-2">
            <?php echo $btns; ?>
        </div>

        <img class="rounded-circle" height="35" alt="image utilisateur" src="<?php echo $article->getUser()->getProfilPictureLink();?>">
        <?php echo $article->getUser()->getPseudo().' | <em>'.$article->getFormatedDate().'</em>' ?>  
        <hr>
        <?php echo nl2br($article->getText()); ?>
    </div>


</body>
</html>

<?php include_once('assets/php/_footer.php');?>