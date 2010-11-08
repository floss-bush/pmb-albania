<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: crypt.class.php,v 1.6 2007-03-10 09:25:48 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Classe de cryptage/décryptage à partir de deux empruntes md5
//
//
// Instanciation : le constructeur prend comme paramètres deux string, empruntes md5
//
// Méthodes publiques : 
//
// string getCrypt(string str) : crypte la chaine str et renvoi la chaine codée correspondante
// string getDecrupt(string str) : décrypte la chaine str et renvoi la chaine décodée correspondante

//Définition de la classe pour include
if ( ! defined( 'CRYPT_CLASS' ) ) {
  define( 'CRYPT_CLASS', 1 );
}

class Crypt {
	
	//------------------------------------------------------------
	// Données
	//------------------------------------------------------------
	
	var $print1;					//Empruntes utilisées pour créer les éléments de codage/décodage
	var $print2;
	var $tPrint1 = array();			//Tableaux normalisés issus des empruntes
	var $tPrint2 = array();
	var $tColCorresp = array();		//Table de codage colonne
	var $tRowCorresp = array();		//Table de codage ligne
	var $tInvColCorresp = array();	//Table de décodage colonne

	//------------------------------------------------------------
	// Constructeur : Crypt($print1,$print2)
	// Création des tables de codages à partir des empruntes
	//------------------------------------------------------------
	function Crypt($print1, $print2) {
		$this -> print1 = $print1;
		$this -> print2 = $print2;

		//Calcul des tableaux normalisés à partir des empruntes
		$decal1 = 0;
		$decal2 = 0;
		for ($i = 0; $i < 32; $i ++) {
			$this -> tPrint1[$i] = $this -> hex2decNormalized($print1[$i]);
			$this -> tPrint2[$i] = $this -> hex2decNormalized($print2[$i]);
			$decal1 += $this -> tPrint1[$i];
			$decal2 += $this -> tPrint2[$i];
		}
		$decal1 = round(($decal1 / 32) * 254) + 1;
		$decal2 = round(($decal2 / 32) * 254) + 1;

		//Calcul des tables cryptage

		$this -> genCryptTables();

		//Calcul des tables de décodage

		$this -> genDecryptTables();
	}

	//------------------------------------------------------------
	// genTcar()
	// Génération de la table des caractères
	//------------------------------------------------------------
	function genTcar() {
		$tCar = array();
		for ($i = 0; $i < 256; $i ++) {
			$tCar[$i] = $i;
		}
		return $tCar;
	}

	//------------------------------------------------------------
	// decal(& array $tCar, int $pos, $nStayingCar)
	// Décalage à gauche de la table des caractères $tCar à partir de la position donnée par $pos sur les $nStayingcar premiers caractères
	//------------------------------------------------------------
	function decal(& $tCar, $pos, $nStayingCar) {
		for ($i = $pos +1; $i < $nStayingCar; $i ++) {
			$tCar[$i -1] = $tCar[$i];
		}
	}
	
	//------------------------------------------------------------
	// hex2decNormalized(char $value)
	// fonction de convetissage hex->decimal normalisé (entre 0 et 1)
	//------------------------------------------------------------
	function hex2decNormalized($value) {
		$tHex = array("0" => 0, "1" => 1, "2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "a" => 10, "b" => 11, "c" => 12, "d" => 13, "e" => 14, "f" => 15);
		return $tHex[$value] / 15;
	}
	
	//-----------------------------------------------------------
	// decalLine(& array $line, int $decal)
	// Permutation d'une ligne $line avec un offset de $decal
	//-----------------------------------------------------------
	function decalLine(& $line, $decal) {
		$lineOut = array();
		for ($i = 0; $i < 256; $i ++) {
			$p = $decal + $i;
			if ($p > 255)
				$p -= 256;
			$lineOut[$i] = $line[$p];
		}
		$line = $lineOut;
	}

	//-----------------------------------------------------------
	// genCryptTables()
	// Génération des tables de codage
	//-----------------------------------------------------------
	function genCryptTables() {
		//Table des colonnes

		$lCar = array();

		//Table des caractères
		$tCar = $this -> genTcar();

		//pour chaque ligne, 256 colonnes soit presque 8 blocs de 32
		for ($b = 0; $b < 8; $b ++) {
			//Première colonne du bloc
			$c = $b * 32;

			$n = 0;
			while (($n < 32) && ($c < 256)) {
				//Nombre de caractères restant dans la table des caractère
				$nStayingCar = 256 - $c;

				$nCar = round(($this -> tPrint1[$n] * ($nStayingCar -1)));
				$lCar[$c] = $tCar[$nCar];
				//Décalage des caractères
				$this -> decal($tCar, $nCar, $nStayingCar);
				$n ++;
				$c ++;
			}
		}

		//32 lignes
		for ($l = 0; $l < 32; $l ++) {
			$this -> tColCorresp[$l] = $lCar;
			//Décalage de la ligne
			$this -> decalLine($lCar, $decal1);
		}

		//Table des lignes

		$lRow = array();

		//pour chaque ligne, 256 colonnes en 8 blocs de 32
		for ($b = 0; $b < 8; $b ++) {
			$c = $b * 32;

			$n = 0;
			while (($n < 32) && ($c < 256)) {
				$lRow[$c] = round($this -> tPrint2[$n] * 31);
				$n ++;
				$c ++;
			}
		}

		//32 lignes
		for ($l = 0; $l < 32; $l ++) {
			$this -> tRowCorresp[$l] = $lRow;
			$this -> decalLine($lRow, $decal2);
		}
	}
	
	//------------------------------------------------------------
	// genDecryptTables()
	// Génération des tables de décodage
	//------------------------------------------------------------
	
	function genDecryptTables() {
		//Table des colonnes
		for ($l = 0; $l < 32; $l ++) {
			for ($c = 0; $c < 256; $c ++) {
				$this -> tInvColCorresp[$l][$this -> tColCorresp[$l][$c]] = $c;
			}
		}

		//Table des lignes inverse = table des lignes normale
	}

	//------------------------------------------------------------
	// getCrypt(string $str)
	// Fonction de cryptage d'une chaine $str
	//------------------------------------------------------------
	function getCrypt($str) {
		$strR = "";
		$line = 0;
		for ($i = 0; $i < strlen($str); $i ++) {
			$nCol = ord($str[$i]);
			$strR.= chr($this -> tColCorresp[$line][$nCol]);
			$line = $this -> tRowCorresp[$line][$nCol];
		}
		return $strR;
	}

	//------------------------------------------------------------
	// getDecrypt(string $str)
	// Fonction de décryptage d'une chaine $str
	//------------------------------------------------------------
	function getDecrypt($str) {
		$strR = "";
		$line = 0;
		for ($i = 0; $i < strlen($str); $i ++) {
			$nCol = ord($str[$i]);
			$strR.= chr($this -> tInvColCorresp[$line][$nCol]);
			$line = $this -> tRowCorresp[$line][$this -> tInvColCorresp[$line][$nCol]];
		}
		return $strR;
	}
}
?>