<?php
    $page = 'display_article';
    
    include_once('assets/php/_includes.php');

    $article = new cArticle();
    if (isset($_GET['article_id'])) {
        $article->loadById($_GET['article_id']);
    }
    if ($article->getId() == null){
        redirect(MAIN_PATH);
    }
    if (isset($_GET['a'])){
        switch ($_GET['a']) {
            case 'delete':
                if ($article->deleteArticle($user)){
                    redirect(MAIN_PATH);
                }
                break;
            default:
                break;
        }

    }

    //Commentaires
    if ($user->isConnected() && isset($_POST['comment']) && $_POST['comment'] != ''){
        $article->getComments()->addComment($article,$user,$_POST['comment']);
    }
    if($user->havePerm('DELETE_COMMENT') && isset($_POST['delete_comment']) && isset($_POST['comment_id'])){
        $comment = new cComment();
        $comment->loadById($_POST['comment_id']);
        $comment->delete();
    }
    $article->loadComments();

    //bouton like
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
    if (isset($user) && $user->getId() != null){
        if ($user->canEditArticle($article)){
            $btnEdit = '<a class="btn btn-warning mr-2" href="'.MAIN_PATH.'edit_article/'.$article->getId().'"> Editer l\'article</a>';
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
        <!-- Affichage image de l'article -->
        <img class="w-100" src="<?php echo $article->getPictureLink();?>" alt="image de l'article">
        <h1 class=><?php echo $article->getTitle();?></h1>
        <div class="mb-2">
            <!-- Affichage boutons -->
            <?php echo $btns; ?>
        </div>

        <!-- Affichage utilisateur et date -->
        <img class="rounded-circle" height="35" alt="image utilisateur" src="<?php echo $article->getUser()->getProfilPictureLink();?>">
        <?php echo $article->getUser()->getPseudo().' | <em>'.$article->getFormatedDate().'</em>' ?>  
        <hr>
        <!-- Affichage texte -->
        <?php echo nl2br($article->getText()); ?>
        <hr>

        <?php
            if ($user->isConnected()){
        ?>
        <!-- Formulaire ajout commentaire -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="comment">Ajouter un commentaire</label>
                <textarea class="form-control" name="comment" id="comment" rows="2" required></textarea>
                <button type="submit" class="btn btn-danger mt-2">Ajouter</button>
            </div>
        </form>
        <hr>
        <?php }?>

        <!-- affichage commentaires -->
        <?php $nbrComments = $article->getComments()->countComments(); ?>
        <div class="h3 mb-3">Commentaires (<?=$nbrComments?>)</div>
        <div class="container">
            <?php
                $i = 0;
                foreach($article->getComments()->getComments() as $comment){
                    $i++;
                ?>
                    <div class="row">
                        <div class="col-xl-2">
                            <img src="<?=$comment->getUser()->getProfilPictureLink()?>"class="rounded-circle z-depth-0"
                            height="35" alt="image utilisateur">
                            <?=$comment->getUser()->getPseudo()?><br>
                            <?=$comment->getFormatedDate()?>
                            
                            <?php
                                if($user->havePerm('DELETE_COMMENT')){
                            ?>
                            <!--Suppression commentaire-->
                            <form action="" method="post" enctype="multipart/form-data">
                                <input name="comment_id" class="d-none" value="<?=$comment->getId()?>">
                                <button class="btn btn-danger mt-2" name="delete_comment">Supprimer</button>
                            </form>
                            <?php } ?>

                        </div>
                        <div class="col-xl-10 mt-1">
                            <?= nl2br($comment->getText())?>
                        </div>
                    </div>
                <?php if ($i < $nbrComments) echo '<hr>';?>
                <?php
                }
            ?>
        </div>
    </div>
</body>
</html>

<?php include_once('assets/php/_footer.php');?>