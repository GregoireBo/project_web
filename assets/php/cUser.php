<?php
include_once("cSQL.php");
include_once("cGroup.php");

class cUser{
    private $m_iId;
    private $m_sPseudo;
    private $m_bIsActive;
    private $m_oGroup;
    private $m_sToken;
    private $m_iPictureId;

    public function __construct(){
        $this->m_oGroup = new cGroup();
    }

    public function load(int $id, string $pseudo, int $grp_id, bool $is_active, string $token, int $pictureId){
        $this->m_iId = $id;
        $this->m_sPseudo = $pseudo;
        $this->m_bIsActive = $is_active;
        $this->m_oGroup->loadByID($grp_id);
        $this->m_sToken = $token;
        $this->m_iPictureId = $pictureId;
    }

    //-
    //loadByID(int ID)
    //
    //Permet de charger l'objet user en fonction de son ID
    public function loadByID($id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,PSEUDO,GRP_ID,IS_ACTIVE,TOKEN,PICTURE_ID FROM USER WHERE ID=?',[$id]);
        if ($oSQL->next()){
            $this->load(
                $oSQL->colNameInt('ID'),
                $oSQL->colName('PSEUDO'),
                $oSQL->colNameInt('GRP_ID'),
                $oSQL->colNameBool('IS_ACTIVE'),
                $oSQL->colName('TOKEN'),
                $oSQL->colName('PICTURE_ID')
            );
        }
    }

    //-
    //connect(string pseudo, string pass)
    //Retourne true si réussi et false si échoué
    //Permet de vérifier si le pseudo et le mot de passe correspondent à un utilisateur, puis charge l'utilisateur correspondant dans l'objet.
    public function connect(string $pseudo, string $pass){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,PASSWORD FROM USER WHERE pseudo=? AND IS_ACTIVE=true',[$pseudo]);
        if ($oSQL->next()){
            if (password_verify($pass,$oSQL->ColName('PASSWORD'))){
                $this->loadByID($oSQL->colNameInt('ID'));
                $_SESSION['PSEUDO'] = $this->m_sPseudo;
                $_SESSION['TOKEN'] = $this->m_sToken;
                return true;
            }
            else return false;
        }
        else return false;
    }

    //-
    //connectToken(string pseudo, string token)
    //Retourne true si réussi et false si échoué
    //Permet de vérifier si le pseudo et le token correspondent à un utilisateur, puis charge l'utilisateur correspondant dans l'objet
    public function connectToken(string $pseudo, string $token){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,TOKEN FROM USER WHERE pseudo=? AND TOKEN =? AND IS_ACTIVE=true',[$pseudo,$token]);
        if ($oSQL->next()){
            $this->loadByID($oSQL->colNameInt('ID'));
            $_SESSION['PSEUDO'] = $this->m_sPseudo;
            $_SESSION['TOKEN'] = $this->m_sToken;
            return true;
        }
        else return false;
    }

    //-
    //inscript(string pseudo, string pass)
    //Retourne true si réussi et false si échoué
    //Permet d'inscrire un utilisateur après avoir vérifié que son pseudo n'existait pas déjà, puis avoir hashé son mot de passe
    public function inscript(string $pseudo, string $pass){
        $oSQL = new cSQL();
        if (!$this->pseudo_exist($pseudo)){
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $token = $this->genToken();
            if ($oSQL->execute('INSERT INTO USER (PSEUDO,PASSWORD,IS_ACTIVE,TOKEN) VALUES (?,?,?,?)',[$pseudo,$pass,0,$token])){
                return true;
            }
            else return false;
        }
        else return false;
    }

    //-
    //deconnect()
    //
    //Déconnecte l'utilisateur
    public function deconnect(){
        session_destroy();
        header("Location:".MAIN_PATH);
    }

    //-
    //switchActive()
    //Retourne true si réussi et false si échoué
    //Permet d'activer l'utilisateur s'il est désactivé et de le désactiver si il est activé.
    public function switchActive(){
        $oSQL = new cSQL();
        if ($this->isActive()) return $oSQL->execute('UPDATE USER SET IS_ACTIVE=false WHERE ID=?',[$this->getID()]);
        else return $oSQL->execute('UPDATE USER SET IS_ACTIVE=true WHERE ID=?',[$this->getID()]);
    }

    //vérifie sir un pseudo existe
    private function pseudo_exist($pseudo){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID WHERE pseudo=?',[$pseudo]);
        if ($oSQL->next()){{}
            return true;
        }
        else return false;
    }

    //-
    //id_exist(int id)
    //Retourne true si l'utilisateur existe, false sinon
    //Permet de savoir si un utilisateur existe par rapport à son id
    public function id_exist(int $id){
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID FROM USER WHERE ID=?',[$id]);
        if ($oSQL->next()){{}
            return true;
        }
        else return false; 
    }

    //-
    //havePerm(string perm)
    //Retourne true si l'utilisateur à la permission, false sinon
    //Permet de savoir si un utilisateur à la permission de ...
    public function havePerm(string $perm){
        return $this->m_oGroup->havePerm($perm);    
    }

    //genToken()
    //genere un token de connexion utilisateur
    private function genToken(){
        return 'CONN.'.strtoupper(bin2hex(random_bytes (25)));
    }

    //-
    //changeGrp(int id)
    //Retourne true si réussi et false si échoué
    //Change le groupe de l'utilisateur
    public function changeGrp(int $id){
        $oSQL = new cSQL();
        return $oSQL->execute('UPDATE USER SET GRP_ID=? WHERE ID=?',[$id,$this->getID()]); 
    }

    //-
    //getID()
    //Retourne l'id de l'utilisateur
    //
    public function getID(){
        return $this->m_iId;}

    //-
    //getPseudo()
    //Retourne le pseudo de l'utilisateur
    //Avec le paramètre haveIcon à true on affiche l'icon du groupe, avec le paramètre haveLink à true, on a un lien vers le profil
    public function getPseudo(bool $haveIcon = true, bool $haveLink = true){
        if ($haveLink) $pseudo_temp = '<a href="'.$this->getLink().'">'.$this->m_sPseudo.'</a>';
        else $pseudo_temp = $this->m_sPseudo;
        if($haveIcon) $pseudo_temp .= $this->getGroup()->getIcon();
        return $pseudo_temp;
    }

    //-
    //isActive()
    //Retourne true si l'utilisateur est activé, et false si l'utilisateur est désactivé
    //
    public function isActive(){
        return $this->m_bIsActive;}

    //-
    //isActiveText()
    //Retourne Activé si l'utilisateur est activé, et Désactivé si l'utilisateur est désactivé
    //
    public function isActiveText(){
        if ($this->isActive()) return 'Activé';
        else return 'Désactivé';    
    }

    //-
    //getGroup()
    //Retourne l'objet group de l'utilisateur
    //
    public function getGroup(){
        return $this->m_oGroup;}

    //-
    //getLink()
    //Retourne le lien vers le profil utilisateur
    //
    public function getLink(){
        return MAIN_PATH.'profil/'.$this->m_iId;}

    //-
    //isConnected()
    //Retourne true si l'utilisateur est connecté, false sinon
    //
    public function isConnected(){
        if ($this->m_iId != null) return true;
        else return false;
    }

    //-
    //getProfilPictureLink()
    //Retourne le lien de l'image de profil de l'utilisateur, ou en fonction de l'id passé en paramètre 
    //
    public function getProfilPictureLink($id = -1){
        if ($id = -1) $id = $this->m_iPictureId;
        if (in_array($id,$this->getListPic())) return MAIN_PATH.'assets/img/profil_pic/'.$id.'.png';
        else return MAIN_PATH.'assets/img/profil_pic/0.png';
    }

    //-
    //getListPic()
    //Retourne la liste des id des photos de profil disponibles
    //
    public function getListPic(){
        $tab = glob('assets/img/profil_pic/*.png');
        foreach ($tab as &$pic) {
            $pic = str_replace('assets/img/profil_pic/','',$pic);
            $pic = str_replace('.png','',$pic);
        }
        return $tab;
    }

    //-
    //getActiveTextColor(bool invert)
    //Retourne warning si l'utilisateur est désactivé et success si il est actif
    //
    public function getActiveTextColor(){
        if ($this->m_bIsActive) return 'success';
        else return 'warning';
    }
} 





?>