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

    public function loadAll(){
        $oSQL = new cSQL();

        $oSQL->execute('SELECT ID FROM ARTICLE');
        if ($oSQL->next()){
            $cUserTemp = new cUser();
            $cUserTemp->loadByID($oSQL->colNameInt('ID'));
            $this->add($cUserTemp);
        }
    }

    public function getUsers(){return $this->m_aoUser;}
} 





?>