//определение переменных
var script = '/check_order_fast/obrabotka.php';
var div;
var overlay;
var goroda;
var сity;
var $table;

/*
var curTD;
var forma;
var komerts_dir;
var apteka;
var contractor;
var statusBID;
var cartridge;
var create_date_from;
var create_date_to;
var close_date_from;
var close_date_to;
var tiket_id;
*/

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
// для работы интерфейса
function reqest(data, element){
	var xhr = new XMLHttpRequest();
	xhr.open('POST', script, true);
	xhr.onreadystatechange = function() {
	
		if (xhr.readyState != 4) return;
		if (xhr.status != 200) {
			console.log(xhr.status + ': ' + xhr.statusText);
			element.innerHTML = xhr.status + ': ' + xhr.statusText;
		} else {
			console.log(xhr.responseText);
			element.innerHTML = xhr.responseText;
		}
	}
	element.innerHTML =  'Загрузка данных...';
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.send(data);
}
function showDiv() {
	div.style.visibility='visible';
	div.style.opacity='1';
	overlay.style.visibility='visible';
	overlay.style.opacity='1';
}
function hideDiv() {
	div.style.visibility='hidden';
	div.style.opacity='0';
	overlay.style.visibility='hidden';
	overlay.style.opacity='0';
	//div.innerHTML='';
	clear();
}
function send() {
	phone = div.children[0].value;
	order = div.children[2].value;
	data='PH='+phone+'&ORD='+order;
	reqest(data, div);
}
function clear() {/*
	var phone = document.createElement('input');
	var order = document.createElement('input');
	var order = document.createElement('button');
	phone.id = 'phone';
	order.id = 'order';
	div.appendChild(phone);
	div.appendChild(order);*/
	$html = '<input id="phone" type="tel" placeholder="Введите Ваш телефон" value=""><br/>';
	$html += '<input id="order" type="text" placeholder="Введите номер Вашего заказа" value=""><br/>';
	$html += '<button onclick="send();">Проверить</button>';
	div.innerHTML = $html;
}
onReady(function(){
	//инициализируем переменных
	div = document.getElementById('in');
	overlay = document.getElementById('overlay');
});
