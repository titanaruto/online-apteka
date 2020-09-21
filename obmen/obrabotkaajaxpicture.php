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
if(!CModule::IncludeModule("iblock")){
		die('Модуль Инфоблоков не подключен!');
}
$CFile = new CFile;
$CIBlockElement = new CIBlockElement;
if($_POST['ALL'] == 'Y'){

	$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
	$dirArray = scandir($directory);
	$regexp = '/^[a-zA-Z0-9А-Яа-я_-]{3,10}\.{1,1}([jpeng]{3,4}){1,1}$/i';
	$arSrch = array();
	$arElements=array();
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
					//pre($arElement);
					if(isset($arSrch[$arElement[EXTERNAL_ID]][ORIGINAL_NAME])){
						$arElements[$arElement[EXTERNAL_ID]] = $arElement;
						echo '<DIV id="'.$file.'" data-displ="block">';
						echo '<SPAN class="name" onclick="">'.$file.'</SPAN>';
						echo '<SPAN class="mc-button repeat" onclick="checkElement(this)">
								<i class="fa fa-repeat" aria-hidden="true"></i>
							 Проверить</SPAN>';
						//echo '<SPAN onclick="">   </SPAN>';
						//echo '<SPAN onclick="instalElement(this);">Установить</SPAN>';
						//echo '<IMG src="/upload/a_obmen/JPG/'.$file.'" width="150"/>';
						echo '<DIV class="clearfix"></DIV>';
						echo '</DIV>';
						/*
						if(isset($arElement['DETAIL_PICTURE'])) {
							//$CFile = new CFile;
							$rsFile = $CFile->GetByID($arElement['DETAIL_PICTURE']);
							$arFile = $rsFile->Fetch();
							$arElements[$arElement[EXTERNAL_ID]][FILE]['DETAIL_PICTURE'] = $arFile;
						}
						usleep(1000);
						if(isset($arElement['PREVIEW_PICTURE'])) {
							//$CFile = new CFile;
							$rsFile = $CFile->GetByID($arElement['PREVIEW_PICTURE']);
							$arFile = $rsFile->Fetch();
							$arElements[$arElement[EXTERNAL_ID]][FILE]['PREVIEW_PICTURE'] = $arFile;
						}
						usleep(1000);
						* */
					}
				}
			}
		}
	}
	//$curentTime = time() - $startTime;
	//pre($curentTime);
	//usleep(1000000);
	//pre($arElements);
	//pre('Всего картинок в наличии: '.count($arSrch));
	//pre('Всего возможно обновить картинок: '.count($arElements));
}
if(!empty($_POST['ID']) && $_POST['CH']=='Y') {
	//pre($_POST['ID']);
	//exit();
	$name = explode('.', $_POST['ID']);
	if($name[0] !== ''){
		$arFilter = array(
			'IBLOCK_ID' => 2,
			'=EXTERNAL_ID' => $name[0],
		);
		$arSelect = array('IBLOCK_ID','ID','EXTERNAL_ID','DETAIL_PICTURE','PREVIEW_PICTURE','EXTERNAL_ID');
		$rsElement = $CIBlockElement->GetList(array('ID'=>'ASC'),$arFilter,false,false,$arSelect);
		while ($arElement = $rsElement->GetNext()) {
			echo '<div class="clearfix" style="padding:20px 10px; padding: 30px 20px;">';
			echo '<div style="width:33%;  display: inline-block; vertical-align: top;  text-align: center; padding: 30px 20px;">';
			echo 'В папке обмена:<br/>';
			echo '<IMG src="/upload/a_obmen/JPG/'.$_POST['ID'].'" width="150"/>';
			if(isset($arElement['DETAIL_PICTURE'])) {
				$rsFile = $CFile->GetByID($arElement['DETAIL_PICTURE']);
				$arFile = $rsFile->Fetch();
				$arElement[FILE]['DETAIL_PICTURE'] = $arFile;
			}
			echo '</div>';
			echo '<div style="width:33%;  display: inline-block; vertical-align: top;  text-align: center; padding: 30px 20px;">';
			echo 'Детальная картинка:<br/>';
			echo '<IMG src="/upload/'.$arFile[SUBDIR].'/'.$arFile[FILE_NAME].'" width="150" />';
			echo '<br/>';
			echo $arFile[ORIGINAL_NAME].'<br/>';
			echo '<button class="btn-success" data-name="'.$arElement[ID].'" data-file="'.$_POST['ID'].'" data-type="DETAIL_PICTURE"
				onclick="replaceIMG(this);">Заменить</button><br/>';
			echo '<button class="btn-danger" data-name="'.$arElement[ID].'" data-file="'.$_POST['ID'].'" data-type="DETAIL_PICTURE"
				onclick="deleteIMG(this);">Удалить</button><br/>';
			/*if($_POST['ID'] != $arFile[ORIGINAL_NAME]){
				echo 'Не совпадает.<br/>';
			}*/
			echo '</div>';
			if(isset($arElement['PREVIEW_PICTURE'])) {
				//$CFile = new CFile;
				$rsFile = $CFile->GetByID($arElement['PREVIEW_PICTURE']);
				$arFile = $rsFile->Fetch();
				$arElement[FILE]['PREVIEW_PICTURE'] = $arFile;
			}
			echo '<div style="width:33%;  display: inline-block; vertical-align: top;  text-align: center; padding: 30px 20px;">';
			echo 'Предварительная:<br/>';
			echo '<IMG src="/upload/'.$arFile[SUBDIR].'/'.$arFile[FILE_NAME].'" width="150" />';
			echo '<br/>';
			echo $arFile[ORIGINAL_NAME].'<br/>';
			echo '<button class="btn-success" data-name="'.$arElement[ID].'" data-file="'.$_POST['ID'].'" data-type="PREVIEW_PICTURE"
			onclick="replaceIMG(this);">Заменить?</button><br/>';
			echo '<button class="btn-danger" data-name="'.$arElement[ID].'" data-file="'.$_POST['ID'].'" data-type="PREVIEW_PICTURE"
			onclick="deleteIMG(this);">Удалить</button><br/>';
			/*if($_POST['ID'] != $arFile[ORIGINAL_NAME]){
				echo 'Не совпадает.<br/>';
			}*/
			echo '</div>';
			/*echo '<div style="width:100%; float:left;">';
			//pre($arElement);
			echo '</div>';*/
			echo '</div>';
		}
	}
}
if(!empty($_POST['ID']) && !empty($_POST['F']) && !empty($_POST['T'])) {
	$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
	$prImage = CFile::MakeFileArray($directory.$_POST['F']);
	//pre($prImage);
	if($_POST['T']=="DETAIL_PICTURE"){
		$arLoadProductArray = Array (
			'DETAIL_PICTURE' => $prImage,
			'~DETAIL_PICTURE' => $prImage,
		);
	} else {
		$arLoadProductArray = Array (
			'PREVIEW_PICTURE' => $prImage,
			'~PREVIEW_PICTURE' => $prImage,
		);
	}
	$CIBlockElement->Update(intval($_POST['ID']), $arLoadProductArray);
	//pre($_POST['ID']);
	$res = CIBlockElement::GetByID(intval($_POST['ID']));
	$arRes = $res->GetNext();
	//pre($arRes);
	if($_POST['T']="DETAIL_PICTURE"){
		echo 'Детальная картинка:<br/>';
		if(isset($arRes['DETAIL_PICTURE'])) {
			$fileID = $arRes['DETAIL_PICTURE'];
		}
	} else {
		echo 'Предварительная:<br/>';
		if(isset($arRes['PREVIEW_PICTURE'])) {
			$fileID = $arRes['PREVIEW_PICTURE'];
		}
	}
	if($fileID>0){
		$rsFile = $CFile->GetByID($fileID);
		$arFile = $rsFile->Fetch();
	}
	echo '<IMG src="/upload/'.$arFile[SUBDIR].'/'.$arFile[FILE_NAME].'" width="150" />';
	echo '<br/>';
	echo $arFile[ORIGINAL_NAME].'<br/>';
	echo '<button class="btn-success" name="'.$_POST['ID'].'" data-file="'.$_POST['F'].'" data-type="'.$_POST['T'].'"
				onclick="replaceIMG(this);">Заменить</button><br/>';
	echo '<button class="btn-danger" name="'.$_POST['ID'].'" data-file="'.$_POST['F'].'" data-type="'.$_POST['T'].'"
			onclick="deleteIMG(this);">Удалить</button><br/>';
}
if(!empty($_POST['ID']) && !empty($_POST['T']) && $_POST['DEL'] == 'Y') {
	$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
	if($_POST['T']=="DETAIL_PICTURE"){
		$arLoadProductArray = Array (
			'DETAIL_PICTURE' => array("del"=>"Y"),
			'~DETAIL_PICTURE' => array("del"=>"Y"),
		);
	} else {
		$arLoadProductArray = Array (
			'PREVIEW_PICTURE' => array("del"=>"Y"),
			'~PREVIEW_PICTURE' => array("del"=>"Y"),
		);
	}
	$CIBlockElement->Update(intval($_POST['ID']), $arLoadProductArray);
	$res = CIBlockElement::GetByID(intval($_POST['ID']));
	$arRes = $res->GetNext();
	if($_POST['T']="DETAIL_PICTURE"){
		echo 'Детальная картинка:<br/>';
		if(isset($arRes['DETAIL_PICTURE'])) {
			$fileID = $arRes['DETAIL_PICTURE'];
		}
	} else {
		echo 'Предварительная:<br/>';
		if(isset($arRes['PREVIEW_PICTURE'])) {
			$fileID = $arRes['PREVIEW_PICTURE'];
		}
	}
	if($fileID>0){
		$rsFile = $CFile->GetByID($fileID);
		$arFile = $rsFile->Fetch();
	}
	echo '<IMG src="/upload/'.$arFile[SUBDIR].'/'.$arFile[FILE_NAME].'" width="150" />';
	echo '<br/>';
	echo $arFile[ORIGINAL_NAME].'<br/>';
	echo '<button class="btn-success" name="'.$_POST['ID'].'" data-file="'.$_POST['F'].'" data-type="'.$_POST['T'].'"
				onclick="replaceIMG(this);">Заменить</button><br/>';
	echo '<button class="btn-danger" name="'.$_POST['ID'].'" data-file="'.$_POST['F'].'" data-type="'.$_POST['T'].'"
			onclick="deleteIMG(this);">Удалить</button><br/>';
}
if(!empty($_POST['F']) && $_POST['R'] == 'Y') {
	$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
	$name = explode('.', $_POST['F']);
	if($name[0] !== ''){
		$arFilter = array(
			'IBLOCK_ID' => 2,
			'=EXTERNAL_ID' => $name[0],
		);
		$arSelect = array('IBLOCK_ID','ID','EXTERNAL_ID','DETAIL_PICTURE','PREVIEW_PICTURE','EXTERNAL_ID');
		$rsElement = $CIBlockElement->GetList(array('ID'=>'ASC'),$arFilter,false,false,$arSelect);
		while ($arElement = $rsElement->GetNext()) {
			$arLoadProductArray = Array (
				'DETAIL_PICTURE' => array("del"=>"Y"),
				'~DETAIL_PICTURE' => array("del"=>"Y"),
				'PREVIEW_PICTURE' => array("del"=>"Y"),
				'~PREVIEW_PICTURE' => array("del"=>"Y"),
			);
			$CIBlockElement->Update(intval($arElement['ID']), $arLoadProductArray);
			//echo 'В товаре '.$name[0].' картинки удалены...<br/>';
			$arLoadProductArray = Array ();
			usleep(30000);
			//pre($arElement['ID']);
			$prImage = CFile::MakeFileArray($directory.$_POST['F']);
			//pre($prImage);
			$arLoadProductArray = Array (
				'DETAIL_PICTURE' => $prImage,
				//'~DETAIL_PICTURE' => $prImage,
				'PREVIEW_PICTURE' => $prImage,
				//'~PREVIEW_PICTURE' => $prImage,
			);
			$CIBlockElement->Update(intval($arElement['ID']), $arLoadProductArray);
			echo 'В товаре '.$name[0].' картинки заменены...<br/>';
		}
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
