<?php 
include_once("assets/php/_includes.php");
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

<body class="jumbotron d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
          <div class="card card-signin flex-row my-5">
            <div class="card-img-left d-none d-md-flex">

            </div>
            <div class="card-body">
              <h5 class="card-title text-center">Connexion</h5>
              <form class="form-signin">
                <div class="form-label-group">
                  <input type="text" id="inputUserame" class="form-control" placeholder="Pseudo" required autofocus>
                </div>
  
                <div class="form-label-group">
                  <input type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required>
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