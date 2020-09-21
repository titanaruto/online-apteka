<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
?>
<div class="bx_ordercart">
	<h4><?=GetMessage("SALE_PRODUCTS_SUMMARY");?></h4>
	<div class="bx_ordercart_order_table_container">
		<table id="tableOrd">
			<thead class="basket_items_head_row">
				<tr>
					<td class="margin"></td>
					<?php
					$bPreviewPicture = false;
					$bDetailPicture = false;
					$imgCount = 0;

					// prelimenary column handling
					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn) {
						if ($arColumn["id"] == "PROPS")
							$bPropsColumn = true;

						if ($arColumn["id"] == "NOTES")
							$bPriceType = true;

						if ($arColumn["id"] == "PREVIEW_PICTURE")
							$bPreviewPicture = true;

						if ($arColumn["id"] == "DETAIL_PICTURE")
							$bDetailPicture = true;
					}

					if ($bPreviewPicture || $bDetailPicture)
						$bShowNameWithPicture = true;


					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn) {

						if (in_array($arColumn["id"], array("PROPS", "TYPE", "NOTES"))) // some values are not shown in columns in this template
							continue;

						if ($arColumn["id"] == "PREVIEW_PICTURE" && $bShowNameWithPicture)
							continue;

						if ($arColumn["id"] == "NAME" && $bShowNameWithPicture):
						?>
							<td class="item" colspan="2">
						<?php
							echo GetMessage("SALE_PRODUCTS");
						elseif ($arColumn["id"] == "NAME" && !$bShowNameWithPicture):
						?>
							<td class="item">
						<?php
							echo $arColumn["name"];
						elseif ($arColumn["id"] == "PRICE"):
						?>
							<td class="price">
						<?php
							echo $arColumn["name"];
						else:
						?>
							<td class="custom">
						<?php
							echo $arColumn["name"];
						endif;
						?>
							</td>
					<?}?>

					<td class="margin"></td>
				</tr>
			</thead>

			<tbody>
				<?foreach ($arResult["GRID"]["ROWS"] as $k => $arData) {?>
                <tr class="row-goods" id="<?= $arData['data']['ID'] ?>">
                    <td>
                        <a data-product-id="<?=$arData['data']['ID']?>" class="mc-button delete main"></a><br />
                    </td>
                    <td class="margin"></td>
                    <?
                    if ($bShowNameWithPicture):
                    ?>
                    <td class="itemphoto">
                        <div class="bx_ordercart_photo_container">
                            <?
                            if (strlen($arData["data"]["PREVIEW_PICTURE_SRC"]) > 0):
                                $url = $arData["data"]["PREVIEW_PICTURE_SRC"];
                            elseif (strlen($arData["data"]["DETAIL_PICTURE_SRC"]) > 0):
                                $url = $arData["data"]["DETAIL_PICTURE_SRC"];
                            else:
                                $url = $templateFolder . "/images/no_photo.png";
                            endif;

                            if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0): ?><a
                                    href="<?= $arData["data"]["DETAIL_PAGE_URL"] ?>"><?
                                endif;
                                ?>
                                <div class="bx_ordercart_photo" style="background-image:url('<?= $url ?>')"></div>
                                <?
                                if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0): ?></a><?
                        endif;
                        ?>
                        </div>
                        <?
                        if (!empty($arData["data"]["BRAND"])):
                            ?>
                            <div class="bx_ordercart_brand">
                                <img alt="" src="<?= $arData["data"]["BRAND"] ?>"/>
                            </div>
                        <?
                        endif;
                        ?>
                    </td>
                    <?
                    endif;
                    // prelimenary check for images to count column width
                    foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn) {
                        $arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];
                        if (is_array($arItem[$arColumn["id"]])) {
                            foreach ($arItem[$arColumn["id"]] as $arValues) {
                                if ($arValues["type"] == "image")
                                    $imgCount++;
                            }
                        }
                    }

                    foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):

                    $class = ($arColumn["id"] == "PRICE_FORMATED") ? "price" : "";

                    if (in_array($arColumn["id"], array("PROPS", "TYPE", "NOTES"))) // some values are not shown in columns in this template
                        continue;

                    if ($arColumn["id"] == "PREVIEW_PICTURE" && $bShowNameWithPicture)
                        continue;

                    $arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];

                    if ($arColumn["id"] == "NAME"):
                    $width = 70 - ($imgCount * 20);
                    ?>
                    <td class="item" style="width:<?/*=$width*/
                    ?>%">

                        <h2 class="bx_ordercart_itemtitle">
                            <?php
                            if (strlen($arItem["DETAIL_PAGE_URL"]) > 0) { ?><a
                                    href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?
                                }
                                ?>
                                <?= $arItem["NAME"] ?>
                                <?php
                                if (strlen($arItem["DETAIL_PAGE_URL"]) > 0): ?></a><?
                        endif;
                        ?>
                        </h2>

                        <div class="bx_ordercart_itemart">
                            <?
									if ($bPropsColumn) {
										foreach ($arItem["PROPS"] as $val) {
											echo $val["NAME"].":&nbsp;<span>".$val["VALUE"]."<span><br/>";
										}
									}
									?>
								</div>
								<?
								if (is_array($arItem["SKU_DATA"])):
									foreach ($arItem["SKU_DATA"] as $propId => $arProp) {

                                        // is image property
                                        $isImgProperty = false;
                                        foreach ($arProp["VALUES"] as $id => $arVal) {
                                            if (isset($arVal["PICT"]) && !empty($arVal["PICT"])) {
                                                $isImgProperty = true;
                                                break;
                                            }
                                        }

                                        $full = (count($arProp["VALUES"]) > 5) ? "full" : "";

                                        if ($isImgProperty): // iblock element relation property
                                            ?>
                                            <div class="bx_item_detail_scu_small_noadaptive <?= $full ?>">

												<span class="bx_item_section_name_gray">
													<?= $arProp["NAME"] ?>:
												</span>

                                                <div class="bx_scu_scroller_container">

                                                    <div class="bx_scu">
                                                        <ul id="prop_<?= $arProp["CODE"] ?>_<?= $arItem["ID"] ?>"
                                                            style="width: 200%;margin-left:0%;">
                                                            <?
                                                            foreach ($arProp["VALUES"] as $valueId => $arSkuValue) {

                                                                $selected = "";
                                                                foreach ($arItem["PROPS"] as $arItemProp) {
                                                                    if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"]) {
                                                                        if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
                                                                            $selected = "class=\"bx_active\"";
                                                                    }
                                                                }
                                                                ?>
                                                                <li style="width:10%;" <?= $selected ?>>
                                                                    <a href="javascript:void(0);">
                                                                        <span style="background-image:url(<?= $arSkuValue["PICT"]["SRC"] ?>)"></span>
                                                                    </a>
                                                                </li>
                                                                <?
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>

                                                    <div class="bx_slide_left"
                                                         onclick="leftScroll('<?= $arProp["CODE"] ?>', <?= $arItem["ID"] ?>);"></div>
                                                    <div class="bx_slide_right"
                                                         onclick="rightScroll('<?= $arProp["CODE"] ?>', <?= $arItem["ID"] ?>);"></div>
                                                </div>

                                            </div>
                                        <?
                                        else:
                                            ?>
                                            <div class="bx_item_detail_size_small_noadaptive <?= $full ?>">

												<span class="bx_item_section_name_gray">
													<?= $arProp["NAME"] ?>:
												</span>

                                                <div class="bx_size_scroller_container">
                                                    <div class="bx_size">
                                                        <ul id="prop_<?= $arProp["CODE"] ?>_<?= $arItem["ID"] ?>"
                                                            style="width: 200%; margin-left:0%;">
                                                            <?
                                                            foreach ($arProp["VALUES"] as $valueId => $arSkuValue) {

                                                                $selected = "";
                                                                foreach ($arItem["PROPS"] as $arItemProp) {
                                                                    if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"]) {
                                                                        if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
                                                                            $selected = "class=\"bx_active\"";
                                                                    }
                                                                }
                                                                ?>
                                                                <li style="width:10%;" <?= $selected ?>>
                                                                    <a href="javascript:void(0);"><?= $arSkuValue["NAME"] ?></a>
                                                                </li>
                                                                <?
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <div class="bx_slide_left"
                                                         onclick="leftScroll('<?= $arProp["CODE"] ?>', <?= $arItem["ID"] ?>);"></div>
                                                    <div class="bx_slide_right"
                                                         onclick="rightScroll('<?= $arProp["CODE"] ?>', <?= $arItem["ID"] ?>);"></div>
                                                </div>

                                            </div>
                                        <?
                                        endif;
                                    }
								endif;
								?>
							</td>
						<?
						elseif ($arColumn["id"] == "PRICE_FORMATED"):
						?>
							<td class="price right" id="td<?=$arItem['PRODUCT_ID']?>">
								<div class="current_price" 
									id="pr<?=$arItem['PRODUCT_ID']?>"><?=$arItem['PRICE_FORMATED']?></div>
								<div class="old_price right" name="old<?=$arItem['PRODUCT_ID']?>">
									<?
									//$arItem["DISCOUNT_PRICE"] = 20;
									if (doubleval($arItem['DISCOUNT_PRICE']) > 0):
										echo SaleFormatCurrency($arItem["PRICE"] + $arItem["DISCOUNT_PRICE"], $arItem["CURRENCY"]);
										$bUseDiscount = true;
									endif;
									?>
								</div>

								<?if ($bPriceType && strlen($arItem["NOTES"]) > 0):?>
									<div style="text-align: left">
										<div class="type_price"><?=GetMessage("SALE_TYPE")?></div>
										<div class="type_price_value"><?=$arItem["NOTES"]?></div>
									</div>
								<?endif;?>
							</td>
						<?
						elseif ($arColumn["id"] == "DISCOUNT"):
						?>
							<td class="custom right">
								<span><?=getColumnName($arColumn)?>:</span>
								<?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]?>
								<?// pre($arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]);?>
							</td>
						<?
						elseif ($arColumn["id"] == "DETAIL_PICTURE" && $bPreviewPicture):
						?>
							<td class="itemphoto">
								<div class="bx_ordercart_photo_container">
									<?
									$url = "";
									if ($arColumn["id"] == "DETAIL_PICTURE" && strlen($arData["data"]["DETAIL_PICTURE_SRC"]) > 0)
										$url = $arData["data"]["DETAIL_PICTURE_SRC"];

									if ($url == "")
										$url = $templateFolder."/images/no_photo.png";

									if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arData["data"]["DETAIL_PAGE_URL"] ?>"><?endif;?>
										<div class="bx_ordercart_photo" style="background-image:url('<?=$url?>')"></div>
									<?if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
								</div>
							</td>
						<?
						elseif (in_array($arColumn["id"], array("QUANTITY", "WEIGHT_FORMATED", "DISCOUNT_PRICE_PERCENT_FORMATED", "SUM"))):
						$id='';
						if($arColumn["id"] == 'DISCOUNT_PRICE_PERCENT_FORMATED') {
							$id='id="SK'.$arItem['PRODUCT_ID'].'"';
						}
						if($arColumn["id"] == 'SUM') {
							$id='id="SM'.$arItem['PRODUCT_ID'].'"';
						}
						
						?>
							<td class="<?=$arItem[$arColumn["id"]] == 0 ? '' : 'custom'?>" <?=$id?>>
								<span><?=getColumnName($arColumn)?></span>
								<?=$arItem[$arColumn["id"]] == 0 ? '' : $arItem[$arColumn["id"]]?>
							</td>
						<?
						else: // some property value

							if (is_array($arItem[$arColumn["id"]])):

								foreach ($arItem[$arColumn["id"]] as $arValues)
									if ($arValues["type"] == "image")
										$columnStyle = "width:20%";
							?>
							<td class="custom-1" style="<?=$columnStyle?>">
								<span><?=getColumnName($arColumn)?>:</span>
								<?
								foreach ($arItem[$arColumn["id"]] as $arValues):
									if ($arValues["type"] == "image"):
									?>
										<div class="bx_ordercart_photo_container">
											<div class="bx_ordercart_photo" style="background-image:url('<?=$arValues["value"]?>')"></div>
										</div>
									<?
									else: // not image
										echo $arValues["value"]."<br/>";
									endif;
								endforeach;
								?>
							</td>
							<?
							else: // not array, but simple value
							?>
							<td class="custom-2" style="<?=$columnStyle?>">
								<span><?=getColumnName($arColumn)?>:</span>
								<?
									echo $arItem[$arColumn["id"]];
								?>
							</td>
							<?
							endif;
						endif;

					endforeach;
					?>

				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
    <script>
        gtag('event', 'begin_checkout', {
            "items": [
                {
                    "id": "<?=$arData['data']['PRODUCT_ID']?>",
                    "name": "<?=$arItem['NAME']?>",
                    "list_name": "Search Results",
                    "brand": "Google",
                    "category": "Apparel/T-Shirts",
                    "list_position": 1,
                    "quantity": 0,
                    "price": '0.0'
                }
            ]
        });
    </script>
	<div class="bx_ordercart_order_pay">
		<div class="bx_ordercart_order_pay_right">
			<table class="bx_ordercart_order_sum">
				<tbody>
                    <tr class='total-costt'>
                        <td class="custom_t1 fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
                        <td class="custom_t2 fwb" class="price" id="totalOrder"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></td>
                    </tr>
				</tbody>
			</table>
			<div style="clear:both;"></div>

		</div>
		<div style="clear:both;"></div>
		<div class="bx_section">
			<h4><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?></h4>
			<textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:100%;min-height:120px"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
			<input type="hidden" name="" value="">
			<div style="clear: both;"></div><br />
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
        $("#tableOrd a.mc-button.delete.main").on('click', function () {
            let data = $(this).data('product-id')
            $.ajax({
                type: "POST",
                url: "/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/ajax/add2basket_ajax.php",
                data: {
                    PRODUCT_ID: data,
                    action: "deleteProdFromCart",
                },
                success: function(msg) {
                    let resp = JSON.parse(msg)
                    if (resp.code == 1) {
                        BX.onCustomEvent('OnBasketChange');
                        $("#tableOrd tr[id=" + data + "]").remove()
                        $("#totalOrder, #courier-cart-sum").html(resp.summFormatted);
                        $("#popup-message").remove();
			$(".delivery-variants li.checked").click()
                        if (resp.summ == 0) {
                            $("#ORDER_FORM").remove()
                        }
                    }
                }
            });
            return false;
        })
    })
</script>