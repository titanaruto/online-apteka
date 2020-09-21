<?/*Синхронизатор Внешних кодов между ПП и промежуточной БД для 1с*/
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/online-apteka.com.ua";//определяем корневой каталог в суперглобальном массиве
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"]; // получаем значение кореневого каталога
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);//определяем константы
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php'); //подключаем файл с классами
/*
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
/* */
set_time_limit(120); // задаем время выполнения скрипта

if(!CModule::IncludeModule("iblock")){ //проверяем на наличие модуля инфоблоков
    die('Модуль Инфоблоков не подключен!');
}
//11.2
$CIBlockSection = new CIBlockSection; //создаем объект класса  для работы с разделами (группами) информационных блоков.
//устанавливаем фильтр для выборки из инфоблока "2" ПП
$arFilter = array(
    'IBLOCK_ID' => intval(2)
);
//устанавливаем получаемые поля секции
$arSelect = array(
    'IBLOCK_ID',
    'ID',
    'EXTERNAL_ID'
);
//плоучаем данные от ПП с определенными выше параметрами
$rsSections = $CIBlockSection->GetList(
    array('ID' => 'ASC'),
    $arFilter,
    false,
    $arSelect
);

//1.14
//создаем объект класса для работы с промежуточной БД 1с
$sql = new workWithDB;
//основной цикл работы
while ($arSection = $rsSections->Fetch()){//разбираем данные от ПП, получая по 1 массиву за итерацию
    pre($arSection['ID']);
    pre($arSection['EXTERNAL_ID']);
    //создаем запрос для выборки данных из промежуточной БД 1с
    //ID - идентификатор товара, ODLEXTKEY - старый внешний ключ, NEWEXTKEY - новый внешний ключ
    //$arSection[EXTERNAL_ID] - внешний ключ от ПП
    $qwery = "SELECT ID,ODLEXTKEY,NEWEXTKEY FROM `catalog_synhr` ";
    $qwery.= "WHERE `ODLEXTKEY` = '$arSection[EXTERNAL_ID]' OR `NEWEXTKEY` = '$arSection[EXTERNAL_ID]' ";
    pre($qwery);
    $res = $sql->freeQuery($qwery); //получаем массив данных - результат запроса
    //pre($res[0]);
    //сравниваем внешний ключ из ПП со старым внешним ключем из промежуточной БД 1с
    if ($arSection['EXTERNAL_ID'] == $res[0]['ODLEXTKEY']){
        //если нашли - записываем его ИД из БД ПП в  промежуточную БД 1с
        $qweryA = "UPDATE `catalog_synhr` SET `ID` = '$arSection[ID]' where `ODLEXTKEY` = '$arSection[EXTERNAL_ID]'";
        $resA = $sql->freeQuery($qweryA);
        pre($resA);
        //массив значений для апдейта внешнего ключа на ПП
        $arFields = Array(
            'EXTERNAL_ID' => $res[0]['NEWEXTKEY']
        );
        //апдейтим внешние ключи
        $result = $CIBlockSection->Update($arSection['ID'],$arFields);
        if(true ===$result){
            echo 'Ok';
        } else {
            echo 'Fail!';
        }


    } else {
        echo '2<br/>';
    }
    //exit();
}

/*
$sql = new workWithDB;
//pre($obmen);
$qwery = 'SELECT * FROM `ord_obmen` WHERE 1=1';
$res = $sql->freeQuery($qwery);
pre($res);
*/

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
