<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?> 
</div><!-- end content-->
				<?if ($isArticlePage==1 && $secLevArticles >1){
					if($secLevArticles < 3){
						//pre($_SERVER);?>
						<div class="sidebar col-md-3 col-sm-4 hidden-xs">
							<?
							$APPLICATION->IncludeComponent("bitrix:main.include", "include_akciya", Array(
									"AREA_FILE_SHOW" => "sect",
									"AREA_FILE_SUFFIX" => "sidebar",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_MODE" => "html"
								),
								false,
								array(
								"HIDE_ICONS" => "N"
								)
							);?>
						</div><!--// sidebar --><?
					} else {?>
						<div class="sidebar col-md-3 col-sm-4 hidden-xs">
						<?
						//pre('66666');
						$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"include_referent",
							Array(
								"AREA_FILE_SHOW" => "sect",
								"AREA_FILE_SUFFIX" => "sidebar",
								"AREA_FILE_RECURSIVE" => "Y",
								"EDIT_MODE" => "html",
							),
							false,
							array(
								"HIDE_ICONS" => "N"
							)
						);?>
						</div>
					<?}?>
				<?} else {?>
					<?if (!$isCatalogPage){?>
						<div class="sidebar col-md-3 col-sm-4 hidden-xs">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "include_right_side", Array(
									"AREA_FILE_SHOW" => "sect",
									"AREA_FILE_SUFFIX" => "sidebar",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_MODE" => "html"
								),
								false,
								array(
									"HIDE_ICONS" => "N"
								)
							);?>
						</div><!--// sidebar -->
					<?}
				}?>
				</div><!--//row-->
				<?if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {?>
					<div class="row step_by_step_row hidden-xs" >
						<div class="step_by_step">
							<span class="title">Tеперь заказать медикаменты еще проще!</span>
							<div class="col-lg-3 col-md-3 col-sm-3">
								<div class="step_block">
									<div class="one">
										<span>Выбрать нужный товар</span>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3">
								<div class="step_block">
									<div class="two">
										<span>Оформить заказ</span>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3">
								<div class="step_block">
									<div class="three">
										<span>Выбрать удобный <br/>
											способ оплаты</span>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3">
								<div class="step_block">
									<div class="four">
										<span>Купить товар с доставкой в ближайшую аптеку</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?}?>
			</div><!--//container bx-content-seection-->
		</div><!--//workarea-->
		<?if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {?>
			<div class="row">
				<div class="subscrible hidden-xs"><?
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
				<div class="col-xs-8 col-lg-3 col-md-3 col-sm-3  ">
					<div class="bx-inclogofooter">
						<div class="bx-inclogofooter-block coloms-footer col-xs-5 col-sm-12 col-md-12 col-lg-12">
							<div class="bx-inclogofooter-block-wrapper">
                                <?php if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] != '/') :?>
                                    <a class="bx-inclogofooter-logo hidden-xs" href="<?=SITE_DIR?>">
                                        <?$APPLICATION->IncludeComponent(
                                            "bitrix:main.include",
                                            "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR."include/company_logo.php"
                                            ), false);
                                        ?>
                                    </a>
                                    <a class="bx-inclogofooter-logo hidden-lg hidden-md hidden-sm" href="<?=SITE_DIR?>">
                                        <?$APPLICATION->IncludeComponent(
                                            "bitrix:main.include",
                                            "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR."include/company_logo_footer.php"
                                            ), false);
                                        ?>
                                    </a>
                                <?php else:?>
                                    <div class="bx-inclogofooter-logo hidden-xs">
                                        <?$APPLICATION->IncludeComponent(
                                            "bitrix:main.include",
                                            "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR."include/company_logo.php"
                                            ), false);
                                        ?>
                                    </div>
                                    <div class="bx-inclogofooter-logo hidden-lg hidden-md hidden-sm">
                                        <?$APPLICATION->IncludeComponent(
                                            "bitrix:main.include",
                                            "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR."include/company_logo_footer.php"
                                            ), false);
                                        ?>
                                    </div>
                                <?php endif?>
							</div>
						</div>
						<div class="bx-inclogofooter-block coloms-footer col-xs-7 col-sm-12 col-md-12 col-lg-12">
							<div class="bx-inclogofooter-tel">
								<?$APPLICATION->IncludeComponent(
									"bitrix:main.include",
									"",
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/telephone.php"
									),
									false);
								?>
							</div>
							<div class="bx-inclogofooter-worktime">
								<?$APPLICATION->IncludeComponent(
									"bitrix:main.include",
									"",
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/schedule.php"
									),
									false);
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="hidden-xs col-lg-3 col-md-3 col-sm-3 ">
						<div class="bx-block-title">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/about_title.php"
								),
								false);
							?>
						</div>
					<?$APPLICATION->IncludeComponent(
							"bitrix:menu",
							"bottom_menu",
							array(
								"ROOT_MENU_TYPE" => "bottom",
								"MAX_LEVEL" => "1",
								"MENU_CACHE_TYPE" => "A",
								"CACHE_SELECTED_ITEMS" => "N",
								"MENU_CACHE_TIME" => "36000000",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_CACHE_GET_VARS" =>
								array(),
							),
						false
					);?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
						<div class="bx-block-title">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => SITE_DIR."include/catalog_title.php"),
								false);
							?>
						</div>
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
				<div class="col-xs-4 col-lg-3 col-md-3 col-sm-3 " id="right_foooter_block" >
					<div id="social_block">
						<ul id="social-list" class="hidden-xs">
							<li>
                                <span class="seo-linkfb" data-link="https://www.facebook.com/medserviceua">   </span>
<!--                                <a target="blank" class="fb" href="https://www.facebook.com/medserviceua"></a>-->
                            </li>
							<li>
                                <span class="seo-linktw" data-link="https://twitter.com/medserviceua">   </span>
<!--                                <a target="blank" class="tw" href="https://twitter.com/medserviceua"></a>-->
                            </li>
							<!--li><a class="od" href="#"></a></li-->
<!--							<li><a target="blank" class="vk" href="https://vk.com/medserviceua"></a></li>-->
						</ul>
						<div class="request">
							<div class="number hidden-xs">
								 <span class="bold">
                                <a href="tel:0 800 50 52 53">0 800 50 52 53</a>
                              </span>
							</div>
							<button class="mc-button phone request_a_call">
<!--								<i class="fa fa-phone"></i>-->
							Заказать звонок</button>
							<div class="overlay"></div>
							<div class="feedback">
								<span class="mc-button closes"></span>
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
											<input id="222" type="tel" required value="" name="user_phone">
										</div>
										<div class="mf-message">
											<div class="mf-text">Сообщение<span class="mf-req">*</span></div>
											<textarea data-pattern="^[А-Яа-яЁё\s]+$" required name="MESSAGE"></textarea>
										</div>
                                        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
										<label for="feedback-submit" class="mc-button letter">
											Отправить
											<input id="feedback-submit" type="submit" value="" name="submit" class="send">
										</label>
									</form>
								</div>
							</div>

						</div>
						<?
							if(isset($_SESSION['MSG_AJAX_OK']) && $_SESSION['MSG_AJAX_OK'] == 'Y') {
							?>
								<div class="overlay return"></div>
								<div class="massage_return">
									<p><i class="fa fa-check" aria-hidden="true"></i>Мы перезвоним Вам в течениие 2 часов и ответим на все вопросы!</p>
									<span class="closes"></span>
								</div>
								<?
								$_SESSION['MSG_AJAX_OK'] = 'N';
									unset($_SESSION['MSG_AJAX_OK']);
								}
						?>
					</div>
					<div id="bx-composite-banner"></div>
				</div>
			</div>
			<div class="bx-footer-partner-logo hidden-xs">
				<div class="container">
					<div class="partner-colms col-xs-12 col-sm-3 col-md-3 col-lg-3">
						<img class="lazy" data-original="/upload/partner/pumb_logo.png" alt="" />
					</div>
					<div class="partner-colms col-xs-6 col-sm-3 col-md-3 col-lg-3">
                         <span class="seo-link" data-link="https://pps.fuib.com/payment/resources/739C0C071EE75948A22CF1205620361A/RU/help-sc-RU.html">
                            <img class="lazy" data-original="/upload/partner/mc-securecode-logo.png" alt="" />
                        </span>
<!--						<a rel="nofollow" target="_blank" href="https://pps.fuib.com/payment/resources/739C0C071EE75948A22CF1205620361A/RU/help-sc-RU.html">-->
<!--							<img src="/upload/partner/mc-securecode-logo.png" alt="" />-->
<!--						</a>-->
					</div>
					<div class="partner-colms col-xs-6 col-sm-3 col-md-3 col-lg-3">
                          <span class="seo-link" data-link="https://pps.fuib.com/payment/resources/739C0C071EE75948A22CF1205620361A/RU/help-vbv-RU.html">
                            <img class="lazy" data-original="/upload/partner/verified.png" alt="" />
                        </span>
<!--						<a rel="nofollow" target="_blank" href="https://pps.fuib.com/payment/resources/739C0C071EE75948A22CF1205620361A/RU/help-vbv-RU.html">-->
<!--							<img src="/upload/partner/verified.png" alt="" />-->
<!--						</a>-->
					</div>
					<div class="partner-colms col-xs-12 col-sm-3 col-md-3 col-lg-3">
						<img class="lazy" data-original="/upload/partner/visa_mastercard.png" alt="" />
					</div>
				</div>
			</div>
			<div class="bx-footer-bottomline">
				<div class="bx-footer-section container">
					<div class="col-sm-6"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/copyright.php"), false);?></div>
					<div class="col-sm-6 bx-up"><a href="javascript:void(0)" data-role="eshopUpButton"><i class="fa fa-caret-up"></i> <?=GetMessage("FOOTER_UP_BUTTON")?></a></div>
				</div>
			</div>
            <script>
                $('.seo-link').replaceWith(function(){return'<a href="'+$(this).data('link')+'">'+$(this).html()+'</a>';})
                $('.seo-linkfb').replaceWith(function(){return'<a class="fb" href="'+$(this).data('link')+'">'+$(this).html()+'</a>';})
                $('.seo-linktw').replaceWith(function(){return'<a class="tw" href="'+$(this).data('link')+'">'+$(this).html()+'</a>';})
            </script>
		</footer>
	</div> <!-- //bx-wrapper


	<input id="voting-checked" type="checkbox" />
	<label class="mc-button voice label-voting-checked" for="voting-checked">
				<i class="fa fa-comments"></i>
	наш опрос</label>

	<div class="voting-wrap">
		<div class="voting-relative-wrap">
			<input id="voting-close" type="checkbox" />
			<label class="label-voting-close" for="voting-checked">
				<span class="icon-bar top-bar"></span>
				<span class="icon-bar middle-bar"></span>
				<span class="icon-bar bottom-bar"></span>
			</label>

			<?/*$APPLICATION->IncludeComponent("bitrix:main.include", "voting", Array(
				"AREA_FILE_SHOW" => "sect",
				"AREA_FILE_SUFFIX" => "sidebar",
				"AREA_FILE_RECURSIVE" => "Y",
				"EDIT_MODE" => "html"
				),
				false,
				array(
					"HIDE_ICONS" => "N"
				)
			);*/?>
		</div>
	</div>-->

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
	echo '<div id="overlay" onclick="hideDiv()"></div>';
	echo '<div id="in"><span onclick="hideDiv()" class="mc-button closes"></span>
		<input id="phone" placeholder="Введите Ваш телефон" type="tel" value=""><br/>
		<input id="order" placeholder="Введите номер Вашего заказа" type="text" value=""><br/>
		<button class="mc-button book" id="Request_a_call" onclick="send();"><i class="fa fa-book" aria-hidden="true"></i>Проверить статус заказа</button>
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
?>

<script src="/bitrix/templates/eshop_adapt_MC/components/bitrix/main.include/js/script.js?31"></script>

<link href="/bitrix/templates/eshop_adapt_MC/components/bitrix/main.include/css/style.css"  rel="stylesheet" />

<script>
	window.scrollTo(0,null);
</script>

<div id="hidden">
    <!-- BEGIN JIVOSITE CODE {literal} -->
    <script>
        /*
        $(window).scroll(function () {
            (function(){
                var widget_id = '4WRyTij6MI';var d=document;var w=window;function l(){
                    var s = document.createElement('script');
                    s.type = 'text/javascript';
                    s.async = true;
                    s.src = '//code.jivosite.com/script/widget/'+widget_id;
                    var ss = document.getElementsByTagName('script')[0];
                    ss.parentNode.insertBefore(s, ss);
                }
                if(d.readyState=='complete'){
                    l();
                } else {
                    if(w.attachEvent){
                        w.attachEvent('onload',l);
                    } else {
                        w.addEventListener('load',l,false);
                    }
                }
            })();
        })

         */
    </script>
    <!-- {/literal} END JIVOSITE CODE -->
</div>

<script>
	// Выпадающий баннер
	//console.log(window.location.href);
	var close = document.getElementById('close');
	var wrap = document.getElementById('wrap');

	function show(){
			document.getElementById('window-banner').style.cssText = "display : block; opacity: 1";
			document.getElementById('wrap').style.display = "block";
		}
		if (window.location.href == 'https://online-apteka.com.ua/') {
			//setTimeout(show, 3000);
		}

		function hide(){
			document.getElementById('window-banner').style.display = "none";
			document.getElementById('wrap').style.display = "none";
		}
		setTimeout(hide, 15000);

	document.addEventListener('click', function(e){
		var event = e.target;

		if(event == close || event == wrap) {
			hide();
		}
});
</script>
<script>
	//Add To Cart
	$('.mc-button').click(function() {
		fbq('track', 'AddToCart', {
			content_ids: ['1234', '1853', '9386'],
			content_type: 'product',
			value: 3.50,
			currency: 'USD'
		});
	});
	//End Add To Cart
</script>
<script>
	//Begin
	$('.mc-button.basket-mc-button').click(function() {
		fbq('track', 'InitiateCheckout');
	});
	//End Begin
</script>
<script>
	//Buy
	$('.mc-button.check').click(function() {
		fbq('track', 'Purchase', {
			content_ids: ['1234', '4642', '35838'],
			content_type: 'product',
			value: 247.35,
			currency: 'USD'
		});
	});
	//End Buy
</script>
<script>
	//Left sidebar text with dot
	var sidebarElemLister = document.querySelectorAll('.submenu > li > a');
	var pointer = '...';
	for (var i = 0; i < sidebarElemLister.length; i++) {
		sidebarElemLister[i].classList.add('bud-elem-list');
		//console.log(sidebarElemLister[i].clientHeight);

		if (sidebarElemLister[i].clientHeight > 64) {
			sidebarElemLister[i].parentNode.classList.add('pointer');
		}
	}
</script>
<!-- Facebook Pixel Code -->
<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
	n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
	document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '281069695668193'); // Insert your pixel ID here.
	fbq('track', 'PageView');
  //Search
	fbq('track', 'Search', {
		search_string: 'leather sandals',
		content_ids: ['1234', '2424', '1318'],
		content_type: 'product'
	});
	//End Search
	//View
	fbq('track', 'ViewContent', {
		content_ids: ['1234'],
		content_type: 'product',
		value: 0.50,
		currency: 'USD'
	});
	//End View

	//Add To Cart
	$('.mc-button').click(function(e) {
		fbq('track', 'AddToCart', {
			content_ids: ['1234', '1853', '9386'],
			content_type: 'product',
			value: 3.50,
			currency: 'USD'
		});

        gtag('event', 'add_to_cart', {
            "items": [
                {
                    "id": "1234",
                    "name": "Apteka product",
                    "list_name": "Search Results",
                    "brand": "Google",
                    "category": "Apparel/T-Shirts",
                    "list_position": 1,
                    "quantity": 1,
                    "price": '2.0'
                }
            ]
        });
	});
	//End Add To Cart

	//Begin
	$('.mc-button.basket-mc-button').click(function() {
		fbq('track', 'InitiateCheckout');
	});
	//End Begin

	//Buy
	$('.mc-button.check').click(function() {
		fbq('track', 'Purchase', {
			content_ids: ['1234', '4642', '35838'],
			content_type: 'product',
			value: 247.35,
			currency: 'USD'
		});
	});

    function add2basket(ID) {
        //alert(ID + " / " + $("#tov" + ID).val());
        let $prId = "#BX_BTN_"+ID;
        $.ajax({
            type: "POST",
            url: "/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/ajax/add2basket_ajax.php",
            data: {
                PRODUCT_ID: ID,
                QUANTITY: 1,
            },
            success: function(msg) {
                let response = JSON.parse(msg)
                $('div[id^=CatalogSectionBasket_bx_]').remove()
                // $($prId).append(msg);
                BX.onCustomEvent('OnBasketChange');
                $('body').append('<div id="CatalogSectionBasket_bx_' + response.id + '"\n'+
'         class="popup-window popup-window-with-titlebar"\n'+
'         style="z-index: 999999; position: fixed; display: block; top: 50%; left: 50%; transform: translate(-50%,-50%); color: #333;">\n'+
'        <div class="popup-window-titlebar"\n'+
'             id="popup-window-titlebar-CatalogSectionBasket_bx_' + response.id + '">\n'+
'            <div style="margin-right: 30px; white-space: nowrap; text-align: left;">Товар добавлен в корзину</div>\n'+
'        </div>\n'+
'        <div id="popup-window-content-CatalogSectionBasket_bx_' + response.id + '"\n'+
'             class="popup-window-content">\n'+
'            <div style="width: 96%; margin: 10px 2%; text-align: center;"><img\n'+
'                        src="' + response.img + '" height="130"\n'+
'                        style="max-height:130px">\n'+
'                <p>' + response.name + '</p></div>\n'+
'        </div>\n'+
'        <a class="popup-window-close-icon popup-window-titlebar-close-icon" onclick="removeBlock(\'CatalogSectionBasket_bx_' + response.id + '\'); return false;" href="javascript:void(0)" rel="nofollow"\n'+
'           style="top: 10px; right: 10px;"></a>\n'+
'        <div class="popup-window-buttons new-search">\n'+
'            <span onclick="$(\'.bx-basket-block.cart-popup-block a\').click()" class="bx_catalog_list_home col3 bx_blue"\n'+
'                  style="margin-bottom: 0px; border-bottom: 0px none transparent;">\n'+
'                <a href="javascript:void(0)" onclick="removeBlock(\'CatalogSectionBasket_bx_' + response.id + '\');" rel="nofollow"\n'+
'                   class="bx_medium bx_bt_button cart-popup-a" id="" style="margin-right: 10px; display: inline-block; text-align: center; cursor: pointer;\n'+
'    white-space: nowrap; font-size: 14px; text-decoration: none; color: #fff;\n'+
'    position: relative; font-weight: 100!important;">Перейти в корзину</a>\n'+
'            </span>\n'+
'            <span class="bx_catalog_list_home col3 bx_blue"\n'+
'                  style=" margin-bottom: 0px; border-bottom: 0px none transparent;">\n'+
'                <a onclick="removeBlock(\'CatalogSectionBasket_bx_' + response.id + '\'); return false;" href="javascript:void(0)" rel="nofollow"\n'+
'                   class="bx_medium bx_bt_button"\n'+
'                   style=" margin-right: 10px; display: inline-block; text-align: center; cursor: pointer; white-space: nowrap; font-size: 14px; text-decoration: none; color: #fff; position: relative; font-weight: 100!important;"\n'+
'                   id="">Продолжить покупки</a>\n'+
'            </span></div>\n'+
'    </div>')
            }
        });
    }
    function removeBlock(id) {
        let $prId = "#"+ id;
        $($prId).remove();
    }

    //End Buy
</script>

<script src="<?=SITE_TEMPLATE_PATH?>/js/my_script.js?48"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.lazyload.min.js"></script>
<script>
    $("a.lazy, img.lazy").lazyload({
        effect : "fadeIn"
    });
</script>
<!--<script src="https://www.google.com/recaptcha/api.js?render=6LexSsEUAAAAANeazttEFhrw4QZORC0g7HgVEex9"></script> --> <!-- // apteka.local-->
<script src="https://www.google.com/recaptcha/api.js?render=6LchScEUAAAAAEFmrC88tpKXQtHVBcVOVDF-GYmC"></script> <!-- online-apteka.com.ua -->
<script>
    grecaptcha.ready(function() {
        // grecaptcha.execute('6LexSsEUAAAAANeazttEFhrw4QZORC0g7HgVEex9', {action: 'callback'}).then(function(token) { <!-- // apteka.local-->
        grecaptcha.execute('6LchScEUAAAAAEFmrC88tpKXQtHVBcVOVDF-GYmC', {action: 'callback'}).then(function(token) { <!-- online-apteka.com.ua -->
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });
</script>
<script type="text/javascript">
    (function(d, w, s) {
        var widgetHash = 'x3r5qqvud0plf655rx5f', gcw = d.createElement(s); gcw.type = 'text/javascript'; gcw.async = true;
        gcw.src = '//widgets.binotel.com/getcall/widgets/'+ widgetHash +'.js';
        var sn = d.getElementsByTagName(s)[0]; sn.parentNode.insertBefore(gcw, sn);
    })(document, window, 'script');
</script>
<style>
    .grecaptcha-badge {
        display: none;
    }
</style>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/minify/copy.min.js?1"></script>
</body>
</html>
