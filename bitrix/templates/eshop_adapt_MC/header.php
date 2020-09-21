<? 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/templates/" . SITE_TEMPLATE_ID . "/header.php");
$APPLICATION->SetTitle("");
$wizTemplateId = COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID);
CJSCore::Init(array("fx"));
$curPage = $APPLICATION->GetCurPage(true);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><? $APPLICATION->ShowTitle() ?><?php echo isset($_GET['PAGEN_1']) ? ('- ' . GetMessage("PAGINATION_TITLE") . ' ' . $_GET['PAGEN_1']) : '' ?></title>
    <?php preg_match('|\/filter\/(.*?)\/apply|sei', $_SERVER['REQUEST_URI'], $arr); ?>
    <?php
    $arr_uri = explode("/", $_SERVER['REQUEST_URI']);
    ?>
    <?php if ((true === (count($_GET) > 0 && isset($_GET['PAGEN_1']))) || (count($arr[1]) > 0) || (true === (count($_GET) > 0 && (isset($_GET['sort']) || isset($_GET['upper']))))) { ?>
        <meta name="robots" content="noindex, nofollow">
    <? } else if(in_array('login', $arr_uri)) { ?>
        <meta name="robots" content="noindex, follow">
    <?php } else {?>
        <meta name="robots" content="index, follow">
    <? } ?>
    <? //echo Tools::setMetaPagination($_GET['PAGEN_1']); ?>
    <?= Tools::canonicalRewrite($_SERVER["REQUEST_URI"])?>
    <?= Tools::setCanonicalTagForPaginationPages(empty($_SERVER["QUERY_STRING"]) ? $_SERVER["REQUEST_URI"] : $_SERVER["QUERY_STRING"])?>
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
    <meta itemprop="telephone" content="0 800 50 52 53">
    <meta itemprop="address" content="">
    <meta itemprop="name" content="https://online-apteka.com.ua">
    <meta name="yandex-verification" content="232554c45f3c3023" />

    <?$APPLICATION->ShowMeta("robots")?>
    <?$APPLICATION->ShowMeta("description")?>
    <?$APPLICATION->ShowCSS()?>
    <?$APPLICATION->ShowHeadStrings()?>
    <?$APPLICATION->ShowHeadScripts()?>
    <?
    /*$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."css-minify/all-style.min.css");*/
    $APPLICATION->SetAdditionalCSS("/bitrix/css/main/bootstrap.css?1");
    $APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css?1");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/colors.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/lightslider.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/my_style.css?81");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/template_styles.css?42");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/font.css?1");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/condensed.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/slider-thumbnail.css");
    ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="icon" type="image/png" href="<?= SITE_DIR ?>favicon.png"/>
    <meta name="google-site-verification" content="BCM97-egJjt7AxFL4yvenxHTX-zvD9zmAS23cPQfyek"/>
    <meta name="yandex-verification" content="d0661e5d7c17adab"/>
    <meta name="google-site-verification" content="nrNxXjREoXc4scWZA7HsTM-JSHKjsNpEdcjiswP67kU" />

    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '281069695668193'); // Insert your pixel ID here.
        fbq('track', 'PageView');
    </script>

    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
    <script>
        //Search
        fbq('track', 'Search', {
            search_string: 'leather sandals',
            content_ids: ['1234', '2424', '1318'],
            content_type: 'product'
        });
        //End Search
    </script>
    <script>
        //View
        fbq('track', 'ViewContent', {
            content_ids: ['1234'],
            content_type: 'product',
            value: 0.50,
            currency: 'USD'
        });
        //End View
    </script>

    <script>
        (function(d) {
            var s = d.createElement('script');
            s.defer = true;
            s.src = 'https://multisearch.io/plugin/11010';
            if (d.head) d.head.appendChild(s);
        })(document);
    </script>

</head>
<body>
<img height="1" width="1" alt="" style="display:none"
     src="https://www.facebook.com/tr?id=281069695668193&ev=PageView&noscript=1"/>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/minify/all.min.js?3"></script>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/prescription.js?5"></script>

<!-- Phone Mask -->
<script>
    $(document).ready(function () {
        jQuery(function ($) {
            $("#date").mask("99/99/9999", {placeholder: "mm/dd/yyyy"});
            $("#phone").mask("+38 (999) 999-99-99");
            $("#222").mask("(999) 999-99-99");
            $("#tin").mask("99-9999999");
            $("#ssn").mask("999-99-9999");
        });
    });
</script>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/minify/jquery.scrollbox.min.js"></script>

<!-- Google analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-88170340-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-88170340-1');
</script>

<div id="wrap"></div>
<!-- Popup баннер -->
<div id="window-banner">
    <span id="close" class="close_prscr close banner-close">x</span>
    <div id="banner-popup" class="top-banner-popup">
        <a href="https://online-apteka.com.ua/action/">
            <img class="lazy" data-original="<?= SITE_TEMPLATE_PATH ?>/images/slider-header/popup-banner.webp" alt=""/>
        </a>
    </div>
</div>
<div class="prescription-block">
    <div class="pres-description"><?= GetMessage("PRESCRIPTION_DESC") ?></div>
    <span class="mc-button closes"></span>
    <div class="prescription-block-chld"></div>
</div>
<!-- <div id="window">
    <span id="close" class="close">x</span>
    <span class="popup-txt"><span class="top-popup-txt">Приносим свои искренные извинения!</span> Все ваши заказы будут обработаны с задержкой, но не один заказ не останется без внимания.</span>
</div> -->
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<? $APPLICATION->IncludeComponent("bitrix:eshop.banner", "", array()); ?>
<div class="bx-wrapper" id="bx_eshop_wrap">
    <header class="bx-header" <?php echo Tools::isMainPage($_SERVER['REQUEST_URI']) ? "itemscope itemtype='http://schema.org/Organization'" : ""?>>
        <?php
        if ($GLOBALS["APPLICATION"]->GetCurPage() == "/"):?>
            <h1 class="visually-hidden ">Онлайн аптека</h1>
        <?php endif; ?>

        <div class="bx-header-section container">
            <div class="row" id="first_row">
                <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                    <ul class="nav nav-pills hidden-xs">
                        <li><a href="/about/delivery/">Оплата и доставка</a></li>
                        <li><a href="/about/guaranty/">Гарантия качества</a></li>
                        <li><a href="/contact/">Контакты</a></li>
                        <li><a href="/about/">О нас</a></li>
                        <li><a href="/help/">Вопросы и ответы</a></li>
                    </ul>
                    <!-- Вывод опроса -->
                    <div id="result-question" >
                        <span id="vote-question" class="vote-ask-question">Хотите поучаствовать в опросе?</span>
                    </div>
                    <?
                    //}?>
                    <?

//                    $APPLICATION->IncludeComponent(
//                        "bitrix:voting.current",
//                        "voice",
//                        array(
//                            "CHANNEL_SID" => "ANKETA",
//                            "VOTE_ID" => "",
//                            "VOTE_ALL_RESULTS" => "Y",
//                            "CACHE_TYPE" => "A",
//                            "CACHE_TIME" => "3600",
//                            "AJAX_MODE" => "Y",
//                            "AJAX_OPTION_JUMP" => "N",
//                            "AJAX_OPTION_STYLE" => "Y",
//                            "AJAX_OPTION_HISTORY" => "Y",
//                            "COMPONENT_TEMPLATE" => "voice",
//                            "AJAX_OPTION_ADDITIONAL" => "ajaxVotingId"
//                        ),
//                        false
//                    );

                    ?>
                    <!-- / Вывод опроса -->
                    <div class="row" id="mr_top">
                        <div class="col-logo">
                            <div class="bx-logo" itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                                <?php $a = $_SERVER['REQUEST_URI'];
                                $zzzz = explode('?', $a);
                                if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') { ?>
                                    <div class="bx-logo-block hidden-xs">
                                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/company_logo_header.php"), false); ?>
                                    </div>
                                <? } else {
                                    ?>
                                    <a class="bx-logo-block hidden-xs" href="<?= SITE_DIR ?>">
                                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/company_logo_header.php"), false); ?>
                                    </a>
                                <? } ?>
                                <? if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') { ?>
                                    <div class="bx-logo-block hidden-lg hidden-md hidden-sm text-center">
                                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/company_logo_mobile.php"), false); ?>
                                    </div>
                                <? } else { ?>
                                    <a class="bx-logo-block hidden-lg hidden-md hidden-sm text-center"
                                       href="<?= SITE_DIR ?>">
                                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/company_logo_mobile.php"), false); ?>
                                    </a>
                                <? } ?>

                            </div>
                            <meta itemprop="url" content="http://online-apteka.com.ua/include/mc-logo.png">
                        </div>
                        <div class="col-xs-5 col-lg-3  col-md-3 col-sm-5 request">
                            <span class="bold">
                                <a href="tel:0 800 50 52 53">0 800 50 52 53</a>
                              </span>
                            <a href="/catalog/" class="catalog-button hidden-sm hidden-md hidden-lg">Каталог</a>
                            <div class="wrap-button-feedback hidden-xs">
                                <button class="mc-button phone request_a_call"><i class="fa fa-phone"></i>Заказать
                                    звонок
                                </button>
                            </div>
                        </div>
                        <div class="col-xs-2 hidden-lg hidden-md hidden-sm mobile-basket clearfix">
                            <? $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "", array(
                                "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                                "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                                "SHOW_PERSONAL_LINK" => "N",
                                "SHOW_NUM_PRODUCTS" => "Y",
                                "SHOW_TOTAL_PRICE" => "Y",
                                "SHOW_PRODUCTS" => "N",
                                "POSITION_FIXED" => "Y",
                                "POSITION_HORIZONTAL" => "center",
                                "POSITION_VERTICAL" => "bottom",
                                "SHOW_AUTHOR" => "Y",
                                "PATH_TO_REGISTER" => SITE_DIR . "login/",
                                "PATH_TO_PROFILE" => SITE_DIR . "personal/"
                            ),
                                false,
                                array()
                            ); ?>
                        </div>
                        <div class="col-xs-2 hidden-lg hidden-md hidden-sm col-right">
                            <input type="checkbox" id="navbar"/>
                            <label for="navbar">
                                <span class="icon-bar top-bar"></span>
                                <span class="icon-bar middle-bar"></span>
                                <span class="icon-bar bottom-bar"></span>
                            </label>
                            <div class="scroller-wrapper-nav-pills">
                                <div class="wrapper-nav-pills">
                                    <div class="logo">
                                        <?php if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] != '/') : ?>
                                            <a href="/">
                                                <img class="lazy" data-original="https://online-apteka.com.ua/include/logo-mobile.png"
                                                     alt="Онлайн аптека"/>
                                            </a>
                                        <?php else: ?>
                                            <img data-original="https://online-apteka.com.ua/include/logo-mobile.png"
                                                 class="img-mobile-main lazy"
                                                 alt="Онлайн аптека"/>
                                        <?php endif ?>
                                    </div>
                                    <ul class="nav nav-pills">
                                        <li><i class="icon-1"></i><a class="cart-popup-a" href="/personal/cart/">Корзина</a></li>
                                        <li class="divider"><i class="icon-2"></i><a class="request_a_call" href="#">Заказать
                                                звонок</a></li>
                                        <li><i class="icon-3"></i><a href="/login/">Войти</a></li>
                                        <li><i class="icon-4"></i><a onclick="showDiv();" href="#">Проверить статус заказа</a>
                                        </li>
                                        <li class="divider"><i class="icon-reg-5"></i><a href="/about/delivery/">Регистрация</a>
                                        </li>
                                        <li><i class="icon-5"></i><a href="/about/delivery/">Оплата и доставка</a></li>
                                        <li><i class="icon-6"></i><a href="/about/guaranty/">Гарантия качества</a></li>
                                        <li><i class="icon-7"></i><a href="/contact/">Контакты</a></li>
                                        <li class="divider"><i class="icon-8"></i><a href="/help/">Вопросы и ответы</a>
                                        </li>
                                        <li><i class="icon-9"></i><a href="/catalog/">Каталог</a></li>
                                        <li><i class="icon-10"></i><a href="/action/">Акции</a></li>
                                        <li><i class="icon-11"></i><a href="/nashy_apteki/">Наши аптеки</a></li>
                                        <li><i class="icon-12"></i><a href="/shzurnal/">Журнал</a></li>
                                        <li><i class="icon-12"></i><a href="/kosmetika/">Косметические бренды</a></li>
                                        <li><i class="icon-12"></i><a href="/articles/">Статьи</a></li>
                                        <li><i class="icon-11"></i><a href="/aptechka/">Аптечки</a></li>
                                        <li><i class="icon-12"></i><a class="prescription" href="#"
                                                                      onclick="popup.Show();">Рецепт врача
                                                <?php if (!isset($_COOKIE['prscrptn'])) : ?>
                                                    <div class="prescription-info-prscr">
                                                        <div><?= GetMessage("PRESCRIPTION_INFO1") ?></div>
                                                        <span><?= GetMessage("PRESCRIPTION_INFO2") ?></span><span
                                                                class="close_prscr"><?= GetMessage("PRESCRIPTION_INFO3") ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="search-adapt col-xs-12 col-lg-4 col-md-5 col-sm-4">
                            <div class="bx-sidebar-block">
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:search.title",
                                    "visual",
                                    array(
                                        "NUM_CATEGORIES" => "1",
                                        "TOP_COUNT" => "5",
                                        "CHECK_DATES" => "N",
                                        "SHOW_OTHERS" => "N",
                                        "PAGE" => SITE_DIR . "catalog/",
                                        "CATEGORY_0_TITLE" => "Товары",
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
                                        "CONVERT_CURRENCY" => "Y",
                                        "COMPONENT_TEMPLATE" => "visual",
                                        "ORDER" => "date",
                                        "USE_LANGUAGE_GUESS" => "N",
                                        "PRICE_VAT_INCLUDE" => "Y",
                                        "PREVIEW_TRUNCATE_LEN" => "",
                                        "CURRENCY_ID" => "UAH"
                                    ),
                                    false
                                ); ?>
                            </div>
                        </div>
                        <div class="hidden-xs col-lg-3 col-md-5 col-sm-4" style="margin-top: 25px; display: block;">
                            <a class="mc-button phone" onclick="showDiv();" style="display: inline-block;" href="#"><i
                                        class="fa fa-phone"></i>Проверить статус заказа</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 hidden-xs">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:sale.basket.basket.line",
                        "all_include",
                        array(
                            "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                            "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                            "SHOW_PERSONAL_LINK" => "N",
                            "SHOW_NUM_PRODUCTS" => "Y",
                            "SHOW_TOTAL_PRICE" => "Y",
                            "SHOW_PRODUCTS" => "N",
                            "POSITION_FIXED" => "N",
                            "SHOW_AUTHOR" => "Y",
                            "PATH_TO_REGISTER" => "",
                            "PATH_TO_PROFILE" => SITE_DIR . "personal/",
                            "COMPONENT_TEMPLATE" => "all_include",
                            "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
                            "SHOW_EMPTY_VALUES" => "Y",
                            "HIDE_ON_BASKET_PAGES" => "Y"
                        ),
                        false
                    ); ?>
                </div>
            </div>
            <div class="row wrap_navbar hidden-xs">
                <div class="row  border_menu">
                    <div class="col-md-12 catalog_menu">
                        <!--						<a href="/banner_link_txt/shedule/">-->
                        <!--							<span class="happy-bd-mother-fucker"></span>-->
                        <!--						</a>-->
                        <ul class="ul_menu">
                            <li class="active-item">
                                <a class="link_main_menu" href="/catalog/">Каталог</a>
                                <div class="wrap_menu hidden-xs">
                                    <? $APPLICATION->IncludeComponent("bitrix:menu", "vertical_multilevel", array(
                                        "ROOT_MENU_TYPE" => "left",
                                        "MENU_CACHE_TYPE" => "A",
                                        "MENU_CACHE_TIME" => "36000000",
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "MENU_THEME" => "site",
                                        "CACHE_SELECTED_ITEMS" => "N",
                                        "MENU_CACHE_GET_VARS" => array(),
                                        "MAX_LEVEL" => "3",
                                        "CHILD_MENU_TYPE" => "left",
                                        "USE_EXT" => "Y",
                                        "DELAY" => "N",
                                        "ALLOW_MULTI_SELECT" => "N",
                                    ),
                                        false
                                    ); ?>
                                </div>
                            </li>
                            <li><a class="link_main_menu" href="/action/">Акции</a></li>
                            <li><a class="link_main_menu" href="/nashy_apteki/">Наши аптеки</a></li>
                            <li><a class="link_main_menu" target="blank" href="/shzurnal/">Журнал</a></li>
                            <li><a class="link_main_menu" href="/kosmetika/">Косметические бренды</a></li>
                            <li><a class="link_main_menu" href="/articles/">Статьи</a></li>
                            <li><a class="link_main_menu" href="/aptechka/">Аптечки</a></li>
                            <li>
                                <a class="link_main_menu prescription" href="#">Рецепт врача
                                    <?php if (!isset($_COOKIE['prscrptn'])) : ?>
                                        <div class="prescription-info-prscr">
                                            <div><?= GetMessage("PRESCRIPTION_INFO1") ?></div>
                                            <span><?= GetMessage("PRESCRIPTION_INFO2") ?></span><span
                                                    class="close_prscr"><?= GetMessage("PRESCRIPTION_INFO3") ?></span>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <!--                            <li><a class="link_main_menu" href="/forum.php">Форум</a></li>-->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row <?= stristr($_SERVER['REQUEST_URI'], '/personal/') ? 'main-banner-visible' : '' ?>"
                 id="by_step">
                <div id="carousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="7000">
                    <ol class="carousel-indicators">
                        <li data-target="#carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel" data-slide-to="1"></li>
                        <li data-target="#carousel" data-slide-to="2"></li>
                        <li data-target="#carousel" data-slide-to="3"></li>
                        <li data-target="#carousel" data-slide-to="4"></li>
<!--                        <li data-target="#carousel" data-slide-to="5"></li>-->
<!--                        <li data-target="#carousel" data-slide-to="6"></li>-->
<!--                        <li data-target="#carousel" data-slide-to="7"></li>-->
<!--                        <li data-target="#carousel" data-slide-to="8"></li>-->
<!--                        <li data-target="#carousel" data-slide-to="9"></li>-->
                    </ol>
                    <div class="carousel-inner">

                        <div class="item active">
                            <img class="lazy" data-original="<?= SITE_TEMPLATE_PATH ?>/images/slider-header/august_2020.jpg" alt="Аптекам «Мед-сервіс» 25 років знижки -30%"/>
                        </div>

                        <div class="item">
                            <a href="/about/delivery/" target="_blank">
                                <img class="lazy" data-original="<?= SITE_TEMPLATE_PATH ?>/images/slider-header/delivery.webp" alt=""/>
                            </a>
                        </div>

<!--                        <div class="item">-->
<!--                            <img class="lazy" data-original="--><?//= SITE_TEMPLATE_PATH ?><!--/images/slider-header/strepsils.jpg?4" alt=""/>-->
<!--                        </div>-->
<!---->
<!--                        <div class="item">-->
<!--                            <img class="lazy" data-original="--><?//= SITE_TEMPLATE_PATH ?><!--/images/slider-header/april_2020_1.webp?4" alt=""/>-->
<!--                        </div>-->
<!---->
<!--                        <div class="item">-->
<!--                            <img class="lazy" data-original="--><?//= SITE_TEMPLATE_PATH ?><!--/images/slider-header/april_2020_2.webp?4" alt=""/>-->
<!--                        </div>-->
<!---->
<!--                        <div class="item">-->
<!--                            <img class="lazy" data-original="--><?//= SITE_TEMPLATE_PATH ?><!--/images/slider-header/april_2020_3.webp?4" alt=""/>-->
<!--                        </div>-->
<!---->
<!--                        <div class="item">-->
<!--                            <img class="lazy" data-original="--><?//= SITE_TEMPLATE_PATH ?><!--/images/slider-header/april_2020_4.webp?4" alt=""/>-->
<!--                        </div>-->
<!---->
<!--                        <div class="item">-->
<!--                            <img class="lazy" data-original="--><?//= SITE_TEMPLATE_PATH ?><!--/images/slider-header/april_2020_5.webp?4" alt=""/>-->
<!--                        </div>-->

                        <div class="item">
                            <img class="lazy" data-original="<?= SITE_TEMPLATE_PATH ?>/images/slider-header/august1.webp?4" alt=""/>
                        </div>

                        <div class="item">
                            <a href="#" class="prescription">
                                <img class="lazy" data-original="<?= SITE_TEMPLATE_PATH ?>/images/slider-header/recept.png" alt=""/>
                            </a>
                        </div>

                        <div class="item">
                            <!--                            <a href="/banner_link_txt/vakciny/">-->
                            <img class="lazy" data-original="<?= SITE_TEMPLATE_PATH ?>/images/slider-header/vakciny.jpg" alt=""/>
                            <!--                            </a>-->
                        </div>

                    </div>
                    <a class="carousel-control left" href="#carousel" data-slide="prev">&lsaquo;</a>
                    <a class="carousel-control right" href="#carousel" data-slide="next">&rsaquo;</a>
                </div>
            </div>
        </div>
    </header>
    <? $a = $_SERVER['REQUEST_URI'];
    $zzzz = explode('?', $a);
    if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') {
    ?>
    <div class="workarea"><?
        }else{
        ?>
        <div class="workarea">
            <style>
                .workarea {
                    margin-top: -20px;
                }
            </style><?
            } ?>
            <div class="container bx-content-seection">
                <? /*$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(
				"START_FROM" => "0",
				"PATH" => "",
				"SITE_ID" => "-",
				),
				false
			);*/ ?>


                <? /*<input id="voting-checked" type="checkbox" />
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

					<?$APPLICATION->IncludeComponent("bitrix:main.include", "voting", Array(
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
				</div>
			</div>*/ ?>
                <?php
                //				$objFindTools = new CIBlockFindTools();
                //				//получаем названия страницы
                //				$dir = $APPLICATION->GetCurPage();
                //				//убираем слеши
                //				$code = trim($dir, '/');
                //				//получаем id секции если мы находимся в секции
                //				$isSection = $objFindTools->GetSectionID(false, $code, []);
                //				//получаем id товара если мы находимся на странице товара
                //				$elementID = $objFindTools->GetElementID(false, $code);
                //				if ($isSection != 0 || $elementID != 0 || $code == 'catalog') {
                //					$isCatalogPage = true;
                //				} else {
                //					$isCatalogPage = false;
                //				}
                $isCatalogPage = preg_match("~^" . SITE_DIR . "catalog/~", $curPage);
                $isArticlePage = preg_match("~^" . SITE_DIR . "articles/~", $curPage);
                if (true === ($isCatalogPage < 1)) {
                    if (true === ($isArticlePage == 1)) {
                        ?>
                        <div id="basicTitle"><?= $APPLICATION->ShowTitle(false); ?></div><?
                    } else {
                        if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] != '/'):?><h1
                                id="basicTitle"><?= $APPLICATION->ShowTitle(false); ?></h1><? endif;
                    }
                }
                if (true === ($isCatalogPage == 1)) {
                    ?>
                    <div class="product-breadcrumbs-list">
                    <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(
                        "START_FROM" => "0",
                        "PATH" => "",
                        "SITE_ID" => "-",
                    ),
                        false
                    ); ?>
                    </div><?
                }
                if (true === ($isArticlePage == 1)) {
                    ?>
                    <div class="product-breadcrumbs-list">
                    <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(
                        "START_FROM" => "0",
                        "PATH" => "",
                        "SITE_ID" => "-",
                    ),
                        false
                    ); ?>
                    </div><?
                }
                ?>

                <? if (isset($_SERVER['REQUEST_URI']) && $zzzz['0'] == '/') { ?>
                    <a class="red_title" href="/action/">Акционные предложения от «Мед-Сервис».</a>
                <? } ?>
                <div class="row">
                    <? /*$isCatalogPage = preg_match("~^".SITE_DIR."catalog/~", $curPage);*/ ?>
                    <? //$isArticlePage = preg_match("~^".SITE_DIR."articles/~", $curPage);
                    $explodUrl = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                    $secLevArticles = count($explodUrl);
                    if (preg_match("/^\?/", end($explodUrl))) {
                        $secLevArticles = $secLevArticles - 1;
                        //unset(end($explodUrl));
                    }
                    $showGoodsAbout = -1;
                    ?>
                    <? /*if($isArticlePage==1 && $secLevArticles >1){}*/ ?>

                    <div class="bx-content <?= ($isCatalogPage ? "col-xs-12" : "col-md-9 col-sm-8") ?>">
