<?php
include_once("cSQL.php");
include_once("cUser.php");

class cArticle{
    private $m_iId;
    private $m_oUser;
    private $m_sTitle;
    private $m_sText;
    private $m_iTimestamp;
    private $m_sShortDescript;
    private $m_sPicDirectory;

    public function __construct(){
        $this->m_oUser = new cUser();
        $this->m_sPicDirectory = MAIN_PATH.'assets/img/articles/';
    }

    public function load(int $id, int $userID, string $title,string $text, string $shortDescript,int $timestamp){
        $this->m_iId = $id;
        $this->m_oUser->loadByID($userID);
        $this->m_sTitle = $title;
        $this->m_sText = $text;
        $this->m_sShortDescript = $shortDescript;
        $this->m_iTimestamp = $timestamp;
    }

    //-
    //loadByID(int id)
    //return true si ça à marché, false sinon
    //Permet de charger l'objet article en fonction de son ID
    public function loadByID($id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,USER_ID,TITLE,TEXT,SHORT_DESC,UNIX_TIMESTAMP(TIMESTAMP) 
                        as TIMESTAMP FROM ARTICLE WHERE ID=? AND IS_DELETED = 0',[$id]);
        if ($oSQL->next()){
            $this->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colNameInt('USER_ID'),
                $oSQL->colName('TITLE'),
                $oSQL->colName('TEXT'),
                $oSQL->colName('SHORT_DESC'),
                $oSQL->colName('TIMESTAMP')
            );
            return true;
        }
        else return false;
    }

    //-
    //createArticle
    //ATTENTION WAMP DOIT AVOIR LA PERMISSION D'ECRIRE DANS LE DOSSIER
    //Permet d'ajouter un article à la base de donnée
    public function createArticle(cUser $user, string $title,string $text, string $shortDescript, $file){
        $oSQL = new cSQL();
        if ($user->canCreateArticle()){
            if (exif_imagetype($file)){//test si c'est une image
                //redimmensionnement image
                $this->resizePic($file, 1100, 400);
                if ($oSQL->execute('SELECT ID FROM ARTICLE ORDER BY ID DESC LIMIT 1')){//récupère l'id de l'article
                    $oSQL->next();
                    $id = $oSQL->colNameInt('ID');
                    $id++;
                    if(move_uploaded_file($file, getcwd().'/assets/img/articles/'.$id.'.jpg')) {//upload de l'image
                        if ($oSQL->execute('INSERT INTO ARTICLE (USER_ID,TITLE,SHORT_DESC,TEXT) VALUES (?,?,?,?)'
                        ,[$user->getId(),$title,$text,$shortDescript])){
                                $this->loadById($id);
                                return 'val';
                        }
                        else return 'errInsert';
                    }
                    else return 'errUpload';
                }
                else return 'errSelect';
            }
            else return 'errImgType';
        }
        else return 'errNoPerm';
    }

    //-
    //editArticle
    //ATTENTION WAMP DOIT AVOIR LA PERMISSION D'ECRIRE DANS LE DOSSIER
    //Permet de modifier un article
    public function editArticle(string $title,string $text, string $shortDescript, $file = ''){
        $oSQL = new cSQL();
        if ($this->getId() != null && $this->getUser()->canEditArticle($this)){
            if ($file != ''){
                if (exif_imagetype($file)){//test si c'est une image
                    //redimmensionnement image
                    $this->resizePic($file, 1100, 400);
                    if(move_uploaded_file($file, getcwd().'/assets/img/articles/'.$this->getId().'.jpg')) {//upload de l'image
                    }
                    else return 'errUpload';
                }
                else return 'errImgType';
            }
            if ($oSQL->execute('UPDATE ARTICLE SET TITLE=?,SHORT_DESC=?,TEXT=? WHERE ID=?'
                            ,[$title,$text,$shortDescript,$this->getId()])){
                                    return 'val';
            }
            else return 'errUpdate';
        }
        else return 'errNoPerm';
    }

    //-
    //resizePic()
    //Redimensionne l'image passée en paramètre
    //
    private function resizePic(string $img, int $width, int $height){
        $imageSize = getimagesize($img);
        $imageRessource= imagecreatefromjpeg($img);
        $imageFinal = imagecreatetruecolor($width, $height);
        $final = imagecopyresampled($imageFinal, $imageRessource, 0,0,0,0, $width, $height, $imageSize[0], $imageSize[1]);
        imagejpeg($imageFinal, $img, 100);
    } 

    //-
    //deleteArticle()
    //Return true si ça à marché, false sinon
    //Supprime un article si l'utilisateur à la permission
    public function deleteArticle(cUser $user){
        $oSQL = new cSQL();
        if ($user->canDeleteArticle()){
            $oSQL->execute('UPDATE ARTICLE SET IS_DELETED=true WHERE ID=?',[$this->getID()]);
            return true;
        }
        return false;
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

    //-
    //getFormatedDate()
    //Retourne la date formatée de l'article
    //
    public function getFormatedDate(){
        return utf8_encode(strftime('%e %b %Y',$this->m_iTimestamp));}

    //-
    //getLink()
    //Retourne le lien vers l'article
    //
    public function getLink(){
        return MAIN_PATH.'article/'.$this->getId();}
} 





?>