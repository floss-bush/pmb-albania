<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: misc.inc.php,v 1.40 2010-08-11 10:08:52 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/semantique.class.php");

//ajout des mots vides calculés
$add_empty_words=semantique::add_empty_words();
if ($add_empty_words) eval($add_empty_words);

// ----------------------------------------------------------------------------
//	fonctions de formatage de chaîne
// ----------------------------------------------------------------------------
// reg_diacrit : fonction pour traiter les caractères accentués en recherche avec regex


function reg_diacrit($chaine) {
	// Armelle : a priori inutile.
	global $charset;
	global $include_path;
	// préparation d'une chaine pour requête par REGEXP
	global $tdiac ;
	if (!$tdiac) { 
			$tdiac = new XMLlist("$include_path/messages/diacritique$charset.xml");
			$tdiac->analyser();
	}
	foreach($tdiac->table as $wreplace => $wdiacritique) {
			if(pmb_preg_match("/$wdiacritique/", $chaine))
				$chaine = pmb_preg_replace("/$wdiacritique/", $wreplace, $chaine);
	}
		$tab = pmb_split('/\s/', $chaine);
	// mise en forme de la chaine pour les alternatives
	// on fonctionne avec OU (pour l'instant)
	if(sizeof($tab) > 1) {
		foreach($tab as $dummykey=>$word) {
			if($word) $this->mots[] = "($word)";
		}
		return join('|', $this->mots);
	} else {
		return $chaine;
	}
}

function convert_diacrit($string) {
	global $tdiac;
	global $charset;
	global $include_path;
	if(!$string) return;
	if (!$tdiac) { 
			$tdiac = new XMLlist("$include_path/messages/diacritique$charset.xml");
			$tdiac->analyser();
	}
	foreach($tdiac->table as $wreplace => $wdiacritique) {
		if(pmb_preg_match("/$wdiacritique/", $string))
			$string = pmb_preg_replace("/$wdiacritique/", $wreplace, $string);
	}	
	return $string;
}

//strip_empty_chars : enlêve tout ce qui n'est pas alphabétique ou numérique d'une chaine
function strip_empty_chars($string) {
	// traitement des diacritiques
	$string = convert_diacrit($string);

	// Mis en commentaire : qu'en est-il des caractères non latins ???
	// SUPPRIME DU COMMENTAIRE : ER : 12/05/2004 : ça fait tout merder...
	// RECH_14 : Attention : ici suppression des éventuels "
	//          les " ne sont plus supprimés 
	$string = stripslashes($string) ;
	$string = pmb_alphabetic('^a-z0-9\s', ' ',pmb_strtolower($string));

	// remplacement espace  insécable 0xA0:	&nbsp;  	Non-breaking space
	$string = clean_nbsp($string);
	// espaces en début et fin
	$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);
	
	// espaces en double
	$string = pmb_preg_replace('/\s+/', ' ', $string);
	
	return $string;
}

// strip_empty_words : fonction enlevant les mots vides d'une chaîne
function strip_empty_words($string) {

	// on inclut le tableau des mots-vides pour la langue par defaut
	// c'est normalement la langue de catalogage...
	// si après nettoyage des mots vide la chaine est vide alors on garde la chaine telle quelle (sans les accents)
	
	global $empty_word;
	
	// nettoyage de l'entrée

	// traitement des diacritiques
	$string = convert_diacrit($string);

	// Mis en commentaire : qu'en est-il des caractères non latins ???
	// SUPPRIME DU COMMENTAIRE : ER : 12/05/2004 : ça fait tout merder...
	// RECH_14 : Attention : ici suppression des éventuels "
	//          les " ne sont plus supprimés 
	$string = stripslashes($string) ;
	$string = pmb_alphabetic('^a-z0-9\s', ' ',pmb_strtolower($string));
	
	// remplacement espace  insécable 0xA0:	&nbsp;  	Non-breaking space
	$string = clean_nbsp($string);
	
	// espaces en début et fin
	$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);
	
	// espaces en double
	$string = pmb_preg_replace('/\s+/', ' ', $string);
	
	$string_avant_mots_vides = $string ; 
	// suppression des mots vides
	if(is_array($empty_word)) {
		foreach($empty_word as $dummykey=>$word) {
			$word = convert_diacrit($word);
			$string = pmb_preg_replace("/^${word}$|^${word}\s|\s${word}\s|\s${word}\$/i", ' ', $string);
			// RECH_14 : suppression des mots vides collés à des guillemets
			if (pmb_preg_match("/\"${word}\s/i",$string)) $string = pmb_preg_replace("/\"${word}\s/i", '"', $string);
			if (pmb_preg_match("/\s${word}\"/i",$string)) $string = pmb_preg_replace("/\s${word}\"/i", '"', $string);
			}
		}


	// re nettoyage des espaces générés
	// espaces en dÈbut et fin
	$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);
	// espaces en double
	$string = pmb_preg_replace('/\s+/', ' ', $string);
	
	if (!$string) {
		$string = $string_avant_mots_vides ;
		// re nettoyage des espaces générés
		// espaces en dÈbut et fin
		$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);
		// espaces en double
		$string = pmb_preg_replace('/\s+/', ' ', $string);
		}

	return $string;
	}

// clean_string() : fonction de nettoyage d'une chaîne
function clean_string($string) {

	// on supprime les caractËres non-imprimables
	$string = pmb_preg_replace("/\\x0|[\x01-\x1f]/U","",$string);

	// suppression des caractËres de ponctuation indÈsirables
	// $string = pmb_preg_replace('/[\{\}\"]/', '', $string);

	// supression du point et des espaces de fin
	$string = pmb_preg_replace('/\s+\.$|\s+$/', '', $string);

	// nettoyage des espaces autour des parenthËses
	$string = pmb_preg_replace('/\(\s+/', '(', $string);
	$string = pmb_preg_replace('/\s+\)/', ')', $string);

	// idem pour les crochets
	$string = pmb_preg_replace('/\[\s+/', '[', $string);
	$string = pmb_preg_replace('/\s+\]/', ']', $string);

	// petit point de détail sur les apostrophes
	$string = pmb_preg_replace('/\'\s+/', "'", $string); 

	// 'trim' par regex
	$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);

	// suppression des espaces doubles
	$string = pmb_preg_replace('/\s+/', ' ', $string);

	return $string;
	}

// ----------------------------------------------------------------------------
//	test_title_query() : nouvelle version analyse d'une rech. sur titre
// ----------------------------------------------------------------------------
function test_title_query($query, $operator=TRUE, $force_regexp=FALSE) {
	// Armelle : a priori utilise uniquement dans édition des périodique. Changer la-bas.
	// fonction d'analyse d'une recherche sur titre
	// la fonction retourne un tableau :
	$query_result = array(  'type' => 0,
	                        'restr' => '',
	                        'order' => '',
	                        'nbr_rows' => 0);
	
	// FORCAGE ER 12/05/2004 : le match against avec la troncature* ne fonctionne pas...
	$force_regexp = TRUE ;
	
	// $query_result['type'] = type de la requête :
	// 0 : rien (problème) 
	// 1: match/against
	// 2: regexp
	// 3: regexp pure sans traitement
	// $query_result['restr'] = critères de restriction
	// $query_result['order'] = critères de tri
	// $query_result['indice'] = façon d'obtenir un indice de pertinence
	// $query_result['nbr_rows'] = nombre de lignes qui matchent
	
	// si operator TRUE La recherche est booléenne AND
	// si operator FALSE La recherche est booléenne OR
	// si force_regexp : la recherche est forcée en mode regexp
	
	$stopwords = FALSE;
	global $dbh;
	
	// initialisation opérateur
	$operator ? $dopt = 'AND' : $dopt = 'OR';
	
	$query = strtolower($query);
	
	// espaces en début et fin
	$query = preg_replace('/^\s+|\s+$/', '', $query);
	
	// espaces en double
	$query = preg_replace('/\s+/', ' ', $query);
	
	
	// traitement des caractères accentués
	$query = convert_diacrit($query);
	
	// contrôle de la requete
	if(!$query)
		return $query_result;
	
	// déterminer si la requête est une regexp
	// si c'est le cas, on utilise la saisie utilisateur sans modification
	// (on part du principe qu'il sait ce qu'il fait)
	
	if(preg_match('/\^|\$|\[|\]|\.|\*|\{|\}|\|/', $query)) {
		// regexp pure : pas de modif de la saisie utilisateur
		$query_result['type'] = 3;
		$query_result['restr'] =  "index_serie REGEXP '$query'";
		$query_result['restr'] .= " OR tit1 REGEXP '$query'";
		$query_result['restr'] .= " OR tit2 REGEXP '$query'";
		$query_result['restr'] .= " OR tit3 REGEXP '$query'";
		$query_result['restr'] .= " OR tit4 REGEXP '$query'";
	       	$query_result['order'] = "index_serie ASC, tnvol ASC, tit1 ASC";
		} else {
	 		// nettoyage de la chaîne
	 		$query = preg_replace("/[\(\)\,\;\'\!\-\+]/", ' ', $query);
	 		
	 		// on supprime les mots vides
	 		$query = strip_empty_words($query);
	 		
	 		// contrôle de la requete
	 		if(!$query) return $query_result;
	
			// la saisie est splitée en un tableau
			$tab = preg_split('/\s+/', $query);
			
			// on cherche à détecter les mots de moins de 4 caractères (stop words)
			// si il y des mots remplissant cette condition, c'est la méthode regexp qui sera employée
			foreach($tab as $dummykey=>$word) {
				if(strlen($word) < 4) {
					$stopwords = TRUE;
					break;
					}
				}
	
			if($stopwords || $force_regexp) {
				// méthode REGEXP
				$query_result['type'] = 2;
				 // constitution du membre restricteur
				// premier mot
				$query_result['restr'] = "(index_sew REGEXP '${tab[0]} ) '";
				for ($i = 1; $i < sizeof($tab); $i++) {
					$query_result['restr'] .= " $dopt (index_sew REGEXP '${tab[$i]}' )";
					}
				// contitution de la clause de tri
				$query_result['order'] = "index_serie ASC, tnvol ASC, tit1 ASC";
				} else {
					// méthode FULLTEXT
					$query_result['type'] = 1;
					// membre restricteur
					$query_result['restr'] = "MATCH (index_wew) AGAINST ('*${tab[0]}*')";
					for ($i = 1; $i < sizeof($tab); $i++) {
						$query_result['restr'] .= " $dopt MATCH";
						$query_result['restr'] .= " (index_wew)";
						$query_result['restr'] .= " AGAINST ('*${tab[$i]}*')";
						}
					// membre de tri
					$query_result['order'] = "index_serie DESC, tnvol ASC, index_sew ASC";
					}
			}
	
	// récupération du nombre de lignes
	$rws = "SELECT count(1) FROM notices WHERE ${query_result['restr']}";
	$result = @mysql_query($rws, $dbh);
	$query_result['nbr_rows'] = @mysql_result($result, 0, 0);
	
	return $query_result;
	}

//Fonction de préparation des chaines pour regexp sans match against
function analyze_query($query) {
	// Armelle - a priori plus utilisé
	// déterminer si la requête est une regexp
	// si c'est le cas, on utilise la saisie utilisateur sans modification
	// (on part du principe qu'il sait ce qu'il fait)
	if(preg_match('/\^|\$|\[|\]|\.|\*|\{|\}|\|\+/', $query)) {
		// traitement des caractères accentués
		$query = preg_replace('/[àáâãäåÀÁÂÃÄÅ]/'	, 'a', $query);
		$query = preg_replace('/[éèêëÈÉÊË]/'		, 'e', $query);
		$query = preg_replace('/[ìíîïÌÍÎÏ]/'		, 'i', $query);
		$query = preg_replace('/[òóôõöÒÓÔÕÖ]/'		, 'o', $query);
		$query = preg_replace('/[ùúûüÙÚÛÜ]/'		, 'u', $query);
		$query = preg_replace('/[çÇ]/m'				, 'c', $query);
		return $query;
	} else {
		return reg_diacrit($query);
	}
}

// ----------------------------------------------------------------------------
//	fonction sur les dates
// ----------------------------------------------------------------------------
// today() : retourne la date du jour au format MySQL-DATE
// penser à mettre à jour les classes concernées
function today() {
	$jour = date('Y-m-d');
	return $jour;
	}

// ----------------------------------------------------------------------------
//	fonction qui retourne le nom de la page courante (SANS L'EXTENSION .php) !
// ----------------------------------------------------------------------------
function current_page() {
	return ereg_replace("/", "", ereg_replace("\/.*\/(.*\.php)$", "\\1", $_SERVER["PHP_SELF"]));
	}

// ----------------------------------------------------------------------------
//	fonction gen_liste qui génère des combo_box super sympas
// ----------------------------------------------------------------------------
function gen_liste ($requete, $champ_code, $champ_info, $nom, $on_change, $selected, $liste_vide_code, $liste_vide_info,$option_premier_code,$option_premier_info) {
	$resultat_liste=mysql_query($requete);
	$renvoi="<select name=\"$nom\"  id=\"$nom\" onChange=\"$on_change\">\n";
	$nb_liste=mysql_num_rows($resultat_liste);
	if ($nb_liste==0) {
		$renvoi.="<option value=\"$liste_vide_code\">$liste_vide_info</option>\n";
		} else {
			if ($option_premier_info!="") {	
				$renvoi.="<option value=\"$option_premier_code\" ";
				if ($selected==$option_premier_code) $renvoi.="selected='selected'";
				$renvoi.=">$option_premier_info</option>\n";
				}
			$i=0;
			while ($i<$nb_liste) {
				$renvoi.="<option value=\"".mysql_result($resultat_liste,$i,$champ_code)."\" ";
				if ($selected==mysql_result($resultat_liste,$i,$champ_code)) $renvoi.="selected";
				$renvoi.=">".mysql_result($resultat_liste,$i,$champ_info)."</option>\n";
				$i++;
				}
			}
	$renvoi.="</select>\n";
	return $renvoi;
	}

// ----------------------------------------------------------------------------
//	fonction qui retourne le nom de la page courante (SANS L'EXTENSION .php) !
// ----------------------------------------------------------------------------
function inslink($texte="", $lien="",$param="") {
	if ($lien) return "<a href='$lien' $param>$texte</a>" ;
		else return "$texte" ;
	}

// ----------------------------------------------------------------------------
//	fonction qui insère l'entrée $entree dans un table si image possible avec le $code
// ----------------------------------------------------------------------------
function do_image(&$entree, $code, $depliable ) {
	global $opac_show_book_pics ;
	global $opac_book_pics_url ;
	global $opac_url_base ;
	if ($code<>"") {
		if ($opac_show_book_pics=='1' && $opac_book_pics_url) {
			$code_chiffre = preg_replace('/-|\.| /', '', $code);
			$url_image = $opac_book_pics_url ;
			$url_image = $opac_url_base."getimage.php?url_image=".urlencode($url_image)."&noticecode=!!noticecode!!" ;
			if ($depliable) $image = "<img src='$opac_url_base/images/vide.png' align='right' hspace='4' vspace='2' isbn='".$code_chiffre."' url_image='".$url_image."'>";
				else {
					$url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
					$image = "<img src='".$url_image_ok."' align='right' hspace='4' vspace='2'>";
					}
			} else $image="" ;
		if ($image) $entree = "<table width='100%'><tr><td>$entree</td><td valign=top align=right>$image</td></tr></table>" ;
			else $entree = "<table width='100%'><tr><td>$entree</td></tr></table>" ;

		} else $entree = "<table width='100%'><tr><td>$entree</td></tr></table>" ;	
	}

// ------------------------------------------------------------------
//  pmb_preg_match($regex,$chaine) : recherche d'une regex
// ------------------------------------------------------------------
function pmb_preg_match($regex,$chaine) {
	global $charset;
	if ($charset != 'utf-8') {
		return preg_match($regex,$chaine);
	}
	else {
		return preg_match($regex.'u',$chaine);
	}
}

// ------------------------------------------------------------------
//  pmb_preg_replace($regex,$replace,$chaine) : remplacement d'une regex par une autre
// ------------------------------------------------------------------
function pmb_preg_replace($regex,$replace,$chaine) {
	global $charset;
	if ($charset != 'utf-8') {
		return preg_replace($regex,$replace,$chaine);
	}
	else {
		return preg_replace($regex.'u',$replace,$chaine);
	}
}

// ------------------------------------------------------------------
//  pmb_str_replace($toreplace,$replace,$chaine) : remplacement d'une chaine par une autre
// ------------------------------------------------------------------
function pmb_str_replace($toreplace,$replace,$chaine) {
	global $charset;
	if ($charset != 'utf-8') {
		return str_replace($toreplace,$replace,$chaine);
	}
	else {
		return preg_replace("/".$toreplace."/u",$replace,$chaine);
	}
}

// ------------------------------------------------------------------
//  pmb_split($separateur,$string) : sépare un chaine de caractère selon un separateur
// ------------------------------------------------------------------
function pmb_split($separateur,$chaine) {
	global $charset;
	if ($charset != 'utf-8') {
		return preg_split($separateur,$chaine);
	}
	else {
		return mb_split($separateur,$chaine);
	}
}

// ------------------------------------------------------------------
//  pmb_alphabetic($string) : enlève les caractères non alphabétique. Equivalent de [a-z0-9]
// pour les caractères latins;
// Pour l'instant pour les caractères non latins: 
// \x{0531}-\x{0587}\x{fb13}-\x{fb17} : Armenien
// \x{0621}-\x{0669}\x{066E}-\x{06D3}\x{06D5}-\x{06FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}: Arabe
// \x{0400}-\x{052F}\x{0500}-\x{050F} : Cyrillique
// \x{4E00}-\x{9BFF} : Chinois 
// ------------------------------------------------------------------

function pmb_alphabetic($regex,$replace,$string) {
	global $charset;
	
	if ($charset != 'utf-8') {
		return preg_replace('/['.$regex.']/', ' ', $string);	
		}
	else {
		return preg_replace('/['.$regex.'\x{0531}-\x{0587}\x{fb13}-\x{fb17}\x{0621}-\x{0669}\x{066E}-\x{06D3}\x{06D5}-\x{06FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}\x{0400}-\x{052F}\x{0500}-\x{050F}\x{4E00}-\x{9BFF}]/u', ' ', $string);
	}
}

// ------------------------------------------------------------------
//  pmb_strlen($string) : calcule la longueur d'une chaine pour utf-8 il s'agit du nombre de caractères.
// ------------------------------------------------------------------
function pmb_strlen($string) {
	global $charset;
	
	if ($charset != 'utf-8') 
		return strlen($string);
	else {
		return mb_strlen($string,$charset);
	}		
}

// ------------------------------------------------------------------
//  pmb_getcar($currentcar,$string) : recupere le caractere $cuurentcar de la chaine
// ------------------------------------------------------------------
function pmb_getcar($currentcar,$string) {
	global $charset;
	
	if ($charset != 'utf-8') 
		return $string[$currentcar];
	else {
		return mb_substr($string,$currentcar, 1,$charset);
	}		
}

// ------------------------------------------------------------------
//  pmb_substr($chaine,$depart,$longueur) : recupere n caracteres 
// ------------------------------------------------------------------
function pmb_substr($chaine,$depart,$longueur=0) {
	global $charset;
	
	if ($charset != 'utf-8') { 
		if ($longueur == 0)
			return substr($chaine,$depart);
		else
			return substr($chaine,$depart,$longueur);
	}
	else {
		if ($longueur == 0)
			return mb_substr($chaine,$depart,$charset);
		else
			return mb_substr($chaine,$depart,$longueur,$charset);
	}		
}

// ------------------------------------------------------------------
//  pmb_strtolower($string) : passage d'une chaine de caractère en minuscule
// ------------------------------------------------------------------
function pmb_strtolower($string) {
	global $charset;
	if ($charset != 'utf-8') {
		return strtolower($string);
	}
	else {
		return mb_strtolower($string,$charset);
	}
}

// ------------------------------------------------------------------
//  pmb_strtoupper($string) : passage d'une chaine de caractère en majuscule
// ------------------------------------------------------------------
function pmb_strtoupper($string) {
	global $charset;
	if ($charset != 'utf-8') {
		return strtoupper($string);
	}
	else {
		return mb_strtoupper($string,$charset);
	}
}

// ------------------------------------------------------------------
//  pmb_bidi($string) : renvoi la chaine de caractere en gérant les problemes 
//  d'affichage droite gauche des parenthèses
// ------------------------------------------------------------------
function pmb_bidi($string) {
	global $charset;
	global $lang;
	
	return $string;
	
	if ($charset != 'utf-8' or $lang == 'ar') {
		// utf-8 obligatoire pour l'arabe
		return $string;
	}
	else {
		//\x{0600}-\x{06FF}\x{0750}-\x{077F} : Arabic
		//x{0590}-\x{05FF} : hebrew
		if (preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{0590}-\x{05FF}]/u', $string)) {

			// 1 - j'entoure les caractères arabes + espace ou parenthese ou chiffre de <span dir=rtl>'
			 $string = preg_replace("/([\s*(&nbsp;)*(&amp;)*\-*\(*0-9*]*[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{0590}-\x{05FF}]+([,*\s*(&nbsp;)*(&amp;)*\-*\(*0-9*]*[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{0590}-\x{05FF}]*[,*\s*(&nbsp;)*(&amp;)*\-*\)*0-9*]*)*)/u","<span dir='rtl'>\\1</span>",$string);
			 // 2 - j'enleve les span dans les 'value' ca marche pas dans les ecrans de saisie
			 $string = preg_replace('/value=[\'\"]<span dir=\'rtl\'>(.*?)<\/span>[\'\"]/u','value=\'\\1\'',$string);
			 // 3 - j'enleve les span dans les 'title'
			 $string = preg_replace('/title=[\'\"]<span dir=\'rtl[\'\"]>(.*?)<\/span>/u','title=\'\\1',$string);
			 // 4 - j'enleve les span dans les 'alt'
			 $string = preg_replace('/alt=[\'\"]<span dir=\'rtl[\'\"]>(.*?)<\/span>/u','alt=\'\\1',$string);
			 // 4 - j'enleve les span sont entre cote, c'est que c'est dans une valeur.
			 $string = preg_replace('/[\'\"]<span dir=\'rtl[\'\"]>(.*?)<\/span>\'/u','\'\\1\'',$string);
			 // 4 - j'enleve les span dans les textarea.
			 //preg_match('/<textarea(.*?)><span dir=\'rtl[\'\"](.*?)<\/span>/u',$string,$toto);
			 //printr($toto);
			 $string = preg_replace('/<textarea(.*?)><span dir=\'rtl[\'\"](.*?)<\/span>/u','<textarea \\1 \\2',$string);
			 return $string;
		}
		else {
			return $string;
		}
		
	}
}

function gen_plus_form($id, $titre, $contenu,$startopen=false) {
	return "	
		<div class='row'></div>
		<div id='$id' class='notice-parent'>
			<img src='./images/plus.gif' name='imEx' id='$id" . "Img' title='détail' border='0' onClick=\"expandBase('$id', true); return false;\" hspace='3'>
			<span class='notice-heada'>
				$titre
			</span>
		</div>
		<div id='$id" . "Child' class='notice-child' ".($startopen?"startOpen='Yes' ":"")."style='margin-bottom:6px;display:none;width:94%'>
			$contenu
		</div>
		";
}

// ------------------------------------------------------------------
//  mail_bloc_adresse() : renvoie un code HTML contenant le bloc d'adresse à mettre en bas 
//  des mails envoyés par PMB (résa, prêts) 
// ------------------------------------------------------------------
function mail_bloc_adresse() {
	global $msg ;
	global $biblio_name, $biblio_email,$biblio_website ;
	global $biblio_adr1, $biblio_adr2, $biblio_cp, $biblio_town, $biblio_phone ; 
	$ret = $biblio_name ;
	if ($biblio_adr1) $ret .= "<br />".$biblio_adr1 ;  
	if ($biblio_adr2) $ret .= "<br />".$biblio_adr2 ;  
	if ($biblio_cp && $biblio_town) $ret .= "<br />".$biblio_cp." ".$biblio_town ;
	elseif ($biblio_town) $ret .= "<br />".$biblio_cp." ".$biblio_town ;
	if ($biblio_phone) $ret .= "<br />".$msg['location_details_phone']." ".$biblio_phone ;
	if ($biblio_email) $ret .= "<br />".$msg['location_details_email']." ".$biblio_email ;
	if ($biblio_website) $ret .= "<br />".$msg['location_details_website']." <a href='".$biblio_website."'>".$biblio_website."</a>" ;

	return $ret ;
}

//---------------------------------
//CONFIGURATION DU PROXY POUR CURL
//---------------------------------

function configurer_proxy_curl(&$curl){
	global $opac_curl_proxy;
	
	if($opac_curl_proxy!=''){
		$param_proxy = explode(',',$opac_curl_proxy);
		$adresse_proxy = $param_proxy[0];
		$port_proxy = $param_proxy[1];
		$user_proxy = $param_proxy[2];
		$pwd_proxy = $param_proxy[3];
		
		curl_setopt($curl, CURLOPT_PROXY, $adresse_proxy);
		curl_setopt($curl, CURLOPT_PROXYPORT, $port_proxy);
		curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$user_proxy:$pwd_proxy");
	}

}

//remplacement espace insécable 0xA0: &nbsp; Non-breaking space => problème lié à certaine version de navigateur
function clean_nbsp($input) {	
	global $charset;
    if($charset=="iso-8859-1")$input = str_replace(chr(0xa0), ' ', $input);
    return $input;
}

function addslashes_array($input_arr){
    if(is_array($input_arr)){
        $tmp = array();
        foreach ($input_arr as $key1 => $val){
            $tmp[$key1] = addslashes_array($val);
        }
        return $tmp;
    } 
    else {
    	if (is_string($input_arr))
        	return addslashes($input_arr);
        else
        	return $input_arr;
    }
}

function stripslashes_array($input_arr){
    if(is_array($input_arr)){
        $tmp = array();
        foreach ($input_arr as $key1 => $val){
            $tmp[$key1] = stripslashes_array($val);
        }
        return $tmp;
    } 
    else {
    	if (is_string($input_arr))
        	return stripslashes($input_arr);
        else
        	return $input_arr;
    }
}

function console_log($msg_to_log){
	print "<script type='text/javascript'>console.log('".addslashes($msg_to_log)."');</script>";
}
