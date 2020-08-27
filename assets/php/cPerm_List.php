<?php
include_once("cSQL.php");
include_once("cPerm.php");

class cPerm_List{
    private $m_aoPerm;

    public function __construct(){
        $this->m_aoPerm = [];
    }


    private function add(cPerm $perm){
        $this->m_aoPerm[$perm->getCode()] = $perm;
    }

    //-
    //loadByGrpID(int id)
    //
    //Charge toutes les permissions par rapport à l'id du groupe
    public function loadByGrpID(int $id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT PERM.ID, PERM.CODE, PERM.DESCRIPT FROM GRP_PERM_LINK
                        INNER JOIN PERM ON PERM.ID = GRP_PERM_LINK.PERM_ID
                        WHERE GRP_PERM_LINK.GRP_ID = ?',[$id]);
        while ($oSQL->next()){
            $cPermTemp = new cPerm();
            $cPermTemp->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colName('CODE'),
                $oSQL->colName('DESCRIPT'),
            );
            $this->add($cPermTemp);
        }
    }

    //-
    //getPerms()
    //Retourne une liste d'objet cPerm
    //
    public function getPerms(){
        return $this->m_aoPerm;}

    //-
    //getOnePerm()
    //Retourne un objet perm si il est dans la liste sinon retourne faux
    //Permet de savoir si la permission est contenue dans la liste
    public function getOnePerm(string $perm){
        if (array_key_exists($perm, $this->m_aoPerm)) return true;
        else return false;
    }
} 





?>