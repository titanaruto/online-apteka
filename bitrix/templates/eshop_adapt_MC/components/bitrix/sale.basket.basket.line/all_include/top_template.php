<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<script>
    BX.ready(function(){
        let addAnswer = new BX.PopupWindow("cart-popup-form", null, {
            content: BX('cart-popup'),
            closeIcon: {right: "20px", top: "10px"},
            titleBar: {content: BX.create("span", {html: '<b>Корзина</b>', 'props': {'className': 'access-title-bar'}})},
            zIndex: 0,
            offsetLeft: 0,
            offsetTop: 0,
            draggable: {restrict: false},
            buttons: []
        });

        let sel

        if (window.matchMedia("(max-width: 768px)").matches) {
            sel = '.bx-basket-block a'
        } else {
            sel = '.bx-basket-block:not(.clearfix) a'
        }

        $(sel).click(function(){
            setTimeout(function () {
                addAnswer.show(); // появление окна
                setTimeout(function () {
                    $("#cart-popup-form[style*='display: none']").remove()
                }, 300)
                var attr = $('body').attr('doublefunction');
                if (typeof attr == "undefined") {
                    deleteProductsFromCart()
                }
            }, 300)
            return false
        });

        $('#openCart, #openCartAction, #openCartTop').click(function(){

            setTimeout(function () {
                $('.bx-basket-block.cart-popup-block a').click()
                if (!$("body").hasAttr("doublefunction"))
                    deleteProductsFromCart()
            }, 300)
        });

        $(".popup-window-close-icon.popup-window-titlebar-close-icon").on('click', function () {
            $('body').removeAttr('doublefunction')
        })
    });
</script>

<div class="bx-hdr-profile clearfix">
	<?if ($arParams['SHOW_AUTHOR'] == 'Y'):?>
		<div class="bx-basket-block clearfix">
			<i class="fa fa-user"></i>
			<?if ($USER->IsAuthorized()):
				$name = trim($USER->GetFullName());
				if (! $name)
					$name = trim($USER->GetLogin());
				if (strlen($name) > 15)
					$name = substr($name, 0, 12).'...';
				?>
				<a href="<?=$arParams['PATH_TO_PROFILE']?>"><?=$name?></a>
				&nbsp;
				<?/*<a onclick="showDiv();" href="#">Проверить заказ</a>*/?>
				&nbsp;
				<a href="?logout=yes"><?=GetMessage('TSB1_LOGOUT')?></a>
			<?else:?>
				<a href="<?=$arParams['PATH_TO_REGISTER']?>?login=yes"><?=GetMessage('TSB1_LOGIN')?></a>
				&nbsp;
				<?/*<a onclick="showDiv();" href="#">Проверить заказ</a>*/?>
				&nbsp;
				<a href="/registr/<?/*=$arParams['PATH_TO_REGISTER']*/?>?register=yes"><?=GetMessage('TSB1_REGISTER')?></a>
			<?endif?>
		</div>
	<?endif?>
	
		<div class="bx-basket-block cart-popup-block">
			<a  href="<?=$arParams['PATH_TO_BASKET']?>">
				<div class="basket-bg"></div>
				<span class="count_goods_bask"><?=$arResult['NUM_PRODUCTS'];?></span> <?$arResult['PRODUCT(S)'] ?>

				<?if ($arParams['SHOW_NUM_PRODUCTS'] == 'Y' && ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y')):?>
					<span class="count_goods_bask"><?=$arResult['NUM_PRODUCTS'];?></span> <?$arResult['PRODUCT(S)'] ?>
				<? echo '<b>Ваша корзина</b>';?>
				<?endif?>

				<?if ($arParams['SHOW_TOTAL_PRICE'] == 'Y'):?>
					 <?if ($arParams['POSITION_FIXED'] == 'Y'):?>class="hidden-xs"<?endif?>
					<span class="total-sum">
						<?=GetMessage('TSB1_TOTAL_PRICE')?>
						<?if ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y'):?>
							<strong><?=$arResult['TOTAL_PRICE']?></strong>
						<?endif?>
					</span>
				<?endif?>
				<?if ($arParams['SHOW_PERSONAL_LINK'] == 'Y'):?>

					<span class="icon_info"></span>
					<a class="" href="<?=$arParams['PATH_TO_PERSONAL']?>"><?=GetMessage('TSB1_PERSONAL')?></a>
				<?endif?>
			</a>
		</div>

</div>

<div id="cart-popup">
    <?$APPLICATION->IncludeComponent(
        "bitrix:sale.basket.basket",
        ".default",
        array(
            "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
            "COLUMNS_LIST" => array(
                0 => "NAME",
                1 => "DISCOUNT",
                2 => "PROPS",
                3 => "DELETE",
                4 => "DELAY",
                5 => "PRICE",
                6 => "QUANTITY",
                7 => "SUM",
            ),
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "PATH_TO_ORDER" => "/personal/order/make/",
            "HIDE_COUPON" => "Y",
            "QUANTITY_FLOAT" => "N",
            "PRICE_VAT_SHOW_VALUE" => "Y",
            "SET_TITLE" => "Y",
            "AJAX_OPTION_ADDITIONAL" => "",
            "OFFERS_PROPS" => array(
                0 => "SIZES_SHOES",
                1 => "SIZES_CLOTHES",
                2 => "COLOR_REF",
            ),
            "COMPONENT_TEMPLATE" => ".default",
            "USE_PREPAYMENT" => "N",
            "AUTO_CALCULATION" => "N",
            "ACTION_VARIABLE" => "basketAction",
            "USE_GIFTS" => "N",
            "CORRECT_RATIO" => "N"
        ),
        false
    );?>
</div>
