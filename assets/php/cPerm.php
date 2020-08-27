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

    //-
    //loadByID(int id)
    //
    //Permet de charger l'objet perm en fonction de son ID
    public function loadByID(int $id){
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

    //-
    //getID()
    //Retourne l'id de la permission
    //
    public function getID(){
        return $this->m_iId;}

    //-
    //getCode()
    //Retourne le code de la permission
    //
    public function getCode(){
        return $this->m_sCode;}

    //-
    //getDescript()
    //Retourne la description de la permission
    //
    public function getDescript(){
        return $this->m_sDescript;}
} 





?>