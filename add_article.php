<?php
    $page = 'create_article';
    include_once('assets/php/_includes.php');

    $article = new cArticle();
    $mode = $page;        
    $response = '';

    if (!isset($user) || !$user->canCreateArticle()) header("Location:".MAIN_PATH);
    if (isset($_GET['article_id'])){
        if ($article->loadById($_GET['article_id']) && $user->canEditArticle($article)){
            $mode = 'edit_article';
        }
    }

    if (isset($user) && isset($_POST['id'])  && isset($_POST['title']) && isset($_POST['short_desc']) 
        && isset($_POST['text'])){
        //AJOUT
        if ($mode == 'create_article' && isset($_FILES["picture"]["tmp_name"])){
            $response = $article->createArticle($user,$_POST['title'],$_POST['short_desc'],$_POST['text'],$_FILES["picture"]);
        }
        //MODIFICATION
        else if ($mode == 'edit_article' && $_POST['id'] != ""){
            if ($article->loadById((int)$_POST['id'])){
                $response = $article->editArticle($_POST['title'],$_POST['short_desc'],$_POST['text'],$_FILES["picture"]);
            }
        }
    }
    $text = '';
    switch ($response) {
        case 'val':
            $text = 'L\'article à bien été ajouté';
            if ($article->getId() == null) header('Location: '.MAIN_PATH);
            else header('Location: '.MAIN_PATH.'article/'.$article->getId());
            break;
        case 'errUpload':
            $text = 'Erreur lors de l\'upload';
            break;
        case 'errImgSize':
            $text = 'L\'image doit faire 1100x400 pixels';
            break;
        case 'errImgType':
            $text = 'Le fichier doit être une image';
            break;
        case 'errSelect':
        case 'errInsert':
            $text = 'Erreur lors de l\'insertion de l\'article';
            break;
        case 'errInsert':
            $text = 'Vous n\'avez pas la permission';
            break;
        default:
            break;
    }
?>


<body>
<div class="container mt-3">
    <h2><?php
        if ($mode == 'create_article') echo "Ajout d'un article";
        else if ($mode == 'edit_article') echo "Modification d'un article";
    ?></h2>
    
    <div class="text-danger"><?php echo $text;?></div>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <input type="text" class="form-control d-none" name="id" id="id"
                <?php if ($mode == 'edit_article') echo 'value="'.$article->getId().'"'; ?>
            >
        </div>
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" class="form-control" name="title" id="title" required 
                <?php if ($mode == 'edit_article') echo 'value="'.$article->getTitle().'"'; ?>
            >
        </div>
        <div class="form-group">
            <label for="short_desc">Description</label>
            <textarea class="form-control" name="short_desc" id="short_desc" rows="3" required><?php if ($mode == 'edit_article') echo $article->getShortDescript(); ?></textarea>
        </div>
        <div class="form-group">
            <label for="text">Texte</label>
            <textarea class="form-control" name="text" id="text" rows="10" required><?php if ($mode == 'edit_article') echo $article->getText(); ?></textarea>
        </div>
        <div class="form-group">
            <label for="picture">Image d'illustration (taille conseillée 1100x400)</label>
            <input type="file" class="form-control-file" name="picture" id="picture" 
            <?php if ($mode != 'edit_article') echo 'required'?>
            >
        </div>
        <button type="submit" class="btn btn-danger">Envoyer</button>
    </form>
</div>
</body>
<?php
include_once('assets/php/_footer.php');
?>
</html>