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

    //-
    //loadByID(int id)
    //
    //Permet de charger l'objet article en fonction de son ID
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

    //-
    //getID()
    //Retourne l'id de l'article
    //
    public function getID(){
        return $this->m_iId;}

    //-
    //getUser()
    //Retourne l'objet cUser correspondant à l'auteur de l'article
    //
    public function getUser(){
        return $this->m_oUser;}

    //-
    //getTitle()
    //Retourne le titre de l'article
    //
    public function getTitle(){
        return $this->m_sTitle;}

    //-
    //getText()
    //Retourne le titre de l'article
    //
    public function getText(){
        return $this->m_sText;}

    //-
    //getPictureLink()
    //Retourne le lien vers l'image de l'article
    //
    public function getPictureLink(){
        return $this->m_sPictureLink;}

    //-
    //getShortDescript()
    //Retourne la description courte de l'article
    //
    public function getShortDescript(){
        return $this->m_sShortDescript;}
} 





?>