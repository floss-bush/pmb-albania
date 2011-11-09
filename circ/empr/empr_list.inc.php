<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_list.inc.php,v 1.48.4.1 2011-05-30 12:51:20 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ("$class_path/emprunteur.class.php");
require_once ("$class_path/docs_location.class.php");
require_once("$class_path/empr_caddie.class.php");

function iconepanier($id_emprunteur) {
	global $empr_show_caddie;
	global $selector_prop_ajout_caddie_empr;
	global $msg;
	$img_ajout_empr_caddie="";
	if ($empr_show_caddie) {
		$img_ajout_empr_caddie = "\n<td><img src='./images/basket_empr.gif' align='middle' alt='basket' title=\"${msg[400]}\" ";
		$img_ajout_empr_caddie .= "onmousedown=\"if (event) e=event; else e=window.event; if (e.target) elt=e.target; else elt=e.srcElement; e.cancelBubble = true; if (e.stopPropagation) e.stopPropagation(); if (elt.nodeName=='IMG') openPopUp('./cart.php?object_type=EMPR&item=".$id_emprunteur."', 'cart', 600, 700 , -2, -2, '$selector_prop_ajout_caddie_empr'); return false;\" style=\"cursor: pointer\"></td>\n";
	}
	return $img_ajout_empr_caddie;
}

function get_nbpret($id_emprunteur){	
	global $dbh, $msg;
	
	$rqt = "select count(pret_idexpl) as prets from empr left join pret on pret_idempr=id_empr where id_empr='".$id_emprunteur."' group by id_empr";
	$res = mysql_query($rqt,$dbh);
	$nb = mysql_fetch_object($res);
	
	return "<td>".$msg['empr_nb_pret']." : ".$nb->prets."</td>";
}

// nombre de références par pages
if ($nb_per_page_empr != "")
	$nb_per_page = $nb_per_page_empr ;
else 
	$nb_per_page = 10;
if ($form_cb) {
	$clause = "WHERE empr_nom like '".str_replace("*", "%", $form_cb)."%' " ;
}
if ($empr_location_id && $pmb_lecteurs_localises) 
	$clause .= " and empr_location='$empr_location_id'" ;
	 
// on récupére le nombre de lignes qui vont bien
if (!$nbr_lignes) {
	$requete = "SELECT COUNT(1) FROM empr $clause ";
	//print "<h1>$requete</h1>";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);
}

if (!$page) $page=1;
$debut =($page-1)*$nb_per_page;

if ($nbr_lignes == 1) {
	// on lance la vraie requête
	$requete = "SELECT id_empr as id FROM empr $clause ";
	$res = @mysql_query($requete, $dbh);

	$id = @mysql_result($res, '0', 'id');
	if ($id) {
		$erreur_affichage="<table border='0' cellpadding='1' >
		<tr><td width='33'>&nbsp;<span>&nbsp;</span></td>
				<td width='100%'>";
		$erreur_affichage.="&nbsp;<span>&nbsp;</span>";
		$erreur_affichage.="</td></tr></table>";
		if ($id_notice) {
			echo "<script> parent.location.href='./circ.php?categ=resa&id_empr=$id&groupID=$groupID&id_notice=$id_notice'; </script>";
		} elseif($id_bulletin) {
			echo "<script> parent.location.href='./circ.php?categ=resa&id_empr=$id&groupID=$groupID&id_bulletin=$id_bulletin'; </script>";
		} else {
			$empr = new emprunteur($id, $erreur_affichage, FALSE, 1);
			$affichage = $empr->fiche;
		}
	}
	
} else if($nbr_lignes) {
	if ($empr_location_id && $pmb_lecteurs_localises) {
		$docs_location=new docs_location($empr_location_id);
		$where_intitule=$msg["empr_location_intitule"]. " \"".$docs_location->libelle."\"";
	} else 
		$where_intitule="";
	if (($empr_sort_rows)||($empr_show_rows)||($empr_filter_rows)) {
		$url_base = "./circ.php?categ=pret&form_cb=".rawurlencode($form_cb)."&id_notice=$id_notice"."&id_bulletin=$id_bulletin&empr_location_id=$empr_location_id";	
		require_once($class_path."/filter_list.class.php");
		$filter=new filter_list("empr","empr_list",$empr_show_rows,$empr_filter_rows,$empr_sort_rows);
		if (!$empr_location_id) $empr_location_id=-1;
		if (array_search("l",explode(",",$empr_filter_rows))!==FALSE) {
			$lo="f".$filter->fixedfields["l"]["ID"];
			global $$lo;
			if (!$$lo) {
				$tableau=array();
				$tableau[0]=$empr_location_id;
				$$lo=$tableau;
			}
		}
		$requete = "SELECT id_empr,empr_cb,empr_nom,empr_prenom,empr_adr1,empr_ville,empr_year FROM empr $clause group by id_empr ORDER BY empr_nom, empr_prenom "; 
		$filter->original_query=$requete;
		$filter->page=$page;
		$filter->nb_per_page=$nb_per_page;
		$filter->multiple=1;
		$t=array();
		$t["row_even"]["style"]="cursor: pointer";
		$t["row_even"]["class"]="even";
		$t["row_odd"]["class"]="odd";
		$t["row_odd"]["style"]="cursor: pointer";
		$t["cols"][0]["style"]="font-weight: bold";
		$filter->css=$t;
		$t=array();
		if ($id_notice || $id_bulletin) {
			// si on est en résa on a un id de notice ou de bulletin
			$t["row"]["onmousedown"]="document.location=\"./circ.php?categ=resa&id_empr=!!id_empr!!&groupID=$groupID&id_notice=$id_notice&id_bulletin=$id_bulletin\";";	
		} else {
			$t["row"]["onmousedown"]="document.location=\"./circ.php?categ=pret&form_cb=!!b!!\";";
		}
		$t["row"]["onmouseout"]="this.className='!!parity!!'";
		$filter->scripts=$t;
		$filter->activate_filters();
		$filtered_query = $filter->filtered_query;
		// ER : trouver ici nbr_lignes
		$nbr_lignes = $filter->nb_lines_query();
		if (!$filter->error) {
			$aff_filters="<script type='text/javascript' src='./javascript/tablist.js'></script><form class='form-$current_module' id='form_filters' name='form_filters' method='post' action='".$url_base."&nb_per_page=$nb_per_page' onSubmit='this.page.value=\"1\";'><h3>".$msg["filters_tris"]."</h3>";
			$aff_filters.="<div class='form-contenu'><input type='hidden' name='page' value='$page'>
							<div id=\"el1Parent\" class=\"notice-parent\"><img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el1Img\" title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase('el1', true); return false;\">
   								<b>".$msg["filters"]."</b></div>
						<div id=\"el1Child\" style=\"margin-left:7px;display:none;\">";
			$aff_filters.=$filter->display_filters();
			$aff_filters.="</div><div class='row'></div><div id=\"el2Parent\" class=\"notice-parent\"><img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el2Img\" title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase('el2', true); return false;\">
							<b>".$msg["tris_dispos"]."</b></div>
							<div id=\"el2Child\" style=\"margin-left:7px;display:none;\">";
			$aff_filters.=$filter->display_sort();
			$aff_filters.="</div></div><div class='row'></div><div class='row'><input type='submit' class='bouton' value='".$msg["empr_sort_filter_button"]."'>";
			if ($empr_show_caddie)
				 $aff_filters.="&nbsp;&nbsp;<input type='button' class='bouton' value='".$msg["add_empr_cart"]."' onClick=\"openPopUp('./cart.php?object_type=EMPR&action=add_result&clause=".rawurlencode($clause)."&filtered_query=".rawurlencode($filtered_query)."', 'cart', 600, 700, -2, -2, '$selector_prop_ajout_caddie_empr'); return false;\">";
			$aff_filters.="</div></form>";
			$aff_filters.="<br />".$filter->make_human_filters();
			$empr_list_tmpl=str_replace("!!filters_list!!",$aff_filters,$empr_list_tmpl);
			$nav_bar=$filter->display_pager();
			$empr_list=$filter->display_result();
		} else $empr_list_tmpl=str_replace("!!filters_list!!",$filter->error_message,$empr_list_tmpl);
	} else {
		// on lance la vraie requête
		$requete = "SELECT *, count(pret_idexpl) as nb_pret FROM empr left join pret on id_empr=pret_idempr $clause group by id_empr ORDER BY empr_nom, empr_prenom LIMIT $debut,$nb_per_page ";
		$res = @mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($res);
		$parity = 0;
		$empr_list .= "<table border='0' width='100%'>";
		while(($empr=mysql_fetch_object($res))) {
			$recherche_groupe=@mysql_query("SELECT libelle_groupe FROM empr_groupe, groupe WHERE empr_id=".$empr->id_empr." AND groupe_id=id_groupe ORDER BY libelle_groupe");
			$grp=array();
			while ($gr=mysql_fetch_object($recherche_groupe)) {
				$grp[]=$gr->libelle_groupe;
			}
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			if ($id_notice || $id_bulletin) {
				// si on est en résa on a un id de notice ou de bulletin
				$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./circ.php?categ=resa&id_empr=$empr->id_empr&groupID=$groupID&id_notice=$id_notice&id_bulletin=$id_bulletin';\" ";
			} else {
				$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./circ.php?categ=pret&form_cb=".rawurlencode($empr->empr_cb)."';\" ";
			}
			// **************** ajout icône ajout panier
			$img_ajout_empr_caddie = iconepanier($empr->id_empr);
			
			$empr_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
			$empr_list .= "
				<td>
					<strong>$empr->empr_cb</strong>
				</td>
				<td>
       				$empr->empr_nom&nbsp;$empr->empr_prenom
				</td>
				<td>
					<b>".implode("/",$grp)."</b>
				</td>
				<td>
					$empr->empr_adr1
				</td>
				<td>
					$empr->empr_ville
				</td>
				<td>
					$empr->empr_year
				</td>	
				<td> $msg[empr_nb_pret]"." : "."
					$empr->nb_pret
				</td>
				$img_ajout_empr_caddie
				</tr>";
			$parity += 1;
			}
		mysql_free_result($res);
		$empr_list .= "</table>";
		if ($id_notice || $id_bulletin) {
			// si on est en résa on a un id de notice ou de bulletin
			$url_base = "./circ.php?categ=resa&id_empr=$id&groupID=$groupID&id_notice=$id_notice&id_bulletin=$id_bulletin";	
		} else {
			$url_base = "./circ.php?categ=pret&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."&empr_location_id=$empr_location_id";
		}
		$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
		$empr_list_tmpl=str_replace("!!filters_list!!","",$empr_list_tmpl);
	}
	// affichage du résultat
	list_empr($form_cb, $empr_list, $nav_bar, $nbr_lignes, $where_intitule);

} else {
	// la requête de recherche d'emprunteur n'a produit aucun résultat
	if ($id_notice || $id_bulletin) {
		// si on est en résa on a un id de notice ou de bulletin
		get_cb( $msg['reserv_doc'], $msg[34], $msg[circ_tit_form_cb_empr], "./circ.php?categ=pret&id_notice=".$id_notice."&id_bulletin=$id_bulletin", 0);
	} else {
		get_cb(	$msg[13], $msg[34], $msg[circ_tit_form_cb_empr], "./circ.php?categ=pret", 0, 0);
	}
	error_message($msg[46], str_replace('!!form_cb!!', $form_cb, $msg[47]), 0, './circ.php');
	}
