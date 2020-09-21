<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Аптечная сеть Мед-сервис™ – это первая национальная сеть, объединяющая под торговой маркой «Мед-сервис» 301 аптеку в 100 городах Украины.");
$APPLICATION->SetPageProperty("title", "Аптеки  Мед- Сервис");
$APPLICATION->SetPageProperty("keywords", "аптеки мед сервис, сеть аптек мед-сервис, адрес аптеки");
$APPLICATION->SetTitle("Наши аптеки"); 
/*
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
/* */


echo '<script type="text/javascript">';
	include_once 'script.js';
echo '</script>';
?>
<link rel="stylesheet" href="style.css" />

<p>Введите название города, чтобы найти ближайшую к вам аптеку.</p>
<?// Прием заказов выполняется с 9:00 до 18:00 каждый будний день, а также с 8:00 до 17:00 в субботу. Заказы, совершенные вне рабочего графика и в воскресенье, обрабатываются на следующий рабочий день нтернет-аптеки.?>
<div class="input_wrap clearfix">
	<input id="search-highlight" name="search-highlight" placeholder="Введите название города..." type="text" data-list=".highlight_list" autocomplete="on">
	<i id="clearString" class="fa fa-ban" aria-hidden="true" style="display:none"></i>
	<i class="fa fa-search" aria-hidden="true"></i>
</div>
<div id="view"></div>
<?






?><?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
