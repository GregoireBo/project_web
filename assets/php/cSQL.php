<?php

class cSQL{
    public $oBdd;
    public $oReq;
    public $aData;
    function __construct(string $host = "", string $database = "", string $user = "", string $password = ""){
        if ($host=="" && $database=="" && $user=="" && $password=="") $this->connect('localhost', 'project_web', 'root', '');
        else $this->connect($host, $database, $user, $password);
    }
    function connect(string $host, string $database, string $user, string $password){
        try {$this->oBdd = new PDO('mysql:host='.$host.';dbname='.$database.';charset=utf8', ''.$user.'', ''.$password.'');}
        catch (Exception $e) {die('Erreur : ' . $e->getMessage());}
    }

    //-
    //execute(string req, array tab_parameter=null)
    //retourne true si réussi, false si non réussi
    //execute une requete SQL
    function execute(string $req, array $tab_parameter=null){
        $this->oReq = $this->oBdd->prepare($req);
        return $this->oReq->execute($tab_parameter);
    }

    //-
    //next()
    //retourne true si il reste des lignes et false sinon
    //passe à la ligne suivante
    function next(){
        return ($this->aData = $this->oReq->fetch());
    }

    //-
    //colName(string name)
    //retourne une colonne en fonction du nom
    //
    function colName(string $name){
        return $this->aData[$name];
    }

    //-
    //colNameInt(string name)
    //retourne une colonne int en fonction du nom
    //
    function colNameInt(string $name){
        return (int)$this->aData[$name];
    }

    //-
    //colNameBool(string name)
    //retourne une colonne bool en fonction du nom
    //
    function colNameBool(string $name){
        return (bool)$this->aData[$name];
    }

    //-
    //getError
    //retourne l'erreur de la dernière requete
    //
    function getError(){
        var_dump($this->oBdd->errorInfo());
    }
}





?>