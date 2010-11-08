<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bul_expl_form.inc.php,v 1.43 2010-02-17 13:53:45 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


if (!$expl_id) {
	echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[4007], $serial_header); // pas d'id, c'est une création
} else {
	echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[4008], $serial_header);
}

/*
le form d'exemplaire renvoit :

$bul_id
$id_form
$org_cb
$expl_id
$expl_bulletin
$expl_typdoc
$expl_cote
$expl_section
$expl_statut
$expl_location
$expl_codestat
$expl_note
$expl_comment
$expl_prix
$expl_owner

*/

function do_selector_bul_section($section_id, $location_id) {
	global $dbh;
 	global $charset;
	
	global $deflt_section;
	global $deflt_location;
	
	if (!$section_id) $section_id=$deflt_section ;
	if (!$location_id) $location_id=$deflt_location;

	$rqtloc = "SELECT idlocation FROM docs_location order by location_libelle";
	$resloc = mysql_query($rqtloc, $dbh);
	while ($loc=mysql_fetch_object($resloc)) {
		$requete = "SELECT idsection, section_libelle FROM docs_section, docsloc_section where idsection=num_section and num_location='$loc->idlocation' order by section_libelle";
		$result = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($result);
		if ($nbr_lignes) {
			if ($loc->idlocation==$location_id) $selector .= "<div id=\"docloc_section".$loc->idlocation."\" style=\"display:block\">";
				else $selector .= "<div id=\"docloc_section".$loc->idlocation."\" style=\"display:none\">";
			$selector .= "<select name='f_ex_section".$loc->idlocation."' id='f_ex_section".$loc->idlocation."'>";
			while($line = mysql_fetch_row($result)) {
				$selector .= "<option value='$line[0]'";
				$line[0] == $section_id ? $selector .= ' SELECTED>' : $selector .= '>';
	 			$selector .= htmlentities($line[1],ENT_QUOTES, $charset).'</option>';
				}                                         
			$selector .= '</select></div>';
			}                 
		}
	return $selector;                         
}                                                 

function bul_do_form($obj, $bul_id=0) {
	// $obj = objet contenant les propriétés de l'exemplaire associé
	global $bul_expl_form;
	global $msg; // pour texte du bouton supprimer
	global $dbh;
	global $pmb_type_audit,$select_categ_prop ;
	global $pmb_antivol;
	global $option_num_auto;
	global $pmb_rfid_activate,$pmb_rfid_serveur_url,$charset;
	global $pmb_expl_show_dates;
				
	if (isset($option_num_auto)) {
  		$requete="DELETE from exemplaires_temp where sess not in (select SESSID from sessions)";
   		$res = mysql_query($requete,$dbh);
    	//Appel à la fonction de génération automatique de cb
    	$code_exemplaire =init_gen_code_exemplaire(0,$obj->expl_bulletin);	
    	do {
    		$code_exemplaire = gen_code_exemplaire(0,$obj->expl_bulletin,$code_exemplaire);
    		$requete="select expl_cb from exemplaires WHERE expl_cb='$code_exemplaire'";
    		$res0 = mysql_query($requete,$dbh);
    		$requete="select cb from exemplaires_temp WHERE cb='$code_exemplaire'";
    		$res1 = mysql_query($requete,$dbh);
    	} while((mysql_num_rows($res0)||mysql_num_rows($res1)));
    		
   		//Memorise dans temps le cb et la session pour le cas de multi utilisateur session
   		$obj->expl_cb = $code_exemplaire;
   		$requete="INSERT INTO exemplaires_temp (cb ,sess) VALUES ('$obj->expl_cb','".SESSid."')";
   		$res = mysql_query($requete,$dbh);
	}
	
	// l'annulation du form renvoit à :
	$annuler = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=".$obj->expl_bulletin;
	$action = "./catalog.php?categ=serials&sub=bulletinage&action=expl_update";

	// mise à jour des champs de gestion
	$bul_expl_form = str_replace('!!bul_id!!', $obj->expl_bulletin, $bul_expl_form);
	$bul_expl_form = str_replace('!!id_form!!', md5(microtime()), $bul_expl_form);
	$bul_expl_form = str_replace('!!org_cb!!', htmlentities($obj->expl_cb,ENT_QUOTES, $charset), $bul_expl_form);	
	$bul_expl_form = str_replace('!!expl_id!!', $obj->expl_id, $bul_expl_form);
	
	$bul_expl_form = str_replace('!!action!!', $action, $bul_expl_form);
	$bul_expl_form = str_replace('!!id!!', $obj->expl_notice, $bul_expl_form);
	$bul_expl_form = str_replace('!!cb!!', htmlentities($obj->expl_cb,ENT_QUOTES, $charset), $bul_expl_form);
	$bul_expl_form = str_replace('!!nbparts!!',   htmlentities($obj->expl_nbparts  , ENT_QUOTES, $charset), $bul_expl_form);
	$bul_expl_form = str_replace('!!note!!', $obj->expl_note, $bul_expl_form);
	$bul_expl_form = str_replace('!!comment!!', $obj->expl_comment, $bul_expl_form);
	$bul_expl_form = str_replace('!!cote!!', $obj->expl_cote, $bul_expl_form);
	$bul_expl_form = str_replace('!!prix!!', $obj->expl_prix, $bul_expl_form);

	// select "type document"
	$bul_expl_form = str_replace('!!type_doc!!',
				do_selector('docs_type', 'expl_typdoc', $obj->expl_typdoc),
				$bul_expl_form);		

	// select "section"
	$bul_expl_form = str_replace('!!section!!',
				do_selector_bul_section($obj->expl_section, $obj->expl_location),
				$bul_expl_form);

	// select "statut"
	$bul_expl_form = str_replace('!!statut!!',
				do_selector('docs_statut', 'expl_statut', $obj->expl_statut),
				$bul_expl_form);

	// select "localisation"
	//visibilité des exemplaires
	global $explr_visible_mod, $pmb_droits_explr_localises ;
	if ($pmb_droits_explr_localises) $where_clause_explr = "idlocation in (".$explr_visible_mod.") and";
	else $where_clause_explr="";
	$bul_expl_form = str_replace('!!localisation!!',
				gen_liste ("select distinct idlocation, location_libelle from docs_location, docsloc_section where $where_clause_explr num_location=idlocation order by 2", "idlocation", "location_libelle", 'expl_location', "calcule_section(this);", $obj->expl_location, "", "","","",0),
				$bul_expl_form);

	// select "code statistique"
	$bul_expl_form = str_replace('!!codestat!!',
				do_selector('docs_codestat', 'expl_codestat', $obj->expl_codestat),
				$bul_expl_form);
	
	// select "owner"
	$bul_expl_form = str_replace('!!owner!!',
				do_selector('lenders', 'expl_owner', $obj->expl_owner),
				$bul_expl_form);

	//dates
	if ($obj->expl_id && $pmb_expl_show_dates=='1') {
		$bul_expl_form = str_replace('<!-- msg_exp_cre_date -->',"<label class='etiquette' >".htmlentities($msg['exp_cre_date'],ENT_QUOTES,$charset)."</label>",$bul_expl_form);
		$bul_expl_form = str_replace('<!-- exp_cre_date -->',format_date($obj->create_date),$bul_expl_form);
		$bul_expl_form = str_replace('<!-- msg_exp_upd_date -->',"<label class='etiquette' >".htmlentities($msg['exp_upd_date'],ENT_QUOTES,$charset)."</label>",$bul_expl_form);
		$bul_expl_form = str_replace('<!-- exp_upd_date -->',format_date($obj->update_date),$bul_expl_form);
	}
	
	// select "type_antivol"
	$selector="";
	if ($pmb_antivol>0) {
		global $value_deflt_antivol;
		if ($obj->type_antivol=="") $obj->type_antivol=$value_deflt_antivol;
		// select "type_antivol"
		$selector = "
		<div class='colonne3'>
		<!-- code stat -->
		<label class='etiquette' for='type_antivol'>$msg[type_antivol]</label>
		<div class='row'>
		<select name='type_antivol' id='type_antivol'>";	
		$selector .= "<option value='0'";
		if($obj->type_antivol ==0)$selector .= ' SELECTED';
		$selector .= '>';
		$selector .= $msg["type_antivol_aucun"].'</option>';
		$selector .= "<option value='1'";
		if($obj->type_antivol ==1)$selector .= ' SELECTED';
		$selector .= '>';
		$selector .= $msg["type_antivol_magnetique"].'</option>';
		$selector .= "<option value='2'";
		if($obj->type_antivol ==2)$selector .= ' SELECTED';
		$selector .= '>';
		$selector .= $msg["type_antivol_autre"].'</option>';                               
		$selector .= '</select></div></div>';   
	}        
	$bul_expl_form = str_replace('!!type_antivol!!', $selector, $bul_expl_form);

	$p_perso=new parametres_perso("expl");
	if (!$p_perso->no_special_fields) {
		$c=0;
		$perso="<hr />";
		global $expl_id_from;
		if ($expl_id_from && !$obj->expl_id) $perso_id_expl=$expl_id_from;
		elseif ($obj->expl_id) $perso_id_expl=$obj->expl_id;
		else $perso_id_expl=0;
		$perso_=$p_perso->show_editable_fields($perso_id_expl);
		for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
			$p=$perso_["FIELDS"][$i];
			if ($c==0) $perso.="<div class='row'>\n";
			$perso.="<div class='colonne2'><label for='".$p["NAME"]."' class='etiquette'>".$p["TITRE"]."</label><div class='row'>".$p["AFF"]."</div></div>\n";
			$c++;
			if ($c==2) {
				$perso.="</div>\n";
				$c=0;
			}
		}	
		if ($c==1) $perso.="<div class='colonne2'>&nbsp;</div>\n</div>\n";
		$perso=$perso_["CHECK_SCRIPTS"]."\n".$perso;
		$perso="<div class='row'>".$perso."</div>";
	} else $perso="";
	$bul_expl_form = str_replace("!!champs_perso!!",$perso,$bul_expl_form);

	// bouton supprimer si modification
	if ($obj->expl_id) {
		$del_button = "<input type='button' class='bouton' value=' $msg[63] ' onClick=\"confirm_expl_delete();\">";	
		$bt_dupliquer = "<input type='button' class='bouton' value=\"".$msg['dupl_expl_bt']."\" name='dupl_ex' id='dupl_ex' onClick=\"unload_off();document.location='./catalog.php?categ=serials&sub=bulletinage&action=dupl_expl&bul_id=".$obj->expl_bulletin."&expl_id=".$obj->expl_id."' ; \" />";
		if ($pmb_type_audit) 
			$link_audit =  "<input class='bouton' type='button' onClick=\"openPopUp('./audit.php?type_obj=2&object_id=$obj->expl_id', 'audit_popup', 700, 500, -2, -2, '$select_categ_prop')\" title='$msg[audit_button]' value='$msg[audit_button]' />";
		else 
			$link_audit = "" ;
	} else {
		$del_button = "" ;
		$link_audit = "" ;
		$bt_dupliquer = "";
	}
		
	if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url ) {
		$script_rfid_encode="if(script_rfid_encode()==false) return false;";	
		$bul_expl_form = str_replace('!!questionrfid!!', $script_rfid_encode, $bul_expl_form);
	}
	else 
		$bul_expl_form = str_replace('!!questionrfid!!', '', $bul_expl_form);
	
	$bul_expl_form = str_replace('!!del!!', $del_button, $bul_expl_form);
	$bul_expl_form = str_replace('!!link_audit!!', $link_audit, $bul_expl_form);
	$bul_expl_form = str_replace('!!bt_dupliquer!!', $bt_dupliquer, $bul_expl_form);
	
	$bul_expl_form = str_replace('!!bul_id!!', $bul_id, $bul_expl_form);
	$bul_expl_form = str_replace('!!expl_id!!', $obj->expl_id, $bul_expl_form);

	// action du bouton annuler
	$bul_expl_form = str_replace('!!annuler_action!!', $annuler, $bul_expl_form);

	// rafraichissement de la liste des sections par rapport à la localisation sélectionnée
//	$bul_expl_form .= "<script> calcule_section(document.forms['expl'].expl_location.options[document.forms['expl'].expl_location.selectedIndex].value); </script>";

	// zone du dernier emrunteur
	$last_pret = "";
	if ($obj->expl_lastempr) {
		$lastempr = new emprunteur($obj->expl_lastempr, '', FALSE, 0) ;
		$last_pret = "<hr /><div class='row'><b>$msg[expl_lastempr] </b>";
		$link = "<a href='./circ.php?categ=pret&form_cb=".rawurlencode($lastempr->cb)."'>";
		$last_pret .= $link.$lastempr->prenom.' '.$lastempr->nom.' ('.$lastempr->cb.')</a>';
		$last_pret .= "</div>";
		}
		
	// zone de l'emprunteur
	$query = "select empr_cb, empr_nom, empr_prenom, ";
	$query .= " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
	$query .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
	$query .= " IF(pret_retour>sysdate(),0,1) as retard " ; 
	$query .= " from pret, empr where pret_idexpl='".$obj->expl_id."' and pret_idempr=id_empr ";
	$result = mysql_query($query, $dbh);
	if (mysql_num_rows($result)) {
		$pret = mysql_fetch_object($result);
		$last_pret .= "<hr /><div class='row'><b>$msg[380]</b> ";
		$link = "<a href='./circ.php?categ=pret&form_cb=".rawurlencode($pret->empr_cb)."'>";
		$last_pret .= $link.$pret->empr_prenom.' '.$pret->empr_nom.' ('.$pret->empr_cb.')</a>';
		$last_pret .= "&nbsp;${msg[381]}&nbsp;".$pret->aff_pret_date;
		$last_pret .= ".&nbsp;${msg[358]}&nbsp;".$pret->aff_pret_retour.".";
		$last_pret .= "</div>";
	} 
	return $bul_expl_form.$last_pret ;

}


//verification des droits de modification notice
$acces_m=1;
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_j = $dom_1->getJoin($PMBuserid,8,'bulletin_notice');
	$q = "select count(1) from bulletins $acces_j  where bulletin_id=".$bul_id;
	$r = mysql_query($q, $dbh);
	if(mysql_result($r,0,0)==0) {
		$acces_m=0;
	}
}

if ($acces_m==0) {

	if (!$expl_id) {
		error_message('', htmlentities($dom_1->getComment('mod_bull_error'), ENT_QUOTES, $charset), 1, '');
	} else {
		error_message('', htmlentities($dom_1->getComment('mod_expl_error'), ENT_QUOTES, $charset), 1, '');
	}

} else {
		

	// affichage des infos du bulletinage pour rappel
	$bulletinage = new bulletinage_display($bul_id);
	print pmb_bidi("<div class='row'><h2>".$bulletinage->display.'</h2></div>');
	
	if ($expl_id) {
		// c'est une modif
		$requete = "SELECT * FROM exemplaires WHERE expl_id=$expl_id AND expl_notice=0 LIMIT 1";
		$myQuery = mysql_query($requete, $dbh);
		if (mysql_num_rows($myQuery)) {
			$expl = mysql_fetch_object($myQuery);
			if ($action=='dupl_expl') {
				$expl_id_from=$expl->expl_id;
				$expl->expl_id=0;
				$expl->expl_cb="";
			}
			print bul_do_form($expl);
		} else {
			print "impossible d'accéder à cet exemplaire.";
		}
	} else {
		// création d'un exemplaire
		// avant toute chose, on regarde si ce cb n'existe pas déjà
		$requete = "SELECT count(1) FROM exemplaires WHERE expl_cb='".$noex."' ";
		$myQuery = mysql_query($requete, $dbh);
		if(!mysql_result($myQuery, 0, 0)) {
			$expl->expl_cb = $noex;
			$expl->expl_id = 0;
			$expl->expl_bulletin = $bul_id;
			$expl->expl_location = $deflt_docs_location;
			$expl->expl_section = $deflt_docs_section;
			$expl->expl_codestat = $deflt_docs_codestat;
			$expl->expl_typdoc = $deflt_docs_type;
			$expl->expl_statut = $deflt_docs_statut;
			$expl->expl_owner = $deflt_lenders;
			$expl_create_date='';
			$expl_update_date='';
			
			$bulletin = new bulletinage($bul_id);
			$expl->expl_cote = prefill_cote($bulletin->bulletin_notice);
			
			print bul_do_form($expl);
		} else {
			print "<div class=\"row\"><div class=\"msg-perio\" size=\"+2\">Ce code barre est déjà utilisé.</div></div>";
			print "<div class=\"row\"><a href=\"./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=";
			print $bulletinage->bul_id;
			print "\">Retour</a></div>";
		}
	}
}
?>