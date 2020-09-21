<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подписка");
?><?$APPLICATION->IncludeComponent("bitrix:subscribe.edit", "template1", Array(
	"COMPONENT_TEMPLATE" => ".default",
		"SHOW_HIDDEN" => "N",	// Показать скрытые рубрики подписки
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "3600",	// Время кеширования (сек.)
		"ALLOW_ANONYMOUS" => "N",	// Разрешить анонимную подписку
		"SHOW_AUTH_LINKS" => "N",	// Показывать ссылки на авторизацию при анонимной подписке
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>