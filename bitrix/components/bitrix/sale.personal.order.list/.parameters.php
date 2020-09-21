<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arYesNo = Array(
	"Y" => GetMessage("SPOL_DESC_YES"),
	"N" => GetMessage("SPOL_DESC_NO"),
);

$arComponentParameters = Array(
	"PARAMETERS" => Array(
		"PATH_TO_DETAIL" => Array(
			"NAME" => GetMessage("SPOL_PATH_TO_DETAIL"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"PATH_TO_COPY" => Array(
			"NAME" => GetMessage("SPOL_PATH_TO_COPY"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"PATH_TO_CANCEL" => Array(
			"NAME" => GetMessage("SPOL_PATH_TO_CANCEL"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"PATH_TO_PAYMENT" => Array(
			"NAME" => GetMessage("SPOD_PATH_TO_PAYMENT"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "payment.php",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"PATH_TO_BASKET" => Array(
			"NAME" => GetMessage("SPOL_PATH_TO_BASKET"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"ORDERS_PER_PAGE" => Array(
			"NAME" => GetMessage("SPOL_ORDERS_PER_PAGE"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => 20,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),

		"ID" => Array(
			"NAME" => GetMessage("SPOL_ID"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "={\$ID}",
			"COLS" => 25,
		),
		"SET_TITLE" => Array(),
		"SAVE_IN_SESSION" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("SPOL_SAVE_IN_SESSION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"NAV_TEMPLATE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("SPOL_NAV_TEMPLATE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),

		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("SPOL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	)
);

if(CModule::IncludeModule("sale"))
{
	// "historic" statuses
	$dbStat = CSaleStatus::GetList(array('sort' => 'asc'), array('LID' => LANGUAGE_ID), false, false, array('ID', 'NAME'));
	$statList = array();
	while ($item = $dbStat->Fetch())
		$statList[$item['ID']] = $item['NAME'];

	$arComponentParameters['PARAMETERS']['HISTORIC_STATUSES'] = array(
		"NAME" => GetMessage("SPOL_HISTORIC_STATUSES"),
		"TYPE" => "LIST",
		"VALUES" => $statList,
		"MULTIPLE" => "Y",
		"DEFAULT" => "F",
		"PARENT" => "ADDITIONAL_SETTINGS",
	);

}

if(CModule::IncludeModule("iblock"))
	$arComponentParameters["PARAMETERS"]["ACTIVE_DATE_FORMAT"] = CIBlockParameters::GetDateFormat(GetMessage("SPOL_ACTIVE_DATE_FORMAT"), "VISUAL");
?>