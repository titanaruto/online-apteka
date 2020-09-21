<?define("NO_KEEP_STATISTIC", true); //Не учитываем статистику
define("NOT_CHECK_PERMISSIONS", true); //Не учитываем права доступа
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

	$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
//	$recaptcha_secret = '6LexSsEUAAAAAIdlUGwQ3A9Xlftg3irbKZ6bzlfj'; // apteka.local
	$recaptcha_secret = '6LchScEUAAAAAMg-HNgGuVyAQJWNeGzvcl7_R1ey'; // online-apteka.com.ua
	$recaptcha_response = $_POST['recaptcha_response'];

	$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
	$recaptcha = json_decode($recaptcha);

	if ($recaptcha->score >= 0.5) {
		$text = '';
		$document_name ='';
		$phone = '';

		foreach ($_REQUEST as $key => $element) {
			$value = htmlEntities($element, ENT_QUOTES, "UTF-8");
			$convertedText = mb_convert_encoding($value, 'UTF-8', mb_detect_encoding($value));
			$value = str_replace('\"', '', $convertedText);
			$value = str_replace("\'", '', $value);

			switch ($key)
			{
				case 'user_name':
					$text.='Ф.И.О.: '.$value.'<br/>';
					$document_name = $value;
					break;
				case 'user_phone':
					$text.='Телефон: '.$value.'<br/>';
					$phone = $value;
					break;
				case 'MESSAGE':
					$text.='Сообщение : '.$value.'<br/>';
					break;
			}
		}
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=UTF-8";
		$to = 'callcentre@med-service.com.ua';
		$subject = 'New Call-back form filled in'.$document_name;
		$message = $text;
		$result = mail($to, $subject, $message,implode("\r\n", $headers));

		if( (!empty($document_name) == true) && (!empty($phone) == true) ) {

			if(CModule::IncludeModule("iblock") )	{
				echo 'CModule::IncludeModule("iblock")<br>';
				$el = new CIBlockElement;
				$arLoadProductArray = Array(
					"IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
					"IBLOCK_ID"         => 6,
					"NAME"              => $document_name.'-'.$phone,
					"ACTIVE"            => "Y",
					"DETAIL_TEXT_TYPE"  => "html",
					"DETAIL_TEXT"       => $text,
				);

				if($PRODUCT_ID = $el->Add($arLoadProductArray))	{
					header("Location: $_SERVER[HTTP_REFERER]");
				}

			}

		}
		$_SESSION['MSG_AJAX_OK'] = 'Y';
	} else {
		header("Location: $_SERVER[HTTP_REFERER]");
	}

}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>
