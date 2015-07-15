<?php

class Code
{

	private $alpha;

	private $msg		= "";
	private $code		= array();
	private $code_b		= array();
	private $code_h		= array();

	public function __construct($alphabet, $message){
		$this->alpha	= $alphabet;
		$this->modifier_msg($message);
	}

	public function modifier_msg($value){
		if (is_array($value))
			$ret = $this->decode_phrase($value);
		else
			$ret = $this->code_phrase($value);
		$this->dec_to_bin();
		$this->dec_to_hexa();
		return $ret;
	}

	public function modifier_hexa($value){
		if (!is_array($value))
			return $this->modifier_hexa_str($value);;
		$dec = array();
		foreach ($value as $elem)
			$dec[] = hexdec($elem);
		return $this->modifier_msg($dec);
	}

	private function modifier_hexa_str($str){
		$str = str_replace(" ", "", $str);
		$max = strlen($str);
		$hexa = array();
		for ($index = 0; $index < $max; $index += 2)
			$hexa[] = substr($str, $index, 2);
		return $this->modifier_hexa($hexa);
	}

	public function modifier_bin($value){
		if (!is_array($value))
			return $this->modifier_bin_str($value);;
		$dec = array();
		foreach ($value as $elem)
			$dec[] = bindec(intval($elem));
		return $this->modifier_msg($dec);
	}

	private function modifier_bin_str($str){
		$str = str_replace(" ", "", $str);
		$max = strlen($str);
		$bin = array();
		for ($index = 0; $index < $max; $index += 8)
			$bin[] = substr($str, $index, 8);
		print_r($bin);
		return $this->modifier_bin($bin);
	}

	public function get_msg(){
		return $this->alpha->declean_str(str_replace("%ks:", ";", $this->msg));
	}

	public function get_msg_bin(){
		$ret = "";
		foreach ($this->code_b as $code)
			$ret .= $code;
		return $ret;
	}

	public function get_msg_hexa(){
		$ret = "";
		foreach ($this->code_h as $code)
			$ret .= $code;
		return $ret;
	}

	public function get_code(){
		return $this->code;
	}

	public function get_code_hexa(){
		return $this->code_h;
	}

	public function get_code_bin(){
		return $this->code_b;
	}


	public function get_alpha(){
		return $this->alpha;
	}

	private function dec_to_bin(){
		$ret = array();
		foreach ($this->code as $clef)
			$ret[] = sprintf("%08b", $clef);
		$this->code_b = $ret;
		return $ret;
	}

	private function dec_to_hexa(){
		$ret = array();
		foreach ($this->code as $clef)
			$ret[] = sprintf("%02x", $clef);
		$this->code_h = $ret;
		return $ret;
	}

	private function code($lettre){
		if ($lettre == ' ' AND $this->alpha->option_space == 0)
			if ($this->alpha->option_ascii == 1)
				return 32;
			else
				return 0;
		if (substr_count($lettre, $this->alpha->string) != 0)
			return NULL;
		$index = 0;
		foreach ($this->alpha->arr as $elem)
			if ($index++ >= 0 AND $lettre == $elem)
				if ($this->alpha->option_ascii == 1)
					return ord($lettre);
				else
					return $index;
		return NULL;
	}

	private function decode($code){
		if ($code == 0 AND $this->alpha->option_space == 0)
			return ' ';
		if ($this->alpha->option_ascii == 1)
			return chr($code);
		if ($code > 0 AND $code > $this->alpha->size)
			return NULL;
		$index = 0;
		foreach ($this->alpha->arr as $elem)
			if ($index++ >= 0 AND $index == $code)
				return $elem;
		return NULL;
	}

	private function code_phrase($str){
		$ret = array();
		$str = $this->alpha->clean_str($str);
		$max = strlen($str);
		$index = 0;
		while ($index < $max){
			$lettre = substr($str, ($index++), 1);
			array_push($ret, $this->code($lettre));
		}
		$this->msg = $str;
		$this->code = $ret;
		return $ret;
	}

	private function decode_phrase($code){
		$ret = "";
		foreach ($code as $elem){
			$ret .= $this->decode($elem);
		}
		$this->msg = $ret;
		$this->code = $code;
		return $ret;
	}

	public function count_lettre($str){
		$ret = array();
		$str = $this->alpha->clean_str($str);
		array_push($ret, substr_count($str, ' '));
		foreach ($this->alpha->arr as $elem){
			$count = substr_count($str, $elem);
			array_push($ret, $count);
		}
		return $ret;
	}
}
?>
