<?php
error_reporting(E_ALL);
ini_set("display_errors",1);

// real DB-localhost settings
$host="localhost"; $user="apteka"; $pwd="9yfBkLz5ThXCL";$db="apteka";

$db_link=mysqli_connect($host, $user, $pwd) or die("Could not connect to MYSQL.");
mysqli_select_db($db_link,$db) or die("Could not select $db.");
mysqli_query($db_link,"set names utf8;");

// real DB settings to bitrix
$host_bx = "tranzit.med-service.dp.ua";$user_bx = "bitrix";$pwd_bx = "123qweasd";$db_bx = "report_ms_portal";

//коннект и пересоздание таблицы
$db_bx_link=mysqli_connect($host_bx, $user_bx, $pwd_bx) or die("Could not connect to MYSQL.");
mysqli_select_db($db_bx_link,$db_bx) or die("Could not select $db_bx.");
mysqli_query($db_bx_link,"set names utf8;");
mysqli_query($db_bx_link,"DROP TABLE tvr_pict;");
mysqli_query($db_bx_link,"CREATE TABLE IF NOT EXISTS `tvr_pict` (
  `XML_ID` varchar(255) NOT NULL,
  `PREVIEW_PICTURE` int(18) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

	//выбираем нужные данные
	$sql="SELECT XML_ID, PREVIEW_PICTURE FROM `b_iblock_element` where NOT PREVIEW_PICTURE IS NULL ";
	$q=mysqli_query($db_link, $sql);
		
	// перебираем и вставляем нужные данные
	$i=0;
	while($row = mysqli_fetch_array($q)) {
		$sql_bx="insert into tvr_pict values('".$row['XML_ID']."','".$row['PREVIEW_PICTURE']."');";
	        mysqli_query($db_bx_link, $sql_bx);
		$i++;
	}


echo $i."-ok";

?>
