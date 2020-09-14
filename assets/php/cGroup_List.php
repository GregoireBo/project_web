<?php
include_once("cSQL.php");
include_once("cGroup.php");

class cGroup_List{
    private $m_aoGroup;

    public function __construct(){
        $this->m_aoGroup = [];
    }

    private function add(cGroup $group){
        array_push($this->m_aoGroup, $group);
    }

    //-
    //loadAll()
    //
    //Charge tout les utilisateurs
    public function loadAll(){
        $oSQL = new cSQL();

        $oSQL->execute('SELECT ID FROM GRP ORDER BY ID');
        while ($oSQL->next()){
            $oGroupTemp = new cGroup();
            $oGroupTemp->loadByID($oSQL->colNameInt('ID'));
            $this->add($oGroupTemp);
        }
    }

    //-
    //getGroups()
    //Retourne une liste d'objet cGroup
    //
    public function getGroups(){
        return $this->m_aoGroup;}

    //-
    //getSelect()
    //Retourne un objet html select avec ses options correspondant aux permissions
    //On peut lui passer en paramètre un groupe id pour qu'il le séléctionne (par défaut 0)
    public function getSelect(int $grp_id = 0){
        $options = '';
        foreach ($this->getGroups() as $group) {
            $selected = '';
            if ($group->getId() == $grp_id) $selected = 'selected';
            $options .= '<option value="'.$group->getId().'" '.$selected.'>'.$group->getName().'</option>';
        }
        return '
            <select class="custom-select mr-sm-2" name="grpSelect" onchange="this.form.submit()">
                '.$options.'
            </select>
        ';
    }
} 





?>