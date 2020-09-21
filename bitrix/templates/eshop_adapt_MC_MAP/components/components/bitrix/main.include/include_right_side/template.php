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
//pre($_SESSION['RELATIVE_BLOG']);

if(true === isset($_SESSION['RELATIVE_BLOG']['ID']) 
		&& $_SESSION['RELATIVE_BLOG']['DETAIL']==='Y') {
	$VALUES = array();
	$res = CIBlockElement::GetProperty(
			$_SESSION['RELATIVE_BLOG']['IBLOCK_ID'],
			$_SESSION['RELATIVE_BLOG']['ID'], 
			"sort", 
			"asc", 
			array("CODE" => "RELATED_GOODS"));
	while ($ob = $res->GetNext()) {
		$VALUES[] = $ob['VALUE'];
	}
	//pre($VALUES);
	
	$arFilter = Array("IBLOCK_ID"=>IntVal(2),"ACTIVE"=>"Y","ID"=>$VALUES);
	$res = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter);
	while($ar_fields = $res->GetNext())
	{?>
	<div>
	
	<div><?
	echo CFile::ShowImage(
			$ar_fields[DETAIL_PICTURE], 
			200, 
			200,
			"border=0", 
			$ar_fields['DETAIL_PAGE_URL']
	);?></div>
	<p><a href="<?=$ar_fields['DETAIL_PAGE_URL']?>"><?=$ar_fields['NAME']?></a></p>
	</div>
	<?
	}
} else {
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
<?}
unset($_SESSION['RELATIVE_BLOG']);
