<?
class obmenPrices{
	
	
	public function __construct () {
		parent::__construct();
		$this->DB = $this->DB();
	}
	public function __destruct() {
		$this->DB = '';
		parent::__destruct();
	}
}
