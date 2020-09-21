<?$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/online-apteka.com.ua";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

set_time_limit(600);
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
				$arFilter = array(
					'IBLOCK_ID' => 2,
					'=EXTERNAL_ID' => $name[0],
				);
				$arSelect = array('IBLOCK_ID', 'ID','DETAIL_PICTURE','DETAIL_TEXT');
				$CIBlockElement = new CIBlockElement;
				$rsElement = $CIBlockElement->GetList(
					array('ID' => 'ASC'), 
					$arFilter, false, false, $arSelect
				);
				echo'<table>';
				$triger = 10;
				while ($arElement = $rsElement->GetNext()) {
					$triger = 0;
				}
				if($triger == 10) {
					echo'<tr style="width:100%">';
					echo '<td style="width:150px">'.$file.'</td>';
					echo '<td style="width:150px">'.$name[0].'</td>';
					echo '<td style="width:150px">Нет товара в ПП</td>';
					echo'</tr>';
				}
				echo'</table>';
			}
		}
	}
	pre('Всего картинок в наличии: '.count($arSrch));
	foreach ($dirArray as $file) {
		$name = explode('.', $file);
		if($name[0] !== '') {
			$arFilter = array(
				'IBLOCK_ID' => 2,
				'=EXTERNAL_ID' => $name[0],
			);
			$arSelect = array('IBLOCK_ID', 'ID','DETAIL_PICTURE','DETAIL_TEXT');
			$CIBlockElement = new CIBlockElement;
			$rsElement = $CIBlockElement->GetList(
				array('ID' => 'ASC'), 
				$arFilter, false, false, $arSelect
			);
			$triger = 10;
			while ($arElement = $rsElement->GetNext()) {
				$triger = 0;
			}
			if($triger == 10) {
				echo $file.'----';
				echo $name[0].'----';
				echo 'Нет товара в ПП';
				echo '<br/>';
			}
		}
		flush();
	}






require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
