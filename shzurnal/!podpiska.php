<?
include_once $_SERVER["DOCUMENT_ROOT"].'/redirect.php';
define("NO_KEEP_STATISTIC", true); //Не учитываем статистику
define("NOT_CHECK_PERMISSIONS", true); //Не учитываем права доступа
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");



/*
pre($_GET);

pre($_POST);*/

$_SESSION['MY_AJAX'] = $_POST;

pre($_SESSION['MY_AJAX']);






//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");