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

	// TOVARI V KOTORIX NET KARTINKI
	set_time_limit(90);
	if(!CModule::IncludeModule("iblock")){
		die('Модуль Инфоблоков не подключен!');
	}
	
	$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
	$dirArray = scandir($directory);
	//unset($dirArray['0']);
	//unset($dirArray['1']);
	//unset($dirArray['2']);
	$regexp = '/^[0-9A-Za-z-_]{10,16}\.{1,1}([jpgen]{3,4}){1,1}$/i';
	$arSrch = array();
	foreach ($dirArray as $file) {
		$a = 0;
		$a = preg_match($regexp, $file, $match);
		if($a > 0){
			$name = explode('.', $file);
			if($name[0] !== ''){
				//pre($name[0]);
				$arSrch[$name[0]][CODE]=$name[0];
				$arSrch[$name[0]][ORIGINAL_NAME]=$file;
			}
		}
	}
	$arFilter = array(
		'IBLOCK_ID' => 2,
		'!PICTURE' => false,
		'!DETAIL_PICTURE' =>false,
	);
	$arSelect = array('IBLOCK_ID', 'ID','ACTIVE','EXTERNAL_ID','PICTURE','DETAIL_PICTURE');
	$CIBlockSection = new CIBlockSection;
	$rsSection = $CIBlockSection->GetList(array('ID' => 'ASC'), $arFilter, false, $arSelect, false);
	$no_photo = CFile::MakeFileArray($directory.'no_photo.png');
	while ($arSection = $rsSection->GetNext()) {
		if(!empty($arSection['PICTURE'])){
			$CFile = new CFile;
			$rsFile = $CFile->GetByID($arSection['PICTURE']);
			$arFile = $rsFile->Fetch();
		}
		//pre($arSrch[$arSection[EXTERNAL_ID]][ORIGINAL_NAME]);
		if(isset($arSrch[$arSection[EXTERNAL_ID]][ORIGINAL_NAME])){
			if($arSrch[$arSection[EXTERNAL_ID]][ORIGINAL_NAME] != $arFile[ORIGINAL_NAME]) {
				usleep(10000);
				$prImage = CFile::MakeFileArray($directory.$arSrch[$arSection[EXTERNAL_ID]][ORIGINAL_NAME]);
				//pre($prImage);
				$arLoadProductArray = Array (
					'PICTURE' => $prImage,
					//'~PICTURE' => $prImage,
					'DETAIL_PICTURE' => $prImage,
					//'~DETAIL_PICTURE' => $prImage,
				);
				$CIBlockSection->Update($arSection['ID'], $arLoadProductArray);
			}
		} else {
			echo 'Нет картинки для категории '.$arSection[EXTERNAL_ID].'! <br/>';
			$no_photo = CFile::MakeFileArray($directory.'no_photo.png');
			//pre($no_photo);
			usleep(10000);
			$arLoadProductArray = Array (
				'PICTURE' => $no_photo,
				//'~PICTURE' => $no_photo,
				'DETAIL_PICTURE' => $no_photo,
				//'~DETAIL_PICTURE' => $no_photo,
			);
			$CIBlockSection->Update($arSection['ID'], $arLoadProductArray);
		}
		flush();
	}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
