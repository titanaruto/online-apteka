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

//pre($arResult);

?>
<?if ($curPage != SITE_DIR."index.php"):?>
	<div class="row" id="transparent">
		<div class="col-lg-12">
			<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(
				"START_FROM" => "0",
				"PATH" => "",
				"SITE_ID" => "-",
				),
				false,
				Array('HIDE_ICONS' => 'N')
			);?>
		</div>
	</div><?
endif;

//pre($arResult['SECTION']['DEPTH_LEVEL']);

	if(!isset($_GET['all']) && $arResult['SECTION']['DEPTH_LEVEL'] > 0 && $arResult['SECTION']['DEPTH_LEVEL'] < 3){
			echo '<a class="back-top" href="'.$_SERVER['REDIRECT_URL'].'/?all=Y">Показать все товары</a>';
	}
	if(isset($_GET['all']) && $arResult['SECTION']['DEPTH_LEVEL'] > 0 && $arResult['SECTION']['DEPTH_LEVEL'] < 3){
			echo '<h2>Все товары в категории '.$arResult['SECTION']['NAME'].'</h2>';
			echo '<a class="back-top" href="'.$_SERVER['REDIRECT_URL'].'/">Вернуться в каталог</a>';
	}
	if((!isset($_GET['all']) && $arResult['SECTION']['DEPTH_LEVEL'] > 0 && $arResult['SECTION']['DEPTH_LEVEL'] < 3 )
		||
		$arResult['SECTION']['DEPTH_LEVEL'] ==0
	){
		?><div class="<? echo $arCurView['CONT']; ?>"><?
		if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID']){
			$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
			$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

			?><h1
				class="<? echo $arCurView['TITLE']; ?>"
				id="<? echo $this->GetEditAreaId($arResult['SECTION']['ID']); ?>"
			><a href="<? echo $arResult['SECTION']['SECTION_PAGE_URL']; ?>"><?
				echo (
					isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
					? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
					: $arResult['SECTION']['NAME']
				);
			?></a></h1><?
		}
		if (0 < $arResult["SECTIONS_COUNT"]){
		?><ul id="222" class="<? echo $arCurView['LIST']; ?>"><?
			switch ($arParams['VIEW_MODE'])
			{
				case 'LINE':
					foreach ($arResult['SECTIONS'] as &$arSection)
					{
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
						?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
							<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>" class="bx_catalog_line_img" style="background-image: url('<? echo $arSection['PICTURE']['SRC']; ?>');" title="<? echo $arSection['PICTURE']['TITLE']; ?>"></a>
							<h2 class="bx_catalog_line_title">
								<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
								if ($arParams["COUNT_ELEMENTS"])
								{
									?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
								}
								?>
							</h2>
								<div></div>
								<span></span>
								<p>lorem</p>
							<?
								if ('' != $arSection['DESCRIPTION'])
								{
									?><p class="bx_catalog_line_description"><? echo $arSection['DESCRIPTION']; ?></p><?
								}
								?><div style="clear: both;"></div>
								
						</li><?
					}
					unset($arSection);
					break;
				case 'TEXT':
					foreach ($arResult['SECTIONS'] as &$arSection)
					{
						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

						?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>"><h2 class="bx_catalog_text_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
						if ($arParams["COUNT_ELEMENTS"])
						{
							?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
						}
						?></h2>
								
						</li><?
					}
					unset($arSection);
					break;
				case 'TILE':
					foreach ($arResult['SECTIONS'] as &$arSection)
					{
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
							);
						?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
							<a
							<?
							//pre($arSection);
							//if(!empty($arSection['PICTURE']['SRC']))
							?>
								href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
								class="bx_catalog_tile_img"
								style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>');"
								title="<? echo $arSection['PICTURE']['TITLE']; ?>"
								> 
								
								<div class="overlay_list"></div>
								<span>Перейти в категорию</span>
								<div class="arror_section_list"></div>	
								
								
							</a><?
							if ('Y' != $arParams['HIDE_SECTION_NAME'])
							{
								?><h2 class="bx_catalog_tile_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
								if ($arParams["COUNT_ELEMENTS"])
								{
									?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
								}
							?></h2><?
							}
							?>
							
						</li><?
					}
					unset($arSection);
					break;
				case 'LIST':
					$intCurrentDepth = 1;
					$boolFirst = true;
					foreach ($arResult['SECTIONS'] as &$arSection)
					{
						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

						if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
						{
							if (0 < $intCurrentDepth)
								echo "\n",str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']),'<ul>';
						}
						elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
						{
							if (!$boolFirst)
								echo '</li>';
						}
						else
						{
							while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
							{
								echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
								$intCurrentDepth--;
							}
							echo str_repeat("\t", $intCurrentDepth-1),'</li>';
						}

						echo (!$boolFirst ? "\n" : ''),str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);
						?><li id="<?=$this->GetEditAreaId($arSection['ID']);?>"><h2 class="bx_sitemap_li_title"><a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>"><? echo $arSection["NAME"];?><?
						if ($arParams["COUNT_ELEMENTS"])
						{
							?> <span>(<? echo $arSection["ELEMENT_CNT"]; ?>)</span><?
						}
						?></a></h2><?

						$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
						$boolFirst = false;
					}
					unset($arSection);
					while ($intCurrentDepth > 1)
					{
						echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
						$intCurrentDepth--;
					}
					if ($intCurrentDepth > 0)
					{
						echo '</li>',"\n";
					}
					break;
			}
		?>
		</ul>
		<?
			echo ('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');
		}
		?></div><?
	}
