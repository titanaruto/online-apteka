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
			<h1 class="subscrible-title">Хотите всегда первым узнавать о наших</h1>
			<h2 class="small_subscrible-title">акциях и скидках до -50%?</h2>
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<div class="col-lg-4 col-md-4 col-sm-4 text-center hidden-xs">
				<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo ""?> /> <?=$itemValue["NAME"]?>
				<label for="sf_RUB_ID_<?=$itemValue["ID"]?>"></label>
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

<style>
[type="checkbox"]:not(:checked),
[type="checkbox"]:checked {
  position: absolute;
  left: -9999px;
}
[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label {
  position: relative;
  /*padding-left: 25px;*/
  cursor: pointer;display: block;
  float: left;
}

/* checkbox aspect */
[type="checkbox"]:not(:checked) + label:before,
[type="checkbox"]:checked + label:before {
  content: '';
  position: absolute;
  left:0;
  top: 2px;
  width: 17px;
  height: 17px;
  border: 1px solid #aaa;
  background: #fff;
  border-radius: 3px;
  /*box-shadow: inset 0 1px 3px rgba(0,0,0,.3)*/
}
/* checked mark aspect */
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  content: '✔';
  position: absolute;
  top: 0;
  left: 4px;
  font-size: 14px;
  color: red;
  transition: all .2s;
}
/* checked mark aspect changes */
[type="checkbox"]:not(:checked) + label:after {
  opacity: 0;
  transform: scale(0);
}
[type="checkbox"]:checked + label:after {
  opacity: 1;
  transform: scale(1);
}

/* disabled checkbox */
[type="checkbox"]:disabled:not(:checked) + label:before,
[type="checkbox"]:disabled:checked + label:before {
  box-shadow: none;
  border-color: #bbb;
  background-color: #ddd;
}

[type="checkbox"]:disabled:checked + label:after {
  color: #999;
}
[type="checkbox"]:disabled + label {
  color: #aaa;
}

/* accessibility */
[type="checkbox"]:checked:focus + label:before,
[type="checkbox"]:not(:checked):focus + label:before {
  border: 1px dotted blue;
}

/* hover style just for information
label:hover:before {
  border: 1px solid #4778d9!important;
} */





</style>
