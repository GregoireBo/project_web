<?php
include_once("cSQL.php");

class cPerm{
    private $m_iId;
    private $m_sCode;
    private $m_sDescript;

    public function __construct(){
    }

    public function load(int $id, string $code, string $descript){
        $this->m_iId = $id;
        $this->m_sCode = $code;
        $this->m_sDescript = $descript;
    }

    public function loadByID($id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,CODE,DESCRIPT FROM PERM WHERE ID=?',[$id]);
        if ($oSQL->next()){
            $this->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colName('CODE'),
                $oSQL->colNameInt('DESCRIPT'),
            );
        }
    }

    public function getID(){return $this->m_iId;}
    public function getCode(){return $this->m_sCode;}
    public function getDescript(){return $this->m_sDescript;}
} 





?>