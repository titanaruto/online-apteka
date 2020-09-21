<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");?>
<?/*
<style>
	#in{
		-webkit-border-radius: 20px 5px;
		-moz-border-radius: 20px 5px;
		border-radius: 20px 5px;
		-webkit-box-shadow: 0 1px 2px 2px #cecece;
		-moz-box-shadow: 0 1px 2px 2px #cecece;
		box-shadow: 0 1px 2px 2px #cecece;
	}
	#phone,
	#order{
		
	    width: 100%;
	    margin: 0 auto;
	    display: block;
	    padding: 6px 12px;
	    font-size: 25px;
	    text-align: center;
	    color: #a9a9a9;
	    background-color: #f8fafc; 
	    outline: none;
	    cursor: pointer;
	    padding: 0 0 0 20px;
	    height: 40px;
	    border: 2px solid #dde4ea;
	    border-radius: 5px;
	}
	button#Request_a_call{
		border: 0;
	    background: #ed1c24;
	    color: #fff;
	    border-radius: 5px 5px 5px 0;
	    padding: 4px 11px;
	    margin-top: 10px;
	    cursor: pointer;
	    text-align: center;
	}
</style>
<?
echo '<script type="text/javascript">';
include_once 'script.js';
echo '</script>';
echo '<div id="ordcheck" onclick="showDiv()">Проверить заказ</div>';
echo '<div id="overlay" onclick="hideDiv()"
						style="
						visibility:hidden;
						opacity: 0;
						position: fixed;
						width: 100%;
						height: 100%;
						background: RGBA(0,0,0,0.5);
						top: 0%;
						left: 0%;
						right: 0%;
						bottom: 0%;
						z-index: 25000;">
	 </div>';
echo '<div id="in" style="
						  visibility:hidden;
						  opacity: 0;
						  position: fixed;
						  width: 400px;
						  background: #fff;
						  top: 25%;
						  left: 50%;
						  right: 50%;
						  z-index: 50000;
						  margin: 0 -200px 0 -200px;
						  padding: 30px 20px;
						  -webkit-transition: all .2s in-ease;
						  -moz-transition: all .2s in-ease;
						  -ms-transition: all .2s in-ease;
						  -o-transition: all .2s in-ease;
						  transition: all .2s in-ease;">
			
	<input id="phone" type="text" value=""><br/>
	<input id="order" type="text" value=""><br/>
			
	<button id="Request_a_call" onclick="send();">Проверить статус заказа</button>
</div>';
CModule::IncludeModule("sale");
$CSaleOrder = new CSaleOrder;
$arBOrder = $CSaleOrder->GetByID(intval(31));
if (count($arBOrder) > 0) {
	//pre($arBOrder);
	$CSaleOrderPropsValue = new CSaleOrderPropsValue;
	$arSelectFields = array('*');
	$arFilter = array('ORDER_ID' => '31');
	$arFilter['ORDER_PROPS_ID'] = 3;
	$dbProps_order = $CSaleOrderPropsValue->GetList(
		array('ID'=>'DESC'),
		$arFilter,
		false,
		false,
		$arSelectFields
	);
	if ($props_order = $dbProps_order->Fetch()){
		//pre($props_order);
	}
}
?>
	*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
