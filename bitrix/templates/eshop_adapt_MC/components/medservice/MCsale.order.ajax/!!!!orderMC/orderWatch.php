<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
/* */
if (!empty($_POST['odd']) && empty($_POST['even'])) {
	$aa = str_split($_POST['odd']);
	if(count($aa)!= 24) {
		echo '0';
		die();
	}
	$card = preg_replace("/[^0-9\(\)]/", '', $_POST['odd']);
	$sql = "SELECT `VALUE` FROM `ord_discoutncard_odd` WHERE `CARDNUMBER`='$card';";
}
if (!empty($_POST['even']) && empty($_POST['odd'])) {
	$aa = str_split($_POST['even']);
	if(count($aa)!= 24) {
		echo '0';
		die();
	}
	$card = preg_replace("/[^0-9\(\)]/", '', $_POST['even']);
	$sql = "SELECT `VALUE` FROM `ord_discoutncard_even` WHERE `CARDNUMBER`='$card';";
}
$qwery = new qwery;
$value = $qwery->frqr($sql);
$value= $value/100;
unset($qwery);
echo $value;

























require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
