<?php
include_once("cSQL.php");
include_once("cUser.php");

class cArticle{
    private $m_iId;
    private $m_oUser;
    private $m_sTitle;
    private $m_sText;
    private $m_sPictureLink;
    private $m_sShortDescript;

    public function __construct(){
        $this->m_oUser = new cUser();
    }

    public function load(int $id, int $userID, string $title,string $text, string $pictureLink, string $shortDescript){
        $this->m_iId = $id;
        $this->m_oUser->loadByID($userID);
        $this->m_sTitle = $title;
        $this->m_sText = $text;
        $this->m_sPictureLink = $pictureLink;
        $this->m_sShortDescript = $shortDescript;

    }

    public function loadByID($id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,USER_ID,TITLE,TEXT,PICTURE_LINK,SHORT_DESC FROM ARTICLE WHERE ID=?',[$id]);
        if ($oSQL->next()){
            $this->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colNameInt('USER_ID'),
                $oSQL->colName('TITLE'),
                $oSQL->colName('TEXT'),
                $oSQL->colName('PICTURE_LINK'),
                $oSQL->colName('SHORT_DESC')
            );
        }
    }

    public function getID(){return $this->m_iId;}
    public function getUser(){return $this->m_oUser;}
    public function getTitle(){return $this->m_sTitle;}
    public function getText(){return $this->m_sText;}
    public function getPictureLink(){return $this->m_sPictureLink;}
    public function getShortDescript(){return $this->m_sShortDescript;}
} 





?>