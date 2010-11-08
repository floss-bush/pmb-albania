<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes_actions.class.php,v 1.9 2010-08-27 14:25:08 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/demandes_notes.class.php");
require_once($class_path."/explnum_doc.class.php");
require_once($class_path."/workflow.class.php");

class demandes_actions{
	
	var $id_action = 0;
	var $type_action = 0;
	var $statut_action = 0;
	var $sujet_action = '';
	var $detail_action = '';
	var $time_elapsed = 0;
	var $date_action = '0000-00-00';
	var $deadline_action = '0000-00-00';
	var $progression_action = 0;
	var $prive_action = 0;
	var $cout = 0;
	var $num_demande = 0;
	var $libelle_demande = '';
	var $actions_num_user = 0;
	var $actions_type_user = 0;
	var $createur_action ="";
	var $list_type = array();
	var $list_statut = array();
	var $workflow = array();
	/*
	 * Constructeur
	 */
	function demandes_actions($id=0){
		
		global $base_path, $dbh, $iddemande;
		
		$this->workflow = new workflow('ACTIONS');
		
		$this->id_action = $id;
		if($this->id_action){
			$req = "select id_action,type_action,statut_action, sujet_action, 
			detail_action,date_action,deadline_action,temps_passe, cout, progression_action, prive_action, num_demande, titre_demande, 
			actions_num_user,actions_type_user
			from demandes_actions 
			join demandes on num_demande=id_demande 
			where id_action='".$this->id_action."'";
			$res=mysql_query($req,$dbh);
			if(mysql_num_rows($res)){
				$obj = mysql_fetch_object($res);
				$this->type_action = $obj->type_action;
				$this->date_action = $obj->date_action;
				$this->deadline_action = $obj->deadline_action;
				$this->sujet_action = $obj->sujet_action;
				$this->detail_action = $obj->detail_action;
				$this->cout = $obj->cout;
				$this->progression_action = $obj->progression_action;
				$this->time_elapsed = $obj->temps_passe;
				$this->num_demande = $obj->num_demande;	
				$this->statut_action = $obj->statut_action;		
				$this->libelle_demande = $obj->titre_demande;
				$this->prive_action = $obj->prive_action;
				$this->actions_num_user = $obj->actions_num_user;
				$this->actions_type_user =  $obj->actions_type_user;
			} else{
				$this->id_action = 0;
				$this->type_action = 0;
				$this->date_action = '0000-00-00';
				$this->deadline_action = '0000-00-00';
				$this->sujet_action = '';
				$this->detail_action = '';
				$this->cout = 0;
				$this->progression_action = 0;
				$this->time_elapsed = 0;
				$this->num_demande = 0;	
				$this->statut_action =	0;
				$this->libelle_demande = '';
				$this->prive_action = 0;
				$this->actions_num_user = 0;
				$this->actions_type_user =  0;
			}			
		} else {
			$this->id_action = 0;
			$this->type_action = 0;
			$this->date_action = '0000-00-00';
			$this->deadline_action = '0000-00-00';
			$this->sujet_action = '';
			$this->detail_action = '';
			$this->cout = 0;
			$this->progression_action = 0;
			$this->time_elapsed = 0;
			$this->num_demande = 0;	
			$this->statut_action =	0;	
			$this->libelle_demande = '';
			$this->prive_action = 0;
			$this->actions_num_user = 0;
			$this->actions_type_user =  0;
		}
		
		$this->list_type = $this->workflow->getTypeList();
		
		$this->list_statut = $this->workflow->getStateList();
		
		if($iddemande) {
			$this->num_demande = $iddemande;
			$req = "select titre_demande from demandes where id_demande='".$iddemande."'";
			$res = mysql_query($req,$dbh);
			$this->libelle_demande = mysql_result($res,0,0);
		}
	}
	
	
	/*
	 * Affichage du formulaire de création/modification
	 */
	function show_modif_form(){
		
		global $form_modif_action,$msg, $charset;
		
		
		if($this->id_action){
			$form_modif_action = str_replace('!!form_title!!',htmlentities(sprintf($msg['demandes_action_modif'],' : '.$this->sujet_action),ENT_QUOTES,$charset),$form_modif_action);
			$form_modif_action = str_replace('!!sujet!!',htmlentities($this->sujet_action,ENT_QUOTES,$charset),$form_modif_action);
			$form_modif_action = str_replace('!!detail!!',htmlentities($this->detail_action,ENT_QUOTES,$charset),$form_modif_action);
			$form_modif_action = str_replace('!!cout!!',htmlentities($this->cout,ENT_QUOTES,$charset),$form_modif_action);
			$form_modif_action = str_replace('!!time_elapsed!!',htmlentities($this->time_elapsed,ENT_QUOTES,$charset),$form_modif_action);
			$form_modif_action = str_replace('!!progression!!',htmlentities($this->progression_action,ENT_QUOTES,$charset),$form_modif_action);
			$form_modif_action = str_replace('!!select_type!!',$this->workflow->getTypeCommentById($this->type_action),$form_modif_action);
			$type_hide = "<input type='hidden' name='idtype' id='idtype' value='$this->type_action' />";
			$form_modif_action = str_replace('!!type_action!!',$type_hide,$form_modif_action);
			$form_modif_action = str_replace('!!select_statut!!',$this->getStatutSelector($this->statut_action),$form_modif_action);
			
			$form_modif_action = str_replace('!!date_fin_btn!!',formatdate($this->deadline_action),$form_modif_action);
			$form_modif_action = str_replace('!!date_debut_btn!!',formatdate($this->date_action),$form_modif_action);
			$form_modif_action = str_replace('!!date_debut!!',htmlentities($this->date_action,ENT_QUOTES,$charset),$form_modif_action);
			$form_modif_action = str_replace('!!date_fin!!',htmlentities($this->deadline_action,ENT_QUOTES,$charset),$form_modif_action);
			
			$btn_suppr = "<input type='submit' class='bouton' value='$msg[63]' onclick='this.form.act.value=\"suppr_action\"; return confirm_delete();' />";	
			$form_modif_action = str_replace('!!btn_suppr!!',$btn_suppr,$form_modif_action);
			$form_modif_action = str_replace('!!idaction!!',$this->id_action,$form_modif_action);
			$form_modif_action = str_replace('!!iddemande!!',$this->num_demande,$form_modif_action);
			if($this->prive_action)
				$form_modif_action = str_replace('!!ck_prive!!','checked',$form_modif_action);
			else $form_modif_action = str_replace('!!ck_prive!!','',$form_modif_action);
			
			
			$act_cancel = "document.location='./demandes.php?categ=action&act=see&idaction=$this->id_action'";
			$act_form = "./demandes.php?categ=action&act=see&idaction=$this->id_action";
			
			$form_modif_action = str_replace('!!form_action!!',$act_form,$form_modif_action);				
			$form_modif_action = str_replace('!!cancel_action!!',$act_cancel,$form_modif_action);	
			$path = "<a href=./demandes.php?categ=gestion&act=see_dmde&iddemande=$this->num_demande>".htmlentities($this->libelle_demande,ENT_QUOTES,$charset)."</a>";
			$path .= " > <a href=./demandes.php?categ=action&act=see&idaction=$this->id_action>".htmlentities($this->sujet_action,ENT_QUOTES,$charset)."</a>";
			$form_modif_action = str_replace('!!path!!',$path,$form_modif_action);
			
			print $form_modif_action;
			
		} else {
			$form_modif_action = str_replace('!!form_title!!',htmlentities($msg['demandes_action_creation'],ENT_QUOTES,$charset),$form_modif_action);
			$form_modif_action = str_replace('!!cout!!','',$form_modif_action);
			$form_modif_action = str_replace('!!progression!!','',$form_modif_action);
			$form_modif_action = str_replace('!!sujet!!','',$form_modif_action);
			$form_modif_action = str_replace('!!detail!!','',$form_modif_action);
			$form_modif_action = str_replace('!!time_elapsed!!','',$form_modif_action);
			$date = formatdate(today());
			$date_debut=date("Y-m-d",time());
			$form_modif_action = str_replace('!!date_fin_btn!!',$date,$form_modif_action);
			$form_modif_action = str_replace('!!date_debut_btn!!',$date,$form_modif_action);
			$form_modif_action = str_replace('!!date_debut!!',$date_debut,$form_modif_action);
			$form_modif_action = str_replace('!!date_fin!!',$date_debut,$form_modif_action);
			$form_modif_action = str_replace('!!select_type!!',$this->getTypeSelector(),$form_modif_action);
			$form_modif_action = str_replace('!!type_action!!','',$form_modif_action);
			$form_modif_action = str_replace('!!select_statut!!',$this->getStatutSelector(),$form_modif_action);
			$form_modif_action = str_replace('!!btn_suppr!!','',$form_modif_action);
			$form_modif_action = str_replace('!!idaction!!','',$form_modif_action);
			$form_modif_action = str_replace('!!iddemande!!',$this->num_demande,$form_modif_action);
			
			$act_cancel = "document.location='./demandes.php?categ=gestion&act=see_dmde&iddemande=$this->num_demande'";
						
			$form_modif_action = str_replace('!!form_action!!',"",$form_modif_action);
			$form_modif_action = str_replace('!!cancel_action!!',$act_cancel,$form_modif_action);	
			$path = "<a href=./demandes.php?categ=gestion&act=see_dmde&iddemande=$this->num_demande>".htmlentities($this->libelle_demande,ENT_QUOTES,$charset)."</a>";
			$form_modif_action = str_replace('!!path!!',$path,$form_modif_action);
			
			print $form_modif_action;
		}
	}
	
	/*
	 * Formulaire de consultation d'une action
	 */
	function show_consultation_form(){
		
		global $form_consult_action, $form_see_docnum, $msg, $charset, $pmb_gestion_devise, $dbh;
		
		$form_consult_action = str_replace('!!form_title!!',htmlentities($this->sujet_action,ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!type_action!!',htmlentities($this->workflow->getTypeCommentById($this->type_action),ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!statut_action!!',htmlentities($this->workflow->getStateCommentById($this->statut_action),ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!detail_action!!',htmlentities($this->detail_action,ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!date_action!!',htmlentities(formatdate($this->date_action),ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!date_butoir_action!!',htmlentities(formatdate($this->deadline_action),ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!time_action!!',htmlentities($this->time_elapsed.$msg['demandes_action_time_unit'],ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!cout_action!!',htmlentities($this->cout,ENT_QUOTES,$charset).$pmb_gestion_devise,$form_consult_action);
		$form_consult_action = str_replace('!!progression_action!!',htmlentities($this->progression_action,ENT_QUOTES,$charset).'%',$form_consult_action);
		$form_consult_action = str_replace('!!idaction!!',htmlentities($this->id_action,ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!iddemande!!',htmlentities($this->num_demande,ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!createur!!',htmlentities($this->getCreateur($this->actions_num_user,$this->actions_type_user),ENT_QUOTES,$charset),$form_consult_action);
		$form_consult_action = str_replace('!!prive_action!!',htmlentities(($this->prive_action ? $msg[40] : $msg[39] ),ENT_QUOTES,$charset),$form_consult_action);
		
		$path = "<a href=./demandes.php?categ=gestion&act=see_dmde&iddemande=$this->num_demande>".htmlentities($this->libelle_demande,ENT_QUOTES,$charset)."</a>";
		$form_consult_action = str_replace('!!path!!',$path,$form_consult_action);
		
		$act_cancel = "document.location='./demandes.php?categ=gestion&act=see_dmde&iddemande=$this->num_demande'";
		$form_consult_action = str_replace('!!cancel_action!!',$act_cancel,$form_consult_action);		
		print $form_consult_action;
		
		//Notes
		$notes = new demandes_notes(0,$this->id_action);
		$notes->show_list_notes($this->id_action);
		
		//Documents Numériques
		$req = "select * from explnum_doc join explnum_doc_actions on num_explnum_doc=id_explnum_doc 
		where num_action='".$this->id_action."'";
		$res = mysql_query($req,$dbh);
		if(mysql_num_rows($res)){
			$tab_docnum = array();
			while(($docnums = mysql_fetch_array($res))){
				$tab_docnum[] = $docnums;
			}
			$explnum_doc = new explnum_doc();
			$liste_docnum = $explnum_doc->show_docnum_table($tab_docnum,'./demandes.php?categ=action&act=modif_docnum&idaction='.$this->id_action);
			$form_see_docnum = str_replace('!!list_docnum!!',$liste_docnum,$form_see_docnum);
		} else {
			$form_see_docnum = str_replace('!!list_docnum!!',htmlentities($msg['demandes_action_no_docnum'],ENT_QUOTES,$charset),$form_see_docnum);
		}
		$form_see_docnum = str_replace('!!idaction!!',$this->id_action,$form_see_docnum);
		print $form_see_docnum;
		
		
	}
	
	/*
	 * Formulaire d'ajout/modification d'un document numérique
	 */
	function show_docnum_form(){
		
		global $form_add_docnum, $msg,$dbh, $charset,$explnumdoc_id,$explnum_doc;
		
		if($explnumdoc_id){
			$rqt = "select prive, rapport from explnum_doc_actions where num_explnum_doc='".$explnumdoc_id."'";
			$res = mysql_query($rqt, $dbh);
			$expl = mysql_fetch_object($res);
			$prive = $expl->prive;
			$rapport = $expl->rapport;
			
			$explnum_doc = new explnum_doc($explnumdoc_id);
			$form_add_docnum = str_replace('!!idaction!!',$this->id_action, $form_add_docnum);
			$form_add_docnum = str_replace('!!url_doc!!',htmlentities($explnum_doc->explnum_doc_url,ENT_QUOTES,$charset), $form_add_docnum);
			$form_add_docnum = str_replace('!!nom!!',htmlentities($explnum_doc->explnum_doc_nomfichier,ENT_QUOTES,$charset), $form_add_docnum);
			$act_cancel = "document.location='./demandes.php?categ=action&act=see&idaction=$this->id_action'";
			$form_add_docnum = str_replace('!!cancel_action!!',$act_cancel, $form_add_docnum);
			$form_add_docnum = str_replace('!!form_title!!',htmlentities($msg['explnum_data_doc'],ENT_QUOTES,$charset),$form_add_docnum);
			$form_add_docnum = str_replace('!!iddocnum!!',$explnumdoc_id,$form_add_docnum);		
			$form_add_docnum = str_replace('!!ck_prive!!',($prive ? 'checked' :''),$form_add_docnum);
			$form_add_docnum = str_replace('!!ck_rapport!!',($rapport ? 'checked' :''),$form_add_docnum);		
			$btn_suppr= "<input type='submit' class='bouton' value='$msg[63]' onClick='this.form.act.value=\"suppr_docnum\" ; ' />";
		} else {
			$form_add_docnum = str_replace('!!idaction!!',$this->id_action, $form_add_docnum);
			$form_add_docnum = str_replace('!!url_doc!!',"", $form_add_docnum);
			$form_add_docnum = str_replace('!!nom!!','', $form_add_docnum);
			$act_cancel = "document.location='./demandes.php?categ=action&act=see&idaction=$this->id_action'";
			$form_add_docnum = str_replace('!!cancel_action!!',$act_cancel, $form_add_docnum);
			$form_add_docnum = str_replace('!!iddocnum!!','',$form_add_docnum);			
			$form_add_docnum = str_replace('!!form_title!!',htmlentities($msg['explnum_ajouter_doc'],ENT_QUOTES,$charset),$form_add_docnum);
			$form_add_docnum = str_replace('!!ck_prive!!','',$form_add_docnum);
			$form_add_docnum = str_replace('!!ck_rapport!!','',$form_add_docnum);
			$btn_suppr="";			
		}
		$form_add_docnum = str_replace('!!suppr_btn!!',$btn_suppr,$form_add_docnum);
		$act_cancel = "document.location='./demandes.php?categ=action&act=see&idaction=$this->id_action'";
		$form_add_docnum = str_replace('!!cancel_action!!',$act_cancel, $form_add_docnum);
		
		$path = "<a href=./demandes.php?categ=gestion&act=see_dmde&iddemande=$this->num_demande>".htmlentities($this->libelle_demande,ENT_QUOTES,$charset)."</a>";
		$path .= " > <a href=./demandes.php?categ=action&act=see&idaction=$this->id_action>".htmlentities($this->sujet_action,ENT_QUOTES,$charset)."</a>";
		$form_add_docnum = str_replace('!!path!!',$path,$form_add_docnum);
		
		print $form_add_docnum;
	}
	
	/*
	 * Retourne un sélecteur avec les types d'action
	 */
	function getTypeSelector($idtype=0){
		
		global $charset, $msg;
		
		$selector = "<select name='idtype'>";
		$select="";
		if($default) $selector .= "<option value='0'>".htmlentities($msg['demandes_action_all_types'],ENT_QUOTES,$charset)."</option>";
		for($i=1;$i<=count($this->list_type);$i++){
			if($idtype == $i) $select = "selected";
			$selector .= "<option value='".$this->list_type[$i]['id']."' $select>".htmlentities($this->list_type[$i]['comment'],ENT_QUOTES,$charset)."</option>";
			$select = "";
		}
		$selector .= "</select>";
		
		return $selector;
	}
	
	/*
	 * Retourne un sélecteur avec les statuts d'action
	 */
	function getStatutSelector($idstatut=0,$ajax=false){
		
		global $charset;
		
		$selector = "<select ".($ajax ? "name='save_statut_".$this->id_action."' id='save_statut_".$this->id_action."'" : "name='idstatut'").">";
		$select="";
		for($i=1;$i<=count($this->list_statut);$i++){
			if($idstatut == $this->list_statut[$i]['id']) $select = "selected";
			$selector .= "<option value='".$this->list_statut[$i]['id']."' $select>".htmlentities($this->list_statut[$i]['comment'],ENT_QUOTES,$charset)."</option>";
			$select = "";
		}
		$selector .= "</select>";
		
		return $selector;
	}
	
	/*
	 * Insertion/Modification d'une action
	 */
	function save(){
		global $dbh, $sujet, $idtype, $idstatut;
		global $date_debut, $date_fin, $detail;
		global $time_elapsed, $progression,$cout,$iddemande, $ck_prive,$PMBuserid;
		
		if($this->id_action){
			//MODIFICATION
			$req = "update demandes_actions set sujet_action='".$sujet."', 
				type_action='".$idtype."', 
				statut_action='".$idstatut."', 
				detail_action='".$detail."', 
				date_action='".$date_debut."', 
				deadline_action='".$date_fin."', 
				temps_passe='".$time_elapsed."',
				cout='".$cout."',  
				progression_action='".$progression."',
				prive_action='".$ck_prive."',
				num_demande='".$iddemande."' 	
				where id_action='".$this->id_action."'";
									
			mysql_query($req,$dbh);
		} else {
			//CREATION
			$req = "insert into demandes_actions set sujet_action='".$sujet."', 
			type_action='".$idtype."',
			statut_action='".$idstatut."', 
			detail_action='".$detail."', 
			date_action='".$date_debut."', 
			deadline_action='".$date_fin."', 
			temps_passe='".$time_elapsed."',
			cout='".$cout."', 
			prive_action='".$ck_prive."',
			progression_action='".$progression."',
			num_demande='".$iddemande."',
			actions_num_user='".$PMBuserid."',
			actions_type_user='0'
			"; 
			
			mysql_query($req,$dbh);
			$this->id_action = mysql_insert_id();
			$this->actions_num_user = $PMBuserid;
			$this->actions_type_user = 0;
		}
		
			$this->sujet_action = stripslashes($sujet);
			$this->type_action = $idtype;
			$this->time_elapsed = ($time_elapsed ? $time_elapsed : 0);
			$this->date_action = $date_debut;
			$this->deadline_action = $date_fin;
			$this->statut_action = $idstatut;
			$this->detail_action = stripslashes($detail);
			$this->cout = ($cout ? $cout : 0);
			$this->progression_action = ($progression ? $progression : 0);
			$this->num_demande = $iddemande;
			$this->prive_action = $ck_prive;
	}
	
	/*
	 * Affichage de la liste des actions
	 */
	function show_list_actions($id_demande=0){
		
		global $form_liste_action, $dbh,$msg, $pmb_gestion_devise;
		
		$req = "SELECT id_action, type_action, sujet_action, detail_action,statut_action, date_action, deadline_action, temps_passe, cout, 
		progression_action, num_demande, count(id_note) as nb, actions_num_user, actions_type_user 
		FROM demandes_actions a left join demandes_notes n ON n.num_action=a.id_action WHERE num_demande='".$id_demande."' group by id_action";
		$res = mysql_query($req,$dbh); 
		$liste ="";
		if(mysql_num_rows($res)){
			$parity=1;						
			while(($actions = mysql_fetch_object($res))){
				if ($parity % 2) {
					$pair_impair = "even";
				} else {
					$pair_impair = "odd";
				}
				$parity += 1;
				$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
				$action = "onclick=document.location='./demandes.php?categ=action&act=see&idaction=".$actions->id_action."'";
				
				$liste .= "<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer'  >";
				$liste .= "
					<td $action>".htmlentities($this->workflow->getTypeCommentById($actions->type_action),ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities($actions->sujet_action,ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities($actions->detail_action,ENT_QUOTES,$charset)."</td>	
					<td ><span id='statut_".$actions->id_action."' dynamics='demandes,statut' dynamics_params='selector'>".htmlentities($this->workflow->getStateCommentById($actions->statut_action),ENT_QUOTES,$charset)."</span></td>
					<td $action>".htmlentities(formatdate($actions->date_action),ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities(formatdate($actions->deadline_action),ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities($this->getCreateur($actions->actions_num_user,$actions->actions_type_user),ENT_QUOTES,$charset)."</td>
					<td ><span dynamics='demandes,temps' dynamics_params='text' id='temps_".$actions->id_action."'>".htmlentities($actions->temps_passe.$msg['demandes_action_time_unit'],ENT_QUOTES,$charset)."</span></td>
					<td id='up_temps_".$actions->id_action."' style=\"display:none\"></td>
					
					<td><span dynamics='demandes,cout' dynamics_params='text' id='cout_".$actions->id_action."'>".htmlentities($actions->cout,ENT_QUOTES,$charset).$pmb_gestion_devise."</td>
					<td id='up_cout_".$actions->id_action."' style=\"display:none\"></td>
					
					<td><span dynamics='demandes,progression' dynamics_params='text' id='progression_".$actions->id_action."' >
						<img src=\"./images/jauge.png\" height='15px' width=\"".$actions->progression_action."%\" title='".$actions->progression_action."%' />
					</td>
					<td $action>".$actions->nb."</td>
					
					<td><input type='checkbox' id='chk[".$actions->id_action."]' name='chk[]' value='".$actions->id_action."'</td>
				"; 
				$liste .= "</tr>";	
			}
			$btn_suppr = "<input type='submit' class='bouton' value='$msg[63]' onclick='this.form.act.value=\"suppr_action\"; return verifChk();'/>";	
		} else {
			$liste .= "<tr><td>".$msg['demandes_action_liste_vide']."</td></tr>";
		}
		$form_liste_action = str_replace('!!iddemande!!',$id_demande,$form_liste_action);
		$form_liste_action = str_replace('!!btn_suppr!!',$btn_suppr,$form_liste_action);
		$form_liste_action = str_replace('!!liste_action!!',$liste,$form_liste_action);
		
		print $form_liste_action;
	}
	
	/*
	 * Suppression d'une action 
	 */
	function delete(){
		
		global $dbh,$chk;
		
		if($this->id_action){
			$req = "delete from demandes_actions where id_action='".$this->id_action."'"; 
			mysql_query($req,$dbh);
			$req = "delete from demandes_notes where num_action='".$this->id_action."'";
			mysql_query($req,$dbh);
			$q = "delete ed,eda from explnum_doc ed join explnum_doc_actions eda on ed.id_explnum_doc=eda.num_explnum_doc where eda.num_action=$this->id_action";
			mysql_query($q, $dbh);
		} elseif($chk){
			for($i=0;$i<count($chk);$i++){
				$req = "delete from demandes_actions where id_action='".$chk[$i]."'"; 
				mysql_query($req,$dbh);
				$req = "delete from demandes_notes where num_action='".$chk[$i]."'";
				mysql_query($req,$dbh);
				$q = "delete ed,eda from explnum_doc ed join explnum_doc_actions eda on ed.id_explnum_doc=eda.num_explnum_doc where eda.num_action=$chk[$i]";
				mysql_query($q, $dbh);
			}
		}		
	}
	
	/*
	 * Liste des actions Questions/Réponses ouverte ou en attente
	 */
	function show_com_form(){
		global $form_communication, $dbh, $charset, $msg;
		
		
		$req_dmde = "select id_demande, titre_demande from demandes
			join demandes_actions on num_demande=id_demande
			join demandes_users du on du.num_demande=id_demande
			and type_action=1 
			and (statut_action=1 or statut_action=2)
			and num_user='".SESSuserid."' group by id_demande";
		$res_dmde=mysql_query($req_dmde,$dbh);
		if(mysql_num_rows($res_dmde)){
			while(($dmde=mysql_fetch_object($res_dmde))){
				$dmde_action = "onclick=document.location='./demandes.php?categ=gestion&act=see_dmde&iddemande=".$dmde->id_demande."'";
				$list .= "<tr id='demande_$dmde->id_demande' $dmde_action style='cursor: pointer'>";
				$list .= "<td colspan=8>".htmlentities($dmde->titre_demande,ENT_QUOTES,$charset)."</td>";
				$list .= "</tr>";	
				$req_act="select id_action, sujet_action,detail_action,date_action, deadline_action, temps_passe, cout, progression_action 
				from demandes_actions 
				where num_demande='".$dmde->id_demande."'
				and type_action=1 
				and (statut_action=1 or statut_action=2)"; 
				$res_act=mysql_query($req_act,$dbh);
				if(mysql_num_rows($res_act)){						
					$parity=1;						
					while(($com = mysql_fetch_object($res_act))){
						if ($parity % 2) {
							$pair_impair = "even";
						} else {
							$pair_impair = "odd";
						}
						$parity += 1;
						$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
						$action = "onclick=document.location='./demandes.php?categ=action&act=see&idaction=".$com->id_action."'";
						$list .= 
						"<tr class='$pair_impair' id='act_$com->id_action' $tr_javascript style='cursor: pointer'>
					 		<td>&nbsp;</td>
					 		<td $action>".htmlentities($com->sujet_action,ENT_QUOTES,$charset)."</td>
							<td $action>".htmlentities($com->detail_action,ENT_QUOTES,$charset)."</td>				
							<td $action>".htmlentities(formatdate($com->date_action),ENT_QUOTES,$charset)."</td>
							<td $action>".htmlentities($com->temps_passe.$msg['demandes_action_time_unit'],ENT_QUOTES,$charset)."</td>
							<td $action><img src=\"./images/jauge.png\" height='15px' width=\"".$com->progression_action."%\" title='".$com->progression_action."%' /></td>
							<td><input type='checkbox' id='chk[".$com->id_action."]' name='chk[]' value='".$com->id_action."'></td>
						</tr>";
					}
				} else $list = "<tr><td>".htmlentities($msg['demandes_no_com'],ENT_QUOTES,$charset)."</td></tr>";
			}
		} else $list = "<tr><td>".htmlentities($msg['demandes_no_com'],ENT_QUOTES,$charset)."</td></tr>";
		
		$btn_action = "<input type='submit' class='bouton' name='close_fil' id='close_fil' value='".$msg['demandes_action_close_fil']."' onclick='this.form.act.value=\"close_fil\"'>";
		$form_communication=str_replace('!!btn_action!!',$btn_action,$form_communication);
		$form_communication=str_replace('!!form_title!!',$msg['demandes_action_com'],$form_communication);
		$form_communication=str_replace('!!liste_comm!!',$list,$form_communication);
		
		$form_communication=str_replace('!!action!!','demandes.php?categ=action&sub=com',$form_communication);
		
		print $form_communication;
	}
	
	/*
	 * Liste des RDV planifiés
	 */
	function show_planning_form(){
		global $form_communication, $dbh, $charset, $msg;
		
		
		$req_dmde = "select id_demande, titre_demande from demandes
			join demandes_actions on num_demande=id_demande
			join demandes_users du on du.num_demande=id_demande
			and type_action=4 
			and statut_action=1 
			and num_user='".SESSuserid."' group by id_demande";
		$res_dmde=mysql_query($req_dmde,$dbh);
		if(mysql_num_rows($res_dmde)){
			while(($dmde=mysql_fetch_object($res_dmde))){
				$dmde_action = "onclick=document.location='./demandes.php?categ=gestion&act=see_dmde&iddemande=".$dmde->id_demande."'";
				$list .= "<tr id='demande_$dmde->id_demande' $dmde_action style='cursor: pointer'>";
				$list .= "<td colspan=8>".htmlentities($dmde->titre_demande,ENT_QUOTES,$charset)."</td>";
				$list .= "</tr>";	
				$req_act="select id_action, sujet_action,detail_action,date_action, deadline_action, temps_passe, cout, progression_action 
				from demandes_actions 
				where num_demande='".$dmde->id_demande."'
				and type_action=4 
				and statut_action=1"; 
				$res_act=mysql_query($req_act,$dbh);
				if(mysql_num_rows($res_act)){						
					$parity=1;						
					while(($com = mysql_fetch_object($res_act))){
						if ($parity % 2) {
							$pair_impair = "even";
						} else {
							$pair_impair = "odd";
						}
						$parity += 1;
						$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
						$action = "onclick=document.location='./demandes.php?categ=action&act=see&idaction=".$com->id_action."'";
						$list .= 
						"<tr class='$pair_impair' id='act_$com->id_action' $tr_javascript style='cursor: pointer'>
					 		<td>&nbsp;</td>
					 		<td $action>".htmlentities($com->sujet_action,ENT_QUOTES,$charset)."</td>
							<td $action>".htmlentities($com->detail_action,ENT_QUOTES,$charset)."</td>				
							<td $action>".htmlentities(formatdate($com->date_action),ENT_QUOTES,$charset)."</td>
							<td $action>".htmlentities($com->temps_passe.$msg['demandes_action_time_unit'],ENT_QUOTES,$charset)."</td>
							<td $action><img src=\"./images/jauge.png\" height='15px' width=\"".$com->progression_action."%\" title='".$com->progression_action."%' /></td>
							<td><input type='checkbox' id='chk[".$com->id_action."]' name='chk[]' value='".$com->id_action."'></td>
						</tr>";
					}
				} else $list = "<tr><td>".htmlentities($msg['demandes_no_rdv_plan'],ENT_QUOTES,$charset)."</td></tr>";
			}
		} else $list = "<tr><td>".htmlentities($msg['demandes_no_rdv_plan'],ENT_QUOTES,$charset)."</td></tr>";
		
		$btn_action = "<input type='submit' class='bouton' name='close_rdv' id='close_rdv' value='".$msg['demandes_action_close_rdv']."'  onclick='this.form.act.value=\"close_rdv\"'>";
		$form_communication=str_replace('!!btn_action!!',$btn_action,$form_communication);
		$form_communication=str_replace('!!form_title!!',$msg['demandes_menu_rdv_planning'],$form_communication);
		$form_communication=str_replace('!!liste_comm!!',$list,$form_communication);
		$form_communication=str_replace('!!action!!','demandes.php?categ=action&sub=rdv_plan',$form_communication);
		print $form_communication;
	}
	
	/*
	 * Formulaire qui gère l'affichage des actions
	 */
	function show_rdv_val_form(){
		global $form_communication, $dbh, $charset, $msg;
		
		$req_dmde = "select id_demande, titre_demande from demandes
			join demandes_actions on num_demande=id_demande
			join demandes_users du on du.num_demande=id_demande
			and type_action=4 
			and statut_action=2 
			and num_user='".SESSuserid."' group by id_demande";
		$res_dmde=mysql_query($req_dmde,$dbh);
		if(mysql_num_rows($res_dmde)){
			while(($dmde=mysql_fetch_object($res_dmde))){
				$dmde_action = "onclick=document.location='./demandes.php?categ=gestion&act=see_dmde&iddemande=".$dmde->id_demande."'";
				$list .= "<tr id='demande_$dmde->id_demande' $dmde_action style='cursor: pointer'>";
				$list .= "<td colspan=8>".htmlentities($dmde->titre_demande,ENT_QUOTES,$charset)."</td>";
				$list .= "</tr>";	
				$req_act="select id_action, sujet_action,detail_action,date_action, deadline_action, temps_passe, cout, progression_action 
				from demandes_actions 
				where num_demande='".$dmde->id_demande."'
				and type_action=4 
				and statut_action=2"; 
				$res_act=mysql_query($req_act,$dbh);
				if(mysql_num_rows($res_act)){						
					$parity=1;						
					while(($com = mysql_fetch_object($res_act))){
						if ($parity % 2) {
							$pair_impair = "even";
						} else {
							$pair_impair = "odd";
						}
						$parity += 1;
						$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
						$action = "onclick=document.location='./demandes.php?categ=action&act=see&idaction=".$com->id_action."'";
						$list .= 
						"<tr class='$pair_impair' id='act_$com->id_action' $tr_javascript style='cursor: pointer'>
					 		<td>&nbsp;</td>
					 		<td $action>".htmlentities($com->sujet_action,ENT_QUOTES,$charset)."</td>
							<td $action>".htmlentities($com->detail_action,ENT_QUOTES,$charset)."</td>				
							<td $action>".htmlentities(formatdate($com->date_action),ENT_QUOTES,$charset)."</td>
							<td $action>".htmlentities($com->temps_passe.$msg['demandes_action_time_unit'],ENT_QUOTES,$charset)."</td>
							<td $action><img src=\"./images/jauge.png\" height='15px' width=\"".$com->progression_action."%\" title='".$com->progression_action."%' /></td>
							<td><input type='checkbox' id='chk[".$com->id_action."]' name='chk[]' value='".$com->id_action."'></td>
						</tr>";
					}
				} else $list = "<tr><td>".htmlentities($msg['demandes_no_rdv_val'],ENT_QUOTES,$charset)."</td></tr>";
			}
		} else $list = "<tr><td>".htmlentities($msg['demandes_no_rdv_val'],ENT_QUOTES,$charset)."</td></tr>";
		
		$btn_action = "<input type='submit' class='bouton' name='val_rdv' id='val_rdv' value='".$msg['demandes_action_valid_rdv']."' onclick='this.form.act.value=\"val_rdv\"'>";
		$form_communication=str_replace('!!btn_action!!',$btn_action,$form_communication);
		$form_communication=str_replace('!!form_title!!',$msg['demandes_menu_rdv_a_valide'],$form_communication);
		$form_communication=str_replace('!!liste_comm!!',$list,$form_communication);
		$form_communication=str_replace('!!action!!','demandes.php?categ=action&sub=rdv_val',$form_communication);
		print $form_communication;
	}
	
	/*
	 * Ferme toutes les discussions en cours
	 */
	function close_fil(){
		global $chk, $dbh;
		
		for($i=0;$i<count($chk);$i++){		
			$req = "update demandes_actions set statut_action=3 where id_action='".$chk[$i]."'";
			mysql_query($req,$dbh);
		}
	}
	
	/*
	 * Annule tous les RDV
	 */
	function close_rdv(){
		global $chk, $dbh;
		
		for($i=0;$i<count($chk);$i++){		
			$req = "update demandes_actions set statut_action=3 where id_action='".$chk[$i]."'";
			mysql_query($req,$dbh);
		}
	}
	
	/*
	 * Valide tous les RDV
	 */
	function valider_rdv(){
		global $chk, $dbh;
		
		for($i=0;$i<count($chk);$i++){		
			$req = "update demandes_actions set statut_action=1 where id_action='".$chk[$i]."'";
			mysql_query($req,$dbh);
		}
	}
	
	/*
	 * Retourne le nom de celui qui a créé l'action
	 */
	function getCreateur($id_createur,$type_createur=0){
		global $dbh;
		
		if(!$type_createur)
			$rqt = "select concat(prenom,' ',nom) as nom, username from users where userid='".$id_createur."'";
		else 
			$rqt = "select concat(empr_prenom,' ',empr_nom) as nom from empr where id_empr='".$id_createur."'";
		
		$res = mysql_query($rqt,$dbh);
		if(mysql_num_rows($res)){		
			$createur = mysql_fetch_object($res);			
			return (trim($createur->nom)  ? $createur->nom : $createur->username );
		}
		
		return "";
	}
}
?>