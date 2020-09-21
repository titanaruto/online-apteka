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

	set_time_limit(1600);
	if(!CModule::IncludeModule("iblock")){
		die('Модуль Инфоблоков не подключен!');
	}

	$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
	$dirArray = scandir($directory);
	unset($dirArray['0']);
	unset($dirArray['1']);
	unset($dirArray['2']);
	$regexp = '/^[a-zA-Z0-9А-Яа-я_-]{3,10}\.{1,1}([jpegnng]{3,4}){1,1}$/i';
	$arSrch = array();
	foreach ($dirArray as $file) {
		$a = 0;
		$a = preg_match($regexp, $file, $match);
		if($a > 0){
			$name = explode('.', $file);
			if($name[0] !== ''){
				$arSrch[$name[0]][CODE]=$name[0];
				$arSrch[$name[0]][ORIGINAL_NAME]=$file;
			}
		}
	}
	pre('Всего картинок в наличии: '.count($arSrch));
	

	$arElements=array();
	$arFilter = array(
		'IBLOCK_ID' => 2,
		'!ID' => false,
		'DETAIL_PICTURE'=>false,
		//'PREVIEW_PICTURE'=>false,
		'ACTIVE' => 'Y',
	);
	$arSelect = array('IBLOCK_ID','ID','EXTERNAL_ID','DETAIL_PICTURE','PREVIEW_PICTURE','NAME');
	$CIBlockElement = new CIBlockElement;
	$rsElement = $CIBlockElement->GetList(array('ID' => 'ASC'), $arFilter, false, false, $arSelect);
	$el = new CIBlockElement;
	while ($arElement = $rsElement->GetNext()) {
		if(!isset($arSrch[$arElement[EXTERNAL_ID]][ORIGINAL_NAME])){
			$arElements[$arElement[EXTERNAL_ID]] = $arElement;
		}
	}
	pre('Всего товаров без картинок: '.count($arElements));
	echo '<table>';
	foreach($arElements as $arElement) {
		echo '<tr>';
		echo '<td>Нет картинки для товара</td>';
		echo '<td> '.$arElement[EXTERNAL_ID].' </td>';
		echo '<td> '.$arElement[NAME].' </td>';
		echo '</tr>';
	}
	echo '</table>';
	exit();
	
	/*	if(isset($arElement['DETAIL_PICTURE'])) {
			$CFile = new CFile;
			$rsFile = $CFile->GetByID($arElement['DETAIL_PICTURE']);
			$arFile = $rsFile->Fetch();
		}
		//pre($arElement);
		//pre($arFile);
		if(isset($arSrch[$arElement[EXTERNAL_ID]][ORIGINAL_NAME])){
			pre($arSrch[$arElement[EXTERNAL_ID]][ORIGINAL_NAME] == $arFile[ORIGINAL_NAME]);
			$prImage = CFile::MakeFileArray($directory.$arSrch[$arElement[EXTERNAL_ID]][ORIGINAL_NAME]);
			$arLoadProductArray = Array (
				'PREVIEW_PICTURE' => $prImage,
				'~PREVIEW_PICTURE' => $prImage,
				'DETAIL_PICTURE' => $prImage,
				'~DETAIL_PICTURE' => $prImage,
			);
			if($arLoadProductArray['DETAIL_PICTURE']['name'] == $arFile['ORIGINAL_NAME']
				&& $arLoadProductArray['DETAIL_PICTURE']['type'] == $arFile['CONTENT_TYPE']
				&& $arLoadProductArray['DETAIL_PICTURE']['size'] == $arFile['FILE_SIZE']
			){
				unset($arLoadProductArray['DETAIL_PICTURE']);
				unset($arLoadProductArray['~DETAIL_PICTURE']);
				unset($arLoadProductArray['PREVIEW_PICTURE']);
				unset($arLoadProductArray['~PREVIEW_PICTURE']);
			}
			$arCount = count($arLoadProductArray);
			if($arCount<>0){
				usleep(10000);
				$el->Update($arElement['ID'], $arLoadProductArray);
			}
		} else {
			echo 'Нет картинки для товара '.$arElement[EXTERNAL_ID].'! <br/>';
		}
		flush();
	//}*/

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
