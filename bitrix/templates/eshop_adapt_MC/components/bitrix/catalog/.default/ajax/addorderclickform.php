<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc as Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Sale\DiscountCouponsManager,
    Bitrix\Main\Context;

if (isset($_POST["phone"])) {
    pre($_POST);
    if (!Loader::IncludeModule('sale') && !Loader::IncludeModule("catalog"))
        die();

    function getPropertyByCode($propertyCollection, $code)
    {
        foreach ($propertyCollection as $property) {
            if ($property->getField('CODE') == $code)
                return $property;
        }
    }

    $siteId = \Bitrix\Main\Context::getCurrent()->getSite();

    /*params
     * name
     * phone
     * email
     * id tover
     * apteka
     */
    $fio = htmlspecialchars($_POST["name"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $email = htmlspecialchars($_POST["email"]);
    $zip = "NAL_NAL";
    $adres = htmlspecialchars($_POST["adres"]);
    $apt_location = htmlspecialchars($_POST["id_local"]);

    $PRODUCT_ID = htmlspecialchars($_POST["product_id"]);
    $QUANTITY = htmlspecialchars($_POST["QUANTITY"]);
    \CSaleBasket::DeleteAll(\CSaleBasket::GetBasketUserID(),false);
    if (CModule::IncludeModule("catalog")) {
        $result = Add2BasketByProductID(
            $PRODUCT_ID,
            $QUANTITY,
            false
        );
    }

    $user_id = $USER->GetID();
    if (is_null($user_id)) {
        $user_id = \CSaleUser::GetAnonymousUserID();
        $user = new CUser;
        $fields = Array(
            "NAME" => $fio,
            "EMAIL" => $email,
            "PERSONAL_PHONE" => $phone,
        );
        $user->Update($user_id, $fields);
        $strError .= $user->LAST_ERROR;
    }


    $currencyCode = Option::get('sale', 'default_currency', 'UAH');

    DiscountCouponsManager::init();

    $order = Order::create($siteId, $user_id);

    $order->setPersonTypeId(1);

    $basket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $siteId)->getOrderableItems();

    /* Действия над товарами*/
    $basketItems = $basket->getBasketItems();
//pre(count($basketItems));
//foreach ($basketItems as $basketItem) {
//    pre($basketItem);
//}
//$arg = 10;
    if (count($basketItems) > 0) {


//
//
        $order->setBasket($basket);

        /*Shipment*/
        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        $shipment->setField('CURRENCY', $order->getCurrency());
        foreach ($order->getBasket() as $item) {
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }
        $arDeliveryServiceAll = Delivery\Services\Manager::getRestrictedObjectsList($shipment);
        $shipmentCollection = $shipment->getCollection();

        if (!empty($arDeliveryServiceAll)) {
            reset($arDeliveryServiceAll);
            $deliveryObj = current($arDeliveryServiceAll);

            if ($deliveryObj->isProfile()) {
                $name = $deliveryObj->getNameWithParent();
            } else {
                $name = $deliveryObj->getName();
            }

            $shipment->setFields(array(
                'DELIVERY_ID' => $deliveryObj->getId(),
                'DELIVERY_NAME' => $name,
                'CURRENCY' => $order->getCurrency()
            ));

            $shipmentCollection->calculateDelivery();
        }
        /**/

        /*Payment*/
        $arPaySystemServiceAll = [];
        $paySystemId = 1;
        $paymentCollection = $order->getPaymentCollection();

        $remainingSum = $order->getPrice() - $paymentCollection->getSum();
        if ($remainingSum > 0 || $order->getPrice() == 0) {
            $extPayment = $paymentCollection->createItem();
            $extPayment->setField('SUM', $remainingSum);
            $arPaySystemServices = PaySystem\Manager::getListWithRestrictions($extPayment);

            $arPaySystemServiceAll += $arPaySystemServices;

            if (array_key_exists($paySystemId, $arPaySystemServiceAll)) {
                $arPaySystem = $arPaySystemServiceAll[$paySystemId];
            } else {
                reset($arPaySystemServiceAll);

                $arPaySystem = current($arPaySystemServiceAll);
            }

            if (!empty($arPaySystem)) {
                $extPayment->setFields(array(
                    'PAY_SYSTEM_ID' => $arPaySystem["ID"],
                    'PAY_SYSTEM_NAME' => $arPaySystem["NAME"]
                ));
            } else
                $extPayment->delete();
        }
        /**/

        $order->doFinalAction(true);
        $propertyCollection = $order->getPropertyCollection();

        $emailProperty = getPropertyByCode($propertyCollection, 'EMAIL');
        $emailProperty->setValue($email);
//    pre($paymentCollection);
        $fioProperty = getPropertyByCode($propertyCollection, 'FIO');
        $fioProperty->setValue($fio);

        $zipProperty = getPropertyByCode($propertyCollection, 'ZIP');
        $zipProperty->setValue($zip);

        $phoneProperty = getPropertyByCode($propertyCollection, 'ADRES');
        $phoneProperty->setValue($adres);

        $apt_locationProperty = getPropertyByCode($propertyCollection, 'APT_LOCATION');
        $apt_locationProperty->setValue($apt_location);


        $phoneProperty = getPropertyByCode($propertyCollection, 'PHONE');
        $phoneProperty->setValue($phone);

        $order->setField('CURRENCY', $currencyCode);
//Комментарий менеджера
        $order->setField('COMMENTS', 'Комментарии менеджера');
//Комментарий покупателя
        $order->setField('USER_DESCRIPTION', '');

        $order->save();

        $orderId = $order->GetId();
    }
}