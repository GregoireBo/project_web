<?php
    $page = 'index';
    
    include_once('assets/php/_includes.php');

    $article = new cArticle();
    if (isset($_GET['article_id'])) {
        $article->loadById($_GET['article_id']);
    }
    if ($article->getId() == null){
        header('Location: '.MAIN_PATH);
    }
?>


<body>
    <div class="container mt-5">
        <img class="w-100" src="<?php echo $article->getPictureLink();?>" alt="image de l'article">
        <h1 class=><?php echo $article->getTitle();?></h1>

        <img class="rounded-circle" height="35" alt="image utilisateur" src="<?php echo $article->getUser()->getProfilPictureLink();?>">
        <?php echo $article->getUser()->getPseudo().' | <em>'.$article->getFormatedDate().'</em>' ?>  
        <hr>
        <?php echo nl2br($article->getText()); ?>
    </div>


</body>
</html>