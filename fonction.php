<?php

require 'autoloader.php'; 
Autoloader::register(); 


function chiffrer($alphabet, $msg, $key, $chiffrement, $reverse = 0){
	$alpha			= new Alphabet($alphabet);
	$code			= new Code($alpha, $msg);
	if ($chiffrement == "Cesar")
		$chiffrement	= new Chiffrement_cesar($code, $chiffrement, $key, $reverse);
	if ($chiffrement == "Substitution")
		$chiffrement	= new Chiffrement_substitution($code, $chiffrement, $key, $reverse);
	if ($chiffrement == "Vigenere")
		$chiffrement	= new Chiffrement_vigenere($code, $chiffrement, $key, $reverse);
	if ($chiffrement == "Enigma")
		$chiffrement	= new Chiffrement_enigma($code, $chiffrement, $key, $reverse);
	return $chiffrement;
}

$msg = "Le texte chiffré s'obtient en remplaçant chaque lettre du texte clair original par une lettre à distance fixe, toujours du même côté, dans l'ordre de l'alphabet. Pour les dernières lettres dans le cas d'un décalage à droite, on reprend au début. Par exemple avec un décalage de 3 vers la droite, A est remplacé par D, B devient E, et ainsi jusqu'à W qui devient Z, puis X devient A etc. Il s'agit d'une permutation circulaire de l'alphabet. La longueur du décalage, 3 dans l'exemple évoqué, constitue la clé du chiffrement qu'il suffit de transmettre au destinataire s'il sait déjà qu'il s'agit d'un chiffrement de César pour que celui-ci puisse déchiffrer le message. Dans le cas de l'alphabet latin, le chiffre de César n'a que 26 clés possibles";
$alphabet = array(
			'alphabet'	=> "",				// Par defaut "abcdefghijklmnopqrstuvwxyz"
			'maj'		=> 0,				// Génère les majuscule de l'alphabet
			'num'		=> 0,				// Génère les chiffres de la base 10
			'special'	=> 0,				// Génère les caractères spéciaux
			'accent'	=> 0,				// Code les accents en UTF-8
			'space'		=> 1,				// Chiffre les espaces
			'binaire'	=> 0,				// Remplace l'alphabet par la base 2
			'hexa'		=> 0,				// Remplace l'alphabet par la base 16
			'ascii'		=> 0,				// Utilise les codes ascii
			'debug'		=> 0				// Debug la table générée
		);

$chiffrer = chiffrer($alphabet, $msg, "", "");



//$key = " muqcjgslwerhyfnpovtbdkziaxAZERTYUIOPQSDFGHJKLMWXCVBN0987654321";
$chiffrer = chiffrer($alphabet, $msg, "", "Enigma", 0);
echo $chiffrer->get_msg();
//$chiffrer->reverse();
//echo "\n";
//echo "\n";
//echo $chiffrer->get_msg();
echo "\n";
?>
