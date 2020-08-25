<?php
    class cClass{
        public $id;
        public $sName;
        public $aMember;
        public function __construct(string $name){
            $this->id = $name;
            $this->sName = $name;
            $this->aMember = [];
        }
        public function add(string $name, string $text, string $return = ''){
            $oMember = new cMember($name, $text,$return);
            array_push($this->aMember,$oMember);
        }

        public function getHTML(){
            $members = '';
            foreach ($this->aMember as $member) {
                $members .= $member->getHTML();
            }
            return '
            <div id="'.$this->id.'" class="tab-pane fade">
                <h3>'.$this->sName.'</h3>
                '.$members.'
            </div>
        ';
        }
    }

    class cMember{
        public $sName;
        public $sText;
        public function __construct(string $name, string $text, string $return = ''){
            $this->sName = $name;
            $this->sText = $text;
            if ($return == '') $return = 'Pas de valeur de retour';
            $this->sReturn = $return;
        }

        public function getHTML(){
            return '
                <br>    
                <br>    
                <h4>'.$this->sName.'</h4>
                <i>'.$this->sReturn.'</i><br>
                '.$this->sText.'
            ';
        }
    }

    $aClass = [];

    //cUser
    $aClassTemp = new cClass('cUser');
    $aClassTemp->add("loadByID(int ID)",
        "Permet de charger l'objet user en fonction de son ID");
    $aClassTemp->add("connect(string pseudo, string pass)",
        "Permet de vérifier si le pseudo et le mot de passe correspondent à un utilisateur, puis charge l'utilisateur correspondant dans l'objet.        ",
        "Retourne true si réussi et false si échoué");
    $aClassTemp->add("inscript(string pseudo, string pass)",
        "Permet d'inscrire un utilisateur après avoir vérifié que son pseudo n'existait pas déjà, puis avoir hashé son mot de passe.",
        "Retourne true si réussi et false si échoué");
    $aClassTemp->add("switchActive()",
        "Permet d'activer l'utilisateur s'il est désactivé et de le désactiver si il est activé.",
        "Retourne true si réussi et false si échoué");
    $aClassTemp->add("getID()","",
        "Retourne l'id de l'utilisateur");
    $aClassTemp->add("getPseudo()","",
        "Retourne le pseudo de l'utilisateur");
    $aClassTemp->add("isActive()","",
        "Retourne true si l'utilisateur est activé, et false si l'utilisateur est désactivé");
    $aClassTemp->add("getGroup()","",
        "Retourne l'objet group de l'utilisateur");
    array_push($aClass,$aClassTemp);

    //cUser_List
    $aClassTemp = new cClass('cUser_List');
    $aClassTemp->add("loadAll()",
        "Charge tout les utilisateurs");
    $aClassTemp->add("getUsers()","",
        "Retourne une liste d'objet cUser");
    array_push($aClass,$aClassTemp);

    //cGroup
    $aClassTemp = new cClass('cGroup');
    $aClassTemp->add("loadByID(int id)",
        "Permet de charger l'objet group en fonction de son ID");
    $aClassTemp->add("getID()","",
        "Retourne l'id du groupe");
    $aClassTemp->add("getName()","",
        "Retourne le nom du groupe");
    $aClassTemp->add("getPermList()","",
        "Retourne une liste d'objet cPerm, correspondant aux permissions du groupe");
    array_push($aClass,$aClassTemp);

    //cPerm
    $aClassTemp = new cClass('cPerm');
    $aClassTemp->add("loadByID(int id)",
        "Permet de charger l'objet perm en fonction de son ID");
    $aClassTemp->add("getID()","",
        "Retourne l'id de la permission");
    $aClassTemp->add("getCode()","",
        "Retourne le code de la permission");
    $aClassTemp->add("getDescript()","",
        "Retourne la description de la permission");
    array_push($aClass,$aClassTemp);

    //cPerm_List
    $aClassTemp = new cClass('cPerm_List');
    $aClassTemp->add("loadByGrpID(int id)",
        "Charge toutes les permissions par rapport à l'id du groupe");
    $aClassTemp->add("getPerms()","",
        "Retourne une liste d'objet cPerm");
    array_push($aClass,$aClassTemp);

    //cArticle
    $aClassTemp = new cClass('cArticle');
    $aClassTemp->add("loadByID(int id)",
        "Permet de charger l'objet article en fonction de son ID");
    $aClassTemp->add("getID()","",
        "Retourne l'id de l'article");
    $aClassTemp->add("getUser()","",
        "Retourne l'objet cUser correspondant à l'auteur de l'article'");
    $aClassTemp->add("getTitle()","",
        "Retourne le titre de l'article");
    $aClassTemp->add("getText()","",
    "Retourne le titre de l'article");
    $aClassTemp->add("getTitle()","",
    "Retourne le texte de l'article");
    $aClassTemp->add("getPictureLink()","",
    "Retourne le lien vers l'image de l'article");
    $aClassTemp->add("getShortDescript()","",
    "Retourne la description courte de l'article");
    array_push($aClass,$aClassTemp);

    //cArticle_List
    $aClassTemp = new cClass('cArticle_List');
    $aClassTemp->add("loadAll(int limit = 10)",
        "Charge un certain nombre d'article du blog dans la limite passée en paramètre (10 par défaut)");
    $aClassTemp->add("getArticles()","",
        "Retourne une liste d'objet cArticle");
    array_push($aClass,$aClassTemp);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Documentation classes</title>
  <link rel="shortcut icon" href="https://fr.wikipedia.org/static/favicon/wikipedia.ico"/>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h2>Documentation classes</h2>
    <p></p>
    
  <ul class="nav nav-tabs">
    <?php 
        foreach ($aClass as $class) {
            echo '<li><a data-toggle="tab" href="#'.$class->id.'">'.$class->sName.'</a></li>';
        }
    ?>
  </ul>

  <div class="tab-content">
    <?php 
        foreach ($aClass as $class) {
            echo $class->getHTML();
        }
    ?>
  </div>
</div>

</body>
</html>
