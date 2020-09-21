<?php

function pre($array){
    echo '<pre>';
        print_r($array);
    echo '</pre>';
    return;
}

# Подключаем пользовательские классы.

CModule::AddAutoloadClasses(
'', // не указываем имя модуля
    array(
        // ключ - имя класса,
        // значение - путь относительно корня сайта к файлу с классом
        'workWithDB' => '/bitrix/php_interface/UserClass/workWithDB.php',
        'custom' => '/bitrix/php_interface/UserClass/custom.php',
        'ftpRequest' => '/bitrix/php_interface/UserClass/ftpRequest.php',
        'Tools' => '/bitrix/php_interface/UserClass/Tools.php',
        'DictionaryTable' => '/local/Sgus/Search/Levenshtein/ORM/DictionaryTable.php',
        'LevenshteinSearch' => '/local/Sgus/Search/Levenshtein/LevenshteinSearch.php',
        'LevenshteinDictionary' => '/local/Sgus/Search/Levenshtein/LevenshteinDictionary.php',
        'SiteMap' => '/bitrix/php_interface/UserClass/SiteMap.php',

        // обмен с 1С
        'obmen' => '/bitrix/php_interface/UserClass/obmen1C/obmen.php',
        'obmenOrders' => '/bitrix/php_interface/UserClass/obmen1C/obmenOrders.php',
        'obmenGoods' => '/bitrix/php_interface/UserClass/obmen1C/obmenGoods.php',
        'obmenPrices' => '/bitrix/php_interface/UserClass/obmen1C/obmenPrices.php',
        'obmenCatalog' => '/bitrix/php_interface/UserClass/obmen1C/obmenCatalog.php',
        'obmenFiles' => '/bitrix/php_interface/UserClass/obmen1C/obmenFiles.php',
        'qwery' => '/bitrix/php_interface/UserClass/obmen1C/qwery.php',
    )
);

//-- Добавление обработчика события
AddEventHandler("sale", "OnOrderNewSendEmail", "bxModifySaleMails");
//-- Собственно обработчик события
function bxModifySaleMails($orderID, &$eventName, &$arFields){
    $res = CSaleBasket::GetList(array("ID" => "ASC"), array("ORDER_ID" => $orderID)); // ID заказа
    $orderList = '';
    while ($staf = $res->Fetch()) {
        $orderList .= $staf['NAME'].' - '.$staf['QUANTITY'].'шт.;<br/>';
    }
    $arOrder = CSaleOrder::GetByID($orderID);

    $order_props = CSaleOrderPropsValue::GetOrderProps($orderID);
    $dkcard = "";
    $location = "";
    while ($arProps = $order_props->Fetch()){
        if ($arProps["CODE"] == "ADRES"){
            $location =  'Украина, '.$arProps["VALUE"].' ';
        }
        if ($arProps["CODE"] == "DKCARD"){
            $DKCARD = $arProps['VALUE'];
        }
    }
    $arFields["FULL_ADDRESS"] = $location;
    $arFields["DKCARD"] = $DKCARD;
    $arFields["ORDER_LIST_MC"] = $orderList;
}

AddEventHandler("sale", "OnOrderAdd", "OnOrderAddHandler");

function OnOrderAddHandler($id, $arFields) {
    $fc = fopen($_SERVER['DOCUMENT_ROOT'] . '/order123.txt', 'a');
    fwrite($fc, print_r($id . ": " . $arFields['ORDER_PROP'][7] . "/" . $arFields['ORDER_PROP'][36], 1) . "\r\n");
//    fwrite($fc, print_r($arFields['ORDER_PROP'], 1) . "\r\n");

    if (trim($arFields['ORDER_PROP'][15]) === 'Доставка курьером') {
        if (empty($arFields['ORDER_PROP'][36])) {
            ?>
            <script>alert("Ошибка: не выбран адрес!")</script>
            <?php
            exit("Ошибка! Не выбран адрес!");
        }
    }

    if ($arFields['ORDER_PROP'][7] == 'ord_oblast_apt') {
        ?>
        <script>alert("Ошибка: не выбрана аптека!")</script>
        <?php
        exit("Ошибка! Не выбрана аптека!");
    }
//    if (!empty($arFields["ORDER_PROP"][33])) {
//        $items = [];
//
//        $additional_service = \Bitrix\Sale\Delivery\ExtraServices\Manager::getExtraServicesList(20, false);
//        foreach ($additional_service as $key => $value) {
//            foreach ($value['PARAMS']['PRICES'] as $item) {
//                $items[] = $item['PRICE'];
//            }
//        }
//
//        $courier_cost = $arFields["PRICE"] >= $items[0] ? 0 : $items[1];
//        $total_sum = str_replace(',', '.', $sum) < $items[0] ? (str_replace(",", ".", $sum) + $courier_cost) : $sum;
//
//        if ($courier_cost > 0) {
//            $arFields = [
//                "PRICE" => $arFields["PRICE"] + $items[1]
//            ];
//            CSaleOrder::Update($id, $arFields, true);
//        }
//    }
}

//-- Добавление обработчика события
AddEventHandler("sale", "OnOrderStatusSendEmail", "bxModifySaleMailsSTATUS");
//-- Собственно обработчик события
function bxModifySaleMailsSTATUS($orderID, &$eventName, &$arFields, $val) {
    $res = CSaleBasket::GetList(array("ID" => "ASC"), array("ORDER_ID" => $orderID)); // ID заказа
    $orderList = '';
    while ($staf = $res->Fetch()) {
        $price = $staf['PRICE']*$staf['QUANTITY'];
        $orderList .= $staf['NAME'].' - '.$staf['QUANTITY'].'шт. - '.round($price,2).' грн.;<br/>';
    }

    $arOrder = CSaleOrder::GetByID($orderID);
    $order_props = CSaleOrderPropsValue::GetOrderProps($orderID);
    $dkcard = "";
    $location = "";
    while ($arProps = $order_props->Fetch()){
        if ($arProps["CODE"] == "ADRES"){
            $location =  'Украина, '.$arProps["VALUE"].' ';
        }
        if ($arProps["CODE"] == "DKCARD"){
            $DKCARD = $arProps['VALUE'];
        }
    }
    $arFields["PRICE"] = $arOrder['PRICE'];
    $arFields["FULL_ADDRESS"] = $location;
    $arFields["DKCARD"] = $DKCARD;
    $arFields["ORDER_LIST_MC"] = $orderList;
}

AddEventHandler('main', 'OnBeforeEventAdd', 'includeCustomMail');
function includeCustomMail($event, $lid, $arFields) {
    if ($event == 'MY_TYPE') {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/classes/custom_mail.php');
    }
}

function my_onAfterResultAddUpdate($WEB_FORM_ID, $RESULT_ID)
{
    if ($WEB_FORM_ID == 2) {
        $arAnswer = CFormResult::GetDataByID($RESULT_ID, array("SIMPLE_QUESTION_621", "SIMPLE_QUESTION_903"), $arResult, $arAnswer2);
        $adr_from = $arAnswer['SIMPLE_QUESTION_621']['0']['USER_TEXT'];
        $phone_from = $arAnswer['SIMPLE_QUESTION_903']['0']['USER_TEXT'];
        $st = preg_replace ("/[^0-9]/","", $phone_from);
        $arSend = array("TO" => $adr_from);
        CEvent::Send("CALLBACK_LETTER", s1, $arSend, "N", 75);
        Tools::sendSms($st, "Спасибо за Ваше обращение. Я отвечу Вам в течение 1 рабочего дня.");
    } else if ($WEB_FORM_ID == 3) {
        $arAnswer = CFormResult::GetDataByID($RESULT_ID, array("address", "name", "price", "reserve_qty", "amount", "phone", "productId", "pharm_id"), $arResult, $arAnswer2);
        require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/ajax/addorder1click.php");
    }
}

AddEventHandler('form', 'onAfterResultAdd', 'my_onAfterResultAddUpdate');

AddEventHandler('main', 'OnEpilog', 'onEpilog', 1);
function onEpilog(){
    global $APPLICATION;
    $arPageProp = $APPLICATION->GetPagePropertyList();
    $arMetaPropName = array('og:title','og:description','og:image','og:type','fb:admins','fb:app_id');
    foreach ($arMetaPropName as $name){
        $key = mb_strtoupper($name, 'UTF-8');
        if (isset($arPageProp[$key])){
            $APPLICATION->AddHeadString('<meta property="'.$name.'" content="'.htmlspecialchars($arPageProp[$key]).'">',$bUnique=true);
        }
    }
}

AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserRegisterHandler");
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");

function OnAfterUserRegisterHandler(&$arFields)
{
    if (intval($arFields["ID"]) > 0)
    {
        $toSend = Array();
        $toSend["PASSWORD"] = $arFields["CONFIRM_PASSWORD"];
        $toSend["TO"] = $arFields["EMAIL"];
        $toSend["USER_ID"] = $arFields["ID"];
        $toSend["USER_IP"] = $arFields["USER_IP"];
        $toSend["USER_HOST"] = $arFields["USER_HOST"];
        $toSend["LOGIN"] = $arFields["LOGIN"];
        $toSend["URL_LOGIN"] = urlencode($arFields["LOGIN"]);
        $toSend["NAME"] = $arFields["LAST_NAME"];

        CEvent::SendImmediate ("NEW_USER_AUTO_REGISTRATION", SITE_ID, $toSend);
    }

    return $arFields;
}

AddEventHandler("search", "BeforeIndex", "BeforeIndexHandler");
function BeforeIndexHandler($arFields) {
    if ($arFields['PARAM1'] == 'catalog') {

    }
}

AddEventHandler("main", "OnBuildGlobalMenu", "ModifiAdminMenu");
function ModifiAdminMenu(&$adminMenu, &$moduleMenu){
    $moduleMenu[] = array(
        "parent_menu" => "global_menu_services", // в раздел "Сервис"
        "section" => "",
        "sort"        => 100,                    // сортировка пункта меню - поднимем повыше
        "url"         => "/bitrix/admin/product_comments.php?lang=".LANG,  // ссылка на пункте меню - тут как раз и пишите адрес вашего файла, созданного в /bitrix/admin/
        "text"        => 'Модерация комментариев к товарам',
        "title"       => 'Модерация комментариев к товарам',
        "icon"        => "form_menu_icon", // малая иконка
        "page_icon"   => "form_page_icon", // большая иконка
        "items_id"    => "",  // идентификатор ветви
        "items"       => array()          // остальные уровни меню

    );
}
























