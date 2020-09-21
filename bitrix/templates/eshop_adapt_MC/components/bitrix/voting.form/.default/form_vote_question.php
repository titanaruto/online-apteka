<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//echo 'hello';
//die;
$APPLICATION->RestartBuffer();
$APPLICATION->ShowHeadScripts();
$APPLICATION->ShowCSS();
?>
<?

                    $APPLICATION->IncludeComponent(
						"bitrix:voting.current",
						"voice",
						array(
							"CHANNEL_SID" => "ANKETA",
							"VOTE_ID" => "",
							"VOTE_ALL_RESULTS" => "Y",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "3600",
							"AJAX_MODE" => "Y",
							"AJAX_OPTION_JUMP" => "N",
							"AJAX_OPTION_STYLE" => "Y",
							"AJAX_OPTION_HISTORY" => "Y",
							"COMPONENT_TEMPLATE" => "voice",
							"AJAX_OPTION_ADDITIONAL" => "ajaxVotingId"
						),
						false
					);

?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>