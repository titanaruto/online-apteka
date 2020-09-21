<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
/*echo $arResult["FILE"];

if($arResult["FILE"] <> '')
	include($arResult["FILE"]);*/
?>
<div class="sidebar_banner">
	<div class="images_wrap">
		<a href="">
			<img src='<?=SITE_TEMPLATE_PATH?>/images/sidebar_banner.png' alt="" />
			<div class="hide_show_item">
				<span href="">Хотите рекламу тут?</span>
				<span>тел: 063-636-36-63</span>
			</div>
		</a>
	</div>
	
	<div class="images_wrap">
		<a href="">
			<img src='<?=SITE_TEMPLATE_PATH;?>/images/sidebar_banner_2.png' alt="" />
			<div class="hide_show_item">
				<span href="">Хотите рекламу тут?</span>
				<span>тел: 063-636-36-63</span>
			</div>
		</a>
	</div>
</div>
