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
?>
<?
//pre($arResult);
if (!empty($arResult['ITEMS'])){
	$templateLibrary = array('popup');
	$currencyList = '';
	if (!empty($arResult['CURRENCIES'])){
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

	$arSkuTemplate = array();
	if ($arParams['DISPLAY_TOP_PAGER']){
		echo $arResult['NAV_STRING'];
	}

	$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
	$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
	$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
	
	/*?><div class="bx-section-desc <? echo $templateData['TEMPLATE_CLASS']; ?>">
		<p class="bx-section-desc-post"><?=$arResult["DESCRIPTION"]?></p>
	</div><?*/
	?>
	<div class="bx_catalog_list_home col<? echo $arParams['LINE_ELEMENT_COUNT']; ?> <? echo $templateData['TEMPLATE_CLASS']; ?>">
	<?
	foreach ($arResult['ITEMS'] as $key => $arItem){
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
	$strMainID = $this->GetEditAreaId($arItem['ID']);
	$arItemIDs = array(
		'ID' => $strMainID,
		'PICT' => $strMainID.'_pict',
		'BASKET_ACTIONS' => $strMainID.'_basket_actions',
		'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
		'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
		'PROP_DIV' => $strMainID.'_sku_tree',
		'PROP' => $strMainID.'_prop_',
		'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	);

	$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

	$productTitle = (
		isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
		? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
		: $arItem['NAME']
	);
	$imgTitle = (
		isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
		? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
		: $arItem['NAME']
	);

	$minPrice = false;
	if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE']))
		$minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
	?><div class="<? 
		echo ($arItem['SECOND_PICT'] ? 'bx_catalog_item double' : 'bx_catalog_item'); ?>"><div 
		class="bx_catalog_item_container" id="<? echo $strMainID; ?>">
		<a id="<? echo $arItemIDs['PICT']; ?>" 
			href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" 
			class="bx_catalog_item_images" 
			style="background-image: url('<? 
				echo $arItem['PREVIEW_PICTURE']['SRC']; ?>')" 
			title="<? echo $imgTitle; ?>"><?
		if ($arItem['SECOND_PICT']){
			?><a id="<? echo $arItemIDs['SECOND_PICT']; ?>" 
			href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" 
			class="bx_catalog_item_images_double" 
			style="background-image: url('<? echo (
				!empty($arItem['PREVIEW_PICTURE_SECOND'])
				? $arItem['PREVIEW_PICTURE_SECOND']['SRC']
				: $arItem['PREVIEW_PICTURE']['SRC']
			); ?>');" title="<? echo $imgTitle; ?>"><?

			if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']){?>
				<div id="<? echo $arItemIDs['SECOND_DSC_PERC']; ?>" 
				class="bx_stick_disc right bottom" 
				style="display:<? 
					echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<? 
				echo $minPrice['DISCOUNT_DIFF_PERCENT']; ?>%</div>
			<?}
			if ($arItem['LABEL']){?>
				<div id="<? echo $arItemIDs['SECOND_STICKER_ID']; ?>" 
				class="bx_stick average left top" title="<? 
					echo $arItem['LABEL_VALUE']; ?>"><? 
					echo $arItem['LABEL_VALUE']; 
					echo '</div>';
			}
			echo '</a>';
		}
		?><div class="bx_catalog_item_title"><a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" 
		title="<? echo $productTitle; ?>"><? echo $productTitle; ?></a></div>
		<?
		$showSubscribeBtn = false;
		$compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCS_TPL_MESS_BTN_COMPARE'));

		?></div></div><?
	}
	?><div style="clear: both;"></div><?

	if ($arParams["DISPLAY_BOTTOM_PAGER"]){
		echo $arResult["NAV_STRING"]; 
	}
}
