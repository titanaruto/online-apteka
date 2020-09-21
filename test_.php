<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

die;

require($_SERVER["DOCUMENT_ROOT"] . "/local/phpmailer/src/phpmailer.php");
//require($_SERVER["DOCUMENT_ROOT"] . "/local/phpmailer/src/exception.php");
require($_SERVER["DOCUMENT_ROOT"] . "/local/phpmailer/src/smtp.php");

pre($_SERVER["DOCUMENT_ROOT"] . "/local/phpmailer/src/phpmailer.php");

$mail = new PHPMailer(true);

try {
    //Recipients
    $mail->setFrom('online-apteka@med-service.dp.ua', 'Mailer');
    $mail->addAddress('gusev6203@gmail.com', 'Joe User');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';

    $mail->send();

    $fc = fopen(__DIR__ . "/phpmailer_success.txt", "a");

    echo 'Message has been sent';
} catch (Exception $e) {
    $fc = fopen(__DIR__ . "/phpmailer_fail.txt", "a");
    fwrite($fc, $mail->ErrorInfo);
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

die;