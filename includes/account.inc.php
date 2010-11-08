<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: account.inc.php,v 1.10 2007-03-10 09:46:47 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function get_account_info($user) {
	if(! $user ) return 0;
	global $dbh;
	$requete = "SELECT * FROM users WHERE username='$user' LIMIT 1";
	$result = mysql_query($requete, $dbh);
	if(mysql_num_rows($result)) {
		$values = mysql_fetch_object($result);
		return $values;
		} else return 0;
	}

function get_styles() {
	// où $rep = répertoire de stockage des feuilles
	// retourne un tableau indexé avec les noms des CSS disponibles
	
	// mise en forme du répertoire
	global $styles_path;
	
	if($styles_path) $rep = $styles_path;
		else $rep = './styles/';
	
	if(!preg_match('/\/$/', $rep)) $rep .= '/';
	
	$handle = @opendir($rep);
	
	if(!$handle) {
		$result = array();
		return $result;
		}
	
	while($css = readdir($handle)) {
		if(is_dir($rep.$css) && !preg_match('/\.|cvs|CVS/', $css) ) $result[] = $css;
		}
	
	closedir($handle);
	
	return $result;
	}

function make_user_lang_combo($lang='') {
	// retourne le combo des langues avec la langue $lang selectionnée
	// nécessite l'inclusion de XMLlist.class.php (normalement c'est déjà le cas partout
	global $include_path;
	global $msg;
	global $charset;
	
	// langue par défaut
	if(!$lang) $lang="fr_FR";
	
	$langues = new XMLlist("$include_path/messages/languages.xml");
	$langues->analyser();
	$clang = $langues->table;
	$combo = "<select name='user_lang' id='user_lang' class='saisie-20em'>";
	while(list($cle, $value) = each($clang)) {
		// arabe seulement si on est en utf-8
		if (($charset != 'utf-8' and $lang != 'ar') or ($charset == 'utf-8')) {
			if(strcmp($cle, $lang) != 0) $combo .= "<option value='$cle'>$value ($cle)</option>";
				else $combo .= "<option value='$cle' selected>$value ($cle)</option>";
		}
	}
	$combo .= "</select>";
	return $combo;
	}

function make_user_style_combo($dstyle='') {
	// retourne le combo des styles avec le style $style selectionné
	global $msg;
	$style = get_styles();
	$combo = "<select name='form_style' id='form_style' class='saisie-20em'>";
	while(list($cle, $valeur) = each($style)) {
        	$libelle = $valeur; 
        	if(strcmp($valeur, $dstyle) == 0)
            		$combo .= "<option value=\"$valeur\" selected='selected'>$libelle</option>";
        		else $combo .= "<option value=\"$valeur\">$libelle</option>";
    		}
    	$combo .= "</select>";
	return $combo;
	}

function make_user_tdoc_combo($typdoc=0) {
	global $dbh;
	global $msg;
	$requete = "SELECT idtyp_doc, tdoc_libelle FROM docs_type order by 2";
	$result = mysql_query($requete, $dbh);
	$combo = "<select name='form_deflt_tdoc' id='form_deflt_tdoc' class='saisie-30em'>";
	while($tdoc = mysql_fetch_object($result)) {
		if($tdoc->idtyp_doc != $typdoc) $combo .= "<option value='".$tdoc->idtyp_doc."'>".$tdoc->tdoc_libelle."</option>";
			else $combo .= "<option value='".$tdoc->idtyp_doc."' selected='selected'>".$tdoc->tdoc_libelle."</option>";
		}
	$combo .= "</select>";
	return $combo;
	}
