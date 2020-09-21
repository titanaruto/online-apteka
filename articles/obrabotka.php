<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
/* */
if(!CModule::IncludeModule("iblock")){
	echo 'Модуль Инфоблоков не подключен!';
	die();
}

if($_POST['LIST']=='Y'){
	$arFilter = array(
		'IBLOCK_ID' => intval(5),
		'=DEPTH_LEVEL' => intval(1),
		'ACTIVE' => 'Y',
	);
	$CIBlockSection = new CIBlockSection;
	$CIBlockElement = new CIBlockElement;
	$rsSections = $CIBlockSection->GetList(array('NAME' => 'ASC'), $arFilter);
	while ($arSection = $rsSections->Fetch()){
		//pre($arSection);
		$CFile = new CFile;
		$rsFile = $CFile->GetByID($arSection['PICTURE']);
		$arFile = $rsFile->Fetch();
		echo '<div class="folder" data-code="'.$arSection['CODE'].'">';
			echo '<img src="/upload/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'].'"/><br/>';
			echo '<a href="/articles/'.$arSection['CODE'].'/" class="name-folder"';
			echo ' onclick="return false" data-code="'.$arSection['CODE'].'">';
			echo '<b>'.$arSection['NAME'].'</b></a>';
		echo '</div>';
	}
}

if(!empty($_POST['CODE'])){
	$regexp = '/^[a-zA-Z-_0-9]{2,255}){1,1}$/i';
	$a = 0;
	$a = preg_match($regexp, $file, $match);
	if($a > 0){
		$CODE=$_POST['CODE'];
	}
	$arFilter = array(
		'IBLOCK_ID' => intval(5),
		'=CODE' => $CODE,
		'ACTIVE' => 'Y',
	);
	$CIBlockSection = new CIBlockSection;
	$CIBlockElement = new CIBlockElement;
	$rsSections = $CIBlockSection->GetList(array('NAME' => 'ASC'), $arFilter);
	while ($arSection = $rsSections->Fetch()){
		//pre($arSection);
		$CFile = new CFile;
		$rsFile = $CFile->GetByID($arSection['PICTURE']);
		$arFile = $rsFile->Fetch();
		//pre($arFile);
		echo '<div class="folder" data-code="'.$arSection['CODE'].'">';
		echo '<img src="/upload/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'].'"/><br/>';
		echo '<a href="/articles/'.$arSection['CODE'].'/" class="name-folder"';
		echo ' onclick="return false" data-code="'.$arSection['CODE'].'">';
		echo '<b>'.$arSection['NAME'].'</b></a>';
		//pre($arSection);
		/*$arFilterZ = array(
			'IBLOCK_ID' => intval(12),
			'=SECTION_ID' => intval($arSection['ID']),
			'ACTIVE' => 'Y',
		);
		$select= array('IBLOCK_ID','ID','NAME','CODE','PROPERTY_GRAFIK_RABOTI','PROPERTY_TELEFON','PROPERTY_ADRES','PROPERTY_STREET','PROPERTY_HOUSE');
		$rsCitySections = $CIBlockElement->GetList(array('NAME' => 'ASC'), $arFilterZ, false, false, $select);
		while ($arCity = $rsCitySections->Fetch()){
			/*$PROP = array(
				'ADRES' => $arCity['PROPERTY_STREET_VALUE'].', '.$arCity['PROPERTY_HOUSE_VALUE'],
				//'BRAND' => $goods['BRAND'],
			);
			if(count($PROP)<>0) {
				CIBlockElement::SetPropertyValuesEx(
						$arCity[ID],
						intVal(12), 
						$PROP
				);
			}*/
			/*
			echo '<div class="inside-block">';
				echo '<span class="adress"> '.$arCity['PROPERTY_ADRES_VALUE'].'</span>';
				echo '<div class="apteka-info">';
					echo '<span class="number-apteka"> Аптека №'.$arCity['NAME'].'</span>';
					echo '<span class="adress-double"> '.$arCity['PROPERTY_ADRES_VALUE'].'</span>';
					echo '<span class="telephone"> '.$arCity['PROPERTY_TELEFON_VALUE'].'</span>';
					echo '<span class="grafick"> '.$arCity['PROPERTY_GRAFIK_RABOTI_VALUE'].'</span>';
					echo '<a href="/nashy_apteki/apteka/'.$arCity['CODE'].'/">Посмотреть на карте</a>';
				echo '</div>';
			echo '</div>';
		}*/
		echo '</div>';
	}
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>
