<?php 
$no_nav = true;
include_once("assets/php/_includes.php");


if (isset($user) && $user->isConnected()){
  header("Location: ".MAIN_PATH);
}

?>



<style>
/*Style spécifique à la connexion*/
  body {
      background: #3a5d27;
      background: linear-gradient(to right, #0f7c70, #798f33);
  }
  .card-signin .card-img-left {
    background: scroll center url('assets/img/login.jpg');
  }
</style>
<head>
  <link rel="stylesheet" href="assets/css/form.css" />
</head>
<body class="jumbotron d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
          <div class="card card-signin flex-row my-5">
            <div class="card-img-left d-none d-md-flex">

            </div>
            <div class="card-body">
              <a href="<?php echo MAIN_PATH;?>"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
              <h5 class="card-title text-center mt-3">Connexion</h5>
              <form class="form-signin" method="post">
                <?php if(isset($_GET['e']) && $_GET['e']=='psmdp') echo '<div class="text-danger  mb-2">Le pseudo ou le mot de passe est incorrect</div>'; ?>
                <div class="form-label-group">
                  <input type="text" name="inputPseudo" id="inputPseudo" class="form-control" placeholder="Pseudo" required autofocus>
                </div>
  
                <div class="form-label-group">
                  <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Mot de passe" required>
                </div>

                <hr>

                <button class="btn btn-lg btn-success btn-block text-uppercase" type="submit">Se connecter</button>
                <a class="d-block text-center mt-2 small" href="register">Je n'ai pas de compte</a>
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
    if (isset($user)){
      if ($user->connect($_POST['inputPseudo'], $_POST['inputPassword'])){}
      else header("Location: ?e=psmdp");
    }
  }
?>



