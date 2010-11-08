<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serial_func.inc.php,v 1.69 2009-12-10 14:37:17 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/docs_location.class.php");

// résultat de recherche pour gestion des périodiques
function show_serial_info($serial_id, $page, $nbr_lignes) {
	global $serial_action_bar;
	global $dbh;
	global $msg;
	global $nb_per_page_a_search;
	global $charset;
	global $deflt_docs_location,$location;
	global $pmb_etat_collections_localise,$pmb_droits_explr_localises,$explr_invisible,$explr_visible_unmod;
	// barre de restriction des bulletins affichés
	global $aff_bulletins_restrict_numero, $aff_bulletins_restrict_date ;
	
	global $view;
	global $sort_children;
	
	if ($pmb_etat_collections_localise) {	
		 $location=((string)$location==""?$deflt_docs_location:$location);
	}
	
	// lien d'ajout d'une notice mère à un caddie
	$selector_prop = "toolbar=no, dependent=yes, width=500, height=400, resizable=yes, scrollbars=yes";
	$cart_click_noti = "onClick=\"openPopUp('./cart.php?object_type=NOTI&item=!!item!!', 'cart', 600, 700, -2, -2, '$selector_prop')\"";
	$cart_link = "<img src='./images/basket_small_20x20.gif' align='middle' alt='basket' title=\"${msg[400]}\" $cart_click_noti>";
		
	$base_url = "./catalog.php?categ=serials&sub=view&serial_id=$serial_id";
	$serial_action_bar = str_replace('!!serial_id!!', $serial_id, $serial_action_bar);
	if ($serial_id) $myQuery = mysql_query("SELECT * FROM notices WHERE notice_id=$serial_id ", $dbh);
	
	
	if ($serial_id && mysql_num_rows($myQuery)) {
		//Bulletins
		$myPerio = mysql_fetch_object($myQuery);
		// function serial_display ($id, $level='1', $action_serial='', $action_analysis='', $action_bulletin='', $lien_suppr_cart="", $lien_explnum="", $bouton_explnum=1,$print=0,$show_explnum=1, $show_statut=0, $show_opac_hidden_fields=true ) {
		$isbd = new serial_display($myPerio, 5, "",                      "",                 "",                  "",                  "./catalog.php?categ=serials&sub=explnum_form&serial_id=!!serial_id!!&explnum_id=!!explnum_id!!");
		$perio_header = $isbd->header;
	
		// isbd du périodique
		$perio_isbd = $isbd->isbd;
		$isbd->get_etat_periodique();
		$perio_isbd.=$isbd->print_etat_periodique();

		if (!$page) $page=1;
		$debut = ($page-1)*$nb_per_page_a_search;
	
		switch ($view) {
			case "abon":
				$base_url = "./catalog.php?categ=serials&sub=view&serial_id=$serial_id&view=abon";
				require_once("views/view_abon.inc.php");
				break;
			case "modele":
				require_once("views/view_modeles.inc.php");
				break;
			case "collstate":
				$base_url = "./catalog.php?categ=serials&sub=view&serial_id=$serial_id&view=collstate";
				require_once("views/view_collstate.inc.php");
				break;				
			default:
			
				// barre de restriction des bulletins affichés
				$clause="";
				if ($aff_bulletins_restrict_numero) {
					$clause = " and bulletin_numero like '%".str_replace("*","%",$aff_bulletins_restrict_numero)."%' ";
					$base_url .= "&aff_bulletins_restrict_numero=".urlencode($aff_bulletins_restrict_numero) ;
				}			
				if ($aff_bulletins_restrict_date) {
					$aff_bulletins_restrict_date_traite = str_replace("*","%",$aff_bulletins_restrict_date) ;
					$aff_bulletins_restrict_date_traite_mention = $aff_bulletins_restrict_date_traite ;
					$tab_bulletins_restrict_date = explode ($msg[format_date_input_separator],$aff_bulletins_restrict_date_traite) ;
					if(count($tab_bulletins_restrict_date)==3)$aff_bulletins_restrict_date_traite = $tab_bulletins_restrict_date[2]."-".$tab_bulletins_restrict_date[1]."-".$tab_bulletins_restrict_date[0];
					if(count($tab_bulletins_restrict_date)==2)$aff_bulletins_restrict_date_traite = $tab_bulletins_restrict_date[1]."-".$tab_bulletins_restrict_date[0];
					if(count($tab_bulletins_restrict_date)==1)$aff_bulletins_restrict_date_traite = $tab_bulletins_restrict_date[0];
					$clause .= " and (date_date like '%".$aff_bulletins_restrict_date_traite."%' or mention_date like '%".$aff_bulletins_restrict_date_traite_mention."%')" ;
					$base_url .= "&aff_bulletins_restrict_date=".urlencode($aff_bulletins_restrict_date) ;
				}
				
				//On compte les expl de la localisation
				$rqt="SELECT COUNT(1) FROM bulletins ".($location?", exemplaires":"")." WHERE ".($location?"(expl_bulletin=bulletin_id and expl_location='$location' or expl_location is null) and ":"")." bulletin_notice='$serial_id'  ";
				$myQuery = mysql_query($rqt, $dbh);
				$nb_expl_loc = mysql_result($myQuery,0,0);
		
				//On compte les bulletins de la localisation
				$rqt="SELECT count(distinct bulletin_id) FROM bulletins ".($location?",exemplaires ":"")." WHERE ".($location?"(expl_bulletin=bulletin_id and expl_location='$location') and ":"")." bulletin_notice='$serial_id' ";
				$myQuery = mysql_query($rqt, $dbh);
				if ($execute_query&&mysql_num_rows($myQuery)) {
					$nb_bull_loc = mysql_result($myQuery,0,0);
				}
				//On compte les bulletinsà afficher
				$rqt="SELECT count(distinct bulletin_id) FROM bulletins ".($location?", exemplaires":"")." WHERE ".($location?"(expl_bulletin=bulletin_id and expl_location='$location' or expl_location is null) and ":"")." bulletin_notice='$serial_id' $clause ";
				$myQuery = mysql_query($rqt, $dbh);
				$nbr_lignes = mysql_result($myQuery,0,0);
				
				require_once("views/view_bulletins.inc.php");
				break;
		}
		
		// Gestion de la supression de la notice si les droits de modification des exemplaires sont localisés.  	
		$flag_no_delete_notice=0;
		//visibilité des exemplaires
		if ($pmb_droits_explr_localises) {
			global $explr_visible_mod;
			$explr_tab_modif=explode(",",$explr_visible_mod);			
			$requete = "SELECT expl_location from exemplaires, bulletins,notices where
				expl_bulletin=bulletin_id and bulletin_notice=notice_id and notice_id= $serial_id";			
			$execute_query=mysql_query($requete);
			if ($execute_query&&mysql_num_rows($execute_query)) {
				while ($r=mysql_fetch_object($execute_query)) {
					if(!in_array ($r->expl_location,$explr_tab_modif )) $flag_no_delete_notice=1;
				}			
			}
		}
		if(!$flag_no_delete_notice)$serial_action_bar = str_replace('!!delete_serial_button!!', "<input type='button' class='bouton' onclick=\"confirm_serial_delete();\" value='$msg[63]' />", $serial_action_bar);
		else $serial_action_bar=str_replace('!!delete_serial_button!!', "", $serial_action_bar);
		
	  	// action_bar : serials.tpl.php...
	  	// mise à jour des info du javascript	  	
	  	$serial_action_bar = str_replace('!!nb_bulletins!!', $isbd->serial_nb_bulletins, $serial_action_bar);
	  	$serial_action_bar = str_replace('!!nb_expl!!', $isbd->serial_nb_exemplaires, $serial_action_bar);
	        	
	    // titre général du périodique
	  	print pmb_bidi("<div class='row'>
	  			<div class='notice-perio'>$isbd->aff_statut
					<h2 style='display: inline;'>".str_replace('!!item!!', $serial_id, $cart_link)." ".$perio_header."</h2>
	        					<div class='row'>$perio_isbd</div>
							<div class='row'>$collections_state</div>
	        				<hr />
	        				<div class='row'>
	        					$serial_action_bar
	        					</div>
	        				</div>
	        			</div>");
		
		// bulletinage
		$onglets = "
		<div id='content_onglet_perio'>
			<span class='".((!$view)?"onglet-perio-selected'>":"onglets-perio'>")."<a href=\"#\" onClick=\"document.location='catalog.php?categ=serials&sub=view&serial_id=".$serial_id."'\">".$msg["abts_onglet_bull"]."</a></span>
			<span class='".(($view=="abon")?"onglet-perio-selected'>":"onglets-perio'>")."<a href=\"#\" onClick=\"document.location='catalog.php?categ=serials&sub=view&serial_id=".$serial_id."&view=abon'\">".$msg["abts_onglet_abt"]."</a></span>
			<span class='".(($view=="modele")?"onglet-perio-selected'>":"onglets-perio'>")."<a href=\"#\"  onClick=\"document.location='catalog.php?categ=serials&sub=view&serial_id=".$serial_id."&view=modele'\">".$msg["abts_onglet_modele"]."</a></span>
			<span class='".(($view=="collstate")?"onglet-perio-selected'>":"onglets-perio'>")."<a href=\"#\"  onClick=\"document.location='catalog.php?categ=serials&sub=view&serial_id=".$serial_id."&view=collstate'\">".$msg["abts_onglet_collstate"]."</a></span>
		</div>
		";
		
		print $onglets;
		$totaux_loc="";
		$temp_location=0;
		$list_locs="";
		switch($view) {
			case "modele":
				$list_locs="";
			break;
			case "abon":
				if ($location) $temp_location=$location;
				$locs=new docs_location();
				$list_locs=$locs->gen_combo_box_empr($temp_location,1,"document.filter_form.location.value=this.options[this.selectedIndex].value; document.filter_form.submit();");
				$link_bulletinage = "<a href='./catalog.php?categ=serials&sub=pointage&serial_id=$serial_id&location_view=$location'>".$msg["link_notice_to_bulletinage"]."</a>"; 				
			break;
			case "collstate":
				if($pmb_etat_collections_localise) {
					if (($location)) $temp_location=$location;					
					$locs=new docs_location();
					$list_locs=$locs->gen_combo_box_empr($temp_location,1,"document.filter_form.location.value=this.options[this.selectedIndex].value; document.filter_form.submit();");
				}				
				$link_bulletinage = "<input type='button' class='bouton' value='".$msg["collstate_add_collstate"]."' 
				onClick=\"document.location='./catalog.php?categ=serials&sub=collstate_form&serial_id=$serial_id&id=';\">";
				
			break;
			default:
				if ($location) $temp_location=$location;	
				$locs=new docs_location();
				$list_locs=$locs->gen_combo_box_empr($temp_location,1,"document.filter_form.location.value=this.options[this.selectedIndex].value; document.filter_form.submit();");
				$link_bulletinage = "<a href='./catalog.php?categ=serials&sub=pointage&serial_id=$serial_id&location_view=$location'>".$msg["link_notice_to_bulletinage"]."</a>";
				if($nb_bull_loc) {
					if($temp_location && $list_locs) {
						$totaux_loc="<strong>$nb_bull_loc</strong> ".$msg["serial_nb_bulletin"]."
						<strong>$nb_expl_loc</strong> ".$msg["bulletin_nb_ex"];
					}
				}
			break;			
		}	

		print pmb_bidi("<div class='bulletins-perio'>
			<div class='row'>
				<h3>".($view=="abon"?$msg["perio_abts_title"]:($view=="modele"?$msg["perio_modeles_title"]:($view=="collstate"?$msg["abts_onglet_collstate"]:$msg["4001"])))."&nbsp;$list_locs
				$link_bulletinage
			</h3>
				$totaux_loc
				</div>
			<div class='row'>
				$bulletins
				</div>
			<div class='row'>&nbsp;</div>
			<div class='row'>
				<div align='center'>
					$pages_display
					</div>
				</div>
			</div>");
	
	}
}

// affichage de la liste utilisateurs pour sélection
function list_serial($cb, $serial_list, $nav_bar) {
	global $serial_list_tmpl;
	$serial_list_tmpl = str_replace("!!cle!!", $cb, $serial_list_tmpl);
	$serial_list_tmpl = str_replace("!!list!!", $serial_list, $serial_list_tmpl);
	$serial_list_tmpl = str_replace("!!nav_bar!!", $nav_bar, $serial_list_tmpl);
	print pmb_bidi($serial_list_tmpl);
}
