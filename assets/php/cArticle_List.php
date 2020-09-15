<?php
include_once("cSQL.php");
include_once("cArticle.php");

class cArticle_List{
    private $m_aoArticle;

    public function __construct(){
        $this->m_aoArticle = [];
    }

    private function add(cArticle $article){
        array_push($this->m_aoArticle, $article);
    }

    //-
    //loadAll(int limit = 10)
    //
    //Charge un certain nombre d'article du blog dans la limite passée en paramètre (10 par défaut)
    public function loadAll(int $offset = 0, int $limit = 10){
        $oSQL = new cSQL();

        $oSQL->execute('SELECT ID FROM ARTICLE WHERE IS_DELETED = 0 ORDER BY ID DESC LIMIT '.$offset.', '.$limit);
        while ($oSQL->next()){
            $cArticleTemp = new cArticle();
            $cArticleTemp->loadByID($oSQL->colNameInt('ID'));
            $this->add($cArticleTemp);
        }
    }

    //-
    //loadByUserId
    //
    //Charge les articles d'un utilisateur
    public function loadByUserId(int $user_id){
        $oSQL = new cSQL();

        $oSQL->execute('SELECT ID FROM ARTICLE WHERE USER_ID=? AND IS_DELETED = 0 ORDER BY ID DESC',[$user_id]);
        while ($oSQL->next()){
            $cArticleTemp = new cArticle();
            $cArticleTemp->loadByID($oSQL->colNameInt('ID'));
            $this->add($cArticleTemp);
        }
    }

    //-
    //getArticles()
    //Retourne une liste d'objet cArticle
    //
    public function getArticles(){
        return $this->m_aoArticle;
    }
    
    //-
    //getTotalNumberArticles()
    //Retourne le nombre total d'articles
    //
    public function getTotalNumberArticles()
    {
        $oSQL = new cSQL();
        $oSQL->execute('SELECT COUNT(ID) AS CNT FROM ARTICLE WHERE IS_DELETED = 0');
        if ($oSQL->next()) {
            return $oSQL->colNameInt('CNT');
        }
    }
} 
?>