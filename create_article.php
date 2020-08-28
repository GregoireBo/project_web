<?php
    $page = 'create_article';
    
    include_once('assets/php/_includes.php');
    if (!isset($user) || !$user->canCreateArticle()) header("Location:".MAIN_PATH);

    if (isset($user) && isset($_POST['title']) && isset($_POST['short_desc']) 
        && isset($_POST['text']) && isset($_FILES["picture"]["tmp_name"])){
        $article = new cArticle();
        $response = $article->createArticle($user,$_POST['title'],$_POST['short_desc'],$_POST['text'],$_FILES["picture"]["tmp_name"]);
        header("Location: ?r=".$response);
    }
    $text = '';
    if (isset($_GET['r'])){
        switch ($_GET['r']) {
            case 'val':
                $text = 'L\'article à bien été ajouté';
                break;
            case 'errUpload':
                $text = 'Erreur lors de l\'upload';
                break;
            case 'errImgSize':
                $text = 'L\'image doit faire 800x300 pixels';
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
    }
?>


<body>
<div class="container mt-3">
    <h2>Ajout d'un article</h2>
    <div class="text-danger"><?php echo $text;?></div>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" required>
        </div>
        <div class="form-group">
            <label for="short_desc">Description</label>
            <textarea class="form-control" name="short_desc" id="short_desc" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="text">Texte</label>
            <textarea class="form-control" name="text" id="text" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="picture">Image d'illustration</label>
            <input type="file" class="form-control-file" name="picture" id="picture" required>
        </div>
        <button type="submit" class="btn btn-danger">Submit</button>
    </form>
</div>
</body>
</html>