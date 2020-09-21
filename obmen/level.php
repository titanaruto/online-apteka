<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");?>
<?

$arFilter = array(
	'IBLOCK_ID' => intval(2),
	'>DEPTH_LEVEL' => 3
);
$CIBlockSection = new CIBlockSection;
$rsSections = $CIBlockSection->GetList(array('ID' => 'ASC'), $arFilter);
$i=1;
while ($arSection = $rsSections->Fetch()){
	echo $i.'--'.$arSection['NAME'].' - '.$arSection['XML_ID'].' - '.$arSection['DEPTH_LEVEL'].'<br/>';
	$i++;
}
echo 'ВСЕГО ОШИБОК: '.$i;
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
