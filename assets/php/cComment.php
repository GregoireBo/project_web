<?php
include_once("cSQL.php");
include_once("cUser.php");

class cComment{
    private $m_iId;
    private $m_iArticleId;
    private $m_oUser;
    private $m_sText;
    private $m_iTimestamp;

    public function __construct(){
        $this->m_oUser = new cUser();
    }

    public function load(int $id, int $articleId, int $userId, string $text, int $timestamp){
        $this->m_iId = $id;
        $this->m_iArticleId = $articleId;
        $this->m_oUser->loadById($userId);
        $this->m_sText = $text;
        $this->m_iTimestamp = $timestamp;
    }

    //-
    //loadByID(int id)
    //
    //Permet de charger l'objet comment en fonction de son ID
    public function loadByID(int $id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,ARTICLE_ID,USER_ID,TEXT,UNIX_TIMESTAMP(TIMESTAMP) 
                        as time FROM COMMENTS WHERE ID=?',[$id]);
        if ($oSQL->next()){
            $this->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colNameInt('ARTICLE_ID'),
                $oSQL->colNameInt('USER_ID'),
                $oSQL->colName('TEXT'),
                $oSQL->colNameInt('time')
            );
        }
    }

    //-
    //delete
    //
    //Permet de supprimer un commentaire
    public function delete(){
        $oSQL = new cSQL();
        $oSQL->execute('UPDATE COMMENTS SET IS_DELETED=true WHERE ID=?',[$this->getId()]);
    }

    //-
    //getID()
    //Retourne l'id du commentaire
    //
    public function getID(){
        return $this->m_iId;}

    //-
    //getArticleId()
    //Retourne l'id de l'article
    //
    public function getArticleId(){
        return $this->m_iArticleId;}

    //-
    //getUser()
    //Retourne l'utilisateur du commentaire
    //
    public function getUser(){
        return $this->m_oUser;}

    //-
    //getText()
    //Retourne le texte du commentaire
    //
    public function getText(){
        return $this->m_sText;}

    //-
    //getFormatedDate()
    //Retourne la date formatée de l'article
    //
    public function getFormatedDate(){
        $diff = time() - $this->m_iTimestamp;  
        if ($diff < 60){
            $secondes = $diff;
            return 'Il y a '.(int)$secondes.' secondes';
        }
        else if ($diff < 3600){
            $minutes = $diff/60;
            return 'Il y a '.(int)$minutes.' minutes';
        }
        else if ($diff < 86400){
            $heures = $diff/3600;
            return 'Il y a '.(int)$heures.' heures';
        }
        else if ($diff < 2592000){
            $jours = $diff/86400;
            return 'Il y a '.(int)$jours.' jours';
        }
        else return utf8_encode(strftime('%e %b %Y',$this->m_iTimestamp));
    
    }
} 





?>