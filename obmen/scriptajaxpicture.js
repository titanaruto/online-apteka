//определение переменных
var script = '/obmen/obrabotkaajaxpicture.php';
var view;
var searcInp;
var busy;
var checkAll;
var replaceAll;
var triger=1;
var allResult;
var bar;
var iii = 0;

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
			//console.log(xhr.responseText);
			element.innerHTML = xhr.responseText;
			//return true;
		}
	}
	element.innerHTML =  'Загрузка данных...';
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.send(data);
}
function checkElement(el){
	el = el.parentNode;
	el.style.width = '100%';
	var id= el.id;
	data='ID='+id+'&CH=Y';
	console.log(el);
	reqest(data, el.childNodes[2]);
}

function replaceIMG(el){
	el.parentNode;
	var data = 'ID='+el.dataset.name+'&F='+el.dataset.file+'&T='+el.dataset.type;
	reqest(data, el.parentNode);
}
function deleteIMG(el){
	console.log(el);
	el.parentNode;
	console.log(el.dataset.name);
	var data = 'ID='+el.dataset.name+'&T='+el.dataset.type+'&DEL=Y';
	console.log(data);
	console.log(data);
	reqest(data, el.parentNode);
}
function search(string=-1) {
	if(string==-1 || string == ''){
		clearString.style.display='none';
		clearSearch();
		return false;
	}
	clearString.style.display='';
	busy.style.display = '';
	for (var i = 0; i < view.childNodes.length; i++){
		if(view.childNodes[i].id.indexOf(string) + 1){
			if(view.childNodes[i].dataset.displ == 'none'){
				view.childNodes[i].style.display = '';
				view.childNodes[i].dataset.displ = 'block';
			}
			
		} else {
			view.childNodes[i].dataset.displ = 'none';
			view.childNodes[i].style.display = 'none';
		}
	}
	busy.style.display = 'none';
}
function clearSearch(){
	clearString.style.display='none';
	for (var i = 0; i < view.childNodes.length; i++){
		view.childNodes[i].style.display = '';
	}
}
function handle(e){
	if(e.keyCode === 13){
		busy.style.display = '';
		search(searcInp.value);
		searcInp.blur();
	}
	return false;
}
function replaceAllIMG(){
	if(triger<1){
		alert('Операция уже выполняется!!! Ждите...');
		return;
	}
	triger=0;
	bar.parentNode.style.display = '';
	bar.style.display = '';
	allResult.style.display = '';
	var intervalID = setInterval(function(){
		if(iii<view.childNodes.length){
			triger=0;
			var y = (iii*100)/view.childNodes.length;
			y = y.toFixed(3);
			bar.style.width = y+'%';
			bar.innerHTML = y+'%';
			id = view.childNodes[iii].id;
			data = 'F='+id+'&R=Y';
			//console.log(data);
			reqest(data, allResult);
			iii++;
			//triger=1;
		} else {
			bar.parentNode.style.display = 'none';
			bar.style.display = 'none';
			allResult.style.display = 'none';
			allResult.innerHTML = '';
			triger=1;
			clearInterval(intervalID);
		}
	}, 1000);
}
onReady(function(){
	//инициализируем переменных
	view = document.getElementById('view');
	searcInp = document.getElementById('search-highlight');
	clearString = document.getElementById('clearString');
	busy = document.getElementById('busy');
	checkAll = document.getElementById('checkAll');
	replaceAll = document.getElementById('replaceAll');
	allResult = document.getElementById('allResult');
	bar = document.getElementById('bar');
	bar.style.width = '0%';
	bar.style.display = 'none';
	bar.parentNode.style.display = 'none';
	allResult.style.display = 'none';
	data='ALL=Y';
	reqest(data, view);
	busy.style.display = 'none';

	searcInp.onkeyup=function(){
		var e=arguments[0] || event;
		if(e.keyCode !== 13){
			search(searcInp.value);
		}
	}
	searcInp.onkeypress=function(){
		var e=arguments[0] || event;
		handle(e);
	}
	clearString.onclick=function(){
		searcInp.value = '';
		clearSearch();
	}
	/*checkAll.onclick=function(){
		clearSearch();
	}*/
	replaceAll.onclick=function(){
		replaceAllIMG();
	}
});
