<?
class obmen {
	public $IBLOCK_ID = 2;
	public $ftp;
	public $file;
	public $ressource;
	public $dirArray = null;
	public $ressourceObmen;
	public $ruleTranslit = array("replace_space"=>"-","replace_other"=>"-");
	public $knownFolder;
	public $folderInBD;
	public $folderChengeInBD;
	public $goodsChengeInBD;
	
	public function __construct() {
		$this->bitrixModuleInclude();
	}
	public function __destruct() {
		
	}
	private function bitrixModuleInclude() {
		if(!CModule::IncludeModule("iblock")){
			echo 'Модуль Инфоблоков не подключен!';
			$this->__destruct();
		}
		if(!Cmodule::IncludeModule('catalog')){
			echo 'Модуль торгового каталога не подключен!';
			$this->__destruct();
		}
		if(!Cmodule::IncludeModule('sale')){
			echo 'Модуль магазина не подключен!';
			$this->__destruct();
		}
	}
	public function format_to_save_string ($text) {
		$text = addslashes($text);
		$text = htmlspecialchars($text);
		$text = addslashes($text);
		$text = str_replace("'/\|",'',$text);
		$text = str_replace("\r\n",' ',$text);
		$text = str_replace("\n",' ',$text);
		return $text;
	}
	public function getRecipientFolder($EXTKEY=null) {
		if($EXTKEY===null) return false;
		$EXTKEY = trim($EXTKEY,' ');
		$arFilter = array(
				'IBLOCK_ID' => intval($this->IBLOCK_ID),
				'=EXTERNAL_ID' => $EXTKEY
		);
		$CIBlockSection = new CIBlockSection;
		$rsSections = $CIBlockSection->GetList(array('ID' => 'ASC'), $arFilter);
		while ($arSection = $rsSections->Fetch())
		{
			return  $arSection;
		}
		return 'noRecipientFolder';
	}
	public function my_mb_ucfirst($str) {
		$fc = mb_strtoupper(mb_substr($str, 0, 1));
		return $fc.mb_substr($str, 1);
	}
	public function getRecipientGoods($EXT_KEY=null, $property = null){
		if($EXT_KEY===null) return false;
		$arFilter = array(
			'IBLOCK_ID' => 2,
			'=EXTERNAL_ID' => $EXT_KEY,
		);
		if($property === null) {
			$arSelect = array(
					'IBLOCK_ID', 'ID', '*','PROPERTY_ARTNUMBER','PROPERTY_BRAND', 
					'PROPERTY_NEWPRODUCT','PROPERTY_SALELEADER','PROPERTY_SPECIALOFFER','PROPERTY_DELIVERY', 'PROPERTY_PREPAID',
					'PROPERTY_MANUFACTURER','PROPERTY_COUNTRY','PROPERTY_MAIN_MEDICINE',
					'PROPERTY_FARM_FORM','PROPERTY_DOSAGE','PROPERTY_QUNTITY','PROPERTY_DESCRIPTION',
					'PROPERTY_QUALIFICATION','PROPERTY_TYPE','PROPERTY_ANALOG_CODE',
					'PROPERTY_RECOMMEND_CODE','PROPERTY_MEASURE','PROPERTY_SPECIAL_PRICE',
					'PROPERTY_PRICE','PROPERTY_RATE','PROPERTY_MORE_PHOTO','PROPERTY_RECOMMEND',
					'PROPERTY_ANALOG',
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
		return false;
	}
}
