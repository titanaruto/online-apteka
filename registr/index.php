<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?$APPLICATION->IncludeComponent("bitrix:main.register","registr",Array(
        "USER_PROPERTY_NAME" => "", 
        "SEF_MODE" => "Y", 
		"SHOW_FIELDS" => Array(		
			"NAME",
			"SECOND_NAME",
			"LAST_NAME",
			"PERSONAL_GENDER",
			"PERSONAL_BIRTHDAY",
		), 
        "REQUIRED_FIELDS" => Array(
        	"PERSONAL_GENDER",
        	"NAME",
        	"SECOND_NAME",
        	"LAST_NAME",
        	"PERSONAL_BIRTHDAY",
        	
        ), 
        "AUTH" => "Y", 
        "USE_BACKURL" => "Y", 
        "SUCCESS_PAGE" => "/", 
        "SET_TITLE" => "Y", 
        "USER_PROPERTY" => Array(), 
        "SEF_FOLDER" => "/", 
        "VARIABLE_ALIASES" => Array()
    )
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>