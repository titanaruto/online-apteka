<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?> <?if ($APPLICATION->GetCurPage(true) == SITE_DIR."index.php"):?> <?/*<div class="sidebar_banner">
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
			<img src='<?=SITE_TEMPLATE_PATH?>/images/sidebar_banner_2.png' alt="" />
			<div class="hide_show_item">
				<span href="">Хотите рекламу тут?</span>
				<span>тел: 063-636-36-63</span>	
			</div>
		</a>
	</div>
</div>
*/?>
<div class="bx-sidebar-block">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"visual",
	Array(
		"CATEGORY_0" => array(0=>"iblock_catalog",),
		"CATEGORY_0_TITLE" => "Товары",
		"CATEGORY_0_iblock_catalog" => array(0=>"all",),
		"CATEGORY_OTHERS_TITLE" => "Прочее",
		"CHECK_DATES" => "N",
		"CONTAINER_ID" => "search",
		"CONVERT_CURRENCY" => "Y",
		"INPUT_ID" => "title-search-input",
		"NUM_CATEGORIES" => "1",
		"PAGE" => SITE_DIR."catalog/",
		"PREVIEW_HEIGHT" => "75",
		"PREVIEW_WIDTH" => "75",
		"PRICE_CODE" => array(0=>"BASE",),
		"SHOW_INPUT" => "Y",
		"SHOW_OTHERS" => "N",
		"SHOW_PREVIEW" => "Y",
		"TOP_COUNT" => "5"
	)
);?>
</div>
 <?endif?>
<div class="bx-sidebar-block">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_RECURSIVE" => "N",
		"AREA_FILE_SHOW" => "file",
		"EDIT_MODE" => "html",
		"PATH" => SITE_DIR."include/socnet_sidebar.php"
	),
false,
Array(
	'HIDE_ICONS' => 'Y'
)
);?>
</div>
<div class="bx-sidebar-block hidden-xs">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_RECURSIVE" => "N",
		"AREA_FILE_SHOW" => "file",
		"EDIT_MODE" => "html",
		"PATH" => SITE_DIR."include/sender.php"
	),
false,
Array(
	'HIDE_ICONS' => 'Y'
)
);?>
</div>
<div class="bx-sidebar-block">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_RECURSIVE" => "N",
		"AREA_FILE_SHOW" => "file",
		"EDIT_MODE" => "html",
		"PATH" => SITE_DIR."include/about.php"
	),
false,
Array(
	'HIDE_ICONS' => 'N'
)
);?>
</div>
<div class="bx-sidebar-block">
	 <?/*$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/twitter.php",
			"AREA_FILE_RECURSIVE" => "N",
			"EDIT_MODE" => "html",
		),
		false,
		Array('HIDE_ICONS' => 'N')
	);*/?>
</div>
<br>