<?/*

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
  global $APPLICATION; 
  $aMenuLinksExt=$APPLICATION->IncludeComponent("bitrix:menu.sections", "", array( 
  "IS_SEF" => "Y", 
  "SEF_BASE_URL" => "/catalog/",  //каталог инфоблока на сайте
  "SECTION_PAGE_URL" => "#SECTION_CODE#/", //ID раздела
  "DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/", //полный путь к элементу инфоблока
  "IBLOCK_TYPE" => "catalog",  //ID типа инфоблока из которого выводим
  "IBLOCK_ID" => "2", // ID инфоблока из которого выводим
  "DEPTH_LEVEL" => "3", //уровень вложенности, этой цифрой можно выводить подразделы разделов если иерархия многоуровневая
  "CACHE_TYPE" => "A", 
  "CACHE_TIME" => "36000000" 
  ), 
false 
); 
  $aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks); 
*/?>


<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if(CModule::IncludeModule("iblock")) {

	$str= trim($_SERVER[REQUEST_URI],'/');
	$url = explode('/', $str);
	$currentSectionCode = array_pop($url);
	
	$arrFilter = Array('IBLOCK_ID'=> $IBLOCK_ID, 'ACTIVE'=>'Y', 'CODE' => $currentSectionCode);
	$arrSelect = Array('IBLOCK_ID','ID','ACTIVE','CODE','NAME');
	$res = CIBlockSection::GetList(Array("CODE"=>"ASC"), $arrFilter, false, $arrSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$ParentId = $arFields['ID'];
	}
	$IBLOCK_ID = 2;// указываем из акого инфоблока берем элементы
	$arOrder = Array("NAME"=>"ASC");    // сортируем по свойству NAME по возрастанию 
	$arFilter = Array("IBLOCK_ID"=>$IBLOCK_ID, "ACTIVE"=>"Y", "SECTION_ID"=>$ParentId);
	$arSelect = Array("ID", "NAME", "IBLOCK_ID", "SECTION_PAGE_URL");
	$res = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
    while($ob = $res->GetNextElement())
    {
	    $arFields = $ob->GetFields();// берем поля
	    // начинаем наполнять массив aMenuLinksExt нужными данными
	    $aMenuLinksExt[] = Array(
	        $arFields['NAME'],
	        $arFields['SECTION_PAGE_URL'],
	    );
	    
    }        //     while($ob = $res->GetNextElement())
}    //     if(CModule::IncludeModule("iblock"))
$aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks);

?> 