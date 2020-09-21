<?$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/online-apteka.com.ua";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
set_time_limit(120);
/*
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
/* */

if(!CModule::IncludeModule("iblock")){
    die('Модуль Инфоблоков не подключен!');
}
$CIBlockElement = new CIBlockElement;

$string = 'корв';

$arFilter = Array(
    "IBLOCK_ID"=>IntVal(2),
    "NAME"=>"%$string%",
    "ACTIVE"=>"Y"
);
$arSelect = Array("IBLOCK_ID", "ID", "NAME");

$res = $CIBlockElement->GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->GetNextElement()){
 $arFields = $ob->GetFields();
 pre($arFields);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
