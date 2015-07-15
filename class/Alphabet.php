<?php

class Alphabet
{

	/* TABLE DE CARACTERES */
	private $ponctuation		= "',.;:-_\"&+()[]{}/=?!~$*%@<>|#";
	private $alphabet			= "abcdefghijklmnopqrstuvwxyz";
	private $hexadecimal		= "0123456789ABCDEF";
	private $numerique			= "0123456789";
	private $binaire			= "01";
	/* Table de caracteres */


	/* OPTIONS */
	private $option_maj			= 0;
	private $option_num			= 0;
	private $option_sub			= 0;
	private $option_hexa		= 0;
	private $option_alpha		= "";
	private $option_debug		= 0;
	private $option_space		= 0;
	private $option_ascii		= 0;
	private $option_accent		= 0;
	private $option_binaire		= 0;
	private $option_special		= 1;
	/* Options */


	/* TABLE GENEREE */
	private $arr				= array();
	private $string				= "";
	private $size				= 0;
	private $sub				= 0;
	/* Table générée */

	public function __construct(array $kwargs){
		$maj					= 0;
		$num					= 0;
		$sub					= 0;
		$hexa					= 0;
		$debug					= 0;
		$space					= 0;
		$ascii					= 0;
		$accent					= 0;
		$special				= 1;
		$binaire				= 0;
		$alphabet				= "";
		extract( $kwargs, EXTR_IF_EXISTS );
		$this->option_maj		= $maj;
		$this->option_num		= $num;
		$this->option_sub		= $sub;
		$this->option_hexa		= $hexa;
		$this->option_alpha		= $alphabet;
		$this->option_ascii		= $ascii;
		$this->option_debug		= $debug;
		$this->option_space		= $space;
		$this->option_accent	= $accent;
		$this->option_special	= $special;
		$this->option_binaire	= $binaire;
		if ($ascii == 1){
			$this->option_maj		= 1;
			$this->option_num		= 1;
			$this->option_special	= 1;
		}
		$this->create();
	}

	public function __get($property){
		return $this->$property;
	}

	private function create(){
		$ret = "";
		if ($this->option_binaire === 1)
			$ret = $this->binaire;
		elseif ($this->option_hexa === 1)
			$ret = $this->hexadecimal;
		else{
			if ($this->option_alpha != "")
				$this->alphabet = $this->option_alpha;
			$ret .= $this->alphabet;
			if ($this->option_maj === 1)
				$ret .= strtoupper($this->alphabet);
			if ($this->option_num === 1)
				$ret .= $this->numerique;
			if ($this->option_special == 1)
				$ret .= $this->ponctuation;
			elseif ($this->option_accent === 1)
				$ret .= "&;";
			if ($this->option_space === 1)
				$ret .= " ";
		}
		if ($this->option_ascii === 1)
			if ($this->option_space === 1)
				for ($i = 32; $i < 127; $i++)
					$this->string .= chr($i);
			else
				for ($i = 33; $i < 127; $i++)
					$this->string .= chr($i);
		else
			$this->string = $ret;
		$this->inarray();
		if ($this->option_debug === 1)
			$this->debug();
	}

	private function debug(){
		if ($this->option_ascii === 1)
			$size = 127 - 33;
		else
			$size = $this->size;
		$star = "";
		for ($i = 0; $i <= $size; $i++)
			$star .= "*";
		echo "$star\n";
		echo "Table ";
		if ($this->option_ascii === 1)
			echo "ASCII ";
		else
			if ($this->option_sub === 1)
				echo "de substitution générée ";
			else
				echo "de taille $size générée ";
		if ($this->option_accent === 1)
			echo "(avec encodage UTF-8) ";
		echo ":\n";
		echo $this->string;
		echo "\n$star\n";
	}

	public function clean_str($str){
		if ($this->option_maj === 0)
			$str = strtolower($str);
		if ($this->option_num === 0)
			$str = $this->clear_special($str, "numerique");
		if ($this->option_special === 0)
			$str = $this->clear_special($str, "ponctuation");
		if ($this->option_accent === 1)
			$str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
		else
			$str = $this->clear_str_accent($str);
		$index = 0;
		$max = strlen($str);
		while ($index < $max){
			$lettre = substr($str, ($index++), 1);
			if (substr_count($lettre, $this->string) != 0)
				$str = str_replace($lettre, ' ', $str);
		}
		while (preg_match("#  #", $str) != 0)
			$str = str_replace("  ", ' ', $str);
		return $str;
	}

	public function declean_str($str){
		while (preg_match("#  #", $str) != 0)
			$str = str_replace("  ", ' ', $str);
		return html_entity_decode($str);
	}

	private function clear_special($str, $table){
		$index = 0;
		$max = strlen($this->$table);
		while ($index < $max){
			$lettre = substr($this->$table, ($index++), 1);
			$str = str_replace($lettre, ' ', $str);
		}
		return $str;
	}

	private function clear_str_accent($phrase){
		return str_replace(
			array(
				'à', 'â', 'ä', 'á', 'ã', 'å',
				'î', 'ï', 'ì', 'í', 
				'ô', 'ö', 'ò', 'ó', 'õ', 'ø', 
				'ù', 'û', 'ü', 'ú', 
				'é', 'è', 'ê', 'ë', 
				'ç', 'ÿ', 'ñ', 
				),
			array(
				'a', 'a', 'a', 'a', 'a', 'a', 
				'i', 'i', 'i', 'i', 
				'o', 'o', 'o', 'o', 'o', 'o', 
				'u', 'u', 'u', 'u', 
				'e', 'e', 'e', 'e', 
				'c', 'y', 'n', 
				),
			$phrase
		);
	}

	private function inarray(){
		$index = 0;
		$this->size = strlen($this->string);
		while ($index < $this->size){
			$lettre = substr($this->string, ($index++), 1);
			array_push($this->arr, $lettre);
		}
	}
}
