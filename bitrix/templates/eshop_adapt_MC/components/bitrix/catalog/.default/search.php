<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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


set_time_limit(120);
/*
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
/* */

$this->addExternalCss("/bitrix/css/main/bootstrap.css");

if($arParams["USE_COMPARE"]=="Y")
{
	?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.list",
	"",
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"NAME" => $arParams["NAME"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?
}
if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
}
else
{
	$basketAction = (isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '');
}
?>
<div class="col-sm-9">
	<div class="row">
		<?/*$APPLICATION->IncludeComponent(
			"bitrix:catalog.search",
			".default",
			array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
				"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
				"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
				"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
				"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
				"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
				"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
				"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
				"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
				"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
				"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
				"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
				"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
				"SECTION_URL" => $arParams["SECTION_URL"],
				"DETAIL_URL" => $arParams["DETAIL_URL"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
				"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
				"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
				"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
				"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
				"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
				"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
				"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
				"PAGER_TITLE" => $arParams["PAGER_TITLE"],
				"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
				"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
				"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
				"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
				"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
				"FILTER_NAME" => "searchFilter",
				"SECTION_ID" => "",
				"SECTION_CODE" => "",
				"SECTION_USER_FIELDS" => array(),
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_ALL_WO_SECTION" => "Y",
				"META_KEYWORDS" => "",
				"META_DESCRIPTION" => "",
				"BROWSER_TITLE" => "",
				"ADD_SECTIONS_CHAIN" => "N",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "N",

				"RESTART" => "N",
				"NO_WORD_LOGIC" => "Y",
				"USE_LANGUAGE_GUESS" => "Y",
				"CHECK_DATES" => "Y",

				'LABEL_PROP' => $arParams['LABEL_PROP'],
				'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
				'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

				'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
				'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
				'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
				'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
				'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
				'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
				'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
				'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
				'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
				'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

				'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
				'ADD_TO_BASKET_ACTION' => $basketAction,
				'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
				'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare']
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);
		//pre($arParams["LIST_PROPERTY_CODE"]);
		unset($basketAction);
		*/
        ?>
        <div class="search-page-content">
            <div>
            <form action="/catalog/" method="get">
                <input type="text" id="title-search-input" class="bx-form-control" name="q" value="" size="40">
                &nbsp;<button style="float: none; margin-top: 0;" class="mc-button search" type="submit" value=""><i class="fa fa-search"></i>Искать</button>
                <input type="hidden" name="how" value="r">
            </form>
            </div>
        <?php
//        if (!isset($_GET["lev"])) {
//            $levSearch = (new LevenshteinSearch())
////                ->setMinPercent(70)
//                ->debug($_REQUEST['debug'] == 'y')
//                ->findWords(mb_strtolower($_GET['q']));
            ?>
                <div data-q="<?=$_GET['q']?>" data-lev="<?=$levSearch[0]?>" class="maybe_you_mean">
<!--                    <span id="search-no">По Вашему запросу <b><em>"--><?//=$_GET['q']?><!--"</em></b> ничего не найдено!</span><br />-->
                    <?php
//                    if (count($levSearch)) {
//                        $result = array_unique($levSearch);
//                        echo "Возможно, Вы имели в виду: ";
//                        $i = 1;
//                        foreach ($result as $phrase) {
//                            echo "<a href='?q=" . $phrase . "&lev=true'>" . $phrase . "</a>" . ($i != count($result) ? ' или ' : '');
//                            $i++;
//                        }
//                    }
                    ?>
                </div>
            <?php
//        } else {
        ?>

<!--            --><?php //}?>
        </div>
		<!-- Форма поиска -->


		<!-- /Форма поиска -->
		<!-- Вывод результатов поиска -->
		<?php
            if (!CModule::IncludeModule("iblock")){
                die('Модуль Инфоблоков не подключен!');
            }

        $result = Tools::search($_SERVER['REQUEST_URI'], $_GET['q']);

		if (count($result) == 0) { ?>
            <div data-q="<?=$_GET['q']?>" data-lev="<?=$levSearch[0]?>" class="maybe_you_mean">
                <span id="search-no">По Вашему запросу <b><em>"<?=$_GET['q']?>"</em></b> ничего не найдено!</span><br />
                <?php
                $search_huphen = false;
                $search_huphen_array = [];

                $dbRes = DictionaryTable::getList();
                while ($item = $dbRes->fetch()) {
                    if ($item["WITHOUT_HUPHEN"] == $_GET['q']) {
                        $search_huphen_array[] = 1;
                        echo "Возможно, Вы имели в виду: <a href='#'>" . $item["UF_WORD"] . "</a>";
                        $result = Tools::search($_SERVER['REQUEST_URI'], $item["UF_WORD"]);
                    }
                }

                if (count($search_huphen_array) == 0) {
                    $search_huphen = true;
                }

                if ($search_huphen) {

                    $levSearch = (new LevenshteinSearch())
                        ->setMinPercent(70)
                        ->debug($_REQUEST['debug'] == 'y')
                        ->findWords(mb_strtolower($_GET['q']));

                    if (count($levSearch)) {
                        $result = Tools::search($_SERVER['REQUEST_URI'], $levSearch[0]);
                        $levResult = array_unique($levSearch);
                        echo "Возможно, Вы имели в виду: ";
                        $levCount = 1;
                        foreach ($levResult as $phrase) {
                            echo "<a href='?q=" . $phrase . "&lev=true'>" . $phrase . "</a>" . ($levCount != count($levResult) ? ' или ' : '');
                            $levCount++;
                        }
                    }
                }
                ?>
            </div>
        <?php
        }

		echo '<div id="res-up" class="search-rez-wrap">';
		$arElem = '';

        usort($result, function($a, $b) {
            return ($a['SORT'] < $b['SORT']) ? -1 : 1;
        });

		foreach($result as $arElem){
            $rsPrices = CPrice::GetList(array(), array('PRODUCT_ID' => $arElem['ID']))->Fetch();
            $buy_status = Tools::isVisibleButtonForOrder($arElem["PHARM_LEFTOVERS"], $arElem["LAST_AVAILABILITY"], intval($rsPrices["PRICE"]));
//            $lo = Tools::getLeftovers ($arElem["EXTERNAL_ID"], false);
            $CPrice = new CPrice;
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $arElem['ID'],
                    //"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
		    "CATALOG_GROUP_ID" => 1
                )
            );
            if ($arr = $res->Fetch()){
                $arElem['PRICE']=$arr;
                if($arElem['PRICE']['PRICE'] > 0){
                    $price = $arElem['PRICE']['PRICE'];
                }
            }
            $promotional_price = Tools::getPromotionalPriceByProductId ($arElem['ID']);
            $persent = round(($promotional_price - $price)*100 / $promotional_price);
				echo '<div data-log="' . print_r([
				        $arElem["PHARM_LEFTOVERS"],
                        $arElem["ID"],
                        $arElem["XML_ID"],
                        $arElem["LAST_AVAILABILITY"],
                        $buy_status,
                        $rsPrices["PRICE"]
                    ], 1) . '" class="search-inner-rez">';
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
							if ($file['height'] != 0) {
                                $img = '<div class="rez-box-pict" style="width:253px;">
									<div class="inner-pict-rez">';
                                if ($persent > 0)
                                    $img .= '<div class="bx_stick_disc_right_bottom">-' . $persent . '%</div>';
                                $img .= '<img class="lazy" data-original="'.$file['src'].'" width="" height="'.$file['height'].'" />
									</div>
									</div>';
                                echo $img;
                            } else {
                                $img = '<div class="rez-box-pict" style="width:253px;">
								<div class="inner-pict-rez">';
                                if ($persent > 0)
                                    $img .= '<div class="bx_stick_disc_right_bottom" id="">-' . $persent . '%</div>';
                                $img .= '<img class="lazy" data-original="/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/bitrix/catalog.element/price/images/no_photo.png" style="height:256px;" />
								</div>
							</div>';
                                echo $img;
                            }
						} else {
							$img = '<div class="rez-box-pict" style="width:253px;">
								<div class="inner-pict-rez">';
                            if ($persent > 0)
                                $img .= '<div class="bx_stick_disc right bottom" id="">-' . $persent . '%</div>';
                                $img .= '<img class="lazy" data-original="/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/bitrix/catalog.element/price/images/no_photo.png" style="height:256px;" />
								</div>
							</div>';
							echo $img;
						}
						//<Конец>Выводим картинку элемента
						//<Начало>Выводим Наименование элемента
						echo '<span class="name_element">'.$arElem['NAME'].'</span>'.'<br/>';
						//<Конец>Выводим Наименование элемента

                       if (!in_array($buy_status, [3, 4])) {
                            echo '<span class="element_price">'.$price.' грн.'.'</span>';
                        }
						//<Конец>Выводим цену элемента
						$arElements[]=$arElem;
            $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
            echo '</a>';
            ?>
                        <div id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" class="bx_catalog_item_controls_blocktwo">

			<?php if ($buy_status == 1) { ?>
        <button onclick='add2basket(<? echo $arElem['ID']; ?>); return false;'  class="mc-button" href="javascript:void(0)" rel="nofollow">
                <?php
            if ($arParams['ADD_TO_BASKET_ACTION'] == 'BUY') {
                echo ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCS_TPL_MESS_BTN_BUY'));
            } else {
//                echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET'));
                echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? "В корзину" : GetMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET'));
            }
            ?>
        </button >
            <div id="BX_BTN_<? echo $arElem['ID']; ?>"></div><?
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
                            "my_param1": "<?=$arElem["NAME"];?>",
                            "my_param2": "<?=$arElem["XML_ID"];?>",
                            "my_param3": "<?=$arElem["PRICES"]["BASE"]["VALUE"] ?>"
                        }
                    });
                </script>
                <button class="b24-web-form-popup-btn-8 mc-button zakaz"> Под заказ </button>
            <?php } else if (in_array($buy_status, [4])) {?>
                <button class="mc-button is-not-store"> Нет в наличии </button>
            <?php }?>
    </div>
<?php
            if (!in_array($buy_status, [3, 4])) {
                echo '<span style="text-decoration: line-through">' . Tools::getPromotionalPriceByProductId($arElem['ID']) . '</span>';
            }
				echo '</div>';
			}
		echo '</div>';
		?>
		<!-- /Вывод результатов поиска -->
	</div>
</div>
<div class="sidebar col-md-3 col-sm-4 hidden-xs">
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
</div><!--// sidebar -->
<script>
	//window.onload = function() {
		let searchBox = document.getElementById('res-up');
		let searchNo = document.getElementById('search-no');
		let maybeYouMean = document.getElementsByClassName('maybe_you_mean')[0];
		let topSearch = document.getElementById('title-search-input');
		let search = {
			sr: topSearch
		};

		Object.values(search)[0].value;
		//Показать или спрятать найденные товары
		for (let i = 0; i < searchBox.children.length; i++) {
			if (topSearch.value == '' || !topSearch.value.match(/[а-яА-Яa-zA-Z\ ]+/ig)) {
				searchBox.children[i].style.display = 'none';
				searchNo.style.display = 'inline-block';
                maybeYouMean.style.display = 'inline-block';
			} else {
				searchBox.children[i].style.display = 'inline-block';
				searchNo.style.display = 'none';
                // maybeYouMean.style.display = 'none';
			}
		}

    let q = $('.maybe_you_mean').data('q').toLowerCase();
    let lev = $('.maybe_you_mean').data('lev').toLowerCase();
    if (q === lev)
        maybeYouMean.style.display = 'none';
    else
        searchNo.style.display = 'inline-block';

		//Событие формирования вводимого значения
		topSearch.onkeyup = function(e) {
			e = e || window.event;
			Object.values(search)[0].value;
			if (e.keyCode == 8 || e.which == 8) {
				searchBox.children;
				for (let j = 0; j < searchBox.children.length; j++) {
					searchBox.children[j].style.display = 'none';
				}
			}
		}
	//}





</script>
