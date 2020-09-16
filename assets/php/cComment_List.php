<?php
include_once("cSQL.php");
include_once("cComment.php");

class cComment_List{
    private $m_aoComment;

    public function __construct(){
        $this->m_aoComment = [];
    }


    private function add(cComment $comment){
        array_push($this->m_aoComment, $comment);
    }

    //-
    //loadByArticleId(int id)
    //
    //Charge tout les commentaires par rapport à l'id d'un article'
    public function loadByArticleId(int $id){
        $this->m_aoComment = [];
        $oSQL = new cSQL();
        $oSQL->execute('SELECT ID FROM COMMENTS WHERE ARTICLE_ID = ? AND IS_DELETED = false ORDER BY ID DESC',[$id]);
        while ($oSQL->next()){
            $cCommentTemp = new cComment();
            $cCommentTemp->loadById($oSQL->colNameInt('ID'));
            $this->add($cCommentTemp);
        }
    }

    //-
    //addComment
    //
    //Ajoute un article
    public function addComment(cArticle $article, cUser $user, string $text){
        $oSQL = new cSQL();
        $oSQL->execute('INSERT INTO COMMENTS (ARTICLE_ID, USER_ID, TEXT) VALUES (?,?,?)',
                        [$article->getId(),$user->getId(),$text]);
    }

    //-
    //getComments()
    //Retourne une liste d'objet cComment
    //
    public function getComments(){
        return $this->m_aoComment;}

    //-
    //countComments()
    //Retourne le nombre de commentaires
    //
    public function countComments(){
        return count($this->m_aoComment);}
} 





?>