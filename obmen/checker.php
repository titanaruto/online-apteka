<?php
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/online-apteka.com.ua";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

    /*ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
/* */

function mailer($type, $count){
    switch ($type) {
        case '0':
            $to = 'savchenko.maksim@med-service.com.ua, gusev.sergey@med-service.com.ua';
            $subject  = 'Внимание !!! Слишком много заказов в обмене для 1С - '.$count;
            break;
        case '1':
            $to = 'gusev.sergey@med-service.com.ua';
            $subject  = 'Внимание !!! Слишком много заказов в обмене для ПП - '.$count;
            break;
        case '2':
            $to = 'savchenko.maksim@med-service.com.ua, gusev.sergey@med-service.com.ua';
            $subject  = 'Внимание !!! Слишком много заказов в обмене для 1С и ПП - '.$count;
            break;
        case '3':
            $to = 'gusev.sergey@med-service.com.ua';
            $subject  = 'Все в порядке, я работаю, заказов в таблице сейчас - '.$count;
            break;
        default:
            exit;
            break;
    }
    $message  = 'Внимание !!! Слишком много заказов в обмене вашего типа - ' .$count;
    $headers  = "MIME-Version: 1.0\n" ;
    $headers .= "Content-Type: text/html; charset=\"utf-8\"\n";
    $headers .= "X-Priority: 1 (Highest)\n";
    $headers .= "X-MSMail-Priority: High\n";
    $headers .= "Importance: High\n";
    //pre($to);
    mail($to, $subject, $message, $headers);
}

$DB = new workWithDB;
$warning = 50;
$sql = "SELECT * FROM `ord_obmen`";
$result = $DB->freeQuery($sql);
$firstC = 0;
$web = 0;

if (is_array($result)) {
    foreach ($result as $value) {
        if ($value['TYPE'] == 0) {
            $firstC++;
        } else if ($value['TYPE'] == 1){
            $web++;
        }
    }
}

if ($firstC >= $warning && $web < $warning) {
    mailer(0,$firstC);
} elseif ($web >= $warning && $firstC < $warning) {
    mailer(1, $web);
} elseif ($firstC >= $warning && $web >= $warning) {
    mailer(2, $firstC+$web);
} else {
    //mailer(3, $firstC+$web);
    exit;
}

//вычисляем сколько всего записей было проблемных во время прошлого запуска ord_obmen_problems
/*$sql = "SELECT COUNT_ORDERS FROM `ord_obmen_problems` where id = (SELECT MAX(id) FROM `ord_obmen_problems`)";
$warning = $DB->freeQuery($sql);
//pre($warning);
//выясняем сколько записей в таблице обмена сейчас
$sql = 'SELECT count(*) FROM `ord_obmen`';
$result = $DB->freeQuery($sql);
if (is_array($result)) {
    pre($result);exit;
    //выбираем данные по заказу с минимальным id с типом 1
    $sql = "SELECT * FROM `ord_obmen` where `ID` = (select min(ID) from `ord_obmen`) and `TYPE` = 1";
    $min = $DB->freeQuery($sql);
    //если есть, то присваиваем его id
    if (is_array($min)) {
        $id = $min[0]['ID'];

        $insert = "INSERT INTO `ord_obmen_problems` (`TYPE`, `ORDERID`, `FINAL`, `DATESTATUS`, `COUNT_ORDERS`) VALUES ";
        $insert.= "('".$result[$id]['TYPE']."','".$result[$id]['ORDERID']."','".$result[$id]['FINAL']."','".date('Y-m-d H:i:s', time())."',(SELECT COUNT(*) FROM `ord_obmen`))";
        //pre($insert);
        //$res = $DB->freeQuery($insert);
        //pre($res);
        $delete = "DELETE FROM `ord_obmen` WHERE `ID` = $id";
        //pre($delete);
        //$res = $DB->freeQuery($delete);
        $to = 'barabash.ivan@med-service.com.ua';
        $subject  = 'Внимание !!! Из обмена Удален заказ  '.$id;
        $message  = 'Из обмена Удален заказ  '.$id.' на '.date('d-m-Y H:i:s', time()).' очередь составила - '.$warning[0]['COUNT_ORDERS'];
        $headers  = "MIME-Version: 1.0\n" ;
        $headers .= "Content-Type: text/html; charset=\"utf-8\"\n";
        $headers .= "X-Priority: 1 (Highest)\n";
        $headers .= "X-MSMail-Priority: High\n";
        $headers .= "Importance: High\n";
        //mail($to, $subject, $message, $headers);
    } else {
        $id = -1;
    }


} else {
    pre($result);
}

    //pre($warning);*/




//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
