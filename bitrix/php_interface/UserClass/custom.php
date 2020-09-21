<?
class custom{
	
	public function __construct() {
		$this->bitrixModuleInclude();
	}
	public function __destruct() {
		
	}
	private function bitrixModuleInclude(){
		if(!CModule::IncludeModule("iblock")){
			echo 'Модуль Инфоблоков не подключен!';
			$this->__destruct();
		}
		/*if(!Cmodule::IncludeModule('catalog')){
			echo 'Модуль торгового каталога не подключен!';
			$this->__destruct();
		}
		if(!Cmodule::IncludeModule('sale')){
			echo 'Модуль магазина не подключен!';
			$this->__destruct();
		}*/
	}
	private function format_to_save_string ($text){
		$text = addslashes($text);
		$text= htmlspecialchars($text);
		$text = addslashes($text);
		//$text = preg_replace("/[^А-Яа-яA-Za-z0-9]/i", "", $text);
		$text = str_replace("'/\|",'',$text);
		$text = str_replace("\r\n",' ',$text);
		$text = str_replace("\n",' ',$text);
		return $text;
	}
	/*$PROP = array(
				'CITY' => $arSection['NAME'],
				//'BRAND' => $goods['BRAND'],
			);
			if(count($PROP)<>0) {
				CIBlockElement::SetPropertyValuesEx(
						$arCity[ID],
						intVal(12), 
						$PROP
				);
			}*/
			/*$PROP = array(
				'NAME' => 'Аптека №000',
				//'BRAND' => $goods['BRAND'],
			);
			$CIBlockElement->Update($arCity['ID'], $PROP);*/
	private function sortByLetter ($text, array $temp_array){
		$f_simbol = strtolower(substr($arElement['PROPERTIES']['CITY']['VALUE'],0,1));
		switch ($f_simbol) {
			case 'а':
			//	echo 'а';
				$temp_array['а'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				
				break;
			case 'б':
			//	echo 'б';
				$temp_array['б'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'в':
			//	echo 'в';
				$temp_array['в'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'г':
			//	echo 'г';
				$temp_array['г'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'д':
			//	echo 'д';
				$temp_array['д'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'е':
				//echo 'е';
				$temp_array['е'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'ж':
			//	echo 'ж';
				$temp_array['ж'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'з':
			//	echo 'з';
				$temp_array['з'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'и':
			//	echo 'и';
				$temp_array['и'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'к':
			//	echo 'к';
				$temp_array['к'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'л':
			//	echo 'л';
				$temp_array['л'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'м':
			//	echo 'м';
				$temp_array['м'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'н':
			//	echo 'н';
				$temp_array['н'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'о':
			//    echo 'о';
				$temp_array['о'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'п':
			//  echo 'п';
				$temp_array['п'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'р':
			//    echo 'р';
				$temp_array['р'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'с':
			//  	echo 'с';
				$temp_array['с'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'т':
			// 	echo 'т';
				$temp_array['т'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'у':
			//	echo 'у';
				$temp_array['у'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'ф':
			//	echo 'ф';
				$temp_array['ф'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'х':
				//echo 'х';
				$temp_array['х'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'ц':
			//	echo 'ц';
				$temp_array['ц'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'ч':
			//	echo 'ч';
				$temp_array['ч'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'ш':
			//	echo 'ш';
				$temp_array['ш'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'щ':
			//	echo 'щ';
				$temp_array['щ'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'э':
				//echo 'э';
				$temp_array['э'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'ю':
				//  echo 'ю';
				$temp_array['ю'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;
			case 'я':
				// echo 'я';
				$temp_array['я'][$arElement['PROPERTIES']['CITY']['VALUE']][] = $arElement;
				break;	    
			default:
				//echo '0-100';
				$temp_array[][] = $arElement;
			break;
		}
		return $text;
	}
}
