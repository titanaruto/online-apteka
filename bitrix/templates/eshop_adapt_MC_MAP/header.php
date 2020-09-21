<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
$wizTemplateId = COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID);
CJSCore::Init(array("fx"));
$curPage = $APPLICATION->GetCurPage(true);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
	<meta charset="utf-8">
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<!--script  src="<?=SITE_TEMPLATE_PATH?>/js/lightslider.js"></script-->
	<!--script src="<?=SITE_TEMPLATE_PATH?>/js/script.js"></script-->
	<!--script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.scrollbox.js"></script>
	<script>
		$(function () {
		  $('#demo5').scrollbox({
		    direction: 'h',
		    //distance: 134,
		    autoPlay: false,
		    //onMouseOverPause: true,
		    //paused: false,
		    //infiniteLoop: true    //безконечный цыкл
		  });
		  $('#demo5-backward').click(function () {
		    $('#demo5').trigger('backward');
		  });
		  $('#demo5-forward').click(function () {
		    $('#demo5').trigger('forward');
		  });
		});
	</script-->
	
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.maskedinput.js"></script>
	<script>
	jQuery(function($){
		$("#date").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
		$("#phone").mask("(999) 999-99-99");
		$("#222").mask("(999) 999-99-99");
		$("#tin").mask("99-9999999");
		$("#ssn").mask("999-99-9999");
	});
	</script>
	<!-- search nashy apteki 
	<script src="<?=SITE_TEMPLATE_PATH?>/js/search/jquery.hideseek.min.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/search/initializers.js"></script>
	<script>
		$('#search-highlight').hideseek({
		  highlight: true,
		  navigation: true
		});
	</script>-->
	<script src="https://maps.googleapis.com/maps/api/js"></script>
	<script>
		var map, infoBubble, infoBubble2;
		function init() {
			  var mapCenter = new google.maps.LatLng(48.468219,35.0342373);
			  map = new google.maps.Map(document.getElementById('map'), {
			  zoom: 13,
			  center: mapCenter,
			  mapTypeId: google.maps.MapTypeId.ROADMAP,
			  draggable: true
			});
			  var marker = new google.maps.Marker({
			  map: map,
			  position: new google.maps.LatLng(48.468219,35.0342373),
			  draggable: false
			}); 
		}
		google.maps.event.addDomListener(window, 'load', init);
	</script>
   
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
	<?$APPLICATION->ShowHead();?>
	<?	
		$APPLICATION->SetAdditionalCSS("/bitrix/css/main/bootstrap.css");
		$APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/colors.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/lightslider.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/my_style.css");	
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/template_styles.css");
	?>
	<!-- Roboto Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
 	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,300,700' rel='stylesheet' type='text/css'>
	<title><?$APPLICATION->ShowTitle()?></title>
</head>
<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<?$APPLICATION->IncludeComponent("bitrix:eshop.banner", "", array());?>


<?/* echo '<pre>';
	print_r($_SERVER);
	echo '</pr
*/?>
<?/*PHP_SELF, REQUEST_URI*/?>

<div class="bx-wrapper" id="bx_eshop_wrap">
<div style="width: 100%; height: 529px;" id="map"></div>
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
			
					<div class="row" id="mr_top">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
								<div class="bx-logo">
									<a class="bx-logo-block hidden-xs" href="<?=SITE_DIR?>">
										<?$APPLICATION->IncludeComponent(0 800 50 52 53"bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo.php"), false);?>
									</a>
									<a class="bx-logo-block hidden-lg hidden-md hidden-sm text-center" href="<?=SITE_DIR?>">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo_mobile.php"), false);?>
									</a>
								</div>
							</div>
							<div class="col-lg-4 col-md-3 col-sm-5 col-xs-4">
								<div class="request">
									<div class="number">
										<span class="bold">0 800 50 52 53</span>
									</div>	
									<button id="Request_a_call">Заказать звонок</button>
									<div class="overlay"></div>
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
			<div class="row border_menu">
				<div class="col-md-12 hidden-xs catalog_menu">
					<ul class="ul_menu">
						<li class="active-item">
							<a class="link_main_menu" href="/catalog/">Каталог</a>
							<div class="wrap_menu">
								<?$APPLICATION->IncludeComponent("bitrix:menu", "vertical_multilevel", array(
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
							</div>
						</li>
						<li><a class="link_main_menu" href="">Акции</a></li>
						<li><a class="link_main_menu" href="">Наши аптеки</a></li>
						<li><a class="link_main_menu" href="">Журнал</a></li>
					</ul>
				</div>
			</div>

			<?/*if ($curPage != SITE_DIR."index.php"):?>
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
				</div>
			<?endif*/?>
			
					<div class="row" id="by_step">
					<div class="container">
						<!--div class="servises_wrapper clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">
								<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
									<div class="servises">
										<a class="" target="black" href="http://online-apteka.com.ua/about/">
											<i class="prices"></i>
											<span>Низкие цены</span>
										</a>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
									<div class="servises">
										<a class="" target="black" href="http://online-apteka.com.ua/about/guaranty/">
											<i class="quality"></i>
											<span>Гарантия качества</span>
										</a>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
									<div class="servises">
										<a target="black" class="" href="http://med-service.com.ua/apteki/ukraine/">
											<i class="delivery"></i>
											<span>Доставка по всей Украине</span>
										</a>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
									<div class="servises" >						
										<a class="" href="#" id="footer_Request_a_call">
											<i class="Hour"></i>
											<span>Круглосуточный прием заказов</span>
										</a>
									</div>
								</div>
							</div>
						</div> -->
					</div>
				</div>
	</header>
	
	<?/*$a = $_SERVER['REQUEST_URI'];
		$zzzz = explode('?',$a);
		if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {*/?>
	<?/*
			}
		*/?>
	
	<?if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {?>
		<div class="workarea">
			<?}else{?>
			<div class="workarea">
				<style>
					.workarea{
						margin-top: -65px;
					}
				</style>
			<?}?>
		<div class="container bx-content-seection">
		<h1 id="basicTitle" class="bx-title dbg_title"><?=$APPLICATION->ShowTitle(false);?></h1>
			<?if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {?>
			<h2 class="red_title">Акционные предложения от «Мед-Сервис».</h2><?} ?>
			<div class="row">
			<?$isCatalogPage = preg_match("~^".SITE_DIR."catalog/~", $curPage);?>
				<div class="bx-content <?=($isCatalogPage ? "col-xs-12" : "col-md-9 col-sm-8")?>">
				