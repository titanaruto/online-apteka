<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>

<?=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y")
{
?>
<?=$arResult["FORM_HEADER"]?>

<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
    <style>
        input[name=form_text_37],
        input[name=form_text_38] {
            display: none;
        }

        .leftovers_popup_table th {
            padding-bottom: 20px;
        }

        .leftovers_popup_table {
            width: 100%;
        }

        .leftovers_popup_table input.inputtext {
            margin-bottom: 10px;
            margin-top: 5px;
            width: 100%;
            padding: 3px;
            font-size: 14px!important;
            color: #000!important;
        }

        .leftovers_popup_table input[type=submit] {
            font-weight: bold!important;
        }

        #leftovers_qty_minus,
        #leftovers_qty_plus {
            font-size: 22px;
            border: 1px solid #a9a9a9;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: -webkit-inline-flex;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-top: 5px!important;
        }

        #leftovers_qty_minus {
            margin-right: 5px;
        }

        #leftovers_qty_plus {
            margin-left: 5px;
        }

        input[name=form_text_34] {
            text-align: center;
            font-weight: bold;
        }
    </style>
<table id="" class="leftovers_popup_table">
	<thead>
		<tr>
			<th colspan="2"><img alt="Онлайн аптека" src="https://online-apteka.com.ua/include/mc-logo.png"></th>
		</tr>
	</thead>
	<tbody>
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	{
	?>
		<tr>
			<td>
                <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                    <span class="error-fld" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></span>
                <?endif;?>
                <?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><?=$arResult["REQUIRED_SIGN"];?><?endif;?>
                <?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?><br />
                <?=$arQuestion["HTML_CODE"]?>
            </td>
		</tr>
	<?
	} //endwhile
	?>
	</tbody>
	<tfoot>
		<tr>
			<th>
                <label for="feedback-submit" class="mc-button letter">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    <input id="" type="submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" name="web_form_submit" class="">
                </label>
<!--                <button type="submit" name="web_form_submit" class="mc-button phone request_a_call"><i class="fa fa-phone"></i>--><?//=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?><!--</button>-->
<!--                <input --><?//=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?><!-- type="submit" name="web_form_submit" value="--><?//=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?><!--" />-->
			</th>
		</tr>
	</tfoot>
</table>
    <script>
        $(document).ready(function () {
            function recalcAmount (qty) {
                var price = $("input[name=form_text_32]").val()
                $("input[name=form_text_35]").val((qty * price).toFixed(2))
            }
            $("input[name=form_text_30]").val($("#ajax-add-answer").attr("pharmacy"))
            $("input[name=form_text_31]").val($("#ajax-add-answer").attr("product"))
            $("input[name=form_text_32]").val($("#ajax-add-answer").attr("price"))
            $("input[name=form_text_33]").val($("#ajax-add-answer").attr("qty"))
            $("input[name=form_text_34]").val(1)
            $("input[name=form_text_35]").val($("#ajax-add-answer").attr("price"))
            $("input[name=form_text_36]").attr("placeholder", "+38 (___) ___-__-__").mask("+38 (999) 999-99-99")
            $("input[name=form_text_37]").val($("#ajax-add-answer").attr("productId"))
            $("input[name=form_text_38]").val($("#ajax-add-answer").attr("pharmId"))
            $("#leftovers_qty_minus").on('click', function () {
                var leftValue = parseInt($(this).next().val())
                if (parseInt($(this).next().val()) <= 1)
                    return false
                $(this).next().val(parseInt(leftValue - 1))
                recalcAmount ($(this).next().val())
            })

            $("#leftovers_qty_plus").on('click', function () {
                var rightValue = parseInt($(this).prev().val())
                if (parseInt($(this).prev().val()) >= parseInt($("input[name=form_text_33]").val()))
                    return false
                $(this).prev().val(parseInt(rightValue + 1))
                recalcAmount ($(this).prev().val())
            })
        })
    </script>
<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>