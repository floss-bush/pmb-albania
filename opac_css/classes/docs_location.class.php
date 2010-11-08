<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: docs_location.class.php,v 1.3 2008-10-08 06:02:14 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des 'docs_location'

if ( ! defined( 'DOCSLOCATION_CLASS' ) ) {
  define( 'DOCSLOCATION_CLASS', 1 );

class docs_location {

/* ---------------------------------------------------------------
		propriétés de la classe
   --------------------------------------------------------------- */

var $id=0;
var $libelle='';
var $pret_flag='';
var $locdoc_codage_import="";
var $locdoc_owner=0;
var $num_infopage=0;
var $url_infopage="";

/* ---------------------------------------------------------------
		docs_location($id) : constructeur
   --------------------------------------------------------------- */

function docs_location($id=0) {
	if ($id) {
		/* on cherche à atteindre un lieu existant */
		$this->id = $id;
		$this->getData();
	} else {
		$this->id = 0;
		$this->getData();
	}
}

/* ---------------------------------------------------------------
		getData() : récupération des propriétés
   --------------------------------------------------------------- */
function getData() {
	global $dbh, $msg;
	global $opac_url_base;
	
	if(!$this->id) return;

	/* récupération des informations du statut */

	$requete = "SELECT * FROM docs_location WHERE idlocation='".$this->id."'";
	$result = @mysql_query($requete, $dbh);
	if(!mysql_num_rows($result)) return;
		
	$data = mysql_fetch_object($result);
	$this->id = $data->idlocation;		
	$this->libelle = $data->location_libelle;		
	$this->locdoc_codage_import = $data->locdoc_codage_import;
	$this->locdoc_owner = $data->locdoc_owner;
	$this->num_infopage = $data->num_infopage;
	if ($this->num_infopage) {
		$this->url_infopage="<a href=\"".$opac_url_base."index.php?lvl=infopages&pagesid=".$this->num_infopage."\" alt=\"".$msg['location_more_info']."\" title=\"".$msg['location_more_info']."\">".htmlentities($this->libelle, ENT_QUOTES, $charset)."</a>";
	}
}

// ---------------------------------------------------------------
//		import() : import d'un lieu de document
// ---------------------------------------------------------------
function import($data) {

	// cette méthode prend en entrée un tableau constitué des informations suivantes :
	//	$data['location_libelle'] 	
	//	$data['locdoc_codage_import']
	//	$data['locdoc_owner']

	global $dbh;

	// check sur le type de  la variable passée en paramètre
	if(!sizeof($data) || !is_array($data)) {
		// si ce n'est pas un tableau ou un tableau vide, on retourne 0
		return 0;
	}
	// check sur les éléments du tableau
	
	$long_maxi = mysql_field_len(mysql_query("SELECT location_libelle FROM docs_location limit 1"),0);
	$data['location_libelle'] = rtrim(substr(preg_replace('/\[|\]/', '', rtrim(ltrim($data['location_libelle']))),0,$long_maxi));
	$long_maxi = mysql_field_len(mysql_query("SELECT locdoc_codage_import FROM docs_location limit 1"),0);
	$data['locdoc_codage_import'] = rtrim(substr(preg_replace('/\[|\]/', '', rtrim(ltrim($data['locdoc_codage_import']))),0,$long_maxi));

	if($data['locdoc_owner']=="") $data['locdoc_owner'] = 0;
	if($data['location_libelle']=="") return 0;
	/* locdoc_codage_import est obligatoire si locdoc_owner != 0 */
	//if(($data['locdoc_owner']!=0) && ($data['locdoc_codage_import']=="")) return 0;
	
	// préparation de la requête
	$key0 = addslashes($data['location_libelle']);
	$key1 = addslashes($data['locdoc_codage_import']);
	$key2 = $data['locdoc_owner'];
	
	/* vérification que le lieu existe */
	$query = "SELECT idlocation FROM docs_location WHERE locdoc_codage_import='${key1}' and locdoc_owner = '${key2}' LIMIT 1 ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't SELECT docs_location ".$query);
	$docs_location  = mysql_fetch_object($result);

	/* le lieu de doc existe, on retourne l'ID */
	if($docs_location->idlocation) return $docs_location->idlocation;

	// id non-récupérée, il faut créer la forme.
	
	$query  = "INSERT INTO docs_location SET ";
	$query .= "location_libelle='".$key0."', ";
	$query .= "locdoc_codage_import='".$key1."', ";
	$query .= "locdoc_owner='".$key2."' ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't INSERT into docs_location ".$query);

	return mysql_insert_id($dbh);
} /* fin méthode import */

/* une fonction pour générer des combo Box 
   paramêtres :
	$selected : l'élément sélectioné le cas échéant
   retourne une chaine de caractères contenant l'objet complet */
function gen_combo_box ( $selected ) {
	global $msg;
	$requete="select idlocation, location_libelle from docs_location order by location_libelle ";
	$champ_code="idlocation";
	$champ_info="location_libelle";
	$nom="book_location_id";
	$on_change="";
	$liste_vide_code="0";
	$liste_vide_info=$msg['class_location'];
	$option_premier_code="";
	$option_premier_info="";
	$gen_liste_str="";
	$resultat_liste=mysql_query($requete);
	$gen_liste_str = "<select name=\"$nom\" onChange=\"$on_change\">\n" ;
	$nb_liste=mysql_numrows($resultat_liste);
	if ($nb_liste==0) {
		$gen_liste_str.="<option value=\"$liste_vide_code\">$liste_vide_info</option>\n" ;
	} else {
		if ($option_premier_info!="") {	
			$gen_liste_str.="<option value=\"".$option_premier_code."\" ";
			if ($selected==$option_premier_code) $gen_liste_str.="selected" ;
			$gen_liste_str.=">".$option_premier_info."\n";
		}
		$i=0;
		while ($i<$nb_liste) {
			$gen_liste_str.="<option value=\"".mysql_result($resultat_liste,$i,$champ_code)."\" " ;
			if ($selected==mysql_result($resultat_liste,$i,$champ_code)) {
				$gen_liste_str.="selected" ;
			}
			$gen_liste_str.=">".mysql_result($resultat_liste,$i,$champ_info)."</option>\n" ;
			$i++;
		}
	}
	$gen_liste_str.="</select>\n" ;
	return $gen_liste_str ;
} /* fin gen_combo_box */

function gen_combo_box_empr ( $selected, $afficher_premier=1, $on_change="" ) {
	global $msg;
	$requete="select idlocation, location_libelle from docs_location order by location_libelle ";
	$champ_code="idlocation";
	$champ_info="location_libelle";
	$nom="empr_location_id";
	$liste_vide_code="0";
	$liste_vide_info=$msg['class_location'];
	$option_premier_code="0";
	if ($afficher_premier) $option_premier_info=$msg['all_location'];
	$gen_liste_str="";
	$resultat_liste=mysql_query($requete);
	$gen_liste_str = "<select name=\"$nom\" onChange=\"$on_change\" >\n";
	$nb_liste=mysql_numrows($resultat_liste);
	if ($nb_liste==0) {
		$gen_liste_str.="<option value=\"$liste_vide_code\">$liste_vide_info</option>\n" ;
	} else {
		if ($option_premier_info!="") {	
			$gen_liste_str.="<option value=\"".$option_premier_code."\" ";
			if ($selected==$option_premier_code) $gen_liste_str.="selected" ;
			$gen_liste_str.=">".$option_premier_info."</option>\n";
		}
		$i=0;
		while ($i<$nb_liste) {
			$gen_liste_str.="<option value=\"".mysql_result($resultat_liste,$i,$champ_code)."\" " ;
			if ($selected==mysql_result($resultat_liste,$i,$champ_code)) {
				$gen_liste_str.="selected" ;
				}
			$gen_liste_str.=">".mysql_result($resultat_liste,$i,$champ_info)."</option>\n" ;
			$i++;
		}
	}
	$gen_liste_str.="</select>\n" ;
	return $gen_liste_str ;
} /* fin gen_combo_box_empr */
	
	
function gen_combo_box_sugg ( $selected, $afficher_premier=1, $on_change="" ) {
	global $msg;
	$requete="select idlocation, location_libelle from docs_location where location_visible_opac=1 order by location_libelle ";
	$champ_code="idlocation";
	$champ_info="location_libelle";
	$nom="sugg_location_id";
	$liste_vide_code="0";
	$liste_vide_info=$msg['class_location'];
	$option_premier_code="0";
	if ($afficher_premier) $option_premier_info=$msg['all_location'];
	$gen_liste_str="";
	$resultat_liste=mysql_query($requete);
	$gen_liste_str = "<select name=\"$nom\" onChange=\"$on_change\" >\n";
	$nb_liste=mysql_numrows($resultat_liste);
	if ($nb_liste==0) {
		$gen_liste_str.="<option value=\"$liste_vide_code\">$liste_vide_info</option>\n" ;
	} else {
		if ($option_premier_info!="") {	
			$gen_liste_str.="<option value=\"".$option_premier_code."\" ";
			if ($selected==$option_premier_code) $gen_liste_str.="selected" ;
			$gen_liste_str.=">".$option_premier_info."</option>\n";
		}
		$i=0;
		while ($i<$nb_liste) {
			$gen_liste_str.="<option value=\"".mysql_result($resultat_liste,$i,$champ_code)."\" " ;
			if ($selected==mysql_result($resultat_liste,$i,$champ_code)) {
				$gen_liste_str.="selected" ;
				}
			$gen_liste_str.=">".mysql_result($resultat_liste,$i,$champ_info)."</option>\n" ;
			$i++;
		}
	}
	$gen_liste_str.="</select>\n" ;
	return $gen_liste_str ;
} /* fin gen_combo_box_sugg */

} /* fin de définition de la classe */

} /* fin de délaration */


