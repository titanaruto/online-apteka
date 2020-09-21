<?$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/online-apteka.com.ua";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
/* */

//set_time_limit(1000);

// Обмен каталогов
$obmen = new obmenCatalog();
$catalog =$obmen->doObmenCatalogFromBD();
unset($obmen);
/* */



// Обмен товаров
$obmenG = new obmenGoods();
$goods = $obmenG->implementationChangeGoods();
unset($obmenG);
/* */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
