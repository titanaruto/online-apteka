<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
?>
<div class="subscribe-form"  id="subscribe-form">
<?
$frame = $this->createFrame("subscribe-form", false)->begin();
?>
	<div class="col-lg-12">
		<form id="formSub" action="<?=$arResult["FORM_ACTION"]?>">
			<div class="subscrible-title">Хотите всегда первым узнавать о наших</div>
			<div class="small_subscrible-title">акциях и скидках до -50%?</div>
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<div class="col-lg-4 col-md-4 col-sm-4 text-center hidden-xs">
				<input class="subscrable-input" type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo ""?> /> <?=$itemValue["NAME"]?>
				<label class="subscrable-label" for="sf_RUB_ID_<?=$itemValue["ID"]?>"></label>
			</div>
			<?endforeach;?>
			<div class="col-lg-4 col-md-4 col-sm-4 text-center hidden-xs">
				<input class="subscrible_email" type="email"  required="" placeholder="Электронная почта" name="sf_EMAIL" size="20" value="<?=$arResult["EMAIL"]?>" title="<?=GetMessage("subscr_form_email_title")?>" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 text-center hidden-xs">
				<input class="submit" type="submit" name="OK" value="<?=GetMessage("subscr_form_button")?>" />
			</div>
		</form>
	</div>
<?
$frame->beginStub();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>">
	
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<label for="sf_RUB_ID_<?=$itemValue["ID"]?>">
				<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>" /> <?=$itemValue["NAME"]?>
			</label>
		<?endforeach;?>

		<div>
			<div>
				<input type="text" name="sf_EMAIL" size="20" value="" title="<?=GetMessage("subscr_form_email_title")?>" /></div>
			</div>
			<div>
				<div><input type="submit" name="OK" value="<?=GetMessage("subscr_form_button")?>" /></div>
			</div>
		</div>
	</form>
<?
$frame->end();
?>
</div>
