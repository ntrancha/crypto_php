<?php

class Rotor
{

	private $rotor;		//	Rotor

	// Crée le rotor
	// alpha				table de caratère utilisée
	// chaine				le rotor
	public function __construct($alpha, $chaine = ""){
		$this->rotor = $this->modifier_rotor($alpha, $chaine);
	}


			/*******************/
			/*      GETTER     */
			/*******************/

	// Retourne le rotor sous forme de chaine
	public function get_rotor(){
		return $this->rotor;
	}

	// Retourne la position du rotor
	public function get_rotor_status(){
		return substr($this->rotor, 0, 1);
	}


			/*******************/
			/*      CONFIG     */
			/*******************/

	// Modifie le rotor
	// alpha				table de caratère utilisée
	// chaine				le rotor
	public function modifier_rotor($alpha, $chaine = ""){
		if ($chaine == "")
			$chaine = str_shuffle($alpha->__get("string"));
		$this->rotor = $chaine;
		return $this->get_rotor();
	}

	// Modifie la position du rotor
	// carac					caractère sur lequel positionner le rotor
	public function config_rotor($carac){
		while (substr($this->rotor, 0, 1) != $carac)
			$this->rotation_rotor();
	}


			/*******************/
			/*      SWITCH     */
			/*******************/

	// Remplace une case du rotor par un autre
	public function switch_elem($elem_A, $elem_B){
		$new = "";
		for ($index = 0; $index < strlen($this->rotor); $index++){
			if ($elem_A === substr($this->rotor, $index, 1))
				$new .= $elem_B;
			elseif ($elem_B === substr($this->rotor, $index, 1))
				$new .= $elem_A;
			else
				$new .= substr($this->rotor, $index, 1);
		}
		$this->rotor = $new;
	}

	// Remplace les deux premieres cases du rotor
	public function switch_first_elem(){
		$elem_A = substr($this-rotor, 0, 1);
		$elem_B = substr($this-rotor, 1, 1);
		$this->switch_elem($elem_A, $elem_B);
	}


			/*******************/
			/*    ROTATION     */
			/*******************/

	// Fait tourner le rotor vers la droite
	public function rotation_rotor(){
		$rotor = $this->get_rotor();
		$size = strlen($rotor);
		$this->rotor = substr($rotor, 1, ($size - 1)).substr($rotor, 0, 1);
	}

	// Fait tourner le rotor vers la gauche
	public function rotation_rotor_r(){
		$rotor = $this->get_rotor();
		$size = strlen($rotor);
		$this->rotor = substr($rotor, ($size - 1), 1).substr($rotor, 0, ($size - 1));
	}

}

?>
