<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Sale\DiscountCouponsManager;

if (!empty($arResult["ERROR_MESSAGE"]))
	ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
$bPriceType    = false;

if ($normalCount > 0):
?>
<div id="basket_items_list">
	<div class="bx_ordercart_order_table_container">
		<div id="basket_items">
			<div class="basket_items_head">
				<div class="basket_items_head_row">
					<?/*<div class="margin"></div>*/?>
					<?
					foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
						$arHeader["name"] = (isset($arHeader["name"]) ? (string)$arHeader["name"] : '');
						if ($arHeader["name"] == '')
							$arHeader["name"] = GetMessage("SALE_".$arHeader["id"]);
						$arHeaders[] = $arHeader["id"];

						// remember which values should be shown not in the separate columns, but inside other columns
						if (in_array($arHeader["id"], array("TYPE")))
						{
							$bPriceType = true;
							continue;
						}
						elseif ($arHeader["id"] == "PROPS")
						{
							$bPropsColumn = true;
							continue;
						}
						elseif ($arHeader["id"] == "DELAY")
						{
							$bDelayColumn = true;
							continue;
						}
						elseif ($arHeader["id"] == "DELETE")
						{
							$bDeleteColumn = true;
							continue;
						}
						elseif ($arHeader["id"] == "WEIGHT")
						{
							$bWeightColumn = true;
						}

						if ($arHeader["id"] == "NAME"):
						?>
							<div class="item" style="width: 40%;" colspan="2" id="col_<?=$arHeader["id"];?>">
						<?
						elseif ($arHeader["id"] == "PRICE"):
						?>
							<div class="price"  id="col_<?=$arHeader["id"];?>">
						<?
						else:
						?>
							<div class="custom" id="col_<?=$arHeader["id"];?>">
						<?
						endif;
						?>
							<?=$arHeader["name"]; ?>
							</div>
					<?
					endforeach;

					if ($bDeleteColumn || $bDelayColumn):
					?>
						<div class="custom"></div>
					<?
					endif;
					?>
						<?/*<div class="margin"></div>*/?>
				</div>
			</div>
								
			<div class="tbody">
				<?
				foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):

					if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):
				?>
					<div class="goods-price" id="<?=$arItem["ID"]?>">
											
						<?/*<div class="margin"></div>*/?>
						<?
						foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

							if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE"))) // some values are not shown in the columns in this template
								continue;

							if ($arHeader["id"] == "NAME"):
							?>
								<div class="itemphoto" style="" >
									<div class="bx_ordercart_photo_container">
										<?
										if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
											$url = $arItem["PREVIEW_PICTURE_SRC"];
										elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
											$url = $arItem["DETAIL_PICTURE_SRC"];
										else:
											$url = $templateFolder."/images/no_photo.png";
										endif;
										?>

										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
											<div class="bx_ordercart_photo" style="background-image:url('<?=$url?>')"></div>
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
									</div>
									<?
									if (!empty($arItem["BRAND"])):
									?>
									<div class="bx_ordercart_brand">
										<img alt="" src="<?=$arItem["BRAND"]?>" />
									</div>
									<?
									endif;
									?>
								
									<h2 class="bx_ordercart_itemtitle">
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
											<?=$arItem["NAME"]?>
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
									</h2>
									<div class="bx_ordercart_itemart">
										<?
										if ($bPropsColumn):
											foreach ($arItem["PROPS"] as $val):

												if (is_array($arItem["SKU_DATA"]))
												{
													$bSkip = false;
													foreach ($arItem["SKU_DATA"] as $propId => $arProp)
													{
														if ($arProp["CODE"] == $val["CODE"])
														{
															$bSkip = true;
															break;
														}
													}
													if ($bSkip)
														continue;
												}

												echo $val["NAME"].":&nbsp;<span>".$val["VALUE"]."<span><br/>";
											endforeach;
										endif;
										?>
									</div>
									<?
									if (is_array($arItem["SKU_DATA"]) && !empty($arItem["SKU_DATA"])):
										foreach ($arItem["SKU_DATA"] as $propId => $arProp):

											// if property contains images or values
											$isImgProperty = false;
											if (!empty($arProp["VALUES"]) && is_array($arProp["VALUES"]))
											{
												foreach ($arProp["VALUES"] as $id => $arVal)
												{
													if (!empty($arVal["PICT"]) && is_array($arVal["PICT"])
														&& !empty($arVal["PICT"]['SRC']))
													{
														$isImgProperty = true;
														break;
													}
												}
											}
											$countValues = count($arProp["VALUES"]);
											$full = ($countValues > 5) ? "full" : "";

											if ($isImgProperty): // iblock element relation property
											?>
												<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">

													<span class="bx_item_section_name_gray">
														<?=$arProp["NAME"]?>:
													</span>

													<div class="bx_scu_scroller_container">

														<div class="bx_scu">
															<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>"
																style="width: 200%; margin-left:0%;"
																class="sku_prop_list"
																>
																<?
																foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

																	$selected = "";
																	foreach ($arItem["PROPS"] as $arItemProp):
																		if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																		{
																			if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"])
																				$selected = "bx_active";
																		}
																	endforeach;
																?>
																	<li style="width:10%;"
																		class="sku_prop <?=$selected?>"
																		data-value-id="<?=$arSkuValue["XML_ID"]?>"
																		data-element="<?=$arItem["ID"]?>"
																		data-property="<?=$arProp["CODE"]?>"
																		>
																		<a href="javascript:void(0);">
																			<span style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>)"></span>
																		</a>
																	</li>
																<?
																endforeach;
																?>
															</ul>
														</div>

														<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
														<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
													</div>

												</div>
											<?
											else:
											?>
												<div class="bx_item_detail_size_small_noadaptive <?=$full?>">

													<span class="bx_item_section_name_gray">
														<?=$arProp["NAME"]?>:
													</span>

													<div class="bx_size_scroller_container">
														<div class="bx_size">
															<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>"
																style="width: 200%; margin-left:0%;"
																class="sku_prop_list"
																>
																<?
																foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

																	$selected = "";
																	foreach ($arItem["PROPS"] as $arItemProp):
																		if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																		{
																			if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
																				$selected = "bx_active";
																		}
																	endforeach;
																?>
																	<li style="width:10%;"
																		class="sku_prop <?=$selected?>"
																		data-value-id="<?=($arProp['TYPE'] == 'S' && $arProp['USER_TYPE'] == 'directory' ? $arSkuValue['XML_ID'] : $arSkuValue['NAME']); ?>"
																		data-element="<?=$arItem["ID"]?>"
																		data-property="<?=$arProp["CODE"]?>"
																		>
																		<a href="javascript:void(0);"><?=$arSkuValue["NAME"]?></a>
																	</li>
																<?
																endforeach;
																?>
															</ul>
														</div>
														<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
														<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>, <?=$countValues?>);"></div>
													</div>

												</div>
											<?
											endif;
										endforeach;
									endif;
									?>
								</div>
							<?
							elseif ($arHeader["id"] == "QUANTITY"):
							?>
								<div class="custom">
									<span><?=$arHeader["name"]; ?>:</span>
									<div class="centered">
										<div class="counter">
											
												<div class="quantity-area">
													<?
													$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
													$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
													$useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
													$useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
													?>
													<input
														class="count-goods"
														type="text"
														size="3"
														id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
														name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
														size="2"
														maxlength="18"
														min="0"
														<?=$max?>
														step="<?=$ratio?>"
														style="max-width: 50px"
														value="<?=$arItem["QUANTITY"]?>"
														onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', <?=$ratio?>, <?=$useFloatQuantityJS?>)"
													>
												</div>
												<?
												if (!isset($arItem["MEASURE_RATIO"]))
												{
													$arItem["MEASURE_RATIO"] = 1;
												}

												if (
													floatval($arItem["MEASURE_RATIO"]) != 0
												):
												?>
													
														<div class="basket_quantity_control">
															<a href="javascript:void(0);" class="plus" onclick="setQuantity(<?=$arItem["ID"]?>, <?=$arItem["MEASURE_RATIO"]?>, 'up', <?=$useFloatQuantityJS?>);"></a>
															<a href="javascript:void(0);" class="minus" onclick="setQuantity(<?=$arItem["ID"]?>, <?=$arItem["MEASURE_RATIO"]?>, 'down', <?=$useFloatQuantityJS?>);"></a>
															<a style="display: none;" href="javascript:void(0);" class="plus" onclick="clickPlus(this, <?=$arItem["ID"]?>, <?=$arItem['PRICE']?>);"></a>
															<a style="display: none;" href="javascript:void(0);" class="minus" onclick="clickMinus(this, <?=$arItem["ID"]?>, <?=$arItem['PRICE']?>);"></a>
															
															
														</div>
													
												<?
												endif;
												if (isset($arItem["MEASURE_TEXT"]))
												{
													?>
														<td style="text-align: left"><?=$arItem["MEASURE_TEXT"]?></td>
													<?
												}
												?>
											</div>
										
									</div>
									<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
								</div>
							<?
							elseif ($arHeader["id"] == "PRICE"):
							//pre($arItem["PRICE_FORMATED"]);
							//pre($arItem["ID"]);
							
							?>
								<div class="price">
										<div class="current_price" id="current_price_<?=$arItem["ID"]?>">
											<?=$arItem["PRICE_FORMATED"]?>
										</div>
										<div class="old_price" id="old_price_<?=$arItem["ID"]?>">
											<?if (floatval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0):?>
												<?=$arItem["FULL_PRICE_FORMATED"]?>
											<?endif;?>
									</div>
<div style="display: none;"><?php pre($arItem['PRICE']); ?></div>
									<?if ($bPriceType && strlen($arItem["NOTES"]) > 0):?>
										<div class="type_price"><?=GetMessage("SALE_TYPE")?></div>
										<div class="type_price_value"><?=$arItem["NOTES"]?></div>
									<?endif;?>
								</div>
							<?
							elseif ($arHeader["id"] == "DISCOUNT"):
							?>
								<div class="custom">
									<span><?=$arHeader["name"]; ?>:</span>
									<div class="discount_value" id="discount_value_<?=$arItem["ID"]?>">- <?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]?></div>
								</div>
								<?// pre($arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]);?>
							<?
							elseif ($arHeader["id"] == "WEIGHT"):
							?>
								<div class="custom">
									<span><?=$arHeader["name"]; ?>:</span>
									<?=$arItem["WEIGHT_FORMATED"]?>
								</div>
							<?
							else:
							?>
								<div class="custom">
									<span><?=$arHeader["name"]; ?>:</span>
									<?
									if ($arHeader["id"] == "SUM"):
									?>
										<div class="price_summ" id="sum_<?=$arItem["ID"]?>">
										<?
											endif;
											echo '<div class="price_summ_totaltext">итого</div>';?>
											
											<div id="total_<?=$arItem["ID"]?>" class="total"><?=$arItem[$arHeader["id"]];?></div><?
											if ($arHeader["id"] == "SUM"):
										?>
										</div>
									<?
									endif;
									?>
								</div>
							<?
							endif;
						endforeach;

						if ($bDelayColumn || $bDeleteColumn):
						?>
							<div class="control">
								<?
								if ($bDeleteColumn):
								?>
									<a class="mc-button delete" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>"><?/*=GetMessage("SALE_DELETE")*/?>x</a><br />
									<style>
										.mc-button.delete:before{
											display: none;
											
										}
										.mc-button.delete{
											-webkit-border-radius: 5px;
											-moz-border-radius: 5px;
											border-radius: 5px;
											margin: 0;
										}
									</style>
								<?
								endif;
								if ($bDelayColumn):
								?>
									<a class="hidden" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delay"])?>"><?=GetMessage("SALE_DELAY")?></a>
								<?
								endif;
								?>
							</div>
						<?
						endif;
						?>
							<?/*<div class="margin"></div>*/?>
					</div>
					<?
					endif;
				endforeach;
				?>
			</div> <!-- tbody -->
		</div>
	</div>
	<input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arHeaders, ","))?>" />
	<input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
	<input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
	<input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
	<input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />

	<div class="bx_ordercart_order_pay">

		<div class="bx_ordercart_order_pay_left" id="coupons_block">
		<?
		if ($arParams["HIDE_COUPON"] != "Y")
		{
		?>
			<div class="bx_ordercart_coupon">
				<span><?=GetMessage("STB_COUPON_PROMT")?></span><input type="text" id="coupon" name="COUPON" value="" onchange="enterCoupon();">
			</div><?
				if (!empty($arResult['COUPON_LIST']))
				{
					foreach ($arResult['COUPON_LIST'] as $oneCoupon)
					{
						$couponClass = 'disabled';
						switch ($oneCoupon['STATUS'])
						{
							case DiscountCouponsManager::STATUS_NOT_FOUND:
							case DiscountCouponsManager::STATUS_FREEZE:
								$couponClass = 'bad';
								break;
							case DiscountCouponsManager::STATUS_APPLYED:
								$couponClass = 'good';
								break;
						}
						?><div class="bx_ordercart_coupon"><input disabled readonly type="text" name="OLD_COUPON[]" value="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>" class="<? echo $couponClass; ?>"><span class="<? echo $couponClass; ?>" data-coupon="<? echo htmlspecialcharsbx($oneCoupon['COUPON']); ?>"></span><div class="bx_ordercart_coupon_notes"><?
						if (isset($oneCoupon['CHECK_CODE_TEXT']))
						{
							echo (is_array($oneCoupon['CHECK_CODE_TEXT']) ? implode('<br>', $oneCoupon['CHECK_CODE_TEXT']) : $oneCoupon['CHECK_CODE_TEXT']);
						}
						?></div></div><?
					}
					unset($couponClass, $oneCoupon);
				}
		}
		else
		{
			?>&nbsp;<?
		}
?>
		</div>
		<div class="bx_ordercart_order_pay_right">
			<table class="bx_ordercart_order_sum">
				<?if ($bWeightColumn):?>
					<?/*tr>
						<td class="custom_t1"><?=GetMessage("SALE_TOTAL_WEIGHT")?></td>
						<td class="custom_t2" id="allWeight_FORMATED"><?=$arResult["allWeight_FORMATED"]?></td>
					</tr */?>
				<?endif;?>
				<?if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y"):?>
					<?/*tr>
						<td><?echo GetMessage('SALE_VAT_EXCLUDED')?></td>
						<td id="allSum_wVAT_FORMATED"><?=$arResult["allSum_wVAT_FORMATED"]?></td>
					</tr*/?>
					<?/*tr>
						<td><?echo GetMessage('SALE_VAT_INCLUDED')?></td>
						<td id="allVATSum_FORMATED"><?=$arResult["allVATSum_FORMATED"]?></td>
					</tr*/?>
				<?endif;?>

					<tr>
						<td class="fwb"><?=GetMessage("SALE_TOTAL")?></td>
						<td class="fwb2" id="allSum_FORMATED" data-price="<?=$arResult['allSum']?>"><?=str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"])?></td>
					</tr>
					
					<tr>
						<td class="custom_t1"></td>
						<?/*td class="custom_t2" style="text-decoration:line-through; color:#828282;" id="PRICE_WITHOUT_DISCOUNT">
							<?if (floatval($arResult["DISCOUNT_PRICE_ALL"]) > 0):?>
								<?=$arResult["PRICE_WITHOUT_DISCOUNT"]?>
							<?endif;?>
						</td*/?>
					</tr>
			</table>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
		<div class="bx_ordercart_order_pay_center">

			<?if ($arParams["USE_PREPAYMENT"] == "Y" && strlen($arResult["PREPAY_BUTTON"]) > 0):?>
				<?=$arResult["PREPAY_BUTTON"]?>
				<span><?=GetMessage("SALE_OR")?></span>
			<?endif;?>

			<button href="javascript:void(0)" onclick="checkOut();" class="mc-button basket-mc-button" style=""><?=GetMessage("SALE_ORDER")?></button>
		</div>
	</div>
</div>
<?
else:
?>
<div id="basket_items_list">
	<table>
		<tbody>
			<tr>
				<td colspan="<?=$numCells?>" style="text-align:center">
					<div class=""><?=GetMessage("SALE_NO_ITEMS");?></div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?
endif;
?>


<div style="display: none;">
	
	<?/*<div class="basket_quantity_control">
		<a href="javascript:void(0);" class="plus" onclick="clickPlus(this);"></a>
		<a href="javascript:void(0);" class="minus" onclick="clickMinus(this);"></a>
	</div>*/?>
</div>
<script>
	
	//var current = document.getElementsByClassName('current_price');
	var total = document.getElementsByClassName('total');
	var countGoods = document.getElementsByClassName('count-goods');
	

	function clickPlus(plus, id, price){

		var totalBassket = document.getElementById('allSum_FORMATED');		 //сумма в корзине   
		var curTotal = 'total_'+id;
		var curTotalElement = document.getElementById(curTotal);

		var curID='QUANTITY_INPUT_'+id;
		var currentElement = document.getElementById(curID);
		currentElement.value = parseInt(currentElement.value) + 1;
		var summ = parseInt(currentElement.value) * parseFloat(price);
		var summFixed = summ.toFixed(2);
		curTotalElement.innerHTML = summFixed + ' грн.';
		var datatotalBassket = totalBassket.dataset.price;			   		//цена в атребуте data-price
		//console.log(DatatotalBassket);								   		//console.log data-price
		totalBassketSumm = parseFloat(price) + parseFloat(datatotalBassket); //сумма товара + сумма в атребуте
		totalBassket.dataset.price = totalBassketSumm;
		
		var totalBassketSummFixed = totalBassketSumm.toFixed(2);			 //кол-во знаков после целого числа
		totalBassket.innerHTML = totalBassketSummFixed + ' грн.';        

	}
	function clickMinus(minus, id, price){
		/*console.log(typeof true);

		console.log(parseInt(true));
		console.log(typeof id);
		if((typeof id) != 'number' && id <= 0 ){
			return false;
		} */
		
		
		var curID='QUANTITY_INPUT_'+id;			
		var currentElement = document.getElementById(curID);	
		
		if(currentElement.value <= 0){
			return false;
		}
		var totalBassket = document.getElementById('allSum_FORMATED');		 // сумма в корзине   
		var curTotal = 'total_'+id;											 // строка id 
		var curTotalElement = document.getElementById(curTotal);			 // строка id конкретного елемента

		//var curID='QUANTITY_INPUT_'+id;										// строка id инпута
					// строка id конкретного инпута
		currentElement.value = parseInt(currentElement.value) - 1;			// значение инпута преобразованое в число и -1
		
		
		var summ = parseInt(currentElement.value) * parseFloat(price);		// значение инпута умноженое на цену товара
		
		var summFixed = summ.toFixed(2);									//кол-во знаков после целого числа
		curTotalElement.innerHTML = summFixed + ' грн.';					//записал сумму цена за все товары
		
		var datatotalBassket = totalBassket.dataset.price;			   		//цена в атребуте data-price
		//console.log(datatotalBassket + " число в дата сет");
		
		totalBassketSumm = parseFloat(datatotalBassket) - parseFloat(price); //сумма в атребуте - сумма товара
		totalBassket.dataset.price = totalBassketSumm;
		
		var totalBassketSummFixed = totalBassketSumm.toFixed(2);			 //кол-во знаков после целого числа
		totalBassket.innerHTML = totalBassketSummFixed + ' грн.';    

		console.log(currentElement.value + ' текущее значение инпута');
			
	}


</script>