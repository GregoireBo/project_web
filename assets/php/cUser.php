<?php
include_once("cSQL.php");
include_once("cGroup.php");

class cUser{
    private $m_iId;
    private $m_sPseudo;
    private $m_bIsActive;
    private $m_oGroup;

    public function __construct(){
        $this->m_oGroup = new cGroup();
    }

    public function load(int $id, string $pseudo, int $grp_id, bool $is_active){
        $this->m_iId = $id;
        $this->m_sPseudo = $pseudo;
        $this->m_bIsActive = $is_active;
        $this->m_oGroup->loadByID($grp_id);
    }

    public function loadByID($id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,PSEUDO,GRP_ID,IS_ACTIVE FROM USER WHERE ID=?',[$id]);
        if ($oSQL->next()){
            $this->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colName('PSEUDO'),
                $oSQL->colNameInt('GRP_ID'),
                $oSQL->colNameBool('IS_ACTIVE')
            );
        }
    }

    public function connect(string $pseudo, string $pass){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,PASSWORD FROM USER WHERE pseudo=?',[$pseudo]);
        if ($oSQL->next()){{}
            if (password_verify($pass,$oSQL->ColName('PASSWORD'))){
                $this->loadByID($oSQL->colNameInt('ID'));
                return true;
            }
            else return false;
        }
        else return false;
    }

    public function inscript(string $pseudo, string $pass){
        $oSQL = new cSQL();
        if (!$this->pseudo_exist($pseudo)){
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            if ($oSQL->execute('INSERT INTO USER (PSEUDO,PASSWORD,IS_ACTIVE) VALUES (?,?,?)',[$pseudo,$pass,0])){
                return true;
            }
            return false;
        }
        return false;
    }

    private function pseudo_exist($pseudo){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID WHERE pseudo=?',[$pseudo]);
        if ($oSQL->next()){{}
            return true;
        }
        else return false;
    }

    public function getID(){return $this->m_iId;}
    public function getPseudo(){return $this->m_sPseudo;}
    public function isActive(){return $this->m_bIsActive;}
    public function getGroup(){return $this->m_oGroup;}
} 





?>