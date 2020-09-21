<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Наши аптеки");
?>

<style>
	#view > div{
		display: inline-block;
	    width: 24%;
	    margin-right: 1%;
	    margin-bottom: 20px;
	    padding-bottom: 20px;
	    border-bottom: 1px solid #999;
	}
	#view > div .name{font-weight: bold;}
	.btn-success, .btn-danger{
		padding: 1px 6px;
		margin: 5px 0;
		border: 0;
	}
	#view > div span:nth-child(2){display: block;}
	#progress{
	    padding: 2px 3px;
	    border: 1px solid #c4dfec;
	    -webkit-border-radius: 4px;
	    -moz-border-radius: 4px;
	    border-radius: 4px;
	    position: relative;
	    width: 100%;
	    height: 30px;
	}
	#bar{
		background-color:#000;
		height:100%;
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		color: #fff;
		font-style: 16px;
		text-align: center;
		line-height: 26px;
		font-weight: bold;
		background: #a00000;
		background: -moz-linear-gradient(left,  #a00000 0%, #a00000 0%, #ed1c24 100%, #ed1c24 100%, #ed1c24 102%);
		background: -webkit-linear-gradient(left,  #a00000 0%,#a00000 0%,#ed1c24 100%,#ed1c24 100%,#ed1c24 102%);
		background: linear-gradient(to right,  #a00000 0%,#a00000 0%,#ed1c24 100%,#ed1c24 100%,#ed1c24 102%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a00000', endColorstr='#ed1c24',GradientType=1 );
	}
</style>

<?
/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
/* */


echo '<script type="text/javascript">';
include_once 'scriptajaxpicture.js';
echo '</script>';

?>
<div class="input_wrap clearfix">
	<input id="search-highlight" name="search-highlight" placeholder="Введите код картинки..." type="text" data-list=".highlight_list" autocomplete="on">
	<i id="clearString" class="fa fa-ban" aria-hidden="true" style="display:none"></i>
	<i class="fa fa-search" aria-hidden="true"></i>
	<button id="replaceAll">Обновить все картинки</button>
</div>

<div id="progress">
   <div id="bar" style="width:0%;">0%</div>
</div>

<div id="allResult"></div>

<br/><br/>
<span id="busy" style="display:none;">ОБРАБОТКА ДАННЫХ....</span>
<div id="view"></div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
