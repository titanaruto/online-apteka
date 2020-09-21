<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/apt_location.php");
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");

$style = (is_array($arResult["ORDER_PROP"]["RELATED"]) && count($arResult["ORDER_PROP"]["RELATED"])) ? "" : "display:none";

//pre($arResult["ORDER_PROP"]["RELATED"]);
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/apt_location.php");
?>
<div class="bx_section" style="<?=$style?>" id="relPrps">
	<h4><?=GetMessage("SOA_TEMPL_RELATED_PROPS")?></h4>
	<?
	
	$chkProps = array();
	$chkProps = $arResult["ORDER_PROP"]["RELATED"];
	foreach($chkProps as $key=>$props){
		if($props['ID'] == 7 || $props['ID'] == 5){
			//$APT_LOCATION = $props;
			unset($arResult["ORDER_PROP"]["RELATED"][$key]);
		}
	}
	?><input type="text" maxlength="250" size="" value="" name="ORDER_PROP_7" id="ORDER_PROP_7" style="display:none"><?
	?><input type="text" maxlength="250" size="" value="" name="ORDER_PROP_5" id="ORDER_PROP_5" style="display:none"><?
	/*
	?><div data-property-id-row="7">
		<div class="bx_block r1x3 pt8">Место получения<span class="bx_sof_req">*</span></div>
		<div class="bx_block r3x1">
			<input type="text" maxlength="250" size="" value="" name="ORDER_PROP_7" id="ORDER_PROP_7" >
		</div>
		<div style="clear: both;"></div>
		<br>
	</div><?
	*/
	
//	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/apt_location.php");
	
	//pre($arResult["ORDER_PROP"]["RELATED"]);
	?>
	<br />
	<?=PrintPropsForm($arResult["ORDER_PROP"]["RELATED"], $arParams["TEMPLATE_LOCATION"])?>
</div>