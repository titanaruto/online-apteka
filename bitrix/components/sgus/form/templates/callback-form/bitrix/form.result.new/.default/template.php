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
<table id="prescription_for_a_doctor_table" class="">
<!--	<thead>-->
<!--		<tr>-->
<!--			<th colspan="2">&nbsp;</th>-->
<!--		</tr>-->
<!--	</thead>-->
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
<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>