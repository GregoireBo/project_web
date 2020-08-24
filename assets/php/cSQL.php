<?php
//classe de connexion à une base de donnée SQL à destination des étudiants du CESI
namespace App\Controller\classes;
use \PDO;

class cSQL{
    public $oBdd;
    public $oReq;
    public $aData;
    function __construct(string $host = "", string $database = "", string $user = "", string $password = ""){
        if ($host=="" && $database=="" && $user=="" && $password=="") $this->connect('localhost', 'CV', 'root', '');
        else $this->connect($host, $database, $user, $password);
    }
    function connect(string $host, string $database, string $user, string $password){
        try {$this->oBdd = new PDO('mysql:host='.$host.';dbname='.$database.';charset=utf8', ''.$user.'', ''.$password.'');}
        catch (Exception $e) {die('Erreur : ' . $e->getMessage());}
    }
    //éxecute une requete
    function execute(string $req, array $tab_parameter=null){
        $this->oReq = $this->oBdd->prepare($req);
        return $this->oReq->execute($tab_parameter);
    }
    //passe à la ligne suivante
    function next(){
        return ($this->aData = $this->oReq->fetch());
    }
    //retourne une colonne en fonction du nom
    function colName(string $name){
        return $this->aData[$name];
    }
    //retourne une colonne en fonction du nom
    function colNameInt(string $name){
        return (int)$this->aData[$name];
    }
    //retourne une colonne en fonction du nom
    function colNameBool(string $name){
        return (bool)$this->aData[$name];
    }
}





?>