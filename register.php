<?php 
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
<body class="jumbotron d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
          <div class="card card-signin flex-row my-5">
            <div class="card-img-left">

            </div>
            <div class="card-body">
              <h5 class="card-title text-center">Inscription</h5>
              <form class="form-signin">
                <div class="form-label-group">
                  <input type="text" id="inputUserame" class="form-control" placeholder="Pseudo" required autofocus>
                </div>
  
                <div class="form-label-group">
                  <input type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required>
                </div>
                
                <div class="form-label-group">
                  <input type="password" id="inputConfirmPassword" class="form-control" placeholder="Répéter mot de passe" required>
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