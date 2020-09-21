<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/fileman/prolog.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/fileman/classes/general/sticker.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$arGroups = CUser::GetUserGroup(61335);
$arGroupAvalaible = [19];
$arGroups = CUser::GetUserGroup($USER->GetID());
$result_intersect = array_intersect($arGroupAvalaible, $arGroups);

if(empty($result_intersect))
    exit("У dас нет прав для просмотра данной страницы");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/product_comments/ProductComments.php");

$obj = new ProductComments;
$messages = $obj->getMessages();

echo "<table class='product_comments_content' border=1 cellpadding=0 cellspacing=0>";
echo "<thead><tr><td width=30>№ п/п</td><td>Автор</td><td>Комментарий</td><td width=100>Статус</td></tr></thead>";

$i = 1;

foreach ($messages as $key => $value) {
    $status = $value['PUBLISH_STATUS'] == 'P' ? true : false;
    $user = CUser::GetByID($value['AUTHOR_ID'])->Fetch();
    echo "<tr id='" . $value['ID'] . "' class='" . ($status ? '' : 'moder_red') . "'><td>" . $i++ . "</td><td>" . ($value['AUTHOR_ID'] ? "<a class='" . ($status ? '' : 'moder_white') . "' href='/bitrix/admin/user_edit.php?lang=ru&ID=" . $value['AUTHOR_ID'] . "'>" . ((empty($user['LAST_NAME']) && empty($user['NAME'])) ? $user['LOGIN'] : ($user['LAST_NAME'] . " " . $user['NAME'])) . "</a>" : $value['AUTHOR_NAME']) . "</td><td><a class='" . ($status ? '' : 'moder_white') . "' target='_blank' href='" . $value['PATH'] . "'>" . $value['POST_TEXT'] . "</a></td><td style='background: white'><div data-id='" . $value['ID'] . "' class='moder-icons'><div class='" . ($status ? 'moder-view' : 'moder-hidden') . "'></div><div class='moder-edit'></div><div class='moder-trash'></div></td></tr>";
}

echo "</table>";
?>

