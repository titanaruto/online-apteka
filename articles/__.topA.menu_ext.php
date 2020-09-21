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
	while($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$ParentId = $arFields['ID'];
	}
	$IBLOCK_ID = 5;// указываем из акого инфоблока берем элементы
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