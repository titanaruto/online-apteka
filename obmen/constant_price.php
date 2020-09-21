<?$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/online-apteka.com.ua";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
/* */

set_time_limit(60);

//$way_str = $_SERVER['DOCUMENT_ROOT'].'/obmen/';
//$failFail = fopen($way_str.'failInstruktion.csv', 'a+');
//$strCsv = array('CODE','IF_TOVAR','BITRIX_ID','TEXT','IF_IMG_FILE');
//fputcsv($failFail, $strCsv);
$way_str = $_SERVER['DOCUMENT_ROOT'].'/obmen/';
$handle = fopen($way_str.'constant_price.csv', "r");


if(!CModule::IncludeModule("iblock")){
	die('Модуль Инфоблоков не подключен!');
}
$CIBlockElement = new CIBlockElement;
while ($res = fgetcsv($handle, 0, ',')){
	$arFilter = array(
		'IBLOCK_ID' => intval(2),
		'=EXTERNAL_ID' => $res['0'],
	);
	$arSelect = array('IBLOCK_ID', 'ID','PROPERTY_CONSTANT_PRICE');
	$rsElement = $CIBlockElement->GetList(array('ID' => 'ASC'), $arFilter, false, false, $arSelect);
	while ($arElement = $rsElement->GetNext()) {
		pre($arElement);
		$PROP = array('CONSTANT_PRICE' => 'Y');
		CIBlockElement::SetPropertyValuesEx(
			intval($arElement['ID']),
			intVal(2), 
			$PROP
		);
	}
	
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
