<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (!$APPLICATION->CaptchaCheckCode($_POST['captcha_word'], $_POST['captcha_code'])) {
    echo 'Неправильный ответ антиспама';
} else {
    $to = COption::GetOptionString('main','email_from');
    $boundary = md5("feedback"); //Строка для привязки вложений в письмо
    $from = "noreply@".($_SERVER["HTTP_HOST"]); //адрес, от которого придёт уведомление, можно не трогать


    $name = htmlspecialchars(stripslashes(trim($_POST['form_name'])));
    $email = htmlspecialchars(stripslashes(trim($_POST['form_email'])));
    $message = htmlspecialchars(stripslashes(trim($_POST['form_message'])));
    $subject = "Создана заявка с ".$_SERVER['HTTP_REFERER'];

    $text = '';
    $text .= (!empty($name))?'<p><strong>name:</strong> '.$name.'</p>':'';
    $text .= (!empty($email))?'<p><strong>email:</strong> '.$email.'</p>':'';
    $text .= (!empty($message))?'<p><strong>message:</strong> '.$message.'</p>':'';
    $text .= (!empty($_SERVER['HTTP_REFERER']))?'<p><strong>url:</strong> '.$_SERVER['HTTP_REFERER'].'</p>':'';

    $headers = '';
    $headers .= "From: " . $from . "\r\n";
    $headers .= "MIME-Version: 1.0 \r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=$boundary; charset=utf-8\r\n\r\n";

    $msg = "--$boundary\r\n";
    $msg .= "Content-Type: text/html; charset=utf-8\r\n";
    $msg .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $msg .= chunk_split(base64_encode($text));

    $result = mail($to, $subject, $msg, $headers);

    if($result){
        echo "Спасибо! Форма успешно отправлена";
    } else {
        echo "Ваша форма не отправлена! Попробуйте еще раз";
    }

}

?>