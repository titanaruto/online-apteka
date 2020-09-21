<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
/* */

if (!empty($_POST['odd']) && empty($_POST['even'])) {
	$aa = str_split($_POST['odd']);
	if(count($aa)!= 24) {
		echo '0';
		die();
	}
	$card = preg_replace("/[^0-9\(\)]/", '', $_POST['odd']);
	$sql = "SELECT `VALUE` FROM `ord_discoutncard_odd` WHERE `CARDNUMBER`='$card';";
	$qwery = new qwery;
	$value = $qwery->frqr($sql);
	$value= $value/100;
	unset($qwery);
	echo $value;
}
if (!empty($_POST['even']) && empty($_POST['odd'])) {
	$aa = str_split($_POST['even']);
	if(count($aa)!= 24) {
		echo '0';
		die();
	}
	$card = preg_replace("/[^0-9\(\)]/", '', $_POST['even']);
	$sql = "SELECT `VALUE` FROM `ord_discoutncard_even` WHERE `CARDNUMBER`='$card';";
	$qwery = new qwery;
	$value = $qwery->frqr($sql);
	$value= $value/100;
	unset($qwery);
	echo $value;
}

//OBL='+id+'&FOB=
if (!empty($_POST['OBL']) && $_POST['OBL'] > 0 && $_POST['FOB']== 1) {
	if(!CModule::IncludeModule("iblock")){
		die('Модуль Инфоблоков не подключен!');
	}
	$CIBlockSection = new CIBlockSection;
	$SECTION_ID = intval($_POST['OBL']);
	$DEPTH_LEVEL = 2+intval($_POST['FOB']);
	$html = '';
	$arFilter = array(
			'IBLOCK_ID' => intval(12),
			'=DEPTH_LEVEL' => intval($DEPTH_LEVEL),
			'ACTIVE' => 'Y',
			'SECTION_ID'=> intval($SECTION_ID)
		);
	$rsSections = $CIBlockSection->GetList(array('NAME' => 'ASC'), $arFilter);
	while ($arSection = $rsSections->Fetch()){
		$html .= '<li name="'.$arSection['CODE'].'" id="'.$arSection['ID'].'">'.$arSection['NAME'].'</li>';
	}
	echo $html;
}
//'CIT='+id+'&OBJ=1';
if (!empty($_POST['CIT']) && $_POST['CIT'] > 0 && $_POST['OBJ']== 1) {
	if(!CModule::IncludeModule("iblock")){
		die('Модуль Инфоблоков не подключен!');
	}
	$CIBlockElement = new CIBlockElement;
	$SECTION_ID = intval($_POST['CIT']);
	$html = '';
	$arFilter = array(
			'IBLOCK_ID' => intval(12),
			'ACTIVE' => 'Y',
			'SECTION_ID'=> intval($SECTION_ID)
		);
	$arSelect = array('ID','IBLOCK_ID', 'NAME', 'CODE', 'EXTERNAL_ID', 'PROPERTY_ADRES', 'PROPERTY_CITY');
	$rsElement = $CIBlockElement->GetList(array('NAME' => 'ASC'), $arFilter,false,false,$arSelect);
	while ($arElement = $rsElement->Fetch()){
		//pre($arElement);
		$html .= '<li data-city="'.$arElement['PROPERTY_CITY_VALUE'].'" id="'.$arElement['EXTERNAL_ID'].'">'.$arElement['PROPERTY_ADRES_VALUE'].'</li>';
	}
	echo $html;
}

















require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
