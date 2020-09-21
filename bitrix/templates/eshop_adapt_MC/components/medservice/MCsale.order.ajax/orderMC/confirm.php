<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!empty($arResult["ORDER"])) {
	//Фильтрация по времени создания пользователя, если время создания совпадает с текущим - разлогинить
	$today = date("d.m.Y G:i");
	//pre($today);

	$rsUser = CUser::GetByID($USER->GetParam('USER_ID'));
	$arUser = $rsUser->Fetch();
	$rezUser = $arUser["DATE_REGISTER"];
	$restUser = substr($rezUser, 0, -3);
	//pre($restUser);

	if ($today == $restUser) {
		$USER->Logout();
		?>
			<script>
				// setTimeout(function() {
				// 	window.location.reload();
				// 	document.location.href="/";
				// }, 20000);
			</script>
		<?
	}
	//Фильтрация по времени создания пользователя//

	$order_props = CSaleOrderPropsValue::GetOrderProps($arResult['ORDER']['ID']);
	while ($arProps = $order_props->Fetch()){
		if ($arProps["CODE"] == "DKCARD"){
			$DKnumb = $arProps['VALUE'];
		}
	}
	$lastCh = substr($DKnumb, -1);
	if ($lastCh & 1) {
		$card = preg_replace("/[^0-9\(\)]/", '', $_POST['odd']);
		$sql = "SELECT `VALUE` FROM `ord_discoutncard_odd` WHERE `CARDNUMBER`='$DKnumb';";
	}else {
		$card = preg_replace("/[^0-9\(\)]/", '', $_POST['even']);
		$sql = "SELECT `VALUE` FROM `ord_discoutncard_even` WHERE `CARDNUMBER`='$DKnumb';";
	}
	//pre($sql);
	$qwery = new qwery;
	$value = $qwery->frqr($sql);
	$value= $value/100;
	//pre($value);
	unset($qwery);
	//pre($arResult);
	$res = CSaleBasket::GetList(array('ID' => 'ASC'), array('ORDER_ID' => $arResult['ORDER']['ID'])); // ID заказа
	$orderList = '';
	$allOrdPr = 0;
	$CPrice = new CPrice;
	$gtag_array = [];
	while ($staf = $res->Fetch()) {
        $gtag_array[] = [
            $staf["ID"],
            $staf["NAME"],
            $staf["QUANTITY"],
            $staf["PRICE"]
        ];
		//pre($staf);
		if($value > 0) {
			$id = $staf['PRODUCT_ID'];
			$PRICE_TYPE_ID = 2;
			$resZZZ = $CPrice->GetList(
				array(),
				array(
					"PRODUCT_ID" => $id,
					"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
				)
			);
			$arrZZZ = $resZZZ->Fetch();
		}
		if(empty($arrZZZ['PRICE']) || $arrZZZ['PRICE']== 0){
			$price = round($staf['PRICE'] - $staf['PRICE']*$value,2);
			$price = $price*$staf['QUANTITY'];
		} else {
			$price = $staf['PRICE']*$staf['QUANTITY'];
		}
		$allOrdPr = $allOrdPr + $price;
	}
	$allOrdPr = $allOrdPr.' грн.';
	?>
	<b><?=GetMessage('SOA_TEMPL_ORDER_COMPLETE')?></b><br /><br />
	<table class="sale_order_full_table">
		<tr>
			<td colspan="2">
				<?= GetMessage('SOA_TEMPL_ORDER_SUC', Array(
					'#ORDER_DATE#' => $arResult['ORDER']['DATE_INSERT'],
					'#ORDER_ID#' => $arResult['ORDER']['ACCOUNT_NUMBER']))?>
				<br /><br />
				<?//= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
    			<p><?=GetMessage('SOA_TEMPL_ORDER_SUC_TEXT');?></p>
			</td>
		</tr>
        <tr>
            <td style="padding-top: 20px">
                <a class="mc-button mc-button-home" style="display: inline-block; float: left" href="/">Главная</a>
            </td>
            <td style="padding-top: 20px">
                <a class="mc-button mc-button-home" style="display: inline-block;" href="/catalog">Каталог</a>
            </td>
        </tr>
	</table>
	<?
}
else
{
	?>
	<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td colspan="2">
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
        <tr>
            <td style="padding-top: 20px">
                <a class="mc-button mc-button-home" style="display: inline-block;" href="/">Главная</a>
            </td>
            <td style="padding-top: 20px">
                <a class="mc-button mc-button-home" style="display: inline-block;" href="/catalog">Каталог</a>
            </td>
        </tr>
	</table>
	<?
}?>

<script>
    gtag('event', 'purchase', {
        "transaction_id": "<?=$arResult['ORDER']['ID']?>",
        "affiliation": "Google online store",
        "value": <?=$arResult['ORDER']['PRICE']?>,
        "currency": "<?=$arResult['ORDER']['CURRENCY']?>",
        "tax": <?=$arResult['ORDER']['TAX_VALUE']?>,
        "shipping": 0,
        "items": [<?php
        foreach ($gtag_array as $i){
            ?>
            {
                "id": "<?=$i[0]?>",
                "name": "<?=$i[1]?>",
                "quantity": <?=$i[2]?>,
                "price": '<?=$i[3]?>'
            }
            <?php
            }
            ?>
        ]
    });

</script>

<!--<script>-->
<!--	setTimeout(function() {-->
<!--		window.location.reload();-->
<!--		document.location.href="/";-->
<!--	}, 20000);-->
<!--</script>-->
