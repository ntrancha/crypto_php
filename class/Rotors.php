<?php

class	Rotors
{

	private $alpha;					//	Table de caractères utilisée
	private $rotors;				//	Ensemble des rotors
	private $initial;				//	Position initial des rotors


	// Crée un pack de rotors
	// alpha							Table de caractère utilisée
	// type								"refault"
	//									"random"
	//									une chaine de tout les rotors
	// num								nombre de rotor random a générer
	public function __construct($alpha, $type = "default", $num = 20){
		$this->alpha = $alpha;
		if (is_array($type))
			$this->rotors = $type;
		elseif ($type == "default")
			$this->rotors = $this->default_rotors();
		elseif ($type == "random")
			$this->rotors = $this->random_rotors($num);
		else
			$this->create_rotors($type);
		$this->initial();
	}


			/************************/
			/*        GETTER        */
			/************************/

	// Retourne le nombre de rotors
	public function get_number(){
		if (is_array($this->rotors))
			return count($this->rotors);
		return -1;
	}

	// Retourne tout les rotors ou un en particulier
	public function get_rotors($num = ""){
		if ($num == "")
			return $this->rotors;
		else if (is_numeric($num) AND $num < $this->get_number())
			return $this->rotors[$num];
		return NULL;
	}

	// Retourne la position d'un rotor
	public function get_rotor_status($num = 0){
		if (is_numeric($num) AND $num < $this->get_number())
			return $this->rotors[$num]->get_rotor_satus();
		return NULL;
	}

	// Retourne l'etat initial d'un ou des rotor(s)
	public function get_initial($num = ""){
		if ($num == "")
			return $this->initial;
		else if (is_numeric($num) AND $num < $this->get_number())
			return substr($this->initial, $num, 1);
		return NULL;
	}


			/************************/
			/*       ROTATION       */
			/************************/

	// Effectue un rotation du rotor vers la droite
	public function rotation($rotor_num = 0){
		$this->rotors[$rotor_num]->rotation_rotor();
		$status = $this->rotors[$rotor_num]->get_rotor_status();
		$initial = substr($this->initial, $rotor_num, 1);
		if ($status === $initial)
			$this->rotation($rotor_num + 1);
	}

	// Effectue un rotation du rotor vers la gauche
	public function rotation_r($rotor_num = 0){
		$this->rotors[$rotor_num]->rotation_rotor_r();
		$status = $this->rotors[$rotor_num]->get_rotor_status();
		$initial = substr($this->initial, $rotor_num, 1);
		if ($status === $initial)
			$this->rotation($rotor_num + 1);
	}


			/************************/
			/*        SWITCH        */
			/************************/

	public function switch_elem($num, $elem_A, $elem_B){
		if (isset($this->rotor[$num]))
			$this->rotor[$num]->switch_elem($elem_A, $elem_B);
	}

	public function switch_first_elem($num){
		if (isset($this->rotor[$num]))
			$this->rotor[$num]->switch_first_elem();
	}

	public function switch_rotor($num_A, $num_B){
		if (isset($this->rotors[$num_A]) AND isset($this->rotors[$num_B])){
			$rotor_A = $this->create_rotor($this->rotors[$num_A]->get_rotor());
			$rotor_B = $this->create_rotor($this->rotors[$num_B]->get_rotor());
			for ($index = 0; $index < count($this->rotors); $index++)
				if ($index === $num_A)
					$this->rotors[$index] = $rotor_B;
				elseif ($index === $num_B)
					$this->rotors[$index] = $rotor_A;
		}
	}


			/************************/
			/*      SUPRESSION      */
			/************************/

	public function del_rotor($num = 0){
		$rotor_del = $this->get_rotors($num);
		unset($this->rotors[$num]);
		$this->rotors = array_values($this->rotors);
		return $rotor_del;
	}


			/************************/
			/*        CONFIG        */
			/************************/

	// Initialise tout les rotors
	public function config_rotors($chaine){
		for ($index = 0; $index < strlen($chaine); $index++)
			if (isset($this->rotors[$index]))
				$this->rotors[$index]->config_rotor(substr($chaine, $index, 1));
		return $this->initial();
	}

	// Enregistre la configuration initiale des rotors
	public function initial(){
		$this->initial = "";
		foreach ($this->rotors as $rotor)
			$this->initial .= $rotor->get_rotor_status();
		return $this->initial;
	}


			/************************/
			/*        DEBUG        */
			/************************/

	// Affiche les rotors
	public function debug(){
		foreach ($this->rotors as $rotor){
			echo '"'.$rotor->get_rotor().'",';
			echo "\n";
		}
	}


			/************************/
			/*       CREATION       */
			/************************/

	// Crée un rotor
	private function create_rotor($chaine = ""){
		$rotor = new Rotor($this->alpha, $chaine);
		return $rotor;
	}

	// Calcul la taille des rotors contenu dans une chaine
	private function calc_len($chaine){
		return (substr_count($chaine, substr($chaine, 0 , 1)) / strlen($chaine));
	}

	// Crée les rotors depuis une chaine
	private function create_rotors($chaine){
		$this->rotors = array();
		$size = $this->calc_len($chaine);
		for ($index = 0; $index < strlen($chaine); $index += $size)
			$this->rotors[] = $this->create_rotor(substr($chaine, $index, $size));
		return $this->get_rotors();
	}

	// Crée des rotors "random"
	private function random_rotors($number = 20){
		$this->rotors = array();
		for ($i = 0; $i < $number; $i++)
			$this->rotors[] = $this->create_rotor();
		return $this->get_rotors();
	}

	// Retour un pack de rotors
	private function default_rotors(){
		$this->rotors = array();
		$rotors = array(
				"qretuwagzovlbi cdmnhxfjyspk",
				"mdgfyvtzjrki lhnbwsqpexcuao",
				"gc pnakybsqwfrezlvtjhxoiudm",
				"elaqkgnfm zrhpvbucodjwyixts",
				"xelkmhdivcngptaufzysjqwrb o",
				"buzdqxcnsvrmjfhoy iwlpkaegt",
				"sund xhqkpebjgywatlfrmcoviz",
				"qjrvoufyslngkphx ewiczmtdba",
				"racxzbgfvdpwyenslijm okuqht",
				"zejchflpov swnamrytxubdqikg",
				"bekxpgqin zdycmhtajfsrvlowu",
				"qsug okmnlzivjfawcdyeprhxbt",
				"wvcjrethfdksnupzm laxgbyoqi",
				"qgmavzrhwekticldsnjfyupbo x",
				"k jtibyuvpraoshnmezxlcfqgdw",
				"mvgepzjflhwy doabciutrqsxkn",
				"dlcpzmabtyu gnqfwkivrosjexh",
				"xmogbdywpqeilazfkh tncrsuvj",
				"yzdugrkcaipsq fevhljnxotmwb",
				"jirgvqxbucwnzdlhkepomafs ty",
				"ghzbsmiqdwkruc pfolaxejtynv",
				"jmpbrsguzwoc anihvlekqfyxtd",
				"oscplqnuhdjkfryi evtwbzgxam",
				"fcnahzpsgdbervqktlujwi oxym",
				"zjycrxgtakoflunedhmqsbvwpi ",
				"wqpfnuegilkv jmorzaxybhsctd",
				"qeldfvgackiunzjsomtyhbxp rw",
				"eqxjt crbofdwvpgylanuishzmk",
				"tjfqxzbkcurhgonmsypvila dew",
				"zqbmlki gxyjwtndhupvfsrcaoe",
				"exslgnck wqjthpfzibroaudvmy",
				"fzcrnhwexusgtql vpkjaioymbd",
				"lgvmwfkrjuts andizecxpyqohb",
				"odxpetjrylzvnkbgiamqhu cwsf",
				"tvsljd nxqirgefwmkcphazouyb",
				"kwgtixs dofyuaehpjcznlbqmrv",
				"eoswcbmylhvjfu nxadqtkigrzp",
				"sgnpklodveaw cbuqfmjzirxtyh",
				"zeospjtcimq vbudxhwyngfralk",
				"rtwhifxgypdqs vznmeujlkacob",
				"cfwelhunp girtyadkjovbqxmsz",
				"wkgqsmhxdl ntfebpjvurazycio",
				"dimcsobzrhjxflg nyaweupvktq",
				"wemdkhv porcbzgtslxiyjafuqn",
				"xqf eibksnclhpjvguztwoymdra",
				"ogxptl edqkmiayvcwujfrhnzsb",
				"rmpq gnwcudbyxakijhoesvltzf",
				"hkqrycjpblxeuwsoidnmvzt afg",
				"ftivxnmezoalkcqjhpgwy dsurb",
				"hkbvnpoalsdir zqtcxfuyjgmew",
				"pruxlbe jtkqysogvmizfwdchna",
				"wrf qtshjnopbvzgulcikydxaem",
				"rfqumepalszcntj gdwhbkivyxo",
				"ynfpguldbwjix vshqctkeazrom",
				"uxfsatdyopnv rqkhiczjlmwbeg",
				"nrsbhlkgzfd tpoqjmuacevyxiw",
				"yzcmoigljrdptveswfnuhqkbxa ",
				"dcxiutje hkyqvgbfaolzwpnrms",
				"ewsufgn orpxtmjkcabzhlqyvid",
				"uidwvejcharqpzlfots mbkgynx",
				"gjvwhpaflkoqed szumncbityxr",
				"audbhzvnxmweg rsyqfiljkptoc",
				"mcwaverflnsqup kioxbhygtzjd",
				"dvnlhex gtisjbrouwkmazfypqc",
				"fvtlzk gxropucsbjyaqeidnhwm",
				"spyz ogjbriwnuhtlvcfkmaqdxe",
				"rhtkasyvqpgfmdl bzoujcinxwe",
				"zxsfvekr nilowqhtyumajpdgcb",
				"udtkvfwr agzcqsnbeymxoilphj",
				"qwbkfxrhlpio nevcsmjgdtzuay",
				"jtrwmphfbdlsnuazgkvyociqex ",
				"esqljpzrivo mdhgcfnytxuabwk",
				"ejhr yqpmdxwksvitolbunzcfag",
				"pmnayqfed iolswzjgctxbrhuvk",
				"e sltxhzmcranvpgjybkowquifd",
				"qp fagmheybijscdxzkutwornlv",
				"gmlqfoaexd wcpztyrkihsuvbnj",
				"bejstwd xpgylaqvickmfonzhru",
				"jgskcvpuzairqytefdbmnwhxo l",
				"qnhs xmipjaybwdolkevfgzturc",
				"xolwsivefazdmukbhypt qrgnjc",
				"ktemqyjfxghcoiwdvnzua lpsbr",
				"hpeidujtkqbwr ysonzaxlgfvcm",
				"gucvdetymzbij sropnhwxlkafq",
				"zsxqoeyhmvcuf kjigbtrnwpald",
				"qveokdshctybrujiz mxnagfwpl",
				"bg iyqulrfwpscxevdmktjohzan",
				"bejwodhgsztpuiyvqlrkx macfn",
				"gtmrzfxspvb couihnkwelqyajd",
				"hfvkgiu nxoalzjbmprwcyestdq",
				"mojtrbky fhxsiguzdqpacwvnle",
				"reafkdlg bycovswtnxzqmhijup",
				"hgvscakd pimnwtroyqfelzjxub",
				"nxwfslzohcmruvjeigbtkyaq dp",
				"oyijwdfvgztehm cuqnbsakrlpx",
				"qzfhrkbyvinumleatsxcwjp ogd",
				"ykzhpstulfm verawjgxqdcnbio",
				"nifrsxgyjobkhvtuwcelzpm daq",
				"f cmqvrsukzwpdjtayhgloenbxi",
				"vgtmnfpleizuyhdbcqkwoajsrx ",
				"zv dhfnmcxtjaieobrylgpsqwuk",
				"nrewhjldqcfvtioykguxmab pzs",
				" asdfyzxhbrwpcvnilekmjuotqg",
				"bgdyaexrjq pzostwhnfkcluvmi",
				"snmzfydwg tjqxblroephivucka",
				"zujqlpfidackmh bxeorgtsvwny",
				"ghylsmbivdrqefjcxnzowutk pa",
				"rk glhpvnqowybcdfteizsjaumx",
				"bgkftyxhdiawqlverjmocnuzs p",
				"azintjmydu gkporvecbwlhsxfq",
				"matobgcxr pfkqlvhndsijweuyz",
				"fwnsvabjyrxiledq gtohmukpzc",
				"gnwjmrfvtzxoku qibcsdyhepal",
				"gawriuecjlfhvqxpzyobsmkt nd",
				"zhyj kgwquolntxavbdsmircfpe",
				"stazjledumnfhvoiybxkwrgqp c",
				"nygsourbwvmfpcihzadjxltqke ",
				"ozmpadlyh bnjitxgsqrcekwfvu",
				"okrgbvfqc ymwjeznhultpidxsa"
					);
		foreach ($rotors as $rotor)
			$this->rotors[] = $this->create_rotor($rotor);
		return $this->get_rotors();
	}

}

?>
