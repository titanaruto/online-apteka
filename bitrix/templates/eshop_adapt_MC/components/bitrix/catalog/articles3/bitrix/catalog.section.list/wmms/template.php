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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'bx_sitemap_ul',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>
<?if ($curPage != SITE_DIR."index.php"):?>
	<div class="row" id="transparent">
		<div class="col-lg-12">
			<?/*$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(
				"START_FROM" => "0",
				"PATH" => "",
				"SITE_ID" => "-",
				),
				false,
				Array('HIDE_ICONS' => 'N')
			);*/?>
		</div>
	</div><?
endif;

$get_fields = CIBlockSection::GetList(
    array(),
    array(
        'IBLOCK_ID' => $arResult["SECTION"]['IBLOCK_ID'],
        'ID' => $arResult["SECTION"]['ID']
    ),
    false,
    array(
        'UF_*'
    )
);

if($get_fields_item = $get_fields->GetNext()) {
    $APPLICATION->SetPageProperty("description", $get_fields_item['UF_META_DESC']);
    $APPLICATION->SetPageProperty("title", $get_fields_item['UF_META_TITLE']);
}

$CIBlockSection = new CIBlockSection;
$arFilter = array('IBLOCK_ID' => 5,'GLOBAL_ACTIVE'=>'Y');
$arSelect = array('IBLOCK_ID','ID','NAME','IBLOCK_SECTION_ID','ACTIVE','LEFT_MARGIN',
					'RIGHT_MARGIN','DEPTH_LEVEL','SECTION_PAGE_URL');
$rsSections = $CIBlockSection->GetList(array('NAME' => 'ASC'), $arFilter,false,$arSelect);
$firstLevelMenu = array();
$secondLevelMenu = array();
while ($arSection = $rsSections->Fetch()){
	//pre($arSection);
	$arSections[] = $arSection;
	if($arSection['DEPTH_LEVEL']==1){
		if($arResult['SECTION']['ID'] == $arSection['ID']){
			$firstAtrLev = $arSection['ID'];
		}
		$firstLevelMenu[] = $arSection;
	}
	if($arSection['DEPTH_LEVEL']==2){
		if($arResult['SECTION']['ID'] == $arSection['ID']){
			$firstAtrLev = $arSection['IBLOCK_SECTION_ID'];
		}
		$secondLevelMenu[$arSection['IBLOCK_SECTION_ID']][] = $arSection;
	}
}
// словить активную секцию
// если второй уровень, то еще добавить актиыную первого.
// ввести две переменные активности для каждого уровня

//pre($arResult);
/*foreach($arResult['SECTIONS'] as $sectionArt){
	pre($sectionArt);
}*/



?>
<style>

</style>
<div class="sidebar col-lg-4 hidden-xs article-sidebar-left" id="artical_menu">
	<ul class="main-menu"><?
		foreach($firstLevelMenu as $flevel){
			if($flevel['ID'] != $firstAtrLev ){?>
				<li class="not-active">
					<a class="top-level" href="/articles/<?=$flevel['CODE'];?>/"><?=$flevel['NAME'];?></a>
					<ul class="submenu">
						<?foreach($secondLevelMenu[$flevel['ID']] as $secondL){?>
							<li><a href="/articles/<?=$secondL['CODE'];?>/"><?=$secondL['NAME'];?></a></li>
						<?}?>
					</ul>
				</li>
			<?} else {?>
				<li class="active" >
					<a href="/articles/<?=$flevel['CODE'];?>/"><?=$flevel['NAME'];?></a>
					<ul class="submenu">
						<?foreach($secondLevelMenu[$flevel['ID']] as $secondL){
							if($arResult['SECTION']['ID'] != $secondL['ID']){?>
								<li><a href="/articles/<?=$secondL['CODE'];?>/"><?=$secondL['NAME'];?></a></li>
							<?} else {?>
								<li calss="active_to">
									<a href="/articles/<?=$secondL['CODE'];?>/"  style="color: red;"><?=$secondL['NAME'];?></a>
								</li>
							<?}
						}?>
					</ul>
				</li>
			<?}
		}?>
	</ul><!-- main-menu -->
</div><!-- sidebar artical_menu -->

<div class="col-lg-8" id="artical_content">
	<h1 class="artical_content_title"><?echo $arResult['SECTION']['NAME']; ?></h1>
	<div class="artical_content_text"><?echo$arResult['SECTION']['DESCRIPTION'];?></div>
	<?if((!isset($_GET['all']) && $arResult['SECTION']['DEPTH_LEVEL'] > 0 && $arResult['SECTION']['DEPTH_LEVEL'] < 3 ) 	|| 	$arResult['SECTION']['DEPTH_LEVEL'] ==0	){?>
		<div class="<? echo $arCurView['CONT']; ?>">
			<?if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID']){
				$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);?>
				<h1 class="<? echo $arCurView['TITLE']; ?>" id="<? echo $this->GetEditAreaId($arResult['SECTION']['ID']); ?>">
					<a href="<? echo $arResult['SECTION']['SECTION_PAGE_URL']; ?>">
						<?	echo (
							isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
							? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
							: $arResult['SECTION']['NAME']
						);?>
					</a>
				</h1>
			<?}
			if (0 < $arResult["SECTIONS_COUNT"]){?>
				<ul id="333" class="<? echo $arCurView['LIST']; ?>">
				<?switch ($arParams['VIEW_MODE']){
					case 'LINE':
						foreach ($arResult['SECTIONS'] as &$arSection){
							$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
							$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
							if (false === $arSection['PICTURE'])
								$arSection['PICTURE'] = array(
									'SRC' => $arCurView['EMPTY_IMG'],
									'ALT' => (
										'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
										? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
										: $arSection["NAME"]
									),
									'TITLE' => (
										'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
										? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
										: $arSection["NAME"]
									)
								);
							?>
							<li class="list-wmms" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
								<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>" class="bx_catalog_line_img" style="background-image: url('<? echo $arSection['PICTURE']['SRC']; ?>');" title="<? echo $arSection['PICTURE']['TITLE']; ?>"></a>
								<h2 class="bx_catalog_line_title">
									<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
										if ($arParams["COUNT_ELEMENTS"]){?>
											<span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span>
										<?}?>
								</h2>
								<div></div>
								<span></span>
								<p>lorem</p>
								<?if ('' != $arSection['DESCRIPTION']){?>
									<p class="bx_catalog_line_description"><? echo $arSection['DESCRIPTION']; ?></p>
									<?}?>
									<div style="clear: both;"></div>
							</li>
						<?}
						unset($arSection);
						break;
					case 'TEXT':
						foreach ($arResult['SECTIONS'] as &$arSection){
							$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
							$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);?>

							<li class="list-wmms" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
								<h2 class="bx_catalog_text_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
									if ($arParams["COUNT_ELEMENTS"]){?>
										<span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span>
									<?}?>
								</h2>

							</li>
						<?}
						unset($arSection);
						break;
					case 'TILE':
						foreach ($arResult['SECTIONS'] as &$arSection){
							//pre($arSection);
							$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
							$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

							if (false === $arSection['PICTURE'])
								$arSection['PICTURE'] = array(
									'SRC' => $arCurView['EMPTY_IMG'],
									'ALT' => (
										'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
										? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
										: $arSection["NAME"]
									),
									'TITLE' => (
										'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
										? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
										: $arSection["NAME"]
									)
								);?>
							<li class="list-wmms" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
								<a 	href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
									class="bx_catalog_tile_img"
									style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>');"
									title="<? echo $arSection['PICTURE']['TITLE']; ?>">
								</a>
								<?if ('Y' != $arParams['HIDE_SECTION_NAME']){?>
									<h2 class="bx_catalog_tile_title">
										<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a>
										<?if ($arParams["COUNT_ELEMENTS"]){?>
											<span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span>
										<?}?>
									</h2>
								<?}?>
							</li>
						<?}
						unset($arSection);
						break;
					case 'LIST':
						$intCurrentDepth = 1;
						$boolFirst = true;
						foreach ($arResult['SECTIONS'] as &$arSection){
							$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
							$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

							if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])	{
								if (0 < $intCurrentDepth)
									echo "\n",str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']),'<ul>';
							}
							elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])	{
								if (!$boolFirst)
									echo '</li>';
							}
							else{
								while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])	{
									echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
									$intCurrentDepth--;
								}
								echo str_repeat("\t", $intCurrentDepth-1),'</li>';
							}

							echo (!$boolFirst ? "\n" : ''),str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);
							?>
							<li id="<?=$this->GetEditAreaId($arSection['ID']);?>">
								<h2 class="bx_sitemap_li_title">
									<a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>"><? echo $arSection["NAME"];?><?
										if ($arParams["COUNT_ELEMENTS"]){
											?> <span>(<? echo $arSection["ELEMENT_CNT"]; ?>)</span>
										<?}?>
									</a>
								</h2><?

							$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
							$boolFirst = false;
						}
						unset($arSection);
						while ($intCurrentDepth > 1){
							echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
							$intCurrentDepth--;
						}
						if ($intCurrentDepth > 0) {
							echo '</li>',"\n";
						}
						break;
				}?>
				</ul><!-- end ul "333" -->
				<?
				echo ('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');
			}?>
		</div><?
	}
?>
</div><!-- этот закрывающий был потерян -->
