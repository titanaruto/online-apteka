<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Инструменты контроля контента");?>
<?
/*
ini_set('error_reporting', E_ALL); //, E_ALL
ini_set('display_errors', 1); //, 1
ini_set('display_startup_errors', 1);//, 1
/* */
?>
<br/><br/>
<a href="/obmen/checknameimg.php" style="width:200px;" target="blanck">Проверить имена картинок</a><br/>
<a href="/obmen/jpgcheck.php" style="width:200px;" target="blanck">Картинка есть, товара нет</a><br/>
<a href="/obmen/nojpgdo.php" style="width:200px;" target="blanck">Товар есть, картинки нет</a><br/>
<a href="/obmen/ajaxpicture.php" style="width:200px;" target="blanck">Обновить картинки товаров</a><br/>
<a href="/obmen/notjpgsection.php" style="width:200px;" target="blanck">Обновить картинки категорий</a><br/>
<a href="/obmen/level.php" style="width:200px;" target="blanck">Превышение уровня вложенности</a><br/>
<p>Для обработки "Товар фиксированной цены" необходимо заполнить файл constant_price.csv в каталоге
"/obmen/". Значимыми данными являются 1С шифр товара. Файл для образца размещен там же. Разделитель полей - запятая.</p>
<a href="/obmen/constant_price.php" style="width:200px;" target="blanck">Товары фиксированной цены</a><br/>





<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
