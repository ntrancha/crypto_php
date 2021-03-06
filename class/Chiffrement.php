<?php

class Chiffrement
{
	protected $msg;					// Message
	protected $code;				// OBJET Code
	protected $keys;				// Clef de chiffrement
	protected $alpha;				// OBJET Alphabet 
	protected $masque;				// OBJET Code [Phrase de chiffrement]
	protected $reverse;				// Mode Reverse
	protected $original;			// ClearText
	protected $msg_code;			// Message en numérique
	protected $chiffrement;			// Type de chiffrement utilisé
	protected $substitution;		// Table de substitution


	public function __construct($code, $chiffrement, $key, $reverse){
		$this->reset_data();
		$this->initial($code, $chiffrement, $key, $reverse);
		$this->maj($this->chiffrement());
	}


			/************************/
			/*         ALGO         */
			/************************/

	public function chiffrement(){
			return $this->maj($this->get_msg());
	}

	public function reverse(){
		if ($this->reverse == 0)
			$this->reverse = 1;
		else
			$this->reverse = 0;
	}


			/************************/
			/*       CONFIG         */
			/************************/

	public function modifier_bin($value){
		$ret = $this->code->modifier_bin($value);
		$this->refresh();
		return $ret;
	}

	public function modifier_hexa($value){
		$ret = $this->code->modifier_hexa($value);
		$this->refresh();
		return $ret;
	}

	public function initial($code, $chiffrement, $key, $reverse){
		$this->keys				= $key;
		$this->code				= $code;
		$this->reverse			= $reverse;
		if ($this->original == "")
			$this->original			= $code->get_msg();
		$this->chiffrement		= $chiffrement;
		$this->substitution		= "";
		$this->refresh();
	}


			/************************/
			/*      OPERATION       */
			/************************/

	protected function _xor($bin, $key){
		$size = strlen($bin);
		$size2 = strlen($key);
		if ($size != $size2)
			return NULL;
		$ret = $bin;
		for ($bit = 0; $bit < $size; $bit++)
			$ret[$bit] = intval($bin[$bit])^intval($key[$bit]);
		return $ret;
	}

	protected function rot($code, $key){
		if ($this->alpha->option_ascii)
			$size = 127 - 32;
		else
			$size = $this->alpha->__get("size");
		while ($key >= $size)
			$key -= $size;
		if ($this->alpha->option_ascii == 1 AND $code > 0){
			if ($this->reverse == 1)
				$code -= $key;
			else
				$code += $key;
			if ($code > 126)
				$code -= $size;
			if ($code < 32)
				$code += $size;
		}else{
			if ($code > 0){
				if ($this->reverse == 1)
					$code -= $key;
				else
					$code += $key;
				if ($code > $size)
					$code -= $size;
				if ($code < 1)
					$code += $size;
			}
		}
		return $code;
	}


			/************************/
			/*        GETTER        */
			/************************/

	public function get_msg(){
		if ($this->alpha->__get("option_debug") == 1){
			echo "Chiffrement: ";
			echo $this->chiffrement;
			echo "\nClef: ";
			if ($this->chiffrement == "Vigenere" AND $this->substitution != "")
				echo $this->substitution->__get("string");
			else
				echo $this->keys;
			if ($this->reverse == 1)
				echo "\nMessage Déchiffré: \n";
			else
				echo "\nMessage Chiffré: \n";
			echo $this->code->get_msg();
			echo "\n";
		}
		return $this->code->get_msg();
	}

	public function get_msg_bin(){
		return $this->code->get_msg_bin();
	}

	public function get_msg_hexa(){
		return $this->code->get_msg_hexa();
	}

	public function get_key(){
		return $this->keys;
	}

	public function get_code(){
		return $this->code->get_code();
	}

	public function get_code_bin(){
		return $this->code->get_code_bin();
	}

	public function get_code_hexa(){
		return $this->code->get_code_hexa();
	}


			/************************/
			/*       SYSTEM         */
			/************************/

	protected function reset_data(){
		$this->msg				= NULL;
		$this->keys				= NULL;
		$this->code				= NULL;
		$this->alpha			= NULL;
		$this->masque			= NULL;
		$this->reverse			= NULL;
		$this->original			= NULL;
		$this->msg_code			= NULL;
		$this->chiffrement		= NULL;
		$this->substitution		= NULL;
	}

	protected function maj($ret){
		$ret = $this->code->modifier_msg($ret);
		$this->refresh();
		return $ret;
	}

	protected function refresh(){
		$this->alpha = $this->code->get_alpha();
		$this->msg = $this->code->get_msg();
		$this->msg_code = $this->code->get_code();
	}
}

?>
