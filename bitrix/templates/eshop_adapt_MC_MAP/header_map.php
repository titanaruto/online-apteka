<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
$wizTemplateId = COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID);
CJSCore::Init(array("fx"));
$curPage = $APPLICATION->GetCurPage(true);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
	
	
	<?$APPLICATION->ShowHead();?>
	
	<?	$APPLICATION->SetAdditionalCSS("/bitrix/css/main/bootstrap.css");?>
	<? $APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/colors.css");
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/my_style.css");	
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/template_styles.css");
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js"></script>
    <script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
 	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,300,700' rel='stylesheet' type='text/css'>
	<title><?$APPLICATION->ShowTitle()?></title>	

	<div id="panel"><?$APPLICATION->ShowPanel();?></div>
	<?$APPLICATION->IncludeComponent("bitrix:eshop.banner", "", array());?>
	<script>
		var map, infoBubble, infoBubble2;
		function init() {
			  var mapCenter = new google.maps.LatLng(50.401699,30.252512);
			  map = new google.maps.Map(document.getElementById('map'), {
			  zoom: 6,
			  center: mapCenter,
			  mapTypeId: google.maps.MapTypeId.ROADMAP,
			  draggable: true
			});
			  var marker = new google.maps.Marker({
			  map: map,
			  position: new google.maps.LatLng(50.401699,30.252512),
			  draggable: false
			}); 
		}
		google.maps.event.addDomListener(window, 'load', init);
	</script>


</head>

<body>


<div class="bx-wrapper" id="bx_eshop_wrap">
	<div style="width: 100%; height: 805px;" id="map"></div>
	<style>
		#map {
		  width: 291px;
		  height: 505px;
		  display: block;
		  margin: 0 auto;
		}
	</style>
	<header class="bx-header" itemscope itemtype="http://schema.org/Organization">
		<div class="bx-header-section container">
			<div class="row" id="first_row">
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
				<img src="" alt="" />
					<ul class="nav nav-pills">
					  <li><a href="/oplata-dostavka/">Оплата и доставка</a></li>
					  <li><a href="/garantiya_kachestva/">Гарантия качества</a></li>
					  <li><a href="/pomosh/">Помощь</a></li>
					  <li><a href="/contact/">Контакты</a></li>
					</ul>
					<div class="row" id="mr_top">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
								<div class="bx-logo">
									<a class="bx-logo-block hidden-xs" href="<?=SITE_DIR?>">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo.php"), false);?>
									</a>
									<a class="bx-logo-block hidden-lg hidden-md hidden-sm text-center" href="<?=SITE_DIR?>">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo_mobile.php"), false);?>
									</a>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-5 col-xs-4">
								<!--div class="bx-inc-orginfo">
									<div>
										<span class="bx-inc-orginfo-phone"><i class="fa fa-phone"></i> <?/*$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/telephone.php"), false);*/?></span>
									</div>
								</div-->
								<div class="request">
									
									<div class="number">
										<span class="bold">0 800 50 52 53</span>
									</div>	
									<button id="Request_a_call">Заказать звонок</button>
									<div class="overlay"></div>
									<div class="feedback">
										<span class="closes">X</span>
										<?$APPLICATION->IncludeComponent(
											"bitrix:main.feedback",
											".default",
											Array(
												"USE_CAPTCHA" => "Y",
												"OK_TEXT" => "Спасибо, ваше сообщение принято.",
												"EMAIL_TO" => "",
												"AJAX_MODE" => "Y",
												"REQUIRED_FIELDS" => array(
												),
												"EVENT_MESSAGE_ID" => array(
												)
											),
											false
										);?>
									</div>
									<script>
										//$('#Request_a_call').click(function() {
											
										//	$('.feedback').show();
										//	$('.overlay').show();
										//})
										//$('.feedback .close, .overlay').click(function() {
										//	$('.overlay, .feedback,').css('display: none;');
										//})
									</script>
									
									<script type="text/javascript">
										$(document).ready(function(){
												$('.feedback .closes, .overlay').click(function (){
												$('.feedback, .overlay').css('opacity','0');
												$('.feedback, .overlay').css('visibility','hidden');
											});
												$('#Request_a_call').click(function (){
												$('.overlay, .feedback').css('opacity','1');
												$('.overlay, .feedback').css('visibility','visible');
										
											});
										});
									</script>
								</div>
							</div>
							
							<div class="col-lg-6 col-md-5 col-sm-4 hidden-xs">
								<?/*время и дата работы*/?>
								<!--div class="bx-worktime">
									<div class="bx-worktime-prop">
										<?/*$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule.php"), false);*/?>
									</div>
								</div-->
								<?/*поиск*/?>
								<div class="bx-sidebar-block">
									<?$APPLICATION->IncludeComponent("bitrix:search.title", "visual", array(
											"NUM_CATEGORIES" => "1",
											"TOP_COUNT" => "5",
											"CHECK_DATES" => "N",
											"SHOW_OTHERS" => "N",
											"PAGE" => SITE_DIR."catalog/",
											"CATEGORY_0_TITLE" => "Товары" ,
											"CATEGORY_0" => array(
												0 => "iblock_catalog",
											),
											"CATEGORY_0_iblock_catalog" => array(
												0 => "all",
											),
											"CATEGORY_OTHERS_TITLE" => "Прочее",
											"SHOW_INPUT" => "Y",
											"INPUT_ID" => "title-search-input",
											"CONTAINER_ID" => "search",
											"PRICE_CODE" => array(
												0 => "BASE",
											),
											"SHOW_PREVIEW" => "Y",
											"PREVIEW_WIDTH" => "75",
											"PREVIEW_HEIGHT" => "75",
											"CONVERT_CURRENCY" => "Y"
										),
										false
									);?>
								</div>
							</div>
						</div>
						<!--div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 hidden-xs">
							<?/*$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "", array(
									"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
									"PATH_TO_PERSONAL" => SITE_DIR."personal/",
									"SHOW_PERSONAL_LINK" => "N",
									"SHOW_NUM_PRODUCTS" => "Y",
									"SHOW_TOTAL_PRICE" => "Y",
									"SHOW_PRODUCTS" => "N",
									"POSITION_FIXED" =>"N",
									"SHOW_AUTHOR" => "Y",
									"PATH_TO_REGISTER" => SITE_DIR."login/",
									"PATH_TO_PROFILE" => SITE_DIR."personal/"
								),
								false,
								array()
							);*/?>
						</div-->
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidden-xs">
					<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", 
							"all_include",
							array(
							"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
							"PATH_TO_PERSONAL" => SITE_DIR."personal/",
							"SHOW_PERSONAL_LINK" => "N",
							"SHOW_NUM_PRODUCTS" => "Y",
							"SHOW_TOTAL_PRICE" => "Y",
							"SHOW_PRODUCTS" => "N",
							"POSITION_FIXED" =>"N",
							"SHOW_AUTHOR" => "Y",
							"PATH_TO_REGISTER" => SITE_DIR."login/",
							"PATH_TO_PROFILE" => SITE_DIR."personal/"
						),
						false,
						array()
					);?>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12 hidden-xs">
					<?if ($wizTemplateId == "eshop_adapt_horizontal"):?>
					<?$APPLICATION->IncludeComponent("bitrix:menu", "catalog_horizontal", array(
							"ROOT_MENU_TYPE" => "left",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_TIME" => "36000000",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_THEME" => "site",
							"CACHE_SELECTED_ITEMS" => "N",
							"MENU_CACHE_GET_VARS" => array(
							),
							"MAX_LEVEL" => "3",
							"CHILD_MENU_TYPE" => "left",
							"USE_EXT" => "Y",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N",
						),
						false
					);?>
					<?endif?>
				</div>
			</div>
			<?if ($curPage != SITE_DIR."index.php"):?>
			<div class="row" id="search_ele_ hashol" style="display: none;">
				<div class="col-lg-12">
					<?/*$APPLICATION->IncludeComponent("bitrix:search.title", "visual", array(
							"NUM_CATEGORIES" => "1",
							"TOP_COUNT" => "5",
							"CHECK_DATES" => "N",
							"SHOW_OTHERS" => "N",
							"PAGE" => SITE_DIR."catalog/",
							"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS") ,
							"CATEGORY_0" => array(
								0 => "iblock_catalog",
							),
							"CATEGORY_0_iblock_catalog" => array(
								0 => "all",
							),
							"CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
							"SHOW_INPUT" => "Y",
							"INPUT_ID" => "title-search-input",
							"CONTAINER_ID" => "search",
							"PRICE_CODE" => array(
								0 => "BASE",
							),
							"SHOW_PREVIEW" => "Y",
							"PREVIEW_WIDTH" => "75",
							"PREVIEW_HEIGHT" => "75",
							"CONVERT_CURRENCY" => "Y"
						),
						false
					);*/?>
				</div>
			</div>
			<?endif?>

			<?if ($curPage != SITE_DIR."index.php"):?>
			<div class="row" id="transparent">
				<div class="col-lg-12">
					<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(
							"START_FROM" => "0",
							"PATH" => "",
							"SITE_ID" => "-"
						),
						false,
						Array('HIDE_ICONS' => 'N')
					);?>
				</div>
			</div>
			
			<?endif?>
<? 
	/*echo $_SERVER['REQUEST_URI'].'<br>';*/
	$a = $_SERVER['REQUEST_URI'];
	$zzzz = explode('?',$a);
	if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {?>
			<div class="row" id="transparent">
				<div class="servises_wrapper">
					<div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">
						<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
							<div class="servises">
								<a class="" href="#">
									<i class="prices"></i>
									<span>Низкие цены</span>
								</a>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
							<div class="servises">
								<a class="" href="#">
									<i class="quality"></i>
									<span>Гарантия качества</span>
								</a>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
							<div class="servises">
								<a class="" href="#">
									<i class="delivery"></i>
									<span>Доставка по всей Украине</span>
								</a>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
							<div class="servises">						
								<a class="" href="#">
									<i class="Hour"></i>
									<span>Круглосуточный прием заказов</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div><?
		}
?>

<div class="row"></div>
	</header>
	<?if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {?>
		<div class="workarea">
			<style>
							/*xs*/
				@media (min-width: 768px){
				}
				
				/*sm*/
				@media screen and (min-width: 768px){
					.workarea{position:relative;}
					.workarea:before{
						content: 'this SM size';
						display: block;
						text-align: center;
						color: #fff;
						width: 100%;
						height: 20px;
						position: absolute;
						top: 469px;
						left: 0;
						background: #e9edf1;
						z-index: 9999;
					}
				}
				
				/*md*/
				@media screen and (min-width: 992px){
					.workarea{position:relative;}
					.workarea:before{
						content: 'this MD size';
						text-align: center;
						color: #fff;
						display: block;
						width: 100%;
						height: 20px;
						position: absolute;
						top: 445px;
						left: 0;
						background: #e9edf1;
						z-index: 9999;
					}
				}
				
				/*lg*/
				@media screen and (min-width: 1200px){
					.workarea{position:relative;}
					.workarea:before{
						content: '';
						display: block;
						width: 100%;
						height: 20px;
						position: absolute;
						top: 524px;
						left: 0;
						background: #e9edf1;
						z-index: 9999;
					}
				}
				
			</style><?} else {?>
			<div class="workarea">	
				<style>
				.workarea{
					position:relative;
					margin-top: -185px !important;
					display: block;
				}
				</style><?
			}
	?>
		<div class="container bx-content-seection">
		<h1 class="bx-title dbg_title"><?=$APPLICATION->ShowTitle(false);?></h1>
			<?if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {?>
			<h2 class="red_title">Специальное предложение</h2><?} ?>
			<div class="row">
			<?$isCatalogPage = preg_match("~^".SITE_DIR."catalog/~", $curPage);?>
				<div class="bx-content <?=($isCatalogPage ? "col-xs-12" : "col-lg-12 col-md-12 col-sm-12")?>">
				