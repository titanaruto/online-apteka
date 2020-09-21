<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include.php");
if ($_POST['action'] == 'checkEmail' && Tools::checkEmail($_POST['email'])) {
    echo $_POST['email'];
};
