<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!CModule::IncludeModule("iblock")){
    die('Модуль Инфоблоков не подключен!');
}
//$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
//$arFilter = Array("IBLOCK_ID"=>12, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
//$res = CIBlockElement::GetList(Array(), $arFilter, false, "", $arSelect);
//$i = 1;
//while($ob = $res->GetNextElement())
//{
//    $arFields = $ob->GetFields()[ID];
//    echo $i . ". " . $arFields . "<br />";
//    $i ++;
//}

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
$arFilter = Array("IBLOCK_ID"=>IntVal(12), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, "", $arSelect);
$i = 1;
while($ob = $res->GetNextElement()){
    echo "<pre>";
    echo $i . ". " . $ob->GetFields()[ID] . "; " .
        $ob->GetFields()[NAME] . "; " .
        $ob->GetProperties()[CITY][VALUE] . "; " .
        $ob->GetProperties()[STREET][VALUE] . "; " .
        $ob->GetProperties()[ADRES][VALUE] . "; " .
        $ob->GetProperties()[HOUSE][VALUE] . "; " .
        $ob->GetProperties()[TELEFON][VALUE] . "<br />";
    print_r($arFields);
    $arProps = $ob->GetProperties();
    print_r($arProps);
    $i ++;
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");