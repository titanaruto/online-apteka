<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent(
	"bitrix:eshop.socnet.links", 
	"big_squares", 
	array(
		"FACEBOOK" => "https://www.facebook.com/medserviceua",
		"TWITTER" => "https://twitter.com/medserviceua",
		"GOOGLE" => "",
		"INSTAGRAM" => "https://www.instagram.com/medserviceua/",
		"COMPONENT_TEMPLATE" => "big_squares",
		"VKONTAKTE" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false,
	array(
		"HIDE_ICONS" => "N"
	)
);?>