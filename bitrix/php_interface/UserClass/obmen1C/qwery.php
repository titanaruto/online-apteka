<?class qwery {
	public $DB;
	public function __construct() {
		$this->DB = new workWithDB;
	}
	public function __destruct() {
		//$this->DB->__destruct();
	}
	public function frqr($sql=''){
		$result = $this->DB->freeQuery($sql);
		return $result[0]['VALUE'];
	}
}
