<?
/*
 *Парсер для наложения водяного знака на картинки
 * */

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
set_time_limit(1800);
/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
/* */
//исходный каталог картинок
$dir = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG_copy/';
//возвращаем список имен файлов в каталоге
$files = scandir($dir);
array_splice($files,0,2);
//задаем регулярное выражение картинок товаров
$regexp = '/^[0-9A-Za-z-_]{10,16}\.{1,1}([jpgen]{3,4}){1,1}$/i';
$maxW = 305;//макс ширина картинки
$maxH = 399;//макс высота картинки
$i = 0;
foreach ($files as $f){
    //создаем белый фон
    $osnova = imagecreatefromjpeg($_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/fon_osnova.jpg');
    $a = preg_match($regexp, $f, $match);
    //выбираем только картинки товаров
    if($a < 1){
        //путь к исходному изображению
        $img_result = $dir.$f;
        // создание изображения, в зависимости от его типа
        /*Определены следующие константы для exif_imagetype:
        1 = IMAGETYPE_GIF, 2 = IMAGETYPE_JPG, 3 = IMAGETYPE_PNG,
        4 = IMAGETYPE_SWF, 5 = IMAGETYPE_PSD, 6 = IMAGETYPE_BMP,
        7 = IMAGETYPE_TIFF_II (intel byte order),8 = IMAGETYPE_TIFF_MM (motorola byte order),
        9 = IMAGETYPE_JPC, 10 = IMAGETYPE_JP2, 11 = IMAGETYPE_JPX. */
        switch(exif_imagetype($img_result)){
            case '1':
                $image = imagecreatefromgif($img_result);
            break;            
            case '2':
                $image = imagecreatefromjpeg($img_result);
            break;
            case '3':
                $image = imagecreatefrompng($img_result);
            break;
        }        
        // получаем размерность изображения $size[0] - width, $size[1] - heght
        $size = getimagesize($img_result);
        //проверяем, необходимость изменения размера картинки
        if ($size[0] > $maxW || $size[1] > $maxH){
            if ($size[0] >= $size[1]){
                $k = $size[0] / $maxW; //вычисляем во сколько раз нужно уменьшать оба параметра
                $h = floor($size[1] / $k); //уменьшаем высоту 
                $w = $maxW;//ширина становится в макс значение
            }else{
                $k = $size[1] / $maxH;//вычисляем во сколько раз нужно уменьшать оба параметра
                $w = floor($size[0] / $k);//уменьшаем ширину
                $h = $maxH;//высота становится в макс значение
            }
        }else{
            //если картинка меньше макс значений, берем ее размеры
            $w = $size[0];
            $h = $size[1];
        }
        //echo $h.'<br/>';
        //расчитываем координаты для центрирования изображения товара
        //на картинке основы
        $dest_x_image = floor($maxW/2 - $w/2);
        $dest_y_image = floor($maxH/2 - $h/2);
        //уменьшаем исходную картинку до нужных размеров и совмещаем
        //ее с основой
        imagecopyresampled($osnova, $image, $dest_x_image, $dest_y_image,
        0, 0, $w, $h, imagesx($image), imagesy($image));
        //папка для хранения результирующих данных
        $etc = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPGwatermark/'.$f;
        //создание водяного знака в формате png
        $watermark_path = $_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/eshop_adapt_MC/images/image_wathermark2.png';
        $watermark = imagecreatefrompng($watermark_path);
        // получаем ширину и высоту watermark
        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);
        // помещаем водяной знак в нижней части справа. Делаем отступ в 5px
        // $dest_x = $size[0] - $watermark_width - 5;
        //$dest_y = $size[1] - $watermark_height - 5;
        // помещаем водяной знак в центре исходной картинки.
        //$dest_x = floor($maxW/2 - $watermark_width/2);
        //$dest_y = floor($maxH/2 - $watermark_height/2);
        // помещаем водяной знак внизу исходной картинки.
        $dest_x = 0;
        $dest_y = $maxH - $watermark_height;
        //устанавливаем режим смешивания для изображения
        imagealphablending($osnova, true);
        imagealphablending($watermark, true);
        // создаём новое изображение
        imagecopy($osnova, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
        //сохраняем полученное изображение
        imagejpeg ($osnova,$etc);
        imagedestroy($osnova);
        //$i++;
        //if ($i > 3) exit;
    }
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
