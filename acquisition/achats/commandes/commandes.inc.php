<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: commandes.inc.php,v 1.69 2010-10-29 08:35:20 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des commandes
require_once("$class_path/entites.class.php");
require_once("$class_path/exercices.class.php");
require_once("$class_path/paiements.class.php");
require_once("$class_path/frais.class.php");
require_once("$class_path/types_produits.class.php");
require_once("$class_path/rubriques.class.php");
require_once("$class_path/offres_remises.class.php");
require_once("$class_path/actes.class.php");
require_once("$class_path/lignes_actes.class.php");
require_once("$class_path/liens_actes.class.php");
require_once("$class_path/suggestions.class.php");
require_once("$class_path/notice.class.php");
require_once("$class_path/sel_display.class.php");
require_once("$class_path/suggestions_map.class.php");
require_once("$include_path/templates/actes.tpl.php");
require_once("$include_path/templates/commandes.tpl.php");
require_once("$base_path/acquisition/achats/func_achats.inc.php");
require_once("$base_path/acquisition/suggestions/func_suggestions.inc.php");


//Affiche la liste des etablissements
function show_list_biblio() {
	
	global $msg, $charset;
	global $tab_bib, $nb_bib;
	global $current_module;

	//Affiche de la liste des etablissements auxquels a acces l'utilisateur si > 1
	if ($nb_bib == '1') {
		show_list_cde($tab_bib[0][0]);		
		exit;
	}
	
	$def_bibli=entites::getSessionBibliId();
	if (in_array($def_bibli, $tab_bib[0])) {
		show_list_cde($def_bibli);
		exit;		
	}			

	$aff = "<form class='form-".$current_module."' id='list_biblio_form' name='list_biblio_form' method='post' action=\"\" >";
	$aff.= "<h3>".htmlentities($msg['acquisition_menu_chx_ent'], ENT_QUOTES, $charset)."</h3><div class='row'></div>";
	$aff.= "<table>";

	$parity=1;
	foreach($tab_bib[0] as $k=>$v) {
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" onmousedown=\"document.forms['list_biblio_form'].setAttribute('action','./acquisition.php?categ=ach&sub=cmde&action=list&id_bibli=".$v."');document.forms['list_biblio_form'].submit(); \" ";
        $aff.= "<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer'><td><i>".htmlentities($tab_bib[1][$k], ENT_QUOTES, $charset)."</i></td></tr>";
	}
	$aff.=" </table></form>";
	print $aff;
}


//Affiche la liste des commandes pour une bibliotheque
function show_list_cde($id_bibli) {
	
	global $msg, $charset;
	global $search_form, $cdelist_form,$cdelist_bt_chk,$cdelist_script;
	global $cdelist_bt_valid,$cdelist_bt_delete,$cdelist_bt_sold,$cdelist_bt_arc;
	global $nb_per_page_acq;
	global $class_path;
	global $user_input, $statut, $page, $nbr_lignes, $last_param;
	global $tab_bib;
	
	//Creation selecteur etablissement
	$sel_bibli ="<select class='saisie-50em' id='id_bibli' name='id_bibli' onchange=\"submit();\" >";
	foreach($tab_bib[0] as $k=>$v) {
		$sel_bibli.="<option value='".$v."' ";
		if($v==$id_bibli) $sel_bibli.="selected='selected' ";
		$sel_bibli.=">".htmlentities($tab_bib[1][$k], ENT_QUOTES, $charset)."</option>";
	}
	$sel_bibli.="</select>";
	$search_form=str_replace('<!-- sel_bibli -->', $sel_bibli, $search_form);
	
	//Creation selecteur statut
	$sel_statut = "<select class='saisie-25em' id='statut' name='statut' onchange=\"submit();\" >";
	$list_statut = actes::getStatelist(TYP_ACT_CDE);
	foreach($list_statut as $k=>$v){
		$sel_statut.="<option value='".$k."'>".htmlentities($v, ENT_QUOTES, $charset)."</option>";
	}
	$sel_statut.= "</select>";
	$search_form=str_replace('<!-- sel_statut -->', $sel_statut ,$search_form);
	
	//Affichage form de recherche
	$titre = htmlentities($msg['recherche'].' : '.$msg['acquisition_ach_cde'], ENT_QUOTES, $charset);
	$action ="./acquisition.php?categ=ach&sub=cmde&action=list&user_input=";
	$bouton_add = "<input class='bouton' type='button' value='".$msg['acquisition_ajout_cde']."' onclick=\"document.location='./acquisition.php?categ=ach&sub=cmde&action=modif&id_bibli=".$id_bibli."&id_cde=0';\" />";
	$search_form = str_replace('!!form_title!!', $titre, $search_form);
	$search_form = str_replace('!!action!!', $action, $search_form);
	$search_form = str_replace('<!-- bouton_add -->', $bouton_add, $search_form);
	$search_form = str_replace('!!user_input!!', $user_input, $search_form);
		
	print $search_form;
	
	if (!$statut) {
		$statut = getSessionCdeState(); //Recuperation du statut courant
	} else {
		setSessionCdeState($statut);	
	}
	print "<script type='text/javascript' >document.forms['search'].elements['statut'].value = '".$statut."';document.forms['search'].elements['user_input'].focus();
	document.forms['search'].elements['user_input'].select();</script>";
	 
	//Prise en compte du formulaire de recherche
	// nombre de références par pages
	if ($nb_per_page_acq != "") $nb_per_page = $nb_per_page_acq ;
		else $nb_per_page = 10;
	
	
	// traitement de la saisie utilisateur
	require_once($class_path."/analyse_query.class.php");
	
	// comptage
	if(!$nbr_lignes) {

		if(!$user_input) {
			$nbr_lignes = entites::getNbActes($id_bibli, TYP_ACT_CDE, $statut);
		} else {
			$aq=new analyse_query(stripslashes($user_input),0,0,0,0);
			if ($aq->error) {
				error_message($msg["searcher_syntax_error"],sprintf($msg["searcher_syntax_error_desc"],$aq->current_car,$aq->input_html,$aq->error_message));
				exit;
			}
			$nbr_lignes = entites::getNbActes($id_bibli, TYP_ACT_CDE, $statut, $aq, $user_input);
	
		}

	} else {
		$aq=new analyse_query(stripslashes($user_input),0,0,0,0);
	}

	
	if(!$page) $page=1;
	$debut =($page-1)*$nb_per_page;

	if($nbr_lignes) {
	
		$url_base = "$PHP_SELF?categ=ach&sub=cmde&action=list&id_bibli=$id_bibli&user_input=".rawurlencode(stripslashes($user_input))."&statut=$statut" ;
		
		// liste
		if(!$user_input) {
			$res = entites::listActes($id_bibli, TYP_ACT_CDE, $statut, $debut, $nb_per_page);
		} else {
			$res = entites::listActes($id_bibli, TYP_ACT_CDE, $statut, $debut, $nb_per_page, $aq, $user_input);
		}

	
		//Affichage liste des commandes	
		$cde_list="";
		$nbr = mysql_num_rows($res);
		
		$parity=1;
		for($i=0;$i<$nbr;$i++) {
			$row=mysql_fetch_object($res);
			$fourn = new entites($row->num_fournisseur);
//TODO A modifier si une seule date de livraison par acte			
			$dateech = actes::getNextLivr($row->id_acte);
			if ($dateech != '0000-00-00') $dateech=formatdate($dateech); else $dateech = '';
	
			$st = ( ($row->statut) & ~(STA_ACT_FAC | STA_ACT_PAY | STA_ACT_ARC) );
			switch ($st) {
				case STA_ACT_AVA :
					$st_cde = htmlentities($msg['acquisition_cde_aval'], ENT_QUOTES, $charset);
					break;
				case STA_ACT_ENC :
					$st_cde = htmlentities($msg['acquisition_cde_enc'], ENT_QUOTES, $charset);
					break;
				case STA_ACT_REC :
					$st_cde = htmlentities($msg['acquisition_cde_liv'], ENT_QUOTES, $charset);
					break;
				default :
					$st_cde = htmlentities($msg['acquisition_cde_enc'], ENT_QUOTES, $charset);
			}
			if( ($row->statut & STA_ACT_PAY) == STA_ACT_PAY ) {
				$st_fac = htmlentities($msg['acquisition_act_pay'], ENT_QUOTES, $charset); 
			} elseif( ($row->statut & STA_ACT_FAC) == STA_ACT_FAC ) {
					$st_fac = htmlentities($msg['acquisition_act_fac'], ENT_QUOTES, $charset); 
				} else 
					$st_fac = '';
			if ($st_fac) $st_cde.='&nbsp;/&nbsp;'.$st_fac;
			if( ($row->statut & STA_ACT_ARC) == STA_ACT_ARC ) $st_cde = '<s>'.$st_cde.'</s>';	
	
			
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
			$dn_javascript = "onmousedown=\"document.location='./acquisition.php?categ=ach&sub=cmde&action=modif&id_bibli=".$id_bibli."&id_cde=".$row->id_acte."' \" ";
			$cde_list.= "<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer' >
						<td ".$dn_javascript." ><i>".$row->numero."</i></td>
						<td ".$dn_javascript." ><i>".htmlentities($fourn->raison_sociale, ENT_QUOTES, $charset)."</i></td>
						<td ".$dn_javascript." ><i>".formatdate($row->date_acte)."</i></td>
						<td ".$dn_javascript." ><i>".$dateech."</i></td>
						<td ".$dn_javascript." ><i>$st_cde</i></td>
						<td>
							<a href=# onclick=\"openPopUp('./pdf.php?pdfdoc=cmde&id_cde=".$row->id_acte."' ,'print_PDF', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes');\" >
								<img src='./images/print.gif' border='0' align='center' alt='".htmlentities(addslashes($msg['imprimer']),ENT_QUOTES, $charset)."' title='".htmlentities(addslashes($msg['imprimer']),ENT_QUOTES, $charset)."' />
							</a>
						</td>";
			if ($statut!=STA_ACT_ALL && $statut!=STA_ACT_ARC) {
				$cde_list.= "<td><input type='checkbox' name='chk[]' id='chk[".$row->id_acte."]' value='".$row->id_acte."'/></td>";
			}
			$cde_list.= "</tr>";
		}
		
		if (!$last_param) { 
			$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page);
		} else {
	    	$nav_bar = "";
		}
		
		$cdelist_form = str_replace('<!-- cde_list -->',$cde_list,$cdelist_form);
		$cdelist_form = str_replace('<!-- nav_bar -->',$nav_bar,$cdelist_form);

		if($statut!=STA_ACT_ALL && $statut!=STA_ACT_ARC) {
			//colonne chk
			$cdelist_form=str_replace("<!-- chk_th -->", "<th class='act_cell_chkbox'>&nbsp;</th>",$cdelist_form);
		
			//Bouton Sélectionner
			$cdelist_form=str_replace('<!-- bt_chk -->', $cdelist_bt_chk,$cdelist_form);

			//JavaScript
			$cdelist_form=str_replace('<!-- script -->', $cdelist_script,$cdelist_form);
		}
		
		$bt_list='';
		$bt_sup='';
		switch($statut) {
			case STA_ACT_AVA :
				$bt_list=$cdelist_bt_valid;
				$bt_sup=$cdelist_bt_delete;
				break;
			case STA_ACT_ENC :	
				$bt_list=$cdelist_bt_sold;
				break;
			case STA_ACT_REC :	
				$bt_list=$cdelist_bt_arc;
				break;
			default:
				break;
		}
		$cdelist_form = str_replace('<!-- bt_list -->',$bt_list,$cdelist_form);
		$cdelist_form = str_replace('<!-- bt_sup -->',$bt_sup,$cdelist_form);
		print $cdelist_form;
		
	} else {
		// la requête n'a produit aucun résultat
		error_message($msg['acquisition_cde_rech'], str_replace('!!cde_cle!!', stripslashes($user_input), $msg['acquisition_cde_rech_error']), 0, './categ=ach&sub=cmde&action=list&id_bibli='.$id_bibli);
	}

}


//Affiche les exercices actifs pour création commande
function show_list_exercices($id_bibli, $fct, $url, $id_dev=0) {
	
	global $dbh;
	global $msg, $charset;
	global $current_module;
	
	$q =  entites::getCurrentExercices($id_bibli);
	$r = mysql_query($q, $dbh);
	$n = mysql_num_rows($r);
	switch ($n) {
		case 0 :
			//Pas d'exercice actif pour la bibliothèque
			$error_msg.= htmlentities($msg["acquisition_err_exer"],ENT_QUOTES, $charset)."<div class='row'></div>";	
			error_message($msg[321], $error_msg.htmlentities($msg["acquisition_err_par"],ENT_QUOTES, $charset), '1', './admin.php?categ=acquisition');
			die;
			break;
		case 1 :
			//1 seul exercice actif pour la bibliotheque
			$row = mysql_fetch_object($r);
			eval($fct."(".$id_bibli.", ".$row->id_exercice.", ".$id_dev.");");
			break;
		default :
			$aff = "<form class='form-".$current_module."' id='list_exe_form' name='list_exe_form' method='post' action=\"\" >";
			$aff.= "<h3>".htmlentities($msg['acquisition_menu_chx_exe'], ENT_QUOTES, $charset)."</h3><div class='row'></div>";
			$aff.= "<table>";
			$parity=1;
			while(($row=mysql_fetch_object($r))) {
				if ($parity % 2) {
					$pair_impair = "even";
				} else {
					$pair_impair = "odd";
				}
				$parity += 1;
				$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" onmousedown=\"document.forms['list_exe_form'].setAttribute('action','".$url."&id_exer=".$row->id_exercice."');document.forms['list_exe_form'].submit(); \" ";
		        $aff.= "<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer'><td><i>".htmlentities($row->libelle, ENT_QUOTES, $charset)."</i></td></tr>";
			}
			$aff.=" </table></form>";
			print $aff;
			break;
	}
}


//Affiche le formulaire de création/modification de commande
function show_cde($id_bibli, $id_exer, $id_cde) {

	global $msg, $charset;
	global $modif_cde_form, $valid_cde_form;
	global $bt_enr, $bt_enr_valid, $bt_val, $bt_dup, $bt_rec, $bt_fac, $bt_audit, $bt_sol, $bt_arc, $bt_sup, $bt_imp ;
	global $sel_date_liv_mod, $sel_date_liv_fix, $sel_date_pay_mod;
	global $pmb_gestion_devise;
	global $p_user;
	global $pmb_type_audit;
	
	//Recuperation etablissement
	$bibli = new entites($id_bibli);
	$lib_bibli = htmlentities($bibli->raison_sociale, ENT_QUOTES, $charset);
	
	//Prise en compte des adresses utilisateurs par defaut 	
	$tab1 = explode('|', $p_user->speci_coordonnees_etab);
	$tab_adr=array();
	foreach ($tab1 as $value) {
		$tab2=explode(',', $value);
		$tab_adr[$tab2[0]]['id_adr_fac']=$tab2[1];
		$tab_adr[$tab2[0]]['id_adr_liv']=$tab2[2];
	}
	$def_id_adr_fac=$tab_adr[$id_bibli]['id_adr_fac'];
	$def_id_adr_liv=$tab_adr[$id_bibli]['id_adr_liv'];
	
	
	if(!$id_cde) {		//creation de commmande
	
		//Recuperation exercice
		$exer = new exercices($id_exer);
		$lib_exer = htmlentities($exer->libelle, ENT_QUOTES, $charset);
	
		$form = $modif_cde_form;
		
		$titre = htmlentities($msg['acquisition_cde_cre'], ENT_QUOTES, $charset);
		$date_cre = formatdate(today());
		//$numero = calcNumero($id_bibli, TYP_ACT_CDE); 
		$statut = STA_ACT_AVA;
		$sel_statut = "<input type='hidden' id='statut' name='statut' value='".$statut."' />";
		$sel_statut.=htmlentities($msg['acquisition_cde_aval'], ENT_QUOTES, $charset);
		$id_fou = '0';
		$lib_fou = '';
		$id_adr_fou = '0';
		$adr_fou = '';
		if ($def_id_adr_fac) {
			$id_adr_fac = $def_id_adr_fac;
			$coord = new coordonnees($def_id_adr_fac);
		} else {
			$coord_fac = entites::get_coordonnees($id_bibli, '1');
			if (mysql_num_rows($coord_fac) != 0) {
				$coord = mysql_fetch_object($coord_fac);
				$id_adr_fac = $coord->id_contact;
			} else {
				$id_adr_fac='0';
			}
		}
		if ($id_adr_fac) {
			if($coord->libelle != '') $adr_fac = htmlentities($coord->libelle, ENT_QUOTES, $charset)."\n";
			if($coord->contact != '') $adr_fac.= htmlentities($coord->contact, ENT_QUOTES, $charset)."\n";
			if($coord->adr1 != '') $adr_fac.= htmlentities($coord->adr1, ENT_QUOTES, $charset)."\n";
			if($coord->adr2 != '') $adr_fac.= htmlentities($coord->adr2, ENT_QUOTES, $charset)."\n";
			if($coord->cp !='') $adr_fac.= htmlentities($coord->cp, ENT_QUOTES, $charset).' ';
			if($coord->ville != '') $adr_fac.= htmlentities($coord->ville, ENT_QUOTES, $charset);
		} else {
			$adr_fac = '';
		}
		if ($def_id_adr_liv) {
			$id_adr_liv = $def_id_adr_liv;
			$coord = new coordonnees($def_id_adr_liv);
		} else {
			$coord_liv = entites::get_coordonnees($id_bibli, '2');
			if (mysql_num_rows($coord_liv) != 0) {
				$coord = mysql_fetch_object($coord_liv);
				$id_adr_liv = $coord->id_contact;
			} else {
				$id_adr_liv='0';
			}
		}
		if ($id_adr_liv) {
			if($coord->libelle != '') $adr_liv = htmlentities($coord->libelle, ENT_QUOTES, $charset)."\n";
			if($coord->contact != '') $adr_liv.= htmlentities($coord->contact, ENT_QUOTES, $charset)."\n"; 
			if($coord->adr1 != '') $adr_liv.= htmlentities($coord->adr1, ENT_QUOTES, $charset)."\n";
			if($coord->adr2 != '') $adr_liv.= htmlentities($coord->adr2, ENT_QUOTES, $charset)."\n";
			if($coord->cp !='') $adr_liv.= htmlentities($coord->cp, ENT_QUOTES, $charset).' ';
			if($coord->ville != '') $adr_liv.= htmlentities($coord->ville, ENT_QUOTES, $charset);
		} else {
			$id_adr_liv = $id_adr_fac;
			$adr_liv = $adr_fac;
		}		
		$comment = '';
		$comment_i = '';
		$lien_dev = '';
		$liens_liv = '';
		$liens_fac = '';
		$ref = '';
		$sel_date_pay = $sel_date_pay_mod;
		$date_pay = '';
		$date_pay_lib = $msg['parperso_nodate'];	
		$num_pay = '';
		$sel_date_liv=$sel_date_liv_mod;
		$date_liv = '';
		$date_liv_lib = $msg['parperso_nodate'];		
		$id_dev = '0';
		$devise = $pmb_gestion_devise;

		$bt_enr_valid = '';
		$bt_dup = '';
		$bt_rec = '';
		$bt_fac = '';
		$bt_sol = '';
		$bt_imp = '';
		$bt_audit = '';
		$bt_arc = '';
		$bt_sup = '';
		$lignes= show_lig_cde(0);//$lignes= array(0=>0, 1=>'');
		
	} else {		//visualisation ou modification de commmande

		//Recuperation commande
		$cde = new actes($id_cde);
		$exer = new exercices($cde->num_exercice);
		$lib_exer = htmlentities($exer->libelle, ENT_QUOTES, $charset);
		
		//elements communs
		$titre = htmlentities($msg['acquisition_cde_mod'], ENT_QUOTES, $charset);
		$date_cre = formatdate($cde->date_acte);
		$numero = htmlentities($cde->numero, ENT_QUOTES, $charset); 
		$id_fou = $cde->num_fournisseur;
		$fou = new entites($id_fou);
		$lib_fou = htmlentities($fou->raison_sociale, ENT_QUOTES, $charset);
		$coord = entites::get_coordonnees($fou->id_entite, '1');
		if (mysql_num_rows($coord) != 0) {
			$coord = mysql_fetch_object($coord);
			$id_adr_fou = $coord->id_contact;
			if($coord->libelle != '') $adr_fou = htmlentities($coord->libelle, ENT_QUOTES, $charset)."\n";
			if($coord->contact !='') $adr_fou.=  htmlentities($coord->contact, ENT_QUOTES, $charset)."\n";
			if($coord->adr1 != '') $adr_fou.= htmlentities($coord->adr1, ENT_QUOTES, $charset)."\n";
			if($coord->adr2 != '') $adr_fou.= htmlentities($coord->adr2, ENT_QUOTES, $charset)."\n";
			if($coord->cp !='') $adr_fou.= htmlentities($coord->cp, ENT_QUOTES, $charset).' ';
			if($coord->ville != '') $adr_fou.= htmlentities($coord->ville, ENT_QUOTES, $charset);
		} else {
			$id_adr_fou = '0';
			$adr_fou = '';
		}
		$id_adr_fac = $cde->num_contact_fact;
		if ($id_adr_fac) {
			$coord_fac = new coordonnees($id_adr_fac);
			if($coord_fac->libelle != '') $adr_fac = htmlentities($coord_fac->libelle, ENT_QUOTES, $charset)."\n";
			if($coord->contact !='') $adr_fac.=  htmlentities($coord_fac->contact, ENT_QUOTES, $charset)."\n";
			if($coord_fac->adr1 != '') $adr_fac.= htmlentities($coord_fac->adr1, ENT_QUOTES, $charset)."\n";
			if($coord_fac->adr2 != '') $adr_fac.= htmlentities($coord_fac->adr2, ENT_QUOTES, $charset)."\n";
			if($coord_fac->cp !='') $adr_fac.= htmlentities($coord_fac->cp, ENT_QUOTES, $charset).' ';
			if($coord_fac->ville != '') $adr_fac.= htmlentities($coord_fac->ville, ENT_QUOTES, $charset);	
		} else {
			$id_adr_fac = '0';
			$adr_fac = '';
		}
		$id_adr_liv = $cde->num_contact_livr;
		if ($id_adr_liv) {
			$coord_liv = new coordonnees($id_adr_liv);
			if($coord_liv->libelle != '') $adr_liv = htmlentities($coord_liv->libelle, ENT_QUOTES, $charset)."\n";
			if($coord_liv->contact != '') $adr_liv.= htmlentities($coord_liv->contact, ENT_QUOTES, $charset)."\n";
			if($coord_liv->adr1 != '') $adr_liv.= htmlentities($coord_liv->adr1, ENT_QUOTES, $charset)."\n";
			if($coord_liv->adr2 != '') $adr_liv.= htmlentities($coord_liv->adr2, ENT_QUOTES, $charset)."\n";
			if($coord_liv->cp !='') $adr_liv.= htmlentities($coord_liv->cp, ENT_QUOTES, $charset).' ';
			if($coord_liv->ville != '') $adr_liv.= htmlentities($coord_liv->ville, ENT_QUOTES, $charset);
		} else {
			$id_adr_liv = '0';
			$adr_liv = '';
		}
		$comment = htmlentities($cde->commentaires, ENT_QUOTES, $charset);	
		$comment_i = htmlentities($cde->commentaires_i, ENT_QUOTES, $charset);
		$id_dev = liens_actes::getDevis($id_cde); 		
		if ($id_dev) {
			$dev=new actes($id_dev);
			$lien_dev = "<a href=\"./acquisition.php?categ=ach&sub=devi&action=modif&id_bibli=".$id_bibli."&id_dev=".$id_dev."\">".$dev->numero."</a>";	
		}
		$tab_liens = liens_actes::getChilds($id_cde);
		$liens_liv = '';
		$liens_fac = '';
		while(($row_liens = mysql_fetch_object($tab_liens))) {
			if( ($row_liens->type_acte) == TYP_ACT_LIV ) {
				$liens_liv.= "<br /><a href=\"./acquisition.php?categ=ach&sub=livr&action=modif&id_bibli=".$id_bibli."&id_liv=".$row_liens->num_acte_lie."\">".$row_liens->numero."</a>"; 
			} 
			if( ($row_liens->type_acte) == TYP_ACT_FAC ) {
				$liens_fac.= "<br /><a href=\"./acquisition.php?categ=ach&sub=fact&action=modif&id_bibli=".$id_bibli."&id_fac=".$row_liens->num_acte_lie."\">".$row_liens->numero."</a>"; 				
			}
		}
		$ref = htmlentities($cde->reference, ENT_QUOTES, $charset);
		$sel_date_pay = $sel_date_pay_mod;
		if ($cde->date_paiement=='0000-00-00') {
			$date_pay = '';
			$date_pay_lib = $msg['parperso_nodate'];
		} else {
			$date_pay = $cde->date_paiement;
			$date_pay_lib = formatdate($cde->date_paiement);
		}
		$num_pay = htmlentities($cde->num_paiement, ENT_QUOTES, $charset);
	
		if (!$pmb_type_audit) {
			$bt_audit = '';
		}
		$devise = htmlentities($cde->devise, ENT_QUOTES, $charset);	
		
		if ($cde->statut != STA_ACT_AVA) {	//Commande non modifiable
			
			$form = $valid_cde_form;
			$statut = $cde->statut;
			$st = ( ($cde->statut) & ~(STA_ACT_FAC | STA_ACT_PAY | STA_ACT_ARC) );
			switch ($st) {
				case STA_ACT_AVA :
					$sel_statut = htmlentities($msg['acquisition_cde_aval'], ENT_QUOTES, $charset);
					break;
				case STA_ACT_ENC :
					$sel_statut = htmlentities($msg['acquisition_cde_enc'], ENT_QUOTES, $charset);
					break;
				case STA_ACT_REC :
					$sel_statut = htmlentities($msg['acquisition_cde_liv'], ENT_QUOTES, $charset);
					break;
				default :
					$sel_statut = htmlentities($msg['acquisition_cde_enc'], ENT_QUOTES, $charset);
				break;
			}
			if( ($cde->statut & STA_ACT_PAY) == STA_ACT_PAY ) {
				$statut_fac = htmlentities($msg['acquisition_act_pay'], ENT_QUOTES, $charset); 
			} elseif( ($cde->statut & STA_ACT_FAC) == STA_ACT_FAC ) {
				$statut_fac = htmlentities($msg['acquisition_act_fac'], ENT_QUOTES, $charset); 
			} else {
				$statut_fac = '';
			}
			if ($statut_fac) $sel_statut.='&nbsp;/&nbsp;'.$statut_fac;
			if( ($cde->statut & STA_ACT_ARC) == STA_ACT_ARC ) $sel_statut = '<s>'.$sel_statut.'</s>';	
			$sel_statut.= "<input type='hidden' id='statut' name='statut' value='".$statut."' />";
			
			$sel_date_liv=$sel_date_liv_fix;
			if ($cde->date_ech=='0000-00-00') {
				$date_liv_lib = $msg['parperso_nodate'];
			} else {
				$date_liv_lib = formatdate($cde->date_ech);
			}
			
			if (($cde->statut & STA_ACT_ARC) == STA_ACT_ARC ){	//Commande archivee

				$bt_enr = '';
				$bt_enr_valid = '';
				$bt_val = '';
				$bt_rec = '';
				$bt_fac = '';
				$bt_sol = '';
				$bt_arc = '';
				
			} else {	//Commande non archivee
				
				$bt_enr = '';
				$bt_val = '';
				
				if (($cde->statut & STA_ACT_REC) == STA_ACT_REC) {	//Commande soldee
					$bt_rec = '';
					$bt_sol = '';		
				}
				if (($cde->statut & STA_ACT_FAC) == STA_ACT_FAC) {	//Commande facturee
					$bt_fac = '';		
				}
				if ( (($cde->statut & STA_ACT_REC) != STA_ACT_REC) 
					|| (($cde->statut & STA_ACT_FAC) != STA_ACT_FAC) 
					|| (($cde->statut & STA_ACT_PAY) != STA_ACT_PAY) ) { //Commande ni soldee, ni facturee, ni payee
						$bt_arc = '';
				}
			}
			
			$lignes= show_lig_cde($id_cde, FALSE);
			
		} else {	//Commande modifiable	
			
			$form = $modif_cde_form;
			
			$statut = STA_ACT_AVA;
			$sel_statut = "<input type='hidden' id='statut' name='statut' value='".$statut."' />";
			$sel_statut.=htmlentities($msg['acquisition_cde_aval'], ENT_QUOTES, $charset);
			
			$sel_date_liv = $sel_date_liv_mod;
			if ($cde->date_ech=='0000-00-00') {
				$date_liv = '';
				$date_liv_lib = $msg['parperso_nodate'];
			} else {
				$date_liv = $cde->date_ech;
				$date_liv_lib = formatdate($cde->date_ech);
			}
			$bt_enr_valid = '';
			$bt_rec = '';
			$bt_fac = '';
			$bt_sol = '';
			
			$lignes= show_lig_cde($id_cde);
			
		}
	}
	
	//complement formulaire
	$form = str_replace('<!-- sel_statut -->', $sel_statut, $form);
	$form = str_replace('<!-- sel_date_liv -->', $sel_date_liv, $form);
	$form = str_replace('<!-- sel_date_pay -->', $sel_date_pay, $form);
	$form = str_replace('<!-- bouton_enr -->', $bt_enr, $form);
	$form = str_replace('<!-- bouton_enr_valid -->', $bt_enr_valid, $form);
	$form = str_replace('<!-- bouton_val -->', $bt_val, $form);
	$form = str_replace('<!-- bouton_dup -->', $bt_dup, $form);
	$form = str_replace('<!-- bouton_rec -->', $bt_rec, $form);
	$form = str_replace('<!-- bouton_fac -->', $bt_fac, $form);
	$form = str_replace('<!-- bouton_imp -->', $bt_imp, $form);
	$form = str_replace('<!-- bouton_audit -->', $bt_audit, $form);
	$form = str_replace('<!-- bouton_arc -->', $bt_arc, $form);
	$form = str_replace('<!-- bouton_sol -->', $bt_sol, $form);
	$form = str_replace('<!-- bouton_sup -->', $bt_sup, $form);
	$form = str_replace('!!act_nblines!!', $lignes[0], $form);
	$form = str_replace('<!-- lignes -->', $lignes[1], $form);
	
	//Remplissage formulaire
	$form = str_replace('!!form_title!!', $titre, $form);		
	$form = str_replace('!!id_bibli!!', $id_bibli, $form);	
	$form = str_replace('!!lib_bibli!!', $lib_bibli, $form);	
	$form = str_replace('!!id_exer!!', $exer->id_exercice, $form);
	$form = str_replace('!!lib_exer!!', $lib_exer, $form);
	$form = str_replace('!!id_cde!!', $id_cde, $form);
	$form = str_replace('!!date_cre!!', $date_cre, $form);
	$form = str_replace('!!dat_def_lib!!', formatdate(today()), $form);
	$form = str_replace('!!dat_def!!', preg_replace('/-/', '', today()), $form);
	$form = str_replace('!!numero!!', $numero, $form);
	$form = str_replace('!!statut!!', $statut, $form);
	$form = str_replace('!!id_fou!!', $id_fou, $form);
	$form = str_replace('!!lib_fou!!', $lib_fou, $form);
	$form = str_replace('!!id_adr_fou!!', $id_adr_fou, $form);
	$form = str_replace('!!adr_fou!!', $adr_fou, $form);
	$form = str_replace('!!date_liv!!', $date_liv, $form);
	$form = str_replace('!!date_liv_lib!!', $date_liv_lib, $form);
	$form = str_replace('!!date_pay!!', $date_pay, $form);
	$form = str_replace('!!date_pay_lib!!', $date_pay_lib, $form);
	$form = str_replace('!!num_pay!!', $num_pay, $form);
	$form = str_replace('!!id_adr_liv!!', $id_adr_liv, $form);
	$form = str_replace('!!adr_liv!!', $adr_liv, $form);
	$form = str_replace('!!id_adr_fac!!', $id_adr_fac, $form);
	$form = str_replace('!!adr_fac!!', $adr_fac, $form);
	$form = str_replace('!!comment!!', $comment, $form);
	$form = str_replace('!!comment_i!!', $comment_i, $form);
	$form = str_replace('!!ref!!', $ref, $form);
	$form = str_replace('!!id_dev!!', $id_dev, $form);	
	$form = str_replace('!!devise!!', $devise, $form);
	$form = str_replace('!!lien_dev!!', $lien_dev, $form);
	$form = str_replace('!!liens_fac!!', $liens_fac, $form);
	$form = str_replace('!!liens_liv!!', $liens_liv, $form);
		
	print $form;
}


//Affiche les lignes d'une commande
function show_lig_cde($id_cde, $mod=TRUE) {
	
	global $charset,$msg;
	global $acquisition_gestion_tva;
	global $modif_cde_row_form, $valid_cde_row_form;
	
	$form = "	
	<script type='text/javascript'>	
		acquisition_force_ttc='".$msg["acquisition_force_ttc"]."';
		acquisition_force_ht='".$msg["acquisition_force_ht"]."';
	</script>
	";
	$i=0;	
	if (!$id_cde) {
		$t = array(0=>$i, $form);
		return $t;
	}
	
	if ($mod) {
		$row_form = $modif_cde_row_form;
	} else {
		$row_form = $valid_cde_row_form;
	}
	
	$lignes = actes::getLignes($id_cde);
	while (($row = mysql_fetch_object($lignes))) {		
		$i++;	
		$form.= $row_form;
		$form = str_replace('!!no!!', $i, $form);
		$form = str_replace('!!code!!', htmlentities($row->code, ENT_QUOTES, $charset), $form);
		$form = str_replace('!!lib!!', htmlentities($row->libelle, ENT_QUOTES, $charset), $form);
		$form = str_replace('!!qte!!', $row->nb, $form);
		$form = str_replace('!!prix!!', $row->prix, $form);
		if ($acquisition_gestion_tva) {
			$form = str_replace('!!tva!!', $row->tva , $form);
			if ($acquisition_gestion_tva==1 ) {
				$prix_ttc=round($row->prix+($row->prix/100*$row->tva),2);
				$onchange_tva="
					onChange='document.getElementById(\"convert_ht_ttc_$i\").innerHTML=
						ht_to_ttc(document.getElementById(\"prix[$i]\").value,document.getElementById(\"tva[$i]\").value);
					' ";
				$convert_prix="
					onChange='document.getElementById(\"convert_ht_ttc_$i\").innerHTML=
						ht_to_ttc(document.getElementById(\"prix[$i]\").value,document.getElementById(\"tva[$i]\").value);
					' ";				
				$convert_ht_ttc="
				<span class='convert_ht_ttc' id='convert_ht_ttc_$i' 
					onclick='
						document.getElementById(\"input_convert_ht_ttc_$i\").value=\"\";
						document.getElementById(\"input_convert_ht_ttc_$i\").style.visibility=\"visible\"; 
						document.getElementById(\"input_convert_ht_ttc_$i\").focus();
					'							
				>".$prix_ttc."</span>
				<input style='visibility:hidden' type='text' id='input_convert_ht_ttc_$i' name='convert_ht_ttc_$i' value='' 				
					onBlur='document.getElementById(\"input_convert_ht_ttc_$i\").style.visibility=\"hidden\";'				
					onChange='document.getElementById(\"prix[$i]\").value=
						ttc_to_ht(document.getElementById(\"input_convert_ht_ttc_$i\").value,document.getElementById(\"tva[$i]\").value);
						document.getElementById(\"input_convert_ht_ttc_$i\").style.visibility=\"hidden\"; 
						document.getElementById(\"convert_ht_ttc_$i\").innerHTML=document.getElementById(\"input_convert_ht_ttc_$i\").value;
					'  
				/>";		
			}elseif ($acquisition_gestion_tva==2 ) {		
				$prix=$row->prix;
				$tva=$row->tva;
				$prix_ht=round( $prix / (($tva/100)+1),2);
				$onchange_tva="
					onChange='document.getElementById(\"convert_ht_ttc_$i\").innerHTML=
						ttc_to_ht(document.getElementById(\"prix[$i]\").value,document.getElementById(\"tva[$i]\").value);
					' ";
				$convert_prix="
					onChange='document.getElementById(\"convert_ht_ttc_$i\").innerHTML=
						ttc_to_ht(document.getElementById(\"prix[$i]\").value,document.getElementById(\"tva[$i]\").value);
					' ";
				$convert_ht_ttc="
				<span class='convert_ht_ttc' id='convert_ht_ttc_$i' 
					onclick='
						document.getElementById(\"input_convert_ht_ttc_$i\").value=\"\";
						document.getElementById(\"input_convert_ht_ttc_$i\").style.visibility=\"visible\"; 
						document.getElementById(\"input_convert_ht_ttc_$i\").focus();
					'							
				>".$prix_ht."</span>
				<input style='visibility:hidden' type='text' id='input_convert_ht_ttc_$i' name='convert_ht_ttc_$i' value='$prix_ttc' 				
					onBlur='document.getElementById(\"input_convert_ht_ttc_$i\").style.visibility=\"hidden\";'				
					onChange='document.getElementById(\"prix[$i]\").value=
						ht_to_ttc(document.getElementById(\"input_convert_ht_ttc_$i\").value,document.getElementById(\"tva[$i]\").value);
						document.getElementById(\"input_convert_ht_ttc_$i\").style.visibility=\"hidden\"; 
						document.getElementById(\"convert_ht_ttc_$i\").innerHTML=document.getElementById(\"input_convert_ht_ttc_$i\").value;
					'  
				/>";				
			}				
			if ($row->debit_tva==1 ) {
				$force_ht_ttc="<br />
				<input type='hidden' id='force_debit[$i]' name='force_debit[$i]' value='1' />				
				<span class='force_ht_ttc' id='force_ht_ttc_$i'				
					onclick='
						if(document.getElementById(\"force_debit[$i]\").value==1){
							document.getElementById(\"force_ht_ttc_$i\").innerHTML=\"".$msg["acquisition_force_ttc"]."\";
							document.getElementById(\"force_debit[$i]\").value=2;
						}else{				
							document.getElementById(\"force_ht_ttc_$i\").innerHTML=\"".$msg["acquisition_force_ht"]."\";
							document.getElementById(\"force_debit[$i]\").value=1;
						}				
					'				
				>".$msg["acquisition_force_ht"]."</span>";				
			}else{
				$force_ht_ttc="<br />
				<input type='hidden' id='force_debit[$i]' name='force_debit[$i]' value='2' />				
				<span class='force_ht_ttc' id='force_ht_ttc_$i'				
					onclick='
						if(document.getElementById(\"force_debit[$i]\").value==2){
							document.getElementById(\"force_ht_ttc_$i\").innerHTML=\"".$msg["acquisition_force_ht"]."\";
							document.getElementById(\"force_debit[$i]\").value=1;
						}else{				
							document.getElementById(\"force_ht_ttc_$i\").innerHTML=\"".$msg["acquisition_force_ttc"]."\";
							document.getElementById(\"force_debit[$i]\").value=2;
						}				
					'				
				>".$msg["acquisition_force_ttc"]."</span>";
			}	
		}
		$form = str_replace('!!onchange_tva!!', $onchange_tva, $form);
		$form = str_replace('!!convert_prix!!', $convert_prix, $form);
		$form = str_replace('!!convert_ht_ttc!!', $convert_ht_ttc, $form);
		$form = str_replace('!!force_ht_ttc!!', $force_ht_ttc, $form);
		$form = str_replace('!!rem!!', $row->remise, $form);
		
		if ($mod) {
			
			if ($row->num_type) {
				$tp = new types_produits($row->num_type);
				$form = str_replace('!!typ!!', $tp->id_produit, $form);
				$form = str_replace('!!lib_typ!!', htmlentities($tp->libelle, ENT_QUOTES, $charset), $form);
			} else {
				$form = str_replace('!!typ!!', '0', $form);
				$form = str_replace('!!lib_typ!!', '', $form);
			}
			if ($row->num_rubrique) {
				$rub = new rubriques($row->num_rubrique);
				$form = str_replace('!!rub!!', $rub->id_rubrique, $form);
				$form = str_replace('!!lib_rub!!', htmlentities($rub->libelle, ENT_QUOTES, $charset), $form);
			} else {
				$form = str_replace('!!rub!!', '0', $form);
				$form = str_replace('!!lib_rub!!', '', $form);
			}
			$form = str_replace('!!id_sug!!', $row->num_acquisition, $form);
			$form = str_replace('!!id_lig!!', $row->id_ligne, $form);
			$form = str_replace('!!typ_lig!!', $row->type_ligne, $form);
			$form = str_replace('!!id_prod!!', $row->num_produit, $form);
			
		} else {
			
			//recherche des lignes de livraison
			$lig_liv = lignes_actes::getLivraisons($row->id_ligne);
			$rec = 0;
			while (($row_liv = mysql_fetch_object($lig_liv))) {
				$rec = $rec + $row_liv->nb;
			}
			$form = str_replace('!!rec!!', $rec, $form);
			if ($row->num_type) {
				$tp = new types_produits($row->num_type);
				$form = str_replace('!!lib_typ!!', htmlentities($tp->libelle, ENT_QUOTES, $charset), $form);
			} else {
				$form = str_replace('!!lib_typ!!', '', $form);
			}
			if ($row->num_rubrique) {
				$rub = new rubriques($row->num_rubrique);
				$form = str_replace('!!lib_rub!!', htmlentities($rub->libelle, ENT_QUOTES, $charset), $form);
			} else {
				$form = str_replace('!!lib_rub!!', '', $form);
			}
		}
	}
	$t = array(0=>$i, 1=>$form);
	return $t;
}


//Affiche le formulaire de création de commande à partir d'un devis
function show_cde_from_dev($id_bibli, $id_exer, $id_dev) {
	
	global $msg, $charset;
	global $modif_cde_form;
	global $bt_enr, $bt_val;
	global $sel_date_liv_mod, $sel_date_pay_mod;
	
	//Recuperation etablissement
	$bibli = new entites($id_bibli);
	$lib_bibli = htmlentities($bibli->raison_sociale, ENT_QUOTES, $charset);
	
	$form = $modif_cde_form;
	
	//Recuperation devis
	$dev = new actes($id_dev);
	
	//Recuperation exercice
	$exer = new exercices($id_exer);
	$lib_exer = htmlentities($exer->libelle, ENT_QUOTES, $charset);

	$titre = htmlentities($msg['acquisition_cde_cre'], ENT_QUOTES, $charset);
	$date_cre = formatdate(today());
	//$numero = calcNumero($id_bibli, TYP_ACT_CDE); 
	$statut = STA_ACT_AVA;
	$sel_statut = "<input type='hidden' id='statut' name='statut' value='".$statut."' />";
	$sel_statut.=htmlentities($msg['acquisition_cde_aval'], ENT_QUOTES, $charset);
	$id_fou = $dev->num_fournisseur;
	$fou = new entites($id_fou);
	$lib_fou = htmlentities($fou->raison_sociale, ENT_QUOTES, $charset);
	$coord = entites::get_coordonnees($fou->id_entite, '1');
	if (mysql_num_rows($coord) != 0) {
		$coord = mysql_fetch_object($coord);
		$id_adr_fou = $coord->id_contact;
		if($coord->libelle != '') $adr_fou = htmlentities($coord->libelle, ENT_QUOTES, $charset)."\n";
		if($coord->contact != '') $adr_fou.= htmlentities($coord->contact, ENT_QUOTES, $charset)."\n";
		if($coord->adr1 != '') $adr_fou.= htmlentities($coord->adr1, ENT_QUOTES, $charset)."\n";
		if($coord->adr2 != '') $adr_fou.= htmlentities($coord->adr2, ENT_QUOTES, $charset)."\n";
		if($coord->cp !='') $adr_fou.= htmlentities($coord->cp, ENT_QUOTES, $charset).' ';
		if($coord->ville != '') $adr_fou.= htmlentities($coord->ville, ENT_QUOTES, $charset);

	} else {
		$id_adr_fou = '0';
		$adr_fou = '';
	}
	$id_adr_fac = $dev->num_contact_fact;
	if ($id_adr_fac) {
		$coord_fac = new coordonnees($id_adr_fac);
		if($coord_fac->libelle != '') $adr_fac = htmlentities($coord_fac->libelle, ENT_QUOTES, $charset)."\n";
		if($coord->contact !='') $adr_fac.=  htmlentities($coord_fac->contact, ENT_QUOTES, $charset)."\n";
		if($coord_fac->adr1 != '') $adr_fac.= htmlentities($coord_fac->adr1, ENT_QUOTES, $charset)."\n";
		if($coord_fac->adr2 != '') $adr_fac.= htmlentities($coord_fac->adr2, ENT_QUOTES, $charset)."\n";
		if($coord_fac->cp !='') $adr_fac.= htmlentities($coord_fac->cp, ENT_QUOTES, $charset).' ';
		if($coord_fac->ville != '') $adr_fac.= htmlentities($coord_fac->ville, ENT_QUOTES, $charset);	
	} else {
		$id_adr_fac = '0';
		$adr_fac = '';
	}
	$id_adr_liv = $dev->num_contact_livr;
	if ($id_adr_liv) {
		$coord_liv = new coordonnees($id_adr_liv);
		if($coord_liv->libelle != '') $adr_liv = htmlentities($coord_liv->libelle, ENT_QUOTES, $charset)."\n";
		if($coord_liv->contact != '') $adr_liv.= htmlentities($coord_liv->contact, ENT_QUOTES, $charset)."\n";
		if($coord_liv->adr1 != '') $adr_liv.= htmlentities($coord_liv->adr1, ENT_QUOTES, $charset)."\n";
		if($coord_liv->adr2 != '') $adr_liv.= htmlentities($coord_liv->adr2, ENT_QUOTES, $charset)."\n";
		if($coord_liv->cp !='') $adr_liv.= htmlentities($coord_liv->cp, ENT_QUOTES, $charset).' ';
		if($coord_liv->ville != '') $adr_liv.= htmlentities($coord_liv->ville, ENT_QUOTES, $charset);
	} else {
		$id_adr_liv = '0';
		$adr_liv = '';
	}
	$comment = '';
	$comment_i = htmlentities($dev->commentaires_i, ENT_QUOTES, $charset);;
	$lien_dev = "<a href=\"./acquisition.php?categ=ach&sub=devi&action=modif&id_bibli=".$id_bibli."&id_dev=".$id_dev."\">".$dev->numero."</a>";	
	$liens_liv = '';
	$liens_fac = '';
	$ref = htmlentities($dev->reference, ENT_QUOTES, $charset);
	$sel_date_pay=$sel_date_pay_mod;
	$date_pay = '';
	$date_pay_lib = $msg['parperso_nodate'];		
	$num_pay = '';
	$sel_date_liv=$sel_date_liv_mod;
	$date_liv = '';
	$date_liv_lib = $msg['parperso_nodate'];		
	$devise = htmlentities($dev->devise, ENT_QUOTES, $charset);

	$bt_dup = '';
	$bt_rec = '';
	$bt_fac = '';
	$bt_imp = '';
	$bt_sol = '';
	$bt_audit = '';
	$bt_sup = '';
	
	$lignes = show_lig_cde_from_dev($id_dev);	
	
	$id_cde=0;
	
	//complement formulaire
	$form = str_replace('<!-- sel_statut -->', $sel_statut, $form);
	$form = str_replace('<!-- sel_date_liv -->', $sel_date_liv, $form);
	$form = str_replace('<!-- sel_date_pay -->', $sel_date_pay, $form);
	$form = str_replace('<!-- bouton_enr -->', $bt_enr, $form);
	$form = str_replace('<!-- bouton_val -->', $bt_val, $form);
	$form = str_replace('<!-- bouton_imp -->', $bt_imp, $form);
	$form = str_replace('<!-- bouton_dup -->', $bt_dup, $form);
	$form = str_replace('<!-- bouton_rec -->', $bt_rec, $form);
	$form = str_replace('<!-- bouton_fac -->', $bt_fac, $form);
	$form = str_replace('<!-- bouton_audit -->', $bt_audit, $form);
	$form = str_replace('<!-- bouton_sol -->', $bt_sol, $form);
	$form = str_replace('<!-- bouton_sup -->', $bt_sup, $form);
	$form = str_replace('!!act_nblines!!', $lignes[0], $form);
	$form = str_replace('<!-- lignes -->', $lignes[1], $form);
	
	//Remplissage formulaire
	$form = str_replace('!!form_title!!', $titre, $form);		
	$form = str_replace('!!id_bibli!!', $id_bibli, $form);	
	$form = str_replace('!!lib_bibli!!', $lib_bibli, $form);	
	$form = str_replace('!!id_exer!!', $exer->id_exercice, $form);
	$form = str_replace('!!lib_exer!!', $lib_exer, $form);
	$form = str_replace('!!id_cde!!', $id_cde, $form);
	$form = str_replace('!!date_cre!!', $date_cre, $form);
	$form = str_replace('!!dat_def_lib!!', formatdate(today()), $form);
	$form = str_replace('!!dat_def!!', preg_replace('/-/', '', today()), $form);
	$form = str_replace('!!numero!!', "", $form);
	$form = str_replace('!!statut!!', $statut, $form);
	$form = str_replace('!!id_fou!!', $id_fou, $form);
	$form = str_replace('!!lib_fou!!', $lib_fou, $form);
	$form = str_replace('!!id_adr_fou!!', $id_adr_fou, $form);
	$form = str_replace('!!adr_fou!!', $adr_fou, $form);
	$form = str_replace('!!date_liv!!', $date_liv, $form);
	$form = str_replace('!!date_liv_lib!!', $date_liv_lib, $form);
	$form = str_replace('!!date_pay!!', $date_pay, $form);
	$form = str_replace('!!date_pay_lib!!', $date_pay_lib, $form);
	$form = str_replace('!!num_pay!!', $num_pay, $form);
	$form = str_replace('!!id_adr_liv!!', $id_adr_liv, $form);
	$form = str_replace('!!adr_liv!!', $adr_liv, $form);
	$form = str_replace('!!id_adr_fac!!', $id_adr_fac, $form);
	$form = str_replace('!!adr_fac!!', $adr_fac, $form);
	$form = str_replace('!!comment!!', $comment, $form);
	$form = str_replace('!!comment_i!!', $comment_i, $form);
	$form = str_replace('!!ref!!', $ref, $form);
	$form = str_replace('!!id_dev!!', $id_dev, $form);	
	$form = str_replace('!!devise!!', $devise, $form);
	$form = str_replace('!!lien_dev!!', $lien_dev, $form);
	$form = str_replace('!!liens_fac!!', $liens_fac, $form);
	$form = str_replace('!!liens_liv!!', $liens_liv, $form);

	print $form;
	
}


//Affiche les lignes d'une commande a partir d'un devis
function show_lig_cde_from_dev($id_dev) {
	
	global $charset;
	global $acquisition_gestion_tva;
	global $modif_cde_row_form;
	
	$form = "";
	$i=0;	
	if (!$id_dev) {
		$t = array(0=>$i, $form);
		return $t;
	}
	
	$lignes = actes::getLignes($id_dev);
	while (($row = mysql_fetch_object($lignes))) {
		
		$i++;	
		$form.= $modif_cde_row_form;
		$form = str_replace('!!no!!', $i, $form);
		$form = str_replace('!!code!!', htmlentities($row->code, ENT_QUOTES, $charset), $form);
		$form = str_replace('!!lib!!', htmlentities($row->libelle, ENT_QUOTES, $charset), $form);
		$form = str_replace('!!qte!!', $row->nb, $form);
		$form = str_replace('!!prix!!', $row->prix, $form);
		if ($acquisition_gestion_tva) {
			$form = str_replace('!!tva!!', $row->tva , $form);
		}
		if ($row->num_type) {
			$tp = new types_produits($row->num_type);
			$form = str_replace('!!typ!!', $tp->id_produit, $form);
			$form = str_replace('!!lib_typ!!', htmlentities($tp->libelle, ENT_QUOTES, $charset), $form);
		} else {
			$form = str_replace('!!typ!!', '0', $form);
			$form = str_replace('!!lib_typ!!', '', $form);
		}
		$form = str_replace('!!rem!!', $row->remise, $form);
		$form = str_replace('!!rub!!', '0', $form);
		$form = str_replace('!!lib_rub!!', '', $form);
		$form = str_replace('!!id_sug!!', $row->num_acquisition, $form);
		$form = str_replace('!!id_lig!!', $row->id_ligne, $form);
		$form = str_replace('!!typ_lig!!', $row->type_ligne, $form);
		$form = str_replace('!!id_prod!!', $row->num_produit, $form);
				
	}
	$t = array(0=>$i, 1=>$form);
	return $t;
}


//Affiche la liste des etablissements et exercices pour choix depuis suggestions
function show_list_biblio_from_sug($sugchk) {
	
	global $dbh;
	global $msg, $charset;
	global $tab_bib;
	global $current_module;
	$sugchk = rawurlencode(serialize($sugchk));

	//Affichage de la liste des etablissements et exercices auxquels a acces l'utilisateur
	$te = array();
	foreach($tab_bib[0] as $k=>$v) {
		$qe =  entites::getCurrentExercices($v);
		$re = mysql_query($qe, $dbh);
		while (($rowe=mysql_fetch_object($re))) {
			$te[$rowe->id_exercice][0]=$v;
			$te[$rowe->id_exercice][1]=$tab_bib[1][$k];
			$te[$rowe->id_exercice][2]=$rowe->libelle; 
		}
 
	}

	switch (count($te)) {
		case 0 :
			//Pas d'exercice actif
			$error_msg.= htmlentities($msg["acquisition_err_exer"],ENT_QUOTES, $charset)."<div class='row'></div>";	
			error_message($msg[321], $error_msg.htmlentities($msg["acquisition_err_par"],ENT_QUOTES, $charset), '1', './admin.php?categ=acquisition');
			die;
			break;

		default:

			$aff = "<form class='form-".$current_module."' id='list_biblio_form' name='list_biblio_form' method='post' action=\"\" >";
			$aff.= "<input type='hidden' id='sugchk' name='sugchk' value='".$sugchk."' />";
			$aff.= "<h3>".htmlentities($msg['acquisition_menu_chx_ent_exe'], ENT_QUOTES, $charset)."</h3><div class='row'></div>";
			$aff.= "<table>";
			$parity = 1;
			$tb = array();
			foreach($te as $key=>$value) {
				if (in_array($value[0], $tb)===false) {
					
					$tb[]=$value[0];

					if ($parity % 2) {
						$pair_impair = "even";
					} else {
						$pair_impair = "odd";
					}
					$parity += 1;
					$aff.= "<tr class='".$pair_impair."' ><th>".htmlentities($value[1], ENT_QUOTES, $charset)."</th></tr>";

				}

				if ($parity % 2) {
					$pair_impair = "even";
				} else {
					$pair_impair = "odd";
				}
				$parity += 1;
				$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" onmousedown=\"document.forms['list_biblio_form'].setAttribute('action','./acquisition.php?categ=ach&sub=cmde&action=from_sug_next&id_bibli=".$value[0]."&id_exer=".$key."');document.forms['list_biblio_form'].submit(); \" ";
				$aff.= "<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer' ><td><i>".htmlentities($value[2], ENT_QUOTES, $charset)."</i></td></tr>";
			}

			$aff.=" </table></form>";
			if (count($te) == '1') $aff.= "<script type='text/javascript'>document.forms['list_biblio_form'].setAttribute('action','./acquisition.php?categ=ach&sub=cmde&action=from_sug_next&id_bibli=".$value[0]."&id_exer=".$key."');document.forms['list_biblio_form'].submit();</script>";
			print $aff;
			break;		
	}

}


//Affiche le formulaire de création de commande depuis suggestions
function show_cde_from_sug($id_bibli, $id_exer, $sugchk) {
	
	global $msg, $charset;
	global $modif_cde_form;
	global $bt_enr, $bt_val;
	global $sel_date_liv_mod, $sel_date_pay_mod;
	global $pmb_gestion_devise;
	global $p_user;
	
	//Recuperation etablissement
	$bibli = new entites($id_bibli);
	$lib_bibli = htmlentities($bibli->raison_sociale, ENT_QUOTES, $charset);
	
	//Prise en compte des adresses utilisateurs par défaut 	
	$tab1 = explode('|', $p_user->speci_coordonnees_etab);
	$tab_adr=array();
	foreach ($tab1 as $v) {
		$tab2=explode(',', $v);
		$tab_adr[$tab2[0]]['id_adr_fac']=$tab2[1];
		$tab_adr[$tab2[0]]['id_adr_liv']=$tab2[2];
	}
	$def_id_adr_fac=$tab_adr[$id_bibli]['id_adr_fac'];
	$def_id_adr_liv=$tab_adr[$id_bibli]['id_adr_liv'];
	
	$form = $modif_cde_form;
	
	//Recuperation exercice
	$exer = new exercices($id_exer);
	$lib_exer = htmlentities($exer->libelle, ENT_QUOTES, $charset);

	$titre = htmlentities($msg['acquisition_cde_cre'], ENT_QUOTES, $charset);
	$date_cre = formatdate(today());
	//$numero = calcNumero($id_bibli, TYP_ACT_CDE); 
	$statut = STA_ACT_AVA;
	$sel_statut = "<input type='hidden' id='statut' name='statut' value='".$statut."' />";
	$sel_statut.=htmlentities($msg['acquisition_cde_aval'], ENT_QUOTES, $charset);
	$id_fou = '0';
	$lib_fou = '';
	$id_adr_fou = '0';
	$adr_fou = '';
	if ($def_id_adr_fac) {
		$id_adr_fac = $def_id_adr_fac;
		$coord = new coordonnees($def_id_adr_fac);
	} else {
		$coord_fac = entites::get_coordonnees($id_bibli, '1');
		if (mysql_num_rows($coord_fac) != 0) {
			$coord = mysql_fetch_object($coord_fac);
			$id_adr_fac = $coord->id_contact;
		} else {
			$id_adr_fac='0';
		}
	}
	if ($id_adr_fac) {
		if($coord->libelle != '') $adr_fac = htmlentities($coord->libelle, ENT_QUOTES, $charset)."\n";
		if($coord->contact != '') $adr_fac.= htmlentities($coord->contact, ENT_QUOTES, $charset)."\n";
		if($coord->adr1 != '') $adr_fac.= htmlentities($coord->adr1, ENT_QUOTES, $charset)."\n";
		if($coord->adr2 != '') $adr_fac.= htmlentities($coord->adr2, ENT_QUOTES, $charset)."\n";
		if($coord->cp !='') $adr_fac.= htmlentities($coord->cp, ENT_QUOTES, $charset).' ';
		if($coord->ville != '') $adr_fac.= htmlentities($coord->ville, ENT_QUOTES, $charset);
	} else {
		$adr_fac = '';
	}

	if ($def_id_adr_liv) {
		$id_adr_liv = $def_id_adr_liv;
		$coord = new coordonnees($def_id_adr_liv);
	} else {
		$coord_liv = entites::get_coordonnees($id_bibli, '2');
		if (mysql_num_rows($coord_liv) != 0) {
			$coord = mysql_fetch_object($coord_liv);
			$id_adr_liv = $coord->id_contact;
		} else {
			$id_adr_liv='0';
		}
	}
	if ($id_adr_liv) {
		if($coord->libelle != '') $adr_liv = htmlentities($coord->libelle, ENT_QUOTES, $charset)."\n";
		if($coord->contact != '') $adr_liv.= htmlentities($coord->contact, ENT_QUOTES, $charset)."\n"; 
		if($coord->adr1 != '') $adr_liv.= htmlentities($coord->adr1, ENT_QUOTES, $charset)."\n";
		if($coord->adr2 != '') $adr_liv.= htmlentities($coord->adr2, ENT_QUOTES, $charset)."\n";
		if($coord->cp !='') $adr_liv.= htmlentities($coord->cp, ENT_QUOTES, $charset).' ';
		if($coord->ville != '') $adr_liv.= htmlentities($coord->ville, ENT_QUOTES, $charset);
	} else {
		$id_adr_liv = $id_adr_fac;
		$adr_liv = $adr_fac;
	}
	$comment = '';
	$comment_i = '';
	$id_dev = 0;
	$lien_dev = '';
	$liens_liv = '';
	$liens_fac = '';
	$ref = '';
	$sel_date_pay=$sel_date_pay_mod;
	$date_pay = '';
	$date_pay_lib = $msg['parperso_nodate'];		
	$num_pay = '';
	$sel_date_liv=$sel_date_liv_mod;
	$date_liv = '';
	$date_liv_lib = $msg['parperso_nodate'];		
	$devise = $pmb_gestion_devise;

	$bt_dup = '';
	$bt_rec = '';
	$bt_fac = '';
	$bt_imp = '';
	$bt_sol = '';
	$bt_audit = '';
	$bt_sup = '';
	
	$lignes = show_lig_cde_from_sug($sugchk);	
	
	$id_cde=0;
	
	//complement formulaire
	$form = str_replace('<!-- sel_statut -->', $sel_statut, $form);
	$form = str_replace('<!-- sel_date_liv -->', $sel_date_liv, $form);
	$form = str_replace('<!-- sel_date_pay -->', $sel_date_pay, $form);
	$form = str_replace('<!-- bouton_enr -->', $bt_enr, $form);
	$form = str_replace('<!-- bouton_val -->', $bt_val, $form);
	$form = str_replace('<!-- bouton_dup -->', $bt_dup, $form);
	$form = str_replace('<!-- bouton_rec -->', $bt_rec, $form);
	$form = str_replace('<!-- bouton_fac -->', $bt_fac, $form);
	$form = str_replace('<!-- bouton_imp -->', $bt_imp, $form);
	$form = str_replace('<!-- bouton_audit -->', $bt_audit, $form);
	$form = str_replace('<!-- bouton_sol -->', $bt_sol, $form);
	$form = str_replace('<!-- bouton_sup -->', $bt_sup, $form);
	$form = str_replace('!!act_nblines!!', $lignes[0], $form);
	$form = str_replace('<!-- lignes -->', $lignes[1], $form);
	
	//Remplissage formulaire
	$form = str_replace('!!form_title!!', $titre, $form);		
	$form = str_replace('!!id_bibli!!', $id_bibli, $form);	
	$form = str_replace('!!lib_bibli!!', $lib_bibli, $form);	
	$form = str_replace('!!id_exer!!', $exer->id_exercice, $form);
	$form = str_replace('!!lib_exer!!', $lib_exer, $form);
	$form = str_replace('!!id_cde!!', $id_cde, $form);
	$form = str_replace('!!date_cre!!', $date_cre, $form);
	$form = str_replace('!!dat_def_lib!!', formatdate(today()), $form);
	$form = str_replace('!!dat_def!!', preg_replace('/-/', '', today()), $form);
	$form = str_replace('!!numero!!', "", $form);
	$form = str_replace('!!statut!!', $statut, $form);
	$form = str_replace('!!id_fou!!', $id_fou, $form);
	$form = str_replace('!!lib_fou!!', $lib_fou, $form);
	$form = str_replace('!!id_adr_fou!!', $id_adr_fou, $form);
	$form = str_replace('!!adr_fou!!', $adr_fou, $form);
	$form = str_replace('!!date_liv!!', $date_liv, $form);
	$form = str_replace('!!date_liv_lib!!', $date_liv_lib, $form);
	$form = str_replace('!!date_pay!!', $date_pay, $form);
	$form = str_replace('!!date_pay_lib!!', $date_pay_lib, $form);
	$form = str_replace('!!num_pay!!', $num_pay, $form);
	$form = str_replace('!!id_adr_liv!!', $id_adr_liv, $form);
	$form = str_replace('!!adr_liv!!', $adr_liv, $form);
	$form = str_replace('!!id_adr_fac!!', $id_adr_fac, $form);
	$form = str_replace('!!adr_fac!!', $adr_fac, $form);
	$form = str_replace('!!comment!!', $comment, $form);
	$form = str_replace('!!comment_i!!', $comment_i, $form);
	$form = str_replace('!!ref!!', $ref, $form);
	$form = str_replace('!!id_dev!!', $id_dev, $form);	
	$form = str_replace('!!devise!!', $devise, $form);
	$form = str_replace('!!lien_dev!!', $lien_dev, $form);
	$form = str_replace('!!liens_fac!!', $liens_fac, $form);
	$form = str_replace('!!liens_liv!!', $liens_liv, $form);

	print $form;
	
}


//Affiche les lignes de commande depuis les suggestions
function show_lig_cde_from_sug($sugchk) {
	
	global $dbh,$charset;
	global $acquisition_gestion_tva;
	global $modif_cde_row_form;
	
	$form = "";
	$i=0;	
	
	$arrchk = unserialize(rawurldecode(stripslashes($sugchk)));
	foreach($arrchk as $value) {

		$i++;
		
		$sug = new suggestions($value);
		$form.=$modif_cde_row_form;
		
		$code="";
		$taec="";
		$prix='0';
		$nb='none';
		
		if ($sug->num_notice) {
			$q = "select niveau_biblio from notices where notice_id='".$sug->num_notice."' "; 
			$r = mysql_query($q,$dbh);
			if(mysql_num_rows($r)) {
				$nb=mysql_result($r,0,0);
			}
		}
		
		switch($nb) {
			case 'a' :
				$typ_lig = 1;
				$notice=new sel_article_display($sug->num_notice,'');
				$notice->getData();
				$notice->responsabilites = get_notice_authors($sug->num_notice);
				$notice->doHeader();
				$taec= $notice->titre;
				if($notice->auteur1) {
					$taec.="\n".$notice->auteur1;
				}
				if($notice->in_bull) {
					$taec.="\n".$notice->in_bull;
				}
				$prix=$notice->prix;
				break;
			case 'm' :
				$typ_lig = 1;
				$notice=new sel_mono_display($sug->num_notice,'');
				$notice->getData();
				$notice->responsabilites = get_notice_authors($sug->num_notice);
				$notice->doHeader();
				$code = $notice->code;
				$taec= $notice->titre;
				if($notice->auteur1) {
					$taec.="\n".$notice->auteur1;
				}
				if ($notice->editeur1) {
					$taec.= "\n".$notice->editeur1;
				}
				if ($notice->editeur1 && $notice->ed_date) {
					$taec.= ", ".$notice->ed_date;
				} elseif ($notice->ed_date){
					$taec.= $notice->ed_date;
				}
				if ($notice->collection) {
					$taec.= "\n".$notice->collection;
				}
				$prix=$notice->prix;
				break;
			default :
				$typ_lig = 0;
				$code = htmlentities($sug->code, ENT_QUOTES, $charset);
				$taec= htmlentities($sug->titre,ENT_QUOTES,$charset);
				if ($sug->auteur!="") $taec.= "\n".htmlentities($sug->auteur,ENT_QUOTES,$charset);
				if ($sug->editeur != "") $taec.= "\n".htmlentities($sug->editeur,ENT_QUOTES,$charset);
				$prix=htmlentities($sug->prix, ENT_QUOTES, $charset); 
				break;
		}
		
		$form = str_replace('!!no!!', $i, $form);
		$form = str_replace('!!code!!', $code, $form);
		$form = str_replace('!!lib!!', $taec, $form);
		$form = str_replace('!!qte!!', $sug->nb, $form);
		$form = str_replace('!!prix!!', $prix, $form);
		if ($acquisition_gestion_tva) {
			$form = str_replace('!!tva!!', '0.00', $form);
		}
		$form = str_replace('!!typ!!', '0', $form);
		$form = str_replace('!!lib_typ!!', '', $form);
		$form = str_replace('!!rem!!', '0.00', $form);
		$form = str_replace('!!rub!!', '0', $form);
		$form = str_replace('!!lib_rub!!', '', $form);
		$form = str_replace('!!id_sug!!', $sug->id_suggestion, $form);
		$form = str_replace('!!id_lig!!', '0', $form);
		$form = str_replace('!!typ_lig!!', $typ_lig, $form);
		$form = str_replace('!!id_prod!!', $sug->num_notice, $form);								
	}
	$t = array(0=>$i, 1=>$form);
	return $t;
}


//Solde la commande en l'état
function sold_cde() {
	
	global $id_cde, $comment, $ref, $date_pay, $num_pay;
	
	if(!$id_cde) return;
	
	$cde = new actes($id_cde);
	
	//Commande considérée comme soldée
	$cde->statut = ($cde->statut & (~STA_ACT_ENC));
	$cde->statut = ($cde->statut | STA_ACT_REC);
	
	//Les quantites livrees sur la commande sont-elles entierement facturees
	//Si oui statut commande >> facture
	$tab_cde = actes::getLignes($id_cde);
	$facture = true;
	while(($row_cde = mysql_fetch_object($tab_cde))) {

		$tab_liv = lignes_actes::getLivraisons($row_cde->id_ligne);
		$tab_fac = lignes_actes::getFactures($row_cde->id_ligne);

		$nb_liv = 0;
		while (($row_liv = mysql_fetch_object($tab_liv))) {
			$nb_liv = $nb_liv + $row_liv->nb;
		}

		$nb_fac = 0;
		while(($row_fac = mysql_fetch_object($tab_fac))) {
			$nb_fac = $nb_fac + $row_fac->nb;
		}
		
		if ($nb_liv > $nb_fac) {
			$facture = false;
			break;
		}	
	}
	
	if ($facture) {

		$cde->statut = ($cde->statut | STA_ACT_FAC); //Pas de reste à facturer >>Statut commande = facturée	

		//Si de plus toutes les factures sont payées, Statut commande=payé
		$tab_pay = liens_actes::getChilds($id_cde, TYP_ACT_FAC);
		$paye= true;
		while (($row_pay = mysql_fetch_object($tab_pay))) {
			if(($row_pay->statut & STA_ACT_PAY) != STA_ACT_PAY){
				$paye = false;
				break;
			}
		}
		if ($paye) $cde->statut = ($cde->statut | STA_ACT_PAY);		

	} else {

		$cde->statut = ($cde->statut & (~STA_ACT_FAC));	//Reste à facturer >>Statut commande = non facturée 	

	}
	$cde->numero=addslashes($cde->numero);
	$cde->commentaires = trim($comment);
	$cde->commentaires_i = addslashes($cde->commentaires_i);
	$cde->reference = trim($ref);
	$cde->date_paiement = $date_pay;
	$cde->num_paiement = trim($num_pay);
	$cde->devise = addslashes($cde->devise);	
	$cde->save();
}


//Archive la commande
function arc_cde(){
	global $id_cde;

	if(!$id_cde) return;
	
	$cde = new actes($id_cde);
	
	//Commande archivée
	$cde->statut = ($cde->statut | STA_ACT_ARC);
	$cde->update_statut();
	
	//Archivage des factures et bl correspondants
	$list_childs = liens_actes::getChilds($id_cde);
	while (($row = mysql_fetch_object($list_childs))) {
		$act = new actes($row->num_acte_lie);
		$act->statut = ($act->statut | STA_ACT_ARC);
		$act->update_statut();
	}
}


//Sauvegarde commande
function update_cde() {
	
	global $id_bibli, $id_exer, $id_cde, $num_cde, $id_dev, $statut;
	global $id_fou;
	global $id_adr_liv, $id_adr_fac;
	global $comment, $comment_i, $ref, $date_pay, $num_pay, $date_liv, $devise;
	global $code, $lib, $qte, $prix, $typ, $tva, $rem, $rub, $id_sug, $id_lig, $typ_lig, $id_prod;
	global $acquisition_gestion_tva;
	global $action;
	global $force_debit;
	
	//Recuperation des lignes valides
	$tab_lig=array();
	if (count($id_lig)) {
		foreach($id_lig as $k=>$v) {
			$code[$k] = trim($code[$k]);
			$lib[$k] = trim($lib[$k]);
			if ($code[$k] !='' || $lib[$k]!='') {		
				$tab_lig[$k]=$v;
			}
		}
	}
	if (!$id_cde) {		//Creation de commande

		$cde = new actes();
		$cde ->type_acte = TYP_ACT_CDE;
		$cde->num_entite = $id_bibli;
		$cde->num_exercice = $id_exer;
		/*$num_cde=trim($num_cde);
		if ($num_cde!='') {
			$cde->numero=$num_cde;
		} else {
			$cde->calc();
		}*/
		if ($action == 'valid') {
			$cde->statut = STA_ACT_ENC;
		} else {
			$cde->statut = STA_ACT_AVA;			
		}
		$cde->num_fournisseur = $id_fou;
		$cde->num_contact_livr = $id_adr_liv;
		$cde->num_contact_fact = $id_adr_fac;
		$cde->commentaires = trim($comment);
		$cde->commentaires_i = trim($comment_i);
		$cde->reference = trim($ref);
		$cde->date_paiement = $date_pay;
		$cde->num_paiement = trim($num_pay);
		$cde->date_ech = $date_liv;
		$cde->devise = trim($devise);
		$cde->save();

		$id_cde= $cde->id_acte;
		
		//creation des liens entre actes
		if ($id_dev) {
			$la = new liens_actes($id_dev, $id_cde);
		}
		
		//creation des lignes de commande
		foreach($tab_lig as $k=>$v) {
			
			$lig_cde = new lignes_actes();
			$lig_cde->type_ligne = $typ_lig[$k];
			$lig_cde->num_acte = $id_cde;
			$lig_cde->num_rubrique = $rub[$k];
			$lig_cde->num_produit = $id_prod[$k];
			$lig_cde->num_acquisition = $id_sug[$k];
			$lig_cde->num_type = $typ[$k];
			$lig_cde->code = trim($code[$k]);
			$lig_cde->libelle = trim($lib[$k]);
			$lig_cde->prix = $prix[$k]; 
			if ($acquisition_gestion_tva) {
				$lig_cde->tva = $tva[$k];				
			} else {
				$lig_cde->tva = '0.00';
			}			
			$lig_cde->debit_tva= $force_debit[$k];
			$lig_cde->remise = $rem[$k];
			$lig_cde->nb = round($qte[$k]);
			$lig_cde->date_ech = $date_liv;			
			$lig_cde->date_cre = today();			
//TODO Verifier que ce statut est utile
			$lig_cde->statut = $cde->statut;
			$lig_cde->save();
		}	

		//Mise à jour du statut des suggestions et envoi email suivi de suggestion
		$sug_map = new suggestions_map();
		$sug_map->doTransition('ORDERED', $id_sug);
		
		
	} else {	//modification de commande
		
		$cde = new actes($id_cde);
		$old_statut=$statut;
		
		if ($old_statut != STA_ACT_ENC) {	//Commande a valider
		
			/*$num_cde=trim($num_cde);
			if ($num_cde!='') {
				$cde->numero=$num_cde;
			} else {
				$cde->numero=addslashes($cde->numero);
			}*/
			if ($action == 'valid') { 
				$cde->statut = STA_ACT_ENC; //Statut commande = A valider->en cours
			}
			$cde->num_fournisseur = $id_fou;
			$cde->num_contact_livr = $id_adr_liv;
			$cde->num_contact_fact = $id_adr_fac;
			$cde->commentaires = trim($comment);
			$cde->commentaires_i = trim($comment_i);
			$cde->reference = trim($ref);
			$cde->date_paiement = $date_pay;
			$cde->num_paiement = trim($num_pay);
			$cde->date_ech = $date_liv;
			$cde->devise = trim($devise);			
			$cde->save();
			
			//maj des lignes de commande
			foreach($tab_lig as $k=>$v) {
							
				$lig_cde = new lignes_actes($v);
				$lig_cde->type_ligne = $typ_lig[$k];
				$lig_cde->num_acte = $id_cde;
				$lig_cde->num_rubrique = $rub[$k];
				$lig_cde->num_produit = $id_prod[$k];
				$lig_cde->num_acquisition = $id_sug[$k];
				$lig_cde->num_type = $typ[$k];
				$lig_cde->code = trim($code[$k]);
				$lig_cde->libelle = trim($lib[$k]);
				$lig_cde->prix = $prix[$k];
				if ($acquisition_gestion_tva) {
					$lig_cde->tva = $tva[$k];
				} else {
					$lig_cde->tva = '0.00';
				}
				$lig_cde->debit_tva= $force_debit[$k];		
				$lig_cde->remise = $rem[$k];
				$lig_cde->nb = round($qte[$k]);

				$lig_cde->date_ech = $date_liv;			
				$lig_cde->date_cre = today();
//TODO Verifier que ce statut est utile
				$lig_cde->statut = ($lig_cde->statut | STA_ACT_AVA);			
				$lig_cde->save();
				if($v==0) $tab_lig[$k]=$lig_cde->id_ligne;
			}		
			//suppression des lignes non reprises
			$cde->cleanLignes($id_cde, $tab_lig);

		} else {	//Commande validee
			
			$cde->numero=addslashes($cde->numero);
			$cde->commentaires = trim($comment);
			$cde->commentaires_i = addslashes($cde->commentaires_i);
			$cde->reference = trim($ref);
			$cde->date_paiement = $date_pay;
			$cde->num_paiement = trim($num_pay);
			$cde->devise = addslashes($cde->devise);	
			$cde->save();		
		}				
	}
}


//Duplication de commande
function duplicate_cde($id_bibli, $id_cde) {

	global $msg, $charset;
	global $modif_cde_form;
	global $bt_enr, $bt_val;
	global $sel_date_liv_mod, $sel_date_pay_mod;
	
	//Recuperation etablissement
	$bibli = new entites($id_bibli);
	$lib_bibli = htmlentities($bibli->raison_sociale, ENT_QUOTES, $charset);
	
	$form = $modif_cde_form;
	
	//Recuperation commande
	$cde = new actes($id_cde);
	$exer = new exercices($cde->num_exercice);
	$lib_exer = htmlentities($exer->libelle, ENT_QUOTES, $charset);

	$titre = htmlentities($msg['acquisition_cde_cre'], ENT_QUOTES, $charset);
	$date_cre = formatdate(today());
	$numero = calcNumero($id_bibli, TYP_ACT_CDE); 
	$statut = STA_ACT_AVA;
	$sel_statut = "<input type='hidden' id='statut' name='statut' value='".$statut."' />";
	$sel_statut.=htmlentities($msg['acquisition_cde_aval'], ENT_QUOTES, $charset);
	$id_fou = $cde->num_fournisseur;
	$fou = new entites($id_fou);
	$lib_fou = htmlentities($fou->raison_sociale, ENT_QUOTES, $charset);
	$coord = entites::get_coordonnees($fou->id_entite, '1');
	if (mysql_num_rows($coord) != 0) {
		$coord = mysql_fetch_object($coord);
		$id_adr_fou = $coord->id_contact;
		if($coord->libelle != '') $adr_fou = htmlentities($coord->libelle, ENT_QUOTES, $charset)."\n";
		if($coord->contact !='') $adr_fou.=  htmlentities($coord->contact, ENT_QUOTES, $charset)."\n";
		if($coord->adr1 != '') $adr_fou.= htmlentities($coord->adr1, ENT_QUOTES, $charset)."\n";
		if($coord->adr2 != '') $adr_fou.= htmlentities($coord->adr2, ENT_QUOTES, $charset)."\n";
		if($coord->cp !='') $adr_fou.= htmlentities($coord->cp, ENT_QUOTES, $charset).' ';
		if($coord->ville != '') $adr_fou.= htmlentities($coord->ville, ENT_QUOTES, $charset);
	} else {
		$id_adr_fou = '0';
		$adr_fou = '';
	}
	$id_adr_fac = $cde->num_contact_fact;
	if ($id_adr_fac) {
		$coord_fac = new coordonnees($id_adr_fac);
		if($coord_fac->libelle != '') $adr_fac = htmlentities($coord_fac->libelle, ENT_QUOTES, $charset)."\n";
		if($coord->contact !='') $adr_fac.=  htmlentities($coord_fac->contact, ENT_QUOTES, $charset)."\n";
		if($coord_fac->adr1 != '') $adr_fac.= htmlentities($coord_fac->adr1, ENT_QUOTES, $charset)."\n";
		if($coord_fac->adr2 != '') $adr_fac.= htmlentities($coord_fac->adr2, ENT_QUOTES, $charset)."\n";
		if($coord_fac->cp !='') $adr_fac.= htmlentities($coord_fac->cp, ENT_QUOTES, $charset).' ';
		if($coord_fac->ville != '') $adr_fac.= htmlentities($coord_fac->ville, ENT_QUOTES, $charset);	
	} else {
		$id_adr_fac = '0';
		$adr_fac = '';
	}
	$id_adr_liv = $cde->num_contact_livr;
	if ($id_adr_liv) {
		$coord_liv = new coordonnees($id_adr_liv);
		if($coord_liv->libelle != '') $adr_liv = htmlentities($coord_liv->libelle, ENT_QUOTES, $charset)."\n";
		if($coord_liv->contact != '') $adr_liv.= htmlentities($coord_liv->contact, ENT_QUOTES, $charset)."\n";
		if($coord_liv->adr1 != '') $adr_liv.= htmlentities($coord_liv->adr1, ENT_QUOTES, $charset)."\n";
		if($coord_liv->adr2 != '') $adr_liv.= htmlentities($coord_liv->adr2, ENT_QUOTES, $charset)."\n";
		if($coord_liv->cp !='') $adr_liv.= htmlentities($coord_liv->cp, ENT_QUOTES, $charset).' ';
		if($coord_liv->ville != '') $adr_liv.= htmlentities($coord_liv->ville, ENT_QUOTES, $charset);
	} else {
		$id_adr_liv = '0';
		$adr_liv = '';
	}
	$comment = '';
	$comment_i = htmlentities($cde->commentaires_i, ENT_QUOTES, $charset);
	$id_dev = liens_actes::getDevis($id_cde); 	
	if ($id_dev) {	
		$dev=new actes($id_dev);
		$lien_dev = "<a href=\"./acquisition.php?categ=ach&sub=devi&action=modif&id_bibli=".$id_bibli."&id_dev=".$id_dev."\">".$dev->numero."</a>";	
	}
	$liens_liv = '';
	$liens_fac = '';
	$ref = htmlentities($cde->reference, ENT_QUOTES, $charset);
	$sel_date_pay=$sel_date_pay_mod;
	$date_pay = '';
	$date_pay_lib = $msg['parperso_nodate'];		
	$num_pay = '';
	$sel_date_liv=$sel_date_liv_mod;
	$date_liv = '';
	$date_liv_lib = $msg['parperso_nodate'];		
	$devise = htmlentities($cde->devise, ENT_QUOTES, $charset);
		
	$bt_dup = '';
	$bt_rec = '';
	$bt_fac = '';
	$bt_imp = '';
	$bt_sol = '';
	$bt_audit = '';
	$bt_sup = '';
	
	$lignes= show_lig_cde($id_cde);
	
	$id_cde=0;
			
	//complement formulaire
	$form = str_replace('<!-- sel_statut -->', $sel_statut, $form);
	$form = str_replace('<!-- sel_date_liv -->', $sel_date_liv, $form);
	$form = str_replace('<!-- sel_date_pay -->', $sel_date_pay, $form);
	$form = str_replace('<!-- bouton_enr -->', $bt_enr, $form);
	$form = str_replace('<!-- bouton_val -->', $bt_val, $form);
	$form = str_replace('<!-- bouton_dup -->', $bt_dup, $form);
	$form = str_replace('<!-- bouton_rec -->', $bt_rec, $form);
	$form = str_replace('<!-- bouton_fac -->', $bt_fac, $form);
	$form = str_replace('<!-- bouton_imp -->', $bt_imp, $form);
	$form = str_replace('<!-- bouton_audit -->', $bt_audit, $form);
	$form = str_replace('<!-- bouton_sol -->', $bt_sol, $form);
	$form = str_replace('<!-- bouton_sup -->', $bt_sup, $form);
	$form = str_replace('!!act_nblines!!', $lignes[0], $form);
	$form = str_replace('<!-- lignes -->', $lignes[1], $form);
	
	//Remplissage formulaire
	$form = str_replace('!!form_title!!', $titre, $form);		
	$form = str_replace('!!id_bibli!!', $id_bibli, $form);	
	$form = str_replace('!!lib_bibli!!', $lib_bibli, $form);	
	$form = str_replace('!!id_exer!!', $exer->id_exercice, $form);
	$form = str_replace('!!lib_exer!!', $lib_exer, $form);
	$form = str_replace('!!id_cde!!', $id_cde, $form);
	$form = str_replace('!!date_cre!!', $date_cre, $form);
	$form = str_replace('!!dat_def_lib!!', formatdate(today()), $form);
	$form = str_replace('!!dat_def!!', preg_replace('/-/', '', today()), $form);
	$form = str_replace('!!numero!!', $numero, $form);
	$form = str_replace('!!statut!!', $statut, $form);
	$form = str_replace('!!id_fou!!', $id_fou, $form);
	$form = str_replace('!!lib_fou!!', $lib_fou, $form);
	$form = str_replace('!!id_adr_fou!!', $id_adr_fou, $form);
	$form = str_replace('!!adr_fou!!', $adr_fou, $form);
	$form = str_replace('!!date_liv!!', $date_liv, $form);
	$form = str_replace('!!date_liv_lib!!', $date_liv_lib, $form);
	$form = str_replace('!!date_pay!!', $date_pay, $form);
	$form = str_replace('!!date_pay_lib!!', $date_pay_lib, $form);
	$form = str_replace('!!num_pay!!', $num_pay, $form);
	$form = str_replace('!!id_adr_liv!!', $id_adr_liv, $form);
	$form = str_replace('!!adr_liv!!', $adr_liv, $form);
	$form = str_replace('!!id_adr_fac!!', $id_adr_fac, $form);
	$form = str_replace('!!adr_fac!!', $adr_fac, $form);
	$form = str_replace('!!comment!!', $comment, $form);
	$form = str_replace('!!comment_i!!', $comment_i, $form);
	$form = str_replace('!!ref!!', $ref, $form);
	$form = str_replace('!!id_dev!!', $id_dev, $form);	
	$form = str_replace('!!devise!!', $devise, $form);
	$form = str_replace('!!lien_dev!!', $lien_dev, $form);
	$form = str_replace('!!liens_fac!!', $liens_fac, $form);
	$form = str_replace('!!liens_liv!!', $liens_liv, $form);
		
	print $form;
}


//TODO Verifier que budgets saisis si obligatoires
//TODO A transferer depuis frame
//Vérification dépassement de budget
function verif_bud() {

	global $msg;
	global $max_lig;
	global $qte, $prix, $rem, $rub;
	global $error, $error_msg;
	global $acquisition_budget;

	if ($acquisition_budget) {

		$tot_rub = array();
		$tot_bud = array();
		for ($i=1;$i<=$max_lig;$i++) {
			$tot_rub[$rub[$i]] = 0;
		}

		//récupère le total de la commande par rubrique
		for ($i=1;$i<=$max_lig;$i++) {
			$tot_rub[$rub[$i]] = $tot_rub[$rub[$i]] + ( $qte[$i]*$prix[$i]*(1 - ($rem[$i]/100) ) );
		}

		//récupère le total de la commande par budget
		foreach ($tot_rub as $key=>$value) {
			$r = new rubriques($key);
			if (!array_key_exists($r->num_budget, $tot_bud)) $tot_bud[$r->num_budget] = 0; 
			$tot_bud[$r->num_budget] = $tot_bud[$r->num_budget] + $value; 
		}
		
		
		//Vérifie que les budgets affectés par rubrique ne sont pas dépassés		
		foreach ($tot_rub as $key=>$value) {
		
			$r = new rubriques($key);
			$b = new budgets($r->num_budget);
				
			if ( $b->type_budget == TYP_BUD_RUB ) {	//Budget affecté par rubrique
				
				$mnt_rub = $r->montant;
				$eng_rub = rubriques::calcEngagement($key) + $value;
				//Budget rubrique dépassé ?
				if ($eng_rub > $mnt_rub) {
					$error = true;
					
					$tab_rub = rubriques::listAncetres($key, true);
					
					$lib_rub = $b->libelle.":";
					foreach ($tab_rub as $value) {
						$lib_rub.= $value[1];
						if($value[0] != $key) $lib_rub.= ":";
					}				
					
					$error_msg = $msg['acquisition_rub']." :\\n\\n ".$lib_rub."\\n\\n".$msg['acquisition_act_bud_dep'];
					break;			
				}
			}
		}
			
		//Vérifie que les budgets affectés globalement ne sont pas dépassés
		foreach ($tot_bud as $key=>$value) {

			$b = new budgets($key);
			
			if ( $b->type_budget == TYP_BUD_GLO ) {			
				
				$mnt_bud = $b->montant_global;
				$eng_bud = budgets::calcEngagement($b->id_budget) + $value;
		
				//Budget dépassé ?
				if ($eng_bud > $mnt_bud) {
					$error = true;
					$error_msg = $msg['acquisition_act_tab_bud']." : ".$b->libelle."\\n\\n".$msg['acquisition_act_bud_dep'];
					break;			
				}
			}
		}	
	}
}



function valid_cde_list() {
	global $chk;
	if(is_array($chk)) {
		foreach ($chk as $id_cde) {
			$cde=new actes($id_cde);
			if ($cde->type_acte==TYP_ACT_CDE && $cde->statut==STA_ACT_AVA) {
				$cde->statut=STA_ACT_ENC;
				$cde->update_statut();
			}
		}
	}
}


function delete_cde_list() {
	global $chk;
	if(is_array($chk)) {
		foreach ($chk as $id_cde) {
			$cde=new actes($id_cde);
			if ($cde->type_acte==TYP_ACT_CDE && $cde->statut==STA_ACT_AVA) {
				$cde->delete();
			}
		}
	}
}


function arc_cde_list() {
	global $chk,$id_cde;
	if(is_array($chk)) {
		foreach ($chk as $id_cde) {
			arc_cde();
		}
	}
}


function sold_cde_list() {
	global $chk,$id_cde;
	if(is_array($chk)) {
		foreach ($chk as $id_cde) {
			sold_cde();
		}
	}
}


//Traitement des actions
print "<h1>".htmlentities($msg['acquisition_ach_ges'],ENT_QUOTES, $charset)."&nbsp;:&nbsp;".htmlentities($msg['acquisition_ach_cde'],ENT_QUOTES, $charset)."</h1>";

switch($action) {

	case 'list':
		entites::setSessionBibliId($id_bibli);
		show_list_cde($id_bibli);
		break;

	case 'modif':
		if(!$id_cde && !$id_exer) {
			$url = "./acquisition.php?categ=ach&sub=cmde&action=modif&id_bibli=".$id_bibli."&id_cde=0";
			show_list_exercices($id_bibli, 'show_cde', $url, 0);
		} else {
			show_cde($id_bibli, $id_exer, $id_cde);
		}
		break;

	case 'delete' :
		actes::delete($id_cde);
		liens_actes::delete($id_cde);
		show_list_cde($id_bibli);
		break;

	case 'update' :
	case 'valid' :
		update_cde();
		show_list_cde($id_bibli);
		break;
		
	case 'from_devis' :
		if (!$id_exer) {
			$url = "./acquisition.php?categ=ach&sub=cmde&action=from_devis&id_bibli=".$id_bibli."&id_dev=".$id_dev;
			show_list_exercices($id_bibli, 'show_cde_from_dev', $url, $id_dev);
		} else {
			show_cde_from_dev($id_bibli, $id_exer, $id_dev);
		}
		break; 
		
	case 'from_sug' :
		show_list_biblio_from_sug($chk);
		break; 

	case 'from_sug_next' :
		show_cde_from_sug($id_bibli, $id_exer, $sugchk);
		break; 

	case 'sold':
		sold_cde();
		$statut = STA_ACT_REC;
		show_list_cde($id_bibli);
		break;		

	case 'arc':
		arc_cde();
		$statut = STA_ACT_ARC;
		show_list_cde($id_bibli);
		break;		

	case 'duplicate' :
		duplicate_cde($id_bibli, $id_cde);
		break;
				
	case 'list_valid':
		valid_cde_list();
		show_list_cde($id_bibli);
		break;		

	case 'list_delete' :
		delete_cde_list();
		show_list_cde($id_bibli);
		break;		
		
	case 'list_arc':
		arc_cde_list();
		show_list_cde($id_bibli);
		break;		
		
	case 'list_sold':
		sold_cde_list();
		show_list_cde($id_bibli);
		break;		
		
	default:
		show_list_biblio();	
		break;
		
}
?>
