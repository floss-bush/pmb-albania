<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr.inc.php,v 1.13 2010-05-07 07:35:57 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

include_once("$include_path/empr_cart.inc.php");
include_once("$class_path/empr_caddie.class.php");

if ($item) {
	print "<h3>".$msg["print_cart_title"]."</h3>\n";
	$emprunteur = new emprunteur($item,'', FALSE, 0);
	print '<strong>'.$emprunteur->prenom." ".$emprunteur->nom.'</strong><br />';
}	
switch ($action) {
	case 'add_item':
		if($idemprcaddie)$caddie[0]=$idemprcaddie;
		foreach($caddie  as $idemprcaddie) {
			$myCart = new empr_caddie($idemprcaddie);
			$myCart->add_item($item);
			$myCart->compte_items();
		}	
		print "<script type='text/javascript'>window.close();</script>"; 
		break;
	case 'new_cart':
	 	$select_cart="
		<select name='cart_type'>
			<option value='EMPR' selected>$msg[caddie_de_EMPR] </option>
		</select>";
	 	$c_form=str_replace('!!cart_type_select!!', $select_cart, $cart_form);
	 	$memo_contexte="<input type='hidden' name='memo_clause' value=\"".htmlentities($clause, ENT_QUOTES, $charset)."\">";
	 	$memo_contexte.="<input type='hidden' name='memo_filtered_query' value=\"".htmlentities($filtered_query, ENT_QUOTES, $charset)."\">";
	 	$c_form=str_replace('<!--memo_contexte-->', $memo_contexte, $c_form);
		print $c_form;
		break;
	case 'del_cart':
	case 'valid_new_cart':		


	case 'add_result':
		// si on est passé par le formulaire de création, on récupère les clauses
		if($memo_clause) $clause=stripslashes($memo_clause);
		if($memo_filtered_query) $filtered_query=stripslashes($memo_filtered_query);
		
		if ($sub_action=='update') {
			if($item) {
				if($idemprcaddie)$caddie[0]=$idemprcaddie;
				foreach($caddie  as $idemprcaddie) {
					$myCart = new empr_caddie($idemprcaddie);
					$myCart->add_item($item);
					$myCart->compte_items();
				}		
			}elseif (($empr_sort_rows)||($empr_show_rows)||($empr_filter_rows)) {
				require_once("$class_path/filter_list.class.php");
				$filter=new filter_list("empr","empr_list",$empr_show_rows,$empr_filter_rows,$empr_sort_rows);
				
				$requete = "SELECT id_empr FROM empr ".stripslashes(rawurldecode($clause));
				$filter->original_query=$requete;
				if($idemprcaddie)$caddie[0]=$idemprcaddie;
				if ($caddie) {
					foreach($caddie  as $idemprcaddie) {							
						$filter->filtered_query = stripslashes(rawurldecode($filtered_query));
						$filter->activate_filters();
						if (!$filter->error) {	
							if ($filter->t_query) {
								$myCart = new empr_caddie($idemprcaddie);
								while ($r=mysql_fetch_object($filter->t_query)) {
									$myCart->add_item($r->id_empr);
								} // fin while
								$myCart->compte_items();
							}							
						} // fin if filter->t_query	
					} // fin if idemprcaddie
				} // fin if !$filster->error
			}
			print "<script type='text/javascript'>window.close();</script>"; 
		} else {
				print "<h3>".$msg["print_cart_title"]."</h3>\n";						
				aff_paniers_empr($item, "./cart.php?object_type=EMPR", "add_result&sub_action=update&clause=".rawurlencode(stripslashes($clause))."&filtered_query=".rawurlencode(stripslashes($filtered_query)),$msg["caddie_add_EMPR"], "", 0, 0, 1,"&clause=".rawurlencode(stripslashes($clause))."&filtered_query=".rawurlencode(stripslashes($filtered_query)));
		}
		break;
	case 'add_empr_limite':
		if ($sub_action=='add' && count($caddie)) {
			// restriction localisation le cas échéant
			if ($pmb_lecteurs_localises) {
				if ($empr_location_id=="") $empr_location_id = $deflt2docs_location ;
				if ($empr_location_id!=0) $restrict_localisation = " AND empr_location='$empr_location_id' ";
				else $restrict_localisation = "";
			}

			$requete = "SELECT id_empr FROM empr where ((to_days(empr_date_expiration) - to_days(now()) ) <=  $pmb_relance_adhesion ) and empr_date_expiration >= now() $restrict_localisation";
			$res_rqt=mysql_query($requete);
			foreach ( $caddie as $id_caddie => $coche) {
       			if($coche){
       				$myCart = new empr_caddie($id_caddie);
					while ($r=mysql_fetch_object($res_rqt)) {
						$myCart->add_item($r->id_empr);
					} // fin while
					mysql_data_seek($res_rqt,0);
       			}
			}
			print "<script type='text/javascript'>window.close();</script>"; 
		} else {
			// function aff_paniers_empr($item=0, $lien_origine="./circ.php?", $action_click = "add_item", $titre="", $restriction_panier="", $lien_edition=0, $lien_suppr=0, $lien_creation=1) {
			aff_paniers_empr(0, "./cart.php?object_type=EMPR&sub_action=add&empr_location_id=$empr_location_id", "add_empr_limite",$msg["caddie_add_emprs"] , "", 0, 0, 0);
		}
		break;
	case 'add_empr_depasse':
		if ($sub_action=='add' && count($caddie)) {
			// restriction localisation le cas échéant
			if ($pmb_lecteurs_localises) {
				if ($empr_location_id=="") $empr_location_id = $deflt2docs_location ;
				if ($empr_location_id!=0) $restrict_localisation = " AND empr_location='$empr_location_id' ";
				else $restrict_localisation = "";
			}
			$requete = "SELECT id_empr FROM empr where empr_date_expiration < now() $restrict_localisation";
			$res_rqt=mysql_query($requete);
			foreach ( $caddie as $id_caddie => $coche) {
       			if($coche){
       				$myCart = new empr_caddie($id_caddie);
					while ($r=mysql_fetch_object($res_rqt)) {
						$myCart->add_item($r->id_empr);
					} // fin while
					mysql_data_seek($res_rqt,0);
       			}
			}
			//$myCart->compte_items();
			print "<script type='text/javascript'>window.close();</script>"; 
		} else {
			// function aff_paniers_empr($item=0, $lien_origine="./circ.php?", $action_click = "add_item", $titre="", $restriction_panier="", $lien_edition=0, $lien_suppr=0, $lien_creation=1) {
			aff_paniers_empr(0, "./cart.php?object_type=EMPR&sub_action=add&empr_location_id=$empr_location_id", "add_empr_depasse",$msg["caddie_add_emprs"] , "", 0, 0, 0);
		}
		break;
	case 'add_empr_encours':
		if ($sub_action=='add' && count($caddie)) {
			// restriction localisation le cas échéant
			if ($pmb_lecteurs_localises) {
				if ($empr_location_id=="") $empr_location_id = $deflt2docs_location ;
				if ($empr_location_id!=0) $restrict_localisation = " AND empr_location='$empr_location_id' ";
				else $restrict_localisation = "";
			}

			$requete = "SELECT id_empr FROM empr where empr_date_expiration >= now() $restrict_localisation";
			$res_rqt=mysql_query($requete);
			foreach ( $caddie as $id_caddie => $coche) {
       			if($coche){
       				$myCart = new empr_caddie($id_caddie);
					while ($r=mysql_fetch_object($res_rqt)) {
						$myCart->add_item($r->id_empr);
					} // fin while
					mysql_data_seek($res_rqt,0);
       			}
			}
			print "<script type='text/javascript'>window.close();</script>"; 
		} else {
			// function aff_paniers_empr($item=0, $lien_origine="./circ.php?", $action_click = "add_item", $titre="", $restriction_panier="", $lien_edition=0, $lien_suppr=0, $lien_creation=1) {
			aff_paniers_empr(0, "./cart.php?object_type=EMPR&sub_action=add&empr_location_id=$empr_location_id", "add_empr_encours",$msg["caddie_add_emprs"] , "", 0, 0, 0);
		}
		break;
	default:
//		print "<h1>".$msg["fonct_no_accessible"]."</h1>";
		aff_paniers_empr($item, "./cart.php?object_type=EMPR", "add_item",$msg["caddie_add_EMPR"], "", 0, 0, 1);
		break;
		
	} 


