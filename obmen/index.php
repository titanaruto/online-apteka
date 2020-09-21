<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");?>
<?/*
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
*/
/*
ini_set('error_reporting', E_ALL); //, E_ALL
ini_set('display_errors', 1); //, 1
ini_set('display_startup_errors', 1);//, 1
/* */

$obmen = new obmenGoods;
//$ChangeGoods =$obmen->implementationChangeGoods();
$ChangeGoods =$obmen->implementationPriceChange();
unset($ChangeGoods);

/*
$obmen = new obmenOneC();
$catalog =$obmen->doObmenCatalogFromBD();
$temp  = $obmen->implementationChangeGoods();
$arOrders = $obmen->runObmenOrders();
unset($obmen);
/* */

















?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
