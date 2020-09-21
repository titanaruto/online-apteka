<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['city'])) {
        $result  = json_encode(Tools::getLeftovers($_POST["xmlId"], $_POST['city']));
    } else {
        $leftovers = Tools::getLeftovers($_POST["xmlId"], false);
        if (count($leftovers) > 0) {
            $result = '<select id="leftovers_cities"><option value="0">- Выберите город -</option>';
            $city_arr = [];
            foreach ($leftovers as $value) {
                if (in_array($value['city'], $city_arr) || empty($value['city'])) continue;
                $city_arr[] = $value['city'];
                $result .= '<option value="' . trim($value['city']) . '">' . $value['city'] . '</option>';
            }
            $result .= '</select>';
        } else {
            $result = "<h3>К сожалению, товара в наличии нет, доступен под заказ.</h3>";
        }

    }

    echo $result;
}


