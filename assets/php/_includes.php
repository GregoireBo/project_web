<?php
include_once("cUser.php");
include_once("cArticle_List.php");
include_once("cUser_List.php");

$user = new cUser();
$user->loadByID(1);
$userl = new cUser_List();
$userl->loadAll();
var_dump($userl);
//var_dump($user->switchActive());
//var_dump($user->getGroup()->getPermList());
//$user->connect('Greg','4');
//var_dump($user->inscript('Yannis','e'));

$article = new cArticle_List();
$article->loadAll(2);
//var_dump($article);

?>