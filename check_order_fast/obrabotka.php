<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(empty($_POST['PH']) || empty($_POST['ORD'])){die('<span onclick="hideDiv()" class="mc-button closes"></span>Ошибка...');}

$postPhone = preg_replace("/[^0-9]/", '', $_POST['PH']);
$postOrder = preg_replace("/[^0-9]/", '', $_POST['ORD']);

CModule::IncludeModule("sale");
$CSaleOrder = new CSaleOrder;
$arBOrder = $CSaleOrder->GetByID(intval($postOrder));
if (count($arBOrder) > 0) {
	$CSaleOrderPropsValue = new CSaleOrderPropsValue;
	$arSelectFields = array('*');
	$arFilter = array('ORDER_ID' => intval($postOrder));
	$arFilter['ORDER_PROPS_ID'] = 3;
	$dbProps_order = $CSaleOrderPropsValue->GetList(
		array('ID'=>'DESC'),
		$arFilter,
		false,
		false,
		$arSelectFields
	);
	if ($props_order = $dbProps_order->Fetch()){
		$ordValue = preg_replace("/[^0-9]/", '', $props_order['VALUE']);
		if($ordValue == $postPhone){
			switch ($arBOrder['STATUS_ID']){
				case 'A':
					echo '<span onclick="hideDiv()" class="mc-button closes"></span>Заказ отменен и обработан учетной системой';
					break;
				case 'B':
					echo '<span onclick="hideDiv()" class="mc-button closes"></span>Принят и обрабатывается сотрудниками call-center';
					break;
				case 'C':
					echo '<span onclick="hideDiv()" class="mc-button closes"></span>Заказ обработан и ждет заказчика';
					break;
				case 'DF':
					echo '<span onclick="hideDiv()" class="mc-button closes"></span>Отгружен';
					break;
				case 'DN':
					echo '<span onclick="hideDiv()" class="mc-button closes"></span>Ожидает обработки';
					break;
				case 'F':
					echo '<span onclick="hideDiv()" class="mc-button closes"></span>Выполнен';
					break;
				case 'N':
					echo '<span onclick="hideDiv()" class="mc-button closes"></span>Заказ принят, но пока не обрабатывается.';
					break;
				case 'P':
					echo '<span onclick="hideDiv()" class="mc-button closes"></span>Заказ оплачен, формируется к отправке клиенту.';
					break;
			}
		} else {
            echo '<span onclick="hideDiv()" class="mc-button closes"></span>Указанный телефон или заказ не найден.';
            die;
		}
	} else {
        echo '<span onclick="hideDiv()" class="mc-button closes"></span>Указанный телефон или заказ не найден.';
        die;
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>
