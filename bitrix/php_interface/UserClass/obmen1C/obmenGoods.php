<?
class obmenGoods extends obmen {
    private $DB;

    public function __construct () {
        parent::__construct();
        $this->DB = new workWithDB;
   }
    public function __destruct() {
        $this->DB->__destruct();
        parent::__destruct();
    }
    public function implementationPriceChange() {
        //$changeGoodsBitrix = array();
        while (intval($this->getCountRowInTablePrice()) > 0 ){
            $goodsInBD = $this->getPriceChangeFromBD();
            foreach ($goodsInBD as $goods){
                $element = $this->getRecipientGoods($goods['EXTKEY']);
                if($element !== 'noRecipientGoods'){
                    $id = $this->updatePrice($element, $goods);
                } else {
                    $id = -1;
                }
                if($id > 0){
                    $ok = $this->addChangePriceInLOG($goods, $id);
                    if($ok == 'Ok'){
                        $res = $this->delChangePricesInBD($goods);
                    }
                } else {
                    $changeGoodsBitrix['ERROR'][] = $goods;
                    $ok = $this->addChangePriceInLOG(
                        $goods,
                        '',
                        'Скорее всего нет такого товара для обновления цены'
                    );
                    if($ok == 'Ok'){
                        $res = $this->delChangePricesInBD($goods);
                    }
                }
                //$changeGoodsBitrix['CHANGED'][] = $id;
            }
        }
        //return $changeGoodsBitrix;
    }
    public function implementationChangeGoods() {
        //pre('implementationChangeGoods');
        //$changeGoodsBitrix = array();
        //$changeGoodsBitrix['CHANGED']=array();
        while (intval($this->getCountRowInTable()) > 0 ){
            //по умолчанию выбираются первые 50 строк
            $goodsInBD = $this->getGoodsChangeFromBD();
            foreach ($goodsInBD as $goods) {
                $element = $this->getRecipientGoods(trim($goods['EXTKEY'],' '));
                //pre($element);
                if($element === 'noRecipientGoods') {
                    $id = $this->addGoods($goods);
                } else {
                    //pre('UPTD');
                    $id = $this->updateGoods($element, $goods);
                }
                //pre($id);
                if($id > 0) {
                    //pre($id);
                    $ok = $this->addChangeGoodsInLOG($goods, $id);
                    //pre($ok);
                    if($ok == 'Ok'){
                        $res = $this->delChangeGoodsInBD($goods);
                    }
                    //pre($id);
                } else {
                    //$changeGoodsBitrix['ERROR'][] = $goods;
                    $ok = $this->addChangeGoodsInLOG($goods, '', 'NO PARENT FOLDER FIND');
                    if($ok == 'Ok'){
                        $res = $this->delChangeGoodsInBD($goods);
                    }
                }
                //exit();
            }
            //exit();
        }
    }

    private function strToArID($str=null){
        if($str===null) return false;
        $text = str_replace(" ",',',$str);
        $text = preg_replace('/\s/', ',', $text);
        $str = trim($text,',');
        $res = explode(',', $str);
        $arResipient = array();
        foreach ($res as $code) {
            if(!empty($code)){
                $a = $this->getRecipientGoods($code,'SHORT');
                if($a !== 'noRecipientGoods') {
                    $arResipient[] = $a;
                }
            }
        }
        return $arResipient;
    }
    private function getLASTChangeElements($sec = 1800) {
        $sql  = " SELECT `ID`, `BITRIXID`, `RECOMMENDCODE`, `ANALOGCODE` FROM `tvr_log` WHERE ";
        $sql .= " `NOTICE`='Ok' and `ANALOGCODE` !='' and `RECOMMENDCODE` != '' ";
        $sql .= "  and `TIMECHANGE` >= date_sub(now(), INTERVAL $sec SECOND) ";
        $result = $this->DB->freeQuery($sql);
        return $result;
    }
    private function addGoods($goods = null) {
        //pre($goods[NAME]);
        //pre('Add Goods');
        if($goods === null) return false;
        usleep(10000);
        $name = $this->my_mb_ucfirst(trim($this->format_to_save_string($goods['NAME'])));
        $name = str_replace(array('\\',),'',$name);
        $arParams = array("replace_space"=>"-","replace_other"=>"-");
        $trans = Cutil::translit($name,"ru",$arParams);
        $CODE = trim($trans, '-');
        $PROP = array(
            'ARTNUMBER' => $goods['ARTNUMBER'],
            'BRAND' => $goods['BRAND'],
            'NEWPRODUCT' => $goods['NEWPRODUCT'],
            'SALELEADER' => $goods['SALELEADER'],
            'SPECIALOFFER' => $goods['SPECIALOFFER'],
            'DELIVERY' => $goods['DELIVERY'],
            'PREPAID' => $goods['PREPAID'],
            'MANUFACTURER' => $goods['MANUFACTURER'],
            'COUNTRY' => $goods['COUNTRY'],
            'MAIN_MEDICINE' => $goods['MAINMEDICINE'],
            'FARM_FORM' => $goods['FARMFORM'],
            'DOSAGE' => $goods['DOSAGE'],
            'QUNTITY' => $goods['QUNTITY'],
            'DESCRIPTION' => $goods['DESCRIPTION'],
            'QUALIFICATION' =>  $goods['QUALIFICATION'],
            'TYPE' => $goods['TYPE'],
            'RECOMMEND_CODE' => $goods['RECOMMENDCODE'],
            'ANALOG_CODE' => $goods['ANALOGCODE'],
            'MEASURE' => $goods['MEASURE'],
            'RATE' => $goods['RATE'],
            'RECOMMEND_CODE' => $goods['RECOMMENDCODE'],
            'ANALOG_CODE' => $goods['ANALOGCODE'],
            'MORE_PHOTO' => $this->getMorePictureFile($goods['MOREPICTURE']),
        );
        $prImage = $this->getFileJPG($goods['DETAILPICTURE']);
        if($prImage == 'FAIL') {
            $prImage = $this->getFileJPG();
        }
        $paretnKey = $this->getRecipientFolderForGoods($goods['PARENTKEY']);

        if($paretnKey == 'noRecipientFolder') return -1;
        $arLoadProductArray = Array(
            'MODIFIED_BY'    => intVal(1),
            'IBLOCK_SECTION_ID' => $paretnKey,
            'IBLOCK_ID'      => intVal($this->IBLOCK_ID),
            'EXTERNAL_ID' => $goods['EXTKEY'],
            'CODE' => $CODE,
            'PROPERTY_VALUES'=> $PROP,
            /*'NAME'           => $this->my_mb_ucfirst($goods['NAME']),*/
            'NAME'           => $name,
            'ACTIVE'         => $goods['ACTIVE'],
            'PREVIEW_TEXT_TYPE' =>'text',
            'PREVIEW_TEXT'      =>$goods['PREVIEWTEXT'],
            'DETAIL_TEXT_TYPE'  =>'html',
            'DETAIL_TEXT'       =>$goods['DETAILTEXT'],
            'DETAIL_PICTURE' => $prImage,
        );
        //pre($arLoadProductArray);
        // Совпадения по символьному коду
        $el = new CIBlockElement;

        if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            $CPrice = new CPrice;
            if($goods['PRICE'] > $goods['SPECIALPRICE'] && $goods['SPECIALPRICE']!=0 && $goods['PRICE']!=0){
                $PRICE_TYPE_ID = 1;
                $arFields = Array(
                    "PRODUCT_ID" => $PRODUCT_ID,
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                    "PRICE" => $goods['SPECIALPRICE'],
                    "CURRENCY" => "UAH",
                    "QUANTITY_FROM" => false,
                    "QUANTITY_TO" => false
                );
                $res = $CPrice->GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $PRODUCT_ID,
                        "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                    )
                );
                if($arr = $res->Fetch()) {
                    CPrice::Update($arr["ID"], $arFields);
                }else{
                    CPrice::Add($arFields);
                }

                $PRICE_TYPE_ID = 2;
                $arFields = Array(
                    "PRODUCT_ID" => $PRODUCT_ID,
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                    "PRICE" => $goods['PRICE'],
                    "CURRENCY" => "UAH",
                    "QUANTITY_FROM" => false,
                    "QUANTITY_TO" => false
                );
                $res = $CPrice->GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $PRODUCT_ID,
                        "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                    )
                );
                if($arr = $res->Fetch()) {
                    CPrice::Update($arr["ID"], $arFields);
                }else{
                    CPrice::Add($arFields);
                }
                $arFields = array("ID" => $PRODUCT_ID,'QUANTITY' => '10000000');
                CCatalogProduct::Add($arFields);
                return $PRODUCT_ID;
            } else {
                $PRICE_TYPE_ID = 1;
                $arFields = Array(
                    "PRODUCT_ID" => $PRODUCT_ID,
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                    "PRICE" => $goods['PRICE'],
                    "CURRENCY" => "UAH",
                    "QUANTITY_FROM" => false,
                    "QUANTITY_TO" => false
                );
                $res = $CPrice->GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $PRODUCT_ID,
                        "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                    )
                );
                if($arr = $res->Fetch()) {
                    CPrice::Update($arr["ID"], $arFields);
                }else{
                    CPrice::Add($arFields);
                }

                $PRICE_TYPE_ID = 2;
                $arFields = Array(
                    "PRODUCT_ID" => $PRODUCT_ID,
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                    "PRICE" => $goods['SPECIALPRICE'],
                    "CURRENCY" => "UAH",
                    "QUANTITY_FROM" => false,
                    "QUANTITY_TO" => false
                );
                $res = $CPrice->GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $PRODUCT_ID,
                        "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                    )
                );
                if($arr = $res->Fetch()) {
                    CPrice::Update($arr['ID'], $arFields);
                }else{
                    CPrice::Add($arFields);
                }
                $arFields = array("ID" => $PRODUCT_ID,'QUANTITY' => '10000000');
                CCatalogProduct::Add($arFields);
                //return $PRODUCT_ID;
            }
            if($goods['PRICE'] == 0.00 && $goods['SPECIALPRICE'] == 0.00 ){
                $PRICE_TYPE_ID = 1;
                $arFields = Array(
                    "PRODUCT_ID" => $PRODUCT_ID,
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                    "PRICE" => $goods['PRICE'],
                    "CURRENCY" => "UAH",
                    "QUANTITY_FROM" => false,
                    "QUANTITY_TO" => false
                );
                $res = $CPrice->GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $PRODUCT_ID,
                        "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                    )
                );
                if($arr = $res->Fetch()) {
                    CPrice::Update($arr["ID"], $arFields);
                }else{
                    CPrice::Add($arFields);
                }

                $PRICE_TYPE_ID = 2;
                $arFields = Array(
                    "PRODUCT_ID" => $PRODUCT_ID,
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                    "PRICE" => $goods['SPECIALPRICE'],
                    "CURRENCY" => "UAH",
                    "QUANTITY_FROM" => false,
                    "QUANTITY_TO" => false
                );
                $res = $CPrice->GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $PRODUCT_ID,
                        "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                    )
                );
                if($arr = $res->Fetch()) {
                    CPrice::Update($arr["ID"], $arFields);
                }else{
                    CPrice::Add($arFields);
                }
                $arFields = array("ID" => $PRODUCT_ID,'QUANTITY' => '0');
                CCatalogProduct::Add($arFields);
                return $PRODUCT_ID;
            }
            return $PRODUCT_ID;
        }else{
            return "Error: ".$el->LAST_ERROR;
        }
        return false;
    }
    private function updatePrice($element=null, $goods=null){
        if($goods === null || $element===null) return false;
        usleep(10000);
        $CPrice = new CPrice;
        if($goods['PRICE'] > $goods['SPECIALPRICE'] && $goods['SPECIALPRICE']!=0 && $goods['PRICE']!=0){
            $PRICE_TYPE_ID = 1;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['SPECIALPRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }

            $PRICE_TYPE_ID = 2;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['PRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }
            $arFields = array("ID" => $element['ID'],'QUANTITY' => '10000000');
            CCatalogProduct::Add($arFields);
            return $element['ID'];
        } else {
            $PRICE_TYPE_ID = 1;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['PRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }

            $PRICE_TYPE_ID = 2;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['SPECIALPRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr['ID'], $arFields);
            }else{
                CPrice::Add($arFields);
            }
            $arFields = array("ID" => $element['ID'],'QUANTITY' => '10000000');
            CCatalogProduct::Add($arFields);
            //return $element['ID'];
        }
        if($goods['PRICE'] == 0.00 && $goods['SPECIALPRICE'] == 0.00 ){
            $PRICE_TYPE_ID = 1;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['PRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }

            $PRICE_TYPE_ID = 2;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['SPECIALPRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }
            $arFields = array("ID" => $arr['ID'],'QUANTITY' => '0');
            CCatalogProduct::Add($arFields);
            return $element['ID'];
        }
        return $element['ID'];
    }
    private function updateGoods($element=null, $goods=null) {
        //pre($goods['NAME']);
        //pre('Update Goods');
        if($goods === null || $element===null) return false;
        usleep(10000);
        $name = $this->my_mb_ucfirst(trim($this->format_to_save_string($goods['NAME'])));
        $name = str_replace(array('\\',),'',$name);
        $arParams = array("replace_space"=>"-","replace_other"=>"-");
        $trans = Cutil::translit($name,"ru",$arParams);
        $CODE = trim($trans, '-');
        $prImage = $this->getFileJPG($goods['DETAILPICTURE']);
        if($prImage=='FAIL') {
            $prImage = $this->getFileJPG();
        }
        $paretnKey = $this->getRecipientFolderForGoods($goods['PARENTKEY']);
        //pre($paretnKey);
        if($paretnKey == 'noRecipientFolder') return -1;
        $arLoadProductArray = Array (
            'IBLOCK_SECTION_ID' => $paretnKey,
            'CODE'              => $CODE,
            'NAME'              => $name,
            'ACTIVE'            => $goods['ACTIVE'],
            'PREVIEW_TEXT_TYPE' => 'text',
            'PREVIEW_TEXT'      => $goods['PREVIEWTEXT'],
            'DETAIL_TEXT_TYPE'  => 'html',
            'DETAIL_TEXT'       => $goods['DETAILTEXT'],
            'DETAIL_PICTURE'    => $prImage,
        );
        //pre($element);
        if(isset($element['DETAIL_PICTURE'])) {
            $CFile = new CFile;
            $rsFile = $CFile->GetByID($element['DETAIL_PICTURE']);
            $arFile = $rsFile->Fetch();
        }
        if ($arLoadProductArray['NAME'] == $element['NAME']) {
            unset($arLoadProductArray['NAME']);
        }
        if ($arLoadProductArray['CODE'] == $element['CODE'] ) {
            unset($arLoadProductArray['CODE']);
        }
        if ($arLoadProductArray['IBLOCK_SECTION_ID'] == $element['IBLOCK_SECTION_ID']) {
            unset($arLoadProductArray['IBLOCK_SECTION_ID']);
        }
        if ($arLoadProductArray['ACTIVE'] == $element['ACTIVE']) {
            unset($arLoadProductArray['ACTIVE']);
        }
        if ($arLoadProductArray['PREVIEW_TEXT'] == $element['~PREVIEW_TEXT']) {
            unset($arLoadProductArray['PREVIEW_TEXT_TYPE']);
            unset($arLoadProductArray['PREVIEW_TEXT']);
        }
        if ($arLoadProductArray['DETAIL_TEXT'] == $element['~DETAIL_TEXT']) {
            unset($arLoadProductArray['DETAIL_TEXT_TYPE']);
            unset($arLoadProductArray['DETAIL_TEXT']);
        }
        if(isset($element['DETAIL_PICTURE'])) {
            if ($arLoadProductArray['DETAIL_PICTURE']['name'] == $arFile['ORIGINAL_NAME']
                && $arLoadProductArray['DETAIL_PICTURE']['type'] == $arFile['CONTENT_TYPE']
                && $arLoadProductArray['DETAIL_PICTURE']['size'] == $arFile['FILE_SIZE']
            ) {
                unset($arLoadProductArray['DETAIL_PICTURE']);
            }
        }
        $arCount = count($arLoadProductArray);
        //pre($arLoadProductArray);
        if($arCount<>0){
            // Совпадения по символьному коду!!!!
            $el = new CIBlockElement;
            $sss = $el->Update($element['ID'], $arLoadProductArray);
            //pre($sss);
        }
        //pre($goods['DESCRIPTION']);
        $PROP = array(
                'ARTNUMBER' => $goods['ARTNUMBER'],
                'BRAND' => $goods['BRAND'],
                'NEWPRODUCT' => $goods['NEWPRODUCT'],
                'SALELEADER' => $goods['SALELEADER'],
                'SPECIALOFFER' => $goods['SPECIALOFFER'],
                'DELIVERY' => $goods['DELIVERY'],
                'PREPAID' => $goods['PREPAID'],
                'MANUFACTURER' => $goods['MANUFACTURER'],
                'COUNTRY' => $goods['COUNTRY'],
                'MAIN_MEDICINE' => $goods['MAINMEDICINE'],
                'FARM_FORM' => $goods['FARMFORM'],
                'DOSAGE' => $goods['DOSAGE'],
                'QUNTITY' => $goods['QUNTITY'],
                'DESCRIPTION' => $goods['DESCRIPTION'],
                'QUALIFICATION' =>  $goods['QUALIFICATION'],
                'TYPE' => $goods['TYPE'],
                'RECOMMEND_CODE' => $goods['RECOMMENDCODE'],
                'ANALOG_CODE' => $goods['ANALOGCODE'],
                'MEASURE' => $goods['MEASURE'],
                'RATE' => $goods['RATE'],
        );
        //pre($PROP);

        if(!empty($goods['MOREPICTURE'])) {
            $PROP['MORE_PHOTO'] = $this->getMorePictureFile($goods['MOREPICTURE']);
        }
        if($element['PROPERTY_NEWPRODUCT_VALUE'] != 'Y') $element['PROPERTY_NEWPRODUCT_VALUE'] = 'N';
        if($element['PROPERTY_SALELEADER_VALUE'] != 'Y') $element['PROPERTY_SALELEADER_VALUE'] = 'N';
        if($element['PROPERTY_SPECIALOFFER_VALUE'] != 'Y') $element['PROPERTY_SPECIALOFFER_VALUE'] = 'N';
        if($element['PROPERTY_DELIVERY_VALUE'] != 'Y') $element['PROPERTY_DELIVERY_VALUE'] = 'N';
        if($element['PROPERTY_PREPAID_VALUE'] != 'Y') $element['PROPERTY_PREPAID_VALUE'] = 'N';

        if ($PROP['NEWPRODUCT'] == $element['PROPERTY_NEWPRODUCT_VALUE']) {
            unset($PROP['NEWPRODUCT']);
        }
        if ($PROP['SALELEADER'] == $element['PROPERTY_SALELEADER_VALUE']) {
            unset($PROP['SALELEADER']);
        }
        if ($PROP['SPECIALOFFER'] == $element['PROPERTY_SPECIALOFFER_VALUE']) {
            unset($PROP['SPECIALOFFER']);
        }
        if ($PROP['DELIVERY'] == $element['PROPERTY_DELIVERY_VALUE']) {
            unset($PROP['DELIVERY']);
        }
        if ($PROP['PREPAID'] == $element['PROPERTY_PREPAID_VALUE']) {
            unset($PROP['PREPAID']);
        }
        if ($PROP['ARTNUMBER'] == $element['PROPERTY_ARTNUMBER_VALUE']) {
            unset($PROP['ARTNUMBER']);
        }
        if ($PROP['BRAND'] == $element['PROPERTY_BRAND_VALUE']) {
            unset($PROP['BRAND']);
        }
        if ($PROP['MANUFACTURER'] == $element['PROPERTY_MANUFACTURER_VALUE']) {
            unset($PROP['MANUFACTURER']);
        }
        if ($PROP['COUNTRY'] == $element['PROPERTY_COUNTRY_VALUE']) {
            unset($PROP['COUNTRY']);
        }
        if ($PROP['MAIN_MEDICINE'] == $element['PROPERTY_MAIN_MEDICINE_VALUE']) {
            unset($PROP['MAIN_MEDICINE']);
        }
        if ($PROP['FARM_FORM'] == $element['PROPERTY_FARM_FORM_VALUE']) {
            unset($PROP['FARM_FORM']);
        }
        if ($PROP['DOSAGE'] == $element['PROPERTY_DOSAGE_VALUE']) {
            unset($PROP['DOSAGE']);
        }
        if ($PROP['QUNTITY'] == $element['PROPERTY_QUNTITY_VALUE']) {
            unset($PROP['QUNTITY']);
        }
        if ($PROP['DESCRIPTION'] == $element['PROPERTY_DESCRIPTION_VALUE']) {
            unset($PROP['DESCRIPTION']);
        }
        if ($PROP['QUALIFICATION'] == $element['PROPERTY_QUALIFICATION_VALUE']) {
            unset($PROP['QUALIFICATION']);
        }
        if ($PROP['TYPE'] == $element['PROPERTY_TYPE_VALUE']) {
            unset($PROP['TYPE']);
        }
        if ($PROP['ANALOG_CODE'] == $element['PROPERTY_ANALOG_CODE_VALUE']) {
            unset($PROP['ANALOG_CODE']);
        }
        if ($PROP['RECOMMEND_CODE'] == $element['PROPERTY_RECOMMEND_CODE_VALUE']) {
            unset($PROP['RECOMMEND_CODE']);
        }
        if ($PROP['MEASURE'] == $element['PROPERTY_MEASURE_VALUE']) {
            unset($PROP['MEASURE']);
        }
        if ($PROP['RATE'] == $element['PROPERTY_RATE_VALUE']) {
            unset($PROP['RATE']);
        }
        $res = CIBlockElement::GetProperty(
                $this->IBLOCK_ID,
                $element['ID'],
                array("sort" => "asc"),
                array("CODE" => "MORE_PHOTO")
        );
        while ($ob = $res->GetNext())
        {
            $VALUES[] = $ob['VALUE'];
        }
        $morePhoto = array();
        if(isset($PROP['MORE_PHOTO'])) {
            $morePhoto = $PROP['MORE_PHOTO'];
        }
        if(!empty($VALUES[0])) {
            foreach ($VALUES as $photo) {
                $CFile = new CFile;
                $rsFile = $CFile->GetByID($photo);
                $arFile = $rsFile->Fetch();
                foreach ($PROP['MORE_PHOTO'] as $key => $pict) {
                    if ($pict['name'] == $arFile['ORIGINAL_NAME']
                            && $pict['type'] == $arFile['CONTENT_TYPE']
                            && $pict['size'] == $arFile['FILE_SIZE']
                    ) {
                        unset($morePhoto[$key]);
                    }
                }
            }
        }
        //pre($PROP);
        if(count($morePhoto) < 1  && isset($PROP['MORE_PHOTO'])) {
            unset($PROP['MORE_PHOTO']);
        }
        if(count($PROP)<>0) {
            CIBlockElement::SetPropertyValuesEx(
                    intval($element['ID']),
                    intVal($this->IBLOCK_ID),
                    $PROP
            );
        }
        //pre($goods['PRICE']);
        //pre($goods['SPECIALPRICE']);
        $CPrice = new CPrice;
        if($goods['PRICE'] > $goods['SPECIALPRICE'] && $goods['SPECIALPRICE']!=0 && $goods['PRICE']!=0){
            //pre($goods['PRICE'].' <-----> '.$goods['SPECIALPRICE']);
            $PRICE_TYPE_ID = 1;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['SPECIALPRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }

            $PRICE_TYPE_ID = 2;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['PRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }
            $arFields = array("ID" => $element['ID'],'QUANTITY' => '10000000');
            CCatalogProduct::Add($arFields);
            return $element['ID'];
        } else {
            //pre($goods['PRICE'].' >-----< '.$goods['SPECIALPRICE']);
            $PRICE_TYPE_ID = 1;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['PRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }

            $PRICE_TYPE_ID = 2;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['SPECIALPRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr['ID'], $arFields);
            }else{
                CPrice::Add($arFields);
            }
            $arFields = array("ID" => $element['ID'],'QUANTITY' => '10000000');
            CCatalogProduct::Add($arFields);
            //return $element['ID'];
        }
        if($goods['PRICE'] == 0.00 && $goods['SPECIALPRICE'] == 0.00 ){
            //pre($goods['PRICE'].' ====== '.$goods['SPECIALPRICE']);
            $PRICE_TYPE_ID = 1;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['PRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }

            $PRICE_TYPE_ID = 2;
            $arFields = Array(
                "PRODUCT_ID" => $element['ID'],
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                "PRICE" => $goods['SPECIALPRICE'],
                "CURRENCY" => "UAH",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );
            $res = $CPrice->GetList(
                array(),
                array(
                    "PRODUCT_ID" => $element['ID'],
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
                )
            );
            if($arr = $res->Fetch()) {
                CPrice::Update($arr["ID"], $arFields);
            }else{
                CPrice::Add($arFields);
            }
            $arFields = array("ID" => $arr['ID'],'QUANTITY' => '0');
            CCatalogProduct::Add($arFields);
            return $element['ID'];
        }
        //pre($element['ID']);
        return $element['ID'];
    }
    private function addChangePriceInLOG($goods=null, $BITRIX_ID = null, $notice = 'Ok') {
        if($goods===null ||  $BITRIX_ID===null) return false;
        $sql  = " INSERT INTO `tvr_log` ";
        $sql .= " (`ID`, `EXTKEY`, `SPECIALPRICE`, `PRICE`, `NOTICE`)";
        $sql .= " VALUES ";
        $sql .= " ('', '$goods[EXTKEY]', '$goods[SPECIALPRICE]', '$goods[PRICE]', '$notice') ";
        $result = $this->DB->freeQuery($sql);
        return $result;
    }
    private function addChangeGoodsInLOG($goods=null, $BITRIX_ID = null, $notice = 'Ok') {
        if($goods===null ||  $BITRIX_ID===null) return false;
        $goods['PREVIEWTEXT'] = mysqli_real_escape_string ($goods['PREVIEWTEXT']);
        $goods['DETAILTEXT'] = mysqli_real_escape_string ($goods['DETAILTEXT']);
        $goods['BRAND'] = mysqli_real_escape_string ($goods['BRAND']);
        $goods['NAME'] = mysqli_real_escape_string ($goods['NAME']);
        $goods['ARTNUMBER'] = mysqli_real_escape_string ($goods['ARTNUMBER']);
        $goods['MANUFACTURER'] = mysqli_real_escape_string ($goods['MANUFACTURER']);
        $goods['DESCRIPTION'] = mysqli_real_escape_string ($goods['DESCRIPTION']);
        //$goods[PREVIEWTEXT] = mysqli_real_escape_string ($goods[PREVIEWTEXT]);

        $sql  = " INSERT INTO `tvr_log` ";
        $sql .= " (`ID`, `BITRIXID`, `ACTIVE`, `NAME`, `CODE`, `EXTKEY`, `PARENTKEY`, `ARTNUMBER`, ";
        $sql .= " `BRAND`, `NEWPRODUCT`, `SALELEADER`, `SPECIALOFFER`, `DELIVERY`,  `PREPAID`,  `MANUFACTURER`, `COUNTRY`, `MAINMEDICINE`, ";
        $sql .= " `FARMFORM`, `DOSAGE`, `QUNTITY`, `DESCRIPTION`, `QUALIFICATION`, `TYPE`, `ANALOGCODE`, ";
        $sql .= " `RECOMMENDCODE`, `PREVIEWTEXT`, `DETAILTEXT`, `MEASURE`, `SPECIALPRICE`, `PRICE`, `RATE`, ";
        $sql .= " `DETAILPICTURE`, `MOREPICTURE`, `SIGN`, `NOTICE`) ";
        $sql .= " VALUES ";
        $sql .= " ('', '$BITRIX_ID','$goods[ACTIVE]','$goods[NAME]','$goods[CODE]', ";
        $sql .= " '$goods[EXTKEY]','$goods[PARENTKEY]','$goods[ARTNUMBER]','$goods[BRAND]', ";
        $sql .= " '$goods[NEWPRODUCT]','$goods[SALELEADER]','$goods[SPECIALOFFER]','$goods[DELIVERY]','$goods[PREPAID]','$goods[MANUFACTURER]', ";
        $sql .= " '$goods[COUNTRY]','$goods[MAINMEDICINE]','$goods[FARMFORM]','$goods[DOSAGE]', ";
        $sql .= " '$goods[QUNTITY]','$goods[DESCRIPTION]','$goods[QUALIFICATION]','$goods[TYPE]', ";
        $sql .= " '$goods[ANALOGCODE]','$goods[RECOMMENDCODE]','$goods[PREVIEWTEXT]','$goods[DETAILTEXT]', ";
        $sql .= " '$goods[MEASURE]','$goods[SPECIALPRICE]','$goods[PRICE]','$goods[RATE]', ";
        $sql .= " '$goods[DETAILPICTURE]','$goods[MOREPICTURE]','$goods[SIGN]', '$notice') ";
        //pre($sql);
        $result = $this->DB->freeQueryOrd($sql);
        return $result;
    }
    private function getCountRowInTable(){
        $sql = "SELECT COUNT(ID) FROM `tvr_change`";
        $count = $this->DB->freeQuery($sql);
        return $count['0']['COUNT(ID)'];
    }
    private function getCountRowInTablePrice()
    {
        $sql = "SELECT COUNT(ID) FROM `tvr_change_price`";
        $count = $this->DB->freeQuery($sql);
        return $count['0']['COUNT(ID)'];
    }
    private function getGoodsChangeFromBD($limit = 50){
        if($limit <= 0 || $limit > 1000 || is_string($limit)) $limit = 50;
        // выбираем только активные позиции (не удаленные)
        $sql = "SELECT * FROM `tvr_change` limit 0, $limit ";
        $this->goodsChengeInBD = $this->DB->freeQuery($sql);
        return $this->goodsChengeInBD;
    }
    private function getPriceChangeFromBD($limit = 50){
        if($limit <= 0 || $limit > 1000 || is_string($limit)) $limit = 50;
        // выбираем только активные позиции (не удаленные)
        $sql = "SELECT * FROM `tvr_change_price` limit 0, $limit ";
        $this->goodsChengeInBD = $this->DB->freeQuery($sql);
        return $this->goodsChengeInBD;
    }
    public function getRecipientGoods($EXT_KEY=null, $property = null){
        if($EXT_KEY===null) return false;
        $arFilter = array(
            'IBLOCK_ID' => $this->IBLOCK_ID,
            '=EXTERNAL_ID' => $EXT_KEY,
        );
        //pre($arFilter);
        if($property === null) {
            $arSelect = array(
                'IBLOCK_ID', 'ID','DETAIL_PICTURE','NAME','CODE','PROPERTY_ARTNUMBER',
                'PROPERTY_BRAND','PROPERTY_NEWPRODUCT','PROPERTY_SALELEADER',
                'PROPERTY_SPECIALOFFER','PROPERTY_DELIVERY', 'PROPERTY_PREPAID',
                'PROPERTY_MANUFACTURER','PROPERTY_COUNTRY','PROPERTY_MAIN_MEDICINE',
                'PROPERTY_FARM_FORM','PROPERTY_DOSAGE','PROPERTY_QUNTITY',
                'PROPERTY_DESCRIPTION','PROPERTY_QUALIFICATION','PROPERTY_TYPE',
                'PROPERTY_ANALOG_CODE','PROPERTY_RECOMMEND_CODE','PROPERTY_MEASURE',
                'PROPERTY_SPECIAL_PRICE','PROPERTY_PRICE','PROPERTY_RATE',
                'PROPERTY_MORE_PHOTO','PROPERTY_RECOMMEND','PROPERTY_ANALOG',
            );
        } else {
            $arSelect = array(
                    'IBLOCK_ID', 'ID'
            );
        }
        $CIBlockElement = new CIBlockElement;
        $rsElement = $CIBlockElement->GetList(array('ID' => 'ASC'), $arFilter, false, false, $arSelect);
        while ($arElement = $rsElement->GetNext()) {
            return  $arElement;
        }
        return 'noRecipientGoods';
    }
    private function getFileJPG($CODE=null) {
        $directory = $_SERVER["DOCUMENT_ROOT"].'/upload/a_obmen/JPG/';
        if ($CODE === null or empty($CODE)) {
            $img = CFile::MakeFileArray($directory.'net_foto.jpg');
            return $img;
        }
        if($this->dirArray === null) {
            $this->dirArray = scandir($directory);
        }
        foreach ($this->dirArray as $file) {
            $name = explode('.', $file);
            if($CODE == $name[0]) {
                $img = CFile::MakeFileArray($directory.$file);
                return $img;
            }
        }
        return 'FAIL';
    }
    private function getMorePictureFile($str=null) {
        if($str===null) return false;
        $arResult = array();
        $arFiles = explode(',', $str);
        foreach ($arFiles as $file) {
            $img = $this->getFileJPG($file);
            if($img != 'FAIL') {
                $arResult[] = $img;
            }
        }
        return $arResult;
    }
    private function getRecipientFolderForGoods($key=null) {
        if($key===null) return false;
        $key = strval($key);
        if(true === is_int($key) && true === is_numeric($key) ) {
            return $key;
        } else {
            $sec = $this->getRecipientFolder($key);
            if($sec != 'noRecipientFolder'){
                return $sec['ID'];
            } else {
                return 'noRecipientFolder';
            }
        }
    }
    private function delChangeGoodsInBD ($goods=null) {
        if($goods===null) return false;
        $sql  = " DELETE FROM `tvr_change` WHERE `ID`= '$goods[ID]' ";
        $result = $this->DB->freeQuery($sql);
        return $result;
    }
    private function delChangePricesInBD ($goods=null) {
        if($goods===null) return false;
        $sql  = " DELETE FROM `tvr_change_price` WHERE `ID`= '$goods[ID]' ";
        $result = $this->DB->freeQuery($sql);
        return $result;
    }
    private function getYes($str){
        if($str === 'Y'){
            return 'Y';
        } else {
            return 'N';
        }
    }

    public function getBalancesFromTransitDb() {
        $sql = "SELECT * FROM `supplier_balances`";
        $balances = $this->DB->freeQuery($sql);
        return $balances;
    }

    public function insertBalancesToDb($product_id, $last_availability) {
        $element = $this->getRecipientGoods($product_id)["ID"];
        $date = explode("-", $last_availability);
        CIBlockElement::SetPropertyValuesEx($element, false, [
            "LAST_AVAILABILITY" => $date[2] . "." . $date[1] . "." . $date[0],
            "SUPPLIER_LAST_UPD" => date( 'Y-m-d H:i:s' )
        ]);
    }

    public function delBalancesFromTransitDb () {
        $sql  = "DELETE FROM `supplier_balances`";
        $this->DB->freeQuery($sql);
    }

    public function truncateLeftoversProducts() {
        $leftovers = Tools::getLeftOversExcludeCity();
        $arSelect = Array("ID", "XML_ID");
        $arFilter = Array("IBLOCK_ID" => 2, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, [
                "PHARM_LEFTOVERS" => $leftovers[$arFields["XML_ID"]],
                "LAST_AVAILABILITY" => '',
                "SUPPLIER_LAST_UPD" => date( 'Y-m-d H:i:s' )
            ]);
        }
    }

    public function changeProductSort() {
        global $DB;
        $arSelect = Array();
        $arFilter = Array("IBLOCK_ID" => 2, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            $rsPrices = CPrice::GetList(array(), array('PRODUCT_ID' => $arFields['ID']))->Fetch();

            $buy_status = Tools::isVisibleButtonForOrder($arProps["PHARM_LEFTOVERS"]["VALUE"], $arProps["LAST_AVAILABILITY"]["VALUE"], $rsPrices["PRICE"]);

            if (in_array($buy_status, [2, 3])) {
                $sort = 888888;
            } else if (in_array($buy_status, [4])) {
                $sort = 999999;
            } else if ($buy_status == 1){
                if ($arProps["SPECIALOFFER"]["VALUE"] == 'Y') {
                    $sort = 1;
                } else {
                    $sort = 500;
                }
            }

            $strSql = "UPDATE `b_iblock_element` SET `SORT` = '" . $sort . "' WHERE `ID` = '" . $arFields['ID'] . "'";
            $DB->Query($strSql, false, $err_mess.__LINE__);
        }
    }
}
