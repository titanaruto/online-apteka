var inp;
var tbl;
var tord;
var triger = false;
var $discount;
var $disc;
var relPrps;
var ord_oblast_name_list;
var ord_city_name_list;
var ord_city_name;
var ord_pharmacy_name_list;
var ord_pharmacy_name;
var ord_pharmacy;
var ord_city;
var inp_loc_adr;
var inp_loc;
var inp;

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
function reqestEl(data, element){
	var xhr = new XMLHttpRequest();
	xhr.open('POST', $script, true);
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
	inp = document.getElementById('ORDER_PROP_9');
	if(inp.value ==''){
		calculateOrderPrice();
	} else {
		regexp = /^\({1,1}[0-9]{2,2}\){1,1}[0-9]{20,20}/gim;
		var dk = regexp.exec(inp.value);
		if(dk==null) {
			inp.style.border = "1px solid red";
		} else {
			if(inp.value.length > 24) {
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
		inp.style.border = "1px solid red";
	} else {
		if(inp.value.length > 24) {
			inp.style.border = "1px solid red";
		}
	}
}
function calculateOrderPrice() {
	if(inp.value ==''){
		calculateOrderItemClearPrice();
		return;
	}
	//alert(inp.value);
	if(inp.value.substr(-1) & 1 ) {
		data='odd='+inp.value;
	} else {
		data='even='+inp.value;
	}
	//alert(data);
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
		tord.style.color = 'black';
		tord.innerHTML = $ordPR + ' грн.';
	}
	triger = false;
}
/*

function getOblList(){
	vis = ord_oblast_name_list.dataset.vis;
	if(vis == 'none'){
		ord_pharmacy.innerHTML = 'Выбрать аптеку...';
		ord_city.innerHTML = 'Выбрать город...';
		inp_loc.value = '';
		inp_loc.innerHTML = '';
		inp_loc_adr.value = '';
		inp_loc_adr.innerHTML = '';
		ord_oblast_name.style.display='block';
		ord_oblast_name_list.dataset.vis = 'block';
		ord_city_name_list.dataset.vis = 'none';
		ord_city_name.style.display = 'none';
		ord_pharmacy_name_list.dataset.vis = 'none';
		ord_pharmacy_name.style.display = 'none';
		ORDER_CONFIRM_BUTTON.style.display = 'none';
		ord_city.onclick = function(){ return false;};
		ord_pharmacy.onclick=function(){return false;};
		ord_oblast_name.onclick=function(e){
			el = e.target;
			//alert(el.id);
			ord_oblast.innerHTML = el.innerHTML;
			ord_oblast_name.style.display='none';
			ord_oblast_name_list.dataset.vis = 'none';
			getCityList(el);
		}
	}
}
function getCityList(el){
	id = el.id;
	data ='OBL='+id+'&FOB=1';
	//ord_city_name = document.getElementById('ord_city_name');
	reqestEl(data, ord_city_name);
	inp_loc.value = '';
	inp_loc.innerHTML = '';
	inp_loc_adr.value = '';
	inp_loc_adr.innerHTML = '';
	ORDER_CONFIRM_BUTTON.style.display = 'none';
	ord_city.onclick=function(){
		vis = ord_city_name_list.dataset.vis;
		if(vis == 'none'){
			ord_pharmacy.innerHTML = 'Выбрать аптеку...';
			inp_loc.value = '';
			inp_loc.innerHTML = '';
			ord_city_name_list.dataset.vis = 'block';
			ord_city_name_list.style.display = 'block';
			ord_city_name.style.display = 'block';
			ord_pharmacy_name_list.dataset.vis = 'none';
			ord_pharmacy_name.style.display = 'none';
			ORDER_CONFIRM_BUTTON.style.display = 'none';
			ord_pharmacy.onclick=function(){return false;};
			inp_loc.value = '';
			inp_loc.innerHTML = '';
			inp_loc_adr.value = '';
		inp_loc_adr.innerHTML = '';
			ord_city_name.onclick=function(e){
				el = e.target;
				ord_city.innerHTML = el.innerHTML;
				ord_city_name_list.dataset.vis = 'none';
				ord_city_name.style.display = 'none';
				getPharmacyList(el);
			}
		}
	}
}
function getPharmacyList(el){
	id = el.id;
	data ='CIT='+id+'&OBJ=1';
	reqestEl(data, ord_pharmacy_name);
	inp_loc.value = '';
	inp_loc.innerHTML = '';
	ord_pharmacy.onclick=function(){
		inp_loc.value = '';
		inp_loc.innerHTML = '';
		inp_loc_adr.value = '';
		inp_loc_adr.innerHTML = '';
		ORDER_CONFIRM_BUTTON.style.display = 'none';
		vis = ord_pharmacy_name_list.dataset.vis;
		if(vis == 'none'){
			ord_pharmacy_name_list.dataset.vis = 'block';
			ord_pharmacy_name_list.style.display = 'block';
			ord_pharmacy_name.style.display = 'block';
			ORDER_CONFIRM_BUTTON.style.display = 'none';
			ord_pharmacy_name.onclick=function(e){
				el = e.target;
				ord_pharmacy.innerHTML = el.innerHTML;
				ord_pharmacy_name_list.dataset.vis = 'none';
				ord_pharmacy_name.style.display = 'none';
				
				inp_loc.value = el.id;
				inp_loc.innerHTML = el.id;
				//alert(el.dataset.city + ', '+ el.innerHTML);
				inp_loc_adr.value = el.dataset.city + ', '+ el.innerHTML;
				inp_loc_adr.innerHTML = el.dataset.city + ', '+ el.innerHTML;
				ORDER_CONFIRM_BUTTON.style.display = 'block';
				//alert(inp_loc.value);
			}
		}
	}
}
*/
onReady(function(){
	//инициализируем переменных
	inp = document.getElementById('ORDER_PROP_9');
	tord = document.getElementById('totalOrder');
	/*
	inp_loc = document.getElementById('ORDER_PROP_7');
	inp_loc_adr = document.getElementById('ORDER_PROP_5');
	ord_oblast_name_list = document.getElementById('ord_oblast_name_list');
	ord_oblast_name = document.getElementById('ord_oblast_name');
	ord_oblast = document.getElementById('ord_oblast');
	ord_city_name_list = document.getElementById('ord_city_name_list');
	ord_pharmacy_name_list = document.getElementById('ord_pharmacy_name_list');
	ord_city = document.getElementById('ord_city');
	ord_pharmacy = document.getElementById('ord_pharmacy');
	ord_city_name = document.getElementById('ord_city_name');
	ord_pharmacy_name = document.getElementById('ord_pharmacy_name');*/
	//ORDER_CONFIRM_BUTTON = document.getElementById('ORDER_CONFIRM_BUTTON');
	//ORDER_CONFIRM_BUTTON.style.display = 'none';
	
	/*if(inp_loc.value == '' && inp_loc_adr.value == '') {
		ORDER_CONFIRM_BUTTON.style.display = 'none';
	}*/
	
	if(inp.value !='') {
		checkDK(inp);
	}
	inp.onkeyup=function(){
		check(inp);
	}
	/*
	ord_oblast.onclick=function(){
		getOblList();
	}
	*/
});
var link_regular_customer = $(".link-regular-customer");
var help_regular_customer = $(".help-regular-customer");
var form_regular_customer = $("#show-regular-customer");


link_regular_customer.click(function(e){
	e.preventDefault();
	form_regular_customer.toggleClass("show-regular-customer");
	link_regular_customer.toggleClass("link-regular-customer-active");
	help_regular_customer.toggleClass("help-regular-customer--not-show");
});