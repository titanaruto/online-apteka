<?
class obmenOneC{
	private $DB;
	private $ftp;
	private $IBLOCK_ID = 2;
	private $file;
	private $ressource;
	private $dirArray = null;
	private $ressourceObmen;
	private $ruleTranslit = array("replace_space"=>"-","replace_other"=>"-");
	private $knownFolder;
	private $folderInBD;
	private $folderChengeInBD;
	private $goodsChengeInBD;


	//**************** <ORDERS> ****************//
	public  function runObmenOrders() {
		$this->runObmenOrdersIn();
		$this->runObmenOrdersOut();
	}
	public  function runObmenOrdersIn() {
		$allOrders['CHANGED'] = $this->getOrdersLast('CHANGED');
		$result['CHANGED'] = $this->insertOrdersInDB($allOrders['CHANGED']);
		unset($allOrders['CHANGED']);
		$allOrders['CANCELED'] = $this->getOrdersLast('CANCELED');
		$result['CANCELED'] = $this->updateCanseledOrdersInDB($allOrders['CANCELED']);
		unset($allOrders['CANCELED']);
		return $result;
	}
	public  function runObmenOrdersOut() {
		//получаем список возвращенных заказов
		$outOrders = $this->getOrdersFromDB();
		//проверяем нужно ли что-то делать?
		$countAr = count($outOrders);
		if($countAr < 1) return false;
		//получаем наполнение заказов товарами
		$arOrders = $this->getOrdersFillingFromDB($outOrders);
		unset($outOrders);
		//разносим данные по товарам и заказам в битрикс
		$result = $this->spreadOrdersOnSite($arOrders);
		unset($arOrders);
		return $result;
	}
	private function spreadOrdersOnSite($arOrders=null) {
		if($arOrders === null or !is_array($arOrders)===true) return false;
		foreach ($arOrders as $arOrder) {
			pre($arOrder);
			//  не использованные поля:
			/*
			 $arOrder[USERFIO] => Бицоев Тарас
			 $arOrder[USEREMAIL] => t.bitsoev@med-service.dp.ua
			 $arOrder[USERPHONE] => 0987654321
			 $arOrder[USERZIP] => 49000
			 $arOrder[USERDELIVERYCOUNTRY] => Украина
			 $arOrder[USERDELIVERYCOUNTRYID] => 0
			 $arOrder[USERDELIVERYSITY] => Днепропетровск
			 $arOrder[USERDELIVERYSITYID] => 804
			 $arOrder[USERDELIVERYSTREET] => Симофорная, 28
			 $arOrder[USERDELIVERYSTREETID]=>1095,
			 */
			$GoodsOrder = $this->compareGoodsItem($arOrder['GOODS']);
			pre($GoodsOrder);
			if($GoodsOrder === 'Ok') {
				$CSaleOrder = new CSaleOrder;
				$arBOrder = $CSaleOrder->GetByID($arOrder['BITRIXID']);
				if (count($arBOrder) > 0) {
					pre($arOrder['STATUSID']);
					$arFields = array(
							'PAYED' => $this->disConvertYorN($arOrder['PAYED']),
							'DATE_PAYED' => $this->DB->convertSQLtoTime($arOrder['DATEPAYED']),
							'CANCELED' => $this->disConvertYorN($arOrder['CANCELED']),
							'DATE_CANCELED' => $this->DB->convertSQLtoTime($arOrder['DATECANCELED']),
							'REASON_CANCELED' => $arOrder['REASONCANCELED'],
							'PRICE_DELIVERY' => $arOrder['PRICEDELIVERY'],
							'STATUS_ID' => $arOrder['STATUSID'],
							//'ALLOW_DELIVERY' => $this->disConvertYorN($arOrder['ALLOWDELIVERY']),
							//'DATE_ALLOW_DELIVERY' => $this->DB->convertSQLtoTime($arOrder['DATEALLOWDELIVERY']),
							'PRICE' => $arOrder['PRICE'],
							'CURRENCY' => $arOrder['CURRENCY'],
							'DISCOUNT_VALUE' => $arOrder['DISCOUNTVALUE'],
							'USER_ID' => $arOrder['USERID'],
							'PAY_SYSTEM_ID' => $arOrder['PAYSYSTEMID'],
							//'DELIVERY_ID' => $arOrder['DELIVERYID'],
							'USER_DESCRIPTION' => $arOrder['USERDESCRIPTION'],
							'ADDITIONAL_INFO' => $arOrder['ADDITIONALINFO'],
							'PS_STATUS' => $arOrder['PSSTATUS'],
							'PS_STATUS_CODE' => $arOrder['PSSTATUSCODE'],
							'PS_STATUS_DESCRIPTION' => $arOrder['PSSTATUSDESCRIPTION'],
							'PS_STATUS_MESSAGE' => $arOrder['PSSTATUSMESSAGE'],
							'PS_SUM' => $arOrder['PSSUM'],
							'PS_CURRENCY' => $arOrder['PSCURRENCY'],
							'PS_RESPONSE_DATE' => $this->DB->convertSQLtoTime($arOrder['PSRESPONSEDATE']),
					);
					//pre($arFields);
					$CSaleOrder = new CSaleOrder;
					$CSaleOrder->Update($arOrder['BITRIXID'], $arFields);
					//pre($ord);
					//$this->DB->convertSQLtoTime($arOrder['DATEUPDATE']);
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
		}
		return true;		
	}
	private function delCangeFromObmen($orderID=null) {
		$orderID = intval($orderID);
		if(null === $orderID || true === !is_int($orderID)) return false;
		$sql ="DELETE FROM `ord_obmen` WHERE `ORDERID`='$orderID' AND `TYPE`>'0'";
		$result = $this->DB->freeQuery($sql);
		if($result !== 'Ok') {
			$this->addOrderErrorLog(
				"Не получилось удалить запись заказа OrderID=$orderID из таблицы ord_obmen",
				'Удаление записи из таблицы БД',
				$orderID
			);
			echo "Не получилось удалить запись заказа OrderID=$orderID из таблицы ord_obmen <br/>";
		}
	}
	private function compareGoodsItem($DBGoods=null) {
		if($DBGoods === null or !is_array($DBGoods)===true) return false;
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
			$delete = true;
			foreach ($DBGoods as $key => $staff) {
				// если товар уже есть в корзине:
				if($staff['EXTKEY'] == $arItem['PRODUCT_XML_ID']){
					$delete = false;
					if($staff['PRICE'] <> $arItem['PRICE']) {
							$arFields = array(
								'PRICE' => $staff['PRICE'],
							);
					}
					if($staff['QUANTITY'] <> $arItem['QUANTITY']) {
							$arFields = array(
									'QUANTITY' => $staff['QUANTITY'],
							);
					}
					$res = $CSaleBasket->Update($arItem['ID'],$arFields);
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
					unset($arAddGoods[$key]);
				}
			}
			// если товар удалили в колл-центре:
			if(true === (true===$delete)) {
				$del = $CSaleBasket->Delete($arItem['ID']);
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
		$addGoods = $this->addNewGoodsToOrder($arAddGoods);
		if(true === (false===$addGoods)) {
			//Error!
			$this->addOrderErrorLog(
					"Товарные позиции заказа OrderID=$orderID не были добавлены",
					'Добавление товаров в заказ',
					$orderID
			);
			echo "Не найдены товары заказа OrderID=$orderID в таблице ord_staff_out";
		}
		return 'Ok';
	}
	private function addNewGoodsToOrder($arAddGoods=null) {
		if($arAddGoods === null or !is_array($arAddGoods)===true) return false;
		$CSaleBasket = new CSaleBasket;
		foreach ($arAddGoods as $key => $staff) {
			$elm = array();
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
						"NAME" => $staff['NAME'],
						"DETAIL_PAGE_URL" => $elm['DETAIL_PAGE_URL'],
						"PRODUCT_XML_ID" => $staff['EXTKEY'],
						"ORDER_ID" => $staff['BITRIXORDERID'],
						
				);
				$CSaleBasket->Add($filds);
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
		}
		return $arOrders;
	}
	private function getStaffFromDB($orderID=null) {
		$orderID = intval($orderID);
		if(null === $orderID || true === !is_int($orderID)) return false;
		//!!!!! ord_staff_out !!!!
		$sql = "SELECT * FROM `ord_staff_out` WHERE `BITRIXORDERID`='$orderID'";
		$result = $this->DB->freeQuery($sql);
		if(empty($result['0'])) {
			$this->addOrderErrorLog(
				"Не найдены товары заказа OrderID=$orderID в таблице ord_staff_out",
				'Получение товарного наполнения заказа',
				$orderID
			);
			echo "Не найдены товары заказа OrderID=$orderID в таблице ord_staff_out";
			return array();
		}
		return $result;
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
		if(empty($result)) return 'Ok';
		return $result;
	}
	private function getOrdersLast($type = null) {
		if($type === null) return false;
		$arOrders = array();
		$CSaleOrder = new CSaleOrder;
		if($type==='CHANGED') {
			$arFilter = array(
					'CANCELED' => 'N',
					'STATUS_ID' => 'N',
			);
		} else if($type==='CANCELED') {
			$arFilter = array(
					'CANCELED' => 'Y',
					'!STATUS_ID' => array('A','F'),
			);
		}
		$dbOrders = $CSaleOrder->GetList(Array("ID"=>"ASC"), $arFilter, false, false, array());
		while ($arOrder = $dbOrders->Fetch()) {
			$arOrders[$arOrder['ID']] = $this->getOrdersPropertyes($arOrder);
		}
		return $arOrders;
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
		if ($props_order = $dbProps_order->Fetch())
		{
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
		if ($props_order = $dbProps_order->Fetch())
		{
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
		if ($props_order = $dbProps_order->Fetch())
		{
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
		if ($props_order = $dbProps_order->Fetch())
		{
			$arOrder['PROPERTIES']['ZIP'] = $props_order;
		}
		
		$arFilter['ORDER_PROPS_ID'] = 6;
		$dbProps_order = $CSaleOrderPropsValue->GetList(
				array('ID'=>'DESC'),
				$arFilter,
				false,
				false,
				$arSelectFields
				);
		if ($props_order = $dbProps_order->Fetch())
		{
			$CSaleLocation = new CSaleLocation;
			$loc = $CSaleLocation->GetByID($props_order['VALUE']);
			$arOrder['PROPERTIES']['LOCATION']=$loc;
		}
		return $arOrder;
	}
	private function insertOrdersInDB($arOrders=null) {
		if($arOrders === null or !is_array($arOrders)===true) return false;
		foreach ($arOrders as $key => $arOrder) {
			pre($arOrder['ID']);
			$isOrderThere = $this->isOrderThere($arOrder);
			if($isOrderThere < 1) {
				$insOrdStafInTableStafIn = $this->insOrdStafInTableStafIn($arOrder['ID']);
				if( $insOrdStafInTableStafIn==='Ok' ) {
					$insOrdInTableOrdIn = $this->insOrdInTableOrdIn($arOrder);
					if( $insOrdInTableOrdIn==='Ok' ) {
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
				} else {
					$this->addOrderErrorLog(
							"Свойства заказа OrderID=$arOrder[ID] не получилось записать в таблицу ord_orders_in", 
							'Запись свойств заказа в БД', 
							$arOrder['ID']
					);
	 				echo "Свойства заказа OrderID=$arOrder[ID] не получилось записать в таблицу ord_orders_in";
				}
			} else {
				// заказ уже есть в таблице заказов
				$this->addOrderErrorLog(
					"Товары из заказа OrderID=$arOrder[ID] уже есть в таблице ord_staff_in",
					'Запись товаров',
					$arOrder['ID']
				);
				echo "Товары из заказа OrderID=$arOrder[ID] уже есть в таблице ord_staff_in";
			}
		}
	}
	private function updateCanseledOrdersInDB($arOrders=null) {
		if($arOrders === null or !is_array($arOrders)===true) return false;
		$result = array();
		foreach ($arOrders as $key => $arOrder) {
			$isOrderThere = $this->isOrderThere($arOrder);
			if($isOrderThere > 0) {
				$updOrdInTableOrdIn = $this->updOrdInTableOrdIn($arOrder);
				//pre($updOrdInTableOrdIn);
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
			} else {
				// заказ нет в таблице заказов
				$this->addOrderErrorLog(
						"Заказа OrderID=$arOrder[ID] нет в таблице ord_staff_in",
						'Проверка заказа перед обновлением',
						$arOrder['ID']
						);
				echo "Заказа OrderID=$arOrder[ID] нет в таблице ord_staff_in<br/>";
				$this->insertOrdersInDB(array($arOrder));
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
		if($arOrder === null or !is_array($arOrder)===true) return false;
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
		$streetID = '';
		if(!empty($arOrder['PROPERTIES']['LOCATION'])) {
			$city = $arOrder['PROPERTIES']['LOCATION']['REGION_NAME'];
			$cityID = $arOrder['PROPERTIES']['LOCATION']['REGION_ID'];
			$street = $arOrder['PROPERTIES']['LOCATION']['CITY_NAME'];
			$streetID = $arOrder['PROPERTIES']['LOCATION']['CITY_ID'];
		} 
		$fio = $arOrder['PROPERTIES']['FIO']['VALUE'];
		$email = $arOrder['PROPERTIES']['EMAIL']['VALUE'];
		$phone = $arOrder['PROPERTIES']['PHONE']['VALUE'];
		$zip = $arOrder['PROPERTIES']['ZIP']['VALUE'];
		
		$sql  = " INSERT INTO `ord_orders_in` ";
		$sql .= " (`ID`, `BITRIXID`, `PAYED`, `DATEPAYED`, `CANCELED`, `DATECANCELED`, ";
		$sql .= " `REASONCANCELED`, `STATUSID`, `DATESTATUS`, `PRICEDELIVERY`, `ALLOWDELIVERY`, `DATEALLOWDELIVERY`, `PRICE`, ";
		$sql .= " `CURRENCY`, `DISCOUNTVALUE`, `USERID`, `PAYSYSTEMID`, `DELIVERYID`, `DATEINSERT`, `DATEUPDATE`, ";
		$sql .= " `USERDESCRIPTION`, `ADDITIONALINFO`, `PSSTATUS`, `PSSTATUSCODE`, `PSSTATUSDESCRIPTION`, `PSSTATUSMESSAGE`, ";
		$sql .= " `PSSUM`, `PSCURRENCY`, `PSRESPONSEDATE`, `USERFIO`, `USEREMAIL`, `USERPHONE`, `USERZIP`, ";
		$sql .= " `USERDELIVERYCOUNTRY`, `USERDELIVERYCOUNTRYID`, `USERDELIVERYSITY`, `USERDELIVERYSITYID`, ";
		$sql .= " `USERDELIVERYSTREET`, `USERDELIVERYSTREETID`) ";
		$sql .= " VALUES ";
		$sql .= " ('', '$arOrder[ID]','$payed','$datepayed','$canseled', '$datecanseled', ";
		$sql .= " '$arOrder[REASON_CANCELED]','$arOrder[STATUS_ID]','$datestatus','$arOrder[PRICE_DELIVERY]','$alloweddelivery','$dateallowdelivety', '$arOrder[PRICE]', ";
		$sql .= " '$arOrder[CURRENCY]','$arOrder[DISCOUNT_VALUE]','$arOrder[USER_ID]','$arOrder[PAY_SYSTEM_ID]','$arOrder[DELIVERY_ID]','$dateinsert','$dateupdate', ";
		$sql .= " '$arOrder[USER_DESCRIPTION]','$arOrder[ADDITIONAL_INFO]','','','','', ";
		$sql .= " '','','','$fio','$email','$phone','$zip','Украина','','$city','$cityID',";
		$sql .= " '$street','$streetID') ";
		$result = $this->DB->freeQuery($sql);
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
			if($stafInDB!== 'Ok' ) {
				$this->addOrderErrorLog(
						"Товар BITRIXID=$staf[ID] уже есть в БД и привязан к заказу OrderID=$arOrderID", 
						'Проверка товаров заказа в БД перед запистью в БД', 
						$arOrderID
				);
				echo 'Error: Order '.$arOrderID.' has been already!';
			} else {
				$this->insertStafinDB($staf);
			}
			$arStaff[]=$staf;
		}
		$request = 'Ok';
		foreach ($arStaff as $staf) {
			$stafInDB = $this->checkStafinDB($arOrderID,$staf['PRODUCT_ID']);
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
		$sql  = " INSERT INTO `ord_staff_in` ";
		$sql .= " (`ID`,`BITRIXORDERID`, `BITRIXID`, `EXTKEY`, `PRICE`, `CURRENCY`, ";
		$sql .= " `QUANTITY`,`NAME`,`SUMM`) ";
		$sql .= " VALUES ";
		$sql .= " ('','$staf[ORDER_ID]','$staf[PRODUCT_ID]','$staf[PRODUCT_XML_ID]', ";
		$sql .= " '$staf[PRICE]', '$staf[CURRENCY]','$staf[QUANTITY]','$staf[NAME]','$summ') ";
		$result = $this->DB->freeQuery($sql);
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
		$sql .= " (`ID`,`ORDERID`, `POINT`, `ERROR`) ";
		$sql .= " VALUES ";
		$sql .= " ('','$arOrderID','$POINT','$ERROR') ";
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
		return $result;
	}
	
	//**************** </ORDERS> ****************//





	//**************** Товары ****************//	
	public function implementationPriceChange() {
		$changeGoodsBitrix = array();
		while (intval($lipton = $this->getCountRowInTablePrice()) > 0 )
		{
			$goodsInBD = $this->getPriceChangeFromBD();
			foreach ($goodsInBD as $goods)
			{
				$element = $this->getRecipientGoods($goods['EXTKEY']);
				if(true!==($element===false))
				{
					$id = $this->updatePrice($element, $goods);
				} else {
					return false;
				}
				if($id > 0)
				{
					$ok = $this->addChangePriceInLOG($goods, $id);
					if($ok == 'Ok')
					{
						$res = $this->delChangePricesInBD($goods);
					}
				} else {
					$changeGoodsBitrix['ERROR'][] = $goods;
					$ok = $this->addChangePriceInLOG($goods, '', 'ERROR');
					if($ok == 'Ok')
					{
						$res = $this->delChangePricesInBD($goods);
					}
				}
				$changeGoodsBitrix['CHANGED'][] = $id;
			}
		}
	}
	public function implementationChangeGoods() {
		$changeGoodsBitrix = array();
		$changeGoodsBitrix['CHANGED']=array();
		$q = intval($lipton = $this->getCountRowInTable());
		//pre($q);
		while (intval($lipton = $this->getCountRowInTable()) > 0 )
		{
			//по умолчанию выбираются первые 50 строк
			$goodsInBD = $this->getGoodsChangeFromBD();
			foreach ($goodsInBD as $goods)
			{
				$element = $this->getRecipientGoods(trim($goods['EXTKEY'],' '));
				if(true===($element===false))
				{
					echo 'Net tovara!!!  ADD!!!  <br/>';
					$id = $this->addGoods($goods);
				} else {
					echo 'Est` tovar!!! UPDATE!!! <br/>';
					$id = $this->updateGoods($element, $goods);
				}
				//pre($id);
				if($id > 0)
				{
					echo $id.'<br/>';
					$ok = $this->addChangeGoodsInLOG($goods, $id);
					if($ok == 'Ok')
					{
						$res = $this->delChangeGoodsInBD($goods);
					}
				} else {
					$changeGoodsBitrix['ERROR'][] = $goods;
					$ok = $this->addChangeGoodsInLOG($goods, '', 'ERROR'.$id);
					if($ok == 'Ok')
					{
						$res = $this->delChangeGoodsInBD($goods);
					}
				}
				$changeGoodsBitrix['CHANGED'][] = $id;
			}
			 
		}
		//echo '111<br/>';
		$this->setProperties();
		//echo '222<br/>';
		return $changeGoodsBitrix;
	}
	public function setProperties($modif = 'LAST') {
		if($modif == 'LAST') {
			$arID = $this->getLASTChangeElements(); // исправить запрос при необходимости
			//pre($arID);
			foreach ($arID as $element) {
				if (!empty($element['RECOMMENDCODE'])) {
					$arID = $this->strToArID($element['RECOMMENDCODE']);
					if(!empty($arID)) {
						$arVal = array();
						foreach ($arID as $id){
							$arVal[]=$id['ID'];
						}
						CIBlockElement::SetPropertyValuesEx(
							$element['BITRIXID'],
							intVal($this->IBLOCK_ID),
							array('RECOMMEND'=>$arVal)
						);
					}
				}
				if (!empty($element['ANALOGCODE'])) {
					$arID = $this->strToArID($element['ANALOGCODE']);
					if(!empty($arID)) {
						$arVal = array();
						foreach ($arID as $id){
							$arVal[]=$id['ID'];
						}
						CIBlockElement::SetPropertyValuesEx(
							$element['BITRIXID'],
							intVal($this->IBLOCK_ID),
							array('ANALOG'=>$arVal)
						);
					}
				}
			}
			
		} else {
			//тут перебираем все товары подряд, без выборки измененных
			//$arID  массив элементов для которых устанавливаем рекомендованные и аналоги
			$arFilter = array(
					'IBLOCK_ID' => intval(2), 
					'ACTIVE' => 'Y', 
					'!PROPERTY_RECOMMEND_CODE'=>false, 
					'!PROPERTY_ANALOG_CODE'=>false
			);
			$arSelect = array(
					'IBLOCK_ID', 'ID','ACTIVE', 
					'PROPERTY_RECOMMEND_CODE', 
					'PROPERTY_ANALOG_CODE'
			);
			$rsElement = CIBlockElement::GetList (
					array('ID' => 'ASC'), 
					$arFilter, 
					false, 
					false, 
					$arSelect
			);
			while ($element = $rsElement->GetNext()) {
				if (!empty($element['~PROPERTY_RECOMMEND_CODE_VALUE'])) {
					$arID = $this->strToArID($element['~PROPERTY_RECOMMEND_CODE_VALUE']);
					if(!empty($arID)) {
						$arVal = array();
						foreach ($arID as $id){
							$arVal[]=$id['ID'];
						}
						CIBlockElement::SetPropertyValuesEx(
								$element['BITRIX_ID'],
								intVal($this->IBLOCK_ID),
								array('RECOMMEND'=>$arVal)
						);
					}
				}
				if (!empty($element['~PROPERTY_ANALOG_CODE_VALUE'])) {
					$arID = $this->strToArID($element['~PROPERTY_ANALOG_CODE_VALUE']);
					if(!empty($arID)) {
						$arVal = array();
						foreach ($arID as $id){
							$arVal[]=$id['ID'];
						}
						CIBlockElement::SetPropertyValuesEx(
								$element['BITRIX_ID'],
								intVal($this->IBLOCK_ID),
								array('ANALOG'=>$arVal)
						);
					}
				}
			}
		}
		return false;
	}
	/*public function openFileObmen()
	{
		$way_str = $_SERVER['DOCUMENT_ROOT'].'/upload/a_obmen/';
		if($this->ressourceObmen = fopen($way_str.'obmen.csv', 'r'))
		{
			echo 'File exists! You can do next action. <br/>';
			return $this->ressourceObmen;
		} else {
			return false;
		}
	}*/
	/*public function closeFileObmen()
	{
		$way_str = $_SERVER['DOCUMENT_ROOT'].'/upload/a_obmen/';
		if($this->ressourceObmen = fclose($this->ressourceObmen))
		{
			echo 'The file was closed correctly! You can do next action. <br/>';
			return true;
		} else {
			return false;
		}
	}*/
	private function strToArID($str=null){
		if($str===null) return false;
		$text = str_replace(" ",',',$str);
		$text = preg_replace('/\s/', ',', $text);
		$str = trim($text,',');
		$res = explode(',', $str);
		$arResipient = array();
		foreach ($res as $code) {
			if(!empty($code)){
				$a = $this->getRecipientGoods($code,'SHORT');
				if(!empty($a)) {
					$arResipient[] = $a;
				}
			}
		}
		return $arResipient;
	}
	private function getLASTChangeElements() {
		$sql  = " SELECT `ID`, `BITRIXID`, `RECOMMENDCODE`, `ANALOGCODE`  FROM `tvr_log` WHERE ";
		$sql .= " `NOTICE`='Ok' and `ANALOGCODE` !='' and `RECOMMENDCODE` != '' ";
		$sql .= "  and `TIMECHANGE` >= date_sub(now(), INTERVAL 300 SECOND) ";//INTERVAL 1 HOUR
		//pre($sql);
		$result = $this->DB->freeQuery($sql);
		return $result;
	}
	private function getParetnID($str) {
		return $str;
	}
	private function addGoods($goods = null)
	{
		if($goods === null) return false;
		$name = trim($this->format_to_save_string($goods['NAME']));
		$arParams = array("replace_space"=>"-","replace_other"=>"-");
		$trans = Cutil::translit($name,"ru",$arParams);
		$CODE = trim($trans, '-');
		$PROP = array(
				'ARTNUMBER' => $goods['ARTNUMBER'],
				'BRAND' => $goods['BRAND'],
				'NEWPRODUCT' => $goods['NEWPRODUCT'],
				'SALELEADER' => $goods['SALELEADER'],
				'SPECIALOFFER' => $goods['SPECIALOFFER'],
				'DELIVERY' => $goods['DELIVERY'],
				'PREPAID' => $goods['PREPAID'],
				'MANUFACTURER' => $goods['MANUFACTURER'],
				'COUNTRY' => $goods['COUNTRY'],
				'MAIN_MEDICINE' => $goods['MAINMEDICINE'],
				'FARM_FORM' => $goods['FARMFORM'],
				'DOSAGE' => $goods['DOSAGE'],
				'QUNTITY' => $goods['QUNTITY'],
				'DESCRIPTION' => $goods['DESCRIPTION'],				
				'QUALIFICATION' =>  $goods['QUALIFICATION'],
				'TYPE' => $goods['TYPE'],
				'RECOMMEND_CODE' => $goods['RECOMMENDCODE'],
				'ANALOG_CODE' => $goods['ANALOGCODE'],
				'MEASURE' => $goods['MEASURE'],
				'RATE' => $goods['RATE'],
				'RECOMMEND_CODE' => $goods['RECOMMENDCODE'],
				'ANALOG_CODE' => $goods['ANALOGCODE'],
				'MORE_PHOTO' => $this->getMorePictureFile($goods['MOREPICTURE']),
		);
		$prImage = $this->getFileJPG($goods['DETAILPICTURE']);
		if(true===(false===$prImage)) {
			$prImage = $this->getFileJPG();
		}
		$paretnKey = $this->getRecipientFolderForGoods($goods['PARENTKEY']);
		
		if(true===(false===$paretnKey)) return -1;
		$arLoadProductArray = Array(
			"MODIFIED_BY"    => intVal(1),
			"IBLOCK_SECTION_ID" => $paretnKey,
			"IBLOCK_ID"      => intVal($this->IBLOCK_ID),
			'EXTERNAL_ID' => $goods['EXTKEY'],
			"CODE" => $CODE,
			"PROPERTY_VALUES"=> $PROP,
			"NAME"           => $goods['NAME'],
			"ACTIVE"         => $goods['ACTIVE'],
			'PREVIEW_TEXT_TYPE' => 'text',
			'PREVIEW_TEXT'   => $goods['PREVIEWTEXT'],
			'DETAIL_TEXT_TYPE'	=> 'text',
			"DETAIL_TEXT"    => $goods['DETAILTEXT'],
			"DETAIL_PICTURE" => $prImage,
		);
		//pre($arLoadProductArray);
		$el = new CIBlockElement;
		if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
				$PRICE_TYPE_ID = 1;
				$arFields = Array(
					"PRODUCT_ID" => $PRODUCT_ID,
					"CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
					"PRICE" => $goods['PRICE'],
					"CURRENCY" => "UAH",
					"QUANTITY_FROM" => false,
					"QUANTITY_TO" => false
				);
				$res = CPrice::GetList(
        			array(),
        			array(
               			 "PRODUCT_ID" => $PRODUCT_ID,
               			 "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
					)
				);
				if ($arr = $res->Fetch()){
				    CPrice::Update($arr["ID"], $arFields);
				} else {
				    CPrice::Add($arFields);
				}
				$arFields = array("ID" => $PRODUCT_ID,'QUANTITY' => '1');
				CCatalogProduct::Add($arFields);
				return $PRODUCT_ID;
		}else{
			return "Error: ".$el->LAST_ERROR;
		}
		return false;
	}
	private function updatePrice($element=null, $goods=null)
	{
		if($goods === null || $element===null) return false;
		$el = new CIBlockElement;
		$PRICE_TYPE_ID = 1;
		$arFields = Array(
			"PRODUCT_ID" => $element[ID],
			"CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
			"PRICE" => $goods['PRICE'],
			"CURRENCY" => "UAH",
			"QUANTITY_FROM" => false,
			"QUANTITY_TO" => false
		);
		$res = CPrice::GetList(
			array(),
		  	array(
		  		"PRODUCT_ID" => $element[ID],
		  		"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
		  	)
		);
		if ($arr = $res->Fetch()) {
			CPrice::Update($arr["ID"], $arFields);
		} else {
			CPrice::Add($arFields);
		}
		return $element[ID];
	}
	private function updateGoods($element=null, $goods=null) {
		if($goods === null || $element===null) return false;
		$name = trim($this->format_to_save_string($goods['NAME']));
		$arParams = array("replace_space"=>"-","replace_other"=>"-");
		$trans = Cutil::translit($name,"ru",$arParams);
		$CODE = trim($trans, '-');
		$prImage = $this->getFileJPG($goods['DETAILPICTURE']);
		if(true===(false===$prImage)) {
			$prImage = $this->getFileJPG();
		}
		$paretnKey = $this->getRecipientFolderForGoods($goods['PARENTKEY']);
		//pre($paretnKey);
		if(true===($paretnKey===false)) return -1;
		$arLoadProductArray = Array (
			'IBLOCK_SECTION_ID'	=> $paretnKey,
			'CODE'				=> $CODE,
			'NAME'           	=> $goods['NAME'],
			'ACTIVE'        	=> $goods['ACTIVE'],
			'PREVIEW_TEXT_TYPE' => 'text',
			'PREVIEW_TEXT'  	=> $goods['PREVIEWTEXT'],//$this->format_to_save_string($goods['PREVIEWTEXT']),
			'DETAIL_TEXT_TYPE'	=> 'text',
			'DETAIL_TEXT'    	=> $goods['DETAILTEXT'],//$this->format_to_save_string($goods['DETAILTEXT']),
			'DETAIL_PICTURE' 	=> $prImage,
		);
		
		if(isset($element['DETAIL_PICTURE'])) {
			$CFile = new CFile;
			$rsFile = $CFile->GetByID($element['DETAIL_PICTURE']);
			$arFile = $rsFile->Fetch();
		}
		if ($arLoadProductArray['NAME'] == $element['NAME']) {
			unset($arLoadProductArray['NAME']);
		}
		if ($arLoadProductArray['CODE'] == $element['CODE'] ) {
			unset($arLoadProductArray['CODE']);
		}
		if ($arLoadProductArray['IBLOCK_SECTION_ID'] == $element['IBLOCK_SECTION_ID']) {
			unset($arLoadProductArray['IBLOCK_SECTION_ID']);
		}
		if ($arLoadProductArray['ACTIVE'] == $element['ACTIVE']) {
			unset($arLoadProductArray['ACTIVE']);
		}
		if ($arLoadProductArray['PREVIEW_TEXT'] == $element['~PREVIEW_TEXT']) {
			unset($arLoadProductArray['PREVIEW_TEXT_TYPE']);
			unset($arLoadProductArray['PREVIEW_TEXT']);
		} 
		if ($arLoadProductArray['DETAIL_TEXT'] == $element['~DETAIL_TEXT']) {
			unset($arLoadProductArray['DETAIL_TEXT_TYPE']);
			unset($arLoadProductArray['DETAIL_TEXT']);
		}
		if(isset($element['DETAIL_PICTURE'])) {
			if ($arLoadProductArray['DETAIL_PICTURE']['name'] == $arFile['ORIGINAL_NAME']
				&& $arLoadProductArray['DETAIL_PICTURE']['type'] == $arFile['CONTENT_TYPE']
				&& $arLoadProductArray['DETAIL_PICTURE']['size'] == $arFile['FILE_SIZE']
			) {
				unset($arLoadProductArray['DETAIL_PICTURE']);
			}
		}
		$arCount = count($arLoadProductArray);
		if($arCount<>0){
			$el = new CIBlockElement;
			$el->Update($element[ID], $arLoadProductArray);
		}
		$PROP = array(
				'ARTNUMBER' => $goods['ARTNUMBER'],
				'BRAND' => $goods['BRAND'],
				'NEWPRODUCT' => $goods['NEWPRODUCT'],
				'SALELEADER' => $goods['SALELEADER'],
				'SPECIALOFFER' => $goods['SPECIALOFFER'],
				'DELIVERY' => $goods['DELIVERY'],
				'PREPAID' => $goods['PREPAID'],
				'MANUFACTURER' => $goods['MANUFACTURER'],
				'COUNTRY' => $goods['COUNTRY'],
				'MAIN_MEDICINE' => $goods['MAINMEDICINE'],
				'FARM_FORM' => $goods['FARMFORM'],
				'DOSAGE' => $goods['DOSAGE'],
				'QUNTITY' => $goods['QUNTITY'],
				'DESCRIPTION' => $goods['DESCRIPTION'],				
				'QUALIFICATION' =>  $goods['QUALIFICATION'],
				'TYPE' => $goods['TYPE'],
				'RECOMMEND_CODE' => $goods['RECOMMENDCODE'],
				'ANALOG_CODE' => $goods['ANALOGCODE'],
				'MEASURE' => $goods['MEASURE'],
				'RATE' => $goods['RATE'],
		);
		if(!empty($goods['MOREPICTURE'])) {
			$PROP['MORE_PHOTO'] = $this->getMorePictureFile($goods['MOREPICTURE']);
		}
		if($element['PROPERTY_NEWPRODUCT_VALUE'] != 'Y') $element['PROPERTY_NEWPRODUCT_VALUE'] = 'N';
		if($element['PROPERTY_SALELEADER_VALUE'] != 'Y') $element['PROPERTY_SALELEADER_VALUE'] = 'N';
		if($element['PROPERTY_SPECIALOFFER_VALUE'] != 'Y') $element['PROPERTY_SPECIALOFFER_VALUE'] = 'N';
		if($element['PROPERTY_DELIVERY_VALUE'] != 'Y') $element['PROPERTY_DELIVERY_VALUE'] = 'N';
		if($element['PROPERTY_PREPAID_VALUE'] != 'Y') $element['PROPERTY_PREPAID_VALUE'] = 'N';

		if ($PROP['NEWPRODUCT'] == $element['PROPERTY_NEWPRODUCT_VALUE']) {
			unset($PROP['NEWPRODUCT']);
		}
		if ($PROP['SALELEADER'] == $element['PROPERTY_SALELEADER_VALUE']) {
			unset($PROP['SALELEADER']);
		}
		if ($PROP['SPECIALOFFER'] == $element['PROPERTY_SPECIALOFFER_VALUE']) {
			unset($PROP['SPECIALOFFER']);
		}
		if ($PROP['DELIVERY'] == $element['PROPERTY_DELIVERY_VALUE']) {
			unset($PROP['DELIVERY']);
		}
		if ($PROP['PREPAID'] == $element['PROPERTY_PREPAID_VALUE']) {
			unset($PROP['PREPAID']);
		}
		if ($PROP['ARTNUMBER'] == $element['PROPERTY_ARTNUMBER_VALUE']) {
			unset($PROP['ARTNUMBER']);
		}
		if ($PROP['BRAND'] == $element['PROPERTY_BRAND_VALUE']) {
			unset($PROP['BRAND']);
		}
		if ($PROP['MANUFACTURER'] == $element['PROPERTY_MANUFACTURER_VALUE']) {
			unset($PROP['MANUFACTURER']);
		}
		if ($PROP['COUNTRY'] == $element['PROPERTY_COUNTRY_VALUE']) {
			unset($PROP['COUNTRY']);
		}
		if ($PROP['MAIN_MEDICINE'] == $element['PROPERTY_MAIN_MEDICINE_VALUE']) {
			unset($PROP['MAIN_MEDICINE']);
		}
		if ($PROP['FARM_FORM'] == $element['PROPERTY_FARM_FORM_VALUE']) {
			unset($PROP['FARM_FORM']);
		}
		if ($PROP['DOSAGE'] == $element['PROPERTY_DOSAGE_VALUE']) {
			unset($PROP['DOSAGE']);
		}
		if ($PROP['QUNTITY'] == $element['PROPERTY_QUNTITY_VALUE']) {
			unset($PROP['QUNTITY']);
		}
		if ($PROP['DESCRIPTION'] == $element['PROPERTY_DESCRIPTION_VALUE']) {
			unset($PROP['DESCRIPTION']);
		}
		if ($PROP['QUALIFICATION'] == $element['PROPERTY_QUALIFICATION_VALUE']) {
			unset($PROP['QUALIFICATION']);
		}
		if ($PROP['TYPE'] == $element['PROPERTY_TYPE_VALUE']) {
			unset($PROP['TYPE']);
		}
		if ($PROP['ANALOG_CODE'] == $element['PROPERTY_ANALOG_CODE_VALUE']) {
			unset($PROP['ANALOG_CODE']);
		}
		if ($PROP['RECOMMEND_CODE'] == $element['PROPERTY_RECOMMEND_CODE_VALUE']) {
			unset($PROP['RECOMMEND_CODE']);
		}
		if ($PROP['MEASURE'] == $element['PROPERTY_MEASURE_VALUE']) {
			unset($PROP['MEASURE']);
		}
		if ($PROP['RATE'] == $element['PROPERTY_RATE_VALUE']) {
			unset($PROP['RATE']);
		}
		$res = CIBlockElement::GetProperty(
				$this->IBLOCK_ID, 
				$element['ID'], 
				array("sort" => "asc"), 
				array("CODE" => "MORE_PHOTO")
		);
		while ($ob = $res->GetNext())
		{
			$VALUES[] = $ob['VALUE'];
		}
		$morePhoto = array();
		if(isset($PROP['MORE_PHOTO'])) {
			$morePhoto = $PROP['MORE_PHOTO'];
		}
		if(!empty($VALUES[0])) {
			foreach ($VALUES as $photo) {
				$CFile = new CFile;
				$rsFile = $CFile->GetByID($photo);
				$arFile = $rsFile->Fetch();
				foreach ($PROP['MORE_PHOTO'] as $key => $pict) {
					if ($pict['name'] == $arFile['ORIGINAL_NAME']
							&& $pict['type'] == $arFile['CONTENT_TYPE']
							&& $pict['size'] == $arFile['FILE_SIZE']
					) {
						unset($morePhoto[$key]);
					}
				}
			}
		}
		if(count($morePhoto) < 1  && isset($PROP['MORE_PHOTO'])) {
			unset($PROP['MORE_PHOTO']);
		}
		if(count($PROP)<>0) {
			CIBlockElement::SetPropertyValuesEx(
					$element[ID],
					intVal($this->IBLOCK_ID), 
					$PROP
			);
		}
		$el = new CIBlockElement;
		$PRICE_TYPE_ID = 1;
		$arFields = Array(
			"PRODUCT_ID" => $element['ID'],
			"CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
			"PRICE" => $goods['PRICE'],
			"CURRENCY" => "UAH",
			"QUANTITY_FROM" => false,
			"QUANTITY_TO" => false
		);
				//pre($arFields);
		$CPrice = new CPrice;
		$res = $CPrice->GetList(
	   		array(),
	  		array(
	        	 "PRODUCT_ID" => $element['ID'],
	        	 "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
			)
		);
		if ($arr = $res->Fetch()) {
		   CPrice::Update($arr["ID"], $arFields);
		} else {
		    CPrice::Add($arFields);
		}
		$arFields = array("ID" => $element['ID'],'QUANTITY' => '1');
		CCatalogProduct::Add($arFields);
		return $element['ID'];
		//return false;
	}
	private function addChangePriceInLOG($goods=null, $BITRIX_ID = null, $notice = 'Ok') {
		if($goods===null ||  $BITRIX_ID===null) return false;
		$sql  = " INSERT INTO `tvr_log` ";
		$sql .= " (`ID`, `EXTKEY`, `PRICE`, `NOTICE`)";
		$sql .= " VALUES ";
		$sql .= " ('', $goods[EXTKEY]', '$goods[PRICE]', '$notice') ";
		$result = $this->DB->freeQuery($sql);
	
		return $result;
	}
	private function addChangeGoodsInLOG($goods=null, $BITRIX_ID = null, $notice = 'Ok') {
		if($goods===null ||  $BITRIX_ID===null) return false;
		$sql  = " INSERT INTO `tvr_log` ";
		$sql .= " (`ID`, `BITRIXID`, `ACTIVE`, `NAME`, `CODE`, `EXTKEY`, `PARENTKEY`, `ARTNUMBER`, ";
		$sql .= " `BRAND`, `NEWPRODUCT`, `SALELEADER`, `SPECIALOFFER`, `DELIVERY`,  `PREPAID`,  `MANUFACTURER`, `COUNTRY`, `MAINMEDICINE`, ";
		$sql .= " `FARMFORM`, `DOSAGE`, `QUNTITY`, `DESCRIPTION`, `QUALIFICATION`, `TYPE`, `ANALOGCODE`, ";
		$sql .= " `RECOMMENDCODE`, `PREVIEWTEXT`, `DETAILTEXT`, `MEASURE`, `SPECIALPRICE`, `PRICE`, `RATE`, ";
		$sql .= " `DETAILPICTURE`, `MOREPICTURE`, `SIGN`, `NOTICE`) ";
		$sql .= " VALUES ";
		$sql .= " ('', '$BITRIX_ID','$goods[ACTIVE]','$goods[NAME]','$goods[CODE]', ";
		$sql .= " '$goods[EXTKEY]','$goods[PARENTKEY]','$goods[ARTNUMBER]','$goods[BRAND]', ";
		$sql .= " '$goods[NEWPRODUCT]','$goods[SALELEADER]','$goods[SPECIALOFFER]','$goods[DELIVERY]','$goods[PREPAID]','$goods[MANUFACTURER]', ";
		$sql .= " '$goods[COUNTRY]','$goods[MAINMEDICINE]','$goods[FARMFORM]','$goods[DOSAGE]', ";
		$sql .= " '$goods[QUNTITY]','$goods[DESCRIPTION]','$goods[QUALIFICATION]','$goods[TYPE]', ";
		$sql .= " '$goods[ANALOGCODE]','$goods[RECOMMENDCODE]','$goods[PREVIEWTEXT]','$goods[DETAILTEXT]', ";
		$sql .= " '$goods[MEASURE]','$goods[SPECIALPRICE]','$goods[PRICE]','$goods[RATE]', ";
		$sql .= " '$goods[DETAILPICTURE]','$goods[MOREPICTURE]','$goods[SIGN]', '$notice') ";
		$result = $this->DB->freeQueryOrd($sql);
	
		return $result;
	}
	private function getCountRowInTable()
	{
		$sql = "SELECT COUNT(ID) FROM `tvr_change`";
		
		$count = $this->DB->freeQuery($sql);
		//pre($count);
		return $count['0']['COUNT(ID)'];
	}
	private function getCountRowInTablePrice()
	{
		$sql = "SELECT COUNT(ID) FROM `tvr_change_price`";
		$count = $this->DB->freeQuery($sql);
		return $count['0']['COUNT(ID)'];
	}
	private function getGoodsChangeFromBD($limit = 50)
	{
		if($limit <= 0 || $limit > 1000 || is_string($limit)) $limit = 50;
		// выбираем только активные позиции (не удаленные)
		$sql = "SELECT * FROM `tvr_change` limit 0, $limit ";
		$this->goodsChengeInBD = $this->DB->freeQuery($sql);
		return $this->goodsChengeInBD;
	}
	private function getPriceChangeFromBD($limit = 50)
	{
		if($limit <= 0 || $limit > 1000 || is_string($limit)) $limit = 50;
		// выбираем только активные позиции (не удаленные)
		$sql = "SELECT * FROM `tvr_change_price` limit 0, $limit ";
		$this->goodsChengeInBD = $this->DB->freeQuery($sql);
		return $this->goodsChengeInBD;
	}
	private function getRecipientGoods($EXT_KEY=null, $property = null)
	{
		if($EXT_KEY===null) return false;
		$arFilter = array(
			'IBLOCK_ID' => 2,
			'=EXTERNAL_ID' => $EXT_KEY,
		);
		if($property === null) {
			$arSelect = array(
					'IBLOCK_ID', 'ID', '*','PROPERTY_ARTNUMBER','PROPERTY_BRAND', 
					'PROPERTY_NEWPRODUCT','PROPERTY_SALELEADER','PROPERTY_SPECIALOFFER','PROPERTY_DELIVERY', 'PROPERTY_PREPAID',
					'PROPERTY_MANUFACTURER','PROPERTY_COUNTRY','PROPERTY_MAIN_MEDICINE',
					'PROPERTY_FARM_FORM','PROPERTY_DOSAGE','PROPERTY_QUNTITY','PROPERTY_DESCRIPTION',
					'PROPERTY_QUALIFICATION','PROPERTY_TYPE','PROPERTY_ANALOG_CODE',
					'PROPERTY_RECOMMEND_CODE','PROPERTY_MEASURE','PROPERTY_SPECIAL_PRICE',
					'PROPERTY_PRICE','PROPERTY_RATE','PROPERTY_MORE_PHOTO','PROPERTY_RECOMMEND',
					'PROPERTY_ANALOG',
			);
		} else {
			$arSelect = array(
					'IBLOCK_ID', 'ID'
			);
		}
		$CIBlockElement = new CIBlockElement;
		$rsElement = $CIBlockElement->GetList(array('ID' => 'ASC'), $arFilter, false, false, $arSelect);
		while ($arElement = $rsElement->GetNext()) {
			return  $arElement;
		}
		return false;
	}
	private function getFileJPG($CODE=null) {
		$directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
		if ($CODE === null or empty($CODE)) {
			$img = CFile::MakeFileArray($directory.'net-foto.jpg');
			return $img;
		}
		if($this->dirArray === null) {
			$this->dirArray = scandir($directory);
		}
		foreach ($this->dirArray as $file) {
			$name = explode('.', $file); 
			if($CODE == $name[0]) {
				$img = CFile::MakeFileArray($directory.$file);
				return $img;
			}
		}
		return false;
	}
	private function getMorePictureFile($str=null) {
		//pre($str);
		if($str===null) return false;
		$arResult = array();
		$arFiles = explode(',', $str);
		//pre($arFiles);
		foreach ($arFiles as $file) {
			$arResult[] = $this->getFileJPG($file);
		}
		return $arResult;
	}
	private function getRecipientFolderForGoods($key=null) {
		if($key===null) return false;
		$key = strval($key);
		if(  true === is_int($key) && true === is_numeric($key) ) {
			return $key;
		} else {
			$sec = $this->getRecipientFolder($key);
			return $sec['ID'];
		}
	}
	
	// Операции с файлом обмена и запись в таблицу изменений товара
	// !!!  добавить методы проверки данны в файле обмена
	/*public function processingFileObmen() {
		$this->openFileObmen();
		$result = $this->readFileObmen();
		$sql = $this->InsertChangeFromFileToDB($result);
		$this->closeFileObmen();
		unset($result);
		unset($sql);
		return 'File operation finish!<br>';
	}*/
	private function delChangeGoodsInBD ($goods=null) {
		if($goods===null) return false;
		$sql  = " DELETE FROM `tvr_change` WHERE `ID`= '$goods[ID]' ";
		$result = $this->DB->freeQuery($sql);
		return $result;
	}
	private function delChangePricesInBD ($goods=null) {
		if($goods===null) return false;
		$sql  = " DELETE FROM `tvr_change_price` WHERE `ID`= '$goods[ID]' ";
		$result = $this->DB->freeQuery($sql);
		return $result;
	}
	/*private function readFileObmen() {
		while ( ($res = fgetcsv($this->ressourceObmen, 0, ';')) !== false)
		{
			$preResult[]=$res;
		}
		$head = $preResult[0];
		unset($preResult[0]);
		foreach ($preResult as $rkey => $row){
			foreach ($row as $kay => $valueQ){
				$str = trim($head[$kay]);
				$str = preg_replace('/ /', '_', $str);
				$result[$rkey][$str] = iconv('windows-1251', 'utf-8', $valueQ);
			}
		}
		unset($head);
		unset($preResult);
		$way = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
		$dirArray = scandir($way);
		$arResult = array();
		foreach ($result as $element)
		{
			/*if(empty($element['TYPE']) && empty($element['BRAND']) 
			   && empty($element['DESCRIPTION']) && empty($element['QUALIFICATION'])
					&& empty($element['DOSAGE']) && empty($element['QUNTITY'])) 
			{
				$name = ucfirst(trim($element['NAME']));
			} else {
				$name = ucfirst(trim($element['TYPE'])).' '.trim($element['BRAND']).' ';
				$name .= strtolower(trim($element['DESCRIPTION']).' '.trim($element['QUALIFICATION']).' '.trim($element['DOSAGE']));
				if(!empty($element['QUNTITY']))
				{
					$name .=' '.strtolower($element['QUNTITY']);
				}
			}*/
	/*
			$name = ucfirst(trim($element['NAME']));
			$name = trim($this->format_to_save_string($name));
			//$arParams = array("replace_space"=>"-","replace_other"=>"-");
			//$trans = Cutil::translit($name,"ru",$arParams);
			//$CODE = trim($trans, '-');
			$EXT_KEY = trim($element['EXT_KEY'],' ');
			$arResult[]= array(
					'ACTIVE' => $this->getYes($element['ACTIVE']),
					'NAME' => $name,
					'CODE' => '',
					'EXTKEY' => $EXT_KEY,
					'PARENTKEY' => $this->getParetnID($element['PARENT_KEY']), // тут непонятка возможна
					'ARTNUMBER' => $element['ARTNUMBER'],
					'BRAND' => $this->format_to_save_string($element['BRAND']),
					'NEWPRODUCT' => $this->getYes($element['NEWPRODUCT']),
					'SALELEADER' => $this->getYes($element['SALELEADER']),
					'SPECIALOFFER' => $this->getYes($element['SPECIALOFFER']),
					'DELIVERY' => $this->getYes($element['DELIVERY']),
					'PREPAID' => $this->getYes($element['PREPAID']),
					'MANUFACTURER' => $element['MANUFACTURER'],
					'COUNTRY' => $this->format_to_save_string($element['COUNTRY']),
					'MAINMEDICINE' => $element['MAIN_MEDICINE'],
					'FARMFORM' => $element['FARM_FORM'],
					'DOSAGE' => $element['DOSAGE'],
					'QUNTITY' => $element['QUNTITY'],
					'DESCRIPTION' => $this->format_to_save_string($element['DESCRIPTION']),
					'QUALIFICATION' =>  $element['QUALIFICATION'],
					'TYPE' => $element['TYPE'],
					'ANALOG_CODE' => $element['ANALOG_CODE'],
					'RECOMMEND_CODE' => $element['RECOMMEND_CODE'],
					"PREVIEWTEXT"   => $this->format_to_save_string($element['PREVIEW_TEXT']),
					"DETAILTEXT"    => $this->format_to_save_string($element['DETAIL_TEXT']),
					'MEASURE' => $element['MEASURE'],
					'SPECIALPRICE' => $element['SPECIAL_PRICE'],
					'PRICE' => $element['PRICE'],
					'RATE' => $element['RATE'],
					'DETAILPICTURE' => $element['DETAIL_PICTURE'],
					'MOREPICTURE' => $element['MORE_PICTURE'],
			);
		}
		return $arResult;
	}*/
	
/*
	 	private function InsertChangeFromFileToDB($CHANGE=null) {
		if($CHANGE===null) return false;
		$i=0;
		$sql = '';
		$title_sql  = " INSERT INTO `tvr_change` ";
		$title_sql .= " (`ID`, `ACTIVE`, `NAME`, `CODE`, `EXTKEY`, `PARENTKEY`, `ARTNUMBER`, ";
		$title_sql .= " `BRAND`, `NEWPRODUCT`, `SALELEADER`, `SPECIALOFFER`, `DELIVERY`, `PREPAID`, `MANUFACTURER`, `COUNTRY`, `MAINMEDICINE`, ";
		$title_sql .= " `FARMFORM`, `DOSAGE`, `QUNTITY`, `DESCRIPTION`, `QUALIFICATION`, `TYPE`, `ANALOG_CODE`, ";
		$title_sql .= " `RECOMMENDCODE`, `PREVIEWTEXT`, `DETAILTEXT`, `MEASURE`, `SPECIALPRICE`, `PRICE`, `RATE`, ";
		$title_sql .= " `DETAILPICTURE`, `MOREPICTURE`, `SIGN`) ";
		$title_sql .= " VALUES ";
		
		foreach ($CHANGE as $element) { 
			if($i==0) {
				$sql=$title_sql;
			}
			if($i > 0) $sql .= ", ";
			$sql .= " ('','$element[ACTIVE]','$element[NAME]','$element[CODE]', ";
			$sql .= " '$element[EXTKEY]','$element[PARENTKEY]','$element[ARTNUMBER]','$element[BRAND]', '$element[NEWPRODUCT]',";
			$sql .= " '$element[SALELEADER]','$element[SPECIALOFFER]', '$element[DELIVERY]', '$element[PREPAID]', '$element[MANUFACTURER]', ";
			$sql .= " '$element[COUNTRY]', '$element[MAINMEDICINE]', '$element[FARMFORM]', '$element[DOSAGE]', ";
			$sql .= " '$element[QUNTITY]','$element[DESCRIPTION]','$element[QUALIFICATION]','$element[TYPE]', ";
			$sql .= " '$element[ANALOGCODE]','$element[RECOMMENDCODE]','$element[PREVIEWTEXT]','$element[DETAILTEXT]', ";
			$sql .= " '$element[MEASURE]','$element[SPECIALPRICE]','$element[PRICE]','$element[RATE]', ";
			$sql .= " '$element[DETAILPICTURE]','$element[MOREPICTURE]','FILE')";
			++$i;
			if($i == 10)
			{
				$i=0;
				//pre($sql);
				$result = $this->DB->freeQuery($sql);
			}
		}
		$result = $this->DB->freeQuery($sql);
		return $sql;
	}
*/
	private function getYes($str){
		if($str === 'Y'){
			return 'Y';
		} else {
			return 'N';
		}
	}
	
	
	//*****************************************************************************************************************//
	//************************************************* КАТАЛОГ *******************************************************//
	//*****************************************************************************************************************//	
	public function doObmenCatalogFromBD(){
		$a=$this->getFolderChangeFromBD();
		$b = $this->complitePrepareFolderChange($a);
		unset($a);
		$c['level1']=$this->implementationChange($b['level1']);
		$c['level2']=$this->implementationChange($b['level2']);
		$c['level3']=$this->implementationChange($b['level3']);
		unset($b);
		return $c;
	}
	public function implementationChange($folderInBD)
	{
		$changeFolderBitrix = array();
		if($folderInBD===null) return false;
		foreach ($folderInBD as $folder)
		{
			$folder['CODE'] = Cutil::translit(strtolower(trim($folder['NAME'],' ')),"ru", $this->ruleTranslit);
			$element = $this->getRecipientFolder($folder['EXTKEY']);
			if(true===($element===false))
			{
				$id = $this->addFolder($folder);
			} else {
				$id = $this->UpdateFolder($element['ID'], $folder);
			}
			if($id > 0)
			{
				$ok = $this->addChangeLOG($folder, $id);
				if($ok == 'Ok')
				{
					$this->delChangeFolderInBD($folder);
				}
			} else {
				$changeFolderBitrix['ERROR'][] = $folder;
				$ok = $this->addChangeLOG($folder, '', 'ERROR');
				if($ok == 'Ok')
				{
					$this->delChangeFolderInBD($folder);
				}
			}
			$changeFolderBitrix['CHANGED'][] = $id;
		}
		return $changeFolderBitrix;
	}
	public function getRecipientFolder($EXTKEY=null)
	{
		//echo 'getRecipientFolder<br>';
		if($EXTKEY===null) return false;
		$EXTKEY = trim($EXTKEY,' ');
		$arFilter = array(
				'IBLOCK_ID' => intval($this->IBLOCK_ID),
				'EXTERNAL_ID' => $EXTKEY
		);
		$CIBlockSection = new CIBlockSection;
		$rsSections = $CIBlockSection->GetList(array('ID' => 'ASC'), $arFilter);
		while ($arSection = $rsSections->Fetch())
		{
			return  $arSection;
		}
		return false;		
	}
	public function getFolderFromBD()
	{
		// выбираем только активные позиции (не удаленные)
		$sql = "SELECT * FROM `ctlg_catalog` WHERE `ACTIVE`='Y' ";
		$this->folderInBD = $this->DB->freeQuery($sql);
		return $this->folderInBD;
	}
	/*public function getKnownFolder()
	{
		$arFilter = array('IBLOCK_ID' => intval($this->IBLOCK_ID), 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE' => 'Y' );
		$rsSections = CIBlockSection::GetList(array('ID' => 'ASC'), $arFilter);
		while ($arSection = $rsSections->Fetch())
		{
		   $this->knownFolder[$arSection['EXTERNAL_ID']] = $arSection;
		}
		return $this->knownFolder;
	}*/
	public function implementationCancge() { //переработать
		foreach ($this->folderInBD as $folder)
		{
			if(isset($this->knownFolder[$folder['EXTKEY']]))
			{
				$status = '';
				$element = $this->knownFolder[$folder['EXTKEY']];
				
				$status = $this->checkChange($folder, $element);
				
				if($status == 'UPDATE')
				{
					$this->UpdateFolder($element['ID'], $folder);
				}
			} else {
				$this->addFolder($folder);
			}
		}
	}
	public function addFirstLevel()
	{
		$data = array();
		foreach ($this->file['level1'] as $folder)
		{
			$this->insertFolderInBD($folder);
		}
		return $data;
	}
	public function addSecondLevel()
	{
		$data = array();
		foreach ($this->file['level2'] as $folder)
		{
			$this->insertFolderInBD($folder);
		}
		return $data;
	}
	public function addThirdLevel()
	{
		$data = array();
		foreach ($this->file['level3'] as $folder)
		{
			$this->insertFolderInBD($folder);
		}
		return $data;
	}
	
	/* ************************************************************************ */
	//							FILE											//
	/* ************************************************************************ */
	public function readFile()
	{
		while ( ($res = fgetcsv($this->ressource, 1000, ';')) !== false)
		{
			$res[0] = iconv('windows-1251', 'utf-8', $res[0]);
			$res[1] = iconv('windows-1251', 'utf-8', $res[1]);
			$res[2] = iconv('windows-1251', 'utf-8', $res[2]);
	
			$CODE = Cutil::translit(strtolower(trim($res['0'],' ')),"ru", $this->ruleTranslit);
			$EXT_KEY = 'EXT_'.randString(16);
			if(!empty($res['0']) && !isset($this->file['level1'][$CODE])){
				$this->file['level1'][$CODE]= array(
						'NAME' => strtolower($res['0']),
						'CODE' => $CODE,
						'EXTKEY' => $EXT_KEY,
						'LEVEL' => '1',
						'PARENTKEY' => 'false',
				);
			}
				
			$CODE_2 = Cutil::translit(strtolower(trim($res['1'],' ')),"ru", $this->ruleTranslit);
			$EXT_KEY_2 = 'EXT_'.randString(16);
			if(!empty($res['1']) && isset($this->file['level1'][$CODE])
					&& !isset($this->file['level2'][$CODE_2]))
			{
				$this->file['level2'][$CODE_2] = array(
						'NAME' => strtolower($res['1']),
						'CODE' => $CODE_2,
						'EXTKEY' => $EXT_KEY_2,
						'LEVEL' => '2',
						'PARENTKEY' => $this->file['level1'][$CODE]['EXT_KEY'],
	
				);
			}
				
			$CODE_3 = Cutil::translit(strtolower(trim($res['2'],' ')),"ru",$this->ruleTranslit);
			$EXT_KEY_3 = 'EXT_'.randString(16);
			if( !empty($res['2']) && !isset($this->file['level3'][$CODE_3])
					&& isset($this->file['level2'][$CODE_2])
					&& isset($this->file['level1'][$CODE])   )
			{
				$this->file['level3'][$CODE_3] = array(
						'NAME' => strtolower($res['2']),
						'CODE' => $CODE_3,
						'EXTKEY' => $EXT_KEY_3,
						'LEVEL' => '3',
						'PARENTKEY' => $this->file['level2'][$CODE_2]['EXT_KEY'],
				);
			}
		}
		return true;
	}
	public function getFileFromFTP() {
		$this->ftp->getAllFiles();
	}
	public function getFileStructure() {
		$this->ftp->getFileObmen();
		$this->ftp->getFileCatalog();
	}
	public function openFile() {
		$way_str = $_SERVER['DOCUMENT_ROOT'].'/upload/a_obmen/';
		if($this->ressource = fopen($way_str.'catalog.csv', 'r'))
		{
			echo 'ok!';
			return $this->ressource;
		} else {
			return false;
		}
	}
	public function closeFile() {
		$way_str = $_SERVER['DOCUMENT_ROOT'].'/upload/a_obmen/';
		if($this->ressource = fclose($this->ressource))
		{
			return $this->ressource;
		} else {
			return false;
		}
	}
	public function __construct($table = 0, $start = 0, $end = 0, $departament = 0) {
		$this->DB = new workWithDB;
		$this->bitrixModuleInclude();
		//$this->ftp = new ftpRequest;
		//$this->ftp->setRout('/upload/a_obmen/');
	}
	public function __destruct() {
		//$this->ftp->__destruct;
		//$this->DB->__destruct;
	}
	
	/* ************************************************************************ */
	//								PRIVATE										//
	/* ************************************************************************ */
	private function complitePrepareFolderChange($folders = null)
	{
		if($folders === null or $folders === 'Ok') {return false;}
		$res = array();
		$res['level1']=array();
		$res['level2']=array();
		$res['level3']=array();
		$res['UNKNOWN']=array();
		
		
		$del = array();
		foreach ($folders as $key => $folder) {
			$folder['NAME'] = strtolower($folder['NAME']);	
			$folder['EXTKEY'] = trim($folder['EXTKEY'],' ');
			$folder['PARENTKEY'] = trim($folder['PARENTKEY'],' ');
			
			if(empty($folder['PARENTKEY'])) {
				$folder['PARENTKEY'] = 'false';
				$folder['LEVEL'] = '1';
				$res['level1'][$folder['EXTKEY']] = $folder;
				$del[]= $key;
				unset($res['UNKNOWN'][$folder['EXTKEY']]);
			} else {
				$res['UNKNOWN'][$folder['EXTKEY']] = $folder;
			}
		}
		foreach ($del as $key){
			unset($folders[$key]);
		}
		
		$del = array();		
		foreach ($folders as $key => $folder) {	
			$folder['NAME'] = strtolower($folder['NAME']);
			$folder['EXTKEY'] = trim($folder['EXTKEY'],' ');
			$folder['PARENTKEY'] = trim($folder['PARENTKEY'],' ');
			
			if(isset($res['level1'][$folder['PARENTKEY']])) {
				$folder['LEVEL'] = '2';
				$res['level2'][$folder['EXTKEY']]=$folder;
				unset($res['UNKNOWN'][$folder['EXTKEY']]);
			}
		}
		foreach ($del as $key){
			unset($folders[$key]);
		}
		
		$del = array();		
		foreach ($folders as $key => $folder) {
			$folder['NAME'] = strtolower($folder['NAME']);
			$folder['EXTKEY'] = trim($folder['EXTKEY'],' ');
			$folder['PARENTKEY'] = trim($folder['PARENTKEY'],' ');
			
			if(isset($res['level2'][$folder['PARENTKEY']])) {
				$folder['LEVEL'] = '3';
				$res['level3'][$folder['EXTKEY']]=$folder;
				unset($res['UNKNOWN'][$folder['EXTKEY']]);
			}
		}
		foreach ($del as $key){
			unset($folders[$key]);
		}
		$del = array();
		
		foreach ($res['UNKNOWN'] as $unknown) {
			$ok = $this->addChangeLOG($unknown, '', $notice = 'Превышен уровень вложенности или родительский каталог не найден');
			if($ok === 'Ok'){
				$del[$unknown['EXTKEY']] = $this->delChangeFolderInBD($unknown);
			}
		}
		//pre($del);
		
		unset($res['UNKNOWN']);
		
		return $res;
	}
	private function getFolderChangeFromBD()
	{
		//if ($parentKey = null) {$parentKey='';}
		// выбираем только активные позиции (не удаленные)
		$sql = "SELECT * FROM `ctlg_change` "; //WHERE `PARENTKEY` = '$parentKey'
		$folderChengeInBD = $this->DB->freeQuery($sql);
		return $folderChengeInBD;
	}
	private function delChangeFolderInBD($folder=null)
	{
		if($folder===null) return false;
		$sql  = " DELETE FROM `ctlg_change` WHERE `ID`= '$folder[ID]' ";
		$result = $this->DB->freeQuery($sql);
		return $result;
	}
	private function addChangeLOG($folder=null, $BITRIXID = null, $notice = 'Ok')
	{
		if($folder===null ||  $BITRIXID===null) return false;
	
		$sql  = "INSERT INTO `ctlg_log` ";
		$sql .=	"(`ID`, `EXTKEY`, `BITRIXID`, `PARENTKEY`, `ACTIVE`, `NAME`, `CODE`,";
		$sql .=	" `DESCRIPTION`, `IMAGE`, `LEVEL`, `NOTICE`)";
		$sql .=	"VALUES ( '', '$folder[EXTKEY]', '$BITRIXID', '$folder[PARENTKEY]', 'Y', '$folder[NAME]', ";
		$sql .=	" '$folder[CODE]','$folder[DESCRIPTION]', '$folder[IMAGE]', $folder[LEVEL], '$notice') ";
		$result = $this->DB->freeQuery($sql);
		if(empty($result))
		{
			return 'ok';
		}
		return $result;
		
	}
	private function UpdateFolder($ID=null,$folder=null)
	{
		if($folder === null || $ID===null) return false;
		$bs = new CIBlockSection;
		$IBLOCK_SECTION_ID = '';
		
		$arFilter = array(
				'IBLOCK_ID' => intval($this->IBLOCK_ID), 
				'GLOBAL_ACTIVE' => 'Y', 
				'ACTIVE' => 'Y', 
				'EXTERNAL_ID' => $folder['PARENTKEY']
		);
		$rsSections = $bs->GetList(array('ID' => 'ASC'), $arFilter);
		while ($arSection = $rsSections->Fetch())
		{
		   $IBLOCK_SECTION_ID = $arSection['ID'];
		}
		
		$arFields = Array(
			'ACTIVE' => $folder['ACTIVE'],
			'IBLOCK_ID' => intval($this->IBLOCK_ID),
			'NAME' => $folder['NAME'],
			'CODE' => $folder['CODE'],
			'IBLOCK_SECTION_ID' => $IBLOCK_SECTION_ID,
			'EXTERNAL_ID'=> $folder['EXTKEY'],
		);
		
		if($ID > 0)
		{
			$res = $bs->Update($ID, $arFields);
			if($res)
			{
				return $ID;
			}else{
				return $res;
			}
		}
		else
		{
			$ID = $bs->Add($arFields);
			if($ID>0) return $ID;
		}
	}
	private function addFolder($folder)
	{
		if($folder === null) return false;
		$bs = new CIBlockSection;
		$IBLOCK_SECTION_ID = '';
		
		$arFilter = array(
				'IBLOCK_ID' => intval($this->IBLOCK_ID), 
				'GLOBAL_ACTIVE' => 'Y', 
				'ACTIVE' => 'Y', 
				'EXTERNAL_ID' => $folder['PARENTKEY'] 
		);
		
		$rsSections = $bs->GetList(array('ID' => 'ASC'), $arFilter);
		while ($arSection = $rsSections->Fetch())
		{
			$IBLOCK_SECTION_ID = $arSection['ID'];
		}
		
		$arFields = Array(
				'ACTIVE' => $folder['ACTIVE'],
				'IBLOCK_ID' => intval($this->IBLOCK_ID),
				'NAME' => $folder['NAME'],
				'CODE' => $folder['CODE'],
				'IBLOCK_SECTION_ID' => $IBLOCK_SECTION_ID,
				'EXTERNAL_ID'=> $folder['EXTKEY'],
				);
		
		$ID = $bs->Add($arFields);
		if($ID>0) return $ID;
		return false;
	}
	private function checkChange($folder = null, $element = null)
	{
		if($folder === null || $element === null) return false;
		if($element[DEPTH_LEVEL] > 1)
		{
			//получаем внешний код родительского каталога
			$rsParentSection = CIBlockSection::GetByID($element['IBLOCK_SECTION_ID']);
			if ($arParentSection = $rsParentSection->GetNext())
			{
				$element['PARENT_EXTERNAL_ID'] = $arParentSection['EXTERNAL_ID'];
			}
			
			// ищем изменения
			if($folder['CODE'] != $element['CODE'] || $folder['NAME'] != $element['NAME']
				|| $folder['ACTIVE'] != $element['ACTIVE']
				|| $folder['PARENTKEY'] != $element['PARENT_EXTERNAL_ID'] )
			{
				return 'UPDATE';
			}
		} else {
			// ищем изменения если каталог первого уровня
			if($folder['CODE'] != $element['CODE'] || $folder['NAME'] != $element['NAME']
				|| $folder['ACTIVE'] != $element['ACTIVE'] )
			{
				return 'UPDATE';
			}
		}
		return 'NO-CHANGE';
	}
	private function insertFolderInBD($array=null)
	{
		if($array===null) return false;
		$check = $this->dbCheckFolderByCODE($array['CODE']);
		if(empty($check['0']))
		{
			$sql  = "INSERT INTO `ctlg_change` (`ID`, `EXTKEY`, `PARENTKEY`,";
			$sql .= " `ACTIVE`, `NAME`, `CODE`, `LEVEL`)";
			$sql .=	"VALUES ( '', '$array[EXTKEY]', '$array[PARENTKEY]', 'Y', '$array[NAME]',  '$array[CODE]', $array[LEVEL]) ";
			$result = $this->DB->freeQuery($sql);
			return $result;
		} else {
			return $check;
		}
		return false;
	}
	private function dbCheckFolderByCODE($CODE=null)
	{
		if($CODE===null) return false;
		$sql = "SELECT * FROM `ctlg_change` WHERE CODE='$CODE' ";
		$result = $this->DB->freeQuery($sql);
		return $result;
	}
	private function bitrixModuleInclude()
	{
		if(!CModule::IncludeModule("iblock")){
			echo 'Модуль Инфоблоков не подключен!';
			$this->__destruct();
		}
		if(!Cmodule::IncludeModule('catalog')){
			echo 'Модуль торгового каталога не подключен!';
			$this->__destruct();
		}
		if(!Cmodule::IncludeModule('sale')){
			echo 'Модуль магазина не подключен!';
			$this->__destruct();
		}
	}
	private function format_to_save_string ($text)
	{
		$text = addslashes($text);
		$text= htmlspecialchars($text);
		$text = addslashes($text);
		//$text = preg_replace("/[^А-Яа-яA-Za-z0-9]/i", "", $text);
		$text = str_replace("'/\|",'',$text);
		$text = str_replace("\r\n",' ',$text);
		$text = str_replace("\n",' ',$text);
		return $text;
	}
}
