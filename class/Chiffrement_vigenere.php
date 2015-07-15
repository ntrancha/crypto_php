<?php

class Chiffrement_vigenere extends Chiffrement
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
			$ret = array();
			$msg = "";
			if ($this->keys == "")
				$this->keys = str_shuffle("phrase de chiffrement par defaut");
			$keys = str_replace(" ", "", $this->keys);
			$size_key = strlen($keys);
			$index = 0;
			foreach ($this->msg_code as $code){
				$msg .= substr($keys, $index++, 1);
				if ($index >= $size_key)
					$index = 0;
			}
			$this->masque = new Code($this->alpha, $msg);
			$index = 0;
			foreach ($this->msg_code as $code){
				if ($index >= $size_key)
					$index = 0;
				$key = $this->masque->get_code()[$index++];
				array_push($ret, $this->rot($code, $key));
			}
			return $ret;
		}

}

?>
