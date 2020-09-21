<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");?>
 <?
// парсит содержимое папки /upload/a_obmen/JPG/ 
// и записывает список содержимого в файлик jpg.csv
$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
$dirArray = scandir($directory);
unset($dirArray[0]);
unset($dirArray[1]);
$directory = $_SERVER["DOCUMENT_ROOT"].'/test/';
file_put_contents('jpg.csv','');
$file = $directory.'jpg.csv';
pre($file);
$hendel = fopen($file,'a+');
$str = iconv('utf-8', 'windows-1251', 'FILE_NAME');
fputcsv($hendel, array($str));
unset($str);
foreach ($dirArray as $str){
	$lengs = strlen($str);
	if($lengs < 15) {
		$str = iconv('utf-8', 'windows-1251', $str);
		fputcsv($hendel,array($str));
	}
}
while ( ($res = fgetcsv($hendel, 0, ';')) !== false)
{
	$preResult[]=$res;
}
fclose($hendel);
pre($preResult);
/*  */
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>