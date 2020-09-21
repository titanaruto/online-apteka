<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);
$this->addExternalCss("/bitrix/css/main/bootstrap.css");

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y'){
	$basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? array($arParams['COMMON_ADD_TO_BASKET_ACTION']) : array());
}else{
	$basketAction = (isset($arParams['DETAIL_ADD_TO_BASKET_ACTION']) ? $arParams['DETAIL_ADD_TO_BASKET_ACTION'] : array());
}

$isSidebar = ($arParams["SIDEBAR_DETAIL_SHOW"] == "Y" && isset($arParams["SIDEBAR_PATH"]) && !empty($arParams["SIDEBAR_PATH"]));
?>
<div class="row">
	
	<div class="col-md-9 col-sm-8">
		<?$ElementID = $APPLICATION->IncludeComponent(
			"bitrix:catalog.element",
			"price",//"price"
			array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
				"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
				"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
				"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
				"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
				"CHECK_SECTION_ID_VARIABLE" => (isset($arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"]) ? $arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"] : ''),
				"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
				"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
				"CACHE_TYPE" => "N",//$arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SET_TITLE" => $arParams["SET_TITLE"],
				"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
				"MESSAGE_404" => $arParams["MESSAGE_404"],
				"SET_STATUS_404" => $arParams["SET_STATUS_404"],
				"SHOW_404" => $arParams["SHOW_404"],
				"FILE_404" => $arParams["FILE_404"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
				"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
				"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
				"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
				"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
				"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
				"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
				"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
				"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],
		
				"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
				"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
				"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
				"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
				"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		
				"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
				"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
				"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"DETAIL_URL" => $arResult['FOLDER'].$arResult['URL_TEMPLATES']["element"],
				'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
				'CURRENCY_ID' => $arParams['CURRENCY_ID'],
				'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
				'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
				'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
				'USE_MAIN_ELEMENT_SECTION' => $arParams['USE_MAIN_ELEMENT_SECTION'],
		
				'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
				'LABEL_PROP' => $arParams['LABEL_PROP'],
				'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
				'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
				'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
				'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
				'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
				'SHOW_MAX_QUANTITY' => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
				'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
				'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
				'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
				'MESS_BTN_COMPARE' => $arParams['MESS_BTN_COMPARE'],
				'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
				'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
				'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
				'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
				'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
				'BLOG_URL' => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
				'BLOG_EMAIL_NOTIFY' => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
				'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
				'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
				'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
				'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
				'BRAND_USE' => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
				'BRAND_PROP_CODE' => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
				'DISPLAY_NAME' => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
				'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
				'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
				'ADD_SECTIONS_CHAIN' => (isset($arParams['ADD_SECTIONS_CHAIN']) ? $arParams['ADD_SECTIONS_CHAIN'] : ''),
				'ADD_ELEMENT_CHAIN' => (isset($arParams['ADD_ELEMENT_CHAIN']) ? $arParams['ADD_ELEMENT_CHAIN'] : ''),
				'DISPLAY_PREVIEW_TEXT_MODE' => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
				'DETAIL_PICTURE_MODE' => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : ''),
				'ADD_TO_BASKET_ACTION' => $basketAction,
				'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
				'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
				'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
				'SHOW_BASIS_PRICE' => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y')
			),
			$component
		);
		
		$GLOBALS["CATALOG_CURRENT_ELEMENT_ID"] = $ElementID;
		unset($basketAction);
		if ($ElementID > 0) {
			if($arParams["USE_STORE"] == "Y" && ModuleManager::isModuleInstalled("catalog")) {
				$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", ".default", 
					array(
						'ELEMENT_ID' => $ElementID,
						'STORE_PATH' => $arParams['STORE_PATH'],
						'CACHE_TYPE' => "A",
						'CACHE_TIME' => "36000",
						'MAIN_TITLE' => $arParams['MAIN_TITLE'],
						'USE_MIN_AMOUNT' =>  $arParams['USE_MIN_AMOUNT'],
						'MIN_AMOUNT' => $arParams['MIN_AMOUNT'],
						'STORES' => $arParams['STORES'],
						'SHOW_EMPTY_STORE' => $arParams['SHOW_EMPTY_STORE'],
						'SHOW_GENERAL_STORE_INFORMATION' => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
						'USER_FIELDS' => $arParams['USER_FIELDS'],
						'FIELDS' => $arParams['FIELDS']
					),
					$component,
					array("HIDE_ICONS" => "Y")
				);
			}
			$arRecomData = array();
			$recomCacheID = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
			$obCache = new CPHPCache();
		if ($obCache->InitCache(36000, serialize($recomCacheID), "/catalog/recommended")){
			$arRecomData = $obCache->GetVars();
		}
			elseif ($obCache->StartDataCache())
			{
				if (Loader::includeModule("catalog"))
				{
					$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
					$arRecomData['OFFER_IBLOCK_ID'] = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
					$arRecomData['IBLOCK_LINK'] = '';
					$arRecomData['ALL_LINK'] = '';
					$rsProps = CIBlockProperty::GetList(
						array('SORT' => 'ASC', 'ID' => 'ASC'),
						array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'PROPERTY_TYPE' => 'E', 'ACTIVE' => 'Y')
					);
					$found = false;
					while ($arProp = $rsProps->Fetch())	{
						if ($found)	{
							break;
						}
						if ($arProp['CODE'] == '')	{
							$arProp['CODE'] = $arProp['ID'];
						}
						$arProp['LINK_IBLOCK_ID'] = intval($arProp['LINK_IBLOCK_ID']);
						if ($arProp['LINK_IBLOCK_ID'] != 0 && $arProp['LINK_IBLOCK_ID'] != $arParams['IBLOCK_ID']){
							continue;
						}
						if ($arProp['LINK_IBLOCK_ID'] > 0){
							if ($arRecomData['IBLOCK_LINK'] == '')	{
								$arRecomData['IBLOCK_LINK'] = $arProp['CODE'];
								$found = true;
							}
						}
						else{
							if ($arRecomData['ALL_LINK'] == ''){
								$arRecomData['ALL_LINK'] = $arProp['CODE'];
							}
						}
					}
					if ($found)	{
						if(defined("BX_COMP_MANAGED_CACHE")){
							global $CACHE_MANAGER;
							$CACHE_MANAGER->StartTagCache("/catalog/recommended");
							$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
							$CACHE_MANAGER->EndTagCache();
						}
					}
				}
				$obCache->EndDataCache($arRecomData);
			}
			if (!empty($arRecomData)){
				if (ModuleManager::isModuleInstalled("sale") && (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N')){
					$APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", "", array(
						"LINE_ELEMENT_COUNT" => 5,
						"TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
						"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
						"BASKET_URL" => $arParams["BASKET_URL"],
						"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action")."_cbdp",
						"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
						"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
						"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
						"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
						"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
						"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
						"SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
						"PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
						"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
						"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
						"SHOW_NAME" => "Y",
						"SHOW_IMAGE" => "Y",
						"MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
						"MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
						"MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
						"MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
						"PAGE_ELEMENT_COUNT" => 5,
						"SHOW_FROM_SECTION" => "N",
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"DEPTH" => "2",
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
						"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
						"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
						"CURRENCY_ID" => $arParams["CURRENCY_ID"],
						"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
						"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
						"SECTION_ELEMENT_ID" => $arResult["VARIABLES"]["SECTION_ID"],
						"SECTION_ELEMENT_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
						"ID" => $ElementID,
						"LABEL_PROP_".$arParams["IBLOCK_ID"] => $arParams['LABEL_PROP'],
						"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => $arParams["LIST_PROPERTY_CODE"],
						"PROPERTY_CODE_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["LIST_OFFERS_PROPERTY_CODE"],
						"CART_PROPERTIES_".$arParams["IBLOCK_ID"] => $arParams["PRODUCT_PROPERTIES"],
						"CART_PROPERTIES_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFERS_CART_PROPERTIES"],
						"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => $arParams['ADD_PICT_PROP'],
						"ADDITIONAL_PICT_PROP_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams['OFFER_ADD_PICT_PROP'],
						"OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
						"RCM_TYPE" => (isset($arParams['BIG_DATA_RCM_TYPE']) ? $arParams['BIG_DATA_RCM_TYPE'] : '')
					),
					$component,
					array("HIDE_ICONS" => "Y")
				);
				}
			}
		}
		?>
	</div><?/*end col-md-9*/?>

	<?/*if ($isSidebar):*/?>
	<div class="sidebar col-md-3 col-sm-4 hidden-xs">
		<?/*php echo 'START SIDE BAR';*/?>
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "include_right_side", Array(
			"AREA_FILE_SHOW" => "sect",	// Показывать включаемую область
			"AREA_FILE_SUFFIX" => "sidebar",	// Суффикс имени файла включаемой области
			"AREA_FILE_RECURSIVE" => "Y",	// Рекурсивное подключение включаемых областей разделов
			"EDIT_MODE" => "html"
		),
		false,
		array(
			"HIDE_ICONS" => "N"
			)
		);?>
		<?/*php ECHO 'END SIDE BAR';*/?>
	</div>
	<?/*endif*/?>	
</div><?/*end row*/?>

<div class="row" style="display: block;">
	<div class="Tabs_for_slider">
		<div class="tabs_containers">
			<div class="tabs_1_conts clearfix">
				<?
				// Вывод контетта слайдера
				$VALUES = array();
				$res = CIBlockElement::GetProperty(
					$arParams["IBLOCK_ID"],
					$ElementID,
					"sort",
					"asc",
					array("CODE" => "RECOMMEND")
				);
				while ($ob = $res->GetNext()){
					$VALUES[] = $ob['VALUE'];
				}
				
				if(true !== empty($VALUES['0'])) {
					$PRICE_TYPE_ID = 1;
					$arFilter = array(
						'IBLOCK_ID' => intval(2),
						'ID' => $VALUES,
						'ACTIVE'=>'Y',
					);
					$arSelect = array('IBLOCK_ID','ID','NAME','DETAIL_PICTURE','DETAIL_PAGE_URL','ADD_URL_TEMPLATE', 'XML_ID', 'SORT');
					$CIBlockElement = new CIBlockElement;
					$rsElements = $CIBlockElement->GetList(array('SORT' => 'ASC'), $arFilter, false, false, $arSelect);
					$arElements=array();
					//<Начало>Выводим все рекомендованные элементы
                    $tabs_for_slider_count = $rsElements->SelectedRowsCount();
				?>
                    <!-- Scrollbox -->
                    <script>
                        $(document).ready(function () {
                            $(function () {
                                $('#demo5').scrollbox({
                                    direction: 'h',
                                    //distance: 134,
                                    autoPlay: <?=$tabs_for_slider_count > 4 ? 'true' : 'false'?>,
                                    //onMouseOverPause: true,
                                    //paused: false,
                                    //infiniteLoop: true    //безконечный цыкл
                                });
                                <?php
                                if ($tabs_for_slider_count < 5) {?>
                                $('#demo5-backward, #demo5-forward').hide()
                                <?php }?>
                                $('#demo5-backward').click(function () {
                                    $('#demo5').trigger('backward');
                                });
                                $('#demo5-forward').click(function () {
                                    $('#demo5').trigger('forward');
                                });
                            });
                        });
                    </script>
				<input id="tabs_1" type="radio" checked="checked" name="radio_types"/>
				<label for="tabs_1">С этим товаром также заказывают</label>
				<div class="wrap_slider">
					<div id="demo5" class="scroll-img">
						<ul>
							<?
							while ($arElem = $rsElements->GetNext()){
                                $pharm_leftovers = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "PHARM_LEFTOVERS"))->GetNext()["VALUE"];
                                $last_availability = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "LAST_AVAILABILITY"))->GetNext()["VALUE"];
                                $rsPrices = CPrice::GetList(array(), array('PRODUCT_ID' => $arElem['ID']))->Fetch();
                                $buy_status = Tools::isVisibleButtonForOrder($pharm_leftovers, $last_availability, intval($rsPrices["PRICE"]));
								//pre();
							//<Начало>Выводим link элемента 
								echo '<li data-log="' . print_r([
                                        $pharm_leftovers,
                                        $arElem["ID"],
                                        $arElem["XML_ID"],
                                        $last_availability,
                                        $buy_status,
                                        $rsPrices["PRICE"]
                                    ], 1) . '">';
									echo '<a target="blank" class="slider_catalog_item_images" href="'.$arElem['DETAIL_PAGE_URL'].'">';
									//<Начало>Выводим link элемента
										//<Начало>Выводим картинку элемента 
										if(!empty($arElem['DETAIL_PICTURE'])){
											$file = CFile::ResizeImageGet(
												$arElem['DETAIL_PICTURE'],
												array('width'=>253, 'height'=>256),
													BX_RESIZE_IMAGE_PROPORTIONAL,
													true
												);
												$img = '<div class="wrapper_img" style="width:255px;height:258px;"><img alt="' . $arElem["NAME"] . '" src="'.$file['src'].'" width="'.$file['width'].'" height="'.$file['height'].'" /></div>';
												echo $img;
											} else {
												$img = '<div alt="Нет фото" class="wrapper_img" style="width:255px;height:258px;"><img src="/upload/a_obmen/JPG/no_photo.png" style="width:253px;height:256px;" /></div>';
												echo $img;
											}
											//<Конец>Выводим картинку элемента
											//<Начало>Выводим Наименование элемента
											echo '<span class="name_element">'.$arElem['NAME'].'</span>'.'<br/>';
											//<Конец>Выводим Наименование элемента
											
											//<Начало>Выводим цену элемента
											$CPrice = new CPrice;
											$res = $CPrice->GetList(
												array(),
												array(
													"PRODUCT_ID" => $arElem['ID'],
													"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
												)
											);
                                            if (!in_array($buy_status, [3, 4])) {
                                                if ($arr = $res->Fetch()) {
                                                    $arElem['PRICE'] = $arr;
                                                    if ($arElem['PRICE']['PRICE'] > 0) {
                                                        echo '<span class="element_price">' . $arElem['PRICE']['PRICE'] . ' грн.' . '</span>';
                                                    }
                                                }
                                            }
											//<Конец>Выводим цену элемента
											$arElements[]=$arElem;
                                            if ($buy_status == 1) {
												echo '<button class="mc-button" onclick="add2basket(' . $arElem["ID"] . '); return false">В корзину</button>';
											} else if (in_array($buy_status, [2, 3])) {?>
                                                <script id="bx24_form_button" data-skip-moving="true">
                                                    (function (w, d, u, b) {
                                                        w['Bitrix24FormObject'] = b;
                                                        w[b] = w[b] || function () {
                                                            arguments[0].ref = u;
                                                            (w[b].forms = w[b].forms || []).push(arguments[0])
                                                        };
                                                        if (w[b]['forms']) return;
                                                        let s = d.createElement('script');
                                                        s.async = 1;
                                                        s.src = u + '?' + (1 * new Date());
                                                        let h = d.getElementsByTagName('script')[0];
                                                        h.parentNode.insertBefore(s, h);
                                                    })(window, document, 'https://b24-wn3zq2.bitrix24.ua/bitrix/js/crm/form_loader.js', 'b24form');

                                                    b24form({
                                                        "id": "8",
                                                        "lang": "ua",
                                                        "sec": "aw079k",
                                                        "type": "button",
                                                        "click": "",
                                                        "presets": {
                                                            "my_param1": "<?=$arElem["NAME"];?>",
                                                            "my_param2": "<?=$arElem["XML_ID"];?>",
                                                            "my_param3": "<?=$arElem['PRICE']['PRICE'] ?>"
                                                        }
                                                    });
                                                </script>
                                                <button onclick="return false;" class="b24-web-form-popup-btn-8 mc-button is-not-store-right"> Под заказ </button>
                                            <?php } else if (in_array($buy_status, [4])) {?>
                                                <button onclick="return false;" class="mc-button is-not-store"> Нет в наличии </button>
                                            <?php }
											//<Конец>Выводим link элемента 
									echo '</a>';
											//<Конец>Выводим link элемента 
								echo '</li>';
									}
									//<Конец>Выводим все рекомендованные элементы
								?>
							</ul>
					</div>	 
					<div id="demo5-btn" class="text-center">
					    <button class="btn_slider" id="demo5-backward"></button>
					    <button class="btn_slider" id="demo5-forward"></button>
					</div>
				</div>
				<? } ?>
			</div><!-- end tabs_1_cont -->
			
			<div class="tabs_2_conts clearfix"><!-- пустая вкладка --></div>
			
		</div><!-- end tabs_containers -->
	</div><!-- end Tabs_for_slider -->
</div><!-- end row-wrap slider -->


<?/*
catalog.bigdata.products

<div class="slider" style="width: 100%; height: auto; display: BLOCK;">
	<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.bigdata.products", 
	"recomend", 
	array(
		"RCM_TYPE" => "any_similar",
		"ID" => "",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "2",
		"HIDE_NOT_AVAILABLE" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"PAGE_ELEMENT_COUNT" => "30",
		"LINE_ELEMENT_COUNT" => "3",
		"TEMPLATE_THEME" => "blue",
		"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SHOW_OLD_PRICE" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "Y",
		"BASKET_URL" => "/personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_PRODUCTS_2" => "Y",
		"CURRENCY_ID" => "UAH",
		"PROPERTY_CODE_2" => array(
			0 => "BRAND",
			1 => "NEWPRODUCT",
			2 => "SALELEADER",
			3 => "SPECIALOFFER",
			4 => "MANUFACTURER",
			5 => "MAIN_MEDICINE",
			6 => "FARM_FORM",
			7 => "ANALOG",
			8 => "",
			9 => "",
		),
		"CART_PROPERTIES_2" => array(
			0 => "RECOMMEND",
			1 => "",
			2 => "",
		),
		"ADDITIONAL_PICT_PROP_2" => "MORE_PHOTO",
		"LABEL_PROP_2" => "-",
		"PROPERTY_CODE_3" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"CART_PROPERTIES_3" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"ADDITIONAL_PICT_PROP_3" => "MORE_PHOTO",
		"OFFER_TREE_PROPS_3" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
		),
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"COMPONENT_TEMPLATE" => "recomend",
		"SHOW_FROM_SECTION" => "N",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => ""
	),
	false
);?>
</div>

*/?>
<script>
	//Если раздел Инструкции пуст - убираем
	var instBox = document.querySelector('.inst-set-all');
	var instBoxInp = document.getElementById('tab_2').nextElementSibling;

	// var q = document.querySelector('.bx-original-item-container');
	// q.parentNode.remove();

	//console.log(instBoxInp);
	if (instBox.innerHTML == '') {
		//alert('пусто');
		instBoxInp.classList.add('hide-tab');
	} else {
		instBoxInp.classList.remove('hide-tab');
		//alert('не пусто');
	}
	//Прячем цену, кнопку и нижний слайдер если товар в наборе
	var priceBox = document.getElementById('bx-set-const-NHsiPA');
	var priceIn = document.querySelector('.item_price');
	var priceBtn = document.querySelector('.item_info_section .item_buttons');
	var sliderBottom = document.querySelector('.Tabs_for_slider');
	var rightPrice = document.querySelectorAll('.right-list .element_price');
	// var rightTitle = document.querySelectorAll('.right-list .name_element');
	// var rightBtn = document.querySelectorAll('.right-list .mc-button');

	if (priceBox) {
		priceIn.style.display = 'none';
		priceBtn.style.display = 'none';
		sliderBottom.style.display = 'none';
		for (var i = 0; i < rightPrice.length; i++) {
			rightPrice[i].style.display = 'none';
		}
	} else {
		priceIn.style.display = 'block';
		priceBtn.style.display = 'block';
		sliderBottom.style.display = 'block';
	}
</script>
