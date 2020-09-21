<?
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

if(!CModule::IncludeModule("iblock")){
	die('Модуль Инфоблоков не подключен!');
}
$CIBlockElement = new CIBlockElement;
$list_break = array();
$dir = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
$files = scandir($dir);
for ($i = 0; $i < count($files);$i++) {
	$file = trim($files[$i],' ');
	$code = explode('.',$file);
	array_push($list_break, $code[0]);
	$list_file[$code[0]] = $file;
}
array_splice($list_break,0,2);
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
		'EXTERNAL_ID'
	);

	$rsElement = $CIBlockElement->GetList(array('ID'=>'ASC'),$arFilter,false,false,$arSelect);
	while ($arElement = $rsElement->GetNext()) {

		// создание jpg изображения
		$img_result = 'http://online-apteka.com.ua/upload/a_obmen/JPG/'.$list_file[$arElement['EXTERNAL_ID']];
        echo $img_result;exit;
        $image = imagecreatefromjpeg($img_result);
        // получаем размерность изображения
        $size = getimagesize($img_result);

        //создание водяного знака в формате png
        $watermark = imagecreatefrompng('http://online-apteka.com.ua/bitrix/templates/eshop_adapt_MC/images/watermark_new_50x50.png');
        // получаем ширину и высоту
        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);
        // помещаем водяной знак в нижней части справа. Делаем отступ в 5px
        $dest_x = $size[0] - $watermark_width - 5;
        $dest_y = $size[1] - $watermark_height - 5;        
        imagealphablending($image, true);
        imagealphablending($watermark, true);
        // создаём новое изображение
        imagecopy($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
        //
        $etc = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPGwatermark/'.$list_file[$arElement['EXTERNAL_ID']];
        imagejpeg ($image,$etc);

        exit;
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
