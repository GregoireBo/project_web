<?php
include_once("cSQL.php");
include_once("cUser.php");

class cArticle{
    private $m_iId;
    private $m_oUser;
    private $m_sTitle;
    private $m_sText;
    private $m_sShortDescript;
    private $m_sPicDirectory;

    public function __construct(){
        $this->m_oUser = new cUser();
        $this->m_sPicDirectory = MAIN_PATH.'/assets/img/articles/';
    }

    public function load(int $id, int $userID, string $title,string $text, string $shortDescript){
        $this->m_iId = $id;
        $this->m_oUser->loadByID($userID);
        $this->m_sTitle = $title;
        $this->m_sText = $text;
        $this->m_sShortDescript = $shortDescript;

    }

    //-
    //loadByID(int id)
    //
    //Permet de charger l'objet article en fonction de son ID
    public function loadByID($id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,USER_ID,TITLE,TEXT,SHORT_DESC FROM ARTICLE WHERE ID=?',[$id]);
        if ($oSQL->next()){
            $this->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colNameInt('USER_ID'),
                $oSQL->colName('TITLE'),
                $oSQL->colName('TEXT'),
                $oSQL->colName('SHORT_DESC')
            );
        }
    }

//-
//createArticle
//ATTENTION WAMP DOIT AVOIR LA PERMISSION D'ECRIRE DANS LE DOSSIER
//Permet d'ajouter un article à la base de donnée
public function createArticle(cUser $user, string $title,string $text, string $shortDescript, $file){
    $oSQL = new cSQL();
    if ($user->canCreateArticle()){
        if (exif_imagetype($file)){//test si c'est une image
            $imgSize = getimagesize($file);
            if($imgSize[0] == 800 && $imgSize[1] == 300){//test si l'image fait 800x300
                if(move_uploaded_file($file, getcwd().'/assets/img/articles/'.$this->getId().'.jpg')) {//upload de l'image
                    if ($oSQL->execute('INSERT INTO ARTICLE (USER_ID,TITLE,SHORT_DESC,TEXT) VALUES (?,?,?,?)'
                    ,[$user->getId(),$title,$text,$shortDescript])){
                        if ($oSQL->execute('SELECT ID FROM ARTICLE ORDER BY ID DESC LIMIT 1')){//récupère l'id de l'article
                            $oSQL->next();
                            $this->loadById($oSQL->colNameInt('ID'));
                            return 'val';
                        }
                        else return 'errSelect';
                    }
                    else return 'errInsert';
                }
                else return 'errUpload';
            }
            else return 'errImgSize';
        }
        else return 'errImgType';
    }
    else return 'errNoPerm';
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
        return $this->m_sPicDirectory.$this->getId().'.jpg';}

    //-
    //getShortDescript()
    //Retourne la description courte de l'article
    //
    public function getShortDescript(){
        return $this->m_sShortDescript;}
} 





?>