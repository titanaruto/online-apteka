<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$APPLICATION->SetPageProperty("description", "Вы можете купить Гропринозин в нашей интернет-аптеке: оформить заказ по телефону или в режиме онлайн.");
$APPLICATION->SetPageProperty("keywords", "Гропринозин цена в аптеках, ГРОПРИНОЗИН цена, наличие в аптеках Днепра, Гропринозин таб 500мг N50, Гропринозин - купить, Цена на Гропринозин, Гропринозин: сравнить цены, инструкция по применению,");
$filterView = (COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID) == "eshop_adapt_vertical" ? "HORIZONTAL" : "VERTICAL");
<<<<<<< HEAD
?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog",
	".default",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_SECTION_CHAIN" => "Y",
=======
?>
<?
//проверка если мы перешли на каталог то url должен иметь /catalog/ если это подкоталоги то их не должно существовать
if(CSite::InDir('/catalog/index.php')){
	$SELF_FOLDER = "/catalog/";
} else {
	$SELF_FOLDER = "/";
}

//обрезаю слеши
$code = trim($APPLICATION->GetCurUri(),'/');

$objFindTools = new CIBlockFindTools();

// метод возвращает ID элемента, если найдет его, и 0, если элемент не будет найден.
$elementID = $objFindTools->GetElementID(false, $code);

//проверяем если страница на которую мы зашли это не страница продукта ...
if($elementID === 0){
	$ELEMENT = "#SECTION_CODE#/";
} else {
	$ELEMENT = "#SECTION_CODE_PATH#/";
}

/*
global $Filter;
$Filter = Array('PROPERTY' => array('SPECIALOFFER' => 'Y') );
/*
"FILTER_NAME" => "aaaFilter",

$INCLUDE_SUBSECTIONS = 'N';
if($_GET['all'] == 'Y') {
	$INCLUDE_SUBSECTIONS = 'Y';
}*/
$APPLICATION->IncludeComponent(
	"bitrix:catalog",
	".default",
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "2",
		"TEMPLATE_THEME" => "site",
		"HIDE_NOT_AVAILABLE" => "N",
		"BASKET_URL" => "/personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => $SELF_FOLDER,
>>>>>>> sergey
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "Y",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"ALSO_BUY_ELEMENT_COUNT" => "4",
		"ALSO_BUY_MIN_BUYES" => "1",
		"BASKET_URL" => "/personal/cart/",
		"BIG_DATA_RCM_TYPE" => "bestsell",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
		"COMMON_SHOW_CLOSE_POPUP" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "UAH",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(0=>"ADD",),
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"DETAIL_BLOG_EMAIL_NOTIFY" => "N",
		"DETAIL_BLOG_URL" => "catalog_comments",
		"DETAIL_BLOG_USE" => "Y",
		"DETAIL_BRAND_PROP_CODE" => array(0=>"",1=>"BRAND_REF",2=>"",),
		"DETAIL_BRAND_USE" => "Y",
		"DETAIL_BROWSER_TITLE" => "TITLE",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_DETAIL_PICTURE_MODE" => "IMG",
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"DETAIL_FB_USE" => "N",
		"DETAIL_META_DESCRIPTION" => "META_DESCRIPTION",
		"DETAIL_META_KEYWORDS" => "KEYWORDS",
		"DETAIL_OFFERS_FIELD_CODE" => array(0=>"NAME",1=>"",),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(0=>"ARTNUMBER",1=>"SIZES_SHOES",2=>"SIZES_CLOTHES",3=>"COLOR_REF",4=>"MORE_PHOTO",5=>"",),
		"DETAIL_PROPERTY_CODE" => array(0=>"ARTNUMBER",1=>"MANUFACTURER",2=>"COUNTRY",3=>"TYPE",4=>"MATERIAL",5=>"",),
		"DETAIL_SET_CANONICAL_URL" => "Y",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"DETAIL_SHOW_BASIS_PRICE" => "Y",
		"DETAIL_SHOW_MAX_QUANTITY" => "N",
		"DETAIL_USE_COMMENTS" => "Y",
		"DETAIL_USE_VOTE_RATING" => "Y",
		"DETAIL_VK_USE" => "N",
		"DETAIL_VOTE_DISPLAY_AS_RATING" => "rating",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FIELDS" => array(0=>"",1=>"",),
		"FILE_404" => "",
		"FILTER_FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "\\$Filter",
		"FILTER_OFFERS_FIELD_CODE" => array(0=>"PREVIEW_PICTURE",1=>"DETAIL_PICTURE",2=>"",),
		"FILTER_OFFERS_PROPERTY_CODE" => array(0=>"",1=>"",),
		"FILTER_PRICE_CODE" => array(0=>"BASE",),
		"FILTER_PROPERTY_CODE" => array(0=>"",1=>"",),
		"FILTER_VIEW_MODE" => "VERTICAL",
		"FORUM_ID" => "2",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"IBLOCK_ID" => "2",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "A",
		"LABEL_PROP" => "-",
		"LINE_ELEMENT_COUNT" => "3",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"LINK_IBLOCK_ID" => "",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_PROPERTY_SID" => "",
		"LIST_BROWSER_TITLE" => "UF_BROWSER_TITLE",
		"LIST_META_DESCRIPTION" => "UF_META_DESCRIPTION",
		"LIST_META_KEYWORDS" => "UF_KEYWORDS",
		"LIST_OFFERS_FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_PICTURE",2=>"DETAIL_PICTURE",3=>"",),
		"LIST_OFFERS_LIMIT" => "0",
		"LIST_OFFERS_PROPERTY_CODE" => array(0=>"SIZES_SHOES",1=>"SIZES_CLOTHES",2=>"COLOR_REF",3=>"MORE_PHOTO",4=>"ARTNUMBER",5=>"",),
		"LIST_PROPERTY_CODE" => array(0=>"TITLE",1=>"",),
		"MAIN_TITLE" => "Наличие на складах",
		"MESSAGES_PER_PAGE" => "12",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MIN_AMOUNT" => "10",
		"OFFERS_CART_PROPERTIES" => array(0=>"SIZES_SHOES",1=>"SIZES_CLOTHES",2=>"COLOR_REF",),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_TREE_PROPS" => array(0=>"SIZES_SHOES",1=>"SIZES_CLOTHES",2=>"COLOR_REF",3=>"",),
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "round",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "15",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"PRICE_CODE" => array(0=>"BASE",),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"QUANTITY_FLOAT" => "N",
		"REVIEW_AJAX_POST" => "Y",
		"SECTIONS_HIDE_SECTION_NAME" => "N",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"SECTIONS_VIEW_MODE" => "TILE",
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"SECTION_COUNT_ELEMENTS" => "N",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_TOP_DEPTH" => "1",
		"SEF_FOLDER" => "/catalog/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => array("sections"=>"/catalog/","section"=>"#SECTION_CODE#/","element"=>"#SECTION_CODE#/#ELEMENT_CODE#/","compare"=>"compare/","smart_filter"=>"#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",),
		"SET_LAST_MODIFIED" => "Y",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "Y",
		"SHOW_DEACTIVATED" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"SHOW_LINK_TO_FORUM" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_TOP_ELEMENTS" => "N",
		"SIDEBAR_DETAIL_SHOW" => "Y",
		"SIDEBAR_PATH" => "/catalog/sidebar.php",
		"SIDEBAR_SECTION_SHOW" => "Y",
		"STORES" => array(),
		"STORE_PATH" => "/store/#store_id#",
		"TEMPLATE_THEME" => "site",
		"TOP_ADD_TO_BASKET_ACTION" => "ADD",
		"URL_TEMPLATES_READ" => "",
		"USER_FIELDS" => array(0=>"",1=>"",),
		"USE_ALSO_BUY" => "Y",
		"USE_BIG_DATA" => "N",
		"USE_CAPTCHA" => "Y",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"USE_COMPARE" => "N",
		"USE_ELEMENT_COUNTER" => "N",
		"USE_FILTER" => "Y",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
<<<<<<< HEAD
		"USE_GIFTS_SECTION" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"USE_MIN_AMOUNT" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"USE_REVIEW" => "Y",
		"USE_SALE_BESTSELLERS" => "N",
		"USE_STORE" => "Y",
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
=======
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"FILE_404" => "",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "/",
			"section" => $ELEMENT,
			"element" => "#ELEMENT_CODE#/",
			"compare" => "compare/",
			"smart_filter" => $ELEMENT."/filter/#SMART_FILTER_PATH#/apply/",
		)
	),
	false
);?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
>>>>>>> sergey