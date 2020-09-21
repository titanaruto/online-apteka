<?
include_once $_SERVER["DOCUMENT_ROOT"].'/redirect.php';
define("NO_KEEP_STATISTIC", true); //Не учитываем статистику
define("NOT_CHECK_PERMISSIONS", true); //Не учитываем права доступа
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");



/*
pre($_GET);
pre($_POST);
*/
$_SESSION['MY_AJAX'] = $_POST;

pre($_SESSION['MY_AJAX']);

$newArray = array();
function format_to_save_string ($text) {
	$text = addslashes($text);
	$text= htmlspecialchars ($text);
	$text = addslashes($text);
	//$text = preg_replace("/[^/+\|]/i", "", $text);
	$text = str_replace("'/\|", '', $text);
	$text = str_replace("\r\n", ' ', $text);
	$text = str_replace("\n", ' ', $text);
	echo $text.'<br/>';
	return $text;
}

foreach ($_POST as $key => $value){
	$newArray[$key] = format_to_save_string ($value);
}


if(CModule::IncludeModule("iblock")){
	$PROP = array(
			'NAME' => $newArray['name'],
			'TOWN' => $newArray['town'],
			'INDEX' => $newArray['index'],
			'EMAIL' => $newArray['email'],
			'REGION' => $newArray['region'],
			'ADRESS' => $newArray['adress'],
			'PHONE' => $newArray['phone'],
	);

	$arLoadProductArray = Array(
			"MODIFIED_BY"    => intVal(1),
			"IBLOCK_ID"      => intVal(18),
			'NAME' => $newArray['name'],
			"PROPERTY_VALUES"=> $PROP
	);
	
	$el = new CIBlockElement;
	if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
		pre($arLoadProductArray);
	}
	
	
	
}
