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
$this->setFrameMode(true);
//pre($arResult);
$templateLibrary = array('popup');
$currencyList = '';
if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BASIS_PRICE' => $strMainID.'_basis_price',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'BASKET_ACTIONS' => $strMainID.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
?>
<?if ($curPage != SITE_DIR."index.php"):?>
	<div class="row" id="transparent" class="article-title-top">
		<div class="col-lg-12">
			<?/*$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(
			"START_FROM" => "0",
			"PATH" => "",
			"SITE_ID" => "-",
			),
			false,
			Array('HIDE_ICONS' => 'N')
		);*/?>
		</div>
	</div>
<?endif?>
<?
$CIBlockElement = new CIBlockElement;
	$arSelect = Array('ID', 'NAME', 'IBLOCK_ID','SECTION_ID','CODE', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE');
	$arFilter = Array(
		'IBLOCK_ID'=>IntVal($arResult['IBLOCK_ID']),
		'ACTIVE'=>'Y',
		'SECTION_ID'=>IntVal($arResult['IBLOCK_SECTION_ID']),
		'!ID'=>IntVal($arResult['ID']),
		);
	$alternativeArticles = array();
	$res = $CIBlockElement->GetList(Array(), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		$alternativeArticles[] = $arFields;
	}
?>
<div class="col-lg-4 artical-wrap-menu" id="artical_menu">
	<ul class="detail-artical_menu">
		<?foreach($alternativeArticles as $article){?>
			<li class="detail-artical_menu">
				<!-- <a class="detail-artical_menu-url" style="background-image: url(<?echo CFile::GetPath($article["PREVIEW_PICTURE"]);?>)" href="<?=$article['DETAIL_PAGE_URL'];?>"> -->
					<?/*echo CFile::ShowImage($article['PREVIEW_PICTURE'], 100, 100);*/?>
					<?/*echo CFile::GetPath($article["PREVIEW_PICTURE"]);*/?>
				</a>
				<a class="detail-artical_menu-name" href="<?=$article['DETAIL_PAGE_URL'];?>"><?=$article['NAME'];?></a>
			</li>
		<?}?>
		<?php if ($article == ''): ?>
			<?//pre($arResult['PROPERTIES']);
				global $aaaFilter;
				$aaaFilter = Array(
					//'IBLOCK_ID'=>IntVal(2),
					//'ACTIVE' => 'Y',
					'sort' => 'rand',
					'ID'=>$arResult['PROPERTIES']['RELATED_GOODS']['VALUE']);
			?>
			<?$ElemLeftProd = $APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				"seller_1colum",
				array(
					"IBLOCK_TYPE_ID" => "catalog",
					"IBLOCK_ID" => "2",
					"BASKET_URL" => "/personal/cart/",
					"COMPONENT_TEMPLATE" => "seller_1colum",
					"IBLOCK_TYPE" => "catalog",
					"SECTION_ID" => "",
					"SECTION_CODE" => "",
					"SECTION_USER_FIELDS" => array(
						0 => "",
						1 => "",
					),
					"ELEMENT_SORT_FIELD" => "",
					"ELEMENT_SORT_ORDER" => "",
					"ELEMENT_SORT_FIELD2" => "",
					"ELEMENT_SORT_ORDER2" => "",
					"USE_FILTER" => "Y",
					"FILTER_NAME" => "aaaFilter",
					"INCLUDE_SUBSECTIONS" => "Y",
					"SHOW_ALL_WO_SECTION" => "Y",
					"HIDE_NOT_AVAILABLE" => "N",
					"PAGE_ELEMENT_COUNT" => "5",
					"LINE_ELEMENT_COUNT" => "1",
					"PROPERTY_CODE" => array(
						0 => "",
						1 => "",
					),
					"OFFERS_FIELD_CODE" => array(
						0 => "",
						1 => "",
					),
					"OFFERS_PROPERTY_CODE" => array(
						0 => "COLOR_REF",
						1 => "SIZES_SHOES",
						2 => "SIZES_CLOTHES",
						3 => "",
					),
					"OFFERS_SORT_FIELD" => "sort",
					"OFFERS_SORT_ORDER" => "desc",
					"OFFERS_SORT_FIELD2" => "id",
					"OFFERS_SORT_ORDER2" => "desc",
					"OFFERS_LIMIT" => "5",
					"TEMPLATE_THEME" => "site",
					"PRODUCT_DISPLAY_MODE" => "Y",
					"ADD_PICT_PROP" => "MORE_PHOTO",
					"LABEL_PROP" => "-",
					"OFFER_ADD_PICT_PROP" => "-",
					"OFFER_TREE_PROPS" => array(
						0 => "COLOR_REF",
						1 => "SIZES_SHOES",
						2 => "SIZES_CLOTHES",
					),
					"PRODUCT_SUBSCRIPTION" => "Y",
					"SHOW_DISCOUNT_PERCENT" => "Y",
					"SHOW_OLD_PRICE" => "Y",
					"SHOW_CLOSE_POPUP" => "Y",
					"MESS_BTN_BUY" => "Купить",
					"MESS_BTN_ADD_TO_BASKET" => "В корзину",
					"MESS_BTN_SUBSCRIBE" => "Подписаться",
					"MESS_BTN_DETAIL" => "Подробнее",
					"MESS_NOT_AVAILABLE" => "Нет в наличии",
					"SECTION_URL" => "",
					"DETAIL_URL" => "",
					"SECTION_ID_VARIABLE" => "SECTION_ID",
					"SEF_MODE" => "N",
					"AJAX_MODE" => "Y",
					"AJAX_OPTION_JUMP" => "Y",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_GROUPS" => "N",
					"SET_TITLE" => "N",
					"SET_BROWSER_TITLE" => "N",
					"BROWSER_TITLE" => "-",
					"SET_META_KEYWORDS" => "N",
					"META_KEYWORDS" => "-",
					"SET_META_DESCRIPTION" => "N",
					"META_DESCRIPTION" => "-",
					"SET_LAST_MODIFIED" => "N",
					"USE_MAIN_ELEMENT_SECTION" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"CACHE_FILTER" => "N",
					"ACTION_VARIABLE" => "action",
					"PRODUCT_ID_VARIABLE" => "id",
					"PRICE_CODE" => array(
						0 => "BASE",
					),
						"USE_PRICE_COUNT" => "N",
						"SHOW_PRICE_COUNT" => "1",
						"PRICE_VAT_INCLUDE" => "Y",
						"CONVERT_CURRENCY" => "N",
						"USE_PRODUCT_QUANTITY" => "N",
						"PRODUCT_QUANTITY_VARIABLE" => "count",
						"ADD_PROPERTIES_TO_BASKET" => "Y",
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"PARTIAL_PRODUCT_PROPERTIES" => "Y",
					"PRODUCT_PROPERTIES" => array(
					),
						"OFFERS_CART_PROPERTIES" => array(
						0 => "COLOR_REF",
						1 => "SIZES_SHOES",
						2 => "SIZES_CLOTHES",
					),
						"ADD_TO_BASKET_ACTION" => "ADD",
						"PAGER_TEMPLATE" => "round",
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "N",
						"PAGER_TITLE" => "Товары",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "N",
						"PAGER_BASE_LINK_ENABLE" => "N",
						"SET_STATUS_404" => "N",
						"SHOW_404" => "N",
						"MESSAGE_404" => "",
						"BACKGROUND_IMAGE" => "-",
						"DISABLE_INIT_JS_IN_COMPONENT" => "N"
					),
					false
				);
				//pre($arParams);
			?>
		<?php endif; ?>
	</ul>
</div><!-- end artical_menu -->

<div class="col-lg-8" id="artical_content" itemprop="articleBody">
	<h1 class="artical_content_title"><?=$APPLICATION->ShowTitle(false);?></h1>
	<?//pre($arResult)?>
	<span style="display: none">дата создания
		<meta itemprop="datePublished" content="<?=$arResult['DATE_CREATE']?>">
	</span>
	<span style="display: none">дата изменения
		<meta itemprop="dateModified" content="<?=$arResult['TIMESTAMP_X']?>">
	</span>
	<?//pre($arResult['PROPERTIES']);
		global $aaaFilter;
		$aaaFilter = Array(
			//'IBLOCK_ID'=>IntVal(2),
			//'ACTIVE' => 'Y',
			'ID'=>$arResult['PROPERTIES']['RELATED_GOODS']['VALUE']);
	?>
	<div class="bx_item_detail <? echo $templateData['TEMPLATE_CLASS']; ?>" id="<? echo $arItemIDs['ID']; ?>">
		<?reset($arResult['MORE_PHOTO']);
		$arFirstPhoto = current($arResult['MORE_PHOTO']);
		?>
		<div id="block_1">
			<?if ('' != $arResult['PREVIEW_TEXT']){?>
				<div>
					<?echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>');?>
				</div>
			<?}?>
		</div><!-- end block_1 -->

		<div class="bx_bigimages_imgcontainer">
			<span class="bx_bigimages_aligner" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
				<img id="<? echo $arItemIDs['PICT']; ?>" src="<? echo $arFirstPhoto['SRC']; ?>" itemprop="image url" alt="<? echo $strAlt; ?>" title="<? echo $strTitle; ?>">
			</span>
		</div><!-- end bx_bigimages_imgcontainer -->

		<div id="block_2">
			<?if ('' != $arResult['DETAIL_TEXT']){?>
				<div class="bx_item_description">
					<div class="bx_item_section_name_gray" style="display: none;"><? echo GetMessage('FULL_DESCRIPTION'); ?></div>
					<?if ('html' == $arResult['DETAIL_TEXT_TYPE']){
						echo $arResult['DETAIL_TEXT'];
					} else {?>
						<p><? echo $arResult['DETAIL_TEXT']; ?></p>
					<?}?>
				</div>
			<?}?>
		</div> <!-- end block_2 -->
	</div><!-- end bx_item_detail -->
</div><!-- artical_content -->
<div class="sidebar col-xs-12 hidden-md hidden-lg hidden-sm hidden-xl">
    <?
    //pre('66666');
    $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "include_referent",
        Array(
            "AREA_FILE_SHOW" => "sect",
            "AREA_FILE_SUFFIX" => "sidebar",
            "AREA_FILE_RECURSIVE" => "Y",
            "EDIT_MODE" => "html",
        ),
        false,
        array(
            "HIDE_ICONS" => "N"
        )
    );?>
</div>
