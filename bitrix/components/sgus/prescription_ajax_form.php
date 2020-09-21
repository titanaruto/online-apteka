<?php

if (!strpos($_SERVER['HTTP_REFERER'], "online-apteka.com.ua")) {
    header( 'Location: /', true, 301 );
    die();
}
if (!isset($_COOKIE['prscrptn']))
    setcookie('prscrptn','111',time() + (3600 * 24 * 30),'/')
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>
<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="mfeedback">
    <?php
    $APPLICATION->IncludeComponent(
        "sgus:form",
        ".default",
        array(
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TIME" => "0",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "EDIT_ADDITIONAL" => "N",
            "EDIT_STATUS" => "Y",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "NOT_SHOW_FILTER" => array(
                0 => "",
                1 => "",
            ),
            "NOT_SHOW_TABLE" => array(
                0 => "",
                1 => "",
            ),
            "RESULT_ID" => 1,
            "SEF_MODE" => "N",
            "SHOW_ADDITIONAL" => "N",
            "SHOW_ANSWER_VALUE" => "N",
            "SHOW_EDIT_PAGE" => "N",
            "SHOW_LIST_PAGE" => "N",
            "SHOW_STATUS" => "Y",
            "SHOW_VIEW_PAGE" => "N",
            "START_PAGE" => "new",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "WEB_FORM_ID" => "1",
            "COMPONENT_TEMPLATE" => ".default",
            "VARIABLE_ALIASES" => array(
                "action" => "action",
            )
        ),
        false
    );
    ?>
</div>
