<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: misc.inc.php,v 1.91 2010-12-20 11:33:47 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Fonction de r�cup�ration d'une URL vignette
function get_vignette($notice_id) {
	global $opac_book_pics_url;
	global $opac_url_base;
	
	$requete="select code,thumbnail_url from notices where notice_id=$notice_id";
	$res=mysql_query($requete);
	
	if ($res) {
		$notice=mysql_fetch_object($res);
		if ($notice->code || $notice->thumbnail_url) {
			if ($notice->thumbnail_url) 
				$url_image_ok=$notice->thumbnail_url;
			else {
				$code_chiffre = pmb_preg_replace('/-|\.| /', '', $notice->code);
				$url_image = $opac_book_pics_url ;
				$url_image = $opac_url_base."getimage.php?url_image=".urlencode($url_image)."&noticecode=!!noticecode!!&vigurl=".urlencode($notice->thumbnail_url) ;
				$url_image_ok = str_replace("!!noticecode!!", $code_chiffre, $url_image) ;
			}
		}
	} else {
		$url_image_ok=$opac_url_base."images/vide.png";
	}
	return $url_image_ok;
}

// ----------------------------------------------------------------------------
//	fonctions de formatage de chaine
// ----------------------------------------------------------------------------
// reg_diacrit : fonction pour traiter les caracteres accentues en recherche avec regex

// choix de la classe � utiliser pour envoi en pdf
if (!$fpdf) {
	if ($charset != 'utf-8') $fpdf = 'FPDF'; else $fpdf = 'UFPDF';
}

require_once($include_path."/marc_tables/".$lang."/empty_words");
require_once($class_path."/semantique.class.php");
//ajout des mots vides calcules
$add_empty_words=semantique::add_empty_words();
if ($add_empty_words) eval($add_empty_words);

function reg_diacrit($chaine) {
	// a priori inutile sauf dans selecteur emprunteur, mais devrait etre changee.
	global $charset;
	global $include_path;
	// preparation d'une chaine pour requete par REGEXP
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


//strip_empty_chars : enleve tout ce qui n'est pas alphabetique ou numerique d'une chaine
function strip_empty_chars($string) {
	// traitement des diacritiques
	$string = convert_diacrit($string);

	// Mis en commentaire : qu'en est-il des caracteres non latins ???
	// SUPPRIME DU COMMENTAIRE : ER : 12/05/2004 : �a fait tout merder...
	// RECH_14 : Attention : ici suppression des eventuels "
	//          les " ne sont plus supprimes 
	$string = stripslashes($string) ;
	$string = pmb_alphabetic('^a-z0-9\s', ' ',pmb_strtolower($string));
	
	// remplacement espace  ins�cable 0xA0:	&nbsp;  	Non-breaking space
	$string = clean_nbsp($string);
	
	// espaces en debut et fin
	$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);
	
	// espaces en double
	$string = pmb_preg_replace('/\s+/', ' ', $string);
	
	return $string;
}

// strip_empty_words : fonction enlevant les mots vides d'une chaine
function strip_empty_words($string, $lg = 0) {

	// on inclut le tableau des mots-vides pour la langue par defaut si elle n'est pas precisee
	// c'est normalement la langue de catalogage...	
	// sinon on inclut le tableau des mots vides pour la langue precisee
	// si apres nettoyage des mots vide la chaine est vide alors on garde la chaine telle quelle (sans les accents)
	global $lang;
	global $include_path;
	if (!$lg || $lg == $lang) {
		global $empty_word;
	} else {
		include("$include_path/marc_tables/$lg/empty_words");
	}
	//echo "<pre>";
	//print_r($empty_word);
	//echo "</pre>";
	
	// nettoyage de l'entree

	// traitement des diacritiques
	$string = convert_diacrit($string);

	// Mis en commentaire : qu'en est-il des caracteres non latins ???
	// SUPPRIME DU COMMENTAIRE : ER : 12/05/2004 : �a fait tout merder...
	// RECH_14 : Attention : ici suppression des eventuels "
	//          les " ne sont plus supprimes 
	$string = stripslashes($string) ;
	$string = pmb_alphabetic('^a-z0-9\s', ' ',pmb_strtolower($string));
	
	// remplacement espace  ins�cable 0xA0:	&nbsp;  	Non-breaking space
	$string = clean_nbsp($string);
		
	// espaces en debut et fin
	$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);
	
	// espaces en double
	$string = pmb_preg_replace('/\s+/', ' ', $string);

	$string_avant_mots_vides = $string ; 
	// suppression des mots vides
	if(is_array($empty_word)) {
		foreach($empty_word as $dummykey=>$word) {
			$word = convert_diacrit($word);
			$string = pmb_preg_replace("/^${word}$|^${word}\s|\s${word}\s|\s${word}\$/i", ' ', $string);
			// RECH_14 : suppression des mots vides colles � des guillemets
			if (pmb_preg_match("/\"${word}\s/i",$string)) $string = pmb_preg_replace("/\"${word}\s/i", '"', $string);
			if (pmb_preg_match("/\s${word}\"/i",$string)) $string = pmb_preg_replace("/\s${word}\"/i", '"', $string);
		}
	}


	// re nettoyage des espaces generes
	// espaces en debut et fin
	$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);
	// espaces en double
	$string = pmb_preg_replace('/\s+/', ' ', $string);
	
	if (!$string) {
		$string = $string_avant_mots_vides ;
		// re nettoyage des espaces generes
		// espaces en debut et fin
		$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);
		// espaces en double
		$string = pmb_preg_replace('/\s+/', ' ', $string);
	}

	return $string;
}

// clean_string() : fonction de nettoyage d'une cha�ne
function clean_string($string) {

	// on supprime les caract�res non-imprimables
	$string = pmb_preg_replace("/\\x0|[\x01-\x1f]/U","",$string);

	// suppression des caract�res de ponctuation indesirables
	// $string = pmb_preg_replace('/[\{\}\"]/', '', $string);

	// supression du point et des espaces de fin
	$string = pmb_preg_replace('/\s+\.$|\s+$/', '', $string);

	// nettoyage des espaces autour des parenth�ses
	$string = pmb_preg_replace('/\(\s+/', '(', $string);
	$string = pmb_preg_replace('/\s+\)/', ')', $string);

	// idem pour les crochets
	$string = pmb_preg_replace('/\[\s+/', '[', $string);
	$string = pmb_preg_replace('/\s+\]/', ']', $string);

	// petit point de detail sur les apostrophes
	//$string = pmb_preg_replace('/\'\s+/', "'", $string); 

	// 'trim' par regex
	$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);

	// suppression des espaces doubles
	$string = pmb_preg_replace('/\s+/', ' ', $string);

	return $string;
}

// ----------------------------------------------------------------------------
//	fonctions sur les dates
// ----------------------------------------------------------------------------
// today() : retourne la date du jour au format MySQL-DATE
function today() {
	$jour = date('Y-m-d');
	return $jour;
}

// formatdate() : retourne une date formatee comme il faut
function formatdate($date_a_convertir, $with_hour=0) {
	global $msg;
	global $dbh;

	if ($with_hour) $resultatdate=mysql_query("select date_format('".$date_a_convertir."', '".$msg["format_date_heure"]."') as date_conv ");
		else $resultatdate=mysql_query("select date_format('".$date_a_convertir."', '".$msg["format_date"]."') as date_conv ");
	$date_conv=mysql_result($resultatdate,0,0);

	return $date_conv ;
}

// extraitdate() : retourne une date formatee comme il faut
function extraitdate($date_a_convertir) {
	global $msg;
	$format_local = str_replace ("%","",$msg["format_date"]);
	$format_local = str_replace ("-","",$format_local);
	$format_local = str_replace ("/","",$format_local);
	$format_local = str_replace ("\\","",$format_local);
	$format_local = str_replace (".","",$format_local);
	$format_local = str_replace (" ","",$format_local);
	$format_local = str_replace ($msg["format_date_input_separator"],"",$format_local);
	list($date[substr($format_local,0,1)],$date[substr($format_local,1,1)],$date[substr($format_local,2,1)]) = sscanf($date_a_convertir,$msg["format_date_input"]) ;
	if ($date['Y'] && $date['m'] && $date['d']){
		 //$date_a_convertir = $date['Y']."-".$date['m']."-".$date['d'] ;
		 $date_a_convertir = sprintf("%04d-%02d-%02d",$date['Y'],$date['m'],$date['d']);
	} else {
		$date_a_convertir="";
	}
	return $date_a_convertir ;
}

// construitdateheuremysql($date) : retourne une date formatee MySQL � partir de "YYYYmmddHHMMSS"
function construitdateheuremysql($date_a_convertir) {
	global $msg;
	$date_a_convertir = str_replace('-', '', $date_a_convertir);
	$date_a_convertir = str_replace('/', '', $date_a_convertir );
	$date_a_convertir = str_replace(' ', '', $date_a_convertir );
	$date_a_convertir = str_replace('#', '', $date_a_convertir );
	$date_a_convertir = str_replace(':', '', $date_a_convertir );
	$date_a_convertir = str_replace('.', '', $date_a_convertir );
	$date_a_convertir = str_replace('@', '', $date_a_convertir );
	$date_a_convertir = str_replace('\\', '', $date_a_convertir );
	$date_a_convertir = str_replace('%', '', $date_a_convertir );
	$date_a_convertir = str_replace($msg["format_date_input_separator"], '', $date_a_convertir );

	$dateconv = substr($date_a_convertir,0,4) ;
	$dateconv.= "-" ;
	$dateconv.= substr($date_a_convertir,4,2) ;
	$dateconv.= "-" ;
	$dateconv.= substr($date_a_convertir,6,2) ;
	if (substr($date_a_convertir,8,2)) {
		$dateconv.= " " ;
		$dateconv.= substr($date_a_convertir,8,2) ;
		$dateconv.= ":" ;
		$dateconv.= substr($date_a_convertir,10,2) ;
		if (substr($date_a_convertir,12,2)) {
			$dateconv.= ":" ;
			$dateconv.= substr($date_a_convertir,12,2) ;
		}
	}
	return $dateconv ;
}

// ----------------------------------------------------------------------------
//	fonctions qui retourne le nom de la page courante (SANS L'EXTENSION .php) !
// ----------------------------------------------------------------------------
function current_page() {
	return ereg_replace("/", "", ereg_replace("\/.*\/(.*\.php)$", "\\1", $_SERVER["PHP_SELF"]));
}

// ----------------------------------------------------------------------------
//	fonction gen_liste qui genere des combo_box a partir d'une requete
// ----------------------------------------------------------------------------
/*
 $requete :					requete sql pour generer la liste (retourne $champ_code, $champ_info)
 $champ_code :				valeur		
 $champ_info :				libelle
 $nom :						id et name
 $on_change :				fonction a appeler sur changement
 $selected :				valeur affichee par defaut
 $liste_vide_code : 		valeur renvoyee si liste vide
 $liste_vide_info :			libelle affiche si liste vide
 $option_premier_code :     valeur en tete de liste
 $option_premier_info :     libelle en tete de liste
 $multiple :				selecteur multiple si 1
 $attr						attributs de la liste
*/
function gen_liste ($requete, $champ_code, $champ_info, $nom, $on_change, $selected, $liste_vide_code, $liste_vide_info,$option_premier_code,$option_premier_info,$multiple=0,$attr='') {
	
	global $dbh, $charset ;
	
	$resultat_liste=mysql_query($requete, $dbh) or die ($requete);
	$renvoi="<select name=\"$nom\" id=\"$nom\" onChange=\"$on_change\" ";
	if ($multiple) $renvoi.="multiple ";
	if ($attr) $renvoi.="$attr ";
	$renvoi.=">\n";
	$nb_liste=mysql_num_rows($resultat_liste);
	if ($nb_liste==0) {
		$renvoi.="<option value=\"$liste_vide_code\">$liste_vide_info</option>\n";
	} else {
		if ($option_premier_info!="") {	
			$renvoi.="<option value=\"$option_premier_code\" ";
			if ($selected==$option_premier_code) $renvoi.="selected=\"selected\"";
			$renvoi.=">$option_premier_info</option>\n";
		}
		$i=0;
		while ($i<$nb_liste) {
			$renvoi.="<option value=\"".mysql_result($resultat_liste,$i,$champ_code)."\" ";
			if ($selected==mysql_result($resultat_liste,$i,$champ_code)) $renvoi.="selected=\"selected\"";
			$renvoi.=">".htmlentities(mysql_result($resultat_liste,$i,$champ_info),ENT_QUOTES, $charset)."</option>\n";
			$i++;
		}
	}
	$renvoi.="</select>\n";
	return $renvoi;
}


// ----------------------------------------------------------------------------
//	fonction gen_liste_multiple qui genere des combo_box super sympas avec selection multiple
// ----------------------------------------------------------------------------
function gen_liste_multiple ($requete, $champ_code, $champ_info, $champ_selected, $nom, $on_change, $selected, $liste_vide_code, $liste_vide_info,$option_premier_code,$option_premier_info,$multiple=0) {
	$resultat_liste=mysql_query($requete) or die ($requete);
	$nb_liste=mysql_num_rows($resultat_liste);
	if ($multiple && $nb_liste) {
		if ($nb_liste < $multiple) $size = $nb_liste+1;
			else $size = $multiple; 
		} else $size = 1 ;
	$renvoi="<select size='$size' name='$nom' onChange=\"$on_change\"";
	if ($multiple) $renvoi.=" multiple";
	$renvoi.=">\n";
	if ($nb_liste==0) {
		$renvoi.="<option value=\"$liste_vide_code\">$liste_vide_info</option>\n";
	} else {
		if ($option_premier_info!="") {	
			$renvoi.="<option value=\"$option_premier_code\" ";
			if ($selected==$option_premier_code) $renvoi.="selected=\"selected\">";
			$renvoi.=">$option_premier_info</option>\n";
		}
		$i=0;
		while ($i<$nb_liste) {
			$renvoi.="<option value=\"".mysql_result($resultat_liste,$i,$champ_code)."\" ";
			if ($selected==mysql_result($resultat_liste,$i,$champ_selected)) $renvoi.="selected=\"selected\"";
			$renvoi.=">".mysql_result($resultat_liste,$i,$champ_info)."</option>\n";
			$i++;
		}
	}
	$renvoi.="</select>\n";
	return $renvoi;
}

// ----------------------------------------------------------------------------
//	fonction do_selector qui genere des combo_box avec tout ce qu'il faut
// ----------------------------------------------------------------------------
function do_selector($table, $name='mySelector', $value=0) {

	global $dbh;
 	global $charset;
	
	$defltvar="deflt_".$table;
	
	global $$defltvar;
	
	if ($value==0) $value= $$defltvar ;

	if(!$table)
		return '';

	$requete = "SELECT * FROM $table order by 2";
	$result = @mysql_query($requete, $dbh);

	$nbr_lignes = mysql_num_rows($result);

	if(!$nbr_lignes)
		return '';			

	$selector = "<select name='$name' id='$name'>";
	while($line = mysql_fetch_row($result)) {
		$selector .= "<option value='${line[0]}'";
		$line[0] == $value ? $selector .= ' selected=\'selected\'>' : $selector .= '>';
 		$selector .= htmlentities($line[1],ENT_QUOTES, $charset).'</option>';
	}                                         
	$selector .= '</select>';                 
                                                  
	return $selector;                         
}                                                 
 


//------like print_r but more readable--for debugging purposes
function printr($arr,$filter="",$name="") {
	//array_shift($args) ;
	print "<pre>\n" ;
	if ($name) {
		print "Printing content of array <b>$name:</b>\n";
	}
	if ($filter == "" || ! is_array($arr) ) {
		print_r($arr) ;
	} else {
		if (is_array($arr)) {
				ksort($arr);
				foreach($arr as $key => $val) {
					if (ereg("$filter", $key) || ereg("$filter", $val) ) {
						print "[" . $key . "] => " . $val ."\n" ;
					}
				}
		}
	}

	print "</pre>";
	return ;
}

// ----------------------------------------------------------------------------
//	fonction de pagination
// ----------------------------------------------------------------------------

function aff_pagination ($url_base="", $nbr_lignes=0, $nb_per_page=0, $page=0, $etendue=10, $aff_nb_per_page=false, $aff_extr=false ) {
	
	global $msg,$charset, $base_path;
	
	$nbepages = ceil($nbr_lignes/$nb_per_page);
	$suivante = $page+1;
	$precedente = $page-1;
	$deb = $page - $etendue ;
	if ($deb<1) $deb=1;
	$fin = $page + $etendue ;
	if($fin>$nbepages)$fin=$nbepages; 
		
	$nav_bar = "";
	
	if ($aff_nb_per_page) {
		$nav_bar = "<div class='left' ><input type='text' name='nb_per_page' id='nb_per_page' class='saisie-2em' value='".$nb_per_page."' />&nbsp;".htmlentities($msg['1905'], ENT_QUOTES, $charset)."&nbsp;";
		$nav_bar.= "<input type='button' class='bouton' value='".$msg['actualiser']."' ";
		$nav_bar.="onclick=\"try{ 
			var page=".$page.";
			var old_nb_per_page=".$nb_per_page.";
			var nbr_lignes=".$nbr_lignes.";
			var new_nb_per_page=document.getElementById('nb_per_page').value;
			var new_nbepages=Math.ceil(nbr_lignes/new_nb_per_page); 
			if(page>new_nbepages) page=new_nbepages;
			document.location='".$url_base."&page='+page+'&nbr_lignes=".$nbr_lignes."&nb_per_page='+new_nb_per_page;
		}catch(e){}; \" /></div>";
	}

	if($aff_extr && (($page-$etendue)>1) ) {
		$nav_bar .= "<a id='premiere' href='".$url_base."&page=1&nbr_lignes=".$nbr_lignes."&nb_per_page=".$nb_per_page."' ><img src='$base_path/images/first.gif' border='0' alt='".$msg['first_page']."' hspace='6' align='middle' title='".$msg['first_page']."' /></a>";
	}
		
	// affichage du lien precedent si necessaire
	if($precedente > 0) {
		$nav_bar .= "<a id='precedente' href='".$url_base."&page=".$precedente."&nbr_lignes=".$nbr_lignes."&nb_per_page=".$nb_per_page."' ><img src='$base_path/images/left.gif' border='0' alt='".$msg[48]."' hspace='6' align='middle' title='".$msg[48]."' /></a>";
	}

	for ($i = $deb; ($i <= $nbepages) && ($i<=$page+$etendue) ; $i++) {
		if($i==$page) {
			$nav_bar .= "<strong>".$i."</strong>";
		} else {
			$nav_bar .= "<a href='".$url_base."&page=".$i."&nbr_lignes=".$nbr_lignes."&nb_per_page=".$nb_per_page."' >".$i."</a>";
		}
		if($i<$nbepages) $nav_bar .= " "; 
	}

       	
	if ($suivante<=$nbepages) {
		$nav_bar .= "<a href='".$url_base."&page=".$suivante."&nbr_lignes=".$nbr_lignes."&nb_per_page=".$nb_per_page."' ><img src='$base_path/images/right.gif' border='0' alt='".$msg[49]."' hspace='6' align='middle' title='".$msg[49]."' /></a>";
	}

	if($aff_extr && (($page+$etendue)<$nbepages) ) {
		$nav_bar .= "<a id='derniere' href='".$url_base."&page=".$nbepages."&nbr_lignes=".$nbr_lignes."&nb_per_page=".$nb_per_page."' ><img src='$base_path/images/last.gif' border='0' alt='".$msg['last_page']."' hspace='6' align='middle' title='".$msg['last_page']."' /></a>";
	}

		
	$nav_bar = "<div align='center'>".$nav_bar."</div>";
	return $nav_bar ;
}

// ----------------------------------------------------------------------------
//	fonction de selection des sous-onglets
// ---------------------------------------------------------------------------
//exemple d'entree : categ=caddie&sub=gestion&quoi=panier
function ongletSelect($urlPart){
	$returnSelection="";
	$items=explode("&",$urlPart);
	foreach($items as $item){
		$item=explode("=",$item);
		global ${$item[0]};
		if (${$item[0]}==$item[1]){
			$returnSelection=" class=\"selected\"";
		} else {
			$returnSelection="";
			break;	
		}
	}
	return $returnSelection;
}


// ----------------------------------------------------------------------------
//	fonction generant une alerte javascript
// ----------------------------------------------------------------------------
function alert_jscript ($message="") {
global $charset;
$ret = "
<script type='text/javascript'>
<!--
alert(\"".$message."\");
-->
</script>" ;
return $ret;
}

// ---------------------------------------------------------------------------------
//	function called to clean marc fields from garbage in some italian z39.50 server
// ---------------------------------------------------------------------------------
function del_more_garbage($string) {

// delete the "<<"   and    ">>" symbols
// con l'apostrofo niente spazio
$string = preg_replace('/<<(\w*[\'])\s*>>\s*/', '$1',$string );
//senza apostrofo uno spazio
$string = preg_replace('/<<(\w*)\s*>>\s*/', '$1 ',$string );

// delete the "* " symbol
$string = preg_replace('/\*/', '',$string );

// delete the ","  at the beginnin or at the end of the string
$string= preg_replace('/^\,|\,$/', '', $string);

return $string;
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
//  pmb_preg_grep($regex,$chaine) : recherche d'une regex
// ------------------------------------------------------------------
function pmb_preg_grep($regex,$chaine) {
	global $charset;
	if ($charset != 'utf-8') {
		return preg_grep($regex,$chaine);
	}
	else {
		return preg_grep($regex.'u',$chaine);
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
//  pmb_split($separateur,$string) : separe un chaine de caractere selon un separateur
// ------------------------------------------------------------------
function pmb_split($separateur,$chaine) {
	global $charset;
	if ($charset != 'utf-8') {
		return explode($separateur,$chaine);
	}
	else {
		return mb_split($separateur,$chaine);
	}
}

// ------------------------------------------------------------------
//  pmb_alphabetic($regex,$replace,$string) : enleve les caracteres non alphabetique. Equivalent de [a-z0-9]
// pour les caracteres latins;
// Pour l'instant pour les caracteres non latins: 
// \x{0531}-\x{0587}\x{fb13}-\x{fb17} : Armenien
// \x{0621}-\x{0669}\x{066E}-\x{06D3}\x{06D5}-\x{06FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}: Arabe
// \x{0400}-\x{052F}\x{0500}-\x{050F} : Cyrillique
// \x{4E00}-\x{9BFF} : Chinois 
// ------------------------------------------------------------------
function pmb_alphabetic($regex,$replace,$string) {
	global $charset;
	
	if ($charset != 'utf-8') {
		return preg_replace('/['.$regex.']/', ' ', $string);	
	} else {
		return preg_replace('/['.$regex.'\x{0531}-\x{0587}\x{fb13}-\x{fb17}\x{0621}-\x{0669}\x{066E}-\x{06D3}\x{06D5}-\x{06FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}\x{0400}-\x{052F}\x{0500}-\x{050F}\x{4E00}-\x{9BFF}]/u', ' ', $string);
	}
}

// ------------------------------------------------------------------
//  pmb_strlen($string) : calcule la longueur d'une chaine pour utf-8 il s'agit du nombre de caracteres.
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
//  pmb_strtolower($string) : passage d'une chaine de caractere en minuscule
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
//  pmb_strtoupper($string) : passage d'une chaine de caractere en majuscule
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
//  pmb_escape() : renvoi la bonne fonction javascript en fonction du charset
// ------------------------------------------------------------------
function pmb_escape() {
	global $charset;
	if ($charset != 'utf-8') {
		return "escape";
	}
	else {
		return "encodeURIComponent";
	}
}

// ------------------------------------------------------------------
//  pmb_bidi($string) : renvoi la chaine de caractere en gerant les problemes 
//  d'affichage droite gauche des parentheses
// ------------------------------------------------------------------
function pmb_bidi($string) {
	global $charset;
	global $lang;
	if ($charset != 'utf-8' or $lang == 'ar') {
		// utf-8 obligatoire pour l'arabe
		return $string;
	}
	else {
		//\x{0600}-\x{06FF}\x{0750}-\x{077F} : Arabic
		//x{0590}-\x{05FF} : hebrew
		if (preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{0590}-\x{05FF}]/u', $string)) {

			// 1 - j'entoure les caracteres arabes + espace ou parenthese ou chiffre de <span dir=rtl>'
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

// ------------------------------------------------------------------
//  pmb_sql_value($string) : renvoie la valeur de l'unique colonne (ou uniquement de la premiere) de la requete $rqt 
// ------------------------------------------------------------------
function pmb_sql_value($rqt) {
	if($result=mysql_query($rqt))
		if($row = mysql_fetch_row($result))	return $row[0];
	return '';
}

// ------------------------------------------------------------------
//  mail_bloc_adresse() : renvoie un code HTML contenant le bloc d'adresse � mettre en bas 
//  des mails envoyes par PMB (resa, prets) 
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

//---------------------------------------------------------------------
//Affiche un bloc avec +
//---------------------------------------------------------------------
function gen_plus($id,$titre,$contenu,$maximise=0) {
	global $msg;
	if($maximise) $max=" startOpen=\"Yes\""; else $max='';
	return "	
	<div class='row'></div>
	<div id='$id' class='notice-parent'>
		<img src='./images/plus.gif' class='img_plus' name='imEx' id='$id"."Img' title='".$msg['plus_detail']."' border='0' onClick=\"expandBase('$id', true); return false;\" hspace='3'>
		<span class='notice-heada'>
			$titre
		</span>
	</div>
	<div id='$id"."Child' class='notice-child' style='margin-bottom:6px;display:none;width:94%' $max>
		$contenu
	</div>
	";
}


//---------------------------------------------------------------------
// teste une requete et retourne false si problematique, sinon true
//---------------------------------------------------------------------
function explain_requete($requete) {

if (strtolower(substr(trim($requete),0,6))!='select') return true;

	global $dbh,$erreur_explain_rqt;
	$requete = "explain ".$requete;
	$result = @mysql_query($requete, $dbh);
	$nbr_lignes = mysql_num_rows($result);

	if (!$nbr_lignes) return false;			
	/*
	echo "<table><tr>";
	echo "<td>id           </td>";
	echo "<td>select_type  </td>";
	echo "<td>table        </td>";
	echo "<td>type         </td>";
	echo "<td>possible_keys</td>";
	echo "<td>key          </td>";
	echo "<td>key_len      </td>";
	echo "<td>ref          </td>";
	echo "<td>rows         </td>";
	echo "<td>Extra        </td>";
	echo "</tr>";
	*/
	$numligne=0;
	$erreur_explain_rqt="";
	$table_davant="";
	while ($ligne = mysql_fetch_object($result)) {
		$numligne++;
		/*
		echo "<tr>";
		echo "<td>".$ligne->id           ."</td>";
		echo "<td>".$ligne->select_type  ."</td>";
		echo "<td>".$ligne->table        ."</td>";
		echo "<td>".$ligne->type         ."</td>";
		echo "<td>".$ligne->possible_keys."</td>";
		echo "<td>".$ligne->key          ."</td>";
		echo "<td>".$ligne->key_len      ."</td>";
		echo "<td>".$ligne->ref          ."</td>";
		echo "<td>".$ligne->rows         ."</td>";
		echo "<td>".$ligne->Extra        ."</td>";
		echo "</tr>";
		*/
		if ($numligne>1) {
			if ($ligne->possible_keys=='' && $ligne->ref=='' && $ligne->select_type=="SIMPLE") {
				$erreur_explain_rqt = " ERROR: ".$table_davant." - ".$ligne->table. " ";
				return false;
			}
		}
		$table_davant=$ligne->table;
	}                                         
	// echo "</table>";
	return true;
}

function clean_tags($tags) {
	global $pmb_keyword_sep;
	$liste = explode($pmb_keyword_sep,$tags);
	for($i=0; $i<count($liste); $i++) {
		if(trim($liste[$i])) $clean_liste[]=trim($liste[$i]);
	}
	if ($clean_liste) {
		return implode($pmb_keyword_sep,$clean_liste);
	} 
	return '';
}

//---------------------------------
//CONFIGURATION DU PROXY POUR CURL
//---------------------------------

function configurer_proxy_curl(&$curl){
	global $pmb_curl_proxy;
	
	if($pmb_curl_proxy!=''){
		$param_proxy = explode(',',$pmb_curl_proxy);
		$adresse_proxy = $param_proxy[0];
		$port_proxy = $param_proxy[1];
		$user_proxy = $param_proxy[2];
		$pwd_proxy = $param_proxy[3];
		
		curl_setopt($curl, CURLOPT_PROXY, $adresse_proxy);
		curl_setopt($curl, CURLOPT_PROXYPORT, $port_proxy);
		curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$user_proxy:$pwd_proxy");
	}

}

//remplacement espace ins�cable 0xA0: &nbsp; Non-breaking space => probl�me li� � certaine version de navigateur
function clean_nbsp($input) {	
	global $charset;
    if($charset=="iso-8859-1")$input = str_replace(chr(0xa0), ' ', $input);
    return $input;
}

// permet d'�viter une d�connection mysql
function mysql_set_wait_timeout($val_second=120) {
	$sql = "set wait_timeout = $val_second";
	mysql_query($sql);	
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

function alert_sound_script(){
	global $param_sounds, $alert_sound_list;
	if (!$param_sounds) return;
	if (!count($alert_sound_list)) return;
	
	// Parfois ceci bloque le focus sur Firefox 3.5. pb de temps r�el dans la gestion des evenements.
	//$script="<embed src='!!sound_file!!' height='0' width='0' autostart='true' loop='false' BORDER='0'>";
 
	
$script=
  "<embed src='!!sound_file!!' autostart='true' width='0' height='0' >
   <script type='text/javascript'>
   var obj='';
   if(document.getElementById('form_cb_expl')){
   	obj='form_cb_expl';
   }    
   if(document.getElementById('cb_doc')){
   	obj='cb_doc';
   }  
   	if(obj){
		setTimeout(\"document.getElementById('\"+obj+\"').blur(); document.getElementById('\"+obj+\"').focus(); \",1200);		
	}
   </script>
   ";
	
	if(in_array("critique",$alert_sound_list))	$sound="sounds/boing.wav";
	elseif(in_array("question",$alert_sound_list))$sound="sounds/boing.wav";
	elseif(in_array("application",$alert_sound_list))$sound="sounds/boing.wav";
	elseif(in_array("information",$alert_sound_list))$sound="sounds/waou.wav";	

	$script=str_replace("!!sound_file!!", $sound, $script) ;

	return $script;
}

function console_log($msg_to_log){
	print "<script type='text/javascript'>console.log('".addslashes($msg_to_log)."');</script>";
}