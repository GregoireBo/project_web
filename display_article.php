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
        <img class="w-100" src="<?php echo $article->getPictureLink();?>">
        <h1 class=><?php echo $article->getTitle();?></h1>
    </div>


</body>
</html>