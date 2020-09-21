<?
class obmenFiles extends obmen {
	private $host = '192.168.0.5'; // Имя хоста
	private $user = 'dev'; // Пользователь
	private $pass = 'devved'; // Пароль
	private $dir = '';
	private $connect = null;
	private $link = null;
	private $rout = null;
	private $remoutRout = null;

	public function __construct () {
		parent::__construct();
		$this->connect = ftp_connect($this->host) or die("Не удалось установить соединение с $this->host");
		if (@!ftp_login($this->connect, $this->user, $this->pass))
		{
			echo 'Проверьте параметры подключения к удаленному хосту!';
			$this->__destruct();
		}
	}
	public function __destruct() {
		ftp_quit($this->connect);
		parent::__destruct();
	}
	
	public function synchronize() {
		//spre($this->connect);
		$list = $this->getFileFromFTP();
	}
	public function getFileFromFTP() {
		$files = ftp_nlist($this->connect, '');//$this->dir   
		unset($files['0']);unset($files['1']);
		$newFiles=array();
		foreach($files as $key=>$nameFile) {
			pre($key);
			echo '['.$key.'] => '.$nameFile.'<br/>';
			$fname = iconv('windows-1251', 'utf-8', $nameFile);
			pre($fname);
			$newFiles[] = $fname;
			$way_str = $_SERVER['DOCUMENT_ROOT'].'/upload/a_obmen/JPG/';
			//$fp = fopen("$way_str.$fname", 'w');
			//pre($fp);
			$name = $way_str.$fname;
			pre($name);
			$ret = ftp_nb_get($this->connect, $name, $nameFile, FTP_BINARY);
			while ($ret == FTP_MOREDATA) {
				$ret = ftp_nb_continue($this->connect);
			}
			if ($ret != FTP_FINISHED) {
				echo "При скачивании файла произошла ошибка...";
				exit(1);
			}
			if ($ret == FTP_FINISHED) {
				echo "Ok!!!";
			}
		}
		pre($newFiles);
	}
	
	/*private function create file($name) {
		
	}*/
	/*private function sravnenie_remot_local($file_name_local, $file_name_remote) {
	$rename_pravilo = "/[a-zA-Z0-9]+\.[a-zA-Z0-9]+/";
	if ($handle_local1 = fopen($file_name_local, "r"))
		echo "файл $file_name_local открыт";
	if ($file_local_mas = fread($handle_local1, filesize($file_name_local)))
		echo "<br> прочитал $file_name_local<br>";

	//получаем массив содержимого файлов
	$fopen_files_local = file($file_name_local);
	$fopen_files_remote = file($file_name_remote);

	//цикл обработки локальной базы расширения .baz
	foreach ($fopen_files_local as $v_loc) {
		echo " --локальный ".$v_loc."<br>";
		//вложенный цикл обработки удалённой базы
		foreach ($fopen_files_remote as $v_rem) {
			echo "Сравниваем файлы<br>"."--удаленный ".$v_rem."<br>";
			$ravno=strcmp($v_loc, $v_rem);
			if ($ravno!==0) {
				echo "<br>$v_loc=НЕ РАВНО=$v_rem<br>";
				echo "<br>Если вижу значит условие прошло<br>";
				//Обратное преобразование имени с размером в обычное имя
				preg_match($rename_pravilo, $v_loc, $v_loc_rename);
				foreach ($v_loc_rename as $v_loc_final_name) {
					//загрузка на ftp
					ftp_nod_upload($v_loc_final_name);
					echo "Файл $v_loc_final_name загружен ---- Условие завершено<br>";
				}
			} else {
				echo "Обратное условие началось<br>Файл $v_loc_final_name <strong>ПРОПУЩЕН</strong><br>";
			} 
		}
	}
	echo "<br>Загрузка завершена<br>";
	ftp_close($antona_connect);
	if ($handle_remote11 = fopen($file_name_remote, "r"))
		echo "<br> Файл $file_name_remote окткрыт ";
	if (fread($handle_remote11, filesize($file_name_remote)))
		echo "<br> прочитал $file_name_remote"; }
	}*/
}
