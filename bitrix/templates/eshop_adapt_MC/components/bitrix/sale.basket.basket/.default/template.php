<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
/** @var CBitrixBasketComponent $component */
$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';
$arUrls = array(
	"delete" => $curPage."delete&id=#ID#",
	"delay" => $curPage."delay&id=#ID#",
	"add" => $curPage."add&id=#ID#",
);
unset($curPage);

$arBasketJSParams = array(
	'SALE_DELETE' => GetMessage("SALE_DELETE"),
	'SALE_DELAY' => GetMessage("SALE_DELAY"),
	'SALE_TYPE' => GetMessage("SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'ADD_URL' => $arUrls["add"]
);
//pre($arResult);


//pre($arResult["GRID"]["ROWS"]);


$CPrice = new CPrice;
foreach($arResult["GRID"]["ROWS"] as $key=>$row){
	$id = $row['PRODUCT_ID'];
	//pre($id);
	$PRICE_TYPE_ID = 2;
	$res = $CPrice->GetList(
		array(),
		array(
			"PRODUCT_ID" => $id,
			"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
		));
	$arr = $res->Fetch();
	$arResult['GRID']['ROWS'][$key]['DISCOUNT_PRICE'] = $arr['PRICE']-$row['PRICE'];
	if(!empty($arr['PRICE'])){
		//акционные товары на которые не распространяется скидка по
		//дисконтной карте
		$a = round((($arr['PRICE']-$row['PRICE'])*100)/$arr['PRICE']);
		$arResult['GRID']['ROWS'][$key]['DISCOUNT_PRICE_PERCENT_FORMATED'] = $a.'%';
		//$sum = $row['data']['PRICE'] * $row['data']['QUANTITY'];
	}
}
	//$arResult['GRID']['ROWS'][93][PRICE_VAT_VALUE] = 30.00;
	//$arResult['GRID']['ROWS'][93]['DISCOUNT_PRICE_PERCENT_FORMATED'] = '7%';










?>
<script type="text/javascript">
	var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>
</script>
<?
$APPLICATION->AddHeadScript($templateFolder."/script.js");

if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
{
	?>
	<div id="warning_message">
		<?
		if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"]))
		{
			foreach ($arResult["WARNING_MESSAGE"] as $v)
				ShowError($v);
		}
		?>
	</div>
	<?

	$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
	$normalHidden = ($normalCount == 0) ? 'style="display:none;"' : '';

	$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
	$delayHidden = ($delayCount == 0) ? 'style="display:none;"' : '';

	$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
	$subscribeHidden = ($subscribeCount == 0) ? 'style="display:none;"' : '';

	$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
	$naHidden = ($naCount == 0) ? 'style="display:none;"' : '';

	?>
		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
			<div id="basket_form_container">
				<div class="bx_ordercart">
					<div class="bx_sort_container">
						<span><?=GetMessage("SALE_ITEMS")?></span>
						<a href="javascript:void(0)" id="basket_toolbar_button" class="current" onclick="showBasketItemsList()"><?=GetMessage("SALE_BASKET_ITEMS")?><div id="normal_count" class="flat" style="display:none">&nbsp;(<?=$normalCount?>)</div></a>
						<a href="javascript:void(0)" id="basket_toolbar_button_delayed" onclick="showBasketItemsList(2)" <?=$delayHidden?>><?=GetMessage("SALE_BASKET_ITEMS_DELAYED")?><div id="delay_count" class="flat">&nbsp;(<?=$delayCount?>)</div></a>
						<a href="javascript:void(0)" id="basket_toolbar_button_subscribed" onclick="showBasketItemsList(3)" <?=$subscribeHidden?>><?=GetMessage("SALE_BASKET_ITEMS_SUBSCRIBED")?><div id="subscribe_count" class="flat">&nbsp;(<?=$subscribeCount?>)</div></a>
						<a href="javascript:void(0)" id="basket_toolbar_button_not_available" onclick="showBasketItemsList(4)" <?=$naHidden?>><?=GetMessage("SALE_BASKET_ITEMS_NOT_AVAILABLE")?><div id="not_available_count" class="flat">&nbsp;(<?=$naCount?>)</div></a>
					</div>
					<?
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delayed.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribed.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_not_available.php");
					?>
				</div>
			</div>
			<input type="hidden" name="BasketOrder" value="BasketOrder" />
			<!-- <input type="hidden" name="ajax_post" id="ajax_post" value="Y"> -->
		</form>
		<div class="bx_ordercart_order_pay_center bx_ordercart_order_pay_left">
			<a onclick="removeAttrDoublefnc(); return false" href="/" class="mc-button button-back">Продолжить покупки</a>
		</div>
	<?
}
else
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>
