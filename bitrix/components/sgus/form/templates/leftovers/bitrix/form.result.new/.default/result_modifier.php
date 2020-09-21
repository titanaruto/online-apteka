<?php
if (!empty($arResult["FORM_NOTE"]))
    $arResult["FORM_NOTE"] = getMessage('FORM_SUCCESS_ANSWER');

$arResult["QUESTIONS"]["address"]["HTML_CODE"] = '<input class="inputtext" name="form_text_30" value="" size="0" type="text" readonly>';
$arResult["QUESTIONS"]["name"]["HTML_CODE"] = '<input class="inputtext" name="form_text_31" value="" size="0" type="text" readonly>';
$arResult["QUESTIONS"]["price"]["HTML_CODE"] = '<input class="inputtext" name="form_text_32" value="" size="0" type="text" readonly>';
$arResult["QUESTIONS"]["qty"]["HTML_CODE"] = '<input class="inputtext" name="form_text_33" value="" size="0" type="text" readonly>';
$arResult["QUESTIONS"]["amount"]["HTML_CODE"] = '<input class="inputtext" name="form_text_35" value="" size="0" type="text" readonly>';
$arResult["QUESTIONS"]["reserve_qty"]["HTML_CODE"] = '<span id="leftovers_qty_minus">-</span><input style="width: 10%" class="inputtext" name="form_text_34" value="" size="0" type="text" readonly><span id="leftovers_qty_plus">+</span>';