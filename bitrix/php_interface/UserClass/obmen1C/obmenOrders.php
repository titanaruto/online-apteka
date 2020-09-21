<?php
class obmenOrders extends obmen {
    private $DB;

    public function runObmenOrders() {
        $this->runObmenOrdersIn();
        $this->runObmenOrdersOut();
    }
    public function runObmenOrdersIn() {

        // получаем список новых заказов
        $allOrders['NEW'] = $this->getOrdersLast('NEW');
        //pre('NEW');
        //pre($allOrders['NEW']);
        $this->insertOrdersInDB($allOrders['NEW']);
        unset($allOrders['NEW']);
        $allOrders['CHANGED'] = $this->getOrdersLast('CHANGED');
        //pre('CHANGED');
        //pre($allOrders['CHANGED']);
        $this->updateOrdersInDB($allOrders['CHANGED']);
        unset($allOrders['CHANGED']);
        // получаем список отмененных заказов
        $allOrders['CANCELED'] = $this->getOrdersLast('CANCELED');
        //pre('CANCELED');
        //pre($allOrders['CANCELED']);
        $this->updateCanseledOrdersInDB($allOrders['CANCELED']);
        unset($allOrders['CANCELED']);
        //return $result;
    }
    public function runObmenOrdersOut() {
        //получаем список возвращенных заказов
        $outOrders = $this->getOrdersFromDB();
        //pre($outOrders);
        //проверяем нужно ли что-то делать?
        //pre($outOrders);
        if($outOrders == 'Ok') return false;
        //получаем наполнение заказов товарами
        $arOrders = $this->getOrdersFillingFromDB($outOrders);
        //pre('444');
        unset($outOrders);
        //pre($arOrders);
        //разносим данные по товарам и заказам в битрикс
        $result = $this->spreadOrdersOnSite($arOrders);
        unset($arOrders);
        return $result;
    }
    public function __construct () {
        parent::__construct();
        $this->DB = new workWithDB;
        //pre($this->DB);
    }
    public function __destruct() {
        $this->DB->__destruct();
        parent::__destruct();
    }
    private function spreadOrdersOnSite($arOrders=null) {
        //pre('spreadOrdersOnSite');
        //pre($arOrders);
        //pre('1111');
        if($arOrders === null or !is_array($arOrders)===true) return false;
        foreach ($arOrders as $arOrder) {
            //pre($arOrder);
            //pre('222222222');
            $GoodsOrder = $this->compareGoodsItem($arOrder['GOODS']);
            //pre($GoodsOrder);
            //pre('333333333');
            if($GoodsOrder === 'Ok') {
                $CSaleOrder = new CSaleOrder;
               // pre($arOrder['BITRIXID']);
                $arBOrder = $CSaleOrder->GetByID($arOrder['BITRIXID']);
                //pre($arBOrder);
                //pre('4444444444');
                if ( count($arBOrder) > 0) {
                    //pre($arOrder['STATUSID']);
                    $rrr = $this->DB->convertSQLtoTime($arOrder['DATECANCELED']);
                    $arFields = array(
                        'PAYED' => $this->disConvertYorN($arOrder['PAYED']),
                        'CANCELED' => $this->disConvertYorN($arOrder['CANCELED']),
                        'REASON_CANCELED' => $arOrder['REASONCANCELED'],
                        'PRICE_DELIVERY' => $arOrder['PRICEDELIVERY'],
                        'STATUS_ID' => $arOrder['STATUSID'],
                        'PRICE' => $arOrder['PRICE'],
                        'CURRENCY' => $arOrder['CURRENCY'],
                        'USER_ID' => $arOrder['USERID'],
                        'PAY_SYSTEM_ID' => $arOrder['PAYSYSTEMID'],
                        'USER_DESCRIPTION' => $arOrder['USERDESCRIPTION'],
                        'ADDITIONAL_INFO' => $arOrder['ADDITIONALINFO'],
                        'PS_STATUS' => $arOrder['PSSTATUS'],
                        'PS_STATUS_CODE' => $arOrder['PSSTATUSCODE'],
                        'PS_STATUS_DESCRIPTION' => $arOrder['PSSTATUSDESCRIPTION'],
                        'PS_STATUS_MESSAGE' => $arOrder['PSSTATUSMESSAGE'],
                        'PS_SUM' => $arOrder['PSSUM'],
                        'PS_CURRENCY' => $arOrder['PSCURRENCY'],
                    );
                    if($arOrder['PSRESPONSEDATE'] != '0000-00-00 00:00:00'){
                        $arFields['PS_RESPONSE_DATE'] = $arOrder['PSRESPONSEDATE'];
                    }
                    if($arOrder['DATECANCELED'] != '0000-00-00 00:00:00') {
                        $arFields['DATE_CANCELED'] = $this->DB->convertSQLtoTime($arOrder['DATECANCELED']);
                    }
                    if($arOrder['DATEPAYED'] != '0000-00-00 00:00:00') {
                        $arFields['DATE_PAYED'] = $this->DB->convertSQLtoTime($arOrder['DATEPAYED']); //
                    }
                    if($arOrder['STATUSID']=='F') {
                        unset($arFields);
                        $arFields = array(
                        'STATUS_ID' => $arOrder['STATUSID'],
                        'PAYED' => $this->disConvertYorN($arOrder['PAYED']),
                        );
                    }

        /************************************************ <COD> ************************************************/
                    $this->ordProprtyUpdate($arOrder['BITRIXID'],7,$arOrder['SHIFR1C']);//1C_LOCATION
                    $prop_val_5=$this->getLocationTextValue($arOrder['SHIFR1C']);
                    $this->ordProprtyUpdate($arOrder['BITRIXID'],5,$prop_val_5);//1C_LOCATION

        /************************************************ </COD> ************************************************/
                    if($arOrder['BITRIXID'] < 29857) {
                        $this->ordProprtyUpdate($arOrder['BITRIXID'],8,$arOrder['USERDELIVERYSTREETID']); // LOCATION (old Method)
                    }
                    $this->ordProprtyUpdate($arOrder['BITRIXID'],9,$arOrder['DISCOUNTCARD']);//DKCARD

                    if($arOrder['STATUSID'] != 'P') {
                        $dddd = $CSaleOrder->Update($arOrder['BITRIXID'], $arFields);
                        if($arOrder['STATUSID'] == 'D') {
                            $CSaleOrder->Update($arOrder['BITRIXID'], array('STATUS_ID' => 'D'));
                        }
                        if($arOrder['STATUSID'] == 'F') {
                            if(intval($arOrder['PAYSYSTEMID']) === 5){
                                $CSaleOrder->Update($arOrder['BITRIXID'], array('STATUS_ID' => 'F','PAYED' => 'Y'));
                            } else {
                                $CSaleOrder->Update($arOrder['BITRIXID'], array('STATUS_ID' => 'F'));
                            }
                        }
                        $this->sendMail($arOrder, $arOrder['STATUSID']);
                    }
                } else {
                    //не найден заказ-корреспондент
                    $orderID=$arOrder['BITRIXID'];
                    $this->addOrderErrorLog(
                            "Не получилось обновить заказ OrderID=$orderID на сайте",
                            'Запись свойств заказа',
                            $orderID
                            );
                    echo "Не получилось обновить заказ OrderID=$orderID на сайте<br/>";
                }
            }
            $this->delCangeFromObmen($arOrder['BITRIXID']);
            //die();
        }
        return true;
    }
    private function delCangeFromObmen($orderID=null) {
        //pre('delCangeFromObmen');
        //pre($orderID);
        $orderID = intval($orderID);
        if(null === $orderID || true === !is_int($orderID)) return false;
        $sql ="DELETE FROM `ord_obmen` WHERE `ORDERID`='$orderID' AND `TYPE`>'0'";
        $result = $this->DB->freeQuery($sql);
        $this->addOrderErrorLog(
            "Заказ $orderID получен из таблицы обмена",
            'Заказ загружен на ПП',
            $orderID
        );
        if($result !== 'Ok') {
            $this->addOrderErrorLog(
                "Не получилось удалить запись заказа OrderID=$orderID из таблицы ord_obmen",
                'Удаление записи из таблицы БД',
                $orderID
            );
            echo "Не получилось удалить запись заказа OrderID=$orderID из таблицы ord_obmen <br/>";
        }
    }
    private function ordProprtyUpdate($orderID=null, $propID=null, $newValue=null){
        if($orderID===null || $propID===null || $newValue===null){
            return false;
        }
        //pre($newValue);
        $arSelectFields = array();
        $arFilter = array();
        $arFilter['ORDER_ID'] = $orderID;
        $arFilter['ORDER_PROPS_ID'] = $propID;
        $CSaleOrderPropsValue = new CSaleOrderPropsValue;
        $dbProps_order = $CSaleOrderPropsValue->GetList(
            array('ID'=>'DESC'),
            $arFilter,
            false,
            false,
            $arSelectFields
        );
        if($props_order = $dbProps_order->Fetch()){
            //pre($props_order);
            $CSaleOrderPropsValue->Update(
                $props_order['ID'], // ID записи в БД, можно получить только через
                                    // CSaleOrderPropsValue->GetList
                array(
                    'ORDER_ID'=>$orderID, // ID заказа
                    'ORDER_PROPS_ID' => $propID, //ID код свойства из свойств заказа
                    'VALUE' => $newValue // ID нового значения
                )
            );
        }
    }
    private function compareGoodsItem($DBGoods=null) {
        if($DBGoods === null or !is_array($DBGoods)===true) return false;
        pre($DBGoods);
        $CSaleBasket = new CSaleBasket;
        $arAddGoods = $DBGoods;
        $arItems = array();
        $dbBasketItems = $CSaleBasket->GetList(
            array("ID" => "ASC"),
            array("ORDER_ID" => intval($DBGoods['0']['BITRIXORDERID'])),
            false,
            false,
            array()
        );
        while ($arItem = $dbBasketItems->Fetch()) {
            //pre($arItem);
            if(true===!empty($arItem['NAME'])){
                $delete = true;
                foreach ($DBGoods as $key => $staff) {
                    // если товар уже есть в корзине и его компоненты не пусты:
                    if($staff['EXTKEY'] == $arItem['PRODUCT_XML_ID'] &&
                            $staff['BITRIXORDERID'] != '' && $staff['BITRIXORDERID'] != 0 &&
                            $staff['EXTKEY'] != '' && $staff['PRICE'] != '' && $staff['PRICE'] != 0 &&
                            $staff['QUANTITY'] != '' && $staff['QUANTITY'] != 0 &&
                            $staff['SUMM'] != '' && $staff['SUMM'] != 0){
                        $delete = false;
                        $arFields = array();
                        if($staff['PRICE'] <> $arItem['PRICE']) {
                                $arFields = array(
                                    'PRICE' => $staff['PRICE'],
                                );
                        }
                        if($staff['QUANTITY'] <> $arItem['QUANTITY']) {
                            //pre('fff');
                            $arFields = array(
                                'QUANTITY' => $staff['QUANTITY'],
                            );
                        }
                        //pre(2222);
                        if(!empty($arFields)){
                            //pre(4444);
                            $res = $CSaleBasket->Update($arItem['ID'],$arFields);
                            //pre($res);
                            if(true === (false===$res)) {
                                //Error!
                                $orderID = $DBGoods['0']['BITRIXORDERID'];
                                $this->addOrderErrorLog(
                                        "Не найдены товары заказа OrderID=$orderID не были обновлены",
                                        'Обновление товаров в заказе',
                                        $orderID
                                        );
                                echo "Не найдены товары заказа OrderID=$orderID не были обновлены <br/>";
                            }
                        }
                        unset($arAddGoods[$key]);
                    }
                }
                // если товар удалили в колл-центре:
                if(true === (true===$delete)) {
                    //pre('1111');
                    $del = $CSaleBasket->Delete($arItem['ID']);
//                        $del;
                    if(true === (false===$del)) {
                        //Error!
                        $orderID = $DBGoods['0']['BITRIXORDERID'];
                        $this->addOrderErrorLog(
                                "Товарные позиции заказа OrderID=$orderID не были удалены",
                                'Удаление товаров из заказа',
                                $orderID
                        );
                        echo "Товарные позиции заказа OrderID=$orderID не были удалены <br/>";
                    }
                }
            }
        }
        $addGoods = $this->addNewGoodsToOrder($arAddGoods);
        //pre($addGoods);
        if(true === (false===$addGoods)) {
            //Error!
            $this->addOrderErrorLog(
                    "Товарные позиции заказа OrderID=$orderID не были добавлены",
                    'Добавление товаров в заказ',
                    $orderID
            );
            echo "Не найдены товары заказа OrderID=$orderID в таблице ord_staff_out <br/>";
        }
        return 'Ok';
    }
    private function addNewGoodsToOrder($arAddGoods=null) {
    //pre($arAddGoods);
    if($arAddGoods === null or !is_array($arAddGoods)===true) return false;
    $CSaleBasket = new CSaleBasket;
    foreach ($arAddGoods as $key => $staff) {
        $elm = array();
        //$name = mysqli_real_escape_string ($staf['NAME']);
        $elm = $this->getRecipientGoods($staff['EXTKEY']);
        if(count($elm) > 0){
            $filds = array(
                    "PRODUCT_ID" => $elm['ID'],
                    "FUSER_ID" => 10,
                    "PRICE" => $staff['PRICE'],
                    "CURRENCY" => $staff['CURRENCY'],
                    "QUANTITY" => $staff['QUANTITY'],
                    "LID" => 's1',
                    "DELAY" => "N",
                    "CAN_BUY" => "Y",
                    "NAME" => mysqli_real_escape_string ($staff['NAME']),
                    "DETAIL_PAGE_URL" => $elm['DETAIL_PAGE_URL'],
                    "PRODUCT_XML_ID" => $staff['EXTKEY'],
                    "ORDER_ID" => $staff['BITRIXORDERID'],

            );
            pre($filds);
            $zzz = $CSaleBasket->Add($filds);
            //pre($zzz);
            $CSaleBasket->OrderBasket(intval($staff['BITRIXORDERID']),10,SITE_ID);
        } else {
            $orderID = $arAddGoods['0']['BITRIXORDERID'];
            $this->addOrderErrorLog(
                    "Товарные позиции заказа OrderID=$orderID не найдены в каталоге",
                    'Добавление товаров в заказа',
                    $orderID
                    );
            echo "Товарные позиции заказа OrderID=$orderID не найдены в каталоге <br/>";
        }
    }
    return 'Ok';
}
    private function getOrdersFillingFromDB($outOrders=null) {
        if($outOrders === null or !is_array($outOrders)===true) return false;
        $arOrders = array();
        foreach ($outOrders as $outOrder) {
            $arOrder = array();
            $arOrder = $this->getOrederFromDB($outOrder['ORDERID']);
            $arOrder['GOODS']=$this->getStaffFromDB($outOrder['ORDERID']);
            $arOrders[] = $arOrder;
            //pre($arOrders);
        }
        return $arOrders;
    }
    private function getStaffFromDB($orderID=null) {
        $orderID = intval($orderID);
        if(null === $orderID || true === !is_int($orderID)) return false;
        //!!!!! ord_staff_out !!!!
        $sql = "SELECT * FROM `ord_staff_out` WHERE `BITRIXORDERID`='$orderID' AND `DROPOUT`='0'";
        $result = $this->DB->freeQuery($sql);
        if($result == 'Ok') {
            $this->addOrderErrorLog(
                "Не найдены товары заказа OrderID=$orderID в таблице ord_staff_out",
                'Получение товарного наполнения заказа',
                $orderID
            );
            echo "Не найдены товары заказа OrderID=$orderID в таблице ord_staff_out<br/>";
            return array();
        }
        // отметить товары отмененные в заказе как исключенные из заказа
        $this->checkDropoutStaff($orderID, $result);
        return $result;
    }
    private function checkDropoutStaff ($orderID=null, $result=array()) {
        if(null === $orderID || true === !is_int($orderID)) return false;
        if(empty($result) || true===!is_array($result)) return false;
        $sql = "SELECT * FROM `ord_staff_in` WHERE `BITRIXORDERID`='$orderID' AND `DROPOUT`='0'";
        //pre($sql);
        $arResult = $this->DB->freeQuery($sql);
        if($arResult == 'Ok') {
            $this->addOrderErrorLog(
                "Не найдены товары заказа OrderID=$orderID в таблице ord_staff_in",
                'Получение товарного наполнения заказа',
                $orderID
            );
            echo "Не найдены товары заказа OrderID=$orderID в таблице ord_staff_in<br/>";
            return array();
        }
        foreach($arResult as $key=>$stafIN){
            foreach($result as $stafOUT){
                if ($stafIN['EXTKEY'] ===$stafOUT['EXTKEY']) {
                    unset($arResult[$key]);
                }
            }
        }
        foreach($arResult as $stafIN){
            $sql  = " UPDATE `ord_staff_in` SET ";
            $sql .= " `DROPOUT`='1' ";
            $sql .= " WHERE `ID`='$stafIN[ID]'";
            $result = $this->DB->freeQuery($sql);
            if($result != 'Ok') {
                $this->addOrderErrorLog(
                    "Не удалось пометить удаленные товары в заказе OrderID=$orderID в таблице ord_staff_in",
                    'отметить товары отмененные в заказе как исключенные из заказа',
                    $orderID
                );
                echo "Не удалось пометить удаленные товары в заказе OrderID=$orderID в таблице ord_staff_in";
                return array();
            }
        }
    }
    private function getOrederFromDB($orderID=null) {
        $orderID = intval($orderID);
        if(null === $orderID || true === !is_int($orderID)) return false;
        //!!!!! ord_orders_out !!!!
        $sql = "SELECT * FROM `ord_orders_out` WHERE `BITRIXID`='$orderID'";
        $result = $this->DB->freeQuery($sql);
        if(empty($result['0'])) {
            $this->addOrderErrorLog(
                "Не найден заказ OrderID=$orderID в таблице ord_orders_out",
                'Получение свойств заказа',
                $orderID
            );
            echo "Не найден заказ OrderID=$orderID в таблице ord_orders_out";
            return false;
        }
        return $result['0'];
    }
    private function getOrdersFromDB() {
        $outOrders = array();
        $sql = "SELECT * FROM `ord_obmen` WHERE `TYPE`>'0'";
        $result = $this->DB->freeQuery($sql);
        return $result;
    }
    private function getOrdersLast($type = null) {
        //return false;
        if($type === null) return false;
        $arOrders = array();
        $CSaleOrder = new CSaleOrder;
        $arFilter = array();
        if($type==='NEW') {
            $arFilter = array(
                    //'ID'=>29939
                    'CANCELED' => 'N',
                    'STATUS_ID' => 'N',
//                    '>=DATE_INSERT' => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), mktime(0, 0, 0, date('m') - 2, 1, date("Y")))
            );
        }
        if($type==='CHANGED') {
            $arFilter = array(
                'CANCELED' => 'N',
                'STATUS_ID' => 'P',
//                '>=DATE_INSERT' => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), mktime(0, 0, 0, date('m') - 2, 1, date("Y")))
            );
        }
        if($type==='CANCELED') {
            $arFilter = array(
                    'CANCELED' => 'Y',
                    '!STATUS_ID' => array('A','F'),
//                    '>=DATE_INSERT' => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), mktime(0, 0, 0, date('m') - 2, 1, date("Y")))
            );
        }
        if(!empty($arFilter)){
            $dbOrders = $CSaleOrder->GetList(Array("ID"=>"ASC"), $arFilter, false, false, array());
            while ($arOrder = $dbOrders->Fetch()) {
                //pre($arOrder);
                $arOrders[$arOrder['ID']] = $this->getOrdersPropertyes($arOrder);
            }
            return $arOrders;
        }
    }
    private function getOrdersPropertyes($arOrder=null) {
        if($arOrder === null or !is_array($arOrder)===true) return false;
        $CSaleOrderPropsValue = new CSaleOrderPropsValue;
        $arSelectFields = array('*');
        $arFilter = array('ORDER_PROPS_ID' => '1', "ORDER_ID" => $arOrder['ID']);
        $dbProps_order = $CSaleOrderPropsValue->GetList(
                array('ID'=>'DESC'),
                $arFilter,
                false,
                false,
                $arSelectFields
                );
        if ($props_order = $dbProps_order->Fetch()){
            $arOrder['PROPERTIES']['FIO'] = $props_order;
        }
        $arFilter['ORDER_PROPS_ID'] = 2;
        $dbProps_order = $CSaleOrderPropsValue->GetList(
                array('ID'=>'DESC'),
                $arFilter,
                false,
                false,
                $arSelectFields
                );
        if ($props_order = $dbProps_order->Fetch()){
            $arOrder['PROPERTIES']['EMAIL'] = $props_order;
        }
        $arFilter['ORDER_PROPS_ID'] = 3;
        $dbProps_order = $CSaleOrderPropsValue->GetList(
                array('ID'=>'DESC'),
                $arFilter,
                false,
                false,
                $arSelectFields
                );
        if ($props_order = $dbProps_order->Fetch()){
            $arOrder['PROPERTIES']['PHONE'] = $props_order;
        }
        $arFilter['ORDER_PROPS_ID'] = 4;
        $dbProps_order = $CSaleOrderPropsValue->GetList(
                array('ID'=>'DESC'),
                $arFilter,
                false,
                false,
                $arSelectFields
                );
        if ($props_order = $dbProps_order->Fetch()){
            $arOrder['PROPERTIES']['ZIP'] = $props_order;
        }

        $arFilter['ORDER_PROPS_ID'] = 7;
        $dbProps_order = $CSaleOrderPropsValue->GetList(
                array('ID'=>'DESC'),
                $arFilter,
                false,
                false,
                $arSelectFields
                );
        if ($props_order = $dbProps_order->Fetch()){
            $arOrder['PROPERTIES']['APT_LOCATION'] = $props_order;
        }

        $arFilter['ORDER_PROPS_ID'] = 8;
        $dbProps_order = $CSaleOrderPropsValue->GetList(
                array('ID'=>'DESC'),
                $arFilter,
                false,
                false,
                $arSelectFields
                );
        if ($props_order = $dbProps_order->Fetch()){
            $CSaleLocation = new CSaleLocation;
            $db_vars = $CSaleLocation->GetList(
            array("ID"=>"ASC","COUNTRY_NAME_LANG" => "ASC","CITY_NAME_LANG" => "ASC"),
            array("ID"=>$props_order['VALUE'],"LID" => LANGUAGE_ID),
            false, false, array()
            );
            while ($loc = $db_vars->Fetch()){
                $arOrder['PROPERTIES']['LOCATION']=$loc;
            }
        }

        $arFilter['ORDER_PROPS_ID'] = 9;
        $dbProps_order = $CSaleOrderPropsValue->GetList(
                array('ID'=>'DESC'),
                $arFilter,
                false,
                false,
                $arSelectFields
                );
        if ($props_order = $dbProps_order->Fetch()){
            $arOrder['PROPERTIES']['DKCARD'] = $props_order;
        }

//$props_array = [];

//        $obProps = Bitrix\Sale\Internals\OrderPropsValueTable::getList(array('filter' => array('ORDER_ID' => $arOrder['ID'])));
//        while($prop = $obProps->Fetch()) {
//            $props_array[$prop["CODE"]] = $prop["VALUE"];
//        }
//$arOrder['PROPERTIES']['DELIVERY_CITY'] = $props_array[np_delivery_city];


        //pre('1111');
//pre($arOrder);
        return $arOrder;
    }
    private function insertOrdersInDB($arOrders=null) {
        if($arOrders === null or !is_array($arOrders)===true) return false;
        foreach ($arOrders as $key => $arOrder) {
            //pre($arOrder);
            // проверяем наличие ИД заказа в табличке "ord_orders_in" (таблица заказов)
            $isOrderThere = $this->isOrderThere($arOrder);
            //pre([$arOrder["ID"], $isOrderThere]);
            //pre('2');
            // если не нашли такого заказа
            if($isOrderThere < 1) {
                // пишем в табличку "ord_staff_in" (таблица товаров в заказах)
                $insOrdStafInTableStafIn = $this->insOrdStafInTableStafIn($arOrder['ID']);
                //pre('$insOrdStafInTableStafIn');
                // если товары записались
//pre([$arOrder["ID"], $isOrderThere, $insOrdStafInTableStafIn]);
                if($insOrdStafInTableStafIn==='Ok' ) {
                    //записываем информацию о заказе в таблицу "ord_orders_in"
                    $this->insOrdInTableOrdIn($arOrder);
                    //pre('3333');
                    // проверяем наличие ИД заказа в табличке "ord_orders_in"
                    $isOrderThere = $this->isOrderThere($arOrder);
                    //pre('$isOrderThere');
                    // если запись заказа присутствует в таблице "ord_orders_in"
                    if($isOrderThere <> 0) {
                        // передаем заказ в таблицу заказов подлежащих обмену
                        $inObmen = $this->insInTblObmen($arOrder['ID']);
                        if($inObmen === 'Ok') {
                            $status = CSaleOrder::StatusOrder($arOrder['ID'], "B");
                            if(true === (false===$status)){
                                $this->addOrderErrorLog(
                                    "Статус заказа OrderID=$arOrder[ID] не получилось изменить на В",
                                    'Изменение статуса заказа на сайте',
                                    $arOrder['ID']
                                );
                                echo "Статус заказа OrderID=$arOrder[ID] не получилось изменить на В";
                            }
                        }else {
                            $this->addOrderErrorLog(
                                "Не удалось записать данные заказа OrderID=$arOrder[ID] в таблицу ord_obmen",
                                'Запись ИД заказа в таблицу обмена',
                                $arOrder['ID']
                            );
                            echo "Не удалось записать данные заказа OrderID=$arOrder[ID] в таблицу ord_obmen";
                        }
                    }else {
                        $this->addOrderErrorLog(
                            "Свойства заказа OrderID=$arOrder[ID] не получилось записать в таблицу ord_orders_in",
                            'Запись свойств заказа в БД',
                            $arOrder['ID']
                        );
                        echo "Свойства заказа OrderID=$arOrder[ID] не получилось записать в таблицу ord_orders_in";
                    }
                }
            }
        }
    }
    private function updateOrdersInDB($arOrders=null) {
        if($arOrders === null or !is_array($arOrders)===true) return false;
        foreach ($arOrders as $key => $arOrder) {
            $isOrderThere = $this->isOrderThere($arOrder);
            if($isOrderThere > 0) {
                //pre($arOrder);
                $datestatus = $this->DB->convert_time_to_sql($arOrder['DATE_STATUS']);
                $datepayed = $this->DB->convert_time_to_sql($arOrder['PSRESPONSEDATE']);
                $sql  = " UPDATE `ord_orders_in` SET ";
                $sql .= " `STATUSID`= 'P', `PAYED`='1', `DATEPAYED`='$datepayed', ";
                $sql .= " `PSSUM`= '$arOrder[SUM_PAID]', ";
                $sql .= " `DATESTATUS`= '$datestatus' ";
                $sql .= " WHERE `BITRIXID`='$arOrder[ID]'";
                $result = $this->DB->freeQuery($sql);
                if($result=='Ok'){
                    $this->insInTblObmen($arOrder['ID'],0,1);
                    $status = CSaleOrder::StatusOrder($arOrder['ID'], "D");
                    $status = CSaleOrder::StatusOrder($arOrder['ID'], "DN");
                }
            }
        }
        //return $result;
    }
    private function updateCanseledOrdersInDB($arOrders=null) {
        //pre('updateCanseledOrdersInDB');
        if($arOrders === null or !is_array($arOrders)===true) return false;
        $result = array();
        foreach ($arOrders as $key => $arOrder) {
            //pre($arOrder);
            // проверяем наличие заказа в таблице ord_orders_in
            $isOrderThere = $this->isOrderThere($arOrder);
            //pre($isOrderThere);
            if($isOrderThere < 1) {
                $insOrdStafInTableStafIn = $this->insOrdStafInTableStafIn($arOrder['ID']);
                if($insOrdStafInTableStafIn==='Ok' ) {
                    $this->insOrdInTableOrdIn($arOrder);
                }
            }
            $updOrdInTableOrdIn = $this->updOrdInTableOrdIn($arOrder);
            if( $updOrdInTableOrdIn === 'Ok' ) {
                $inObmen = $this->insInTblObmen($arOrder['ID']);
                if($inObmen === 'Ok') {
                    $status = CSaleOrder::StatusOrder($arOrder['ID'], "A");
                    $result[$arOrder['ID']]= 'Ok!';
                    if(true === (false===$status)){
                        $this->addOrderErrorLog(
                                "Статус заказа OrderID=$arOrder[ID] не получилось изменить на A",
                                'Изменение статуса заказа на сайте',
                                $arOrder['ID']
                        );
                        echo "Статус заказа OrderID=$arOrder[ID] не получилось изменить на A<br/>";
                    }
                }else {
                    $this->addOrderErrorLog(
                        "Не удалось записать данные заказа OrderID=$arOrder[ID] в таблицу ord_obmen",
                        'Запись ИД заказа в таблицу обмена',
                        $arOrder['ID']
                    );
                    echo "Не удалось записать данные заказа OrderID=$arOrder[ID] в таблицу ord_obmen<br/>";
                }
            } else {
                $this->addOrderErrorLog(
                    "Не удалось изменить запись ОТМЕНЕННОГО заказа OrderID=$arOrder[ID] в таблице ord_staff_in",
                        'Изменение заказа при отмене',
                        $arOrder['ID']
                );
                echo "Не удалось изменить запись ОТМЕНЕННОГО заказа OrderID=$arOrder[ID] в таблице ord_staff_in <br/>";
            }
        }
        return $result;
    }
    private function disConvertYorN($str) {
        $int = intval($str);
        if($int > 0) {
            return 'Y';
        } else {
            return 'N';
        }
        return false;
    }
    private function convertYorN($str) {
        if($str === 'Y') {
            return 1;
        } else {
            return 0;
        }
        return false;
    }
    private function isOrderThere($arOrder = null) {
        if($arOrder === null or !is_array($arOrder) === true) return false;
        $sql  = " SELECT COUNT(*) as `NUMR` FROM `ord_orders_in` WHERE ";
        $sql .= " `BITRIXID`='$arOrder[ID]' ";
        $result = $this->DB->freeQuery($sql);
        return $result[0]['NUMR'];
    }
    private function insOrdInTableOrdIn($arOrder = null) {
        if($arOrder === null or !is_array($arOrder)===true) return false;
        $payed = $this->convertYorN($arOrder['PAYED']);
        $canseled = $this->convertYorN($arOrder['CANCELED']);
        $alloweddelivery = $this->convertYorN($arOrder['ALLOW_DELIVERY']);
        if(!empty($arOrder['DATE_PAYED'])) {
            $datepayed = $this->DB->convert_time_to_sql($arOrder['DATE_PAYED']);
        } else {
            $datepayed = '';
        }
        if(!empty($arOrder['DATE_CANCELED'])) {
            $datecanseled = $this->DB->convert_time_to_sql($arOrder['DATE_CANCELED']);
        } else {
            $datecanseled = '';
        }
        $datestatus = $this->DB->convert_time_to_sql($arOrder['DATE_STATUS']);
        $dateinsert = $this->DB->convert_time_to_sql($arOrder['DATE_INSERT']);
        $dateupdate = $this->DB->convert_time_to_sql($arOrder['DATE_UPDATE']);
        if(!empty($arOrder['DATE_ALLOW_DELIVERY'])) {
            $dateallowdelivety = $this->DB->convert_time_to_sql($arOrder['DATE_ALLOW_DELIVERY']);
        } else {
            $dateallowdelivety = '';
        }
        $city = '';
        $cityID = '';
        $street = '';
        $streetID = 0;

        //pre($arOrder['PROPERTIES']['APT_LOCATION']['VALUE']);

        //pre($arOrder);
        if(!empty($arOrder['PROPERTIES']['LOCATION'])) {
            $city = $arOrder['PROPERTIES']['LOCATION']['REGION_NAME'];
            $cityID = $arOrder['PROPERTIES']['LOCATION']['REGION_ID'];
            $street = $arOrder['PROPERTIES']['LOCATION']['CITY_NAME'];
            $streetID = $arOrder['PROPERTIES']['LOCATION']['CITY_ID'];
        }
        $location = $arOrder['PROPERTIES']['APT_LOCATION']['VALUE'];
        //pre($location);
        //$adr_text_text = $this->getLocationTextValue($location);
        $street =  $this->getLocationTextValue($location);
        //pre($street);
        /*
        $locationSH = '';
        $sql = '';
        $sql  = " SELECT `IDLOCATION` FROM `loc_location` WHERE ";
        $sql .= " `CODE1C`='$location'";
        $resultA = $this->DB->freeQuery($sql);
        $streetID = $resultA['0']['IDLOCATION'];
        */
        //pre($location);

        $fio = mysqli_real_escape_string ($arOrder['PROPERTIES']['FIO']['VALUE']);
        $email = mysqli_real_escape_string ($arOrder['PROPERTIES']['EMAIL']['VALUE']);
        $phone = $arOrder['PROPERTIES']['PHONE']['VALUE'];
        $zip = $arOrder['PROPERTIES']['ZIP']['VALUE'];
        if($zip == 'CARD_CARD'){
            $arOrder['PAY_SYSTEM_ID'] = 7;
        } else {
            $arOrder['PAY_SYSTEM_ID'] = 5;
        }
        $zip = 49000;
        $dkcard = mysqli_real_escape_string ($arOrder['PROPERTIES']['DKCARD']['VALUE']);
        $arOrder['USER_DESCRIPTION'] = mysqli_real_escape_string ($arOrder[USER_DESCRIPTION]);

        $props_array = [];

        $obProps = Bitrix\Sale\Internals\OrderPropsValueTable::getList(array('filter' => array('ORDER_ID' => $arOrder['ID'])));
        while($prop = $obProps->Fetch()) {
            $props_array[$prop["CODE"]] = str_replace("'", "\'", trim($prop["VALUE"]));
        }



        $props_array[ukr_delivery_area] = 'Івано-Франківська';
        $props_array[ukr_delivery_city] = 'Яблунівка (Тисменицький р-н)';
        $props_array[ukr_delivery_warehouse] = '5705';
        $props_array[ukr_area_id] = '9';
        $props_array[ukr_district_id] = '15';
        $props_array[ukr_district_name] = 'Тисменицький';
        $props_array[ukr_city_id] = '502';
        $props_array[ukr_street_id] = '505394';
        $props_array[ukr_street_name] = 'Стасюка';
        $props_array[ukr_house_num] = '4';
        $props_array[ukr_postcode] = '77460';
        $props_array[ukr_street_type] = 'вулиця';

//        $props_array[courier_lat] = '48.4676536';
//        $props_array[courier_lng] = '35.0365239';

//        $props_array[delivery_cost] = '50';
//
//        $props_array[courier_address] = 'Днепр, Старокозацкая, 154-а, 11';

        $props_array[ukr_delivery_city] = trim(preg_replace("/\([^)]+\)/","", $props_array[ukr_delivery_city]));

        $sql  = " INSERT INTO `ord_orders_in` ";
        $sql .= " (`ID`, `BITRIXID`, `PAYED`, `DATEPAYED`, `CANCELED`, `DATECANCELED`, ";
        $sql .= " `REASONCANCELED`, `STATUSID`, `DATESTATUS`, `PRICEDELIVERY`, `ALLOWDELIVERY`, `DATEALLOWDELIVERY`, `PRICE`, ";
        $sql .= " `CURRENCY`, `DISCOUNTCARD`, `USERID`, `PAYSYSTEMID`, `DELIVERYID`, `DATEINSERT`, `DATEUPDATE`, ";
        $sql .= " `USERDESCRIPTION`, `ADDITIONALINFO`, `PSSTATUS`, `PSSTATUSCODE`, `PSSTATUSDESCRIPTION`, `PSSTATUSMESSAGE`, ";
        $sql .= " `PSSUM`, `PSCURRENCY`, `PSRESPONSEDATE`, `USERFIO`, `USEREMAIL`, `USERPHONE`, `USERZIP`, ";
        $sql .= " `USERDELIVERYCOUNTRY`, `USERDELIVERYCOUNTRYID`, `USERDELIVERYSITY`, `USERDELIVERYSITYID`, ";
        $sql .= " `USERDELIVERYSTREET`, `USERDELIVERYSTREETID`, `SHIFR1C`, `RECIPIENTCITYNAME`, `RECIPIENTAREA`, `RECIPIENTADDRESSNAME`, `RECIPIENTHOUSE`, `RECIPIENTFLAT`, `RECIPIENTNAME`, `DELIVERYTYPE`, `RECIPIENTWAREHOUSE`, `AREAUKR`, `CITYUKR`, `WAREHOUSEUKR`, `AREAIDUKR`, `DISTRICTIDUKR`, `DISTRICTUKR`, `CITYIDUKR`, `STREETIDUKR`, `STREETNAMEUKR`, `HOSENUMUKR`, `POSTCODEUKR`, `STREETYPEUKR`, `COURIERLAT`, `COURIERLNG`, `DELIVERYCOST`, `COURIERADDRESS`) ";
        $sql .= " VALUES ";
        $sql .= " ('', '$arOrder[ID]','$payed','$datepayed','$canseled', '$datecanseled', ";
        $sql .= " '$arOrder[REASON_CANCELED]','$arOrder[STATUS_ID]','$datestatus','$arOrder[PRICE_DELIVERY]','$alloweddelivery','$dateallowdelivety', '$arOrder[PRICE]', ";
        $sql .= " '$arOrder[CURRENCY]','$dkcard','$arOrder[USER_ID]','$arOrder[PAY_SYSTEM_ID]','$arOrder[DELIVERY_ID]','$dateinsert','$dateupdate', ";
        $sql .= " '$arOrder[USER_DESCRIPTION]','$arOrder[ADDITIONAL_INFO]','','','','', ";
        //$arOrder[DATE_PAYED] - дата-время оплаты
        //$arOrder[SUM_PAID] - оплаченная сумма
        $sql .= " '$arOrder[SUM_PAID]','','$arOrder[DATE_PAYED]','$fio','$email','$phone','$zip','Украина','','$city','$cityID',";
        $sql .= " '$street','$streetID', '$location', '$props_array[np_delivery_city]', '$props_array[np_delivery_area]', '$props_array[np_delivery_street]', '$props_array[np_delivery_house]', '$props_array[np_delivery_flat]', '$props_array[FIO]', '$props_array[np_delivery_variant]', '$props_array[np_delivery_warehouse]', '$props_array[ukr_delivery_area]', '$props_array[ukr_delivery_city]', '$props_array[ukr_delivery_warehouse]', '$props_array[ukr_area_id]', '$props_array[ukr_district_id]', '$props_array[ukr_district_name]', '$props_array[ukr_city_id]', '$props_array[ukr_street_id]', '$props_array[ukr_street_name]', '$props_array[ukr_house_num]', '$props_array[ukr_postcode]', '$props_array[ukr_street_type]', '$props_array[courier_delivery_lat]', '$props_array[courier_delivery_lng]', '$props_array[delivery_cost]', '$props_array[courier_delivery_address]') ";
        //pre($sql);
        $result = $this->DB->freeQuery($sql);
        if($arOrder[STATUS_ID]=='P'){
            sendMail($arOrder, 'P');
        }
        return $result;
    }
    private function updOrdInTableOrdIn($arOrder = null) {
        if($arOrder === null or !is_array($arOrder)===true) return false;
        $canseled = $this->convertYorN($arOrder['CANCELED']);
        if(!empty($arOrder['DATE_CANCELED'])) {
            $datecanseled = $this->DB->convert_time_to_sql($arOrder['DATE_CANCELED']);
        } else {
            $datecanseled = '';
        }
        $sql  = " UPDATE `ord_orders_in` SET ";
        $sql .= " `CANCELED`= '$canseled', `DATECANCELED`='$datecanseled', `REASONCANCELED`='$arOrder[REASON_CANCELED]'";
        $sql .= " WHERE `BITRIXID`='$arOrder[ID]'";
        $result = $this->DB->freeQuery($sql);
        if(empty($result)) return 'Ok';
        return $result;
    }
    private function insOrdStafInTableStafIn($arOrderID = null) {
        $arOrderID = intval($arOrderID);
        if(null === $arOrderID || true === !is_int($arOrderID)) return false;
        $arStaff = array();
        $res = CSaleBasket::GetList(array("ID" => "ASC"), array("ORDER_ID" => $arOrderID)); // ID заказа
        while ($staf = $res->Fetch()) {
            $stafInDB = $this->checkStafinDB($arOrderID,$staf['PRODUCT_ID']);
            if($stafInDB === 'Ok' ) {
                //pre('aa');
                $this->insertStafinDB($staf);
            }
            $arStaff[]=$staf;
        }
        $request = 'Ok';
        foreach ($arStaff as $staf) {
            $stafInDB = $this->checkStafinDB($arOrderID, $staf['PRODUCT_ID']);
            if($stafInDB==='Ok') {
                $request = false;
                $this->addOrderErrorLog(
                        "Товар BITRIXID=$staf[ID] из заказа OrderID=$arOrderID не получилось записать в БД",
                        'Запись товаров в БД',
                        $arOrderID
                );
                echo 'Error: Can not write staff BITRIXID='.$staf['PRODUCT_ID'].' in DB!\n';
            }
        }
        return $request;
    }
    private function insertStafinDB ($staf=null) {
        if($staf === null or !is_array($staf)===true) return false;
        $summ = round($staf['PRICE'] * $staf['QUANTITY'], 2);
        $name = mysqli_real_escape_string ($staf['NAME']);
        $sql  = " INSERT INTO `ord_staff_in` ";
        $sql .= " (`ID`,`BITRIXORDERID`, `BITRIXID`, `EXTKEY`, `PRICE`, `CURRENCY`, ";
        $sql .= " `QUANTITY`,`NAME`,`SUMM`,`DROPOUT`) ";
        $sql .= " VALUES ";
        $sql .= " ('','$staf[ORDER_ID]','$staf[PRODUCT_ID]','$staf[PRODUCT_XML_ID]', ";
        $sql .= " '$staf[PRICE]', '$staf[CURRENCY]','$staf[QUANTITY]','$name','$summ','0') ";
        //pre($sql);
        $result = $this->DB->freeQuery($sql);
        //pre('$sql');
        return $result;
    }
    private function checkStafinDB ($arOrderID=null, $staffID = null) {
        $arOrderID = intval($arOrderID);
        $staffID = intval($staffID);
        if(null === $arOrderID || true === !is_int($arOrderID)) return false;
        if(null === $staffID || true === !is_int($staffID)) return false;
        $sql = "SELECT * FROM `ord_staff_in` WHERE `BITRIXORDERID`=$arOrderID AND `BITRIXID`=$staffID";
        $result = $this->DB->freeQuery($sql);
        return $result;
    }
    private function addOrderErrorLog ($ERROR=null,$POINT=null,$arOrderID=null) {

        if($ERROR === null) return false;
        $sql  = " INSERT INTO `ord_log` ";
        $sql .= " (`ORDERID`, `POINT`, `ERROR`) ";
        $sql .= " VALUES ";
        $sql .= " ('$arOrderID','$POINT','$ERROR') ";
        $result = $this->DB->freeQuery($sql);
        return $result;
    }
    private function insInTblObmen ($arOrderID=null,$type=null,$final=null) {
        if($type===null){ $type = 0;}
        if($final===null){ $final = 0;}
        $arOrderID = intval($arOrderID);
        if(null === $arOrderID || true === !is_int($arOrderID)) return false;
        $sql  = " INSERT INTO `ord_obmen` ";
        $sql .= " (`ID`,`TYPE`, `ORDERID`, `FINAL`) ";
        $sql .= " VALUES ";
        $sql .= " ('','$type','$arOrderID','$final') ";
        $result = $this->DB->freeQuery($sql);
        $this->addOrderErrorLog(
            "Заказ $arOrderID записан в таблицу обмена",
            'Заказ выгружен',
            $arOrderID
        );
        return $result;
    }
    public function getLocationTextValue ($loc1cCode=null) {
        //pre($loc1cCode);
        if($loc1cCode===null){ return false;}
        $CIBlockElement = new CIBlockElement;
        $arFilter = array(
            'IBLOCK_ID' => intval(12),
            'ACTIVE' => 'Y',
            '=EXTERNAL_ID'=> $loc1cCode
        );
        $arSelect = array('ID','IBLOCK_ID', 'NAME', 'CODE', 'EXTERNAL_ID', 'PROPERTY_ADRES', 'PROPERTY_CITY',);
        $rsElement = $CIBlockElement->GetList(array('NAME' => 'ASC'), $arFilter,false,false,$arSelect);
        while ($arElement = $rsElement->Fetch()){
            $text = $arElement['PROPERTY_CITY_VALUE'].', '.$arElement['PROPERTY_ADRES_VALUE'];
            //pre($text);
        }
        return $text;
    }
    private function sendMail(array $arOrder, $type='Z') {
//        require($_SERVER["DOCUMENT_ROOT"] . "/local/phpmailer/src/phpmailer.php");
//        require($_SERVER["DOCUMENT_ROOT"] . "/local/phpmailer/src/smtp.php");

        //pre($arOrder);
        if(empty($arOrder)) { return false;}
        if($type==='Z'){
            addOrderErrorLog ('No SEND MAIL','SEND MAIL',$arOrder['BITRIXID']);
            return false;
        }
        $order_props = CSaleOrderPropsValue::GetOrderProps($arOrder['BITRIXID']);
        while ($arProps = $order_props->Fetch()){
            if ($arProps["CODE"] == "LOCATION"){
                $arLocs = CSaleLocation::GetByID($arProps["VALUE"]);
                $adress =  'Украина, '.$arLocs["REGION_NAME_ORIG"].', ';
                $adress .= $arLocs["CITY_NAME_ORIG"].';';
            }
            if ($arProps["CODE"] == "DKCARD"){
                $DKCARD = $arProps['VALUE'];
            }
            if ($arProps["CODE"] == "APT_LOCATION"){
                $APT_LOCATION = $arProps['VALUE'];
                $adr_text = $this->getLocationTextValue($arProps['VALUE']);
                $APT_LOCATION_text = 'Украина, '.$adr_text;
            }
        }
        $res = CSaleBasket::GetList(array("ID" => "ASC"), array("ORDER_ID" => $arOrder['BITRIXID'])); // ID заказа
        $orderList = '';
        while ($staf = $res->Fetch()) {
            $price = $staf['PRICE']*$staf['QUANTITY'];
            $orderList .= $staf['NAME'].' - '.$staf['QUANTITY'].'шт. - '.round($price,2).' грн.;<br/>';
        }
        //pre($type);
        if($type==='C') {
$user_info = CUser::GetByID($arOrder["USERID"])->Fetch();
            $mess  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
            $mess .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">';
            $mess .= '<head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>';
            $mess .= '<style>body{font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;';
            $mess .= 'font-size: 14px;color: #000;}</style>';
            $mess .= '</head><body><table cellpadding="0" cellspacing="0" width="850" ';
            $mess .= 'style="background-color: #d1d1d1; border-radius: 2px; border:1px solid #d1d1d1; margin: 0 auto;"';
            $mess .= ' border="1" bordercolor="#d1d1d1">';
            $mess .= '<tr><td height="83" width="850" bgcolor="#eaf3f5" ';
            $mess .= 'style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">';
            $mess .= '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
            $mess .= '<td bgcolor="#ffffff" height="75" style="font-weight: bold; ';
            $mess .= 'text-align: center; font-size: 26px; color: #0b3961;">';
            // ФИО юзверя.
            $mess .= $user_info["NAME"].', Ваш заказ ожидает оплаты.';

            $mess .= '<br/></td>';
            $mess .= '</tr><tr><td bgcolor="#bad3df" height="11"></td></tr></table></td></tr><tr>';
            $mess .= '<td width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0;';
            $mess .= ' padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">';
            $mess .= '<p style="margin-top:30px; margin-bottom: 28px; font-weight: bold; font-size: 19px;">';
            //
            $mess .= 'Заказ №'.$arOrder['BITRIXID'].' от '.$this->DB->convertSQLtoTime($arOrder['DATEINSERT']).'</p>';

            $mess .= '<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">';
            $mess .= 'СТАТУС ЗАКАЗА: ЗАКАЗ ГОТОВ К ОПЛАТЕ!<br />';
            //$adress
            $mess .= 'Адрес доставки: '.$APT_LOCATION_text/*$adress*/.' <br />';
            //$DKCARD
            $mess .= 'Дисконтная карта: '.$DKCARD.' <br/>';

            $mess .= 'Стоимость заказа: '.$arOrder['PRICE'].' грн.<br />';

            $mess .= '<br />';
            $mess .= 'Состав заказа:<br />';

            //$orderList
            $mess .= $orderList.'<br /></p><br /><br />';
            $mess .= '<p>Оплатить заказ можно, перейдя по ссылке: ';
//            $order_replace = preg_replace('/[ \n\r\t]/', '', 'ORDER_ID');
            $mess .= '<a href="https://online-apteka.com.ua/personal/order/payment/?ORDER_ID='.$arOrder['BITRIXID'].'">Оплата заказа №' . $arOrder['BITRIXID'] . '</a>';
            //
            $mess .= '</p>';
            $mess .= '<br />';
            $mess .= '<br />';
            $mess .= '<p>Вы можете следить за выполнением своего заказа (на какой стадии';
            $mess .= ' выполнения он находится), войдя в <a href="http://online-apteka.com.ua/personal/order/">';
            $mess .= ' Ваш персональный раздел сайта</a> Интернет Аптека Мед-Сервис.<br />';
            $mess .= '<br />Обратите внимание, что для входа в этот раздел Вам ';
            $mess .= 'необходимо будет ввести логин и пароль пользователя сайта ';
            $mess .= 'Интернет Аптека Мед-Сервис.<br /><br />Для того, чтобы аннулировать заказ, ';
            $mess .= 'воспользуйтесь функцией отмены заказа, которая доступна в Вашем ';
            $mess .= 'персональном разделе сайта Интернет Аптека Мед-Сервис.<br /><br />';
            $mess .= 'Пожалуйста, при обращении к администрации сайта Интернет Аптека Мед-Сервис ОБЯЗАТЕЛЬНО';
            //
            $mess .= ' указывайте номер Вашего заказа - '.$arOrder['BITRIXID'].'.<br /><br />';
            $mess .= 'Спасибо за покупку!<br /></p></td>';
            $mess .= '</tr><tr>';
            $mess .= '<td height="50px" width="850" bgcolor="#f7f7f7" valign="top" ';
            $mess .= 'style="border: none; padding-top: 0; padding-right: 44px; ';
            $mess .= 'padding-bottom: 30px; padding-left: 44px;">';
            $mess .= '<p style="border-top: 1px solid #d1d1d1; margin-bottom: 5px; ';
            $mess .= 'margin-top: 0; padding-top: 20px; line-height:21px;">С уважением,';
            $mess .= '<br />администрация <a href="http://online-apteka.com.ua/" style="color:#2e6eb6;">';
            $mess .= 'Интернет-магазина</a><br />E-mail: <a href="mailto:online-apteka@med-service.dp.ua" ';
            $mess .= 'style="color:#2e6eb6;">online-apteka@med-service.dp.ua</a></p>';
            $mess .= '</td></tr></table></body></html>';

            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=UTF-8";
            $headers[] = "From: online-apteka <online-apteka@med-service.dp.ua>";
            $subject = 'Заказ №'.$arOrder['BITRIXID'].' готов к ОПЛАТЕ';
//            $result = mail($arOrder['USEREMAIL'], $subject, $mess, implode("\r\n", $headers));

//            $arSend = array("TO" => $arOrder['USEREMAIL'], "THEME" => $subject, "BODY" => $mess);
$arSend = array("TO" => $user_info["EMAIL"], "THEME" => $subject, "BODY" => $mess);
            CEvent::Send("ORDER_IS_READY_TO_PAY", s1, $arSend, "N", 80);

//            $mail = new PHPMailer(true);
//
//            try {
//                $mail->CharSet = "UTF-8";
//                $mail->setFrom('online-apteka@med-service.dp.ua', 'online-apteka2');
//                $mail->addAddress('gusev.sergey@med-service.com.ua');
//                $mail->isHTML(true);
//                $mail->Subject = $subject;
//                $mail->Body    = $mess;
//
//                $mail->send();
//
//                $fc = fopen(__DIR__ . "/phpmailer_success.txt", "a");
//                fwrite($fc, [$arOrder['USEREMAIL']]);
//            } catch (Exception $e) {
//                $fc = fopen(__DIR__ . "/phpmailer_fail.txt", "a");
//                fwrite($fc, $mail->ErrorInfo);
//            }
        }
        //заказ готов к оплате
        if($type==='D') {
            if($arOrder['PAYSYSTEMID'] >5){
                // оплата безналом
                $mess  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
                $mess .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">';
                $mess .= '<head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>';
                $mess .= '<style>body{font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;';
                $mess .= 'font-size: 14px;color: #000;}</style>';
                $mess .= '</head><body><table cellpadding="0" cellspacing="0" width="850" ';
                $mess .= 'style="background-color: #d1d1d1; border-radius: 2px; border:1px solid #d1d1d1; margin: 0 auto;"';
                $mess .= ' border="1" bordercolor="#d1d1d1">';
                $mess .= '<tr><td height="83" width="850" bgcolor="#eaf3f5" ';
                $mess .= 'style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">';
                $mess .= '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
                $mess .= '<td bgcolor="#ffffff" height="75" style="font-weight: bold; ';
                $mess .= 'text-align: center; font-size: 26px; color: #0b3961;">';
                // ФИО юзверя.
                $mess .= $arOrder['USERFIO'].', Ваш заказ отправлен.';

                $mess .= '<br/></td>';
                $mess .= '</tr><tr><td bgcolor="#bad3df" height="11"></td></tr></table></td></tr><tr>';
                $mess .= '<td width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0;';
                $mess .= ' padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">';
                $mess .= '<p style="margin-top:30px; margin-bottom: 28px; font-weight: bold; font-size: 19px;">';
                //
                $mess .= 'Заказ №'.$arOrder['BITRIXID'].' от '.$this->DB->convertSQLtoTime($arOrder['DATEINSERT']).'</p>';

                $mess .= '<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">';
                $mess .= 'СТАТУС ЗАКАЗА: ТОВАР В ПУТИ<br />';
                //$adress
                $mess .= 'Адрес доставки: '.$APT_LOCATION_text/*$adress*/.' <br />';
                //$DKCARD
                $mess .= 'Дисконтная карта: '.$DKCARD.' <br/>';

                $mess .= 'Стоимость заказа: <b>'.$arOrder['PRICE'].' грн.</b><br />';

                $mess .= '<br />';
                $mess .= 'Ваш заказ:<br />';

                //$orderList
                $mess .= $orderList.'<br /></p><br /><br />';

                $mess .= '<p>Время сборки и доставки товара на указанный Вами адрес составляет от 1 до 7 дней.</p>';
                $mess .= '<br />';

                $mess .= '<p>Доставка заказанного товара в указанную Вами аптеку «Мед-сервис» осуществляется бесплатно.</p>';
                $mess .= '<br />';

                $mess .= '<p>Получение товара производится путем самовывоза. Забрать собранный заказ';
                $mess .= ' можно по режиму работы выбранной Вами аптеки.</p>';
                $mess .= '<br />';

                $mess .= '<p>После прибытия товара на указанный Вами адрес аптеки «Мед-сервис» на Ваш контактный';
                $mess .= ' номер телефона будет выслано СМС-оповещение о том, что Ваш заказ готов к получению.</p>';
                $mess .= '<br />';

                $mess .= '<p style="margin-top:30px; margin-bottom: 28px; font-weight: bold; font-size: 19px;">Благодарим Вас за покупку!</p>';
                $mess .= '<br />';

                $mess .= '</td>';
                $mess .= '</tr><tr>';
                $mess .= '<td height="50px" width="850" bgcolor="#f7f7f7" valign="top" ';
                $mess .= 'style="border: none; padding-top: 0; padding-right: 44px; ';
                $mess .= 'padding-bottom: 30px; padding-left: 44px;">';
                $mess .= '<p style="border-top: 1px solid #d1d1d1; margin-bottom: 5px; ';
                $mess .= 'margin-top: 0; padding-top: 20px; line-height:21px;">С уважением,';
                $mess .= '<br />администрация <a href="https://online-apteka.com.ua/" style="color:#2e6eb6;">';
                $mess .= 'Интернет-магазина</a><br /> ';
                $mess .= '<a>online-apteka@med-service.dp.ua</a></p>';
                $mess .= '</td></tr></table></body></html>';
            } else {
                // оплата налом
                $mess  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
                $mess .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">';
                $mess .= '<head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>';
                $mess .= '<style>body{font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;';
                $mess .= 'font-size: 14px;color: #000;}</style>';
                $mess .= '</head><body><table cellpadding="0" cellspacing="0" width="850" ';
                $mess .= 'style="background-color: #d1d1d1; border-radius: 2px; border:1px solid #d1d1d1; margin: 0 auto;"';
                $mess .= ' border="1" bordercolor="#d1d1d1">';
                $mess .= '<tr><td height="83" width="850" bgcolor="#eaf3f5" ';
                $mess .= 'style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">';
                $mess .= '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
                $mess .= '<td bgcolor="#ffffff" height="75" style="font-weight: bold; ';
                $mess .= 'text-align: center; font-size: 26px; color: #0b3961;">';
                // ФИО юзверя.
                $mess .= $arOrder['USERFIO'].', Ваш заказ отправлен.';

                $mess .= '<br/></td>';
                $mess .= '</tr><tr><td bgcolor="#bad3df" height="11"></td></tr></table></td></tr><tr>';
                $mess .= '<td width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0;';
                $mess .= ' padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">';
                $mess .= '<p style="margin-top:30px; margin-bottom: 28px; font-weight: bold; font-size: 19px;">';
                //
                $mess .= 'Заказ №'.$arOrder['BITRIXID'].' от '.$this->DB->convertSQLtoTime($arOrder['DATEINSERT']).'</p>';

                $mess .= '<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">';
                $mess .= 'СТАТУС ЗАКАЗА: ТОВАР В ПУТИ<br />';
                //$adress
                $mess .= 'Адрес доставки: '.$APT_LOCATION_text/*$adress*/.' <br />';
                //$DKCARD
                $mess .= 'Дисконтная карта: '.$DKCARD.' <br/>';

                $mess .= 'Стоимость заказа: <b>'.$arOrder['PRICE'].' грн.</b><br />';

                $mess .= '<br />';
                $mess .= 'Ваш заказ:<br />';

                //$orderList
                $mess .= $orderList.'<br /></p><br /><br />';

                $mess .= '<p>Время сборки и доставки товара на указанный Вами адрес составляет от 1';
                $mess .= ' до 7 дней. Доставка заказанного товара в указанную Вами аптеку «Мед-сервис»';
                $mess .= ' осуществляется бесплатно. Получение товара производится путем самовывоза. ';
                $mess .= 'Забрать собранный заказ можно по режиму работы выбранной Вами аптеки.</p>';
                $mess .= '<br />';

                $mess .= '<p>После прибытия товара на указанный Вами адрес аптеки «Мед-сервис» на Ваш';
                $mess .= ' контактный номер телефона будет выслано СМС-оповещение о том, что Ваш заказ';
                $mess .= ' готов к получению и оплате.</p>';
                $mess .= '<br />';

                $mess .= '<p>Получение товара производится путем самовывоза. Забрать собранный заказ';
                $mess .= ' можно по режиму работы выбранной Вами аптеки.</p>';
                $mess .= '<br />';

                $mess .= '<p>Оплата заказа осуществляется в кассе аптеки «Мед-сервис» по наличному и';
                $mess .= ' безналичному расчету пластиковыми картами Visa, MasterCard, Maestro.</p>';
                $mess .= '<br />';

                $mess .= '<p>Для оплаты товара сообщите, пожалуйста, фармацевту аптеки «Мед-сервис» ';
                $mess .= 'номер заказа, который будет указан в СМС-оповещении.</p>';
                $mess .= '<br />';

                $mess .= '<p style="margin-top:30px; margin-bottom: 28px; font-weight: bold; font-size: 19px;">Благодарим Вас за покупку!</p>';
                $mess .= '<br />';

                $mess .= '</td>';
                $mess .= '</tr><tr>';
                $mess .= '<td height="50px" width="850" bgcolor="#f7f7f7" valign="top" ';
                $mess .= 'style="border: none; padding-top: 0; padding-right: 44px; ';
                $mess .= 'padding-bottom: 30px; padding-left: 44px;">';
                $mess .= '<p style="border-top: 1px solid #d1d1d1; margin-bottom: 5px; ';
                $mess .= 'margin-top: 0; padding-top: 20px; line-height:21px;">С уважением,';
                $mess .= '<br />администрация <a href="https://online-apteka.com.ua/" style="color:#2e6eb6;">';
                $mess .= 'Интернет-магазина</a><br /> ';
                $mess .= '<a>online-apteka@med-service.dp.ua</a></p>';
                $mess .= '</td></tr></table></body></html>';
            }
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=UTF-8";
            $headers[] = "From: online-apteka <online-apteka@med-service.dp.ua>";
            $subject = 'Заказ №'.$arOrder['BITRIXID'].' готов к ПОЛУЧЕНИЮ и ОПЛАТЕ!';
            $fc = fopen(__DIR__ . "/status_d.txt", "a");
            $array = file(__DIR__ . "/status_d.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (!in_array($arOrder['BITRIXID'], $array)) {
                $result = mail($arOrder['USEREMAIL'], $subject, $mess, implode("\r\n", $headers));
                fwrite($fc, $arOrder['BITRIXID'] . "\r\n");
            }
            fclose(__DIR__ . "/status_d.txt");
        }
        //статус оплачен переделав ТОВАР В ПУТИ согласно 3 документу задачи № 68817
        if($type==='P') {
            $mess  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
            $mess .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">';
            $mess .= '<head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>';
            $mess .= '<style>body{font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;';
            $mess .= 'font-size: 14px;color: #000;}</style>';
            $mess .= '</head><body><table cellpadding="0" cellspacing="0" width="850" ';
            $mess .= 'style="background-color: #d1d1d1; border-radius: 2px; border:1px solid #d1d1d1; margin: 0 auto;"';
            $mess .= ' border="1" bordercolor="#d1d1d1">';
            $mess .= '<tr><td height="83" width="850" bgcolor="#eaf3f5" ';
            $mess .= 'style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">';
            $mess .= '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
            $mess .= '<td bgcolor="#ffffff" height="75" style="font-weight: bold; ';
            $mess .= 'text-align: center; font-size: 26px; color: #0b3961;">';
            // ФИО юзверя.
            $mess .= 'Уважаемый '.$arOrder['USERFIO'].', Ваш заказ отправлен.';
            $mess .= '<br/></td>';

            $mess .= '</tr><tr><td bgcolor="#bad3df" height="11"></td></tr></table></td></tr><tr>';
            $mess .= '<td width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0;';
            $mess .= ' padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">';
            $mess .= '<p style="margin-top:30px; margin-bottom: 28px; font-weight: bold; font-size: 19px;">';

            //$mess .= 'Заказ №'.$arOrder['BITRIXID'].' от '.$this->DB->convertSQLtoTime($arOrder['DATEINSERT']).'</p>';

            $mess .= '<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">';
            $mess .= 'СТАТУС ЗАКАЗА: ТОВАР В ПУТИ<br />';
            //$adress
            $mess .= 'Адрес доставки: '.$APT_LOCATION_text/*$adress*/.' <br />';
            //$DKCARD
            $mess .= 'Дисконтная карта: '.$DKCARD.' <br/>';

            $mess .= 'Стоимость заказа: <b>'.$arOrder['PRICE'].' грн.</b><br />';

            $mess .= '<br />';
            $mess .= 'Ваш заказ:<br />';

            //$orderList
            $mess .= $orderList.'<br /></p><br /><br />';



            $mess .= '<p>Время сборки и доставки товара на указанный Вами адрес составляет от 1 до 7 дней.';
            $mess .= ' <br>Доставка заказанного товара в указанную Вами аптеку «Мед-сервис» осуществляется';
            $mess .= ' бесплатно. Получение товара производится путем самовывоза. Забрать собранный заказ';
            $mess .= ' можно по режиму работы выбранной Вами аптеки. <br /><br /></p>';
            //$mess .= '<br />';
            //$mess .= '<br />';
            $mess .= '<p>После прибытия товара на указанный Вами адрес аптеки «Мед-сервис» на Ваш ';
            $mess .= 'контактный номер телефона будет выслано СМС-оповещение о том, что Ваш заказ готов';
            $mess .= ' к получению и оплате. <br /><br /></p>';

            $mess .= '<p>Оплата заказа осуществляется в кассе аптеки «Мед-сервис» по наличному и безналичному расчету пластиковыми картами Visa, MasterCard, Maestro. <br><br></p>';
            $mess .= '<p>Для оплаты товара сообщите, пожалуйста, фармацевту аптеки «Мед-сервис» номер заказа, который будет указан в СМС-оповещении.<br><br></p>';


            $mess .= '<b>Благодарим Вас за покупку!</b><br /></p></td>';
            $mess .= '</tr><tr>';
            $mess .= '<td height="50px" width="850" bgcolor="#f7f7f7" valign="top" ';
            $mess .= 'style="border: none; padding-top: 0; padding-right: 44px; ';
            $mess .= 'padding-bottom: 30px; padding-left: 44px;">';
            $mess .= '<p style="border-top: 1px solid #d1d1d1; margin-bottom: 5px; ';
            $mess .= 'margin-top: 0; padding-top: 20px; line-height:21px;">С уважением,';
            $mess .= '<br />администрация <a href="https://online-apteka.com.ua/" style="color:#2e6eb6;">';
            $mess .= 'Интернет-магазина</a><br /> ';
            $mess .= '<a>online-apteka@med-service.dp.ua</a></p>';
            $mess .= '</td></tr></table></body></html>';

            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=UTF-8";
            $headers[] = "From: online-apteka <online-apteka@med-service.dp.ua>";
            $subject = 'Заказ №'.$arOrder['BITRIXID'].' готов к ПОЛУЧЕНИЮ и ОПЛАТЕ!';
            //$result = mail($arOrder['USEREMAIL'], $subject, $mess, implode("\r\n", $headers));

        }
        if($type==='F') {
            /*
            $mess  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
            $mess .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">';
            $mess .= '<head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>';
            $mess .= '<style>body{font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;';
            $mess .= 'font-size: 14px;color: #000;}</style>';
            $mess .= '</head><body><table cellpadding="0" cellspacing="0" width="850" ';
            $mess .= 'style="background-color: #d1d1d1; border-radius: 2px; border:1px solid #d1d1d1; margin: 0 auto;"';
            $mess .= ' border="1" bordercolor="#d1d1d1">';
            $mess .= '<tr><td height="83" width="850" bgcolor="#eaf3f5" ';
            $mess .= 'style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">';
            $mess .= '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
            $mess .= '<td bgcolor="#ffffff" height="75" style="font-weight: bold; ';
            $mess .= 'text-align: center; font-size: 26px; color: #0b3961;">';
            // ФИО юзверя.
            $mess .= 'Уважаемый '.$arOrder['USERFIO'].' благодарим Вас ';

            $mess .= 'за заказ на сайте Интернет Аптека Мед-Сервис!<br/></td>';
            $mess .= '</tr><tr><td bgcolor="#bad3df" height="11"></td></tr></table></td></tr><tr>';
            $mess .= '<td width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0;';
            $mess .= ' padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">';
            $mess .= '<p style="margin-top:30px; margin-bottom: 28px; font-weight: bold; font-size: 19px;">';
            //
            $mess .= 'Заказ №'.$arOrder['BITRIXID'].' от '.$this->DB->convertSQLtoTime($arOrder['DATEINSERT']).'</p>';

            $mess .= '<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">';
            $mess .= 'СТАТУС ЗАКАЗА: ЗАКАЗ ВЫПОЛНЕН ПОЛНОСТЬЮ!<br />';
            //$adress
            $mess .= 'Адрес доставки: '.$adress.' <br />';
            //$DKCARD
            $mess .= 'Дисконтная карта: '.$DKCARD.' <br/>';

            $mess .= 'Стоимость заказа: '.$arOrder['PRICE'].' грн.<br />';

            $mess .= '<br />';
            $mess .= 'Состав заказа:<br />';

            //$orderList
            $mess .= $orderList.'<br /></p><br /><br />';
            $mess .= '<p><b> ЗАКАЗ ВЫПОЛНЕН ПОЛНОСТЬЮ! </b></p>';
            $mess .= '<br />';
            $mess .= '<br />';
            $mess .= '<p>Вы можете следить за выполнением своего заказа (на какой стадии';
            $mess .= ' выполнения он находится), войдя в <a href="http://online-apteka.com.ua/personal/order/">';
            $mess .= ' Ваш персональный раздел сайта</a> Интернет Аптека Мед-Сервис.<br />';
            $mess .= '<br />Обратите внимание, что для входа в этот раздел Вам ';
            $mess .= 'необходимо будет ввести логин и пароль пользователя сайта ';
            $mess .= 'Интернет Аптека Мед-Сервис.<br /><br />Для того, чтобы аннулировать заказ, ';
            $mess .= 'воспользуйтесь функцией отмены заказа, которая доступна в Вашем ';
            $mess .= 'персональном разделе сайта Интернет Аптека Мед-Сервис.<br /><br />';
            $mess .= 'Пожалуйста, при обращении к администрации сайта Интернет Аптека Мед-Сервис ОБЯЗАТЕЛЬНО';
            //
            $mess .= ' указывайте номер Вашего заказа - '.$arOrder['BITRIXID'].'.<br /><br />';
            $mess .= 'Спасибо за покупку!<br /></p></td>';
            $mess .= '</tr><tr>';
            $mess .= '<td height="50px" width="850" bgcolor="#f7f7f7" valign="top" ';
            $mess .= 'style="border: none; padding-top: 0; padding-right: 44px; ';
            $mess .= 'padding-bottom: 30px; padding-left: 44px;">';
            $mess .= '<p style="border-top: 1px solid #d1d1d1; margin-bottom: 5px; ';
            $mess .= 'margin-top: 0; padding-top: 20px; line-height:21px;">С уважением,';
            $mess .= '<br />администрация <a href="http://online-apteka.com.ua/" style="color:#2e6eb6;">';
            $mess .= 'Интернет-магазина</a><br />E-mail: <a href="mailto:online-apteka@med-service.dp.ua" ';
            $mess .= 'style="color:#2e6eb6;">online-apteka@med-service.dp.ua</a></p>';
            $mess .= '</td></tr></table></body></html>';

            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=UTF-8";
            $headers[] = "From: online-apteka <online-apteka@med-service.dp.ua>";
            $subject = 'Заказ №'.$arOrder['BITRIXID'].' ВЫПОЛНЕН!';
            $result = mail($arOrder['USEREMAIL'], $subject, $mess, implode("\r\n", $headers));
            * */
        }
        if($type==='A') {
            $mess  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
            $mess .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">';
            $mess .= '<head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>';
            $mess .= '<style>body{font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;';
            $mess .= 'font-size: 14px;color: #000;}</style>';
            $mess .= '</head><body><table cellpadding="0" cellspacing="0" width="850" ';
            $mess .= 'style="background-color: #d1d1d1; border-radius: 2px; border:1px solid #d1d1d1; margin: 0 auto;"';
            $mess .= ' border="1" bordercolor="#d1d1d1">';
            $mess .= '<tr><td height="83" width="850" bgcolor="#eaf3f5" ';
            $mess .= 'style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">';
            $mess .= '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
            $mess .= '<td bgcolor="#ffffff" height="75" style="font-weight: bold; ';
            $mess .= 'text-align: center; font-size: 26px; color: #0b3961;">';
            // ФИО юзверя.
            $mess .= $arOrder['USERFIO'] . ", Ваш заказ (" . $arOrder['BITRIXID'] . ") отменен.";

            $mess .= '<br/>';
            $mess .= '<tr><td bgcolor="#bad3df" height="11"></td></tr><tr>';
            $mess .= '<td width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">';
            $mess .= '<p style="margin-top:30px; margin-bottom: 28px; font-weight: bold; font-size: 19px;">';
            //
            $mess .= 'Заказ №'.$arOrder['BITRIXID'].' от '.$this->DB->convertSQLtoTime($arOrder['DATEINSERT']).'</p>';

            $mess .= '<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">';
            $mess .= 'СТАТУС ЗАКАЗА: <b>ЗАКАЗ ОТМЕНЕН!</b><br />';
            //$adress
            $mess .= 'Адрес доставки: '.$APT_LOCATION_text/*$adress*/.' <br />';
            //$DKCARD
            $mess .= 'Дисконтная карта: '.$DKCARD.' <br/>';

            $mess .= 'Стоимость заказа: '.$arOrder['PRICE'].' грн.<br />';

            $mess .= '<br />';
            $mess .= 'Состав заказа:<br />';

            //$orderList
            $mess .= $orderList.'<br /></p><br /><br />';

            $mess .= '<p>Если Вы захотите повторно воспользоваться услугами нашего интернет-магазина,';
            $mess .= ' пожалуйста, перейдите на <a href="http://online-apteka.com.ua/" style="color:#2e6eb6;">';
            $mess .= 'online-apteka.com.ua</a></p></td>';
            $mess .= '</tr><tr>';
            $mess .= '<td height="50px" width="850" bgcolor="#f7f7f7" valign="top" ';
            $mess .= 'style="border: none; padding-top: 0; padding-right: 44px; ';
            $mess .= 'padding-bottom: 30px; padding-left: 44px;">';
            $mess .= '<p style="border-top: 1px solid #d1d1d1; margin-bottom: 5px; ';
            $mess .= 'margin-top: 0; padding-top: 20px; line-height:21px;">С уважением,';
            $mess .= '<br />администрация <a href="https://online-apteka.com.ua/" style="color:#2e6eb6;">';
            $mess .= 'Интернет-магазина</a><br />E-mail: <a href="mailto:online-apteka@med-service.dp.ua" ';
            $mess .= 'style="color:#2e6eb6;">online-apteka@med-service.dp.ua</a></p>';
            $mess .= '</td></tr></td></tr></table></body></html>';

            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=UTF-8";
            $headers[] = "From: online-apteka <online-apteka@med-service.dp.ua>";
            $subject = 'Заказ №'.$arOrder['BITRIXID'].' ОТМЕНЕН!';
            $result = mail($arOrder['USEREMAIL'], $subject, $mess, implode("\r\n", $headers));

            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=UTF-8";
            $headers[] = "From: online-apteka <online-apteka@med-service.dp.ua>";
            $subject = 'Заказ №'.$arOrder['BITRIXID'].' ОТМЕНЕН!';
            $result = mail('onlineapteka@med-service.dp.ua', $subject, $mess, implode("\r\n", $headers));
//            $mail = new PHPMailer(true);
//            try {
//                $mail->setFrom('onlineapteka@med-service.dp.ua', 'online-apteka');
//                $mail->isHTML(true);
//                $mail->Subject = $subject;
//                $mail->Body    = $mess;
//
//                $mail->send();
//                echo 'Message has been sent';
//            } catch (Exception $e) {
//                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//            }
        }
    }
}
