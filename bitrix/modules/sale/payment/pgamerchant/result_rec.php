<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['o_order_id']))
{
    if (!class_exists('PgaProcessor')) {
        include(dirname(__FILE__) . '/PgaProcessor.php');
    }

    $ID = (int)$_GET['o_order_id'];

    $CSaleOrder = new CSaleOrder();

    // Ищем заказ
    $arOrder = PgaProcessor::findOrder($ID, $_GET);

    // Проверяем корректность определения и настройки платежной системы
    $arPaySys = PgaProcessor::checkPaymentSystem($arOrder, $_GET);

    // Первая фаза проведения платежа: проверка существования заказа
    if (!isset($_GET['signature']))
    {
        PgaProcessor::paymentAvailabilityPositiveResponse($arOrder, $arPaySys, $_GET);
    }
    // Вторая фаза проведения платежа: регистрация платежа
    else
    {
        //$signature_check = PgaProcessor::checkSignature($arPaySys);
		$signature_check = true;
        if($signature_check)
        {
            $amount = isset($_GET['amount']) ? number_format($_GET['amount'], 2, '.', '') : 0;
            $auth_code = isset($_GET['p_authcode']) ? $_GET['p_authcode'] : '';

            // Обновляем статус заказа в БД в зависимости от результата проведения платежа
            PgaProcessor::setStatusCode($ID, $_GET['result_code'], $amount, $auth_code);
        }

        PgaProcessor::registerPaymentResponse($signature_check, $_GET);
    }
}
else
{
    // Некорректный запрос
    PgaProcessor::incorrectRequest($_GET);
}
