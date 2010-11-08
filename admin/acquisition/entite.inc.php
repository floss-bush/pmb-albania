<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: entite.inc.php,v 1.26 2010-01-11 15:47:37 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


//gestion des coordonnees des etablissements
require_once("$class_path/entites.class.php");
require_once("$include_path/templates/coordonnees.tpl.php");



function show_list_coord() {

	global $dbh;
	global $msg;
	global $charset;

	print "<table>";

	$q = entites::list_biblio();
	$res = mysql_query($q, $dbh);
	$nbr = mysql_num_rows($res);

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_object($res);
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=acquisition&sub=entite&action=modif&id=$row->id_entite';\" ";
        print ("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>$row->raison_sociale</i></td></tr>");
	}
	print "</table>
		<input class='bouton' type='button' value=' ".htmlentities($msg[acquisition_ajout_biblio],ENT_QUOTES,$charset)." ' onClick=\"document.location='./admin.php?categ=acquisition&sub=entite&action=add'\" />";


}


function show_coord_form($id= 0) {
		
	global $msg;
	global $charset;
	global $coord_form, $coord_form_biblio, $coord_form_suite;
	global $ptab, $script;
	
	$ptab[1] = $ptab[1].$ptab[10].$ptab[11];
	$ptab[1] = str_replace('!!adresse!!', htmlentities($msg[acquisition_adr_fac],ENT_QUOTES, $charset), $ptab[1]);
	$coord_form = str_replace('!!id!!', $id, $coord_form);
	$ptab[3] = str_replace('!!id!!', $id, $ptab[3]);

	if(!$id) {
		$coord_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_ajout_biblio],ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!raison_suppr!!', '', $coord_form);
		$coord_form = str_replace('!!raison!!', '', $coord_form);
		
		$coord_form = str_replace('!!contact!!', $ptab[1], $coord_form);
		$coord_form = str_replace('!!max_coord!!', '2', $coord_form);
		
		$coord_form = str_replace('!!id1!!', '0', $coord_form);
		$coord_form = str_replace('!!lib_1!!', '', $coord_form);		
		$coord_form = str_replace('!!cta_1!!', '', $coord_form);		
		$coord_form = str_replace('!!ad1_1!!', '', $coord_form);
		$coord_form = str_replace('!!ad2_1!!', '', $coord_form);
		$coord_form = str_replace('!!cpo_1!!', '', $coord_form);
		$coord_form = str_replace('!!vil_1!!', '', $coord_form);
		$coord_form = str_replace('!!eta_1!!', '', $coord_form);
		$coord_form = str_replace('!!pay_1!!', '', $coord_form);
		$coord_form = str_replace('!!te1_1!!', '', $coord_form);
		$coord_form = str_replace('!!te2_1!!', '', $coord_form);
		$coord_form = str_replace('!!fax_1!!', '', $coord_form);
		$coord_form = str_replace('!!ema_1!!', '', $coord_form);
		$coord_form = str_replace('!!com_1!!', '', $coord_form);
		$coord_form = str_replace('!!id2!!', '0', $coord_form);
		$coord_form = str_replace('!!lib_2!!', '', $coord_form);		
		$coord_form = str_replace('!!cta_2!!', '', $coord_form);		
		$coord_form = str_replace('!!ad1_2!!', '', $coord_form);
		$coord_form = str_replace('!!ad2_2!!', '', $coord_form);
		$coord_form = str_replace('!!cpo_2!!', '', $coord_form);
		$coord_form = str_replace('!!vil_2!!', '', $coord_form);
		$coord_form = str_replace('!!eta_2!!', '', $coord_form);
		$coord_form = str_replace('!!pay_2!!', '', $coord_form);
		$coord_form = str_replace('!!te1_2!!', '', $coord_form);
		$coord_form = str_replace('!!te2_2!!', '', $coord_form);
		$coord_form = str_replace('!!fax_2!!', '', $coord_form);
		$coord_form = str_replace('!!ema_2!!', '', $coord_form);
		$coord_form = str_replace('!!com_2!!', '', $coord_form);
		
		$coord_form = str_replace('!!commentaires!!', '', $coord_form);
		$coord_form = str_replace('!!siret!!', '', $coord_form);
		$coord_form = str_replace('!!rcs!!', '', $coord_form);
		$coord_form = str_replace('!!naf!!', '', $coord_form);
		$coord_form = str_replace('!!tva!!', '', $coord_form);
		$coord_form = str_replace('!!site_web!!', '', $coord_form);
		$coord_form = str_replace('!!logo!!', '', $coord_form);

		autorisations();

	} else {
		
		$biblio = new entites($id);
		$coord_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_modif_biblio],ENT_QUOTES,$charset), $coord_form);
	
		$coord_form = str_replace('!!raison!!', htmlentities($biblio->raison_sociale,ENT_QUOTES, $charset), $coord_form);

		$coord_form = str_replace('!!contact!!', $ptab[1], $coord_form);

		$row = mysql_fetch_object($biblio->get_coordonnees($biblio->id_entite,'1'));
		$coord_form = str_replace('!!id1!!', $row->id_contact, $coord_form);
		$coord_form = str_replace('!!lib_1!!', htmlentities($row->libelle,ENT_QUOTES,$charset), $coord_form);		
		$coord_form = str_replace('!!cta_1!!', htmlentities($row->contact,ENT_QUOTES,$charset), $coord_form);		
		$coord_form = str_replace('!!ad1_1!!', htmlentities($row->adr1,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!ad2_1!!', htmlentities($row->adr2,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!cpo_1!!', htmlentities($row->cp,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!vil_1!!', htmlentities($row->ville,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!eta_1!!', htmlentities($row->etat,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!pay_1!!', htmlentities($row->pays,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!te1_1!!', htmlentities($row->tel1,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!te2_1!!', htmlentities($row->tel2,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!fax_1!!', htmlentities($row->fax,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!ema_1!!', htmlentities($row->email,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!com_1!!', htmlentities($row->commentaires,ENT_QUOTES,$charset), $coord_form);

		$row = mysql_fetch_object($biblio->get_coordonnees($biblio->id_entite,'2'));
		$coord_form = str_replace('!!id2!!', $row->id_contact, $coord_form);
		$coord_form = str_replace('!!lib_2!!', htmlentities($row->libelle,ENT_QUOTES,$charset), $coord_form);		
		$coord_form = str_replace('!!cta_2!!', htmlentities($row->contact,ENT_QUOTES,$charset), $coord_form);		
		$coord_form = str_replace('!!ad1_2!!', htmlentities($row->adr1,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!ad2_2!!', htmlentities($row->adr2,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!cpo_2!!', htmlentities($row->cp,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!vil_2!!', htmlentities($row->ville,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!eta_2!!', htmlentities($row->etat,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!pay_2!!', htmlentities($row->pays,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!te1_2!!', htmlentities($row->tel1,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!te2_2!!', htmlentities($row->tel2,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!fax_2!!', htmlentities($row->fax,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!ema_2!!', htmlentities($row->email,ENT_QUOTES,$charset), $coord_form);
		$coord_form = str_replace('!!com_2!!', htmlentities($row->commentaires,ENT_QUOTES,$charset), $coord_form);
		
		$liste_coord = $biblio->get_coordonnees($biblio->id_entite,'0');
		$coord_form = str_replace('!!max_coord!!', (mysql_num_rows($liste_coord)+2), $coord_form);
		$i=3;
		while ($row = mysql_fetch_object($liste_coord)) {
			
			$coord_form = str_replace('<!--coord_repetables-->', $ptab[2].'<!--coord_repetables-->', $coord_form);
			$coord_form = str_replace('!!no_X!!', $i, $coord_form);
			$i++;
			$coord_form = str_replace('!!idX!!', $row->id_contact, $coord_form);
			$coord_form = str_replace('!!lib_X!!', htmlentities($row->libelle,ENT_QUOTES,$charset), $coord_form);		
			$coord_form = str_replace('!!cta_X!!', htmlentities($row->contact,ENT_QUOTES,$charset), $coord_form);		
			$coord_form = str_replace('!!ad1_X!!', htmlentities($row->adr1,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!ad2_X!!', htmlentities($row->adr2,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!cpo_X!!', htmlentities($row->cp,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!vil_X!!', htmlentities($row->ville,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!eta_X!!', htmlentities($row->etat,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!pay_X!!', htmlentities($row->pays,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!te1_X!!', htmlentities($row->tel1,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!te2_X!!', htmlentities($row->tel2,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!fax_X!!', htmlentities($row->fax,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!ema_X!!', htmlentities($row->email,ENT_QUOTES,$charset), $coord_form);
			$coord_form = str_replace('!!com_X!!', htmlentities($row->commentaires,ENT_QUOTES,$charset), $coord_form);				
		 
		}
								
		$coord_form = str_replace('!!commentaires!!', htmlentities($biblio->commentaires,ENT_QUOTES, $charset), $coord_form);
		$coord_form = str_replace('!!siret!!', htmlentities($biblio->siret,ENT_QUOTES, $charset), $coord_form);
		$coord_form = str_replace('!!rcs!!', htmlentities($biblio->rcs,ENT_QUOTES, $charset), $coord_form);
		$coord_form = str_replace('!!naf!!', htmlentities($biblio->naf,ENT_QUOTES, $charset), $coord_form);
		$coord_form = str_replace('!!tva!!', htmlentities($biblio->tva,ENT_QUOTES, $charset), $coord_form);
		$coord_form = str_replace('!!site_web!!', htmlentities($biblio->site_web,ENT_QUOTES, $charset), $coord_form);
		$coord_form = str_replace('!!logo!!', htmlentities($biblio->logo,ENT_QUOTES, $charset), $coord_form);
		
		autorisations($biblio->autorisations);
		
		$coord_form = str_replace('<!-- bouton_sup -->', $ptab[3], $coord_form); 
		$coord_form = str_replace('!!raison_suppr!!', addslashes($biblio->raison_sociale), $coord_form);

	}

		
	print confirmation_delete("./admin.php?categ=acquisition&sub=entite&action=del&id=");
	print $script;
	print $coord_form;
	
	
}


function autorisations($autorisations='') {
	
	global $dbh;
	global $charset;
	global $coord_form;
	global $ptab;
	
	$aut = explode(' ',$autorisations);
	
	//Récupération de la liste des utilisateurs
	$q = "SELECT userid, username FROM users order by username ";
	$r = mysql_query($q, $dbh);

	while ($row = mysql_fetch_object($r)) {
			
		$coord_form = str_replace('<!-- autorisations -->', $ptab[4].'<!-- autorisations -->', $coord_form);
		
		$coord_form = str_replace('!!user_name!!', htmlentities($row->username,ENT_QUOTES, $charset), $coord_form);
		$coord_form = str_replace('!!user_id!!', $row->userid, $coord_form);
		if (in_array($row->userid, $aut)) { 
			$chk = 'checked=\'checked\'';
		} else {
			$chk = '';
		}
		$coord_form = str_replace('!!checked!!', $chk, $coord_form);				
	}
		
}


//Traitement des actions
switch($action) {
	case 'update':
		// vérification validité des données fournies.( pas deux raisons sociales identiques)
		$nbr = entites::exists_rs($raison,0,$id);
		if ($nbr > 0) {
			error_form_message($raison.$msg["acquisition_raison_already_used"]);
			break;
		} 

		$biblio = new entites($id);
		$biblio->type_entite = '1';
		$biblio->raison_sociale = $raison;
		$biblio->commentaires = $comment;
		$biblio->siret = $siret;
		$biblio->naf = $naf;
		$biblio->rcs = $rcs;
		$biblio->tva = $tva;
		$biblio->site_web = $site_web;
		$biblio->logo = $logo;
		
		if (is_array($user_aut)) $biblio->autorisations = ' '.implode(' ',$user_aut).' ';
			else $biblio->autorisations = '';
		$biblio->save();
 
		if ($id) {
			//màj des autorisations dans les rubriques
			$biblio->majAutorisations();			
		}


		$id = $biblio->id_entite;
		
		for($i=1; $i <= $max_coord; $i++) {
			switch ($mod_[$i]) {
				case '1' :

					$coord = new coordonnees($no_[$i]); 
					$coord->num_entite = $id;
					if ($i == 1 || $i == 2) $coord->type_coord = $i; else $coord->type_coord = 0;
					$coord->libelle = $lib_[$i];
					$coord->contact = $cta_[$i];
					$coord->adr1 = $ad1_[$i];
					$coord->adr2 = $ad2_[$i];
					$coord->cp = $cpo_[$i];
					$coord->ville = $vil_[$i];
					$coord->etat = $eta_[$i];
					$coord->pays = $pay_[$i];
					$coord->tel1 = $te1_[$i];
					$coord->tel2 = $te2_[$i];
					$coord->fax = $fax_[$i];
					$coord->email = $ema_[$i];
					$coord->save();
					break;
					
				case '-1' : 
					if($no_[$i]) {
						$coord = new coordonnees($no_[$i]);
						$coord->delete($no_[$i]);
					}
					break;
					
				default :
					break;
				
			}
			
		} 
		
		
		show_list_coord();
		break;
		
	case 'add':
		show_coord_form();
		break;
		
	case 'modif':
		if (entites::exists($id)) {
			show_coord_form($id);
		} else {
			show_list_coord();
		}
		break;
		
	case 'del':
		if($id) {
			$total2 = entites::getNbFournisseurs($id);
			$total3 = entites::has_exercices($id);
			$total4 = entites::has_budgets($id);
			$total5 = entites::has_suggestions($id);
			$total7 = entites::has_actes($id);
			if (($total2+$total3+$total4+$total5+$total7)==0) {
				entites::delete($id);
				show_list_coord();
			} else {
				$msg_suppr_err = $msg[acquisition_entite_used] ;
				if ($total2) $msg_suppr_err .= "<br />- ".$msg[acquisition_entite_used_fou] ;
				if ($total3) $msg_suppr_err .= "<br />- ".$msg[acquisition_entite_used_exe] ;
				if ($total4) $msg_suppr_err .= "<br />- ".$msg[acquisition_entite_used_bud] ;
				if ($total5) $msg_suppr_err .= "<br />- ".$msg[acquisition_entite_used_sug] ;
				if ($total7) $msg_suppr_err .= "<br />- ".$msg[acquisition_entite_used_act] ;		
				
				error_message($msg[321], $msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=entite');
			}
		} else {
			show_list_coord();
		}
		break;
	default:
		show_list_coord();
		break;
}

?>
