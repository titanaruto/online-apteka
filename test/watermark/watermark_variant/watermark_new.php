<?
/*Модуль для наложения вотермарки на лету, на входе нужно передать
 * имя файла через GET запрос*
 *<img src="watermark.php?image=original.jpg" alt="Какой-то текст" />
 * */
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
// получаем имя изображения через GET
if ($_GET['image']){
    $image_path = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/'.$_GET['image'];
}else {
    exit;
}
//формируем полный путь к файлу картинки
//$image_path = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/'.$path;

// создание jpg изображения
//$img_result = 'http://online-apteka.com.ua/upload/a_obmen/JPG/'.$list_file[$arElement['EXTERNAL_ID']];
$image = imagecreatefromjpeg($image_path);
// получаем размерность изображения
$size = getimagesize($image_path);

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
        //header('content-type: image/jpeg');
        imagejpeg($image);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
