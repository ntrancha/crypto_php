<?php

class Chiffrement_substitution extends Chiffrement
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
			$this->keys = str_shuffle($this->alpha->__get("string"));
		if ($this->substitution == "")
			$this->substitution = new Alphabet(array(
						'alphabet'	=> $this->keys,
						'debug'		=> $this->alpha->__get("option_debug"),
						'sub'		=> 1,
						'special'	=> 0
						));
		$substitution = $this->substitution;
		$size_s = strlen($substitution->__get("string"));
		$size_a = strlen($this->alpha->__get("string"));
		if ($this->reverse == 1){
			$alphabet_s	= $this->alpha->__get("string");
			$alphabet	= $substitution->__get("string");
		}else{
			$alphabet	= $this->alpha->__get("string");
			$alphabet_s	= $substitution->__get("string");
		}
		$message = $this->msg;
		$ret = "";
		$max = strlen($message);
		for ($index = 0; $index < $max; $index++){
			$lettre = substr($message, $index, 1);
			$sub = "";
			for ($test = 0; $test < $this->alpha->__get("size"); $test++)
				if ($lettre === substr($alphabet, $test, 1))
					$sub = substr($alphabet_s, $test, 1);
			if ($sub == "")
				$ret .= $lettre;
			else
				$ret .= $sub;
		}
		return $ret;
	}

}

?>
