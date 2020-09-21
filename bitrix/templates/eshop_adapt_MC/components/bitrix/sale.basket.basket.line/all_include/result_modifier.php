<?php

$dbRes = \Bitrix\Sale\Basket::getList([
    'select' => ['NAME', 'QUANTITY'],
    'filter' => [
        '=FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
        '=ORDER_ID' => null,
        '=LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
        '=CAN_BUY' => 'Y',
    ]
]);

$item_total = 0;
while ($item = $dbRes->fetch())
{
    $item_total += $item["QUANTITY"];
}

$arResult["NUM_PRODUCTS"] = $item_total;

