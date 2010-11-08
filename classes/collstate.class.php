<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collstate.class.php,v 1.11 2010-08-11 10:25:38 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// classes de gestion des états de collection de périodique

require_once($class_path."/parametres_perso.class.php");
require_once($include_path."/templates/collstate.tpl.php");
require_once($class_path."/serials.class.php");	
		
class collstate {

// classe de la notice chapeau des périodiques
var $id    			 = 0;       // id de l'état de collection
var $serial_id       = 0;         // id du périodique lié

// constructeur
function collstate($id=0,$serial_id=0) {
	// si id, allez chercher les infos dans la base
	if($id) {
		$this->id = $id;
		$this->fetch_data();
	}
	if($serial_id) {
		//$perio = new serial($serial_id);
		//$this->serial_id = $perio->serial_id;
		$this->serial_id = $serial_id;
	}	
	return $this->id;
}
    
// récupération des infos en base
function fetch_data() {
	global $dbh;
	global $explr_invisible, $explr_visible_unmod, $explr_visible_mod, $pmb_droits_explr_localises ;
	
	$myQuery = mysql_query("SELECT * FROM collections_state WHERE collstate_id='".$this->id."' LIMIT 1", $dbh);
	$mycoll= mysql_fetch_object($myQuery);
	
	$this->serial_id=$mycoll->id_serial;
	$this->location_id=$mycoll->location_id;
	$this->state_collections=$mycoll->state_collections;
	$this->emplacement=$mycoll->collstate_emplacement;
	$this->type=$mycoll->collstate_type;
	$this->origine=$mycoll->collstate_origine;
	$this->note=$mycoll->collstate_note;
	$this->cote=$mycoll->collstate_cote;
	$this->archive=$mycoll->collstate_archive;
	$this->lacune=$mycoll->collstate_lacune;
	$this->statut=$mycoll->collstate_statut;
	
	$myQuery = mysql_query("SELECT * FROM arch_emplacement WHERE archempla_id='".$this->emplacement."' LIMIT 1", $dbh);
	$myempl= mysql_fetch_object($myQuery);
	$this->emplacement_libelle=$myempl->archempla_libelle;	

	$myQuery = mysql_query("SELECT * FROM arch_type WHERE archtype_id='".$this->type."' LIMIT 1", $dbh);
	$mytype= mysql_fetch_object($myQuery);
	$this->type_libelle=$mytype->archtype_libelle;	
	
	// Lecture des statuts
	$myQuery = mysql_query("SELECT * FROM arch_statut WHERE archstatut_id='".$this->statut."' LIMIT 1", $dbh);
	$mystatut= mysql_fetch_object($myQuery);
	$this->statut_gestion_libelle=$mystatut->archstatut_gestion_libelle;	
	$this->statut_opac_libelle=$mystatut->archstatut_opac_libelle;
	$this->statut_visible_opac=$mystatut->archstatut_visible_opac;	
	$this->statut_visible_opac_abon=$mystatut->archstatut_visible_opac_abon; 	
	$this->statut_visible_gestion=$mystatut->archstatut_visible_gestion; 	
	$this->statut_class_html=$mystatut->archstatut_class_html;
	
	$myQuery = mysql_query("select location_libelle from docs_location where idlocation='".$this->location_id."' LIMIT 1", $dbh);
	$mylocation= mysql_fetch_object($myQuery);
	$this->location_libelle=$mylocation->location_libelle;	
				
	if ($pmb_droits_explr_localises) {
		$tab_invis=explode(",",$explr_invisible);
		$tab_unmod=explode(",",$explr_visible_unmod);

		$as_invis = array_search($this->location_id,$tab_invis);
		$as_unmod = array_search($this->location_id,$tab_unmod);
		if ($as_invis!== FALSE && $as_invis!== NULL) $this->explr_acces_autorise="INVIS" ;
		elseif ($as_unmod!== FALSE && $as_unmod!== NULL) $this->explr_acces_autorise="UNMOD" ;
		else $this->explr_acces_autorise="MODIF" ;
	} else $this->explr_acces_autorise="MODIF" ;
}

//Récupération de l'affichage dans l'isbd
function get_callstate_isbd() {
	global $msg, $pmb_etat_collections_localise,$pmb_droits_explr_localises,$explr_visible_mod;
	
	if ($pmb_etat_collections_localise && $pmb_droits_explr_localises && $explr_visible_mod) {
		$restrict_location=" and location_id in (".$explr_visible_mod.") and idlocation=location_id";	
		$table_location=",docs_location";
		$select_location=",location_libelle";
	} 
	$rqt="select state_collections $select_location from collections_state $table_location where id_serial=".$this->serial_id.$restrict_location;
	$execute_query=mysql_query($rqt);
	if (mysql_num_rows($execute_query)) {
		$bool=false;
		$affichage="<br /><strong>".$msg["4001"]."</strong><br />";
		while (($r=mysql_fetch_object($execute_query))) {
			if ($r->state_collections) {
				if ($r->location_libelle) $affichage .= "<strong>".$r->location_libelle."</strong> : ";
				$affichage .= str_replace("\n","<br />",$r->state_collections)."<br />\n";
				$bool=true;
			}	
		}
		if ($bool==true) return($affichage);
	}
	return "";
}

//Récupérer de l'affichage complet
function get_display_list($base_url,$filtre,$debut=0,$page=0, $type=0,$form=1) {
	global $dbh, $msg,$nb_per_page_a_search,$tpl_collstate_liste,$tpl_collstate_liste_line,$tpl_collstate_liste_form;
	global $explr_invisible,$pmb_droits_explr_localises,$pmb_etat_collections_localise,$deflt_docs_location;
	

	$location=$filtre->location;
	if (!$pmb_etat_collections_localise) {	
		 $location="";
	}
	if (($pmb_droits_explr_localises)&&($explr_invisible)) $restrict_location=" location_id not in (".$explr_invisible.") and ";
	else  $restrict_location="";
	
	//On compte les bulletins à afficher
	$rqt="SELECT count( collstate_id) FROM collections_state WHERE $restrict_location ".($location?"(location_id='$location') and ":"")." id_serial='".$this->serial_id."' ";
	$myQuery = mysql_query($rqt, $dbh);
	$nbr_lignes = mysql_result($myQuery,0,0);

	$req="SELECT  collstate_id , location_id FROM collections_state LEFT JOIN docs_location ON location_id=idlocation LEFT JOIN arch_emplacement ON collstate_emplacement=archempla_id WHERE $restrict_location ".($location?"(location_id='$location') and ":"")." 
	id_serial='".$this->serial_id."' ORDER BY ".($pmb_etat_collections_localise?"location_libelle, ":"")."archempla_libelle, collstate_cote 
	LIMIT $debut,$nb_per_page_a_search";
	$myQuery = mysql_query($req, $dbh);

	if((mysql_num_rows($myQuery))) {
		$parity=1;
		while(($coll = mysql_fetch_object($myQuery))) {
			$my_collstate=new collstate($coll->collstate_id);
/*		
	Avoir comment gerer un + pour des grands etats de collections 	
			if (count($my_collstate->state_collections)>80 || count($my_collstate->lacune)>80) {
				$plus_statecollection="<img src='images/plus.gif' class='img_plus' onClick='if (event) e=event; else e=window.event; e.cancelBubble=true; if (e.stopPropagation) e.stopPropagation(); show_sources(\"!!id!!\"); '/>";
				$texte_statecollection="<tr class='$pair_impair' style='display:none' id='".$coll->collstate_id."'><td>&nbsp;</td><td colspan='3'><table style='border:1px solid'>
				<td>".str_replace("\n","<br />",$my_collstate->state_collections)."</td>
				<td>".str_replace("\n","<br />",$my_collstate->lacune)."</td>";
			} else $plus_statecollection="<td>&nbsp;</td>";
*/				
			
			
			if ($parity++ % 2) $pair_impair = "even"; else $pair_impair = "odd";
			// Si modifiable, ajout du lien vers le formulaire
			if($my_collstate->explr_acces_autorise=="MODIF") {
	        	$tr_javascript="  onmousedown=\"document.location='./catalog.php?categ=serials&sub=collstate_form&id=".$coll->collstate_id."&serial_id=".$this->serial_id."';\" ";
			} else {
				$tr_javascript="";
			}
	        $tr_surbrillance = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";

	        $line = str_replace('!!tr_javascript!!',$tr_javascript , $tpl_collstate_liste_line[$type]);
	        $line = str_replace('!!tr_surbrillance!!',$tr_surbrillance , $line);
	        $line = str_replace('!!pair_impair!!',$pair_impair , $line);
	        $line = str_replace('!!localisation!!', $my_collstate->location_libelle, $line);
	        $line = str_replace('!!cote!!', $my_collstate->cote, $line);	        
	        $line = str_replace('!!type_libelle!!', $my_collstate->type_libelle, $line);
	        $line = str_replace('!!emplacement_libelle!!', $my_collstate->emplacement_libelle, $line);
	        $line = str_replace('!!statut_libelle!!', "<span class='".$my_collstate->statut_class_html."'  style='margin-right: 3px;'><img src='./images/spacer.gif' width='10' height='10' /></span>".$my_collstate->statut_gestion_libelle, $line);
	        $line = str_replace('!!origine!!', $my_collstate->origine, $line);
	        $line = str_replace('!!state_collections!!',str_replace("\n","<br />",$my_collstate->state_collections), $line);
	        $line = str_replace('!!lacune!!', str_replace("\n","<br />",$my_collstate->lacune), $line);
	        $liste.=$line;
		}	
		$liste = str_replace('!!collstate_liste!!',$liste , $tpl_collstate_liste[$type]);
	} else {
		$liste= $msg["collstate_no_collstate"];
	}
	
	if($form)$liste = str_replace('!!collstate_table!!',$liste , $tpl_collstate_liste_form);
	$liste = str_replace('!!base_url!!', $base_url, $liste);
	$liste = str_replace('!!location!!', $location, $liste);			
	
	$this->liste=$liste;
	$this->nbr=$nbr_lignes;
	// barre de navigation par page
	$this->pagination = aff_pagination ($base_url."&location=$location", $nbr_lignes, $nb_per_page_a_search, $page, 10, false, true) ;	
}

// fonction de mise à jour ou de création d'état de collection
function update($value) {	
	global $dbh,$msg;
	$fields="";
	foreach($value as $key => $val) {
		if($fields) $fields.=","; 
		$fields.=" $key='".addslashes($val)."' ";	
	}		
	if($this->id) {
		// modif
		$no_erreur=mysql_query("UPDATE collections_state SET $fields WHERE collstate_id=".$this->id, $dbh);	
		if(!$no_erreur) {
			error_message($msg["collstate_add_collstate"], $msg["collstate_add_error"],1);	
			exit;
		}
		
	} else {
		// create
		$no_erreur=mysql_query("INSERT INTO collections_state SET $fields ", $dbh);
		$this->id = mysql_insert_id($dbh);
		if(!$no_erreur) {
			error_message($msg["collstate_edit_collstate"], $msg["collstate_add_error"],1);
			exit;
		}
	}	
	return $this->id;
}

function update_from_form() {
	global $state_collections,$origine,$archive,$cote,$note,$lacune,$serial_id,$archstatut_id,$archtype_id,$location_id,$archempla_id;
	global $deflt_docs_location;		
	
	$value->id_serial=stripslashes($serial_id);
	if(!$location_id) $location_id=$deflt_docs_location;
	$value->location_id=stripslashes($location_id);
	$value->state_collections=stripslashes($state_collections);
	$value->collstate_emplacement=stripslashes($archempla_id);
	$value->collstate_type=stripslashes($archtype_id);
	$value->collstate_origine=stripslashes($origine);
	$value->collstate_note=stripslashes($note);
	$value->collstate_cote=stripslashes($cote);
	$value->collstate_archive=stripslashes($archive);
	$value->collstate_lacune=stripslashes($lacune);
	if(!$archstatut_id)$archstatut_id=1;
	$value->collstate_statut=stripslashes($archstatut_id);
	$this->update($value); 
	
	//Traitement des champs perso
	$p_perso=new parametres_perso("collstate");
	$p_perso->check_submited_fields();
	$p_perso->rec_fields_perso($this->id);
	
}
// fonction générant le form de saisie de notice chapeau
function do_form() {
	global $msg;	
	global $collstate_form,$statut_field,$emplacement_field, $location_field, $support_field;
	global $deflt_docs_location;
	global 	$deflt_arch_statut,$deflt_arch_emplacement,$deflt_arch_type;
	global $charset;
	global $pmb_etat_collections_localise;		
	// titre formulaire
	if($this->id) {
		$libelle=$libelle=$msg["collstate_edit_collstate"];
		$link_delete="<input type='button' class='bouton' value='$msg[63]' onClick=\"confirm_delete();\" />";
		
	} else {
		$libelle=$msg["collstate_add_collstate"];
		$link_delete="";
	}
	$collstate_form = str_replace('!!id!!', htmlentities($this->id,ENT_QUOTES,$charset), $collstate_form);
	$collstate_form = str_replace('!!location_id!!', htmlentities($this->location_id,ENT_QUOTES,$charset), $collstate_form);
	$collstate_form = str_replace('!!serial_id!!', htmlentities($this->serial_id,ENT_QUOTES,$charset), $collstate_form);
	$action="./catalog.php?categ=serials&sub=collstate_update&serial_id=".rawurlencode($this->serial_id)."&id=".rawurlencode($this->id);
	$collstate_form = str_replace('!!action!!', $action, $collstate_form);
	$collstate_form = str_replace('!!delete!!', $link_delete, $collstate_form);
	$collstate_form = str_replace('!!libelle!!',$libelle , $collstate_form);
	
	$collstate_form = str_replace('!!origine!!',htmlentities($this->origine,ENT_QUOTES,$charset) , $collstate_form);
	$collstate_form = str_replace('!!archive!!',htmlentities($this->archive,ENT_QUOTES,$charset) , $collstate_form);	
	$collstate_form = str_replace('!!cote!!',htmlentities($this->cote,ENT_QUOTES,$charset) , $collstate_form);
	$collstate_form = str_replace('!!note!!',htmlentities($this->note,ENT_QUOTES,$charset) , $collstate_form);
	$collstate_form = str_replace('!!lacune!!',htmlentities($this->lacune,ENT_QUOTES,$charset) , $collstate_form);	
	$collstate_form = str_replace('!!state_collections!!',htmlentities($this->state_collections,ENT_QUOTES,$charset) , $collstate_form);		

	// champs des localisations 
	if($pmb_etat_collections_localise) {
		if(!$this->location_id) $this->location_id=$deflt_docs_location;
		$select = gen_liste("select distinct idlocation, location_libelle from docs_location order by 2 ", "idlocation", "location_libelle", 'location_id', "", $this->location_id, "", "","","",0);
		$field="";
		if($select) $field = str_replace('!!location!!',$select, $location_field);
		$collstate_form = str_replace('!!location_field!!',$field, $collstate_form);		
	}else{
		$field="<input type='hidden' name='location_id' id='location_id' value=''/> ";
		$collstate_form = str_replace('!!location_field!!',$field, $collstate_form);
	}
	

	// champs des emplacements 
	if(!$this->emplacement) $this->emplacement=$deflt_arch_emplacement;
	$select = gen_liste("select archempla_id, archempla_libelle from arch_emplacement order by 2", "archempla_id", "archempla_libelle", "archempla_id", "",$this->emplacement, "",  "", "","","",0) ;
	$field="";
	if($select) $field = str_replace('!!emplacement!!',$select, $emplacement_field);		
	$collstate_form = str_replace('!!emplacement_field!!',$field, $collstate_form);

	// champs des supports
	if(!$this->type) $this->type=$deflt_arch_type;
	$select = gen_liste("select archtype_id, archtype_libelle from arch_type order by 2", "archtype_id", "archtype_libelle", "archtype_id", "", $this->type, "", "","","",0) ;
	$field="";
	if($select) $field = str_replace('!!support!!',$select, $support_field);		
	$collstate_form = str_replace('!!support_field!!',$field, $collstate_form);
	
	// champs des statuts 
	if(!$this->statut) $this->statut=$deflt_arch_statut;
	$select = gen_liste("select archstatut_id, archstatut_gestion_libelle from arch_statut order by 2", "archstatut_id", "archstatut_gestion_libelle", "archstatut_id", "", $this->statut, "", "","","",0) ;
	$field="";
	if($select) $field = str_replace('!!statut!!',$select, $statut_field);		
	$collstate_form = str_replace('!!statut_field!!',$field, $collstate_form);

	// Champs perso 
	$p_perso=new parametres_perso("collstate");	
	$parametres_perso="";
	if (!$p_perso->no_special_fields) {
		$perso_=$p_perso->show_editable_fields($this->id);
		$perso="";
		for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
			$p=$perso_["FIELDS"][$i];
			$perso.="
				<div class='row'>
					<label for='".$p["NAME"]."' class='etiquette'>".$p["TITRE"]."</label>
				</div>
				<div class='row'>
					".$p["AFF"]."
				</div>";
		}
		$perso.=$perso_["CHECK_SCRIPTS"];
		$parametres_perso.=$perso;
	}	
	$collstate_form = str_replace('!!parametres_perso!!',$parametres_perso , $collstate_form);

	$link_annul = "onClick=\"unload_off();history.go(-1);\"";
	$collstate_form = str_replace('!!annul!!', $link_annul, $collstate_form);
	
	//vérification de la présence de champs perso
	//si non, on confirme la soumission du formulaire
	if($p_perso->no_special_fields)
		$return_form = "return true";
	//sinon, on vérifie leurs valeurs
	else $return_form = "return check_form()"; 
	$collstate_form = str_replace('!!return_form!!',$return_form, $collstate_form);
	
	return $collstate_form;	
}

// suppression d'une collection ou de toute les collections d'un périodique
function delete() {
	global $dbh;
	
	if($this->id) {
		//elimination des champs persos
		$p_perso=new parametres_perso("collstate");
		$p_perso->delete_values($this->id);		
		mysql_query("DELETE from collections_state WHERE collstate_id='".$this->id."' ", $dbh);
	} else if($this->serial_id) {
		$myQuery = mysql_query("SELECT collstate_id FROM collections_state WHERE id_serial='".$this->serial_id."' ", $dbh);
		if((mysql_num_rows($myQuery))) {
			while(($coll = mysql_fetch_object($myQuery))) {
				$my_collstate=new collstate($coll->collstate_id);
				$my_collstate->delete();
			}
		}		
	}	
}

} // fin définition classe
