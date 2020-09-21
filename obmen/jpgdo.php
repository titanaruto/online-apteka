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
	//unset($dirArray['0']);
	//unset($dirArray['1']);
	//unset($dirArray['2']);
	$regexp = '/^[a-zA-Z0-9А-Яа-я_-]{3,10}\.{1,1}([jpeng]{3,4}){1,1}$/i';
	$arSrch = array();
	$arElements=array();
	$CIBlockElement = new CIBlockElement;
	foreach ($dirArray as $file) {
		$a = 0;
		$a = preg_match($regexp, $file, $match);
		if($a > 0){
			$name = explode('.', $file);
			if($name[0] !== ''){
				$arSrch[$name[0]][CODE]=$name[0];
				$arSrch[$name[0]][ORIGINAL_NAME]=$file;
				$arFilter = array(
					'IBLOCK_ID' => 2,
					'=EXTERNAL_ID' => $name[0],
				);
				$arSelect = array('IBLOCK_ID','ID','EXTERNAL_ID','DETAIL_PICTURE','PREVIEW_PICTURE');
				$rsElement = $CIBlockElement->GetList(array('ID'=>'ASC'),$arFilter,false,false,$arSelect);
				while ($arElement = $rsElement->GetNext()) {
					if(isset($arSrch[$arElement[EXTERNAL_ID]][ORIGINAL_NAME])){
						$arElements[$arElement[EXTERNAL_ID]] = $arElement;
					}
				}
			}
		}
	}
	pre('Всего картинок в наличии: '.count($arSrch));
	pre('Всего возможно обновить картинок: '.count($arElements));
	//flush();
	//exit();
	foreach($arElements as $arElement) {
		pre($arElement[EXTERNAL_ID]);
		if(isset($arElement['DETAIL_PICTURE'])) {
			$CFile = new CFile;
			$rsFile = $CFile->GetByID($arElement['DETAIL_PICTURE']);
			$arFile = $rsFile->Fetch();
		}
		pre($arSrch[$arElement[EXTERNAL_ID]][ORIGINAL_NAME] != $arFile[ORIGINAL_NAME]);
		if($arSrch[$arElement[EXTERNAL_ID]][ORIGINAL_NAME] != $arFile[ORIGINAL_NAME]){
			pre('Такая картинка уже установлена, нужно детально проверить!');
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
				pre('Такая картинка уже установлена, обновлять не нужно!');
				unset($arLoadProductArray['DETAIL_PICTURE']);
				unset($arLoadProductArray['~DETAIL_PICTURE']);
				unset($arLoadProductArray['PREVIEW_PICTURE']);
				unset($arLoadProductArray['~PREVIEW_PICTURE']);
			}
			$arCount = count($arLoadProductArray);
			if($arCount<>0){
				usleep(300000);
				$CIBlockElement->Update($arElement['ID'], $arLoadProductArray);
			}
		} else {
			pre('Картинка установлена не корректно, нужно обновить!');
			$prImage = CFile::MakeFileArray($directory.$arSrch[$arElement[EXTERNAL_ID]][ORIGINAL_NAME]);
			$arLoadProductArray = Array (
				'PREVIEW_PICTURE' => $prImage,
				'~PREVIEW_PICTURE' => $prImage,
				'DETAIL_PICTURE' => $prImage,
				'~DETAIL_PICTURE' => $prImage,
			);
			usleep(300000);
			//pre($arLoadProductArray);
			//exit();
			$CIBlockElement->Update($arElement['ID'], $arLoadProductArray);
		}
		flush();
	}
	exit();


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
