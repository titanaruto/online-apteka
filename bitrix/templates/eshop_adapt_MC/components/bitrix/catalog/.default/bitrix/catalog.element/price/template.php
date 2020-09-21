<?php  if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
//pre($arResult);
if (!empty($arResult["PROPERTIES"]["PRODUCT_META_DESC"]["VALUE"]))
    $APPLICATION->SetPageProperty("description", $arResult["PROPERTIES"]["PRODUCT_META_DESC"]["VALUE"]);
$lo = Tools::getLeftovers($arResult["XML_ID"], '');
if ($arResult['ID'] == 82924) : ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-88170340-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-88170340-1');
    </script>
<?php  endif;
$this->setFrameMode(true);
//pre($arResult['DETAIL_PICTURE']['SRC']);
$_SESSION['BRAND_ELEMENT_VALUE_GOODS'] = '';
$_SESSION['BRAND_ELEMENT_VALUE_GOODS'] = $arResult['PROPERTIES']['BRAND']['VALUE'];

$_SESSION['BRAND_ELEMENT_SECTION_ID'] = '';
$_SESSION['BRAND_ELEMENT_SECTION_ID'] = $arResult['IBLOCK_SECTION_ID'];
$templateLibrary = array('popup');
$currencyList = '';
if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
    'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css',
    'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
    'ID' => $strMainID,
    'PICT' => $strMainID . '_pict',
    'DISCOUNT_PICT_ID' => $strMainID . '_dsc_pict',
    'STICKER_ID' => $strMainID . '_sticker',
    'BIG_SLIDER_ID' => $strMainID . '_big_slider',
    'BIG_IMG_CONT_ID' => $strMainID . '_bigimg_cont',
    'SLIDER_CONT_ID' => $strMainID . '_slider_cont',
    'SLIDER_LIST' => $strMainID . '_slider_list',
    'SLIDER_LEFT' => $strMainID . '_slider_left',
    'SLIDER_RIGHT' => $strMainID . '_slider_right',
    'OLD_PRICE' => $strMainID . '_old_price',
    'PRICE' => $strMainID . '_price',
    'DISCOUNT_PRICE' => $strMainID . '_price_discount',
    'SLIDER_CONT_OF_ID' => $strMainID . '_slider_cont_',
    'SLIDER_LIST_OF_ID' => $strMainID . '_slider_list_',
    'SLIDER_LEFT_OF_ID' => $strMainID . '_slider_left_',
    'SLIDER_RIGHT_OF_ID' => $strMainID . '_slider_right_',
    'QUANTITY' => $strMainID . '_quantity',
    'QUANTITY_DOWN' => $strMainID . '_quant_down',
    'QUANTITY_UP' => $strMainID . '_quant_up',
    'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
    'QUANTITY_LIMIT' => $strMainID . '_quant_limit',
    'BASIS_PRICE' => $strMainID . '_basis_price',
    'BUY_LINK' => $strMainID . '_buy_link',
    'ADD_BASKET_LINK' => $strMainID . '_add_basket_link',
    'BASKET_ACTIONS' => $strMainID . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $strMainID . '_not_avail',
    'COMPARE_LINK' => $strMainID . '_compare_link',
    'PROP' => $strMainID . '_prop_',
    'PROP_DIV' => $strMainID . '_skudiv',
    'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
    'OFFER_GROUP' => $strMainID . '_set_group_',
    'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
);
$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$CPrice = new CPrice;
$res = $CPrice->GetList(
    array(),
    array('PRODUCT_ID' => $arResult['ID'], 'CATALOG_GROUP_ID' => intval(2))
);
if ($arr = $res->Fetch()) {
    $arResult['CATALOG_PRICE_2'] = $arr['PRICE'];
}
//pre($arResult);
//pre($arResult['PROPERTIES']['SALELEADER']['VALUE']);

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
<?php  if ($curPage != SITE_DIR . "index.php"): ?>
    <?php  /*<h1><?
	echo (
		isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
		? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
		: $arResult["NAME"]
	); ?>
</h1>

*/
//pre('111');
    ?>

    <div class="row" id="transparent">
        <div class="col-lg-12">
            <?php  /*$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(
				"START_FROM" => "0",
				"PATH" => "",
				"SITE_ID" => "-",
				),
				false,
				Array('HIDE_ICONS' => 'N')
			);*/ ?>
        </div>
    </div>
<?php  endif ?>
<div class="bx_item_detail <?php  echo $templateData['TEMPLATE_CLASS']; ?>" id="<?php  echo $arItemIDs['ID']; ?>">
    <?
    if ('Y' == $arParams['DISPLAY_NAME']) {
        ?>
        <?php  /*
<div class="bx_item_title"><h1><span><?
	echo (
		isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
		? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
		: $arResult["NAME"]
	); ?>
</span></h1></div>
*/
        ?>
        <?

    }
    //pre($arResult);

    reset($arResult['MORE_PHOTO']);
    $arFirstPhoto = current($arResult['MORE_PHOTO']);
    ?>
<!--    <div class="bx_item_container" itemscope="" itemtype="http://schema.org/Product">-->
    <?php 
    $product_price = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE']["VALUE"] : $arResult['MIN_PRICE']["VALUE"]);
    $buy_status = Tools::isVisibleButtonForOrder($arResult["PHARM_LEFTOVERS"], $arResult["LAST_AVAILABILITY"], intval($product_price));?>
    <div data-log="<?php  print_r([$arResult["PHARM_LEFTOVERS"], $arResult["ID"], $arResult["XML_ID"], $arResult["LAST_AVAILABILITY"], $buy_status, $product_price]) ?>" class="bx_item_container">
        <div class="bx_lt">
            <div class="bx_item_slider" id="<?php  echo $arItemIDs['BIG_SLIDER_ID']; ?>">
                <div class="bx_bigimages" id="<?php  echo $arItemIDs['BIG_IMG_CONT_ID']; ?>">
                    <?
                    //********************************************//
                    //********************************************//
                    $CPrice = new CPrice;
                    $res = $CPrice->GetList(
                        array(),
                        array('PRODUCT_ID' => $arResult['ID'], 'CATALOG_GROUP_ID' => intval(2))
                    );
                    if ($arr = $res->Fetch()) {
                        $arResult['CATALOG_PRICE_2'] = $arr['PRICE'];
                        $arResult[PRICES][BASE][VALUE];
                        $persent_A = (($arResult['CATALOG_PRICE_2'] - $arResult[PRICES][BASE][VALUE]) * 100) / $arResult['CATALOG_PRICE_2'];
                        $a = round($persent_A);
                    }
                    if ($a > 0) {
                        ?>
                        <div class="bx_stick_disc right bottom" id="stick_disc_stick_id"
                             style="z-index: 20;"><?php  echo -$a; ?>%
                        </div>
                        <?
                    }
                    if ($arResult['PROPERTIES']['SALELEADER']['VALUE'] == 'Y') {
                        ?>
                        <div class="bx_stick average left top"
                             id="<?php  echo $arItemIDs['STICKER_ID'] ?>"
                             title="<?php  echo $arResult['LABEL_VALUE']; ?>"
                             style="z-index: 20;"> ТОП продаж
                        </div>
                        <?
                    }
                    ?>
                    <div class="bx_bigimages_imgcontainer">
                        <span class="bx_bigimages_aligner">
                            <img
                                    itemprop="image"
                                    id="<?=file_exists($_SERVER["DOCUMENT_ROOT"] . $arFirstPhoto['SRC']) ? $arItemIDs['PICT'] : '' ?>"
                                    src="<?php  echo !file_exists($_SERVER["DOCUMENT_ROOT"] . $arFirstPhoto['SRC']) ? '/upload/a_obmen/JPG/no_photo.png' : $arFirstPhoto['SRC']; ?>"
                                    alt="<?php  echo $strAlt; ?>" title="<?php  echo $strTitle; ?>"></span>
                        <?
                        if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']) {
                            if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS'])) {
                                if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']) {

                                    ?>
                                    <div class="bx_stick_disc right bottom"
                                         id="<?php  echo $arItemIDs['DISCOUNT_PICT_ID'] ?>"><?php  echo -$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>
                                        %
                                    </div>
                                    <?
                                }
                            } else {
                                ?>
                                <div class="bx_stick_disc right bottom" id="<?php  echo $arItemIDs['DISCOUNT_PICT_ID'] ?>"
                                     style="display: none;"></div>
                                <?
                            }
                        }

                        if ($arResult['LABEL']) {
                            ?>
                            <div class="bx_stick average left top" id="<?php  echo $arItemIDs['STICKER_ID'] ?>"
                                 title="<?php  echo $arResult['LABEL_VALUE']; ?>"><?php  echo $arResult['LABEL_VALUE']; ?></div>
                            <?
                        }
                        ?>
                    </div>
                </div>
                <?
                if ($arResult['SHOW_SLIDER']) {
                    if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS'])) {
                        if (5 < $arResult['MORE_PHOTO_COUNT']) {
                            $strClass = 'bx_slider_conteiner full';
                            $strOneWidth = (100 / $arResult['MORE_PHOTO_COUNT']) . '%';
                            $strWidth = (20 * $arResult['MORE_PHOTO_COUNT']) . '%';
                            $strSlideStyle = '';
                        } else {
                            $strClass = 'bx_slider_conteiner';
                            $strOneWidth = '20%';
                            $strWidth = '100%';
                            $strSlideStyle = 'display: none;';
                        }
                        ?>

                        <div class="<?php  echo $strClass; ?>" id="<?php  echo $arItemIDs['SLIDER_CONT_ID']; ?>">
                            <div class="bx_slider_scroller_container">
                                <div <?php  echo count($arResult['MORE_PHOTO'] <= 1) ? "style='display: none'" : "" ?>
                                        class="bx_slide">
                                    <ul style="width: <?php  echo $strWidth; ?>;"
                                        id="<?php  echo $arItemIDs['SLIDER_LIST']; ?>">
                                        <?
                                        foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto) {
                                            ?>
                                            <li data-value="<?php  echo $arOnePhoto['ID']; ?>"
                                                style="width: <?php  echo $strOneWidth; ?>; padding-top: <?php  echo $strOneWidth; ?>;">
                                                <span class="cnt"><span class="cnt_item"
                                                                        style="background-image:url('<?php  echo $arOnePhoto['SRC']; ?>');"></span></span>
                                            </li>
                                            <?
                                        }
                                        unset($arOnePhoto);
                                        ?>
                                    </ul>
                                </div>
                                <div class="bx_slide_left" id="<?php  echo $arItemIDs['SLIDER_LEFT']; ?>"
                                     style="<?php  echo $strSlideStyle; ?>"></div>
                                <div class="bx_slide_right" id="<?php  echo $arItemIDs['SLIDER_RIGHT']; ?>"
                                     style="<?php  echo $strSlideStyle; ?>"></div>
                            </div>
                        </div>
                        <?
                    } else {
                        foreach ($arResult['OFFERS'] as $key => $arOneOffer) {
                            if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
                                continue;
                            $strVisible = ($key == $arResult['OFFERS_SELECTED'] ? '' : 'none');
                            if (5 < $arOneOffer['MORE_PHOTO_COUNT']) {
                                $strClass = 'bx_slider_conteiner full';
                                $strOneWidth = (100 / $arOneOffer['MORE_PHOTO_COUNT']) . '%';
                                $strWidth = (20 * $arOneOffer['MORE_PHOTO_COUNT']) . '%';
                                $strSlideStyle = '';
                            } else {
                                $strClass = 'bx_slider_conteiner';
                                $strOneWidth = '20%';
                                $strWidth = '100%';
                                $strSlideStyle = 'display: none;';
                            }
                            ?>
                            <div class="<?php  echo $strClass; ?>"
                                 id="<?php  echo $arItemIDs['SLIDER_CONT_OF_ID'] . $arOneOffer['ID']; ?>"
                                 style="display: <?php  echo $strVisible; ?>;">
                                <div class="bx_slider_scroller_container">
                                    <div class="bx_slide">
                                        <ul style="width: <?php  echo $strWidth; ?>;"
                                            id="<?php  echo $arItemIDs['SLIDER_LIST_OF_ID'] . $arOneOffer['ID']; ?>">
                                            <?
                                            foreach ($arOneOffer['MORE_PHOTO'] as &$arOnePhoto) {
                                                ?>
                                                <li data-value="<?php  echo $arOneOffer['ID'] . '_' . $arOnePhoto['ID']; ?>"
                                                    style="width: <?php  echo $strOneWidth; ?>; padding-top: <?php  echo $strOneWidth; ?>">
                                                    <span class="cnt"><span class="cnt_item"
                                                                            style="background-image:url('<?php  echo $arOnePhoto['SRC']; ?>');"></span></span>
                                                </li>
                                                <?
                                            }
                                            unset($arOnePhoto);
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="bx_slide_left"
                                         id="<?php  echo $arItemIDs['SLIDER_LEFT_OF_ID'] . $arOneOffer['ID'] ?>"
                                         style="<?php  echo $strSlideStyle; ?>"
                                         data-value="<?php  echo $arOneOffer['ID']; ?>"></div>
                                    <div class="bx_slide_right"
                                         id="<?php  echo $arItemIDs['SLIDER_RIGHT_OF_ID'] . $arOneOffer['ID'] ?>"
                                         style="<?php  echo $strSlideStyle; ?>"
                                         data-value="<?php  echo $arOneOffer['ID']; ?>"></div>
                                </div>
                            </div>
                            <?
                        }
                    }
                }
                ?>
            </div>
        </div>
        <div class="bx_rt">
            <span itemprop="name"><h1><?= $APPLICATION->ShowTitle(false); ?></h1></span>
            <?php  if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') { ?>
                <style>
                    #basicTitle {
                        display: none;
                    }
                </style>
            <?php  } else { ?>
                <style>
                    #basicTitle {
                        display: none;
                    }
                </style><?
            }
            ?>
            <span class="description_Element" itemprop="brand">
				<span><?= $arResult['PROPERTIES']['MANUFACTURER']['VALUE'] ?></span> (
				<span><?= $arResult['PROPERTIES']['COUNTRY']['VALUE'] ?></span> )
				
			</span>
            <div class="item_info_section">
                <span><?php  /*=$arResult['PREVIEW_TEXT'] */ ?></span>

            </div>
            <div class="item_info_section" itemprop="brand">
                <?
                if (!empty($arResult['DISPLAY_PROPERTIES'])) {
                    ?>
                    <dl>
                        <?
                        foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp) {
                            if ($arOneProp['NAME'] === "Артикул") {
                                $articule = is_array($arOneProp['DISPLAY_VALUE'])
                                    ? implode(' / ', $arOneProp['DISPLAY_VALUE'])
                                    : $arOneProp['DISPLAY_VALUE'];
                            }
                            ?>

                            <dt><?php  echo $arOneProp['NAME']; ?></dt>
                            <dd><?
                            echo(
                            is_array($arOneProp['DISPLAY_VALUE'])
                                ? implode(' / ', $arOneProp['DISPLAY_VALUE'])
                                : $arOneProp['DISPLAY_VALUE']
                            ); ?></dd><?
                        }
                        unset($arOneProp);
                        ?>
                    </dl>
                    <?
                }
                if ($arResult['SHOW_OFFERS_PROPS']) {
                    ?>
                    <dl id="<?php  echo $arItemIDs['DISPLAY_PROP_DIV'] ?>" style="display: none;"></dl>
                    <?
                }
                ?>
            </div>
            <?
            $useBrands = ('Y' == $arParams['BRAND_USE']);
            $useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
            if ($useBrands || $useVoteRating) {
                ?>
                <div class="bx_optionblock">
                    <?
                    if ($useVoteRating) {
                        ?><?/*$APPLICATION->IncludeComponent(
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
		);*/
                        ?>
                        <!--a href="#comments">Добавить отзыв</a-->
                        <?
                    }
                    if ($useBrands) {
                        ?><?
                        $APPLICATION->IncludeComponent("bitrix:catalog.brandblock", ".default", array(
                            "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                            "ELEMENT_ID" => $arResult['ID'],
                            "ELEMENT_CODE" => "",
                            "PROP_CODE" => $arParams['BRAND_PROP_CODE'],
                            "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                            "CACHE_TIME" => $arParams['CACHE_TIME'],
                            "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                            "WIDTH" => "",
                            "HEIGHT" => ""
                        ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        ); ?><?
                    }
                    ?>
                </div>

                <?
            }
            unset($useVoteRating, $useBrands);
            ?>

<!--            <div class="item_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">-->
            <?php  if (!in_array($buy_status, [3, 4])) { ?>
                <div class="item_price">
                    <?
                    if (!empty($arResult['CATALOG_PRICE_2']) && $arResult['CATALOG_PRICE_2'] > 0) {
                        $arItem['CATALOG_PRICE_2'] = $arResult['CATALOG_PRICE_2'];
                    }
                    $minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
                    //##################################
                    //###############################
                    //pre($minPrice);
                    //$boolDiscountShow = (0 < $minPrice['DISCOUNT_DIFF']);

                    if ($minPrice['VALUE'] > 0) {
                        /*if(!isset($arItem['CATALOG_PRICE_2']) || $arItem['CATALOG_PRICE_2'] == 0){
                            ?><div class="sails-price-detail">
                                <span class="percent doverie-detail"><?=round($minPrice['VALUE']*0.95, 2).' грн';?></span>
                                <span class="percent vip-detail"><?=round($minPrice['VALUE']*0.93, 2).' грн';?></span>
                                <span class="percent zabota-detail"><?=round($minPrice['VALUE']*0.9, 2).' грн';?></span>
                            </div><?
                        }*/
                        if ($arItem['CATALOG_PRICE_2'] > $minPrice['VALUE'] && $minPrice['VALUE'] != 0) {
                            $a = $arItem['CATALOG_PRICE_2'];
                            $b = $minPrice['VALUE'];
                            $c = round(($a - $b), 2);
                            $minPrice['PRINT_DISCOUNT_DIFF'] = $c;
                            echo '<div class="item_old_price" id="' . $arItemIDs['OLD_PRICE'] . '">';
                            echo $arItem['CATALOG_PRICE_2'];
                            echo '</div>';
                        } ?>
                        <meta itemprop="price" content="<?= $minPrice['DISCOUNT_VALUE'] ?>">
                        <?php 
                        echo '<div class="item_current_price" id="' . $arItemIDs['PRICE'] . '">';
                        echo count($lo) > 0 ? $minPrice['DISCOUNT_VALUE'] . '<span itemprop="priceCurrency"> грн</span>' : '';
                        echo '</div>';

                        //pre ($minPrice);

                        if ($arItem['CATALOG_PRICE_2'] > $minPrice['VALUE'] && $minPrice['VALUE'] != 0) {
                            echo '<div class="item_economy_price" id="' . $arItemIDs['DISCOUNT_PRICE'] . '">';
                            echo GetMessage(
                                'CT_BCE_CATALOG_ECONOMY_INFO',
                                array('#ECONOMY#' => $c . '<span> грн</span>'));
                            echo '</div>';
                        }
                    } else {
                        ?>
                        <div class="item_old_price" id="<?php  echo $arItemIDs['OLD_PRICE']; ?>"
                             style="display:none;"><?
                            echo($boolDiscountShow ? $minPrice['PRINT_VALUE'] : '');
                            ?></div>
                        <meta itemprop="price" content="<?= $minPrice['DISCOUNT_VALUE'] ?>">
                        <div class="item_current_price" id="<?php  echo $arItemIDs['PRICE']; ?>"
                             style="display:none;"><?
                            echo $minPrice['DISCOUNT_VALUE'];
                            echo '<span itemprop="priceCurrency"> грн</span>';
                            ?></div>

                        <div style="display:none;" class="item_current_price" itemprop="priceCurrency"
                             id="<?php  echo $arItemIDs['PRICE']; ?>"
                             style="display:none;"><?
                            echo $minPrice['DISCOUNT_VALUE'];
                            echo '<span itemprop="priceCurrency"> грн</span>';
                            ?></div>

                        <div class="item_economy_price" id="<?php  echo $arItemIDs['DISCOUNT_PRICE']; ?>"
                             style="display:none;"><?
                        echo($boolDiscountShow ? GetMessage('CT_BCE_CATALOG_ECONOMY_INFO',
                            array('#ECONOMY#' => $minPrice['PRINT_DISCOUNT_DIFF'])) : '');
                        ?></div><?
                    }
                    ?>
                    <div class="item_economy_price"
                         id="<?php echo $arItemIDs['DISCOUNT_PRICE']; ?>"
                         style="display: <?php echo($boolDiscountShow ? '' : 'none'); ?>"><?
                        echo($boolDiscountShow ? GetMessage('CT_BCE_CATALOG_ECONOMY_INFO',
                            array('#ECONOMY#' => $minPrice['PRINT_DISCOUNT_DIFF'])) : ''); ?></div>
                </div>
                <?
            }
            $mPriceTrigerEgoMat = $minPrice['VALUE'];
            $crmPrice = $minPrice;
            unset($minPrice);

            if ('' != $arResult['PREVIEW_TEXT']) {
                if (
                    'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
                    || ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
                ) {
                    ?>
                    <div class="item_info_section"></div><?
                }
            }

            ?>
            <div class="item_info_section_wrapper">
            <div class="item_info_section">

                <?
                //pre($arResult);
                if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
                    $canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
                } else {
                    $canBuy = $arResult['CAN_BUY'];
                }

                $buyBtnMessage = ($arParams['MESS_BTN_BUY'] != '' ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
                $addToBasketBtnMessage = ($arParams['MESS_BTN_ADD_TO_BASKET'] != '' ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCE_CATALOG_ADD'));
                $notAvailableMessage = ($arParams['MESS_NOT_AVAILABLE'] != '' ? 'Под заказ'/*$arParams['MESS_NOT_AVAILABLE']*/ : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
                $showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
                $showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
                $showSubscribeBtn = false;
                $compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCE_CATALOG_COMPARE'));

                if ($arParams['USE_PRODUCT_QUANTITY'] == 'Y') {
                    if ($arParams['SHOW_BASIS_PRICE'] == 'Y') {
                        $basisPriceInfo = array(
                            '#PRICE#' => $arResult['MIN_BASIS_PRICE']['PRINT_DISCOUNT_VALUE'],
                            '#MEASURE#' => (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : '')
                        );
                        ?>
                        <p id="<?php echo $arItemIDs['BASIS_PRICE']; ?>"
                           class="item_section_name_gray"><?php echo GetMessage('CT_BCE_CATALOG_MESS_BASIS_PRICE', $basisPriceInfo); ?></p>
                        <?
                    } ?>

                    <span class="item_section_name_gray"><?php echo GetMessage('CATALOG_QUANTITY'); ?></span>

                    <div class="item_buttons vam">

		<span class="item_buttons_counter_block">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb"
               id="<?php echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a>
			<input id="<?php echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input"
                   value="<?php echo(isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
                       ? 1
                       : $arResult['CATALOG_MEASURE_RATIO']
                   ); ?>">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb"
               id="<?php echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
			<span class="bx_cnt_desc"
                  id="<?php echo $arItemIDs['QUANTITY_MEASURE']; ?>"><?php echo(isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''); ?></span>
		</span>
                        <span class="item_buttons_counter_block" id="<?php echo $arItemIDs['BASKET_ACTIONS']; ?>"
                              style="display: <?php echo($canBuy ? '' : 'none'); ?>;">
<?
if ($showAddBtn) {
    ?>
    <a href="javascript:void(0);" class="mc-button"
       id="<?php echo $arItemIDs['ADD_BASKET_LINK']; ?>"><span></span><?php echo $addToBasketBtnMessage; ?></a>
    <?
}
?>
		</span>
                        <span id="<?php echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_notavailable"
                              style="display: <?php echo(!$canBuy ? '' : 'none'); ?>;"><?php echo $notAvailableMessage; ?></span>
                        <?
                        if ($arParams['DISPLAY_COMPARE'] || $showSubscribeBtn) {
                            ?>
                            <span class="item_buttons_counter_block">
<?
if ($arParams['DISPLAY_COMPARE']) {
    ?>
    <a href="javascript:void(0);" class="bx_big bx_bt_button_type_2 bx_cart"
       id="<?php echo $arItemIDs['COMPARE_LINK']; ?>"><?php echo $compareBtnMessage; ?></a>
    <?
}
if ($showSubscribeBtn) {

}
?>
		</span>
                            <?
                        }
                        ?>

                    </div>

                    <?
                    if ('Y' == $arParams['SHOW_MAX_QUANTITY']) {
                        if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
                            ?>
                            <p id="<?php echo $arItemIDs['QUANTITY_LIMIT']; ?>"
                               style="display: none;"><?php echo GetMessage('OSTATOK'); ?>: <span></span></p>
                            <?
                        } else {
                            if ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']) {
                                ?>
                                <p id="<?php echo $arItemIDs['QUANTITY_LIMIT']; ?>"><?php echo GetMessage('OSTATOK'); ?>:
                                    <span><?php echo $arResult['CATALOG_QUANTITY']; ?></span></p>
                                <?
                            }
                        }
                    }
                } else {
                    ?>
                    <div class="item_buttons vam">
                    <span class="item_buttons_counter_block" id="<?php echo $arItemIDs['BASKET_ACTIONS']; ?>"
                          style="display: <?php echo($canBuy ? '' : 'none'); ?>;">
	
	<?
    if ($showBuyBtn) { ?>
        <a href="javascript:void(0);" class="mc-button"
           id="<?php echo $arItemIDs['BUY_LINK']; ?>"><span></span><?php echo $buyBtnMessage; ?></a>
        <?
    }
    if ($showAddBtn) {
        // !!!!!!!!!!!!!!!!!!!
        if ($buy_status == 1) {
            ?>
            <a href="javascript:void(0);" class="mc-button"
               id="<?php echo $arItemIDs['ADD_BASKET_LINK']; ?>"><span></span><?php echo $addToBasketBtnMessage; ?></a>
            <?
            } else if (in_array($buy_status, [2, 3])) {?>
            <script id="bx24_form_button" data-skip-moving="true">
        (function (w, d, u, b) {
            w['Bitrix24FormObject'] = b;
            w[b] = w[b] || function () {
                arguments[0].ref = u;
                (w[b].forms = w[b].forms || []).push(arguments[0])
            };
            if (w[b]['forms']) return;
            var s = d.createElement('script');
            s.async = 1;
            s.src = u + '?' + (1 * new Date());
            var h = d.getElementsByTagName('script')[0];
            h.parentNode.insertBefore(s, h);
        })(window, document, 'https://b24-wn3zq2.bitrix24.ua/bitrix/js/crm/form_loader.js', 'b24form');

        b24form({
            "id": "8",
            "lang": "ua",
            "sec": "aw079k",
            "type": "button",
            "click": "",
            "presets": {
                "my_param1": "<?= $APPLICATION->ShowTitle(false); ?>",
                "my_param2": "<?=$articule;?>",
                "my_param3": "<?= $minPrice['DISCOUNT_VALUE'] ?>"
            }
        });
</script>
            <button class="b24-web-form-popup-btn-8 mc-button zakaz  <?= $mPriceTrigerEgoMat == 0 ? 'zakaz' : '' ?>"> Под заказ </button>
            <?php  } else if (in_array($buy_status, [4])) {?>
                <button class="mc-button is-not-store"> Нет в наличии </button>
            <?php  }?>

            <!--            <a href="javascript:void(0);" class="mc-button --><?//=$mPriceTrigerEgoMat == 0 ? 'zakaz' : ''?><!--" id="--><?// echo $arItemIDs['ADD_BASKET_LINK']; ?><!--"><span></span> Под заказ </a>-->
            <?

    }
    ?>
		</span>
                    <span id="<?php echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_notavailable"
                          style="display: <?php echo(!$canBuy ? '' : 'none'); ?>;"><?php echo $notAvailableMessage; ?></span>
                    <?
                    if ($arParams['DISPLAY_COMPARE'] || $showSubscribeBtn) {
                        ?>
                        <span class="item_buttons_counter_block">
                        <?
                        if ($arParams['DISPLAY_COMPARE']) {
                            ?>
                            <a href="javascript:void(0);" class="bx_big bx_bt_button_type_2 bx_cart"
                               id="<?php echo $arItemIDs['COMPARE_LINK']; ?>"><?php echo $compareBtnMessage; ?></a>
                            <?
                        }
                        ?></span><?
                    }
                    ?></div><?
                }
                unset($showAddBtn, $showBuyBtn);
                ?>

            </div>
            <?php  if (in_array($buy_status, [1])) { ?>
                <button id="one-click-btn" class="mc-button">Купить в 1 клик</button>
            <?php  } ?>
            <div id="one_click_form" class="popup-window popup-window-with-titlebar" style="display: none">
                <div class="popup-window-titlebar" id="popup-window-titlebar-one_click_form">
                    <span class="access-title-bar"></span>
                </div>
                <div id="popup-window-content-one_click_form">
                    <div id="ajax-one-click"></div>
                </div>
                <a class="popup-window-close-icon popup-window-titlebar-close-icon" href="javascript:void(0);"></a>
            </div>

            <?php 
            /*<div class="crm-btn">
                      <?php  //pre( $arResult['DISPLAY_PROPERTIES']['ARTNUMBER']['DISPLAY_VALUE']);?>
                      <script id="bx24_form_button" data-skip-moving="true">
                          (function(w,d,u,b){w['Bitrix24FormObject']=b;w[b] = w[b] || function(){arguments[0].ref=u;
                              (w[b].forms=w[b].forms||[]).push(arguments[0])};
                              if(w[b]['forms']) return;
                              var s=d.createElement('script');s.async=1;s.src=u+'?'+(1*new Date());
                              var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                          })(window,document,'https://b24-wn3zq2.bitrix24.ua/bitrix/js/crm/form_loader.js','b24form');

                          b24form({"id":"8","lang":"ua","sec":"aw079k","type":"button","click":"","presets": {"my_param1": "<?=$APPLICATION->ShowTitle(false);?>", "my_param2" : "<?=$arResult['DISPLAY_PROPERTIES']['ARTNUMBER']['DISPLAY_VALUE'];?>", "my_param3" : "<?=$crmPrice['DISCOUNT_VALUE']?>"}});
                      </script><button  class="b24-web-form-popup-btn-8 mc-button">Заказ в 1 Клик</button>
                  </div>*/ ?>
            <div style="display: none;"></div>
            <?
            if ($mPriceTrigerEgoMat > 0) {
                if ((true === !isset($arItem['CATALOG_PRICE_2']) || $arItem['CATALOG_PRICE_2'] == 0)
                    && true === empty($arResult['PROPERTIES']['CONSTANT_PRICE']['VALUE'])) {
                    ?>
                    <div class="precent-block">
                        <span class="precent">Цена со скидкой по дисконтным картам</span>
                    </div>
                    <div class="sails-price-detail">
                    <span class="percent doverie-detail"><?= round($mPriceTrigerEgoMat * 0.95, 2) . ' грн'; ?></span>
                    <span class="percent vip-detail"><?= round($mPriceTrigerEgoMat * 0.93, 2) . ' грн'; ?></span>
                    <span class="percent zabota-detail"><?= round($mPriceTrigerEgoMat * 0.93, 2) . ' грн'; ?><?/*=round($mPriceTrigerEgoMat*0.9, 2).' грн'; 10% скидка*/
                        ?></span>
                    </div><?
                }
            } ?>

            <div class="clb"></div>
        </div>
        </div>

        <script>
            gtag('event', 'view_item', {
                "items": [
                    {
                        "id": "<?= $arResult["ID"];?>",
                        "name": "<?= $APPLICATION->ShowTitle(false); ?>",
                        "list_name": "Search Results",
                        "brand": "<?= $arResult['PROPERTIES']['MANUFACTURER']['VALUE'] ?>",
                        "category": "Apparel/T-Shirts",
                        "list_position": 1,
                        "quantity": 1,
                        "price": '<?= $minPrice['DISCOUNT_VALUE'];?>'
                    }
                ]
            });
        </script>
        <div class="bx_md">
            <div class="item_info_section">
                <?
                if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
                    if ($arResult['OFFER_GROUP']) {
                        foreach ($arResult['OFFER_GROUP_VALUES'] as $offerID) {
                            ?>
                            <span id="<?php echo $arItemIDs['OFFER_GROUP'] . $offerID; ?>" style="display: none;">
<?
$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
    ".default",
    array(
        "IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
        "ELEMENT_ID" => $offerID,
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "BASKET_URL" => $arParams["BASKET_URL"],
        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
        "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
        "CURRENCY_ID" => $arParams["CURRENCY_ID"]
    ),
    $component,
    array("HIDE_ICONS" => "Y")
); ?><?
?>
	</span>
                            <?
                        }
                    }
                } else {
                    if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP']) {
                        ?>

                        <?
                        $APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
                            ".default",
                            array(
                                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                "ELEMENT_ID" => $arResult["ID"],
                                "PRICE_CODE" => $arParams["PRICE_CODE"],
                                "BASKET_URL" => $arParams["BASKET_URL"],
                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                "TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
                                "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
                                "CURRENCY_ID" => $arParams["CURRENCY_ID"]
                            ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        ); ?><?
                    }
                }
                ?>
            </div>
        </div>
        <div class="bx_rb" style="display: none;"></div>
        <div class="bx_lb">
            <div class="tac ovh">
            </div>

            <div class="tab-section-container">
                <?php 
                $arResult['DETAIL_TEXT'] = preg_replace('#(h1|H1)#', 'h2', $arResult['DETAIL_TEXT']);
                $arResult['DETAIL_TEXT'] = str_replace('href', 'rel="nofollow" href', $arResult['DETAIL_TEXT']);
                ?>
                <div class="Tabs_Detail_Page">
                    <input id="tab_1" type="radio" checked="checked" name="radio_type"/>
                        <label for="tab_1">Краткое описание</label>
                    <input id="tab_2" type="radio" name="radio_type"/>
                        <label for="tab_2"><?php echo GetMessage('LEFTOVERS'); ?></label>
                    <?php  if (!empty(trim($arResult['DETAIL_TEXT'])) || $arResult === '') { ?>
                        <input id="tab_3" type="radio" name="radio_type"/>
                            <label for="tab_3">Инструкция</label>
                    <?php  } ?>
                    <input id="tab_4" type="radio" name="radio_type"/>
                        <label for="tab_4"><?php echo GetMessage('REVIEWS'); ?></label>

                    <div class="tab_containers">
                        <div class="tab_1_conts">
                            <span><?= str_replace("¶", "<br />", $arResult['PREVIEW_TEXT']) ?></span>
                        </div>
                        <div class="tab_3_conts"
                             style="display: <?php if (empty(trim($arResult['DETAIL_TEXT'])) || $arResult === '') {
                                 echo "none";
                             } ?>;">
                            <?php /*<span>
					<span><?=$arResult['PROPERTIES']['MANUFACTURER']['VALUE']?></span> (
					<span><?=$arResult['PROPERTIES']['COUNTRY']['VALUE']?></span> )
				</span>*/
                            ?>

                            <?
                            $ptn = "/^[0-9a-zA-Z_]{1,20}[\.]{1,1}htm/";
                            preg_match($ptn, $arResult['DETAIL_TEXT'], $matches);
                            ?>
                            <div class="bx_item_description">
                                <div class="bx_item_section_name_gray"
                                     style="display: none; /*border-bottom: 1px solid #f2f2f2;*/"><?php echo GetMessage('FULL_DESCRIPTION'); ?></div>
                                <?
                                $Fchar = strtoupper(substr($arResult['DETAIL_TEXT'], 0, 1));
                                mb_internal_encoding("UTF-8");
                                $toDelete = 1;
                                $result = mb_substr($arResult['DETAIL_TEXT'], $toDelete);
                                $resultText = $Fchar . $result;
                                $arResult['DETAIL_TEXT'] = $resultText;
                                if ('html' == $arResult['DETAIL_TEXT_TYPE']) {
                                    ?><span class="inst-set-all"><?
                                    echo str_replace("¶", "<br />", $arResult['DETAIL_TEXT']); ?></span><?
                                } else {
                                    ?><p><?php echo $arResult['DETAIL_TEXT']; ?></p><?
                                }
                                ?>
                            </div>
                        </div>
                        <div class="tab_2_conts">
                            <div id="leftovers_select_result"></div>
                            <div id="leftovers_result">
                                <div class="leftovers_cssload-container">
                                    <div class="leftovers_cssload-speeding-wheel"></div>
                                </div>
                            </div>
                            <input type="hidden" id="leftovers_ajax_path"
                                   value="/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/bitrix/catalog.element/price/ajax.php"/>
                            <input type="hidden" id="sessId" value="<?= session_id() ?>"/>
                            <input type="hidden" id="xmlId" value="<?= $arResult['XML_ID'] ?>"/>
                            <input type="hidden" id="product" value="<?= $arResult['NAME'] ?>"/>
                            <input type="hidden" id="productId" value="<?= $arResult['ID'] ?>"/>
                            <input type="hidden" id="price" value="<?= $arResult['PRICES']['BASE']['VALUE'] ?>"/>
                            <input type="hidden" id="currency"
                                   value="<?= $arResult['CURRENCIES'][0]['FORMAT']['FORMAT_STRING'] ?>"/>
                        </div>
                        <div class="tab_4_conts">
                            <?
                            if ('Y' == $arParams['USE_COMMENTS']) {
                                ?>
                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:catalog.comments",
                                    ".default",
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
                                ); ?>
                                <?
                            }
                            ?>
<!--                            <div class="reitingBlock" itemscope itemtype="http://schema.org/Product">-->
                            <div class="reitingBlock">
                                <div>
                                    <span>Качество</span>
                                    <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:iblock.vote",
                                        "quality",
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
                                    ); ?>
                                </div>
                                <!--iblock.vote общая оценка  -->
                                <div>
                                    <span>Общая оценка</span>
                                    <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:iblock.vote",
                                        "assessment",
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
                                    ); ?>
                                </div>
                                <div>
                                    <span>Цена</span>
                                    <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:iblock.vote",
                                        "price",
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
                                    ); ?>
                                </div>
                                <meta itemprop="name" content="Качество">
                            </div>
                        </div>
                    </div>

                    <div pharmacy="" id="ajax-add-answer"></div>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="clb"></div>
</div><?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
    foreach ($arResult['JS_OFFERS'] as &$arOneJS) {
        if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE']) {
            $arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
            $arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
        }
        $strProps = '';
        if ($arResult['SHOW_OFFERS_PROPS']) {
            if (!empty($arOneJS['DISPLAY_PROPERTIES'])) {
                foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp) {
                    $strProps .= '<dt>' . $arOneProp['NAME'] . '</dt><dd>' . (
                        is_array($arOneProp['VALUE'])
                            ? implode(' / ', $arOneProp['VALUE'])
                            : $arOneProp['VALUE']
                        ) . '</dd>';
                }
            }
        }
        $arOneJS['DISPLAY_PROPERTIES'] = $strProps;
    }
    if (isset($arOneJS))
        unset($arOneJS);
    $arJSParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => true,
            'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
            'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
            'OFFER_GROUP' => $arResult['OFFER_GROUP'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
        ),
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'VISUAL' => array(
            'ID' => $arItemIDs['ID'],
        ),
        'DEFAULT_PICTURE' => array(
            'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
            'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
        ),
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'NAME' => $arResult['~NAME']
        ),
        'BASKET' => array(
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        ),
        'OFFERS' => $arResult['JS_OFFERS'],
        'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
        'TREE_PROPS' => $arSkuProps
    );
    if ($arParams['DISPLAY_COMPARE']) {
        $arJSParams['COMPARE'] = array(
            'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
            'COMPARE_PATH' => $arParams['COMPARE_PATH']
        );
    }
} else {
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
    if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties) {
        ?>
        <div id="<?php echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
            <?
            if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
                foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                    ?>
                    <input type="hidden" name="<?php echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php echo $propID; ?>]"
                           value="<?php echo htmlspecialcharsbx($propInfo['ID']); ?>">
                    <?
                    if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
                        unset($arResult['PRODUCT_PROPERTIES'][$propID]);
                }
            }
            $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
            if (!$emptyProductProperties) {
                ?>
                <table>
                    <?
                    foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
                        ?>
                        <tr>
                            <td><?php echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
                            <td>
                                <?
                                if (
                                    'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
                                    && 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
                                ) {
                                    foreach ($propInfo['VALUES'] as $valueID => $value) {
                                        ?><label><input type="radio"
                                                        name="<?php echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php echo $propID; ?>]"
                                                        value="<?php echo $valueID; ?>" <?php echo($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><?php echo $value; ?>
                                        </label><br><?
                                    }
                                } else {
                                    ?><select
                                    name="<?php echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php echo $propID; ?>]"><?
                                    foreach ($propInfo['VALUES'] as $valueID => $value) {
                                        ?>
                                        <option
                                        value="<?php echo $valueID; ?>" <?php echo($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><?php echo $value; ?></option><?
                                    }
                                    ?></select><?
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <?
            }
            ?>
        </div>


        </div>
        <?
    }
    if ($arResult['MIN_PRICE']['DISCOUNT_VALUE'] != $arResult['MIN_PRICE']['VALUE']) {
        $arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];
        $arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
    }
    $arJSParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
            'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
            'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
        ),
        'VISUAL' => array(
            'ID' => $arItemIDs['ID'],
        ),
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'PICT' => $arFirstPhoto,
            'NAME' => $arResult['~NAME'],
            'SUBSCRIPTION' => true,
            'PRICE' => $arResult['MIN_PRICE'],
            'BASIS_PRICE' => $arResult['MIN_BASIS_PRICE'],
            'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
            'SLIDER' => $arResult['MORE_PHOTO'],
            'CAN_BUY' => $arResult['CAN_BUY'],
            'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
            'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
            'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
            'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
        ),
        'BASKET' => array(
            'ADD_PROPS' => ($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y'),
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'EMPTY_PROPS' => $emptyProductProperties,
            'BASKET_URL' => $arParams['BASKET_URL'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        )
    );
    if ($arParams['DISPLAY_COMPARE']) {
        $arJSParams['COMPARE'] = array(
            'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
            'COMPARE_PATH' => $arParams['COMPARE_PATH']
        );
    }
    unset($emptyProductProperties);
}
?>
<script>
    let <?php echo $strObName; ?> =
    new JCCatalogElement(<?php echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
    BX.message({
        ECONOMY_INFO_MESSAGE: '<?php echo GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO'); ?>',
        BASIS_PRICE_MESSAGE: '<?php echo GetMessageJS('CT_BCE_CATALOG_MESS_BASIS_PRICE') ?>',
        TITLE_ERROR: '<?php echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
        TITLE_BASKET_PROPS: '<?php echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
        BASKET_UNKNOWN_ERROR: '<?php echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
        BTN_SEND_PROPS: '<?php echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
        BTN_MESSAGE_BASKET_REDIRECT: '<?php echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT') ?>',
        BTN_MESSAGE_CLOSE: '<?php echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE'); ?>',
        BTN_MESSAGE_CLOSE_POPUP: '<?php echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP'); ?>',
        TITLE_SUCCESSFUL: '<?php echo GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK'); ?>',
        COMPARE_MESSAGE_OK: '<?php echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK') ?>',
        COMPARE_UNKNOWN_ERROR: '<?php echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR') ?>',
        COMPARE_TITLE: '<?php echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE') ?>',
        BTN_MESSAGE_COMPARE_REDIRECT: '<?php echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT') ?>',
        SITE_ID: '<?php echo SITE_ID; ?>'
    });
</script>

<script>
    <!--
    BX.ready(function () {

        let addAnswer = new BX.PopupWindow("leftovers_form", null, {
            content: BX('ajax-add-answer'),
            closeIcon: {right: "20px", top: "10px"},
            titleBar: {
                content: BX.create("span", {
                    html: '<b>Резервирование товара</b>',
                    'props': {'className': 'access-title-bar'}
                })
            },
            zIndex: 0,
            offsetLeft: 0,
            offsetTop: 0,
            draggable: {restrict: false},
            buttons: []
        });
        $('*').on("click", ".leftovers_popup", function () {
            let ajax_add_answer = $("#ajax-add-answer");
            ajax_add_answer.attr("pharmacy", $(this).parent().prev().prev().prev().text())
            ajax_add_answer.attr("qty", $(this).parent().prev().prev().text())
            ajax_add_answer.attr("product", $("#product").val())
            ajax_add_answer.attr("productId", $("#productId").val())
            ajax_add_answer.attr("pharmId", $(this).parent().prev().prev().prev().attr('pharmId'))
            ajax_add_answer.attr("price", $("#price").val())
            ajax_add_answer.attr("currency", $("#currency").val())
            BX.ajax.insertToNode('/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/bitrix/catalog.element/price/reserve.php', BX('ajax-add-answer'));
            addAnswer.show();
        });

        let ajax_one_click = $("#ajax-one-click");

        $("#one_click_form .popup-window-close-icon").click(function () {
            $("#one_click_form ").css("display", "none");
        });
        $('#one-click-btn').click(function () {
            $("#one_click_form ").css("display", "block");
            let one_click_url = '/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/bitrix/catalog.element/price/click.php';

            ajax_one_click.empty();
            ajax_one_click.append('<div class="leftovers_cssload-container" >' +
                '<div class="leftovers_cssload-speeding-wheel"></div>' +
                '</div>');
            $("#ajax-one-click .leftovers_cssload-container").css("display", "block");
            $("#ajax-one-click .leftovers_cssload-container").css("margin-bottom", "40px");
            $.ajax({
                type: "POST",
                url: one_click_url,
                cache: false,
                data: {
                    "prd-id": "<?=$arResult['ID']?>",
                    "sessId": "<?= session_id() ?>",
                    "prd-src": "<?=$arFirstPhoto['SRC']; ?>",
                    "prd-name": "<?=$APPLICATION->ShowTitle(false);?>",
                    "prd-price": "<?=$arResult['PRICES']['BASE']['VALUE']?>"
                },
                success: function (response) {
                    ajax_one_click.html(response);
                }
            });
        });

    });
    //-->
</script>

<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "<?= $APPLICATION->ShowTitle(false); ?>",
        "image": "<?="https://" . $_SERVER["HTTP_HOST"] . $arFirstPhoto['SRC']; ?>",
        "description": "<?= $APPLICATION->ShowTitle(false); ?> в интернет-магазине",
        "sku": "<?=$arResult['DISPLAY_PROPERTIES']["ARTNUMBER"]["DISPLAY_VALUE"]?>",
        "mpn": "<?=$arResult['DISPLAY_PROPERTIES']["ARTNUMBER"]["DISPLAY_VALUE"]?>",
        "brand": {
            "name": "<?=$arResult['DISPLAY_PROPERTIES']["MANUFACTURER"]["DISPLAY_VALUE"]?>"
        },
        "offers": {
            "@type": "Offer",
            "url": "'https://'<?=$_SERVER["HTTP_HOST"]?><?=$_SERVER["REQUEST_URI"]?>",
            "priceCurrency": "<?=$arResult["CATALOG_CURRENCY_1"]?>",
            "price": "<?=$arResult["CATALOG_PRICE_1"]?>",
            "availability": "InStock",
            "priceValidUntil": "2029-12-31"
        },
        "review": {
            "@type": "Review",
            "reviewRating": {
              "@type": "Rating",
              "ratingValue": "4",
              "bestRating": "5"
            },
            "author": {
              "@type": "Person",
              "name": "Admin"
            }
          },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "5",
            "reviewCount": "5"
        }
    }
</script>




