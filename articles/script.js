//определение переменных
var script = '/articles/obrabotka.php';
var articles;
var searcInp;
var search_result = document.createElement('div');
search_result.style.display ='none';
search_result.style.background = '#90EE90';
search_result.className = "search_result";


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
String.prototype.ucFirst = function() {
	var str = this;
	if(str.length) {
		str = str.charAt(0).toUpperCase() + str.slice(1);
	}
	return str;
};
function evntClickSet(element){
	for (var i = 0; i < element.childNodes.length; i++) {
		console.log(i);
		element.childNodes[i].onclick=function(){
			goClick(element.childNodes[i]);
		}
	}
}
function goClick(element) {
	console.log(element.target);
	var $code = element.dataset.code;
	var data = 'CODE='+$code;
}


function search(string=-1) {
	
}
onReady(function(){
	articles = document.getElementById('articles');
	var data = 'LIST=Y';
	reqest(data, articles);
	searcInp.change=function(){
		alert('1');
		search(searcInp.value);
	}
});
