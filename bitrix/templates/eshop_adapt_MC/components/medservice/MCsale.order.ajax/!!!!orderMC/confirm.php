<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!empty($arResult["ORDER"]))
{
	$order_props = CSaleOrderPropsValue::GetOrderProps($arResult['ORDER']['ID']);
	while ($arProps = $order_props->Fetch()){
		if ($arProps["CODE"] == "DKCARD"){
			$DKnumb = $arProps['VALUE'];
		}
	}
	$lastCh = substr($DKnumb, -1);
	if ($lastCh & 1) {
		$card = preg_replace("/[^0-9\(\)]/", '', $_POST['odd']);
		$sql = "SELECT `VALUE` FROM `ord_discoutncard_odd` WHERE `CARDNUMBER`='$DKnumb';";
	}else {
		$card = preg_replace("/[^0-9\(\)]/", '', $_POST['even']);
		$sql = "SELECT `VALUE` FROM `ord_discoutncard_even` WHERE `CARDNUMBER`='$DKnumb';";
	}
	//pre($sql);
	$qwery = new qwery;
	$value = $qwery->frqr($sql);
	$value= $value/100;
	//pre($value);
	unset($qwery);
	//pre($arResult);
	$res = CSaleBasket::GetList(array('ID' => 'ASC'), array('ORDER_ID' => $arResult['ORDER']['ID'])); // ID заказа
	$orderList = '';
	$allOrdPr = 0;
	$CPrice = new CPrice;
	while ($staf = $res->Fetch()) {
		//pre($staf);
		if($value > 0) {
			$id = $staf['PRODUCT_ID'];
			$PRICE_TYPE_ID = 2;
			$resZZZ = $CPrice->GetList(
				array(),
				array(
					"PRODUCT_ID" => $id,
					"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
				)
			);
			$arrZZZ = $resZZZ->Fetch();
		}
		if(empty($arrZZZ['PRICE']) || $arrZZZ['PRICE']== 0){
			$price = round($staf['PRICE'] - $staf['PRICE']*$value,2);
			$price = $price*$staf['QUANTITY'];
		} else {
			$price = $staf['PRICE']*$staf['QUANTITY'];
		}
		$allOrdPr = $allOrdPr + $price;
	}
	$allOrdPr = $allOrdPr.' грн.';
	?>
	<b><?=GetMessage('SOA_TEMPL_ORDER_COMPLETE')?></b><br /><br />
	<table class="sale_order_full_table">
		<tr>
			<td>
				<?= GetMessage('SOA_TEMPL_ORDER_SUC', Array(
					'#ORDER_DATE#' => $arResult['ORDER']['DATE_INSERT'], 
					'#ORDER_ID#' => $arResult['ORDER']['ACCOUNT_NUMBER']))?>
				<br /><br />
				<?//= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
			</td>
		</tr>
		<tr>
			<td>
				<?/*<p>
					<style>
						tr td p a{display: inline;}
					</style>
					<B>Оплатить	бронирование платежной картой Visa/MasterCard возможно будет только после проверки заказа сотрудниками	call-centre. <br /><br />
					 Вам перезвонят в течении одного часа.</B>
					 <br /><br />
					
					Оплата бронирования осуществляется после подтверждения заказа, в <a href="http://online-apteka.com.ua/personal/order/"> Персональном разделе сайта </a>	(мои заказы) либо по ссылке, полученной
					на e-mail.
					<br />
					<br />
					
					В персональном разделе сайта, выбираете пункт: &quot;Оплатить услугу бронирования товара по заказу №<?=$arResult["ORDER"]['ID']?> на сумму <?=$allOrdPr?><br />
					
					<br />
					
					Вы можете следить за выполнением своего заказа в <A HREF="http://online-apteka.com.ua/personal/order/"><U>Персональном разделе сайта</U></A><br />
					<br />
					
					Обратите внимание, что для входа в этот раздел вам необходимо будет ввести логин и	пароль пользователя сайта.
				</p>
			*/?>
			<p>
				Для подтверждения заказа мы свяжемся с Вами в течение <b>двух часов.</b> <br />
				При подтверждении заказа по номеру горячей линии Вы можете выбрать адрес доставки товара в аптеку и способ оплаты. <br />
				
				1. В случае безналичного расчета (оплата услуги бронирования заказа картами Visa/MasterСard) после подтверждения Вашего заказа перейдите в Ваш <a href="/personal/order/" style="display:inline;">персональный раздел</a> сайта (Мои заказы) либо по ссылке, полученной в оповещении об оплате заказа на указанный Вами e-mail. <br />
				
				Для оплаты услуги бронирования в персональном разделе сайта выберете пункт «Оплатить». Оплатить услугу бронирования товара по безналичному расчету платежной картой Visa/MasterCard возможно будет только после проверки заказа сотрудниками call-centre. <br />
				<br />
				
				2. В случае наличного расчета (оплата за товар в аптеке) после подтверждения Вашего заказа на Ваш контактный номер телефона будет выслано СМС-оповещение о доставке товара на указанный Вами адрес аптеки Мед-сервис. Время сборки и доставки товара на указанный Вами адрес составляет от 1 до 7 дней. Доставка заказанного товара в любую удобную для Вас аптеку Мед-сервис осуществляется бесплатно. 				
				Получение товара производится путем самовывоза. Забрать собранный заказ можно по режиму работы выбранной Вами аптеки. Оплатить заказ можно будет в кассе аптеки Мед-сервис по наличному и безналичному расчету пластиковыми картами Visa, MasterCard, Maestro. <br />
				Выбор способа оплаты определяется во время звонка call-centre и подтверждается Вами лично. 
				Вы можете следить за статусом своего заказа в Вашем персональном разделе сайта. 
				Для входа в этот раздел Вам нужно будет ввести логин и пароль пользователя сайта. 
				Также Вы можете воспользоваться кнопкой «Проверить заказ» в верхнем меню сайта. 
				Для этого Вам нужно будет ввести Ваш контактный номер телефона и номер Вашего заказа.
				
			</p>

<?/*
			Оплата бронирования осуществляется после подтверждения заказа, в 
			<a href="/personal/order/" style="display:inline;">Персональном 
			разделе сайта</a> (мои заказы) либо по ссылке, полученной на e-mail.<br/>
			В персональном разделе сайта, выбираете пункт: "Оплатить 
			услугу бронирования товара по заказу №<?=$arResult["ORDER"]['ID']?> на сумму <?=$allOrdPr?><br/>
			Вы можете следить за выполнением своего заказа в <br/>
			<a href="/personal/order/" style="display:inline;">Персональном разделе сайта</a><br/>
			Обратите внимание, что для входа в этот раздел вам необходимо 
			будет ввести логин и пароль пользователя сайта.<br/>
			<b>Оплатить бронирование платежной картой Visa/MasterCard 
			возможно будет только
			 после проверки заказа сотрудниками call-centre.
			<br />Вам перезвонят в течении одного часа.</b>
			<br/><br/><br/>
			После подтверждения заказа на Ваш контактнй номер телефона 
			будет выслано СМС-оповещение о доставке товара на
			 указанный Вами адрес аптеки Мед-сервис. Время сборки и доставки 
			 товара на указаный Вами адрес составляет от 1 до 7 дней.
			  Доставка заказанного товара в любую удобную для Вас аптеку
			   Мед-сервис осуществляется бесплатно.<br/>
			Получение товара производится путем самовывоза. Забрать 
			собраный заказ можно по режиму работы выбраной Вами аптеки<br/>
			*Товар группы "Под заказ", может быть отправлен на оплату, после согласования сотрудником Call-центра
*/?>
			</td>
		</tr>
	</table>
	<?/*
	if (!empty($arResult["PAY_SYSTEM"]))
	{
		?>
		<br /><br />

		<table class="sale_order_full_table">
			<tr>
				<td class="ps_logo">
					<div class="pay_name"><?=GetMessage("SOA_TEMPL_PAY")?></div>
					<?=CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 100, 100, "border=0", "", false);?>
					<div class="paysystem_name"><?= $arResult["PAY_SYSTEM"]["NAME"] ?></div><br>
				</td>
			</tr>
			<?
			if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
			{
				?>
				<tr>
					<td>
						<?
						if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
						{
							?>
							<script language="JavaScript">
								window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>');
							</script>
							<?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))))?>
							<?
							if (CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE']))
							{
								?><br />
								<?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&pdf=1&DOWNLOAD=Y")) ?>
								<?
							}
						}
						else
						{
							if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0)
							{
								try
								{
									include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
								}
								catch(\Bitrix\Main\SystemException $e)
								{
									if($e->getCode() == CSalePaySystemAction::GET_PARAM_VALUE)
										$message = GetMessage("SOA_TEMPL_ORDER_PS_ERROR");
									else
										$message = $e->getMessage();

									echo '<span style="color:red;">'.$message.'</span>';
								}
							}
						}
						?>
					</td>
				</tr>
				<?
			}
			?>
		</table>
		<?
	}*/
}
else
{
	?>
	<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>
	<?
}?>
