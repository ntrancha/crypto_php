<?php

class Chiffrement_enigma extends Chiffrement
{
	private $set_of_rotors;
	private $used_rotors;
	private $config_rotors;
	private $initial;
	private $key_sub;


	public function __construct($code, $chiffrement, $key, $reverse){
		$this->reset_data();
		$this->initial($code, $chiffrement, $key, $reverse);
		$this->make_config($key);
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


			/************************/
			/*       CONFIG         */
			/************************/

	public function make_config($key){
		if (!is_array($key))
			$key = array("", "", "aaaaaaaaaaaaaaa", "");
		$this->keys = $key;

		// SET of ROTORS
		if (!is_object($key[0]))
			if ($key[0] === "")
				// Si c'est une chaine vide, utilise les rotors par defaut
				$this->set_of_rotors	= $this->default_rotors();
			elseif (is_numeric($key[0]))
				// Si c'est un nombre, génère N rotors aléatoire
				$this->set_of_rotors	= $this->random_rotors($key[0]);
			elseif (is_string($key[0]))
				// Si c'est une chaine, génère les rotors contenus dans la chaine
				$this->set_of_rotors	= $this->random_rotors($key[0]);
		elseif (is_object($key[0]))
			// Si c'est un pack de rotor l'ajouter
			$this->set_of_rotors	= $key[0];

		// USED ROTORS
		if (!is_array($key[1]))
			if ($key[1] === "")
				// Si la chaine est vide, selectione 3 rotors au hasard
				$this->used_rotors		= $this->random_rotor(8);
			elseif (is_numeric($key[1]))
				// Sinon selectionner N rotors au hasard
				$this->used_rotors		= $this->random_rotor($key[1]);
		elseif (is_array($key[1]))
			// Si c'est un array, sélectione les rotors indiqués dans le array
			$this->used_rotors		= $this->select_rotor($key[1]);

		// CONFIG ROTORS
		if (is_string($key[2]) AND $key[2] != "")
			// Initialise les rotors en fonction d'une chaine
			$this->initial = $this->config_rotors($key[2]);
		else
			// Retourne la valeur initial des rotors
			$this->initial = $this->config_rotors();

		// KEY of SUBSTITUTION
		$this->key_sub			= $key[3];
	}


			/************************/
			/*         ALGO         */
			/************************/

	public function chiffrement(){
		print_r($this->used_rotors->get_rotors());
		
		echo $this->initial;
	}


			/************************/
			/*    CONFIG ROTOR      */
			/************************/

	private function config_rotors($chaine = ""){
		$this->config_rotors = $chaine;
		if ($chaine != "")
			$chaine = $this->used_rotors->config_rotors($chaine);
		else
			$chaine = $this->used_rotors->initial();
		return $chaine;
	}


			/************************/
			/*       SELECT         */
			/************************/

	private function select_rotor($selection){
		$ret = array();
		foreach ($selection as $number){
			$size = count($this->set_of_rotors->get_rotors());
			while ($number >= $size)
				$number -= $size;
			$ret[] = $this->set_of_rotors->get_rotors($number);
			$this->set_of_rotors->del_rotor($number);
			$rotors = $this->create_rotors($ret);
		}
		return $rotors;
	}

	private function random_rotor($number = 3){
		if ($number >= count($this->set_of_rotors->get_rotors()))
			return NULL;
		$ret = array();
		for ($count = 0; $count < $number; $count++){
			$num = rand(0, (count($this->set_of_rotors->get_rotors()) - 1));
			$ret[] = $this->set_of_rotors->get_rotors($num);
			$this->set_of_rotors->del_rotor($number);
			$rotors = $this->create_rotors($ret);
		}
		return $rotors;
	}


			/************************/
			/*      CREATION        */
			/************************/

	// Crée un pack de rotors depuis une chaine ou un array les contenant tous
	private function create_rotors($chaine){
		$rotors = new Rotors($this->alpha, $chaine);
		if ($this->alpha->__get("option_debug") == 1)
			$rotors->debug();
		return $rotors;
	}

	// Retourne le pack de rotors par defaut
	private function default_rotors(){
		$rotors = new Rotors($this->alpha, "default");
		if ($this->alpha->__get("option_debug") == 1)
			$rotors->debug();
		return $rotors;
	}

	// Crée un pack de rotors "random"
	private function random_rotors($number = 5){
		$rotors = new Rotors($this->alpha, "random", $number);
		if ($this->alpha->__get("option_debug") == 1)
			$rotors->debug();
		return $rotors;
	}

}

?>
