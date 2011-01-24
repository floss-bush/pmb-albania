<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: indexint.inc.php,v 1.34 2010-12-15 13:37:03 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion d'un élément à ne pas afficher
if (!$no_display) $no_display=0;
// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=indexint&caller=$caller&param1=$param1&param2=$param2&no_display=$no_display&bt_ajouter=$bt_ajouter&typdoc=$typdoc&f_user_input=$f_user_input&dyn=$dyn&callback=$callback&infield=$infield";
if (!$id_pclass && !$num_pclass && $thesaurus_classement_defaut){
	$id_pclass=$thesaurus_classement_defaut;
}elseif (!$id_pclass && $num_pclass){
	$id_pclass=$num_pclass;
}
	
if ($thesaurus_classement_mode_pmb) { //classement indexation décimale autorisé en parametrage
	$q = "select id_pclass,name_pclass from pclassement where typedoc like '%$typdoc%' order by name_pclass";
	$r = mysql_query($q, $dbh);	
	
	$toprint_typdocfield = "<select id='id_pclass' name='id_pclass' ";
	$toprint_typdocfield.= "onchange = \"document.location = '".$base_url."&id_pclass='+document.getElementById('id_pclass').value; \">" ;
	$pclass_url="&typdoc=$typdoc";
	$nb=0;

	while ($row = mysql_fetch_object($r)) {
		$toprint_typdocfield .= "<option value='$row->id_pclass'";
		//Si $id_pclass pas défini, prendre l'id par défaut
		if ($id_pclass==$row->id_pclass) {
			$toprint_typdocfield .=" selected";
			$pclass_url.="&id_pclass=$id_pclass";	
			$id_pclass=$row->id_pclass;		
		}
		$pclassid=$row->id_pclass;
		$pclass_name=$row->name_pclass;
		$toprint_typdocfield .= ">".$row->name_pclass."</option>\n";
		$nb++;
	}
	$toprint_typdocfield .= "</select>";
	//Si qu'un classement de trouvé, pas de choix à afficher
	if ($nb==1) {
		$toprint_typdocfield="[$pclass_name]";
		$id_pclass=$pclassid;
	}
	if ($nb<1) $toprint_typdocfield='';
	
	// puger le [..] envoyé en rech par l'appel du popup:
	if (strpos($deb_rech,"]")) $deb_rech=substr($deb_rech,strpos($deb_rech,"]")+2);
		
} else {
	$pclass_url="&id_pclass=$thesaurus_classement_defaut";	
}
$base_url .= $pclass_url;

// contenu popup sélection 
require('./selectors/templates/sel_indexint.tpl.php');
$sel_search_form = str_replace("!!pclassement!!", $toprint_typdocfield, $sel_search_form);	
// affichage du header
print $sel_header;

// traitement en entrée des requêtes utilisateur
if ($deb_rech) $f_user_input = $deb_rech ;
if($f_user_input=="" && $user_input=="") {
	$user_input='';
} else {
	// traitement de la saisie utilisateur
	if ($user_input) $f_user_input=$user_input;
	if (($f_user_input)&&(!$user_input)) $user_input=$f_user_input;
}
// affichage des membres de la page

if($bt_ajouter == "no"){
	$bouton_ajouter="";
}else{
	$bouton_ajouter= "<div class='row'><input type='button' class='bouton_small' onclick=\"document.location='$base_url&action=add$pclass_url'\" value='$msg[indexint_create_button]'></div>";
}

switch($action){
	case 'add':
		print $indexint_form;
		break;
	case 'update':
		$value=	$indexint_nom;
		require_once("$class_path/indexint.class.php");
		$indexint = new indexint(0);
		$indexint->update($value,$indexint_comment,$id_pclass);
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		if ((string)$exact=="") $exact=1;
		if ($exact) {
			$sel_search_form = str_replace("!!check1!!", "checked", $sel_search_form);
			$sel_search_form = str_replace("!!check0!!", "", $sel_search_form);
		} else {
			$sel_search_form = str_replace("!!check1!!", "", $sel_search_form);
			$sel_search_form = str_replace("!!check0!!", "checked", $sel_search_form);
		}
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $indexint_nom, 0, 0, $indexint->indexint_id);
		break;
	default:
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		if ((string)$exact=="") $exact=1;
		if ($exact) {
			$sel_search_form = str_replace("!!check1!!", "checked", $sel_search_form);
			$sel_search_form = str_replace("!!check0!!", "", $sel_search_form);
		} else {
			$sel_search_form = str_replace("!!check1!!", "", $sel_search_form);
			$sel_search_form = str_replace("!!check0!!", "checked", $sel_search_form);
		}
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $user_input, $nbr_lignes, $page);
		break;
	}

function show_results($dbh, $user_input, $nbr_lignes=0, $page=0, $id = 0) {
	global $nb_per_page;
	global $base_url;
	global $caller;
	global $no_display;
	global $exact;
	global $charset;
	global $msg ;
	global $thesaurus_classement_mode_pmb,$thesaurus_classement_defaut,$id_pclass,$typdoc;
	global $callback;

	
	if ($thesaurus_classement_mode_pmb != 0) { //classement indexation décimale autorisé en parametrage
		$pclass_and_req=" and num_pclass='$id_pclass' and id_pclass = num_pclass ";
		$pclass_url="&id_pclass=$id_pclass";
	} else {
		$pclass_and_req=" and num_pclass='$thesaurus_classement_defaut' and id_pclass = num_pclass";
		$pclass_url="&id_pclass=$thesaurus_classement_defaut";		
	}
	// on récupére le nombre de lignes qui vont bien
	if (!$id) {
		if($user_input=="") {
			$requete = "SELECT COUNT(1) FROM indexint,pclassement where indexint_id!='$no_display' $pclass_and_req ";
		} else {
			if (!$exact) {
				$aq=new analyse_query(stripslashes($user_input));
				if ($aq->error) {
					error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
					exit;
				}
				$requete=$aq->get_query_count("indexint, pclassement","concat(indexint_name,' ',indexint_comment)","index_indexint","indexint_id","indexint_id!='$no_display' $pclass_and_req");
			} else {
				$requete="select count(distinct indexint_id) from indexint,pclassement where indexint_name like '".str_replace("*","%",$user_input)."' and indexint_id!='$no_display' $pclass_and_req";
			}
		}
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = @mysql_result($res, 0, 0);
	} else $nbr_lignes=1;
	
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;
	if($nbr_lignes) {
		// on lance la vraie requête
		if (!$id) {
			if($user_input=="") {
				$requete = "SELECT * FROM indexint,pclassement where indexint_id!='$no_display' $pclass_and_req ";
				$requete .= "ORDER BY indexint_name LIMIT $debut,$nb_per_page ";
			} else {
				if (!$exact) {
					$members=$aq->get_query_members("indexint","concat(indexint_name,' ',indexint_comment)","index_indexint","indexint_id");
					$requete="select *,".$members["select"]." as pert from indexint,pclassement where ".$members["where"]." and indexint_id!='$no_display' $pclass_and_req group by indexint_id order by pert desc, index_indexint limit $debut,$nb_per_page";
				} else {
					$requete="select * from indexint,pclassement where indexint_name like '".str_replace("*","%",$user_input)."' and indexint_id!='$no_display' $pclass_and_req group by indexint_id order by indexint_name limit $debut,$nb_per_page";
				}
			}
		} else $requete="select * from indexint,pclassement where indexint_id='".$id."' $pclass_and_req";
		$res = @mysql_query($requete, $dbh);
		while(($indexint=mysql_fetch_object($res))) {
			if ($indexint->indexint_comment) $entry = $indexint->indexint_name." - ".$indexint->indexint_comment;
			else $entry = $indexint->indexint_name ;
			if ($thesaurus_classement_mode_pmb != 0) { //classement indexation décimale autorisé en parametrage
				$entry="[".$indexint->name_pclass."] ".$entry;
			}
			print pmb_bidi("
			<a href='#' onclick=\"set_parent('$caller', '$indexint->indexint_id', '".htmlentities(addslashes(str_replace("\r"," ",str_replace("\n"," ",$entry))),ENT_QUOTES,$charset)."','$callback')\">
				$entry</a>");
			print "<br />";
		}
		mysql_free_result($res);

		// constitution des liens
		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;

		// affichage du lien précédent si nécéssaire
		print '<hr /><div align=center>';
		if($precedente > 0) {
			print "<a href='$base_url&page=$precedente&nbr_lignes=$nbr_lignes".$pclass_url."&user_input=".rawurlencode(stripslashes($user_input))."&exact=$exact'><img src='./images/left.gif' border='0' title='$msg[48]' alt='[$msg[48]]' hspace='3' align='middle' /></a>";
		}
		for($i = 1; $i <= $nbepages; $i++) {
			if($i==$page) print "<b>$i/$nbepages</b>";
		}

		if($suivante<=$nbepages)
			print "<a href='$base_url&page=$suivante&nbr_lignes=$nbr_lignes".$pclass_url."&user_input=".rawurlencode(stripslashes($user_input))."&exact=$exact'><img src='./images/right.gif' border='0' title='$msg[49]' alt='[$msg[49]]' hspace='3' align='middle' /></a>";
	}
	print '</div>';
}
print $sel_footer;