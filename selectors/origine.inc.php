<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: origine.inc.php,v 1.11 2010-02-23 16:43:48 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=origine&caller=$caller&sub=$sub&param1=$param1&param2=$param2&param3=$param3&param4=$param4&param5=$param5&param6=$param6&callback=$callback&filtre=$filtre";




// traitement en entrée des requêtes utilisateur
if ($deb_rech) $f_user_input = $deb_rech ;
if($f_user_input=="" && $user_input=="") {
	$user_input='';
} else {
	// traitement de la saisie utilisateur
	if ($user_input) $f_user_input=$user_input;
	if (($f_user_input)&&(!$user_input)) $user_input=$f_user_input;
}

// contenu popup sélection emprunteur
require('./selectors/templates/sel_origine.tpl.php');


//Récupération des pondérations de suggestions
$tab_poids = explode(",", $acquisition_poids_sugg);
$tab_poids[0] = substr($tab_poids[0], 2); //utilisateur
$tab_poids[1] = substr($tab_poids[1], 2); //abonné
$tab_poids[2] = substr($tab_poids[2], 2); //visiteur



switch ($sub) {
	
	case 'empr' :
		// affichage du header
		$sel_header = str_replace('!!is_current_empr!!', "class='sel_navbar_current'", $sel_header);
		$sel_header = str_replace('!!is_current_user!!', '', $sel_header);
		print $sel_header;

		$sel_search_form = str_replace("!!deb_rech!!", stripslashes($f_user_input), $sel_search_form);
		$sel_search_form = str_replace("!!sel_loc!!","", $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_empr_results($dbh, $user_input, $nbr_lignes, $page);
	break;

	case 'user' :
		// affichage du header
		$sel_header = str_replace('!!is_current_user!!', "class='sel_navbar_current'", $sel_header);
		$sel_header = str_replace('!!is_current_empr!!', '', $sel_header);
		print $sel_header;
		
		// affichage des membres de la page	
		$sel_search_form = str_replace("!!deb_rech!!", stripslashes($f_user_input), $sel_search_form);
		$sel_search_form = str_replace("!!sel_loc!!","", $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_user_results($dbh, $user_input, $nbr_lignes, $page);
	break;
	default :
		if($filtre == 'ONLY_EMPR'){
			// affichage du header
			$sel_header = str_replace('!!is_current_empr!!', "class='sel_navbar_current'", $sel_header);
			$sel_header = str_replace('!!is_current_user!!', '', $sel_header);
			print $sel_header;
			
			// Localisation de l'emprunteur
			if($pmb_lecteurs_localises){
				$req_loc = "select idlocation, location_libelle from docs_location";
				$res_loc = mysql_query($req_loc,$dbh);
				$sel_loc = "<select id='empr_loca' name='empr_loca' onchange='this.form.submit();return test_form(this.form);'>";
				$sel_loc .= "<option value='0' ".(!$empr_loca ? 'selected' : '').">".htmlentities($msg['demandes_localisation_all'],ENT_QUOTES,$charset)."</option>";
				while($loc = mysql_fetch_object($res_loc)){
					$sel_loc .= "<option value='".$loc->idlocation."' ".(($empr_loca==$loc->idlocation) ? 'selected' : '').">".htmlentities($loc->location_libelle,ENT_QUOTES,$charset)."</option>";
				}
				$sel_loc.= "</select>";
				$sel_search_form = str_replace("!!sel_loc!!",$sel_loc, $sel_search_form);
			} else $sel_search_form = str_replace("!!sel_loc!!","", $sel_search_form);
			
			// affichage des membres de la page	
			$sel_search_form = str_replace("!!deb_rech!!", stripslashes($f_user_input), $sel_search_form);
			print $sel_search_form;
			print $jscript;
			show_empr_results($dbh, $user_input, $nbr_lignes, $page);
		} else {
			// affichage du header
			$sel_header = str_replace('!!is_current_user!!', "class='sel_navbar_current'", $sel_header);
			$sel_header = str_replace('!!is_current_empr!!', '', $sel_header);
			print $sel_header;
			
			// affichage des membres de la page	
			$sel_search_form = str_replace("!!deb_rech!!", stripslashes($f_user_input), $sel_search_form);
			$sel_search_form = str_replace("!!sel_loc!!","", $sel_search_form);
			print $sel_search_form;
			print $jscript;
			show_user_results($dbh, $user_input, $nbr_lignes, $page);
		}
		break;

}


function show_empr_results($dbh, $user_input, $nbr_lignes=0, $page=0) {

	global $nb_per_page;
	global $base_url;
	global $caller;
 	global $charset;
 	global $tab_poids;
 	global $empr_loca;
 	

	$user_input = str_replace("*", "%", $user_input) ;
	if(strpos($user_input,',') !== false){
 		$tab_input = explode(",", $user_input);
 		$where = "empr_prenom like '%".$tab_input[0]."%' or empr_nom like '%".$tab_input[1]."%'";
	}  else {
		$where = "empr_prenom like '%".$user_input."%' or empr_nom like '%".$user_input."%' or empr_cb like '%".$user_input."%'";
 	}
 	
 	if($empr_loca){
 		$where_loc = " empr_location='$empr_loca' ";
 	} else {
 		$where_loc ="";
 	}
	// on récupére le nombre de lignes qui vont bien
	if(!$user_input) {
		$requete = "SELECT COUNT(1) FROM empr ".( $where_loc ? "WHERE ".$where_loc :"");
	} else {
		$requete = "SELECT COUNT(1) FROM empr WHERE ( $where ) ".( $where_loc ? "AND ".$where_loc :"");
	}

	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);

	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on lance la vraie requête
		if(!$user_input) {
			$requete = "SELECT id_empr, empr_nom, empr_prenom, empr_location FROM empr ".( $where_loc ? "WHERE ".$where_loc :"")." ORDER BY empr_nom, empr_prenom LIMIT $debut,$nb_per_page ";
		} else {
			$requete = "SELECT id_empr, empr_nom, empr_prenom FROM empr WHERE ( $where ) ".( $where_loc ? "AND ".$where_loc :"");
			$requete .= "ORDER BY empr_nom, empr_prenom LIMIT $debut,$nb_per_page ";
		}

		$res = @mysql_query($requete, $dbh);
		while(($empr=mysql_fetch_object($res))) {
            $empr_entry = $empr->empr_nom;
            if($empr->empr_prenom) $empr_entry = $empr->empr_prenom.' '.$empr_entry;
            $location = ( $empr->empr_location ? $empr->empr_location :  $em);
            print pmb_bidi("
 			<a href='#' onclick=\"set_parent('$caller', '$empr->id_empr', '".htmlentities(addslashes($empr_entry),ENT_QUOTES, $charset)."', '1', '".$tab_poids[1]."','".$location."') \">
				$empr_entry</a>");
			print "<br />";
		}
		mysql_free_result($res);

		// constitution des liens

		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;

		// affichage du lien précédent si nécéssaire
		print '<hr /><div align=center>';
		if($user_input == '%') $user_input = "*";
		if($precedente > 0)
		print "<a href='$base_url&page=$precedente&nbr_lignes=$nbr_lignes&user_input=$user_input&empr_loca=$empr_loca'><img src='./images/left.gif' border='0' title='$msg[48]' alt='[$msg[48]]' hspace='3' align='middle' /></a>";
		for($i = 1; $i <= $nbepages; $i++) {
			if($i==$page)
				print "<b>$i/$nbepages</b>";
			}

		if($suivante<=$nbepages)
			print "<a href='$base_url&page=$suivante&nbr_lignes=$nbr_lignes&user_input=$user_input&empr_loca=$empr_loca'><img src='./images/right.gif' border='0' title='$msg[49]' alt='[$msg[49]]' hspace='3' align='middle' /></a>";
		}
		print '</div>';
}



function show_user_results($dbh, $user_input, $nbr_lignes=0, $page=0) {

	global $nb_per_page;
	global $base_url;
	global $caller;
 	global $charset;
 	global $tab_poids;


	$user_input = str_replace("*", "%", $user_input) ;
	if(strpos($user_input,',') !== false){
	 	$tab_input = explode(",", $user_input);
		$where = "prenom like '%".$tab_input[0]."%' or nom like '%".$tab_input[1]."%'";
	} else {
		$where = "prenom like '%".$user_input."%' or nom like '%".$user_input."%'";
	}
	// on récupére le nombre de lignes qui vont bien
	if(!$user_input) {
		$requete = "SELECT COUNT(1) FROM users ";
	} else {
		$requete = "SELECT COUNT(1) FROM users WHERE $where ";
	}

	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);

	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
		// on lance la vraie requête

		if(!$user_input) {
			$requete = "SELECT userid, nom, prenom FROM users ORDER BY nom, prenom LIMIT $debut,$nb_per_page ";
		} else {
			$requete = "SELECT userid, nom, prenom FROM users WHERE $where ";
			$requete .= "ORDER BY nom, prenom LIMIT $debut,$nb_per_page ";
		}

		$res = @mysql_query($requete, $dbh);
		while(($row_user=mysql_fetch_object($res))) {
            $user_entry = $row_user->nom;
            if($row_user->prenom) $user_entry = $row_user->prenom.' '.$user_entry;
            print pmb_bidi("
 			<a href='#' onclick=\"set_parent('$caller', '$row_user->userid', '".htmlentities(addslashes($user_entry),ENT_QUOTES, $charset)."', '0', '".$tab_poids[0]."' )\">
				$user_entry</a>");
			print "<br />";
		}
		mysql_free_result($res);

		// constitution des liens

		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;

		// affichage du lien précédent si nécéssaire
		print '<hr /><div align=center>';
		if($precedente > 0)
		print "<a href='$base_url&page=$precedente&nbr_lignes=$nbr_lignes&user_input=$user_input'><img src='./images/left.gif' border='0' title='$msg[48]' alt='[$msg[48]]' hspace='3' align='middle' /></a>";
		for($i = 1; $i <= $nbepages; $i++) {
			if($i==$page)
				print "<b>$i/$nbepages</b>";
			}

		if($suivante<=$nbepages)
			print "<a href='$base_url&page=$suivante&nbr_lignes=$nbr_lignes&user_input=$user_input'><img src='./images/right.gif' border='0' title='$msg[49]' alt='[$msg[49]]' hspace='3' align='middle' /></a>";
		}
		print '</div>';
}




print $sel_footer;
