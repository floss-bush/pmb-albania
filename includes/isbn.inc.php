<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: isbn.inc.php,v 1.16 2008-09-04 08:02:43 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonctions pour gérer les ISBN

function isISBN($isbn) {
	$checksum=0;
	// s'il y a des lettres, ce n'est pas un ISBN
	if(preg_match('/[A-WY-Z]/i', $isbn))
		return FALSE;

	$isbn = preg_replace('/-|\.| /', '', $isbn);
	
	$key = $isbn[strlen($isbn) - 1];
	
	if (strlen($isbn)==10) {
		if($key == 'X')
			$key = 10;
		$isbn = substr($isbn, 0, strlen($isbn) - 1);
	
		// vérification de la clé
		for($i = 0; $i < strlen($isbn) ; $i++) {
			$checksum += (10 - $i) * $isbn[$i];
		}
		$checksum += $key;
		
		if (($checksum%11) == 0) return TRUE ;
			else return FALSE ;
	} else if (strlen($isbn)==13) {
		if ((substr($isbn,0,3)=="978")||(substr($isbn,0,3)=="979")) {
			//Vérification de la clé
			$p=1;
			for ($i=0; $i<13; $i++) {
				$checksum+=$p*$isbn[$i];
				$p=($p==1?3:1);
			}
			if (($checksum%10) == 0) return TRUE; else return FALSE ;
		} else return false;
	} else return false;
}

function isISBN10($isbn) {
	// return true si ISBN sur 10 caractères	
	if(preg_match('/[A-WY-Z]/i', $isbn))
		return false;
	$isbn = preg_replace('/-|\.| /', '', $isbn);
	if (strlen($isbn)==10) return true;
	else return false;
}

function isISBN13($isbn) {
	// return true si ISBN sur 10 caractères	
	if(preg_match('/[A-WY-Z]/i', $isbn))
		return false;
	$isbn = preg_replace('/-|\.| /', '', $isbn);
	if (strlen($isbn)==13) return true;
	else return false;
}

function key10($isbnwk) {
	$cksum=0;
	for ($i=0; $i<strlen($isbnwk); $i++) {
		$cksum+=(10-$i)*$isbnwk[$i];
	}
	$key=$cksum%11;
	$key=11-$key;
	if ($key==1) $key="X"; else if ($key==11) $key=0;
	return $key;
}

function key13($isbnwk) {
	$chcksum=0;
	$p=1;
	for ($i=0; $i<strlen($isbnwk); $i++) {
		$cksum+=$p*$isbnwk[$i];
		$p==1?$p=3:$p=1;
	}
	$key=10-$cksum%10;
	if ($key==10) $key=0;
	return $key;
}

function formatISBN($isbn,$taille="") {
	$isbn = preg_replace('/-|\.| /', '', $isbn);
	
	if (strlen($isbn)==13) {
		$segg=substr($isbn,0,3);
		$isbn=substr($isbn,3,10);
	} else $segg="";
	
	// traitement du code géographique

	$sTmp1 = substr($isbn, 0, 1) - 0;
	$sTmp2 = substr($isbn, 0, 2) - 0;
	$sTmp3 = substr($isbn, 0, 3) - 0;
	$sTmp4 = substr($isbn, 0, 4) - 0;
	$sTmp5 = substr($isbn, 0, 5) - 0;

	if($sTmp1 <= 7) $seg1 = $sTmp1;
		elseif ($sTmp2 <= 94) $seg1 = $sTmp2;
			elseif ($sTmp3 <= 995) $seg1 = $sTmp3;
				elseif ($sTmp4 <= 9989) $seg1 = $sTmp4;
					else $seg1 = $sTmp5;

	$isbn = preg_replace("/^$seg1/", '', $isbn);

	// calcul du segment de l'éditeur
	$sTmp2 = substr($isbn, 0, 2) - 0;
	$sTmp3 = substr($isbn, 0, 3) - 0;
	$sTmp4 = substr($isbn, 0, 4) - 0;
	$sTmp5 = substr($isbn, 0, 5) - 0;
	$sTmp6 = substr($isbn, 0, 6) - 0;
	$sTmp7 = substr($isbn, 0, 7) - 0;

	if($sTmp2 <= 19) {
		$seg2 = substr($isbn, 0, 2);
	} else {
		if($sTmp3 <= 699) {
			$seg2 = substr($isbn, 0, 3);
		} else {
			if($sTmp4 <= 8399) {
				$seg2 = substr($isbn, 0, 4);
			} else {
				if($sTmp5 <= 89999) {
					$seg2 = substr($isbn, 0, 5);
				} else {
					if($sTmp6 <= 9499999) {
						$seg2 = substr($isbn, 0, 6);
					} else {
						$seg2 = substr($isbn, 0, 7);
					}
				}
			}
		}
	}

	$isbn = preg_replace("/^$seg2/", '', $isbn);

	$key = $isbn[strlen($isbn) - 1];

	$seg3 = substr($isbn, 0, strlen($isbn) - 1);

	$isbn = ($segg?$segg."-":"")."$seg1-$seg2-$seg3-$key";

	if (!$taille) 
		return $isbn;
	else {
		if ($taille==10) {
			//C'est un 13, on recalcule la clef pour le 10
			if ($segg) {
				$key=key10($seg1.$seg2.$seg3);
				// print  "$seg1-$seg2-$seg3-$key";
				return "$seg1-$seg2-$seg3-$key";
			} else return $isbn;
		} else if ($taille==13) {
			//C'est un 10, on recalcule la clef
			if (!$segg) {
				$segg="978";
				$key=key13($segg.$seg1.$seg2.$seg3);
				// print  "$seg1-$seg2-$seg3-$key";
				return "$segg-$seg1-$seg2-$seg3-$key";
			} else return $isbn;
		}
	}
}

function z_formatISBN($isbn,$taille) {
	return formatISBN($isbn,$taille);
}

function isEAN($ean) {
	$checksum=0;
	$ean = preg_replace('/-|\.| /', '', $ean);
	if(!preg_match('/^978[0-9]|^979[0-9]/', $ean)) return FALSE;
	
	if(strlen($ean) != 13) return FALSE;

	for($i = 0; $i < 13; $i = $i + 2) {
		$checksum += $ean[$i];
	}

	for($i = 1; $i < 13; $i = $i + 2) {
		$checksum += $ean[$i] * 3;
	}

	if($checksum % 10 == 0) return TRUE;

	return FALSE;
}

function EANtoISBN10($ean) {
	$checksum=0;
	// on contrôle si cela la conversion est applicable
	if (!isEAN($ean)) return '';
	if(!preg_match('/^978[0-9]/', $ean)) return '';
		
	$isbn = preg_replace('/^978|[0-9]$/', '', $ean);

	// calcul de la clé
	for ($i = 0; $i < strlen($isbn) ; $i++) {
		$checksum += (10 - $i) * $isbn[$i];
	}
	$key = 11 - $checksum % 11;

	if($key == 10) $key = 'X';

	if($key == 11) $key = '0';

	// traitement du code géographique
	$sTmp1 = substr($isbn, 0, 1) - 0;
	$sTmp2 = substr($isbn, 0, 2) - 0;
	$sTmp3 = substr($isbn, 0, 3) - 0;
	$sTmp4 = substr($isbn, 0, 4) - 0;
	$sTmp5 = substr($isbn, 0, 5) - 0;

	if($sTmp1 <= 7) {
		$seg1 = $sTmp1;
	} else {
		if($sTmp2 <= 94) {
			$seg1 = $sTmp2;
		} else {
			if($sTmp3 <= 995) {
				$seg1 = $sTmp3;
		} else {
			if($sTmp4 <= 9989) {
				$seg1 = $sTmp4;
			} else {
				$seg1 = $sTmp5;
			}
		}
		}
	}

	$isbn = preg_replace("/^$seg1/", '', $isbn);

	// calcul du segment de l'éditeur
	$sTmp2 = substr($isbn, 0, 2) - 0;
	$sTmp3 = substr($isbn, 0, 3) - 0;
	$sTmp4 = substr($isbn, 0, 4) - 0;
	$sTmp5 = substr($isbn, 0, 5) - 0;
	$sTmp6 = substr($isbn, 0, 6) - 0;
	$sTmp7 = substr($isbn, 0, 7) - 0;

	if($sTmp2 <= 19) {
		$seg2 = substr($isbn, 0, 2);
	} else {
		if($sTmp3 <= 699) {
			$seg2 = substr($isbn, 0, 3);
		} else {
			if($sTmp4 <= 8399) {
				$seg2 = substr($isbn, 0, 4);
			} else {
				if($sTmp5 <= 89999) {
					$seg2 = substr($isbn, 0, 5);
				} else {
					if($sTmp6 <= 9499999) {
						$seg2 = substr($isbn, 0, 6);
					} else {
						$seg2 = substr($isbn, 0, 7);
					}
				}
			}
		}
	}

	$seg3 = preg_replace("/^$seg2/", '', $isbn);
	$isbn = "$seg1-$seg2-$seg3-$key";
	return $isbn;
}

function EANtoISBN($ean) {
	// on contrôle si cela la conversion est applicable
	if (!isEAN($ean))
		return '';
	
	return formatISBN($ean);
}


function z_EANtoISBN($ean) {
	// on contrôle si cela la conversion est applicable
	if (!isEAN($ean))
		return '';
	
	return z_formatISBN($ean);
}

function traite_code_isbn ($saisieISBN="") {
	if($saisieISBN) {
		if(isEAN($saisieISBN)) {
			// la saisie est un EAN -> on tente de le formater en ISBN
			$code = EANtoISBN($saisieISBN);
			// si échec, on prend l'EAN comme il vient
			if(!$code) $code = $saisieISBN;
		} else {
			if(isISBN($saisieISBN)) {
				// si la saisie est un ISBN
				$code = formatISBN($saisieISBN);
				// si échec, ISBN erroné on le prend sous cette forme
				if(!$code) $code = $saisieISBN;
			} else {
				// ce n'est rien de tout ça, on prend la saisie telle quelle
				$code = $saisieISBN;
						}
		}
		return $code ;
	}
	return "";
}


//Pour vérifier un ISSN 
function isISSN($issn) {
	
	$checksum=0;
	
	// s'il y a des lettres, pas un ISSN
	if(preg_match('/[A-WY-Z]/i', $issn)) return FALSE;
	$issn = preg_replace('/-|\.| /', '', $issn);
	
	//Plus de 8 digits, pas un ISSN
	if (strlen($issn)!=8) return FALSE;
	
	$key = $issn[strlen($issn) - 1];
	
	if(strtoupper($key) == 'X') $key = 10;
	$issn = substr($issn, 0, strlen($issn) - 1);
	
	// vérification de la clé
	for($i = 0; $i < strlen($issn) ; $i++) {
		$checksum += (8 - $i) * $issn[$i];
	}
	
	$checksum += $key;
	
	if (($checksum%11) == 0) return TRUE ;
		else return FALSE ;

}

//retourne un code issn formate correctement ou le code saisi si ce n'est pas un issn  
function traite_code_ISSN($issn) {
	if ($issn) {
		if (isISSN($issn)) {
			$issn = preg_replace("/[^0-9|X]/i", '', $issn);
			$issn = str_replace('x','X',$issn);
			$issn=substr($issn,0,4).'-'.substr($issn,4,4);
			return $issn;
		} else return $issn;
	}
	return '';
}
