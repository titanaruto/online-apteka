<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$tree = CIBlockSection::GetTreeList(
    $arFilter=Array('IBLOCK_ID' => 2),
    $arSelect=Array()
);
while($section = $tree->GetNext()) {
    $none_section = [1722, 3040];
    if (in_array($section["ID"], $none_section)) continue;
    $rsSection = CIBlockSection::GetList(array(), array('ID' => $section["ID"]), true, array());
    if ($arSection = $rsSection->GetNext()) {
        $bs = new CIBlockSection;
        if ($arSection['ELEMENT_CNT'] == 0) {
            $bs->Update($section["ID"], ["ACTIVE" => "N"]);
        } elseif ($arSection['ELEMENT_CNT'] > 0) {
            $activeElements = CIBlockSection::GetSectionElementsCount($section["ID"], Array("CNT_ACTIVE"=>"Y"));
            if ($activeElements == 0) {
                $bs->Update($section["ID"], ["ACTIVE" => "N"]);
            } else {
                $res = $bs->Update($section["ID"], ["ACTIVE" => "Y"]);
            }
        }
    }
}
