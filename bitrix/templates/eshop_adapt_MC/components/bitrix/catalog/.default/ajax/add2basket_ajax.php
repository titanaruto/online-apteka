<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include.php"); ?>

<?
if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog")) {
    if (isset($_POST['PRODUCT_ID']) && isset($_POST['QUANTITY'])) {
        $PRODUCT_ID = intval($_POST['PRODUCT_ID']);
        $QUANTITY = intval($_POST['QUANTITY']);
//        echo $PRODUCT_ID."/".$QUANTITY;
        $result = Add2BasketByProductID(
            $PRODUCT_ID,
            $QUANTITY,
            false
        );
        $arBasket = CSaleBasket::GetByID($result);

        $bLe = new CIBlockElement();
        $arElem = $bLe->GetByID($PRODUCT_ID)->GetNext();
        $cFile = new CFile();
        $arFile = $cFile->GetByID($arElem["PREVIEW_PICTURE"])->Fetch();
        $img = "/upload/" . $arFile["SUBDIR"] . "/" . $arFile["FILE_NAME"];
        echo json_encode(
                [
                    "id" => $arBasket['ID'] . "_" . $arBasket['PRODUCT_ID'],
                    "img" => $img,
                    "name" => $arBasket['NAME']
                ]
        );
    } else if (isset($_POST['PRODUCT_ID']) && isset($_POST['action']) && $_POST['action'] == "deleteProdFromCart") {
        if (is_array($_POST["PRODUCT_ID"])) {
            foreach ($_POST["PRODUCT_ID"] as $product) {
                CSaleBasket::Delete($product);
            }
        } else
            CSaleBasket::Delete($_POST["PRODUCT_ID"]);

        CSaleBasket::Init();
        $arRes = array();
        $db_res = CSaleBasket::GetList(($by="NAME"), ($order="ASC"), array("FUSER_ID"=>$_SESSION["SALE_USER_ID"], "LID"=>SITE_ID, "ORDER_ID"=>"NULL"));
        $summ = 0;
        while ($res = $db_res->GetNext())
        {
            if (strlen($res["CALLBACK_FUNC"])>0)
            {
                CSaleBasket::UpdatePrice($res["ID"], $res["CALLBACK_FUNC"], $res["MODULE"], $res["PRODUCT_ID"], $res["QUANTITY"]);
                $res = CSaleBasket::GetByID($res["ID"]);
            }
            $summ = $summ + $res["PRICE"]*$res["QUANTITY"];
        }
        echo json_encode(["summ" => $summ, "summFormatted" => str_replace(".", ",", $summ) . " ГРН.", "code" => 1]);
    } else {
        echo "Нет параметров ";
    }
} else {
    echo "Не подключены модули";
}

