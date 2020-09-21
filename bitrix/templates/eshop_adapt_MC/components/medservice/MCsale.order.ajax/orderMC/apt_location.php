<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	$CIBlockSection = new CIBlockSection;
	$CIBlockElement = new CIBlockElement;
	?>

	<div class="location-row clearfix">
		<h4><?=GetMessage("SOA_TEMPL_GET_PRODUCT_CITY")?></h4>
		<div class="info-search">
			<span id="question" class="question-txt">При оформлении заказа нужно ввести название города, и в нижнем выпадающем окне, при клике, вы увидите адреса аптек выбранного Вами города!</span>
		</div>
		<div id="order-wrap" class="as-inputs order-as-inputs" data-vis="none"><i class="fa fa-search"></i><input type="search" id="ord_oblast" name="search-all" class="location-search-all" placeholder="Выбрать город..." title="" autocomplete="off"></div>
		<div class="location-row clearfix location-address">
<!--			<div style="width: 277px; float: left; padding: 10px 10px 0;" ></div>-->
				<span class="as-inputs" id="ord_city" style="height: 44px;">
                    <i class="fa fa-search"></i><input type="search" id="ord_oblast" name="search-all" class="location-search-all" placeholder="Начните вводить адрес аптеки..." title="" autocomplete="off">
                </span>
                <ul  class="row-list" id="ord_oblast_apt" style="display:none"></ul>
			</div>
	</div>
	<div class=" " id="ord_oblast_name_list" data-vis="none">
		<ul  class="row-list" id="ord_oblast_name" style="display:none">
            <?php
		$arFilter = array(
			'IBLOCK_ID' => intval(12),
			'ACTIVE' => 'Y',
		);
		$arSelectApt = array(
			'IBLOCK_ID',
			'ID',
			'IBLOCK_SECTION_ID',
			'NAME',
			'EXTERNAL_ID',
			'CODE',
			'PROPERTY_CITY',
			'PROPERTY_ADRES',
		);

		$rsElementApt = $CIBlockElement->GetList(array('NAME' => 'DESC'), $arFilter, false, false, $arSelectApt);
		while ($arElementApt = $rsElementApt->Fetch()){
			//$resSection = CIBlockSection::GetNavChain($arElement['IBLOCK_ID'], $arElement['IBLOCK_SECTION_ID']);
			//while($arSection = $resSection->GetNext()){
				//pre($arElementApt['IBLOCK_SECTION_ID']);
				//if ($arSection['DEPTH_LEVEL'] == 2) {
					$name_shut = $arElementApt['PROPERTY_ADRES_VALUE'];
					echo '<li name="'.$arElementApt['NAME'].'" id="'.$arElementApt['EXTERNAL_ID'].'" style="display:none;" class="up-hood">'.$arElementApt['PROPERTY_CITY_VALUE'].'</li>';
					echo '<li name="'.$arElementApt['NAME'].'" id="'.$arElementApt['EXTERNAL_ID'].'" class="hood">'.$arElementApt['PROPERTY_ADRES_VALUE'].'</li>';
				//}
			//}
		}
		?>
		</ul>
	</div>