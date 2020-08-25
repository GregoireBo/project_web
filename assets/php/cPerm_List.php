<?php
include_once("cSQL.php");
include_once("cPerm.php");

class cPerm_List{
    private $m_aoPerm;

    public function __construct(){
        $this->m_aoPerm = [];
    }

    private function add(cPerm $perm){
        array_push($this->m_aoPerm, $perm);
    }

    public function loadByGrpID($id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT PERM.ID, PERM.CODE, PERM.DESCRIPT FROM GRP_PERM_LINK
                        INNER JOIN PERM ON PERM.ID = GRP_PERM_LINK.PERM_ID
                        WHERE GRP_PERM_LINK.GRP_ID = ?',[$id]);
        if ($oSQL->next()){
            $cPermTemp = new cPerm();
            $cPermTemp->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colName('CODE'),
                $oSQL->colName('DESCRIPT'),
            );
            $this->add($cPermTemp);
        }
    }

    public function getPerms(){return $this->m_aoPerm;}
} 





?>