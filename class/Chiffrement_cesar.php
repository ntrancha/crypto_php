<?php


class Chiffrement_cesar extends Chiffrement
{

	public function __construct($code, $chiffrement, $key, $reverse){
		$this->reset_data();
		$this->initial($code, $chiffrement, $key, $reverse);
		$this->maj($this->chiffrement());
	}

	public function reverse(){
		if ($this->reverse == 0)
			$this->reverse = 1;
		else
			$this->reverse = 0;
		$this->maj($this->chiffrement());
		return $this->get_msg();
	}

	protected function chiffrement(){
		if ($this->keys == "")
			$this->keys = rand(1, strlen($this->alpha->__get("string")) - 1);
		$ret = array();
		foreach ($this->msg_code as $code)
			array_push($ret, $this->rot($code, $this->keys));
		return $ret;
	}
}

?>
