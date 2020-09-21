//определение переменных
var script = '/nashy_apteki/obrabotka.php';
var view;
var searcInp;
var clearString;
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
function auto_layout_keyboard( str ) {
	replacer = {
		"q":"й", "w":"ц", "e":"у", "r":"к", "t":"е", "y":"н", "u":"г",
		"i":"ш", "o":"щ", "p":"з", "[":"х", "]":"ъ", "a":"ф", "s":"ы",
		"d":"в", "f":"а", "g":"п", "h":"р", "j":"о", "k":"л", "l":"д",
		";":"ж", "'":"э", "z":"я", "x":"ч", "c":"с", "v":"м", "b":"и",
		"n":"т", "m":"ь", ",":"б", ".":"ю", "/":"."
	};
	return str.replace(/[A-z/,.;\'\]\[]/g, function ( x ){
		return x == x.toLowerCase() ? replacer[ x ] : replacer[ x.toLowerCase() ].toUpperCase();
	});
}
function checkItem(element){
	search(element.dataset.name);
	searcInp.value = element.dataset.name;
	search_result.style.display = 'none';
	search_result.innerHTML = '';
	return false;
}
//https://developer.mozilla.org/ru/docs/Web/Guide/HTML/Using_data_attributes
function search(string=-1) {
	if(string==-1 || string == ''){
		search_result.style.display ='none';
		clearString.style.display='none';
		clearSearch();
		return false;
	}
	clearString.style.display='';
	string = auto_layout_keyboard(string.toLowerCase());
	searcInp.value = string.ucFirst();
	if(string.length<1) {
		for (var i = 0; i < view.childNodes.length; i++) {
			view.childNodes[i].style.display = '';
		}
		search_result.style.display ='none';
		return false;
	}
	///console.log(string);
	var result = '';
	for (var i = 0; i < view.childNodes.length; i++){
		var curElem = view.childNodes[i].dataset.name.toLowerCase();
		if(curElem.indexOf(string) + 1){
			if(view.childNodes[i].style.display == 'none'){
				view.childNodes[i].style.display = '';
			}
			//dataset.name;
			result += '<span onclick="checkItem(this)" data-name="'+ curElem.ucFirst() +'">';
			result += '<b>' + curElem.ucFirst() + '</b>';
			result += '</span><br/>'
			//console.log(curElem);
		} else {
			view.childNodes[i].style.display = 'none';
		}
	}
	search_result.innerHTML = result;
	search_result.style.display ='';
}
function clearSearch(){
	search_result.style.display = 'none';
	search_result.innerHTML = '';
	clearString.style.display='none';
	for (var i = 0; i < view.childNodes.length; i++){
		view.childNodes[i].style.display = '';
	}
}
function handle(e){
	if(e.keyCode === 13){
		search(searcInp.value);
		search_result.style.display = 'none';
		search_result.innerHTML = '';
		searcInp.blur();
		//clearString.focus();
	}
	return false;
}
onReady(function(){
	//инициализируем переменных
	view = document.getElementById('view');
	searcInp = document.getElementById('search-highlight');
	clearString = document.getElementById('clearString');
	searcInp.parentNode.insertBefore(search_result, searcInp.nextSibling);
	var data = 'LIST=Y';
	reqest(data,view);
	searcInp.onkeyup=function(){
		var e=arguments[0] || event;
		if(e.keyCode !== 13){
			search(searcInp.value);
		}
	}
	searcInp.onkeypress=function(){
		var e=arguments[0] || event;
		//alert(e.keyCode);
		handle(e);
	}
	clearString.onclick=function(){
		searcInp.value = '';
		clearSearch();
	}
});
