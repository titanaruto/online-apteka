<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:main.map",
    "",
    Array(
        "LEVEL" => "10",
        "COL_NUM" => "1",
        "SHOW_DESCRIPTION" => "N",
        "SET_TITLE" => "Y",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "3600",
        "CACHE_NOTES" => ""
    )
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>