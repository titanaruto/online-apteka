<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include.php");
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/Sgus/NovaPoshta/NovaPoshtaApi2.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/Sgus/UkrPoshta/UkrposhtaApi.php');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/product_comments/ProductComments.php");
\Bitrix\Main\Loader::includeModule('sale');
CModule::IncludeModule("iblock");

$np = new \LisDev\Delivery\NovaPoshtaApi2(
    'cce12933c6213748d60b2acbb64835c6',
    'ua',
    FALSE,
    'file_get_content'
);

$ukrposhta = new UkrposhtaApi('f9027fbb-cf33-3e11-84bb-5484491e2c94','ba5378df-985e-49c5-9cf3-d222fa60aa68');

if (isset($_POST['action']) && $_POST['action'] == 'deliveryCheck') {
    $listDelivery = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();

    foreach ($listDelivery as $key => $value) {
        $min_key = array_keys($listDelivery, min($listDelivery))[0];
        $arr[$min_key] = min($listDelivery);
        unset($listDelivery[$min_key]);
    }

    $delivery_list = [];

    foreach ($arr as $delivery) {
        if ($delivery["PARENT_ID"] > 0)
            continue;
        $delivery_list[] = $delivery["ID"] . ";" . $delivery["NAME"] . ";" . CFile::GetPath($delivery["LOGOTIP"]);
    }

    echo json_encode($delivery_list);
//    pre($delivery_list);

} elseif (isset($_POST['action']) && $_POST['action'] == 'getAreaNP') {
    $areas = $np->getAreas()['data'];

    $result = "<option hidden value='0'>- Выберите область -</option>";

    foreach ($areas as $area) {
        $result .= "<option value='" . $area["Ref"] . "'>" . $area["Description"] . "</option>";
    }

    echo $result;

} elseif (isset($_POST['action']) && $_POST['action'] == 'getCityNP') {
    $cities = $np->getCities()['data'];
    $result = "<option hidden value='0'>- Выберите нас. пункт -</option>";

    foreach ($cities as $city) {
        if ($city["Area"] != $_POST["area"]) {
            continue;
        }

        $result .= "<option value='" . $city["Ref"] . "'>" . $city["Description"] . "</option>";
    }

    echo $result;

} elseif (isset($_POST['action']) && $_POST['action'] == 'getWarehousesNP') {
    $warehouses = $np->getWarehouses($_POST["wareHouse"])['data'];
    $result = "<option hidden value='0'>- Выберите отделение -</option>";

    foreach ($warehouses as $warehouse) {
        $result .= "<option value='" . $warehouse["Number"] . "'>" . $warehouse["Description"] . "</option>";
    }

    echo $result;

} elseif (isset($_POST['action']) && $_POST['action'] == 'getStreetNP') {

    $totalCount = 0;
    $i = 0;
    $streets = [];
    $str = [];
    $street_array = [];

    function getStreets($city, $page) {

        global $np;
        global $totalCount;
        global $i;
        global $streets;
        global $str;

        $streets = $np->getStreet($city, '', $page);
        $str[] = array_push($str, $streets['data']);

        if ($totalCount == 0) {
            $i++;
            $totalCount = $streets['info']['totalCount'] - count($streets['data']);
        } else {
            $totalCount -= count($streets['data']);
        }

        if ($totalCount > 0) {
            $i++;
            getStreets($city, $i);
        }

        return $str;
    }

    $str = getStreets($_POST["city"], 0);

    foreach ($str as $street) {
        if (is_array($street)) {
            foreach ($street as $s) {
                $street_array[] = [
                    $s['Description'] . " (" .$s['StreetsType'] . ")",
                    $s['Ref']
                ];
            }

        }
    }

    echo json_encode($street_array, JSON_UNESCAPED_UNICODE);

} elseif (isset($_POST['action']) && $_POST['action'] == 'deliveryOption') {
    $array = [];

    $dbBasketItems = CSaleBasket::GetList(
        array("NAME" => "ASC"),
        array(
            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
            "LID" => SITE_ID,
            "ORDER_ID" => "NULL"
        ),
        false,
        false,
        array("PRODUCT_ID", "NAME")
    );

    while ($arItems = $dbBasketItems->Fetch()) {
        $db_props = CIBlockElement::GetProperty(2, $arItems['PRODUCT_ID'], array("sort" => "asc"), Array("CODE"=>"DELIVERYOPTION"))->Fetch()["VALUE"];
        if ($db_props != "N")
            continue;
        $array[] = [
            $arItems["PRODUCT_ID"],
            $arItems["NAME"],
            $db_props
        ];
    }

    if (count($array) > 0) {
        $mess = "<p>В Вашей корзине имеются товары с особыми условиями хранения, которые можно получить только в аптеке:</p>";
        $mess .= "<ul>";
        foreach ($array as $item) {
            $mess .= "<li><b>" . $item[1] . "</b></li>";
        }
        $mess .= "</ul>";
        $mess .= "Удалите их из корзины или выберите вид доставки «Забрать в аптеке»!";
    }

    echo $mess;
} elseif (isset($_POST['action']) && $_POST['action'] == 'getAreaUkr') {
    $areas = $ukrposhta->modelAreas();

    $result = "<option hidden value='0'>- Выберите область -</option>";

    foreach ($areas as $area) {
        $result .= "<option value='" . $area["REGION_ID"] . "'>" . $area["REGION_UA"] . "</option>";
    }

    echo $result;
} elseif (isset($_POST['action']) && $_POST['action'] == 'getCityUkr') {
    $cities = $ukrposhta->getCitiesByAreaId($_POST['area']);

    $result = "<option hidden value='0'>- Выберите нас. пункт -</option>";

    foreach ($cities as $city) {
        $result .= "<option data-region-id='" . $city["DISTRICT_ID"] . "' data-region-name='" . $city["DISTRICT_UA"] . "' value='" . $city["CITY_ID"] . "'>" . $city["CITY_UA"] . " (" . $city["DISTRICT_UA"] . ")</option>";
    }

    echo $result;
} elseif (isset($_POST['action']) && $_POST['action'] == 'getWarehousesUkr') {
    $departments = $ukrposhta->getPostofficesByCity($_POST['wareHouse']);

    if (!empty($departments)) {
        $result = "<option hidden value='0'>- Выберите отделение -</option>";

        foreach ($departments as $department) {
            $dep_data = $ukrposhta->getPostofficesByIndex($department["POSTCODE"])[0];
            $result .= "<option data-street-type='" . $dep_data["STREETTYPE_UA"] . "' data-street-id='" . $dep_data["POSTREET_ID"] . "' data-street-name='" . $dep_data["STREET_UA"] . "' data-house-number='" . $dep_data["HOUSENUMBER"] . "' data-postindex='" . $dep_data["POSTINDEX"] . "' value='" . $department["POSTOFFICE_ID"] . "'>" . $department["STREET_UA_VPZ"] . "</option>";
        }

        echo $result;
    } else echo "Error!";
} elseif (isset($_POST['action']) && $_POST['action'] == 'getCourierVariants') {

    $CIBlockElement = new CIBlockElement;

    $arFilter = array(
        'IBLOCK_ID' => intval(13),
        'ACTIVE' => 'Y',
    );
    $arSelectCity = array(
        'NAME',
    );
    $rsElementCity = $CIBlockElement->GetList(array('NAME' => 'DESC'), $arFilter, false, false, $arSelectCity);
    while ($arElementCity = $rsElementCity->Fetch()){
        if (trim(strtolower($_POST['city'])) === trim(strtolower($arElementCity['NAME']))) {
            echo 1;
            exit;
        }
    }
    echo 2;
} elseif (isset($_POST['action']) && $_POST['action'] == 'getCourierPrices') {
    $additional_service = \Bitrix\Sale\Delivery\ExtraServices\Manager::getExtraServicesList($_POST['deliveryId'], false);
    foreach ($additional_service as $key => $value) {
        foreach ($value['PARAMS']['PRICES'] as $item) {
            $items[] = $item['PRICE'];
        }
    }

    $sum = preg_replace('/[^0-9\,]/', '', $_POST["totalSum"]);
    $courier_cost = str_replace(',', '.', $sum) >= $items[0] ? 'бесплатно' : $items[1] . " грн.";
    $total_sum = str_replace(',', '.', $sum) < $items[0] ? (str_replace(",", ".", $sum) + $courier_cost) : $sum;

    echo "<tr class='advanced_courier'><td class='custom_t1 fwb' colspan=6 class='itog'>Сумма:</td><td class='custom_t2 fwb' class='price' id='courier-cart-sum'>" . $sum . " грн.</td></tr><tr class='advanced_courier'><td class='custom_t1 fwb' colspan=6 class='itog'>Стоимость доставки:</td><td class='custom_t2 fwb' class='price' id='delivery_cost'>" . $courier_cost . "</td></tr><tr class='advanced_courier'><td class='custom_t1 fwb' colspan=6 class='itog'>итого:</td><td class='custom_t2 fwb' class='price' id='totalOrder'>" . $total_sum . " грн.</td></tr>";

//    echo json_encode($items, JSON_UNESCAPED_UNICODE);

} elseif (isset($_POST['action']) && $_POST['action'] == 'moder-view') {
    $obj = new ProductComments;
    if ($obj->hideMessage($_POST['id']))
        echo 1;
} elseif (isset($_POST['action']) && $_POST['action'] == 'moder-hidden') {
    $obj = new ProductComments;
    if ($obj->viewMessage($_POST['id']))
        echo 2;
} elseif (isset($_POST['action']) && $_POST['action'] == 'moder-edit') {
    $obj = new ProductComments;
    if ($_POST['text']) {
        if ($obj->editMessage($_POST['text'], $_POST['id']))
            echo 3;
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'moder-trash') {
    $obj = new ProductComments;
    if ($obj->deleteMessage($_POST['id']))
        echo 4;
}
