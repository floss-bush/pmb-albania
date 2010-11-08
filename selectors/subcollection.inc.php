<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: subcollection.inc.php,v 1.25 2010-07-28 07:44:39 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// sélecteur pour sous collection

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=subcollection&caller=$caller&mode=$mode&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn";

// contenu popup sélection collection
require('./selectors/templates/sel_sub_collection.tpl.php');

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

if($bt_ajouter == "no"){
	$bouton_ajouter="";
}else{
	$bouton_ajouter= "<input type='button' class='bouton_small' onclick=\"document.location='$base_url&action=add'\" value='$msg[176]' />";
}

// fonctions d'affichage des membres de la page
switch($action){
	case 'add':
		print $sub_collection_form;
		break;
	case 'update':
		require_once("$class_path/editor.class.php");
		require_once("$class_path/collection.class.php");
		require_once("$class_path/subcollection.class.php");
		$value['name']		=	$collection_nom;
		$value['parent']	=	$coll_id;
		$value['issn'] = $issn;
		$collection = new subcollection();
		$collection->update($value);
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $collection_nom, 0, 0, $collection->id);
		break;
	default:
		$sel_search_form = str_replace("!!bouton_ajouter!!", $bouton_ajouter, $sel_search_form);
		$sel_search_form = str_replace("!!deb_rech!!", htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset), $sel_search_form);
		print $sel_search_form;
		print $jscript;
		show_results($dbh, $user_input, $nbr_lignes, $page);
		break;
}

function show_results($dbh, $user_input, $nbr_lignes=0, $page=0, $id = 0) {
	global $nb_per_page;
	global $base_url;
	global $caller;
	global $msg;
	global $charset;
	
	// on récupére le nombre de lignes qui vont bien
	if (!$id) {
		if($user_input=="") $requete = "SELECT COUNT(1) FROM sub_collections where sub_coll_id!='$no_display' "; 
			else {
				$aq=new analyse_query(stripslashes($user_input));
				if ($aq->error) {
					error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
					exit;
				}
				$requete=$aq->get_query_count("sub_collections","sub_coll_name","index_sub_coll","sub_coll_id","sub_coll_id!='$no_display' ");
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
				$requete = "SELECT A.*,B.*,C.* FROM sub_collections A, collections B, publishers C";
				$requete .= " WHERE sub_coll_id!='$no_display' and A.sub_coll_parent=B.collection_id";
				$requete .= " AND B.collection_parent=C.ed_id";
				$requete .= " ORDER BY A.sub_coll_name LIMIT $debut,$nb_per_page ";
			} else {
				$members=$aq->get_query_members("sub_collections","sub_coll_name","index_sub_coll","sub_coll_id");
				$requete = "select sub_collections.*,collections.*,publishers.*, ".$members["select"]." as pert from sub_collections, collections, publishers ";
				$requete.="where ".$members["where"]." and sub_coll_id!='$no_display' and sub_coll_parent=collection_id and collection_parent=ed_id group by sub_coll_id order by pert desc,index_sub_coll, index_coll, index_publisher limit $debut,$nb_per_page";
			}
		} else $requete="select sub_collections.*,collections.*,publishers.* from sub_collections,collections,publishers where sub_coll_id='".$id."' and sub_coll_parent=collection_id and collection_parent=ed_id group by sub_coll_id";	
		$res = @mysql_query($requete, $dbh);
		while(($col=mysql_fetch_object($res))) {
			$idsubcoll = $col->sub_coll_id;
			$libellesubcoll = htmlentities(addslashes($col->sub_coll_name),ENT_QUOTES,$charset);
			$idparentcoll = $col->sub_coll_parent;
			$idparentlibelle = htmlentities(addslashes($col->collection_name),ENT_QUOTES,$charset);
			$idediteur = $col->ed_id;
			$libelleediteur = htmlentities(addslashes($col->ed_name),ENT_QUOTES,$charset);
			print pmb_bidi("
			<a href='#' onclick=\"set_parent('$caller', $idsubcoll, '".$libellesubcoll."', $idparentcoll, '".$idparentlibelle."', $idediteur, '".$libelleediteur."')\">
				$col->sub_coll_name</a>");
			print pmb_bidi("&nbsp;($col->collection_name.&nbsp;$col->ed_name)<br />");
		}
		mysql_free_result($res);

		// constitution des liens
		$nbepages = ceil($nbr_lignes/$nb_per_page);
		$suivante = $page+1;
		$precedente = $page-1;

		// affichage pagination
		print "<div class='row'>&nbsp;<hr /></div><div align='center'>";
		$url_base = $base_url."&user_input=".rawurlencode(stripslashes($user_input));
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		print $nav_bar;
		print "</div>";
	}
}

print $sel_footer;
