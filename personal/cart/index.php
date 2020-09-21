<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
header('Location: /',true, 301);
die;
$APPLICATION->SetTitle("Корзина");
?>

<?php
//$APPLICATION->IncludeComponent(
//	"bitrix:sale.basket.basket",
//	".default",
//	array(
//		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
//		"COLUMNS_LIST" => array(
//			0 => "NAME",
//			1 => "DISCOUNT",
//			2 => "PROPS",
//			3 => "DELETE",
//			4 => "DELAY",
//			5 => "PRICE",
//			6 => "QUANTITY",
//			7 => "SUM",
//		),
//		"AJAX_MODE" => "N",
//		"AJAX_OPTION_JUMP" => "N",
//		"AJAX_OPTION_STYLE" => "Y",
//		"AJAX_OPTION_HISTORY" => "N",
//		"PATH_TO_ORDER" => "/personal/order/make/",
//		"HIDE_COUPON" => "Y",
//		"QUANTITY_FLOAT" => "N",
//		"PRICE_VAT_SHOW_VALUE" => "Y",
//		"SET_TITLE" => "Y",
//		"AJAX_OPTION_ADDITIONAL" => "",
//		"OFFERS_PROPS" => array(
//			0 => "SIZES_SHOES",
//			1 => "SIZES_CLOTHES",
//			2 => "COLOR_REF",
//		),
//		"COMPONENT_TEMPLATE" => ".default",
//		"USE_PREPAYMENT" => "N",
//		"AUTO_CALCULATION" => "N",
//		"ACTION_VARIABLE" => "basketAction",
//		"USE_GIFTS" => "N",
//		"CORRECT_RATIO" => "N"
//	),
//	false
//);
?>


<div class="slider" style="width: 100%; height: auto; display: none;">
	<?php
//    $APPLICATION->IncludeComponent(
//	"bitrix:catalog.bigdata.products",
//	"recomend",
//	array(
//		"RCM_TYPE" => "any_similar",
//		"ID" => "",
//		"IBLOCK_TYPE" => "catalog",
//		"IBLOCK_ID" => "2",
//		"HIDE_NOT_AVAILABLE" => "N",
//		"SHOW_DISCOUNT_PERCENT" => "N",
//		"PRODUCT_SUBSCRIPTION" => "N",
//		"SHOW_NAME" => "Y",
//		"SHOW_IMAGE" => "Y",
//		"MESS_BTN_BUY" => "Купить",
//		"MESS_BTN_DETAIL" => "Подробнее",
//		"MESS_BTN_SUBSCRIBE" => "Подписаться",
//		"PAGE_ELEMENT_COUNT" => "30",
//		"LINE_ELEMENT_COUNT" => "3",
//		"TEMPLATE_THEME" => "blue",
//		"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
//		"CACHE_TYPE" => "A",
//		"CACHE_TIME" => "36000000",
//		"CACHE_GROUPS" => "Y",
//		"SHOW_OLD_PRICE" => "N",
//		"PRICE_CODE" => array(
//			0 => "BASE",
//		),
//		"SHOW_PRICE_COUNT" => "1",
//		"PRICE_VAT_INCLUDE" => "Y",
//		"CONVERT_CURRENCY" => "Y",
//		"BASKET_URL" => "/personal/cart/",
//		"ACTION_VARIABLE" => "action",
//		"PRODUCT_ID_VARIABLE" => "id",
//		"ADD_PROPERTIES_TO_BASKET" => "Y",
//		"PRODUCT_PROPS_VARIABLE" => "prop",
//		"PARTIAL_PRODUCT_PROPERTIES" => "N",
//		"USE_PRODUCT_QUANTITY" => "N",
//		"SHOW_PRODUCTS_2" => "Y",
//		"CURRENCY_ID" => "UAH",
//		"PROPERTY_CODE_2" => array(
//			0 => "BRAND",
//			1 => "NEWPRODUCT",
//			2 => "SALELEADER",
//			3 => "SPECIALOFFER",
//			4 => "MANUFACTURER",
//			5 => "MAIN_MEDICINE",
//			6 => "FARM_FORM",
//			7 => "ANALOG",
//			8 => "",
//			9 => "",
//		),
//		"CART_PROPERTIES_2" => array(
//			0 => "RECOMMEND",
//			1 => "",
//			2 => "",
//		),
//		"ADDITIONAL_PICT_PROP_2" => "MORE_PHOTO",
//		"LABEL_PROP_2" => "-",
//		"PROPERTY_CODE_3" => array(
//			0 => "COLOR_REF",
//			1 => "SIZES_SHOES",
//			2 => "SIZES_CLOTHES",
//			3 => "",
//		),
//		"CART_PROPERTIES_3" => array(
//			0 => "COLOR_REF",
//			1 => "SIZES_SHOES",
//			2 => "SIZES_CLOTHES",
//			3 => "",
//		),
//		"ADDITIONAL_PICT_PROP_3" => "MORE_PHOTO",
//		"OFFER_TREE_PROPS_3" => array(
//			0 => "COLOR_REF",
//			1 => "SIZES_SHOES",
//			2 => "SIZES_CLOTHES",
//		),
//		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
//		"COMPONENT_TEMPLATE" => "recomend",
//		"SHOW_FROM_SECTION" => "N",
//		"SECTION_ID" => "",
//		"SECTION_CODE" => "",
//		"SECTION_ELEMENT_ID" => "",
//		"SECTION_ELEMENT_CODE" => "",
//		"DEPTH" => ""
//	),
//	false
//);
	?>
</div>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>