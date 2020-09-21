<?php

global $MESS;

$MESS['INTERVALE.PGA_SHORT_NAME'] = 'PGAMerchantPlugin';
$MESS['INTERVALE.PGA_DESCRIPTION'] = 'Модуль, позволяющий проводить платежи по картам Visa и MasterCard';

$MESS["INTERVALE.PGA_START_PAYMENT_URL"] = "Адрес запроса";
$MESS["INTERVALE.PGA_START_PAYMENT_URL_DESC"] = "для инициации платежа";

$MESS["INTERVALE.PGA_SHOP_PCID"] = "PCID Магазина";
$MESS["INTERVALE.PGA_SHOP_PCID_DESC"] = "";

$MESS["INTERVALE.PGA_ACCOUNT_PCID"] = "Account PCID";
$MESS["INTERVALE.PGA_ACCOUNT_PCID_DESC"] = "";

$MESS["INTERVALE.PGA_CERT_PATH"] = "Путь к сертификату";
$MESS["INTERVALE.PGA_CERT_PATH_DESC"] = "Укажите путь до сертификата от корня сайта.<br/> Например: \"/path/to/cert/certificate_file.crt\"";

$MESS["INTERVALE.PGA_CURRENCY"] = "Код валюты";
$MESS["INTERVALE.PGA_CURRENCY_DESC"] = "Сurrency (Рубль = 643)";

$MESS["INTERVALE.PGA_DECLINE_URL"] = "Адрес при ошибке оплаты";
$MESS["INTERVALE.PGA_DECLINE_URL_DESC"] = "URL (на веб-сайте продавца) для перенаправления плательщика при неуспешном платеже";

$MESS["INTERVALE.PGA_SUCCESS_URL"] = "Адрес при успешной оплате";
$MESS["INTERVALE.PGA_SUCCESS_URL_DESC"] = "URL (на веб-сайте продавца) для перенаправления плательщика при успешном платеже";

$MESS["INTERVALE.PGA_ORDER_ID"] = "Номер заказа";
$MESS["INTERVALE.PGA_ORDER_ID_DESC"] = "Номер заказа в вашем Интернет-магазине";

/* ================================ */

$MESS["INTERVALE.PGA_CB_URL"] = "Адрес для уведомлений об оплате";
$MESS["INTERVALE.PGA_CB_URL_DESC"] = "URL скрипта (на веб-сайте продавца) обрабатывающего оповещения о результате платежа";


$MESS["INTERVALE.PGA_LANG_CODE"] = "Язык диалога с пользователем";
$MESS["INTERVALE.PGA_LANG_CODE_DESC"] = "Двухсимвольный код в соответствии со стандартом ISO 639.";

$MESS["INTERVALE.PGA_PAGE_ID"] = "Идентификатор платежной страницы";
$MESS["INTERVALE.PGA_PAGE_ID_DESC"] = "Строка длиной 32 символа. Если параметр не указан, используется платежная страница по умолчанию.";