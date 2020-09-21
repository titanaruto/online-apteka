<?$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
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
$way_str = $_SERVER['DOCUMENT_ROOT'].'/obmen/';
$failFail = fopen($way_str.'failInstruktion.csv', 'a+');
$strCsv = array('CODE','IF_TOVAR','BITRIX_ID','TEXT','IF_IMG_FILE');
fputcsv($failFail, $strCsv);
$way_str = $_SERVER['DOCUMENT_ROOT'].'/obmen/';
$handle = fopen($way_str.'AI2098.csv', "r");



if(!CModule::IncludeModule("iblock")){
	die('Модуль Инфоблоков не подключен!');
}

function getFileJPG ($CODE='SOTONA') {
	$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
	if($CODE=='SOTONA') {
		$img = CFile::MakeFileArray($directory.'net-foto.jpg');
		return $img;
	}
	$dirArray = scandir($directory);
	unset($dirArray['0']);
	unset($dirArray['1']);
	foreach ($dirArray as $file) {
		$name = explode('.', $file);
		if($CODE == $name[0]) {
			$img = CFile::MakeFileArray($directory.$file);
			return $img;
		}
	}
	return 'FAIL';
}

	while ($res = fgetcsv($handle, 0, ';')){
		//pre($res);
		$strCsv = array();
		$strCsv[]=$res[0];
		$str = preg_replace('/ /', '', $res['0']);
		$arFilter = array(
			'IBLOCK_ID' => 2,
			'=EXTERNAL_ID' => $str,
		);
		$arSelect = array('IBLOCK_ID', 'ID','DETAIL_PICTURE','DETAIL_TEXT');
		$CIBlockElement = new CIBlockElement;
		$rsElement = $CIBlockElement->GetList(
			array('ID' => 'ASC'), 
			$arFilter, false, false, $arSelect
		);
		$triger = 10;
		while ($arElement = $rsElement->GetNext()) {
			pre($arElement['ID']);
			$strCsv[] = 'Y';
			$strCsv[] = $arElement['ID'];
			$ptn = "/^[0-9a-zA-Z_]{1,20}[\.]{1,1}htm/";
			preg_match($ptn,$arResult['DETAIL_TEXT'], $matches);
			if ('' != $arResult['DETAIL_TEXT'] && empty($matches)){
				$strCsv[]='MARION';
				$arLoadProductArray = Array();
			} else {
				$strCsv[]='NO_LINK';
				$arLoadProductArray = Array (
					'DETAIL_TEXT_TYPE'=>'text',
					'DETAIL_TEXT'=> $res['1'],//substr(html_entity_decode($res['1']),0,1993),
				);
			}
			$triger = 0;
			$prImage = getFileJPG($res[0]);
			if($prImage=='FAIL'){
				$strCsv[]='N';
				echo 'NO IMG<br/>';
				$prImage = getFileJPG();
			}
			$arLoadProductArray ['DETAIL_PICTURE'] = $prImage;
			$el = new CIBlockElement;
			$el->Update($arElement['ID'], $arLoadProductArray);
			fputcsv($failFail, $strCsv);
		} // end WHILE
		

		if($triger == 10) {
			$strCsv[]='N';
			$strCsv[]='';
			$strCsv[]='';
			fputcsv($failFail, $strCsv);
		}
		flush();
		time_nanosleep(0, 5000000);

	}// end WHILE

fclose($failFail);
fclose($handle);


/*
$head = $preResult[0];
unset($preResult[0]);
foreach ($preResult as $rkey => $row){
	foreach ($row as $kay => $valueQ){
		$str = trim($head[$kay]);
		$str = preg_replace('/ /', '_', $str);
		$result[$rkey][$str] = iconv('windows-1251', 'utf-8', $valueQ);
	}
}
unset($head);
unset($preResult);
$way = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
$dirArray = scandir($way);
$arResult = array();
foreach ($result as $element){
	pre($element);
			/*if(empty($element['TYPE']) && empty($element['BRAND']) 
			   && empty($element['DESCRIPTION']) && empty($element['QUALIFICATION'])
					&& empty($element['DOSAGE']) && empty($element['QUNTITY'])) 
			{
				$name = ucfirst(trim($element['NAME']));
			} else {
				$name = ucfirst(trim($element['TYPE'])).' '.trim($element['BRAND']).' ';
				$name .= strtolower(trim($element['DESCRIPTION']).' '.trim($element['QUALIFICATION']).' '.trim($element['DOSAGE']));
				if(!empty($element['QUNTITY']))
				{
					$name .=' '.strtolower($element['QUNTITY']);
				}
	}*/
//}



/*
$dirArray = array();
$arElements=array();
function getFileJPG($CODE='SOTONA') {
	$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
	if($CODE=='SOTONA') {
		$img = CFile::MakeFileArray($directory.'net-foto.jpg');
		return $img;
	}
	$dirArray = scandir($directory);
	unset($dirArray['0']);
	unset($dirArray['1']);
	foreach ($dirArray as $file) {
		$name = explode('.', $file);
		if($CODE == $name[0]) {
			$img = CFile::MakeFileArray($directory.$file);
			return $img;
		}
	}
	return false;
}

$arFilter = array(
	'IBLOCK_ID' => intval(2), 
	//'=ID'=>'12538',
	'ACTIVE' => 'Y',
	'DETAIL_PICTURE'=>false,
);
$arSelect = array(
	'IBLOCK_ID', 'ID','ACTIVE', 'DETAIL_PICTURE',
	'PROPERTY_EXTCODE', 'EXTERNAL_ID',
);
$CIBlockElement =  new CIBlockElement;
$rsElement = $CIBlockElement->GetList (
	array('ID' => 'ASC'), 
	$arFilter, 
	false, 
	false, 
	$arSelect
);

$el = new CIBlockElement;
while ($element = $rsElement->GetNext()) {
	$prImage = getFileJPG($element['EXTERNAL_ID']);
	if(true===(false===$prImage)){
		$prImage = getFileJPG();
	}
	$arLoadProductArray = array(
		'DETAIL_PICTURE'=> $prImage,
	);
	$el->Update($element['ID'], $arLoadProductArray);
	echo $element['ID'].'<br/>';
}

*/

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
