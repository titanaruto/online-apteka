<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
					
					</div>
					
					<?if (!$isCatalogPage):?>
					<div class="sidebar col-md-3 col-sm-4">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "include_right_side", Array(
								"AREA_FILE_SHOW" => "sect",	// Показывать включаемую область
								"AREA_FILE_SUFFIX" => "sidebar",	// Суффикс имени файла включаемой области
								"AREA_FILE_RECURSIVE" => "Y",	// Рекурсивное подключение включаемых областей разделов
								"EDIT_MODE" => "html"
							),
							false,
							array(
							"HIDE_ICONS" => "N"
							)
						);?>
					</div><!--// sidebar -->
					<?endif?>
				</div><!--//row-->
				<?if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {?>
						<div class="row step_by_step_row" >
							<div class="step_by_step">
								<h1>Tеперь заказать медикаменты еще проще!</h1>
								<div class="col-lg-3 col-md-3 col-sm-3">
									<div class="step_block">
										<div class="one" href="#">
											<span>Выбрать нужный товар</span>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3">
									<div class="step_block">
										<div class="two" href="#">
											<span>Оформить заказ</span>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3">
									<div class="step_block">
										<div class="three" href="#">
											<span>Выбрать удобный <br/>
												способ оплаты</span>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3">
									<div class="step_block">
										<div class="four" href="#">
											<span>Купить товар с доставкой в ближайшую аптеку</span>
										</div>
									</div>
								</div>
							</div>
						</div>
							<?/*<div class="row" id="news">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<h2 style="padding-left: 14px;">Блог</h2>
										$APPLICATION->IncludeComponent(
											"bitrix:news.list", 
											"official", 
											array(
												"IBLOCK_TYPE" => "news",
												"IBLOCK_ID" => "1",
												"NEWS_COUNT" => "2",
												"SORT_BY1" => "ACTIVE_FROM",
												"SORT_ORDER1" => "DESC",
												"SORT_BY2" => "SORT",
												"SORT_ORDER2" => "ASC",
												"FILTER_NAME" => "",
												"FIELD_CODE" => array(
													0 => "",
													1 => "",
												),
												"PROPERTY_CODE" => array(
													0 => "",
													1 => "",
												),
												"CHECK_DATES" => "Y",
												"DETAIL_URL" => "",
												"AJAX_MODE" => "Y",
												"AJAX_OPTION_SHADOW" => "Y",
												"AJAX_OPTION_JUMP" => "N",
												"AJAX_OPTION_STYLE" => "Y",
												"AJAX_OPTION_HISTORY" => "N",
												"CACHE_TYPE" => "A",
												"CACHE_TIME" => "36000000",
												"CACHE_FILTER" => "N",
												"CACHE_GROUPS" => "Y",
												"PREVIEW_TRUNCATE_LEN" => "120",
												"ACTIVE_DATE_FORMAT" => "j F Y",
												"DISPLAY_PANEL" => "N",
												"SET_TITLE" => "N",
												"SET_STATUS_404" => "N",
												"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
												"ADD_SECTIONS_CHAIN" => "N",
												"HIDE_LINK_WHEN_NO_DETAIL" => "N",
												"PARENT_SECTION" => "",
												"PARENT_SECTION_CODE" => "",
												"DISPLAY_NAME" => "Y",
												"DISPLAY_TOP_PAGER" => "N",
												"DISPLAY_BOTTOM_PAGER" => "N",
												"PAGER_SHOW_ALWAYS" => "N",
												"PAGER_TEMPLATE" => "",
												"PAGER_DESC_NUMBERING" => "N",
												"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
												"PAGER_SHOW_ALL" => "N",
												"AJAX_OPTION_ADDITIONAL" => "",
												"COMPONENT_TEMPLATE" => "official",
												"SET_BROWSER_TITLE" => "Y",
												"SET_META_KEYWORDS" => "Y",
												"SET_META_DESCRIPTION" => "Y",
												"SET_LAST_MODIFIED" => "N",
												"INCLUDE_SUBSECTIONS" => "Y",
												"DISPLAY_DATE" => "Y",
												"DISPLAY_PICTURE" => "N",
												"DISPLAY_PREVIEW_TEXT" => "Y",
												"MEDIA_PROPERTY" => "",
												"SEARCH_PAGE" => "/search/",
												"USE_RATING" => "N",
												"USE_SHARE" => "N",
												"PAGER_TITLE" => "Новости",
												"PAGER_BASE_LINK_ENABLE" => "N",
												"SHOW_404" => "N",
												"MESSAGE_404" => ""
											),
											false
										);
									</div>
									<div id="www" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<img src="<?=SITE_TEMPLATE_PATH?>/images/otzyv.jpg" alt="" />
									</div>
								</div>
							</div>*/?>
			<?}?>
			</div><!--//container bx-content-seection-->
		</div><!--//workarea-->
	
		<?if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {?>
			<div class="row">
			
				<div class="subscrible"><?
					$APPLICATION->IncludeComponent(
						"bitrix:subscribe.form", 
						".default", 
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"USE_PERSONALIZATION" => "Y",
							"SHOW_HIDDEN" => "N",
							"PAGE" => "#SITE_DIR#subscribe/",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "3600"
						),
						false
					);?>
				</div>
			</div>
		<?}?>
		
		<footer class="bx-footer">
			<div class="bx-footer-line">
				<div class="bx-footer-section container">
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => SITE_DIR."include/socnet_footer.php",
							"AREA_FILE_RECURSIVE" => "N",
							"EDIT_MODE" => "html",
						),
						false,
						Array('HIDE_ICONS' => 'Y')
					);?>
					
					
				</div>
			</div>
			
			<div class="bx-footer-section container bx-center-section">
				<div class="col-lg-3 col-md-3 col-sm-3  ">
					<div class="bx-inclogofooter">
						<div class="bx-inclogofooter-block">
							<a class="bx-inclogofooter-logo" href="<?=SITE_DIR?>">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo_mobile.php"), false);?>
							</a>
						</div>
						<div class="bx-inclogofooter-block">
							<div class="bx-inclogofooter-tel"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/telephone.php"), false);?></div>
							<div class="bx-inclogofooter-worktime"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule.php"), false);?></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 ">
					<h4 class="bx-block-title"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/about_title.php"), false);?></h4>
					<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
							"ROOT_MENU_TYPE" => "bottom",
							"MAX_LEVEL" => "1",
							"MENU_CACHE_TYPE" => "A",
							"CACHE_SELECTED_ITEMS" => "N",
							"MENU_CACHE_TIME" => "36000000",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_CACHE_GET_VARS" => array(
							),
						),
						false
					);?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<h4 class="bx-block-title"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/catalog_title.php"), false);?></h4>
					<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
							"ROOT_MENU_TYPE" => "left",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_TIME" => "36000000",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_CACHE_GET_VARS" => array(
							),
							"CACHE_SELECTED_ITEMS" => "N",
							"MAX_LEVEL" => "1",
							"USE_EXT" => "Y",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N"
						),
						false
					);?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 " id="right_foooter_block" ><!-- рассылка -->
					<div id="social_block" style="/*padding: 20px;background:#eaeaeb*/">
						<?/*$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => SITE_DIR."include/sender.php",
								"AREA_FILE_RECURSIVE" => "N",
								"EDIT_MODE" => "html",
							),
							false,
							Array('HIDE_ICONS' => 'Y')
						);*/?>
						
						<ul id="social-list">
							<li><a target="blank" class="fb" href="https://www.facebook.com/medserviceua"></a></li>
							<li><a target="blank" class="tw" href="https://twitter.com/medserviceua"></a></li>
							<!--li><a class="od" href="#"></a></li-->
							<li><a target="blank" class="vk" href="https://vk.com/medserviceua"></a></li>
						</ul>
						<div class="request">
							<!--div class="selection">
								<span>Ваш город:</span>
								<select>
									<option>Днепропетровск</option>
									<option>Харьков</option>
									<option>Луганск</option>
									<option>Запорожье</option>
									<option>Николаев</option>
								</select>
							</div-->
							<div class="number">
								<span class="bold">0 800 50 52 53</span>
							</div>	
							<button id="footer_Request_a_call">Заказать звонок</button>
								<div class="overlay"></div>
								<div class="feedback">
										<span class="closes">X</span>
	     									<div class="mfeedback">
											<form method="POST" action="<?=SITE_TEMPLATE_PATH?>/ajax_tel.php">
												<div class="mf-name">
													<div class="mf-text">Ваше имя<span class="mf-req">*</span></div>
													<input type="text" pattern="^[А-Яа-яЁё\s]+$" required value="" name="user_name" placeholder="Иван Иванов">
												</div>
												<div class="mf-email">
												<div class="mf-text">
													<b style="padding-left:10px;">Ваш телефон</b><span class="mf-req">*</span>
												</div>	
													<input id="222" type="text" required value="" name="user_phone">
												</div>
													<div class="mf-message">
														<div class="mf-text">Сообщение<span class="mf-req">*</span></div>
														<textarea pattern="^[А-Яа-яЁё\s]+$" required name="MESSAGE"></textarea>
													</div>
												<input type="submit" value="Отправить" name="submit" class="send">
											</form>
										</div>
								</div>
								
								<script type="text/javascript">
									$(document).ready(function(){
										$('.feedback .closes, .overlay').click(function (){
										$('.feedback, .overlay').css('opacity','0');
										$('.feedback, .overlay').css('visibility','hidden');
									});
										$('#footer_Request_a_call').click(function (){
										$('.overlay, .feedback').css('opacity','1');
										$('.overlay, .feedback').css('visibility','visible');
									});
									});
								</script>	
						</div>
					</div>
					<div id="bx-composite-banner" style="/*padding-top: 20px*/"></div>
				</div>
				
			</div>
			
			<div class="bx-footer-partner-logo">
				<div class="container">
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<img src="/upload/partner/pumb_logo.png" alt="" />
					</div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<img src="/upload/partner/mc-securecode-logo.png" alt="" />
					</div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<img src="/upload/partner/verified.png" alt="" />
					</div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<img src="/upload/partner/visa_mastercard.png" alt="" />
					</div>
				</div>
			</div>
			
			<div class="bx-footer-bottomline">
				<div class="bx-footer-section container">
					<div class="col-sm-6"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/copyright.php"), false);?></div>
					<div class="col-sm-6 bx-up"><a href="javascript:void(0)" data-role="eshopUpButton"><i class="fa fa-caret-up"></i> <?=GetMessage("FOOTER_UP_BUTTON")?></a></div>
				</div>
			</div>

			
		</footer>
		<div class="col-xs-12 hidden-lg hidden-md hidden-sm">
			<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "", array(
					"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
					"PATH_TO_PERSONAL" => SITE_DIR."personal/",
					"SHOW_PERSONAL_LINK" => "N",
					"SHOW_NUM_PRODUCTS" => "Y",
					"SHOW_TOTAL_PRICE" => "Y",
					"SHOW_PRODUCTS" => "N",
					"POSITION_FIXED" =>"Y",
					"POSITION_HORIZONTAL" => "center",
					"POSITION_VERTICAL" => "bottom",
					"SHOW_AUTHOR" => "Y",
					"PATH_TO_REGISTER" => SITE_DIR."login/",
					"PATH_TO_PROFILE" => SITE_DIR."personal/"
				),
				false,
				array()
			);?>
		</div>
	</div> <!-- //bx-wrapper -->

<script>
	BX.ready(function(){
		var upButton = document.querySelector('[data-role="eshopUpButton"]');
		BX.bind(upButton, "click", function(){
			var windowScroll = BX.GetWindowScrollPos();
			(new BX.easing({
				duration : 500,
				start : { scroll : windowScroll.scrollTop },
				finish : { scroll : 0 },
				transition : BX.easing.makeEaseOut(BX.easing.transitions.quart),
				step : function(state){
					window.scrollTo(0, state.scroll);
				},
				complete: function() {
				}
			})).animate();
		})
	});
</script>
<?
/*echo '<script type="text/javascript">';
include_once '/check_order_fast/script.js';
echo '</script>';
echo '<div id="ordcheck" >Проверить заказ</div>';
echo '<div id="overlay" onclick="hideDiv()"></div>';
echo '<div id="in">
			
	<input id="phone" placeholder="Введите Ваш телефон" type="text" value=""><br/>
	<input id="order" placeholder="Введите номер Вашего заказа" type="text" value=""><br/>
			
	<button id="Request_a_call" onclick="send();">Проверить статус заказа</button>
</div>';
CModule::IncludeModule("sale");
$CSaleOrder = new CSaleOrder;
$arBOrder = $CSaleOrder->GetByID(intval(31));
if (count($arBOrder) > 0) {
	//pre($arBOrder);
	$CSaleOrderPropsValue = new CSaleOrderPropsValue;
	$arSelectFields = array('*');
	$arFilter = array('ORDER_ID' => '31');
	$arFilter['ORDER_PROPS_ID'] = 3;
	$dbProps_order = $CSaleOrderPropsValue->GetList(
		array('ID'=>'DESC'),
		$arFilter,
		false,
		false,
		$arSelectFields
	);
	if ($props_order = $dbProps_order->Fetch()){
		//pre($props_order);
	}
}
*/?>
<!-- Jivosite -->
<script type='text/javascript'>
(function(){ var widget_id = 'trk28cTlx9';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->
</body>
</html>
