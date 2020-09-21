<?php
/*************************************
регистрация и авторизовация пользователя на битриксе
Используем для регистрации из соц. сетей. На примере facebook.
 *************************************/
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// print_r($_POST);

$CUser = new CUser; // объявляем класс для работы с пользователями

//первым делом проверям существование пользователя, есть ли такой пользователь в базе по email.
//полцаем id пользователя если он существует
$filter = Array("EMAIL" => trim($_POST["email"]));
$sql = CUser::GetList(($by="id"), ($order="desc"), $filter);
if($sql->NavNext(true, "f_"))
{
    if (!empty($f_ID)) {//дополнительная проверка на пустоту
        $USER->Authorize($f_ID);//и сама авторизация пользователя
        echo json_encode(array('rez'=>'true'));
    }
}else{
    //если такого пользователя не существует, то регистрируем его
    $arFields = array(
        'LOGIN' => trim($_POST["email"]),
        'EMAIL' => trim($_POST["email"]),
        'NAME'=> trim($_POST["first_name"]),
        'LAST_NAME' => trim($_POST["last_name"]),
        // 'PASSWORD' => '123456789',
        // 'CONFIRM_PASSWORD' => '123456789',
        "LID"               => SITE_ID,
        "ACTIVE"            => "Y",//делаем его активным пользователем
        'EXTERNAL_AUTH_ID' => 'Facebook',//метот авторизации
        'UF_SOC_FK' => trim($_POST["link"])//страница пользователя? в facebook в нашем примере.
    );
    $USER_ID = $CUser->Add($arFields);//добавлем пользователя и присваиваем ему id
    //сделаем проверку, дествительно ли он зарегистрирован
    if (!empty($USER_ID)) {
        $USER->Authorize($USER_ID);//авторизовываем пользователя его его id
        echo json_encode(array('rez'=>'true'));
    }
}

?>