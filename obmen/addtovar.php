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

set_time_limit(60);

if(!CModule::IncludeModule("iblock")){
	echo 'Модуль Инфоблоков не подключен!';
	$this->__destruct();
}
if(!Cmodule::IncludeModule('catalog')){
	echo 'Модуль торгового каталога не подключен!';
	$this->__destruct();
}
if(!Cmodule::IncludeModule('sale')){
	echo 'Модуль магазина не подключен!';
	$this->__destruct();
}
$CSaleBasket = new CSaleBasket;

/*$filds = array( 
"PRODUCT_ID" => , 
"FUSER_ID" => 10, 
"PRICE" => 80.00, 
"CURRENCY" => 'UAH', 
"QUANTITY" => 100, 
"LID" => 's1', 
"DELAY" => "N", 
"CAN_BUY" => "Y", 
"NAME" => '5 дней контроль массы тела капсулы №60', 
"DETAIL_PAGE_URL" => '/catalog/tabletki-sposobstvuyushchie-pokhudeniyu/5-dney-kontrol-massy-tela-kapsuly-60/', 
"PRODUCT_XML_ID" => 'М_789', 
"ORDER_ID" => 5915, 
); */

	$filds = array(
	'PRODUCT_ID' => 34481,
    'FUSER_ID' => 10,
    'PRICE' => 80.00,
    'CURRENCY' => 'UAH',
    'QUANTITY' => 2,
    'LID' => 's1',
    'DELAY' => 'N',
    'CAN_BUY' => 'Y',
    'NAME' => 'Линекс капс №16',
    'DETAIL_PAGE_URL' => '/catalog/antidiareynye-preparaty-sredstva-primenyaemye-dlya-lecheniya-infektsionno-vospalitelnykh-zabolevani/lineks-kaps-16/',
    'PRODUCT_XML_ID' => 'М_789',
    'ORDER_ID' => 6070
);
$zzz = $CSaleBasket->Add($filds); 
$CSaleBasket->OrderBasket(intval(6070),10, 's1');



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
