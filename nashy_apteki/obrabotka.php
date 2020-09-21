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
	$arParams = array("replace_space"=>"-","replace_other"=>"-");
	
	$arFilter = array(
		'IBLOCK_ID' => intval(12),
		'=DEPTH_LEVEL' => intval(3),
		'ACTIVE' => 'Y',
	);
	$CIBlockSection = new CIBlockSection;
	$CIBlockElement = new CIBlockElement;
	$rsSections = $CIBlockSection->GetList(array('NAME' => 'ASC'), $arFilter);
	while ($arSection = $rsSections->Fetch()){
		//pre($arSection);
		echo '<div class="gorod" data-name="'.$arSection['NAME'].'" onclick="checkItem(this)">';
		echo '<a class="name-city"><b>'.$arSection['NAME'].'</b></a>';
		$arFilterZ = array(
			'IBLOCK_ID' => intval(12),
			'=SECTION_ID' => intval($arSection['ID']),
			'ACTIVE' => 'Y',
		);
		$select= array('IBLOCK_ID','ID','NAME','CODE','PROPERTY_GRAFIK_RABOTI','PROPERTY_TELEFON','PROPERTY_ADRES','PROPERTY_STREET','PROPERTY_HOUSE');
		$rsCitySections = $CIBlockElement->GetList(array('NAME' => 'ASC'), $arFilterZ, false, false, $select);
		$addreses = [];
		while ($arCity = $rsCitySections->Fetch()){
			$addreses[] =  $arCity;
		}

		usort($addreses, function($a, $b) {
			return ($a['PROPERTY_STREET_VALUE'] < $b['PROPERTY_STREET_VALUE']) ? -1 : 1;
		});

		foreach ($addreses as $address) {
			echo '<div class="inside-block">';
			echo '<span class="adress"> '.$address['PROPERTY_ADRES_VALUE'].'</span>';
			echo '<div class="apteka-info">';
			echo '<a href="/nashy_apteki/'.$address['CODE'].'/" class="number-apteka"> Аптека №'.$address['NAME'].'</a>';
			echo '<span class="adress-double"> '.$address['PROPERTY_ADRES_VALUE'].'</span>';
			echo '<span class="telephone"> '.$address['PROPERTY_TELEFON_VALUE'].'</span>';
			echo '<span class="grafick"> '.$address['PROPERTY_GRAFIK_RABOTI_VALUE'].'</span>';
			echo '<a href="/nashy_apteki/'.$address['CODE'].'/" target="_blank">Посмотреть на карте</a>';
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
	}
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>
