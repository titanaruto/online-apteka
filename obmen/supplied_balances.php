<?php
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/online-apteka.com.ua";
//$_SERVER["DOCUMENT_ROOT"] = "/var/www/apteka.local/public";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("catalog");

$obmen = new obmenGoods();

$balances = $obmen->getBalancesFromTransitDb();

$obmen->truncateLeftoversProducts();

if ($balances > 0) {
    $obmen->delBalancesFromTransitDb();
    foreach ($balances as $item) {
        $obmen->insertBalancesToDb($item["EXTKEY"], $item["LAST_AVAILABILITY"]);
    }
}

unset($obmen);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
