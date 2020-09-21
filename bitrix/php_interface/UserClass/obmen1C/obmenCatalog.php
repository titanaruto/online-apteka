<?
class obmenCatalog extends obmen {
	private $DB;

	public function doObmenCatalogFromBD(){
		$changes = $this->getFolderChangeFromBD();
		$byLevel = $this->complitePrepareFolderChange($changes);
		$catalog = array();
		unset($changes);
		if(!empty($byLevel['level1'])) {
			$catalog['level1']=$this->implementationFirstLevel($byLevel['level1']);
		}
		if(!empty($byLevel['level2'])) {
			$catalog['level2']=$this->implementationOtherLevel($byLevel['level2']);
		}
		if(!empty($byLevel['level3'])) {
			$catalog['level3']=$this->implementationOtherLevel($byLevel['level3']);
		}
		if(!empty($byLevel['UNKNOWN'])) {
			$catalog['UNKNOWN']=$this->implementationOtherLevel($byLevel['UNKNOWN']);
		}
		unset($byLevel);
		return $catalog;
	}
	public function __construct () {
		parent::__construct();
		$this->DB = new workWithDB;
	}
	public function __destruct() {
		$this->DB->__destruct();
		parent::__destruct();
	}
	private function complitePrepareFolderChange($folders = null) {
		if($folders === null or $folders === 'Ok') {return false;}
		$res = array();
		$level2 = array();
		$res['level1']=array();
		$res['level2']=array();
		$res['level3']=array();
		$res['UNKNOWN']=array();
		$del = array();
		//Отсеиваем 1 уровень, т.е. где $folder['PARENTKEY'] = ""
		// остальные у нас: 2й или 3й или ошибка.
		foreach ($folders as $key => $folder) {
			$folder['NAME'] = strtolower($folder['NAME']);
			$folder['EXTKEY'] = trim($folder['EXTKEY'],' ');
			$folder['PARENTKEY'] = trim($folder['PARENTKEY'],' ');
			if(empty($folder['PARENTKEY'])) {
				$folder['PARENTKEY'] = 'false';
				$folder['LEVEL'] = '1';
				$res['level1'][$folder['EXTKEY']] = $folder;
				$del[]= $folder['EXTKEY'];
				if(isset($res['UNKNOWN'][$folder['EXTKEY']])) {
					unset($res['UNKNOWN'][$folder['EXTKEY']]);
				}
			} else {
				$level2[$folder['EXTKEY']] = $folder;
				$res['UNKNOWN'][$folder['EXTKEY']] = $folder;
			}
		}
		foreach ($del as $key){
			unset($folders[$key]);
		}
		//теперь отсеиваем третий уровень от второго
		foreach ($folders as $key => $folder) {
			$folder['NAME'] = strtolower($folder['NAME']);
			$folder['EXTKEY'] = trim($folder['EXTKEY'],' ');
			$folder['PARENTKEY'] = trim($folder['PARENTKEY'],' ');
			if(isset($level2[$folder['PARENTKEY']])) {
				$folder['LEVEL']='3';
				$del[]= $folder['EXTKEY'];
				$level2[$folder['PARENTKEY']]['LEVEL'] = '2';
				if(!isset($res['level2'][$folder['PARENTKEY']])) {
					$res['level2'][$folder['PARENTKEY']]=$level2[$folder['PARENTKEY']];
				}
				$res['level3'][$folder['EXTKEY']]=$folder;
				unset($res['UNKNOWN'][$folder['EXTKEY']]);
				unset($res['UNKNOWN'][$folder['PARENTKEY']]);
				$del[]= $folder['EXTKEY'];
				$del[]= $folder['PARENTKEY'];
			}
		}
		foreach ($del as $key){
			unset($folders[$key]);
		}
		$del = array();
		return $res;
	}
	private function getFolderChangeFromBD() {
		$sql = "SELECT * FROM `ctlg_change` ";
		$folderChengeInBD = $this->DB->freeQuery($sql);
		return $folderChengeInBD;
	}
	private function implementationOtherLevel($folderInBD) {
		$changeFolderBitrix = array();
		if($folderInBD===null) return false;
		foreach ($folderInBD as $folder) {
			$id = -1;
			$NAME = trim($folder['NAME'],' ');
			$NAME = strtolower($NAME);
			$folder['CODE'] = Cutil::translit($NAME,"ru", $this->ruleTranslit);
			$parent = $this->getRecipientFolder($folder['PARENTKEY']);
			$element = $this->getRecipientFolder($folder['EXTKEY']); //метод родительского класса
			//pre($parent);
			//pre($element);
			
			if($parent=='noRecipientFolder'){
				$ok = $this->addChangeLOG(
					$folder,
					'', 
					$notice = 'Превышен уровень вложенности или родительский каталог не найден'
				);
				if($ok === 'Ok'){
					$this->delChangeFolderInBD($folder);
				}
			} else {
				if($element=='noRecipientFolder'){
					//pre($folder);
					$id = $this->addFolder($folder);
				} else {
					//pre($folder);
					$id = $this->UpdateFolder($element['ID'], $folder);
				}
				if($id > 0) {
					$ok = $this->addChangeLOG($folder, $id);
					if($ok == 'Ok') {
						$this->delChangeFolderInBD($folder);
					}
				} else {
					// Совпадения по символьному коду
					$changeFolderBitrix['ERROR'][] = $folder;
					$ok = $this->addChangeLOG($folder, '', 'Ошибка записи или обновления каталога implementationOtherLevel');
					if($ok == 'Ok') {
						$this->delChangeFolderInBD($folder);
					}
				}
				$changeFolderBitrix['CHANGED'][] = $id;
				usleep(20000);
			}
		}
		return $changeFolderBitrix;
	}
	private function implementationFirstLevel($folderInBD) {
		$changeFolderBitrix = array();
		if($folderInBD===null) return false;
		foreach ($folderInBD as $folder) {
			$id = -1;
			$NAME = trim($folder['NAME'],' ');
			$NAME = strtolower($NAME);
			$folder['CODE'] = Cutil::translit($NAME,"ru", $this->ruleTranslit);
			$element = $this->getRecipientFolder($folder['EXTKEY']);
			if($element=='noRecipientFolder'){
				$id = $this->addFolder($folder);
			} else {
				$id = $this->UpdateFolder($element['ID'], $folder);
			};
			if($id > 0) {
				$ok = $this->addChangeLOG($folder, $id);
				if($ok == 'Ok') {
					$this->delChangeFolderInBD($folder);
				}
			} else {
				// Совпадения по символьному коду
				$changeFolderBitrix['ERROR'][] = $folder;
				$ok = $this->addChangeLOG($folder, '', 'Ошибка записи или обновления каталога implementationFirstLevel');
				if($ok == 'Ok') {
					$this->delChangeFolderInBD($folder);
				}
			}
			$changeFolderBitrix['CHANGED'][] = $id;
			usleep(20000);
		}
		return $changeFolderBitrix;
	}
	private function getFolderFromBD() {
		// выбираем только активные позиции (не удаленные)
		$sql = "SELECT * FROM `ctlg_catalog` WHERE `ACTIVE`='Y' ";
		$this->folderInBD = $this->DB->freeQuery($sql);
		return $this->folderInBD;
	}
	/*public function implementationCancge() { //переработать
		foreach ($this->folderInBD as $folder)
		{
			if(isset($this->knownFolder[$folder['EXTKEY']]))
			{
				$status = '';
				$element = $this->knownFolder[$folder['EXTKEY']];
				
				$status = $this->checkChange($folder, $element);
				
				if($status == 'UPDATE')
				{
					$this->UpdateFolder($element['ID'], $folder);
				}
			} else {
				$this->addFolder($folder);
			}
		}
	}*/
	public function addFirstLevel() {
		$data = array();
		foreach ($this->file['level1'] as $folder)
		{
			$this->insertFolderInBD($folder);
		}
		return $data;
	}
	private function addFolder($folder) {
		if($folder === null) return false;
		usleep(10000);
		$bs = new CIBlockSection;
		$IBLOCK_SECTION_ID = '';
		$arPICTURE = $this->getFileJPG($folder['EXTKEY']);
		if($arPICTURE=='FAIL') {
			$arPICTURE = $this->getFileJPG();
		}
		$arFilter = array(
				'IBLOCK_ID' => intval($this->IBLOCK_ID), 
				'GLOBAL_ACTIVE' => 'Y', 
				'ACTIVE' => 'Y', 
				'EXTERNAL_ID' => $folder['PARENTKEY'] 
		);
		$rsSections = $bs->GetList(array('ID' => 'ASC'), $arFilter);
		while ($arSection = $rsSections->Fetch()){
			$IBLOCK_SECTION_ID = $arSection['ID'];
		}
		$arFields = Array(
				'ACTIVE' => $folder['ACTIVE'],
				'IBLOCK_ID' => intval($this->IBLOCK_ID),
				'NAME' => $this->my_mb_ucfirst($folder['NAME']),
				'CODE' => $folder['CODE'],
				'IBLOCK_SECTION_ID' => $IBLOCK_SECTION_ID,
				'EXTERNAL_ID'=> $folder['EXTKEY'],
				'DETAIL_PICTURE' => $arPICTURE,
				'PICTURE' => $arPICTURE,
				//'~DETAIL_PICTURE' => $arPICTURE,
				//'~PICTURE' => $arPICTURE
		);
		$ID = $bs->Add($arFields);
		if($ID>0) return $ID;
		return false;
	}
	private function UpdateFolder($ID=null,$folder=null){
		if($folder === null || $ID===null) return false;
		usleep(10000);
		$bs = new CIBlockSection;
		$IBLOCK_SECTION_ID = '';
		$arPICTURE = $this->getFileJPG($folder['EXTKEY']);
		if($arPICTURE=='FAIL') {
			$arPICTURE = $this->getFileJPG();
		}
		//берем родительскую секцию
		$arFilter = array(
				'IBLOCK_ID' => intval($this->IBLOCK_ID), 
				'=EXTERNAL_ID' => $folder['PARENTKEY']
		);
		$rsSections = $bs->GetList(array('ID' => 'ASC'), $arFilter);
		while ($arSection = $rsSections->Fetch()){
			$IBLOCK_SECTION_ID = $arSection['ID'];
		}
		// берем изменяемую секцию
		$arFilter = array(
				'IBLOCK_ID' => intval($this->IBLOCK_ID), 
				'=EXTERNAL_ID' => $folder['EXTKEY']
		);
		$rsSections = $bs->GetList(array('ID' => 'ASC'), $arFilter);
		$arSection = $rsSections->Fetch();
		$arFields = Array(
			'ACTIVE' => $folder['ACTIVE'],
			'IBLOCK_ID' => intval($this->IBLOCK_ID),
//			'NAME' => $this->my_mb_ucfirst($folder['NAME']),
			'CODE' => $folder['CODE'],
			'IBLOCK_SECTION_ID' => $IBLOCK_SECTION_ID,
			'EXTERNAL_ID'=> $folder['EXTKEY'],
			'DETAIL_PICTURE' => $arPICTURE,
			//'~DETAIL_PICTURE' => $arPICTURE,
			'PICTURE' => $prImage,
			//'~PICTURE' => $prImage
		);
		if(!empty($curentSection['PICTURE'])) {
			$CFile = new CFile;
			$rsFile = $CFile->GetByID($curentSection['PICTURE']);
			$arFile = $rsFile->Fetch();
		}
		if(!empty($curentSection['PICTURE'])) {
			if ($arFields['PICTURE']['name'] == $arFile['ORIGINAL_NAME']
				&& $arFields['PICTURE']['type'] == $arFile['CONTENT_TYPE']
				&& $arFields['PICTURE']['size'] == $arFile['FILE_SIZE']
			) {
				unset($arFields['PICTURE']);
				unset($arFields['~PICTURE']);
				unset($arFields['DETAIL_PICTURE']);
				unset($arFields['~DETAIL_PICTURE']);
			}
		}
		if($ID > 0){
			$res = $bs->Update($ID, $arFields);
			if($res) {
				return $ID;
			}else{
				return $res;
			}
		}else{
			$ID = $bs->Add($arFields);
			if($ID>0) return $ID;
		}
	}
	private function addChangeLOG($folder=null, $BITRIXID = null, $notice = 'Ok') {
		if($folder===null ||  $BITRIXID===null) return false;
		$sql  = "INSERT INTO `ctlg_log` ";
		$sql .=	"(`ID`, `EXTKEY`, `BITRIXID`, `PARENTKEY`, `ACTIVE`, `NAME`, `CODE`,";
		$sql .=	" `DESCRIPTION`, `IMAGE`, `LEVEL`, `NOTICE`)";
		$sql .=	"VALUES ( '', '$folder[EXTKEY]', '$BITRIXID', '$folder[PARENTKEY]', '$folder[ACTIVE]', '$folder[NAME]', ";
		$sql .=	" '$folder[CODE]','$folder[DESCRIPTION]', '$folder[IMAGE]', $folder[LEVEL], '$notice') ";
		$result = $this->DB->freeQuery($sql);
		if(empty($result)) {
			return 'ok';
		}
		return $result;
	}
	private function delChangeFolderInBD($folder=null) {
		if($folder===null) return false;
		$sql = " DELETE FROM `ctlg_change` WHERE `ID`= '$folder[ID]' ";
		$result = $this->DB->freeQuery($sql);
		return $result;
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
}
