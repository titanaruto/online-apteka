<?$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/online-apteka.com.ua";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("prof"); ?>
<?
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
$db_sales = CSaleOrderUserProps::GetList(
		array("USER_ID" => "ASC"),
		array(">USER_ID" => 0)
	);
$userSProfDouble = array();
while ($ar_sales = $db_sales->Fetch()){
	$userSProfDouble[$ar_sales['USER_ID']][] = $ar_sales;
}
foreach ($userSProfDouble as $pof){
	$a = count($pof);
	if($a >1){
		pre($pof);
		//CSaleOrderUserProps::Delete($pof[0]['ID']);
	}
}
//
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
