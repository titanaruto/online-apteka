<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?>
<!-- Google Code for &#1047;&#1040;&#1050;&#1040;&#1047; &#1085;&#1072; &#1089;&#1072;&#1081;&#1090;&#1077; online-apteka.com.ua Conversion Page -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 816844209;
    var google_conversion_label = "jEpzCL7ypn4QsZvAhQM";
    var google_remarketing_only = false;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/816844209/?label=jEpzCL7ypn4QsZvAhQM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
<?$APPLICATION->IncludeComponent(
	"medservice:MCsale.order.ajax", 
	"orderMC", 
	array(
		"ALLOW_AUTO_REGISTER" => "Y",
		"ALLOW_NEW_PROFILE" => "N",
		"COMPONENT_TEMPLATE" => "orderMC",
		"COUNT_DELIVERY_TAX" => "N",
		"DELIVERY_NO_AJAX" => "Y",
		"DELIVERY_NO_SESSION" => "Y",
		"DELIVERY_TO_PAYSYSTEM" => "p2d",
		"DISABLE_BASKET_REDIRECT" => "Y",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"PATH_TO_AUTH" => "/auth/",
		"PATH_TO_BASKET" => "/personal/cart/",
		"PATH_TO_PAYMENT" => "/personal/order/payment/",
		"PATH_TO_PERSONAL" => "/personal/order/",
		"PAY_FROM_ACCOUNT" => "N",
		"PRODUCT_COLUMNS" => array(
		),
		"PROP_1" => array(
			0 => "5",
			1 => "7",
		),
		"SEND_NEW_USER_NOTIFY" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_PAYMENT_SERVICES_NAMES" => "Y",
		"SHOW_STORES_IMAGES" => "Y",
		"TEMPLATE_LOCATION" => ".default",
		"USE_PREPAYMENT" => "N"
	),
	false
);

/* стандартный компонент
	$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax",
	"",
	Array(
		"ALLOW_AUTO_REGISTER" => "Y",
		"COUNT_DELIVERY_TAX" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"DELIVERY2PAY_SYSTEM" => Array(),
		"DELIVERY_NO_AJAX" => "N",
		"DELIVERY_NO_SESSION" => "Y",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"PATH_TO_BASKET" => "/personal/cart/",
		"PATH_TO_ORDER" => "/personal/order/make/",
		"PATH_TO_PAYMENT" => "/personal/order/payment/",
		"PATH_TO_PERSONAL" => "/personal/order/",
		"PAY_FROM_ACCOUNT" => "Y",
		"PROP_1" => array(),
		"SEND_NEW_USER_NOTIFY" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_ACCOUNT_NUMBER" => "Y",
		"TEMPLATE_LOCATION" => "popup"
	)
);
*/
?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
