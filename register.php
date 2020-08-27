<?php 
$no_nav = true;
include_once("assets/php/_includes.php");
?>



<style>
  /*Style spécifique à la connexion*/
    body {
      background: #802930;
      background: linear-gradient(to right, #802930, #da9fa8);
    }
    .card-signin .card-img-left {
      background: scroll center url('assets/img/register.jpg');
    }
  </style>
  
</style>
<head>
  <link rel="stylesheet" href="assets/css/form.css" />
</head>
<body class="jumbotron d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
          <div class="card card-signin flex-row my-5">
            <div class="card-img-left">

            </div>
            <div class="card-body">
              <a href="<?php echo MAIN_PATH;?>" ><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
              <h5 class="card-title text-center mt-3">Inscription</h5>
              <form class="form-signin" method="post">
                <?php if(isset($_GET['e']) && $_GET['e']=='err') echo '<span class="text-danger">Erreur lors de l\'inscription</span>'; ?>
                <?php if(isset($_GET['e']) && $_GET['e']=='suc') echo '<span class="text-success">Inscription réussi, un administrateur va la valider.</span>'; ?>
                <div class="form-label-group">
                  <input type="text" name="inputPseudo" id="inputPseudo" class="form-control" placeholder="Pseudo" required autofocus>
                </div>
                <?php if(isset($_GET['e']) && $_GET['e']=='mdp') echo '<span class="text-danger">Les mots de passe ne correpondent pas.</span>'; ?>
                <div class="form-label-group">
                  <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Mot de passe" required>
                </div>
                
                <div class="form-label-group">
                  <input type="password" name="inputConfirmPassword" id="inputConfirmPassword" class="form-control" placeholder="Répéter mot de passe" required>
                </div>
  
                <hr>

                <button class="btn btn-lg btn-danger btn-block text-uppercase" type="submit">Confirmer l'inscription</button>
                <a class="d-block text-center mt-2 small" href="login">Déjà inscrit ?</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>


<?php
  if (isset($_POST['inputPseudo']) && isset($_POST['inputPassword'])){
    if ($_POST['inputPassword'] == $_POST['inputConfirmPassword']){
      if (isset($user)){
        if($user->inscript($_POST['inputPseudo'], $_POST['inputPassword'])){
          header("Location: ?e=suc");
        }
        else header("Location: ?e=err");
      }
      else header("Location: ?e=err");
    }
    else header("Location: ?e=mdp");
  }
?>



<?php
if (isset($user) && $user->isConnected()){
  header("Location: ".MAIN_PATH);
}

?>