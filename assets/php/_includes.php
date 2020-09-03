<?php
const MAIN_PATH = '/project_web/';

session_start();
setlocale(LC_ALL,'fr_FR');

if (!isset($no_nav)) $no_nav = false;


include_once("cUser.php");
include_once("cUser_List.php");
include_once("cArticle.php");
include_once("cArticle_List.php");
include_once("cGroup.php");
include_once("cGroup_List.php");
include_once("cPerm.php");
include_once("cPerm_List.php");

$user = new cUser();
if (isset($_SESSION['PSEUDO']) && isset($_SESSION['TOKEN']))
{
    $user->connectToken($_SESSION['PSEUDO'],$_SESSION['TOKEN']);
}



include_once("_head.php");
if (!$no_nav) include_once("_nav.php");

?>

