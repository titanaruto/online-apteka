<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("voting");?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "voting", Array(
		"AREA_FILE_SHOW" => "sect",	
		"AREA_FILE_SUFFIX" => "sidebar",	
		"AREA_FILE_RECURSIVE" => "Y",	
		"EDIT_MODE" => "html"
	),
		false,
		array(
		"HIDE_ICONS" => "N"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>