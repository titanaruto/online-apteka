<?php

class PgaProcessor
{
    /**
     * Проверка существования заказа.
     * Если не нашли, возвращаем XML с соответствущим телом ответа.
     *
     * @param $ID
     * @param $get
     * @return array
     */
    public static function findOrder($ID, $get = null)
    {

        $CSaleOrder = new CSaleOrder();

        $arFilter = Array(
            "ID" => $ID,
        );
        $dbOrder = $CSaleOrder->GetList(Array("ID" => "ASC"), $arFilter);

        $order = $dbOrder->GetNext();

        if(!$order)
        {
            $sxe = (isset($get) && isset($get['signature']))
                ? new SimpleXMLElement("<register-payment-response></register-payment-response>")
                : new SimpleXMLElement("<payment-avail-response></payment-avail-response>");

            $result = $sxe->addChild('result');
            $result->addChild('code', 2);
            $result->addChild('desc', 'Заказ №'.$ID.' не найден');

            $response = trim($sxe->asXML());

            self::logRecord($ID, $get, $response);

            echo $response;
            exit;
        }

        return $order;
    }

    /**
     * По ID заказа возвращает код статуса заказа.
     *
     * @param int $order_id
     * @return string
     */
    public static function getStatusCode($order_id)
    {
        $CSaleOrder = new CSaleOrder();
        $CSalePaySystemAction = new CSalePaySystemAction();

        // Получаем статус заказа.
        $arOrder = $CSaleOrder->GetByID($order_id);
        $CSalePaySystemAction->InitParamArrays($arOrder, $arOrder['ID']);
        $status = $arOrder['STATUS_ID'];

        // Распределяет по группам.
        if ($status == 'N' && $arOrder['CANCELED'] == 'N') {
            // Статус заказа 'Принят' эквивалентен отсутствию платежа.
            return 'O';
        } elseif (($status == 'P' || $status == 'F') && $arOrder['CANCELED'] == 'N') {
            // Статусы заказа 'Оплачен' и 'Отправлен' эквивалентны статусу платежа 'paid'.
            return 'P';
        } elseif ($arOrder['CANCELED'] == 'Y') {
            // Статус заказа 'Отменен' эквивалентны статусу платежа 'canceled'.
            return 'C';
        } elseif (false) {
            // Состояние заказа запрещено изменять.
            return 'Z';
        } else {
            return 'W';
        }
    }

    /**
     * Изменяет статус заказа.
     *
     * @param int $order_id
     * @param string $status
     * @param int $amount
     * @param string $auth_code
     * @return boolean
     */
    public static function setStatusCode($order_id, $status, $amount, $auth_code = '')
    {
        $CDatabase = new CDatabase();
        $CSaleOrder = new CSaleOrder();

        if ((int)$status == 1) {
            // 'удачный платеж'
            // Статус платежа '1' эквивалентен статусу заказа 'Оплачен'.
            $arFields = array(
                'STATUS_ID' => 'P',
                'DATE_STATUS' => Date($CDatabase->DateFormatToPHP(CLang::GetDateFormat('FULL', LANG))),
                'PS_STATUS' => 'Y',
                'PS_RESPONSE_DATE' => Date($CDatabase->DateFormatToPHP(CLang::GetDateFormat('FULL', LANG))),
                'PAYED' => 'Y',
                'DATE_PAYED' => Date($CDatabase->DateFormatToPHP(CLang::GetDateFormat('FULL', LANG))),
                'CANCELED' => 'N',
                'PS_STATUS_CODE' => (string)$status,
                'PS_STATUS_MESSAGE' => 'Оплата прошла успешно.',
                'PS_SUM' => $amount,
                'PS_STATUS_DESCRIPTION' => self::clearData($auth_code)
            );
        } else {
            // 'неудачный платеж'
            // Статус платежа '2' эквивалентен статусу заказа 'Отменен'.
            $arFields = array(
                'PS_STATUS' => 'N',
                'PAYED' => 'N',
                'PS_RESPONSE_DATE' => Date($CDatabase->DateFormatToPHP(CLang::GetDateFormat('FULL', LANG))),
                'DATE_PAYED' => Date($CDatabase->DateFormatToPHP(CLang::GetDateFormat('FULL', LANG))),
                //'CANCELED' => 'Y',
                //'DATE_CANCELED' => Date($CDatabase->DateFormatToPHP(CLang::GetDateFormat('FULL', LANG))),
                'PS_STATUS_CODE' => (string)$status,
                'PS_STATUS_MESSAGE' => 'Ошибка проведения платежа.',
                'PS_SUM' => $amount
            );
        }

        // Изменяет статус заказа.
        if (!empty($arFields)) {
            $CSaleOrder->Update((int)$order_id, $arFields);
        }
        return true;
    }

    public static function logRecord($order_id, $request, $xmlResponse)
    {
        global $DB;

        $SERVER_PROTOCOL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? "https://" : "http://";

        $session_id = isset($request['trx_id']) ? $request['trx_id'] : '';
        $action_name = (isset($request['signature'])) ? "PAY_REG" : "PAY_CHECK";

        $request_url = explode('&signature=', $SERVER_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        $request = (is_array($request)) ? json_encode($request) : 'request data is not array';

        $timestamp = new DateTime();

        $event = array(
            'action_to' => 'BITRIX',
            'action_name' => $action_name,
            'request_data' => str_replace('&quot;', '', htmlspecialchars($request, ENT_QUOTES)),
            'response_data' => $xmlResponse,
            'order_id' => $order_id,
            'session_id' => $session_id,
            'url' => $request_url,
            'registered_ts' => $timestamp->getTimestamp(),
            'ip' => $_SERVER['REMOTE_ADDR']
        );

        $DB->Query(
            "INSERT INTO b_pga_log (`".implode('`,`', array_keys($event))."`) VALUES ('".implode("', '", $event)."');"
            , false
            , 'File: ' . __FILE__ . '<br>Line: ' . __LINE__
        );
    }

    public static function logCertEvent($order_id, $response = array())
    {
        global $DB;

        $timestamp = new DateTime();

        $event = array(
            'action_to' => 'BITRIX',
            'action_name' => 'CERT_CHECK',
            'request_data' => '',
            'response_data' => str_replace(",", ",".PHP_EOL, json_encode($response)),
            'order_id' => $order_id,
            'session_id' => '',
            'url' => '',
            'registered_ts' => $timestamp->getTimestamp(),
            'ip' => $_SERVER['REMOTE_ADDR']
        );

        $DB->Query(
            "INSERT INTO b_pga_log (`".implode('`,`', array_keys($event))."`) VALUES ('".implode("', '", $event)."');"
            , false
            , 'File: ' . __FILE__ . '<br>Line: ' . __LINE__
        );
    }

    /**
     * Определяем платежную систему. Если не находим, возвращаем XML с соответствущим телом ответа.
     *
     * @param $order
     * @param null $get
     * @return array|bool
     */
    public static function checkPaymentSystem($order, $get = null)
    {
        $CSalePaySystem = new CSalePaySystem();
        $arPaySys = $CSalePaySystem->GetByID($order["PAY_SYSTEM_ID"], $order["PERSON_TYPE_ID"]);
        if(!$arPaySys)
        {
            $sxe = (isset($get) && isset($get['signature']))
                ? new SimpleXMLElement("<register-payment-response></register-payment-response>")
                : new SimpleXMLElement("<payment-avail-response></payment-avail-response>");

            $result = $sxe->addChild('result');
            $result->addChild('code', 2);
            $result->addChild('desc', 'Платежная система не определена.');

            $response = trim($sxe->asXML());

            self::logRecord((int)$get['o_order_id'], $get, $response);

            echo $response;
            exit();
        }

        $PS_info = unserialize($arPaySys["PSA_PARAMS"]);

        // Убедимся, что пользователь верно установил сертификат выданный эквайером.
        PgaProcessor::checkCertificate($order['ID'], $PS_info, $get);
		
        return $arPaySys;
    }

	/**
	 * Проверка существования сертификата эквайера. Может вызываться на обоих фазах проведения платежа. Для этого передаем $sxe.
	 * Убедимся, что пользователь залил серификат по фтп на сайт и корректно прописал путь до него в настройках платежного модуля $PS_info.
	 *
	 * @param $order_id
	 * @param $PS_info
	 * @param null $get
	 */
	public static function checkCertificate($order_id, $PS_info, $get = null)
	{
		$SERVER_PROTOCOL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? "HTTPS" : "HTTP";
		$PS_info['CERT_PATH']['VALUE'] = '/certificate_file.crt';
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].$PS_info['CERT_PATH']['VALUE']) || !is_file($_SERVER['DOCUMENT_ROOT'].$PS_info['CERT_PATH']['VALUE']))
		{
			$sxe = (isset($get) && isset($get['signature']))
				? new SimpleXMLElement("<register-payment-response></register-payment-response>")
				: new SimpleXMLElement("<payment-avail-response></payment-avail-response>");

			$result = $sxe->addChild('result');
			$result->addChild('code', 2);
			$result->addChild('desc', 'Unable to locate certificate');

			$response = trim($sxe->asXML());

			self::logRecord((int)$get['o_order_id'], $get, $response);

			$logData = array(
				'ERROR_MESSAGE' => 'Unable to locate certificate',
				'CERT_FILE' => $_SERVER['DOCUMENT_ROOT'].$PS_info['CERT_PATH']['VALUE'],
				'SERVER_PROTOCOL' => $SERVER_PROTOCOL,
				'HTTP_HOST' => $_SERVER['HTTP_HOST'],
				'REQUEST_URI' => $_SERVER['REQUEST_URI']
			);

			self::logCertEvent((int)$order_id, $logData);

			echo $response;
			exit();
		}
		else
		{
			$logData = array(
				'MESSAGE' => 'Certificate found',
				'CERT_FILE' => $_SERVER['DOCUMENT_ROOT'].$PS_info['CERT_PATH']['VALUE'],
				'SERVER_PROTOCOL' => $SERVER_PROTOCOL,
				'HTTP_HOST' => $_SERVER['HTTP_HOST'],
				'REQUEST_URI' => $_SERVER['REQUEST_URI']
			);

			self::logCertEvent((int)$order_id, $logData);
		}
	}

    /**
     * Проверка подписи (ЭЦП) в запросе от эквайера
     *
     * @param $arPaySys
     * @return int
     */
    public static function checkSignature($arPaySys)
    {
    	//echo 'зашли в проверку сигнатуры';
        $PS_info = unserialize($arPaySys["PSA_PARAMS"]);

        $SERVER_PROTOCOL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? "https://" : "http://";

        if($_SERVER['HTTP_HOST']=='dev.intervale.ru:443')
        {
            $request = explode('&signature=', 'https://dev.intervale.ru:33443' . $_SERVER['REQUEST_URI']);
        }
        else
            $request = explode('&signature=', $SERVER_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            
        $data = $request[0];
        $signature = base64_decode(urldecode($request[1]));
        
        // Находим сертификат эквайера
        $fp = fopen($_SERVER['DOCUMENT_ROOT'].$PS_info['CERT_PATH']['VALUE'], "r");
        if(!$fp || !$cert = fread($fp, 8192))
        {
            $response = array(
                'ERROR_MESSAGE' => 'Unable to read cert file.',
                'CERT_PATH' => $_SERVER['DOCUMENT_ROOT'].$PS_info['CERT_PATH']['VALUE'],
                'SERVER_PROTOCOL' => $SERVER_PROTOCOL,
                'HTTP_HOST' => $_SERVER['HTTP_HOST'],
				'REQUEST_URI' => $_SERVER['REQUEST_URI']
			);

			self::logCertEvent((int)$arPaySys['o.order_id'], $arPaySys, $response);
        }
		fclose($fp);

        // Достаем из сертификата публичный ключ
		$public_key = openssl_pkey_get_public ($cert);
       // pre($public_key);

        // Проверяем подпись и освобождаем ключ
		$signature_check = openssl_verify($data, $signature, $public_key, OPENSSL_ALGO_SHA1);
		openssl_free_key($public_key);

		if(!$signature_check){
		    $response = array(
			    'ERROR_MESSAGE' => 'OPENSSL_VERIFY Error',
			    'CERT_PATH' => $_SERVER['DOCUMENT_ROOT'].$PS_info['CERT_PATH']['VALUE'],
			    'SERVER_PROTOCOL' => $SERVER_PROTOCOL,
			    'HTTP_HOST' => $_SERVER['HTTP_HOST'],
			    'REQUEST_URI' => $_SERVER['REQUEST_URI'],
			    'SIGNED_DATA' => $data
		    );

		    self::logCertEvent((int)$arPaySys['o.order_id'], $arPaySys, $response);
	    }

        return $signature_check;
    }

    /**
     * Положительный ответ на проверку возможности проведения платежа
     *
     * @param $order
     * @param $arPaySys
     * @param $get
     */
    public static function paymentAvailabilityPositiveResponse($order, $arPaySys, $get)
    {
        $PS_info = unserialize($arPaySys["PSA_PARAMS"]);

        // Тело XML-ответа
        $sxe = new SimpleXMLElement("<payment-avail-response></payment-avail-response>");

        $result = $sxe->addChild('result');
        $result->addChild('code', 1);
        $result->addChild('desc', 'OK');

        $sxe->addChild('merchant-trx', $order['ID']);

        $purchase = $sxe->addChild('purchase');
        $purchase->addChild('shortDesc', 'Заказ №'.$order['ID']);
        $purchase->addChild('longDesc', 'Заказ №'.$order['ID']);

        $accountAmount = $purchase->addChild('account-amount');
        $accountAmount->addChild('id', $PS_info['ACCOUNT_PCID']['VALUE']);
        $accountAmount->addChild('amount', number_format($order['PRICE'], 2, '', ''));
        $accountAmount->addChild('currency', $PS_info['CURRENCY']['VALUE']);
        $accountAmount->addChild('exponent', '2');

        $response = trim($sxe->asXML());

        self::logRecord((int)$order['ID'], $get, $response);

        echo $response;
        exit();
    }

    public static function registerPaymentResponse($signature_check, $get)
    {
        $sxe = new SimpleXMLElement("<register-payment-response></register-payment-response>");
        $result = $sxe->addChild('result');

        if($signature_check)
        {
            $result->addChild('code', 1);
            $result->addChild('desc', 'OK');
        }
        else
        {
            $result->addChild('code', 2);
            $result->addChild('desc', 'Wrong signature.');
        }

        $response = trim($sxe->asXML());

        self::logRecord((int)$get['ID'], $get, $response);

        echo $response;
        exit();
    }

    public static function incorrectRequest($get = null)
    {
        $sxe = (isset($get) && isset($get['signature']))
            ? new SimpleXMLElement("<register-payment-response></register-payment-response>")
            : new SimpleXMLElement("<payment-avail-response></payment-avail-response>");

        $result = $sxe->addChild('result');
        $result->addChild('code', 2);
        $result->addChild('desc', 'Некорректный запрос');

        $response = trim($sxe->asXML());

        self::logRecord((int)$get['ID'], $get, $response);

        echo $response;
        exit();
    }

    public static function clearData($param)
    {
        $search = array('"\'"si', '"\""si', '"\`"si');
        return preg_replace($search, "", trim(strip_tags($param)));
    }
}
