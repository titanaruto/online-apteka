<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

CModule::IncludeModule('sale');

if (!class_exists('PgaProcessor') && file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/payment/pgamerchant/PgaProcessor.php')) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/payment/pgamerchant/PgaProcessor.php');
}

// Ищем заказ
$arOrder = PgaProcessor::findOrder((int)$_GET['o_order_id'], $_GET);

// Все в пордке. Подключаем обработчик коллбэка.
$APPLICATION->IncludeComponent(
    "bitrix:sale.order.payment.receive",
    "",
    Array(
        "PAY_SYSTEM_ID" => $arOrder["PAY_SYSTEM_ID"],
        "PERSON_TYPE_ID" => $arOrder["PERSON_TYPE_ID"]
    ),
    false
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');