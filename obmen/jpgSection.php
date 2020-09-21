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

// Установить картинки на секции без картинок
	set_time_limit(90);
	if(!CModule::IncludeModule("iblock")){
		die('Модуль Инфоблоков не подключен!');
	}
	
	$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
	$dirArray = scandir($directory);
	
	unset($dirArray['0']);
	unset($dirArray['1']);
	unset($dirArray['2']);
	$regexp = '/^[0-9A-Za-z-_]{10,16}\.{1,1}([jpegnng]{3,4}){1,1}$/i';
	
	foreach ($dirArray as $file) {
		$a = 0;
		$a = preg_match($regexp, $file, $match);
		if($a > 0){
			$name = explode('.', $file);
			//pre($name);
			if($name[0] !== '') {
				$arFilter = array(
					'IBLOCK_ID' => 2,
					'=EXTERNAL_ID' => $name[0],
				);
				$arSelect = array('IBLOCK_ID', 'ID','ACTIVE','EXTERNAL_ID','PICTURE','DETAIL_PICTURE');
				$CIBlockSection = new CIBlockSection;
				$rsSection = $CIBlockSection->GetList(
					array('ID' => 'ASC'), 
					$arFilter, false, $arSelect, false
				);
				while ($arSection = $rsSection->GetNext()) {
					if(empty($arSection['PICTURE'])) {
						$prImage = CFile::MakeFileArray($directory.$file);
						$arLoadProductArray = Array (
							'PICTURE' => $prImage,
							'~PICTURE' => $prImage,
						);
						$CIBlockSection->Update($arSection['ID'], $arLoadProductArray);
					}
					if(empty($arSection['DETAIL_PICTURE'])) {
						$prImage = CFile::MakeFileArray($directory.$file);
						$arLoadProductArray = Array (
							'DETAIL_PICTURE' => $prImage,
							'~DETAIL_PICTURE' => $prImage,
						);
						$CIBlockSection->Update($arSection['ID'], $arLoadProductArray);
					}
					if(empty($arSection['DETAIL_PICTURE']) && !empty($arSection['~DETAIL_PICTURE'])){
						$prImage = CFile::MakeFileArray($directory.$file);
						$arLoadProductArray = Array (
							'DETAIL_PICTURE' => $prImage,
							'~DETAIL_PICTURE' => $prImage,
						);
						$CIBlockSection->Update($arSection['ID'], $arLoadProductArray);
					}
					if(empty($arSection['PICTURE']) && !empty($arSection['~PICTURE'])){
						$prImage = CFile::MakeFileArray($directory.$file);
						$arLoadProductArray = Array (
							'PICTURE' => $prImage,
							'~PICTURE' => $prImage,
						);
						$CIBlockSection->Update($arSection['ID'], $arLoadProductArray);
					}
				}
			}
		}
		//usleep(10000);
		flush();
	}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
