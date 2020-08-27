<?php
include_once("cSQL.php");
include_once("cPerm_List.php");

class cGroup{
    private $m_iId;
    private $m_sName;
    private $m_sIcon;
    private $m_oPermList;

    public function __construct(){
        $this->m_sIcon = '';
        $this->m_oPermList = new cPerm_List();
    }

    public function load(int $id, string $name, string $icon){
        $this->m_iId = $id;
        $this->m_sName = $name;
        $this->m_sIcon = $icon;
        $this->m_oPermList->loadByGrpID($id);
    }

    //-
    //loadByID(int id)
    //
    //Permet de charger l'objet group en fonction de son ID
    public function loadByID(int $id){
        if ($id == 0) $this->m_iId = 0;
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,NAME,ICON FROM GRP WHERE ID=?',[$id]);
        if ($oSQL->next()){
            $this->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colName('NAME'),
                $oSQL->colName('ICON')
            );
        }
    }

    //-
    //havePerm(string perm)
    //Retourne true si le groupe à la permission, false sinon
    //Permet de savoir si un groupe à la permission de ...
    public function havePerm(string $perm){
        return $this->m_oPermList->getOnePerm($perm);    
    }

    //-
    //getID()
    //Retourne l'id du groupe
    //
    public function getID(){
        return $this->m_iId;}

    //-
    //getName()
    //Retourne le nom du groupe
    //
    public function getName(){
        return $this->m_sName;}

    //-
    //getIcon()
    //Retourne l'icon du groupe
    //Au format <i>
    public function getIcon(){
        if ($this->m_sIcon == '') return '';
        else{return '
            <span class="fa-stack fa-xs" >
                <i class="fas text-primary fa-certificate fa-stack-2x"></i>
                <i class="fas text-white '.$this->m_sIcon.' fa-stack-1x"></i>
            </span>
        ';
        }
    }

    //-
    //getPermList()
    //Retourne une liste d'objet cPerm, correspondant aux permissions du groupe
    //
    public function getPermList(){
        return $this->m_oPermList;}


} 





?>