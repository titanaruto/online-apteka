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
// КООРДИНАТЫ
//pre($arResult[PROPERTIES][ON_MAP][VALUE]);
//pre($arResult[PROPERTIES]); //[MED_SERVICE]  [MED_KIDS]  [MED_BEAUTY]
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
      crossorigin=""/>


<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
        crossorigin=""></script>
<div class="bx_item_detail <? echo $templateData['TEMPLATE_CLASS']; ?>" id="<? echo $arItemIDs['ID']; ?>">
<?
echo '<a href="/nashy_apteki/">Вернуться к списку аптек</a>';
if ('Y' == $arParams['DISPLAY_NAME'])
{
?>
<div class="bx_item_title"><h1><span><?/*
	echo (
		isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
		? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
		: $arResult["NAME"]
	); */?>
	<?echo 'г.'.$arResult['PROPERTIES']['CITY']['VALUE'].', ';?>
	<?echo ' ';?>
	<?echo 'ул.'.$arResult['PROPERTIES']['ADRES']['VALUE'];?>
</span></h1></div><?


}
reset($arResult['MORE_PHOTO']);
$arFirstPhoto = current($arResult['MORE_PHOTO']);
?>
	<div class="bx_item_container">
        <div style="height: 400px; margin-bottom: 40px;" id="mapid"></div>
        <script>
            let mymap = L.map('mapid').setView([<?=$arResult['PROPERTIES']['ON_MAP']['VALUE']?>], 13);

            let LeafIcon = L.Icon.extend({
                options: {
                    iconSize: [40, 40]
                }
            });

            let greenIcon = new LeafIcon({iconUrl: 'http://med-service.com.ua/images/markerclusterer/m1.png'});

            let marker = L.marker([<?=$arResult['PROPERTIES']['ON_MAP']['VALUE']?>], {icon: greenIcon}).addTo(mymap);

            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                maxZoom: 18,
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1
            }).addTo(mymap);
        </script>

		<div itemscope itemtype="http://schema.org/LocalBusiness" class="preview clearfix col-xs-12">
			<div class="picture-preview col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<!-- ADD PHOTO -->
				<?if($arResult["DETAIL_PICTURE"]):?>
						<div class="more-photo-image"><img border="0" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" /></div>
					<?else :?>
						<div class="more-photo-image"><a href="<?=$PHOTO["SRC"]?>"><img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/preview-picture.jpg" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" /></a></div>
				<?endif?>
			</div>
			<div class="preview-info col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<?
					$MSNAME = 'Аптека Мед-сервис №'.$arResult['NAME'];
					//pre($arResult[PROPERTIES][MED_SERVICE][VALUE]);
					//pre($arResult[PROPERTIES][MED_BEAUTY][VALUE]);
					//pre($arResult[PROPERTIES][MED_KIDS][VALUE]);
					//pre($arResult[PROPERTIES][SOSIAL][VALUE]); //[MED_SERVICE]  [MED_KIDS]  [MED_BEAUTY]
					if(!empty($arResult[PROPERTIES][SOSIAL][VALUE])
						&& empty($arResult[PROPERTIES][MED_BEAUTY][VALUE])
						&& empty($arResult[PROPERTIES][MED_SERVICE][VALUE])
						&& empty($arResult[PROPERTIES][MED_KIDS][VALUE])) {
						$MSNAME = 'Аптека «МСА» - партнер проекта №'.$arResult['NAME'];
					}
					if(!empty($arResult[PROPERTIES][MED_KIDS][VALUE])
						&& empty($arResult[PROPERTIES][MED_BEAUTY][VALUE])
						&& empty($arResult[PROPERTIES][MED_SERVICE][VALUE])
						&& empty($arResult[PROPERTIES][SOSIAL][VALUE])) {
						$MSNAME = 'Аптека Мед-сервис-kids №'.$arResult['NAME'];
					}
					if(!empty($arResult[PROPERTIES][MED_BEAUTY][VALUE])
						&& empty($arResult[PROPERTIES][MED_KIDS][VALUE])
						&& empty($arResult[PROPERTIES][MED_SERVICE][VALUE])
						&& empty($arResult[PROPERTIES][SOSIAL][VALUE])) {
						$MSNAME = 'Аптека Мед-сервис-beauty №'.$arResult['NAME'];
					}
					if(!empty($arResult[PROPERTIES][MED_SERVICE][VALUE])
						&& empty($arResult[PROPERTIES][MED_BEAUTY][VALUE])
						&& empty($arResult[PROPERTIES][MED_KIDS][VALUE])
						&& empty($arResult[PROPERTIES][SOSIAL][VALUE])) {
						$MSNAME = 'Аптека Мед-сервис №'.$arResult['NAME'];
					}
					?><h3 itemprop="name"><?echo $MSNAME;?></h3><?
					//echo '<br/> Адрес: ';
					?>
					<div class="adress clearfix" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span  itemprop="addressLocality" class="adress-apteki"><?echo 'г.'.$arResult['PROPERTIES']['CITY']['VALUE'].', ';?></span>
						<?echo ' ';?>
						<span  itemprop="streetAddress"><?echo 'ул.'.$arResult['PROPERTIES']['ADRES']['VALUE'];?></span>
					</div>
					<span  itemprop="telephone" class="telefon-apteki"><?echo $arResult['PROPERTIES']['TELEFON']['VALUE'];?></span>
					<?
					
					//echo '<br/> График работы: ';
					?><span class="grafik-apteki"><?echo $arResult['PROPERTIES']['GRAFIK_RABOTI']['VALUE'];?></span><?
					if(true === !empty($arResult['PROPERTIES']['SOCIAL_PROJECT']['VALUE'])
						&& $arResult['PROPERTIES']['SOCIAL_PROJECT']['VALUE']=='Y'
					) {
						echo '<span class="special-project"></span>';
					}
					//echo '<br/>';
				?>
			</div>
			<meta itemprop="latitude" content="LATITUDE" /> 
			<meta itemprop="longitude" content="LONGITUDE" /> 
		</div>
	
		<div class="bx_lt">
		</div>
		<div class="bx_rt">
<?
$useBrands = ('Y' == $arParams['BRAND_USE']);
$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
if ($useBrands || $useVoteRating)
{
?>
	<div class="bx_optionblock">
<?
	if ($useVoteRating){
		?><?$APPLICATION->IncludeComponent(
			"bitrix:iblock.vote",
			"stars",
			array(
				"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
				"IBLOCK_ID" => $arParams['IBLOCK_ID'],
				"ELEMENT_ID" => $arResult['ID'],
				"ELEMENT_CODE" => "",
				"MAX_VOTE" => "5",
				"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
				"SET_STATUS_404" => "N",
				"DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
				"CACHE_TYPE" => $arParams['CACHE_TYPE'],
				"CACHE_TIME" => $arParams['CACHE_TIME']
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?><?
	}

?>
	</div>
<?
}
unset($useVoteRating);
?>
<div class="item_price">
<?
if ('' != $arResult['PREVIEW_TEXT'])
{
	if (
		'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
		|| ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
	)
	{
		?>
		<div class="item_info_section">
		<?
			echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>');
		?>
		</div>
		<?
	}
}
?>

</div>
<?
/*

	echo $arResult['NAME'];
	echo '<br/> Адрес: ';
	echo $arResult['PROPERTIES']['CITY']['VALUE'];
	echo '  ';
	echo $arResult['PROPERTIES']['ADRES']['VALUE'];
	echo '<br/> Телефон: ';
	echo $arResult['PROPERTIES']['TELEFON']['VALUE'];
	echo '<br/> График работы: ';
	echo $arResult['PROPERTIES']['GRAFIK_RABOTI']['VALUE'];
	echo '<br/>';

*/?>
<div class="clb"></div>
</div>
<div class="bx_rb">
<div class="item_info_section">
<?
if ('' != $arResult['DETAIL_TEXT'])
{
?>
	<div class="bx_item_description">
	<div class="bx_item_section_name_gray" style="border-bottom: 1px solid #f2f2f2;"><? echo GetMessage('FULL_DESCRIPTION'); ?></div>
<?
	if ('html' == $arResult['DETAIL_TEXT_TYPE']){
		echo $arResult['DETAIL_TEXT'];
	}else{
		?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
	}
?>
	</div>
<?
}
?>
</div>
		</div>
		<div class="bx_lb">
<div class="tac ovh">
</div>
<div class="tab-section-container">
<?
if ('Y' == $arParams['USE_COMMENTS'])
{
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.comments",
	"",
	array(
		"ELEMENT_ID" => $arResult['ID'],
		"ELEMENT_CODE" => "",
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"SHOW_DEACTIVATED" => $arParams['SHOW_DEACTIVATED'],
		"URL_TO_COMMENT" => "",
		"WIDTH" => "",
		"COMMENTS_COUNT" => "5",
		"BLOG_USE" => $arParams['BLOG_USE'],
		"FB_USE" => $arParams['FB_USE'],
		"FB_APP_ID" => $arParams['FB_APP_ID'],
		"VK_USE" => $arParams['VK_USE'],
		"VK_API_ID" => $arParams['VK_API_ID'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
		"BLOG_TITLE" => "",
		"BLOG_URL" => $arParams['BLOG_URL'],
		"PATH_TO_SMILE" => "",
		"EMAIL_NOTIFY" => $arParams['BLOG_EMAIL_NOTIFY'],
		"AJAX_POST" => "Y",
		"SHOW_SPAM" => "Y",
		"SHOW_RATING" => "N",
		"FB_TITLE" => "",
		"FB_USER_ADMIN_ID" => "",
		"FB_COLORSCHEME" => "light",
		"FB_ORDER_BY" => "reverse_time",
		"VK_TITLE" => "",
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME']
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>
<?
}
?>
</div>
</div>
<div style="clear: both;"></div>
</div>
<div class="clb"></div>
</div>



