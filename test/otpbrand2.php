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

$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/test/tmp.csv', 'w');
if(!CModule::IncludeModule("iblock")){
	die('Модуль Инфоблоков не подключен!');
}
$CIBlockElement = new CIBlockElement;
$list_break = array();
$dir = $_SERVER["DOCUMENT_ROOT"].'/upload/prom/';
$files = scandir($dir);
for ($i = 0; $i <= 10000; $i++) {
	//pre($files[$i]);
	$file = trim($files[$i],' ');
	//$res_array = explode(' ', $files[$i]);
	$code = explode('.',$file);
	array_push($list_break, $code[0]);
	$list_file[$code[0]] = $file;
}
array_splice($list_break,0,2);
$titles = array('Название_позиции', 'Идентификатор_товара', 'Ключевые_слова', 'Описание', 'Цена', 'Валюта', 'Скидка', 'Единица_измерения', 'Наличие', 'Ссылка_изображения', 'Производитель');
fputcsv($fp, $titles, ';', '"');
$externalList = array_chunk($list_break, 200);
//pre($externalList);
foreach ($externalList as $arExternal) {
	$arFilter = array(
		'IBLOCK_ID' => 2,
		'ACTIVE' => 'Y',
		'EXTERNAL_ID'=> $arExternal
	);
	//pre($arFilter);
	$arSelect = array(
		'IBLOCK_ID',
		'ID',
		'NAME',
		'EXTERNAL_ID',
		'PREVIEW_TEXT',
		'CATALOG_PRICE_2',
		'CATALOG_GROUP_2',
		'CATALOG_CURRENCY_2',
		'PROPERTY_KEYWORDS_VALUE', //Ключевые слова
		'CATALOG_AVAILABLE',
		'SHOW_DISCOUNT_PERCENT'
	);

	$rsElement = $CIBlockElement->GetList(array('ID'=>'ASC'),$arFilter,false,false,$arSelect);
	while ($arElement = $rsElement->GetNext()) {
		//pre($arElement);
		//Производитель & Ключевые слова
		$rsList = CIBlockElement::GetList(false, array('ID' => $arElement['ID'], 'IBLOCK_ID' => 2), false, false, array('PROPERTY_MANUFACTURER', 'PROPERTY_KEYWORDS'));
		if($arList = $rsList->Fetch()){
			$arList['PROPERTY_MANUFACTURER_VALUE'];
			$arList['PROPERTY_KEYWORDS_VALUE'];
		}
		//Цена
		$db_res = CPrice::GetList(array(),array("PRODUCT_ID" => $arElement['ID'],"CATALOG_GROUP_ID" => 1));
		if($ar_res = $db_res->Fetch()){
			if($ar_res["PRICE"] != 0) {
				CurrencyFormat($ar_res["PRICE"], $ar_res["CURRENCY"]);
			} else {
				$ar_res["PRICE"] = '';
			}
			//pre($ar_res["PRICE"]);
		}
		//Скидка
		$db_res2 = CPrice::GetList(array(),array("PRODUCT_ID" => $arElement['ID'],"CATALOG_GROUP_ID" => 2));
		if($ar_res2 = $db_res2->Fetch()){
			CurrencyFormat($ar_res2["PRICE"], $ar_res2["CURRENCY"]);
		}
		//Расчитываем % скидки
		$main_price = $ar_res["PRICE"];
		$main_price_dis = $ar_res2["PRICE"];
		if($main_price_dis != ''){
			$rezult = round((($main_price_dis-$main_price)*100/$main_price_dis), 0) . '%';
			//exit();
			if ($rezult == 0) {
				$rezult = '';
			}
		}
		//Изображение товара
		$img_result = 'http://online-apteka.com.ua/upload/prom/'.$list_file[$arElement['EXTERNAL_ID']];
		//pre($img_result);

		//Наличие
		if ($ar_res['PRICE'] !== '') {
			$avaliable = '+';
		} else {
			$avaliable = '@';
		}

		//Вывод едениц измерения
		if( CModule::IncludeModule("catalog") ) {
			$res_measure = CCatalogMeasure::getList();
			while($measure = $res_measure->Fetch()) {
				if($measure['ID']==$codeMeasure) return $measure['SYMBOL_RUS'];
				$measure_result = explode(' , ', $measure['SYMBOL_RUS'] . '.');
			}
			//pre($measure_result[0]);
		}

		$list = array (
			array(
				$arElement['NAME'],
				$arElement['ID'],
				$arList['PROPERTY_KEYWORDS_VALUE'],
				$arElement['PREVIEW_TEXT'],
				$ar_res['PRICE'],
				$ar_res['CURRENCY'],
				$rezult,
				$measure_result[0],
				$avaliable,
				$img_result,
				$arList['PROPERTY_MANUFACTURER_VALUE']
			)
		);

		foreach ($list as $fields) {
			fputcsv($fp, $fields, ';', '"');
		}
	}
}
fclose($fp);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
