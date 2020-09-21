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
set_time_limit(6000);
//тут перебираем все товары подряд, без выборки измененных
//$arID  массив элементов для которых устанавливаем рекомендованные и аналоги
if(!CModule::IncludeModule("iblock")){
	die('Модуль Инфоблоков не подключен!');
}
function getRecipientGoods($EXT_KEY=null){
	if($EXT_KEY===null) return false;
	$arFilter = array(
		'IBLOCK_ID' => intval(2),
		'=EXTERNAL_ID' => $EXT_KEY,
	);
	$arSelect = array('IBLOCK_ID', 'ID');
	$CIBlockElement = new CIBlockElement;
	$rsElement = $CIBlockElement->GetList(array('ID' => 'ASC'), $arFilter, false, false, $arSelect);
	while ($arElement = $rsElement->GetNext()) {
		return  $arElement;
	}
	return false;
}
function strToArID($str=null){
	if($str===null) return false;
	$text = str_replace(" ",',',$str);
	$text = preg_replace('/\s/', ',', $text);
	$str = trim($text,',');
	$res = explode(',', $str);
	$arResipient = array();
	foreach ($res as $code) {
		if(!empty($code)){
			$a = getRecipientGoods($code,'SHORT');
			if(!empty($a)) {
				$arResipient[] = $a;
			}
		}
	}
	return $arResipient;
}
$arFilter = array(
	'IBLOCK_ID' => intval(2), 
	'ACTIVE' => 'Y',
	array(
        "LOGIC" => "OR",
        array('!PROPERTY_RECOMMEND_CODE'=>false, '!PROPERTY_ANALOG_CODE'=>false),
        array('!PROPERTY_RECOMMEND_CODE'=>false),
        array('!PROPERTY_ANALOG_CODE'=>false),
    ),
	//'>DATE_MODIFY_FROM'=>ConvertTimeStamp(time()-3600,'FULL')
);
$arSelect = array(
	'IBLOCK_ID', 'ID','ACTIVE', 
	'PROPERTY_RECOMMEND_CODE', 
	'PROPERTY_ANALOG_CODE'
);
$rsElement = CIBlockElement::GetList (
	array('ID' => 'ASC'), 
	$arFilter, 
	false, 
	false, 
	$arSelect
);
while ($element = $rsElement->GetNext()) {
	usleep(20000);
	if (!empty($element['~PROPERTY_RECOMMEND_CODE_VALUE'])) {
		$arID = strToArID($element['~PROPERTY_RECOMMEND_CODE_VALUE']);
		if(!empty($arID)) {
			$arVal = array();
			foreach ($arID as $id){
				$arVal[]=$id['ID'];
			}
			CIBlockElement::SetPropertyValuesEx(
				$element['ID'],
				intVal(intval(2)),
				array('RECOMMEND'=>$arVal)
			);
		}
	}
	if (!empty($element['~PROPERTY_ANALOG_CODE_VALUE'])) {
		$arID = strToArID($element['~PROPERTY_ANALOG_CODE_VALUE']);
		if(!empty($arID)) {
			$arVal = array();
			foreach ($arID as $id){
				$arVal[]=$id['ID'];
			}
			CIBlockElement::SetPropertyValuesEx(
				$element['ID'],
				intVal(intval(2)),
				array('ANALOG'=>$arVal)
			);
		}
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
