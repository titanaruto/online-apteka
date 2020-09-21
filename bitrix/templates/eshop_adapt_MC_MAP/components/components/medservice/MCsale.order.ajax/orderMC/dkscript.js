var inp;
var tbl;
var tord;
var triger = false;
var $discount;
var $disc;
recalculate = document.createElement('a');
recalculate.innerHTML = 'Пересчитать';
recalculate.className = "link_nexts";
recalculate.style.float = "right";

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
// изменения в инфоблоки
function reqest(data){ //, $res
	var xhr = new XMLHttpRequest();
	xhr.open('POST', $script, true);
	//element.innerHTML =  'Загрузка данных...';
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.send(data);
	xhr.onreadystatechange = function() {
		if (xhr.readyState != 4) return;
		if (xhr.status != 200) {
			console.log(xhr.status + ': ' + xhr.statusText);
			//element.innerHTML = xhr.status + ': ' + xhr.statusText;
		} else {
			console.log(xhr.responseText);
			callbackReqest(xhr.responseText);
		}
	}
}
function callbackReqest(text) {
	text= text*1;
	if(text>0){
		triger=false;
		calculateOrderItemDiscountPrice(text);
	} else {
		triger == true;
		calculateOrderItemClearPrice();
	}
}
function checkDK(inp){
	if(inp.value ==''){
		calculateOrderPrice();
	} else {
		regexp = /^\({1,1}[0-9]{2,2}\){1,1}[0-9]{20,20}/gim;
		var dk = regexp.exec(inp.value);
		if(dk==null) {
			//alert('Не корректное значение Дисконтной карты, МАЛО цифр 1111111!');
			inp.style.border = "1px solid red";
		} else {
			if(inp.value.length > 24) {
				//alert('Не корректное значение Дисконтной карты, МАЛО цифр 1111111!');
				inp.style.border = "1px solid red";
			} else {
				inp.style.border = "";
				calculateOrderPrice();
			}
		}
	}
}
function check(inp){
	regexp = /^\({1,1}[0-9]{2,2}\){1,1}[0-9]{20,20}/gim;
	var dk = regexp.exec(inp.value);
	if(dk==null) {
	//	alert('Не корректное значение Дисконтной карты, мало цифр 33333!');
		inp.style.border = "1px solid red";
	} else {
		if(inp.value.length > 24) {
			//alert('Не корректное значение Дисконтной карты, МНОГО цифр 444444!');
			inp.style.border = "1px solid red";
		}
	}
}
function calculateOrderPrice() {
	if(inp.value ==''){
		calculateOrderItemClearPrice();
		return;
	}
	//alert(inp.value.slice(-1));
	if(inp.value.substr(-1) & 1 ) {
		data='odd='+inp.value;
	} else {
		data='even='+inp.value;
	}
	reqest(data);
}
function calculateOrderItemDiscountPrice(disct) {
	if(triger == false && $index>0) {
		$disc = disct;
		var $ordPR = 0;
		for(var ii=0; ii<$end.length; ii++){
			$ordPR = ($ordPR + $end[ii]*1);
		}
		for(var i=0; i<$index; i++){
			oldPr = $el[i][0].toFixed(2) + ' грн.';
			price = ($el[i][0] - $el[i][0] * $disc).toFixed(2);
			pricePr = price + ' грн.';
			td = document.getElementById('td' + $el[i][1]);
			td.children[0].innerHTML = pricePr;
			td.children[1].innerHTML = oldPr;
			sk = document.getElementById('SK' + $el[i][1]);
			sk.style.color = 'red';
			a = "<div>" + ($disc*100).toFixed(2) + "% </div>";
			sk.innerHTML = a;
			sm = document.getElementById('SM' + $el[i][1]);
			sm.style.color = 'red';
			numttl = price*$el[i][2];
			ttl = (numttl).toFixed(2);
			$ordPR=$ordPR + numttl;
			sm.innerHTML = ttl+' грн.';
		}
		//totalOrder = document.getElementById('totalOrder');
		tord.style.color = 'red';
		tord.innerHTML = ($ordPR).toFixed(2) + ' грн.';
		}
	triger = true;
}
function calculateOrderItemClearPrice() {
	if(triger == true  && $index > 0) {
		var $ordPR = 0;
		for(var ii=0; ii<$end.length; ii++){
			$ordPR = ($ordPR + $end[ii]*1);
		}
		
		for(var i=0; i<$index; i++){
			oldPr = '';
			price = $el[i][0];
			pricePr = price + ' грн.';
			td = document.getElementById('td' + $el[i][1]);
			td.children[0].innerHTML = pricePr;
			td.children[1].innerHTML = oldPr;
			sk = document.getElementById('SK' + $el[i][1]);
			sk.style.color = 'black';
			a = '';
			sk.innerHTML = a;
			sm = document.getElementById('SM' + $el[i][1]);
			sm.style.color = 'black';
			numttl = price*$el[i][2];
			ttl = (numttl).toFixed(2);
			$ordPR=$ordPR + numttl;
			sm.innerHTML = ttl+' грн.';
		}
		//totalOrder = document.getElementById('totalOrder');
		tord.style.color = 'black';
		tord.innerHTML = $ordPR + ' грн.';
	}
	triger = false;
}
onReady(function(){
	//инициализируем переменных
	inp = document.getElementById('ORDER_PROP_9');
	parentGuest = inp.parentNode;
	parentGuest.appendChild(recalculate);
	recalculate.onclick = function() {
		checkDK(inp);
	}
	tord = document.getElementById('totalOrder');
	if(inp.value !='') {
		checkDK(inp);
	}
	/*inp.onblur=function(){
		checkDK(inp);
	}*/
	inp.onkeyup=function(){
		check(inp);
	}
	
});
