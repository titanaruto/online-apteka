<?php

class Tools
{
    private static $login = 'MEDSERVICE';
    private static $password = 'dsmA@2C&2fkc9)ec1';
    private static $url = 'https://api-v2.hyber.im/208';

    public static function setMetaPagination ($value = null) {
        if(CModule::IncludeModule("iblock")) {
            global $DB;
            $exp = explode("/", $_SERVER['REQUEST_URI']);
            $sql = "select id FROM b_iblock_section WHERE code='" . $exp[count($exp) - 2] . "'";
            $res = $DB->Query($sql, false, $err_mess.__LINE__);
            $activeElements = CIBlockSection::GetSectionElementsCount($res->fetch()['id'], Array("CNT_ACTIVE"=>"Y"));
            $page_count = ceil($activeElements / 15);
            $isHttps = !empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']);
            $uri_parts = explode('?', $request_uri, 2);
            $url = ($isHttps ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $uri_parts[0];
            $prev = ($value != null) ? '<link rel = "prev" href = "' . $url . ($value - 1 != 0 ? '?PAGEN_1=' . ($value - 1) : '') . '">' : '';
            $next = ($value != $page_count) ? '<link rel = "next" href = "' . $url . '?PAGEN_1=' . ($value + 1) . '">' : '';
            return $prev . "\r\n" . $next . "\r\n";
        }
    }

    public static function canonicalRewrite($uri = null) {
        $arr = [
            "gclid",
            "utm_medium",
            "utm_source",
            "utm_campaign",
            "utm_content",
            "utm_term",
            "openstat",
            "action"
        ];

        if (count($_GET) > 0) {
            $isHttps = !empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']);
            $new = "";
            $http = parse_url($uri);
            parse_str($http['query'], $output);
            foreach ($_GET as $key => $item) {
                if (in_array($key, $arr)) {
                    if ($key === "action") {
                        unset($output);
                        $new = $http["path"] . '' . http_build_query($output);
                        return '<link rel="canonical" href="' . ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']) . $new . '"/>';
                    } else {
                        unset($output[$key]);
                        $new = $http["path"] . '' . http_build_query($output);
                        header("HTTP/1.1 301 Moved Permanently");
                        header("Location: " . ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']) . $new);
                        die();
                    }
                }
            }

//            return '<link rel="canonical" href="' . ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']) . $new . '"/>';
        }
    }

    public static function setCanonicalTagForPaginationPages ($uri = null) {
        if (strpos($uri, "PAGEN_") > 0 || strpos($uri, "?action=") > 0 || strpos($uri, "VOTE_ID") > 0) {
            $isHttps = !empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']);
            $uri = substr($uri, 0, strripos($uri, "/") + 1);
            $pos = strpos($uri, "SEF_APPLICATION_CUR_PAGE_URL");
            if ($pos !== false) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']) . substr($uri, strpos($uri, "/")));
                die();
            }
            return '<link rel="canonical" href="' . ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']) . $uri . '"/>';
        } else {
            return false;
        }
    }

    public static function sendSms ($phone, $text) {
        $credentials = self::$login . ':' . self::$password;
        $request_data = array(
            'phone_number' => $phone,
            'tag' => 'MEDSERVICE',
            'is_promotional' => 'true',
            'channels' => [
                'viber',
                'sms'
            ],
            'channel_options' => [
                'viber' => [
                    'text' => $text,
                    'ttl' => 60
                ],
                'sms' => [
                    'text' => $text,
                    'ttl' => 60
                ]
            ]);
        $json = json_encode($request_data);
        $ch = curl_init(self::$url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic ' . base64_encode($credentials)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }

    public static function getPromotionalPriceByProductId ($product_id = null) {
        global $DB;
        $sql = "select PRICE FROM b_catalog_price WHERE PRODUCT_ID = '" . $product_id . "' AND catalog_group_id = 2";
        $result = $DB->Query($sql, false, $err_mess.__LINE__)->fetch()['PRICE'];
        return ($result == 0 ? '' : $result);
    }

    public static function createSitemap () {
        $IBLOCK_ID = 2;
        $result = [];

        $arFilter = Array(
            'IBLOCK_ID' => $IBLOCK_ID,
            'GLOBAL_ACTIVE' => 'Y');
        $obSection = CIBlockSection::GetTreeList($arFilter);

        while($arResult = $obSection->GetNext()) {
            $result[] = $arResult["SECTION_PAGE_URL"];
        }

        $arFilter = Array(
            'IBLOCK_ID' => $IBLOCK_ID,
            'GLOBAL_ACTIVE' => 'Y');
        $db_list = CIBlockSection::GetList(array(), $arFilter, true);
        $i = 0;
        while ($ar_result = $db_list->GetNext()) {
            $arSelect = Array("DETAIL_PAGE_URL");
            $arFilter = Array("IBLOCK_ID" => IntVal($IBLOCK_ID), 'SECTION_ID' => $ar_result['ID'], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
            $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $result[] = $arFields["DETAIL_PAGE_URL"];
            }
        }

        if (!empty($result)) {
            $dirName = $_SERVER['DOCUMENT_ROOT'] . '/sitemap';
            $isHttps = !empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']);
            $strBegin = "<?xml version='1.0' encoding='UTF-8'?>\n<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
            if (file_exists($dirName)) {
                function rmRec($path) {
                    if (is_file($path)) return unlink($path);
                    if (is_dir($path)) {
                        foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
                            rmRec($path.DIRECTORY_SEPARATOR.$p);
                        return rmdir($path);
                    }
                    return false;
                }
                rmRec($dirName);
            }
            if (!file_exists($dirName)) {
                if (mkdir($dirName, 0755)) {
                    $i = 0;
                    $arr_items = [];
                    foreach ($result as $item) {
                        $arr[] = $item;
                        if (++$i%500 == 0) {
                            $file_name = "/sitemap_" . $i . ".xml";
                            $arr_items[] = $file_name;
                            $e = fopen($dirName . $file_name, "w");
                            fwrite($e, $strBegin);
                            foreach ($arr as $v) {
                                $url = ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']). $v;
                                $strToWrite = "\t<url>\n\t\t<loc>" . $url . "</loc>\n\t</url>\n";
                                fwrite($e, $strToWrite);
                            }
                            fwrite($e, "</sitemapindex>\n");
                            fclose($e);
                            $arr = [];
                        }
                    }
                    if (!empty($arr_items)) {
                        $e = fopen($dirName . "/sitemap.xml", "w");
                        fwrite($e, $strBegin);
                        foreach ($arr_items as $v) {
                            $url = ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']) . "/sitemap" . $v;
                            $strToWrite = "\t<sitemap>\n\t\t<loc>" . $url . "</loc>\n\t</sitemap>\n";
                            fwrite($e, $strToWrite);
                        }
                        fwrite($e, "</sitemapindex>\n");
                        fclose($e);
                    }
                }
            }
        }
    }

    public static function uploadPharmacyDefaultImage () {
        $arExt = ["JPG", "jpg"];
        $directory = "/home/bitrix/www/upload/a_obmen/JPG/";
        $images = glob($directory . "*.*");
        foreach($images as $image) {
            $im1 = substr($image, strripos($image, "/") + 1);
            $img = explode(".", $im1)[0];
            $ext = explode(".", $im1)[1];

            $el = new CIBlockElement;
            $file_path = $directory . $img . "." . $ext;
            if (in_array($ext, $arExt)) {
                $arLoadProductArray = Array(
                    "DETAIL_PICTURE" => CFile::MakeFileArray($file_path)
                );
                $arSelect = Array("ID");
                $arFilter = Array("IBLOCK_ID"=>12, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$img);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                while($ob = $res->GetNextElement())
                {
                    $arFields = $ob->GetFields();
                    $PRODUCT_ID = $arFields["ID"];
                    if ($el->Update($PRODUCT_ID, $arLoadProductArray))
                        echo $img . "-OK<br />";
                }
            }
        }
    }

    public static function getLeftOversExcludeCity() {
        $leftovers = [];
        $serverName='mssrv1c03.med-service.dp.ua';
        $dbname='ProductRemains';
        $username='astor';
        $password='12345678';
        $dbh = new PDO ("dblib:host=$serverName;dbname=$dbname;charset=utf8",$username,
            $password,array(1006 => "SET NAMES utf8"));
//        $sql = "SELECT IDProduct, sum(qty) FROM ProductRemains.dbo.Remains Group by IDProduct";
        $sql = "SELECT IDProduct, sum(qty) FROM ProductRemains.dbo.Remains WHERE NOT IDStore LIKE '%KR%' Group by IDProduct";
        foreach ($dbh->query($sql) as $row) {
//            $encoded = mb_convert_encoding($row[0], 'utf-8', 'cp-1251');
$encoded = $row[0];
            $leftovers[$encoded] = $row[1];
        }
        return $leftovers;
    }

    public static function getLeftovers ($id, $city) {
//        return [];
        CModule::IncludeModule("iblock");
        $result = [];
        try {
            $serverName='mssrv1c03.med-service.dp.ua';
            $dbname='ProductRemains';
            $username='astor';
            $password='12345678';

            $dbh = new PDO ("dblib:host=$serverName;dbname=$dbname;charset=utf8",$username,
                $password,array(1006 => "SET NAMES utf8"));

        } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        }
        //$encodedId = mb_convert_encoding($id, 'cp-1251', 'utf-8');
$encodedId = $id;
        $sql = "SELECT * FROM ProductRemains.dbo.Remains WHERE IDProduct = '" . $encodedId . "'";
        $idStore = [];
        foreach ($dbh->query($sql) as $row) {
            if ($row["Qty"] <= 0) continue;
            $arProps = [];
            $arFields = [];
            //$encoded = mb_convert_encoding($row["IDStore"], 'utf-8', 'cp-1251');
            $encoded = $row["IDStore"]; // local
            if (in_array($encoded, $idStore))
                continue;
            array_push($idStore, $encoded);
            $arSelect = Array("ID", "XML_ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
            $arFilter = Array("IBLOCK_ID" => 12, "XML_ID" => $encoded, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement()){
                $arFields[] = $ob->GetFields();
                $arProps[] = $ob->GetProperties();
            }

            if ($city) {
                if ($arProps[0]["CITY"]["VALUE"] != $city) {
                    continue;
                }
            }

//            $fc = fopen(__DIR__ . '/product.txt', 'a');
//            fwrite($fc, print_r([$encoded, $arProps], 1));
            if (!empty($arProps[0]["CITY"]["VALUE"])) {
                $result[] = [
                    "qty"     => $row["Qty"],
                    "city"    => $arProps[0]["CITY"]["VALUE"],
                    "address" => $arProps[0]["ADRES"]["VALUE"],
                    "name"    => $arFields[0]["NAME"],
                    "id"      => $encoded,
                    "time"    => $arProps[0]["GRAFIK_RABOTI"]["VALUE"],
                ];
            }
        }
        if ($city) {
            usort($result, function($a, $b) {
                if ($a['address'] == $b['address']) {
                    return 0;
                }
                return ($a['address'] < $b['address']) ? -1 : 1;
            });
        } else {
            usort($result, function($a, $b) {
                if ($a['city'] == $b['city']) {
                    return 0;
                }
                return ($a['city'] < $b['city']) ? -1 : 1;
            });
        }

        return $result;
    }

    public static function getPharmacyCount () {
        if (CModule::IncludeModule("iblock")) {
            $phrm_cnt = CIBlockElement::GetList(
                array(),
                array('IBLOCK_ID' => 12),
                array(),
                false,
                array('ID')
            );
            $arFilter = Array(
                "IBLOCK_ID" => 12,
                "DEPTH_LEVEL" => 3
            );

            $city_cnt = "" . CIBlockSection::GetCount($arFilter);

            return [
                $phrm_cnt,
                $city_cnt
            ];
        }
    }

    public static function getPharmacies() {
        $array = [];
        $items = [];
        $header = "Название;";
        $arrayNames = [];
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
        $arFilter = Array("IBLOCK_ID"=>12, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement()){
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            $arrayNames[] = [
                $arFields["NAME"]
            ];
            $array[] = $arProps;
        }

        foreach ($array as $item) {
            foreach ($item as $key => $value) {
                if (!empty($value["NAME"]))
                    $header .= $value["NAME"] . ";";
            }
            break;
        }

        $fc = fopen(__DIR__ . "/pharmacies.csv", "a");
        fwrite($fc, $header . "\r\n");
        $i = 0;
        foreach ($array as $item) {
            $str = $arrayNames[$i][0] . ";";
            foreach ($item as $key => $value) {
                $str .= str_replace(";", " ", $value["~VALUE"]) . ";";
                $items[$key] = $value;
            }
            fwrite($fc, $str . "\r\n");
            $i ++;
        }
    }

    public static function getProductSet($product_id) {
        $res = [];
        $result = CCatalogProductSet::getAllSetsByProduct($product_id, CCatalogProductSet::TYPE_GROUP);
        if (!empty($result)) {
            foreach ($result as $bundle) {
                foreach ($bundle["ITEMS"] as $items) {
                    $res[] = $items["ITEM_ID"];
                }
            }
        }
        return $res;
    }

    public static function checkEmail($email) {
        global $USER;
        $arSpecUser = [];
        $filter = Array();
//        $rsUsers = CUser::GetList(($by = "NAME"), ($order = "desc"), $filter);
        $rsUsers = CUser::GetList($by="", $order="", array('=EMAIL' => $email));
        while ($arUser = $rsUsers->Fetch()) {
            $arSpecUser[] = $arUser['EMAIL'];
        }
        return in_array($email, $arSpecUser);
    }

    public static function isMainPage($uri) {
        return $uri == "/" ? true : false;
    }

    public static function createCsv() {
        $arSelect = Array();
        $arFilter = Array("IBLOCK_ID" => 2, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        if ($res) {
            $url = "https://" . $_SERVER['HTTP_HOST'];
            $dirName = $_SERVER['DOCUMENT_ROOT'] . '/sitemap/csv';
            if (!file_exists($dirName))
                mkdir($dirName, 0755);
            if (file_exists($dirName)) {
                $fc = fopen($dirName . "/pharm_09.csv", "a");
                fwrite($fc, "pharmacy_id,product_sku,price,url,title\r\n");
                while($ob = $res->GetNextElement()) {
                    $arFields = $ob->GetFields();
                    $arProps = $ob->GetProperties();
                    $rsPrices = CPrice::GetList(array(), array('PRODUCT_ID' => $arFields['ID']));
                    if ($arPrice = $rsPrices->Fetch()) {
                        $url = ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']). $arFields["DETAIL_PAGE_URL"];
                        fwrite($fc, 9 . "," . $arProps["ARTNUMBER"]["VALUE"] . "," . $arPrice["PRICE"] . "," . $url . "," . str_replace(',', '-', $arFields["NAME"]) . "\r\n");
                    }
                }
                fclose($fc);
            }
        }
    }

    public static function isVisibleButtonForOrder($leftovers, $last_availability, $minPrice) {
        $date_ar = explode('.', $last_availability);
        $date = $date_ar[2] . "-" . $date_ar[1] . "-" . $date_ar[0];
        $two_days_ago = strtotime('-2 DAY', strtotime(date('Y-m-d')));
        $two_months_ago = strtotime('-2 MONTH', strtotime(date('Y-m-d')));
        $date_diff = strtotime($date) - $two_months_ago;
        $days_diff = strtotime($date) - $two_days_ago;

        if (((empty($last_availability) && !empty($leftovers)) || (!empty($last_availability) && !empty($leftovers) && $leftovers > 0 && ($days_diff >= 0 || $days_diff <= 0))) && $minPrice > 0) {
            return 1;
        } else if ((empty($leftovers) || $leftovers < 0) && $days_diff >= 0 && $minPrice > 0) {
            return 2;
        } else if ((empty($leftovers) || $leftovers < 0) && $date_diff < 0) {
            return 4;
        } else if (((empty($leftovers) || $leftovers < 0) && $date_diff >= 0 && $days_diff < 0) || ($minPrice <= 0 || empty($minPrice)))  {
            return 3;
        }
    }

    public static function getStatusProductForSearch($id, $minPrice) {
        $leftovers = CIBlockElement::GetProperty(2, $id, "sort", "asc", array("CODE" => "PHARM_LEFTOVERS"))->GetNext()["VALUE"];
        $last_availability = CIBlockElement::GetProperty(2, $id, "sort", "asc", array("CODE" => "LAST_AVAILABILITY"))->GetNext()["VALUE"];

        return self::isVisibleButtonForOrder($leftovers, $last_availability, $minPrice);
    }

    public static function autoGenerateMetaTagsForProductsAndArticles() {
        $IBLOCK_ID = 5;

        CModule::IncludeModule("iblock");

        $arFilter = Array("IBLOCK_ID" => IntVal($IBLOCK_ID), "ACTIVE" => "Y");

        $rs_section = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter);

        $ar_result = [];

        while ( $ar_section = $rs_section->Fetch() ) {
            $ar_result[] = array(
                'ID' => $ar_section['ID'],
                'NAME' => $ar_section['NAME'],
                'DEPTH_LEVEL' => $ar_section['DEPTH_LEVEL']
            );
        }

        foreach( $ar_result as $ar_value ) {
            if ($ar_value["DEPTH_LEVEL"] == 1) {

                $bs = new CIBlockSection;

                $arFields = Array(
                    "UF_META_TITLE" => $ar_value["NAME"] . " | Статьи и полезные материалы – Мед-Сервис",
                    "UF_META_DESC"  => "Полезные статьи по теме «" . $ar_value["NAME"] . "». Что нужно знать, актуальная информация, меры борьбы и профилактики | Мед-Сервис"
                );

                $bs->Update($ar_value["ID"], $arFields);
            }

            $arSelect = Array("ID", "NAME", "PREVIEW_TEXT");
            $arFilter = Array("IBLOCK_ID" => $IBLOCK_ID, "SECTION_ID" => $ar_value['ID']);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);

            while($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();

                $el = new CIBlockElement;

                $str = html_entity_decode(strip_tags(str_replace("\r", "", str_replace("\n", " ", $arFields['PREVIEW_TEXT']))));
                $str = preg_replace('|[\s]+|s', ' ', $str);
                $meta_description = mb_strimwidth($str, 0, 180, "...");

                $arLoadProductArray = Array(
                    "IPROPERTY_TEMPLATES" => array(
                        "ELEMENT_META_DESCRIPTION" => $meta_description
                    )
                );

                $el->Update($arFields["ID"], $arLoadProductArray);
            }
        }

        $IBLOCK_ID = 2;

        $arSelect = Array("ID", "NAME");
        $arFilter = Array("IBLOCK_ID" => $IBLOCK_ID, "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);

        while($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();

            CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, [
                "PRODUCT_META_DESC" => $arFields["NAME"] . " купить по низким ценам ⭐ Инструкция по применению ✅ Доставка по Украине ☎ 0-800-505-253 ⚕ интернет аптека Мед-Сервис"
            ]);
        }
    }

    public static function search($homepage, $string) {
        $CIBlockElement = new CIBlockElement;
        $result = array();
        $arFilter = Array(
            "IBLOCK_ID"=>IntVal(2),
            "NAME"=> "$string%",
            "ACTIVE"=>"Y",
        );
        $arSelect = Array('IBLOCK_ID', 'ID', 'NAME', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'XML_ID', 'SORT');

        $rsElements = $CIBlockElement->GetList(Array("NAME"=>"ASC"), $arFilter, false, Array("nPageSize"=>20), $arSelect);//"nPageSize"=>50
        while($arElem = $rsElements->GetNext()){
            $arElem["PHARM_LEFTOVERS"] = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "PHARM_LEFTOVERS"))->GetNext()["VALUE"];
            $arElem["LAST_AVAILABILITY"] = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "LAST_AVAILABILITY"))->GetNext()["VALUE"];
            $result[$arElem['ID']] = $arElem;
        }

        $arFilter = Array(
            "IBLOCK_ID"=>IntVal(2),
            "NAME"=> "%$string",
            "ACTIVE"=>"Y",
        );
        $arSelect = Array('IBLOCK_ID', 'ID', 'NAME', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'XML_ID', 'SORT');
        $rsElements = $CIBlockElement->GetList(Array("NAME"=>"ASC"), $arFilter, false, Array("nPageSize"=>20), $arSelect);//"nPageSize"=>50
        while($arElem = $rsElements->GetNext()){
            $arElem["PHARM_LEFTOVERS"] = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "PHARM_LEFTOVERS"))->GetNext()["VALUE"];
            $arElem["LAST_AVAILABILITY"] = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "LAST_AVAILABILITY"))->GetNext()["VALUE"];
            $result[$arElem['ID']] = $arElem;
        }

        $arFilter = Array(
            "IBLOCK_ID"=>IntVal(2),
            "NAME"=> "%$string%",
            "ACTIVE"=>"Y",
        );
        $arSelect = Array('IBLOCK_ID', 'ID', 'NAME', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'XML_ID', 'SORT');
        $rsElements = $CIBlockElement->GetList(Array("NAME"=>"ASC"), $arFilter, false, Array("nPageSize"=>20), $arSelect);//"nPageSize"=>50
        $ar_res_search = [];
        while($arElem = $rsElements->GetNext()){
            $ar_res_search[] = $arElem["ID"];
            $VALUES = array();
            $arElem["PHARM_LEFTOVERS"] = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "PHARM_LEFTOVERS"))->GetNext()["VALUE"];
            $arElem["LAST_AVAILABILITY"] = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "LAST_AVAILABILITY"))->GetNext()["VALUE"];
            $result[$arElem['ID']] = $arElem;
        }

        return $result;
    }

    public static function createFid() {
$dirName = $_SERVER['DOCUMENT_ROOT'] . "/upload";
if (file_exists($dirName . "/products.xml"))
                    unlink($dirName . "/products.xml");
$e = fopen($dirName . "/products.xml", "a");
        $IBLOCK_ID = 2;
        $result = [];
        $products = [];

        $arFilter = Array(
            'IBLOCK_ID' => $IBLOCK_ID,
            'GLOBAL_ACTIVE' => 'Y');
        $obSection = CIBlockSection::GetTreeList($arFilter);

        while($arResult = $obSection->GetNext()) {
            $result[] = [
                $arResult["ID"],
                $arResult["NAME"],
                $arResult["SECTION_PAGE_URL"],
            ];
        }

        $arFilter = Array(
            'IBLOCK_ID' => $IBLOCK_ID,
            'GLOBAL_ACTIVE' => 'Y');
        $db_list = CIBlockSection::GetList(array(), $arFilter, true);

        while ($ar_result = $db_list->GetNext()) {

            $arSelect = Array("DETAIL_PAGE_URL", "ID", "NAME", "PREVIEW_PICTURE", "DATE_CREATE");
            $arFilter = Array("IBLOCK_ID" => IntVal($IBLOCK_ID), 'SECTION_ID' => $ar_result['ID'], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
            $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $arProps = $ob->GetProperties();
                $pharm_leftovers = CIBlockElement::GetProperty($IBLOCK_ID, $arFields["ID"], "sort", "asc", array("CODE" => "PHARM_LEFTOVERS"))->GetNext()["VALUE"];
                $last_availability = CIBlockElement::GetProperty($IBLOCK_ID, $arFields["ID"], "sort", "asc", array("CODE" => "LAST_AVAILABILITY"))->GetNext()["VALUE"];
                $rsPrices = CPrice::GetList(array(), array('PRODUCT_ID' => $arFields['ID']))->Fetch();
                $buy_status = Tools::isVisibleButtonForOrder($pharm_leftovers, $last_availability, intval($rsPrices["PRICE"]));
                //BRAND
                //NEWPRODUCT
                //SALELEADER
                //SPECIALOFFER
                //MANUFACTURER
//                pre($arProps);
//                break;
                $products[] = [
                    $arFields["ID"],
                    $arFields["DETAIL_PAGE_URL"],
                    $arFields["NAME"],
                    $arFields["IBLOCK_SECTION_ID"],
                    !empty($arFields["PREVIEW_PICTURE"]) ? CFile::GetPath($arFields["PREVIEW_PICTURE"]) : "/upload/a_obmen/JPG/no_photo.png",
                    $rsPrices["PRICE"],
                    $rsPrices["CURRENCY"],
                    $arProps["MANUFACTURER"]["VALUE"],
                    $arFields["DATE_CREATE"],
                    $arProps["NEWPRODUCT"]["VALUE"] != "N" ? $arProps["NEWPRODUCT"]["NAME"] : ($arProps["SALELEADER"]["VALUE"] != "N" ? $arProps["SALELEADER"]["NAME"] : ($arProps["SPECIALOFFER"]["VALUE"] != "N" ? $arProps["SPECIALOFFER"]["NAME"] : "")),
                    in_array($buy_status, [2, 3]) ? "Под заказ" : ($buy_status == 4 ? "Нет в наличии" : "В наличии")
                ];
            }
        }
//pre($result);
//die;
        if (!empty($result)) {
//            $dirName = $_SERVER['DOCUMENT_ROOT'] . "/upload";
            $isHttps = !empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']);
            $site_name = $url = ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']);
            $strBegin = "<yml_catalog>\n<shop>\n<name>Med-service</name>\n<url>" . $site_name . "</url>\n<currencies>\n<currency id=\"UAH\" rate=\"1\"/>\n</currencies>\n";

            $arr_items = [];
            foreach ($result as $item) {
                $arr_items[] = $item;
            }
            if (!empty($arr_items)) {
//                if (file_exists($dirName . "/products.xml"))
//                    unlink($dirName . "/products.xml");
//pre($dirName . "/products.xml");
//                $e = fopen($dirName . "/products.xml", "a");
                fwrite($e, $strBegin);

                fwrite($e, "<categories>\n");

                foreach ($arr_items as $v) {
                    $url = $site_name . $v[2];
                    $strToWrite = "<category id=\"" . $v[0] . "\" url=\"" . $url . "\">" . $v[1] . "</category>\n";
                    fwrite($e, $strToWrite);
                }

                fwrite($e, "</categories>\n");

                fwrite($e, "<offers>\n");

                foreach ($products as $p) {
//pre($p);
                    $url = $site_name . $p[1];
                    $strToWrite = "<offer available='' id=\"" . $p[0] . "\">\n<url>" . $url . "</url>\n<name>" . $p[2] . "</name>\n<label>" . $p[9] . "</label>\n<price>" . $p[5] . "</price>\n<currencyId>" . $p[6] . "</currencyId>\n<presence>" . $p[10] . "</presence>\n<createdAt>" . $p[8] . "</createdAt>\n<vendor>" . $p[7] . "</vendor>\n<picture>" . $site_name . $p[4] . "</picture>\n<categoryId>" . $p[3] . "</categoryId>\n</offer>\n";
                    fwrite($e, $strToWrite);
                }

                fwrite($e, "</offers>\n");

                fwrite($e, "</shop>\n</yml_catalog>\n");
                fclose($e);
            }
        }
    }

}
