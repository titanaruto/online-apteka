<?
class ftpRequest {
	private $host = '192.168.0.5'; // Имя хоста
	private $user = 'dev'; // Пользователь
	private $pass = 'dev'; // Пароль
	private $connect = null;
	private $link = null;
	private $rout = null;
	private $remoutRout = null;

	public function setRemoutRout($str='')
	{
		$this->remoutRout = $str;
	}
	public function getRemoutRout()
	{
		return $this->remoutRout;
	}
	public function setRout($str='')
	{
		$this->rout = $_SERVER['DOCUMENT_ROOT'].$str;
	}
	public function getRout()
	{
		return $this->rout;
	}
	public function format_to_save_string ($text)
	{
		$text = addslashes($text);
		$text= htmlspecialchars ($text);
		$text = addslashes($text);
		$text = preg_replace("/[^А-Яа-яA-Za-z0-9]/i", "", $text);
		$text = str_replace("'/\|",'',$text);
		$text = str_replace("\r\n",' ',$text);
		$text = str_replace("\n",' ',$text);
		return $text;
	}
	public function freeQuery($local_file=null, $remote_file=null)
	{
		if($local_file===null || $remote_file===null) return false;
		$local_source = $this->rout.$local_file;
		$remote_source = $this->remoutRout.$remote_file;
		if(!ftp_get($this->connect, $local_source, $remote_source, FTP_BINARY))
		{
			echo "Не удалось завершить операцию записи файла!\n";
		}
		return $local_source;
	}
	public function getFile($name){
		$this->freeQuery($name, $name);
	}
	public function getFileObmen(){
		$this->setRout('/upload/a_obmen/');
		$this->freeQuery('obmen.csv', 'obmen.csv');
	}
	public function getFileCatalog(){
		$this->setRout('/upload/a_obmen/');
		$this->freeQuery('catalog.csv', 'catalog.csv');
	}
	public function getAllFiles()
	{
		$this->freeQuery('CC_33988.jpg', 'CC_33988.jpg');
		$dir = ftp_pwd($this->connect);
		pre($dir);
	
	/*	//if($local_file===null || $remote_file===null) return false;
		$local_source = $this->rout.$local_file;
		$remote_source = $this->remoutRout.$remote_file;
		if(!ftp_get($this->connect, $local_source, $remote_source, FTP_BINARY))
		{
			echo "Не удалось завершить операцию записи файла!\n";
		}
		return $local_source;*/
	}
	function __construct()
	{
		$this->connect = ftp_connect($this->host) or die("Не удалось установить соединение с $this->host");
		if (@!ftp_login($this->connect, $this->user, $this->pass))
		{
			echo 'Проверьте параметры подключения к удаленному хосту!';
			$this->__destruct();
		}

	}
	
	function __destruct() {
		ftp_quit($this->connect);
	}
}