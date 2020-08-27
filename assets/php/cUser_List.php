<?php
include_once("cSQL.php");
include_once("cUser.php");

class cUser_List{
    private $m_aoUser;

    public function __construct(){
        $this->m_aoUser = [];
    }

    private function add(cUser $user){
        array_push($this->m_aoUser, $user);
    }

    //-
    //loadAll()
    //
    //Charge tout les utilisateurs
    public function loadAll(){
        $oSQL = new cSQL();

        $oSQL->execute('SELECT ID FROM USER ORDER BY ID');
        while ($oSQL->next()){
            $cUserTemp = new cUser();
            $cUserTemp->loadByID($oSQL->colNameInt('ID'));
            $this->add($cUserTemp);
        }
    }

    //-
    //getUsers()
    //Retourne une liste d'objet cUser
    //
    public function getUsers(){
        return $this->m_aoUser;}
} 





?>