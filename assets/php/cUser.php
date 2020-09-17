<?php
include_once("cSQL.php");
include_once("cGroup.php");
include_once("cArticle_List.php");

class cUser
{
    private $m_iId;
    private $m_sPseudo;
    private $m_bIsActive;
    private $m_oArticles;
    private $m_oGroup;
    private $m_sToken;
    private $m_iPictureId;

    public function __construct()
    {
        $this->m_oGroup = new cGroup();
        $this->m_oArticles = null;
    }

    public function load(int $id, string $pseudo, int $grp_id, bool $is_active, string $token, int $pictureId)
    {
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
    public function loadByID($id)
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,PSEUDO,GRP_ID,IS_ACTIVE,TOKEN,PICTURE_ID FROM USER WHERE ID=?', [$id]);
        if ($oSQL->next()) {
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
    public function connect(string $pseudo, string $pass)
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,PASSWORD,IS_ACTIVE FROM USER WHERE pseudo=?', [$pseudo]);
        if ($oSQL->next()) {
            if ($oSQL->ColName('IS_ACTIVE') == 1) {
                if (password_verify($pass, $oSQL->ColName('PASSWORD'))) {
                    $this->loadByID($oSQL->colNameInt('ID'));
                    $_SESSION['PSEUDO'] = $this->m_sPseudo;
                    $_SESSION['TOKEN'] = $this->m_sToken;
                    return "ok";
                } else return "nok";
            } else return "not_active";
        } else return "nok";
    }

    //-
    //connectToken(string pseudo, string token)
    //Retourne true si réussi et false si échoué
    //Permet de vérifier si le pseudo et le token correspondent à un utilisateur, puis charge l'utilisateur correspondant dans l'objet
    public function connectToken(string $pseudo, string $token)
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID,TOKEN FROM USER WHERE pseudo=? AND TOKEN =? AND IS_ACTIVE=true', [$pseudo, $token]);
        if ($oSQL->next()) {
            $this->loadByID($oSQL->colNameInt('ID'));
            $_SESSION['PSEUDO'] = $this->m_sPseudo;
            $_SESSION['TOKEN'] = $this->m_sToken;
            return true;
        } else return false;
    }

    //-
    //inscript(string pseudo, string pass)
    //Retourne val si réussi ou un code d'erreur sinon
    //Permet d'inscrire un utilisateur après avoir vérifié que son pseudo n'existait pas déjà, puis avoir hashé son mot de passe
    public function inscript(string $pseudo, string $pass)
    {
        $oSQL = new cSQL();
        if (!$this->pseudo_exist($pseudo)) {
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $token = $this->genToken();
            if ($oSQL->execute('INSERT INTO USER (PSEUDO,PASSWORD,IS_ACTIVE,TOKEN,GRP_ID) VALUES (?,?,?,?,?)', [$pseudo, $pass, 0, $token, 2])) {
                return 'val';
            } else return 'errInsert';
        } else return 'errPseudoExist';
    }

    //-
    //update(string pseudo, int pictureId)
    //Retourne val si réussi ou un code d'erreur sinon
    //Permet de modifier l'utilisateur (pseudo et image)
    public function update(string $pseudo, int $pictureId)
    {
        $oSQL = new cSQL();
        if ($this->getPseudo(false, false) == $pseudo || !$this->pseudo_exist($pseudo)) {
            if ($oSQL->execute('UPDATE USER SET PSEUDO=?, PICTURE_ID=? WHERE ID = ?', [$pseudo, $pictureId, $this->getId()])) {
                $_SESSION['PSEUDO'] = $pseudo;
                return 'ok';
            } else return 'nok';
        } else return 'pseudo_exist';
    }

    //-
    //changePassword
    //Retourne val si réussi ou un code d'erreur sinon
    //Permet de modifier le mot de passe de l'utilisateur
    public function changePassword(string $pass, string $newPass, string $newPass2)
    {
        $oSQL = new cSQL();
        if ($newPass == $newPass2) {
            $oSQL->execute('SELECT PASSWORD FROM USER WHERE ID=?', [$this->getId()]);
            if ($oSQL->next()) {
                if (password_verify($pass, $oSQL->colName('PASSWORD'))) {
                    $newPass = password_hash($newPass, PASSWORD_DEFAULT);
                    if ($oSQL->execute('UPDATE USER SET PASSWORD=?WHERE ID = ?', [$newPass, $this->getId()])) {
                        return 'ok';
                    } else return 'nok';
                } else return 'wrong_pass';
            } else return "nok";
        } else return 'pass_not_same';
    }

    //-
    //deconnect()
    //
    //Déconnecte l'utilisateur
    public function deconnect()
    {
        session_destroy();
    }

    //-
    //switchActive()
    //Retourne true si réussi et false si échoué
    //Permet d'activer l'utilisateur s'il est désactivé et de le désactiver si il est activé.
    public function switchActive()
    {
        $oSQL = new cSQL();
        if ($this->isActive()) return $oSQL->execute('UPDATE USER SET IS_ACTIVE=false WHERE ID=?', [$this->getID()]);
        else return $oSQL->execute('UPDATE USER SET IS_ACTIVE=true WHERE ID=?', [$this->getID()]);
    }

    //vérifie si un pseudo existe
    private function pseudo_exist($pseudo)
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID FROM USER WHERE pseudo=?', [$pseudo]);
        if ($oSQL->next()) {
            return true;
        } else return false;
    }

    //-
    //id_exist(int id)
    //Retourne true si l'utilisateur existe, false sinon
    //Permet de savoir si un utilisateur existe par rapport à son id
    public function id_exist(int $id)
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID FROM USER WHERE ID=?', [$id]);
        if ($oSQL->next()) {
            return true;
        } else return false;
    }

    //-
    //havePerm(string perm)
    //Retourne true si l'utilisateur à la permission, false sinon
    //Permet de savoir si un utilisateur à la permission de ...
    public function havePerm(string $perm)
    {
        return $this->m_oGroup->havePerm($perm);
    }

    //-
    //canCreateArticle()
    //Retourne true si l'utilisateur à la permission de créer un article, false sinon
    //Permet de savoir si un utilisateur à la permission de créer un article
    public function canCreateArticle()
    {
        return ($this->isConnected() && $this->havePerm('CREATE_ARTICLE'));
    }

    //-
    //canEditArticle()
    //Retourne true si l'utilisateur à la permission de modifier un article, false sinon
    //Permet de savoir si un utilisateur à la permission de modifier un article (si il en est propriétaire OU qu'il à la permission d'édit n'importe lequel)
    public function canEditArticle(cArticle $article)
    {
        if ($this->isConnected()) {
            if ($article->getUser()->getId() == $this->getId()) {
                return true;
            } else if ($this->havePerm('EDIT_ARTICLE')) {
                return true;
            } else return false;
        } else return false;
    }

    //-
    //canDeleteArticle()
    //Retourne true si l'utilisateur à la permission de supprimer un article, false sinon
    //Permet de savoir si un utilisateur à la permission de supprimer un article (meme si il n'en est pas propriétaire)
    public function canDeleteArticle()
    {
        return ($this->isConnected() && $this->havePerm('DELETE_ARTICLE'));
    }

    //genToken()
    //genere un token de connexion utilisateur
    private function genToken()
    {
        return 'CONN.' . strtoupper(bin2hex(random_bytes(25)));
    }

    //-
    //changeGrp(int id)
    //Retourne true si réussi et false si échoué
    //Change le groupe de l'utilisateur
    public function changeGrp(int $id)
    {
        $oSQL = new cSQL();
        return $oSQL->execute('UPDATE USER SET GRP_ID=? WHERE ID=?', [$id, $this->getID()]);
    }

    //-
    //follow
    //Retourne true si réussi et false si échoué
    //Suis un utilisateur
    public function follow(cUser $otherUser)
    {
        $oSQL = new cSQL();
        if (!$this->isFollowing($otherUser)) {
            return $oSQL->execute('INSERT INTO FOLLOW (USER_ID,FOLLOWED_USER_ID) VALUES (?,?)', [$this->getID(), $otherUser->getId()]);
        } else return false;
    }

    //-
    //unfollow
    //Retourne true si réussi et false si échoué
    //permet de ne plus suivre un utilisateur
    public function unfollow(cUser $otherUser)
    {
        $oSQL = new cSQL();
        if ($this->isFollowing($otherUser)) {
            return $oSQL->execute('DELETE FROM FOLLOW WHERE USER_ID=? AND FOLLOWED_USER_ID=?', [$this->getID(), $otherUser->getId()]);
        } else return false;
    }

    //-
    //like
    //Retourne true si réussi et false si échoué
    //aime un article
    public function like(cArticle $otherArticle)
    {
        $oSQL = new cSQL();
        if (!$this->hasLiked($otherArticle)) {
            return $oSQL->execute('INSERT INTO LIKES (USER_ID,LIKED_ARTICLE_ID) VALUES (?,?)', [$this->getID(), $otherArticle->getId()]);
        } else return false;
    }
    //-
    //undoLike
    //Retourne true si réussi et false si échoué
    //n'aime plus un article
    public function undoLike(cArticle $otherArticle)
    {
        $oSQL = new cSQL();
        if ($this->hasLiked($otherArticle)) {
            return $oSQL->execute('DELETE FROM LIKES WHERE USER_ID=? AND LIKED_ARTICLE_ID=?', [$this->getID(), $otherArticle->getId()]);
        } else return false;
    }

    //-
    //getID()
    //Retourne l'id de l'utilisateur
    //
    public function getID()
    {
        return $this->m_iId;
    }

    //-
    //getPseudo()
    //Retourne le pseudo de l'utilisateur
    //Avec le paramètre haveIcon à true on affiche l'icon du groupe, avec le paramètre haveLink à true, on a un lien vers le profil
    public function getPseudo(bool $haveIcon = true, bool $haveLink = true)
    {
        if ($haveLink) $pseudo_temp = '<a href="' . $this->getLink() . '">' . $this->m_sPseudo . '</a>';
        else $pseudo_temp = $this->m_sPseudo;
        if ($haveIcon) $pseudo_temp .= $this->getGroup()->getIcon();
        return $pseudo_temp;
    }

    //-
    //isActive()
    //Retourne true si l'utilisateur est activé, et false si l'utilisateur est désactivé
    //
    public function isActive()
    {
        return $this->m_bIsActive;
    }

    //-
    //isActiveText()
    //Retourne Activé si l'utilisateur est activé, et Désactivé si l'utilisateur est désactivé
    //
    public function isActiveText()
    {
        if ($this->isActive()) return 'Activé';
        else return 'Désactivé';
    }

    //-
    //getGroup()
    //Retourne l'objet group de l'utilisateur
    //
    public function getGroup()
    {
        return $this->m_oGroup;
    }

    //-
    //getLink()
    //Retourne le lien vers le profil utilisateur
    //
    public function getLink()
    {
        return MAIN_PATH . 'profil/' . $this->m_iId;
    }

    //-
    //isConnected()
    //Retourne true si l'utilisateur est connecté, false sinon
    //
    public function isConnected()
    {
        if ($this->m_iId != null) return true;
        else return false;
    }

    //-
    //getProfilPictureLink()
    //Retourne le lien de l'image de profil de l'utilisateur, ou en fonction de l'id passé en paramètre 
    //
    public function getProfilPictureLink($id = -1)
    {
        if ($id = -1) $id = $this->m_iPictureId;
        if (in_array($id, $this->getListPic())) return MAIN_PATH . 'assets/img/profil_pic/' . $id . '.png';
        else return MAIN_PATH . 'assets/img/profil_pic/0.png';
    }

    //-
    //getProfilPictureId()
    //Retourne l'id de l'image de profil de l'utilisateur
    //
    public function getProfilPictureId()
    {
        return $this->m_iPictureId;
    }

    //-
    //getListPic()
    //Retourne la liste des id des photos de profil disponibles
    //
    public function getListPic()
    {
        $tab = glob('assets/img/profil_pic/*.png');
        foreach ($tab as &$pic) {
            $pic = str_replace('assets/img/profil_pic/', '', $pic);
            $pic = str_replace('.png', '', $pic);
        }
        return $tab;
    }

    //-
    //getActiveTextColor()
    //Retourne warning si l'utilisateur est désactivé et success si il est actif
    //
    public function getActiveTextColor()
    {
        if ($this->m_bIsActive) return 'success';
        else return 'warning';
    }

    //-
    //getArticles()
    //Retourne la liste des articles de l'utilisateur
    //
    public function getArticles()
    {
        if ($this->m_oArticles == null) {
            $this->m_oArticles = new cArticle_List();
            $this->m_oArticles->loadByUserId($this->getId());
        }
        return $this->m_oArticles->getArticles();
    }

    //-
    //getListFollowing()
    //Retourne la liste des utilisateurs que l'user follow
    //
    public function getListFollowing($limit = 0)
    {
        $oSQL = new cSQL();
        $tabUser = [];
        $textLimit = '';
        if ($limit != 0) $textLimit = ' LIMIT ' . $limit;
        $oSQL->execute('SELECT FOLLOWED_USER_ID as ID FROM FOLLOW WHERE USER_ID = ? ORDER BY ID DESC' . $textLimit, [$this->getId()]);
        while ($oSQL->next()) {
            $oUserTemp = new cUser();
            $oUserTemp->loadByID($oSQL->colNameInt('ID'));
            array_push($tabUser, $oUserTemp);
        }
        return $tabUser;
    }

    //-
    //isFollowing()
    //Retourne true si l'utilisateur follow l'utilisateur passé en paramètre, false sinon
    //
    public function isFollowing(cUser $otherUser)
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID FROM FOLLOW WHERE USER_ID = ? AND FOLLOWED_USER_ID = ?', [$this->getId(), $otherUser->getId()]);
        if ($oSQL->next()) { {
            }
            return true;
        } else return false;
    }

    //-
    //getListLikedArticles()
    //Retourne la liste des articles que l'utilisateur aime
    //
    public function getListLikedArticles($limit = 0)
    {
        $oSQL = new cSQL();
        $tabUser = [];
        $textLimit = '';
        if ($limit != 0) $textLimit = ' LIMIT ' . $limit;
        $oSQL->execute('SELECT LIKED_ARTICLE_ID as ID FROM LIKES WHERE USER_ID = ? ORDER BY ID DESC' . $textLimit, [$this->getId()]);
        while ($oSQL->next()) {
            $oArticleTemp = new cArticle();
            $oArticleTemp->loadByID($oSQL->colNameInt('ID'));
            array_push($tabUser, $oArticleTemp);
        }
        return $tabUser;
    }

    //-
    //hasLiked()
    //Retourne true si l'utilisateur aime l'article passé en paramètre, false sinon
    //
    public function hasLiked(cArticle $otherArticle)
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID FROM LIKES WHERE USER_ID = ? AND LIKED_ARTICLE_ID = ?', [$this->getId(), $otherArticle->getId()]);
        if ($oSQL->next()) {
            return true;
        } else return false;
    }

    //-
    //countFollow()
    //Retourne le nombre de follow de l'utilisateur
    //
    public function countFollow()
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT COUNT(ID) as CNT FROM FOLLOW WHERE USER_ID = ?', [$this->getId()]);
        if ($oSQL->next()) {
            return $oSQL->colNameInt('CNT');
        } else return 0;
    }

    //-
    //countLikes()
    //Retourne le nombre de likes de l'utilisateur
    //
    public function countLikes()
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT COUNT(ID) as CNT FROM LIKES WHERE USER_ID = ?', [$this->getId()]);
        if ($oSQL->next()) {
            return $oSQL->colNameInt('CNT');
        } else return 0;
    }

    //-
    //getListArticlesFromFollowedUsers()
    //Retourne les articles récemment sortis en fonction des utilisateurs suivis par notre profil
    //
    public function getListArticlesFromFollowedUsers($fromWeek = 2, $limit = 5)
    {
        $oSQL = new cSQL();
        $tabUser = [];
        $textLimit = '';
        if ($limit != 0) $textLimit = ' LIMIT ' . $limit;
        $oSQL->execute('SELECT ARTICLE.ID as ID FROM USER, FOLLOW, ARTICLE
        WHERE FOLLOW.USER_ID = ?
        AND FOLLOW.FOLLOWED_USER_ID = USER.ID
        AND USER.ID = ARTICLE.USER_ID
        AND ARTICLE.IS_DELETED = 0
        AND (WEEK(article.TIMESTAMP)) > (WEEk(CURDATE()) - ?)
        ORDER BY ID DESC' . $textLimit, [$this->getId(), $fromWeek]);
        while ($oSQL->next()) {
            $oArticleTemp = new cArticle();
            $oArticleTemp->loadByID($oSQL->colNameInt('ID'));
            array_push($tabUser, $oArticleTemp);
        }
        return $tabUser;
    }

    //-
    //countArticlesFromFollowedUsers()
    //Retourne le nombre d'articles des utilisateurs suivis par notre profil
    //
    public function countArticlesFromFollowedUsers($fromWeek = 2)
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT COUNT(ARTICLE.ID) as CNT FROM ARTICLE, USER, FOLLOW
        WHERE FOLLOW.USER_ID = ?
        AND FOLLOW.FOLLOWED_USER_ID = user.ID
        AND user.ID = article.USER_ID
        AND article.IS_DELETED = 0
        AND (WEEK(article.TIMESTAMP)) > (WEEk(CURDATE()) - ?)', [$this->getId(), $fromWeek]);
        if ($oSQL->next()) {
            return $oSQL->colNameInt('CNT');
        } else return 0;
    }
}
