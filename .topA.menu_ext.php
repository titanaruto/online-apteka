<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
  global $APPLICATION; 
  $aMenuLinksExt=$APPLICATION->IncludeComponent("bitrix:menu.sections", "", array( 
  "IS_SEF" => "Y", 
  "SEF_BASE_URL" => "/catalog/",  //каталог инфоблока на сайте
  "SECTION_PAGE_URL" => "#SECTION_ID#/", //ID раздела
  "DETAIL_PAGE_URL" => "#SECTION_ID#/#ELEMENT_ID#.html", //полный путь к элементу инфоблока
  "IBLOCK_TYPE" => "138",  //ID типа инфоблока из которого выводим
  "IBLOCK_ID" => "2", // ID инфоблока из которого выводим
  "DEPTH_LEVEL" => "3", //уровень вложенности, этой цифрой можно выводить подразделы разделов если иерархия многоуровневая
  "CACHE_TYPE" => "A", 
  "CACHE_TIME" => "36000000" 
  ), 
false 
); 
  $aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks); 
?>