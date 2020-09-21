<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("Оплата заказа");
/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
/* */


global $USER;
if (true===$USER->IsAuthorized()){
	$regexp = '/^[0-9]{3,}$/';
	$a = preg_match($regexp, $_GET['ORDER_ID'], $match);
	if($a > 0){
		Cmodule::IncludeModule('sale');
		$arOrder = CSaleOrder::GetByID($_GET['ORDER_ID']);
		$APPLICATION->IncludeComponent(
			"bitrix:sale.order.payment",
			"",Array()
		);
	?>
	<script type="text/javascript">
		function bindReady(handler){
		var called = false;
		function ready() { // (1)
			if (called) return;
			called = true;
			handler();
		}
		if ( document.addEventListener ){
			document.addEventListener( "DOMContentLoaded", function(){
				ready();
			}, false );
		} else if ( document.attachEvent ) {
			if ( document.documentElement.doScroll && window == window.top ){
				function tryScroll(){
					if (called) return;
					if (!document.body) return;
					try {
						document.documentElement.doScroll("left")
						ready();
					} catch(e) {
						setTimeout(tryScroll, 0);
					}
				}
				tryScroll();
			}
			document.attachEvent("onreadystatechange", function(){
				if ( document.readyState === "complete" ){
					ready();
				}
			});
		}
		if (window.addEventListener){
			window.addEventListener('load', ready, false);
		}else if (window.attachEvent){
			window.attachEvent('onload', ready);
		}
	}
	readyList = [];
	function onReady(handler) {
		if (!readyList.length) {
			bindReady(function() {
				for(var i=0; i<readyList.length; i++) {
					readyList[i]();
				}
			})
		}
		readyList.push(handler);
	}
	onReady(function(){
		var frame = document.getElementById('frame');
		frame.style.display='none';
		var body = frame.parentNode;
		var div = body.childNodes[0];
		div.style.display='none';
		//div.style.width='130px';
		div.style.height='0px';
		div.style.float='right';
		var insInp =div.childNodes[1].childNodes[15].childNodes[1];
		insInp.setAttribute('disabled','disabled');

		var confirm = document.getElementById('confirm').onclick = function() {
			if( this.checked ) {
				insInp.removeAttribute("disabled", false);
			} else {
				insInp.setAttribute("disabled", true);
			}
		}

		var confirm = document.getElementById('confirm');
		confirm.style.display='none';
		var button = document.getElementById('button');
		button.style.display='none';
		document.body.insertBefore(button, document.body.firstChild);
		document.body.insertBefore(confirm, document.body.firstChild);
		document.body.insertBefore(frame, document.body.firstChild);
		frame.style.display='';
		confirm.style.display='';
		button.style.display='';
		div.style.display='inline';
	});
	</script>
	<?/*?>

	<a href="/oferta/of<?=$arOrder[PAY_SYSTEM_ID]?>.php">Договор оферты</a><?*/?>
	<style>
	iframe{
		margin-bottom: 25px;
	}
	input#confirm:checked + label{
		-webkit-box-shadow: inset -1px 0 2px 2px #cecece;
		-moz-box-shadow: inset -1px 0 2px 2px #cecece;
		box-shadow: inset -1px 0 2px 2px #cecece;
	}
	#button{
		border: 0;
		background: #ed1c24;
		color: #fff;
		border-radius: 5px 5px 5px 0;
		padding: 4px 11px;
		margin-top: 10px;
		cursor: pointer;
	}
	input[name="sbmt"]{
		border: 0;
		background: #ed1c24;
		color: #fff;
		border-radius: 5px 5px 5px 0;
		padding: 4px 11px;
		cursor: pointer;
		margin-top: -20px;
	}
	input[name="sbmt"]:disabled{
		background: grey;
	}

	</style>
	<iframe id="frame" style="width:100%; height:90%;" src="<?php echo isset($_SERVER['HTTPS']) ? 'https' : 'http'?>://online-apteka.com.ua/oferta/of<?=$arOrder[PAY_SYSTEM_ID]?>.php"></iframe>

		<input id="confirm" name="CONFIRM" type="checkbox" value="<?=$_GET['ORDER_ID'];?>">
		<label id="button" style="display: block;" for="confirm">Я СОГЛАСЕН / СОГЛАСНА</label>
				<!--button id=""> Я СОГЛАСЕН / СОГЛАСНА </button>-->

	<?
	}
} else {
	header('Location: http://online-apteka.com.ua//login/');
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
