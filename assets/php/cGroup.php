<?php
include_once("cSQL.php");
include_once("cPerm_List.php");

class cGroup{
    private $m_iId;
    private $m_sName;
    private $m_oPermList;

    public function __construct(){
        $this->m_oPermList = new cPerm_List();
    }

    public function load(int $id, string $name){
        $this->m_iId = $id;
        $this->m_sName = $name;
        $this->m_oPermList->loadByGrpID($id);
    }

    public function loadByID($id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,NAME FROM GRP WHERE ID=?',[$id]);
        if ($oSQL->next()){
            $this->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colName('NAME')
            );
        }
    }

    public function getID(){return $this->m_iId;}
    public function getName(){return $this->m_sName;}
    public function getPermList(){return $this->m_oPermList;}
} 





?>