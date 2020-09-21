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
$this->setFrameMode(true);
//pre($arResult);
$_SESSION['RELATIVE_BLOG']['IBLOCK_ID']=$arResult['IBLOCK_ID'];
$_SESSION['RELATIVE_BLOG']['ID']=$arResult['ID'];
$_SESSION['RELATIVE_BLOG']['DETAIL']='Y';
$_SESSION['RELATIVE_BLOG']['IBLOCK_SECTION_ID']=$arResult['IBLOCK_SECTION_ID'];
$_SESSION['RELATIVE_BLOG']['SECTION']='N';
$_SESSION['RELATIVE_BLOG']['DEPTH_LEVEL']='3';

$this->SetViewTarget("blogs");
?><div>
	<div><?
		echo CFile::ShowImage(
				$arResult[DETAIL_PICTURE], 
				200, 
				200,
				"border=0", false 
		);
	?></div>
	<p><?=$arResult['NAME']?></p>
	<p><?=$arResult['DETAIL_TEXT']?></p>
	<?//pre($arResult);?>
</div>

<?
$this->EndViewTarget("blogs");