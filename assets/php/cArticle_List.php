<?php
include_once("cSQL.php");
include_once("cArticle.php");

class cArticle_List{
    private $m_aArticle;

    public function __construct(){
        $this->m_aArticle = [];
    }

    private function add(cArticle $article){
        array_push($this->m_aArticle, $article);
    }

    public function loadAll(int $limit = 10){
        $oSQL = new cSQL();

        $oSQL->execute('SELECT ID FROM ARTICLE LIMIT '.$limit);
        if ($oSQL->next()){
            $cArticleTemp = new cArticle();
            $cArticleTemp->loadByID($oSQL->colNameInt('ID'));
            $this->add($cArticleTemp);
        }
    }

    public function getArticles(){return $this->m_aArticle;}
} 





?>