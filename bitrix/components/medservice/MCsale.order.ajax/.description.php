<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => 'Аякс заказ кастомизированный МС',
	"DESCRIPTION" => 'Заваз с оплатой с внутреннего счета покупателя',
	"ICON" => "/images/sale_order_full.gif",
	"PATH" => array(
		"ID" => "e-store-MC",
		"CHILD" => array(
			"ID" => "sale_order_MC",
			"NAME" => 'MC-order-ajax'
		)
	),
);
?>