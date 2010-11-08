<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes_notes.class.php,v 1.6 2010-03-04 15:18:21 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/mail.inc.php");

class demandes_notes {
	
	var $id_note = 0;
	var $date_note = '0000-00-00';
	var $contenu = '';
	var $prive = 0;
	var $rapport = 0;
	var $num_note_parent = 0;
	var $num_action = 0;
	var $num_demande = 0;
	var $libelle_action = '';
	var $libelle_demande = '';
	var $notes_num_user = 0;
	var $notes_type_user = 0;
	var $createur_note = '';
	
	function demandes_notes($id_note=0,$id_action=0){
		global $dbh;
		
		$this->id_note = $id_note;
		if($id_action) $this->num_action = $id_action;
		
		if($this->id_note){
			$req = "select id_note, prive, rapport,contenu,date_note, sujet_action, id_demande, titre_demande, notes_num_user, notes_type_user, num_action, num_note_parent from demandes_notes 
			join demandes_actions on num_action=id_action 
			join demandes on num_demande=id_demande
			where id_note='".$this->id_note."'";
			$res = mysql_query($req,$dbh);
			if(mysql_num_rows($res)){
				$obj = mysql_fetch_object($res);
				$this->date_note = $obj->date_note;
				$this->contenu = $obj->contenu;
				$this->rapport = $obj->rapport;
				$this->prive = $obj->prive;
				$this->num_note_parent = $obj->num_note_parent;	
				$this->num_action = $obj->num_action;	
				$this->libelle_action = $obj->sujet_action;
				$this->libelle_demande = $obj->titre_demande;
				$this->num_demande = $obj->id_demande;
				$this->notes_num_user = $obj->notes_num_user;
				$this->notes_type_user = $obj->notes_type_user;
			} else {
				$this->date_note = '0000-00-00';
				$this->contenu = '';
				$this->rapport = 0;
				$this->prive = 0;
				$this->num_note_parent = 0;	
				$this->num_action = 0;
				$this->notes_num_user = 0;
				$this->notes_type_user = 0;
			}
		} else {
			$this->date_note = '0000-00-00';
			$this->contenu = '';
			$this->rapport = 0;
			$this->prive = 0;
			$this->num_note_parent = 0;	
			$this->notes_num_user = 0;
			$this->notes_type_user = 0;
		}
		
		if($this->num_action){
			$req = "select sujet_action, titre_demande, id_demande  
			from demandes_actions join demandes on num_demande=id_demande
			where id_action='".$this->num_action."'
			";
			$res = mysql_query($req,$dbh);
			$obj = mysql_fetch_object($res);
			$this->libelle_action = $obj->sujet_action;
			$this->libelle_demande = $obj->titre_demande;
			$this->num_demande = $obj->id_demande;
		}
		
		if($this->notes_num_user){
			$this->createur_note = $this->getCreateur($this->notes_num_user,$this->notes_type_user);
		}
	}
	
	/*
	 * Formulaire d'ajout/modification
	 */
	function show_modif_form($reply=false){
		global $form_modif_note, $msg, $charset, $demandes_include_note;
		
		$act_cancel = "document.location='./demandes.php?categ=action&act=see&idaction=$this->num_action'";
		$form_modif_note = str_replace('!!cancel_action!!',$act_cancel,$form_modif_note);
		
		if($this->id_note && !$reply){			
			$title = (strlen($this->contenu)>30 ? substr($this->contenu,0,30).'...' : $this->contenu);
			$form_modif_note = str_replace('!!form_title!!',$msg['demandes_note_modif'].' : '.$title,$form_modif_note);
			
			$form_modif_note = str_replace('!!contenu!!',htmlentities($this->contenu,ENT_QUOTES,$charset),$form_modif_note);
			if($this->rapport)
				$form_modif_note = str_replace('!!ck_rapport!!','checked',$form_modif_note);
			else $form_modif_note = str_replace('!!ck_rapport!!','',$form_modif_note);
			if($this->prive)
				$form_modif_note = str_replace('!!ck_prive!!','checked',$form_modif_note);
			else $form_modif_note = str_replace('!!ck_prive!!','',$form_modif_note);
			$form_modif_note = str_replace('!!date_note_btn!!',formatdate($this->date_note),$form_modif_note);
			$form_modif_note = str_replace('!!date_note!!',$this->date_note,$form_modif_note);
			$form_modif_note = str_replace('!!idnote!!',$this->id_note,$form_modif_note);
			$form_modif_note = str_replace('!!idaction!!',$this->num_action,$form_modif_note);			
			//Parent
			$nots = new demandes_notes($this->num_note_parent);
			$form_modif_note = str_replace('!!parent_text!!',$nots->contenu,$form_modif_note);
			$form_modif_note = str_replace('!!id_note_parent!!',$nots->id_note,$form_modif_note);
			$form_modif_note = str_replace('!!style!!',"",$form_modif_note);
			
			$btn_suppr = "<input type='submit' class='bouton' value='".$msg[63]."' id='suppr_note' name='suppr_note' onclick='this.form.act.value=\"suppr_note\";return confirm_delete();'";
		} elseif($this->id_note && $reply){
			$nots = new demandes_notes($this->id_note);
			$title = (strlen($nots->contenu)>30 ? substr($nots->contenu,0,30).'...' : $nots->contenu);
			$form_modif_note = str_replace('!!form_title!!',$msg['demandes_note_reply'].' : '.$title,$form_modif_note);			
			$form_modif_note = str_replace('!!contenu!!','',$form_modif_note);
			if($demandes_include_note)
				$form_modif_note = str_replace('!!ck_rapport!!','checked',$form_modif_note);
			else $form_modif_note = str_replace('!!ck_rapport!!','',$form_modif_note);	
			$form_modif_note = str_replace('!!ck_prive!!','',$form_modif_note);
			$date = formatdate(today());
			$date_note=date("Ymd",time());
			$form_modif_note = str_replace('!!date_note_btn!!',$date,$form_modif_note);
			$form_modif_note = str_replace('!!date_note!!',$date_note,$form_modif_note);
			$form_modif_note = str_replace('!!idnote!!','',$form_modif_note);
			$form_modif_note = str_replace('!!idaction!!',$this->num_action,$form_modif_note);						
			//Parent
			$form_modif_note = str_replace('!!parent_text!!',$nots->contenu,$form_modif_note);
			$form_modif_note = str_replace('!!id_note_parent!!',$nots->id_note,$form_modif_note);
			$form_modif_note = str_replace('!!style!!',"style='display:none'",$form_modif_note);
		} else {
			$form_modif_note = str_replace('!!form_title!!',$msg['demandes_note_creation'],$form_modif_note);
			$form_modif_note = str_replace('!!ck_prive!!','',$form_modif_note);
			if($demandes_include_note)
				$form_modif_note = str_replace('!!ck_rapport!!','checked',$form_modif_note);
			else $form_modif_note = str_replace('!!ck_rapport!!','',$form_modif_note);	
			$form_modif_note = str_replace('!!contenu!!','',$form_modif_note);
			$date = formatdate(today());
			$date_note=date("Ymd",time());
			$form_modif_note = str_replace('!!date_note_btn!!',$date,$form_modif_note);
			$form_modif_note = str_replace('!!date_note!!',$date_note,$form_modif_note);
			$form_modif_note = str_replace('!!idnote!!','',$form_modif_note);
			$form_modif_note = str_replace('!!idaction!!',$this->num_action,$form_modif_note);
			$form_modif_note = str_replace('!!parent_text!!','',$form_modif_note);
			$form_modif_note = str_replace('!!id_note_parent!!','',$form_modif_note);
			$form_modif_note = str_replace('!!style!!','',$form_modif_note);
		}
		
		$path = "<a href=./demandes.php?categ=gestion&act=see_dmde&iddemande=$this->num_demande>".htmlentities($this->libelle_demande,ENT_QUOTES,$charset)."</a>";
		$path .= " > <a href=./demandes.php?categ=action&act=see&idaction=$this->num_action>".htmlentities($this->libelle_action,ENT_QUOTES,$charset)."</a>";
		$form_modif_note = str_replace('!!path!!',$path,$form_modif_note);
		$form_modif_note = str_replace('!!btn_suppr!!',$btn_suppr,$form_modif_note);
		
		print $form_modif_note;
	}
	
	/*
	 * Création/Modification d'une demande
	 */
	function save(){
		
		global $dbh, $contenu_note, $idaction, $id_note_parent;
		global $date_note, $ck_rapport, $ck_prive, $PMBuserid;
		
		if($this->id_note){
			//MODIFICATION
			$req = "update demandes_notes set contenu='".$contenu_note."',  
				date_note='".$date_note."', 
				prive='".($ck_prive ? 1 : 0)."', 
				rapport='".($ck_rapport ? 1 : 0)."', 
				num_action='".$idaction."',
				notes_num_user='".$PMBuserid."',
				notes_type_user='0',
				num_note_parent='".$id_note_parent."'
				where id_note='".$this->id_note."'";
				$req_up = "update demandes_actions set actions_read='1' where id_action='".$idaction."'";
				mysql_query($req_up,$dbh);
		} else {
			//CREATION
			$req = "insert into demandes_notes set contenu='".$contenu_note."',  
				date_note='".$date_note."', 
				prive='".($ck_prive ? 1 : 0)."', 
				rapport='".($ck_rapport ? 1 : 0)."', 
				num_action='".$idaction."',
				num_note_parent='".$id_note_parent."',
				notes_num_user='".$PMBuserid."',
				notes_type_user='0'";
			
			if(!$ck_prive) {
				$this->send_alert_by_mail($PMBuserid,$id_note_parent);
				$req_up = "update demandes_actions set actions_read='1' where id_action='".$idaction."'";
				mysql_query($req_up,$dbh);
			}
		}
		mysql_query($req,$dbh);
	}
	
	/*
	 * Suppression d'une note
	 */
	function delete(){
		global $dbh;
		if($this->id_note){
			$req = "delete from demandes_notes where id_note='".$this->id_note."'";
			mysql_query($req,$dbh);
			$req = "delete from demandes_notes where num_note_parent='".$this->id_note."'";
			mysql_query($req,$dbh);
		}
	}
	
	/*
	 * Affichage de la liste des notes associées à une action
	 */
	function show_list_notes($idaction=0){
		
		global $form_table_note, $dbh, $msg, $charset;
		
	
		$req = "select id_note, CONCAT(SUBSTRING(contenu,1,50),'','...') as titre, contenu, date_note, prive, rapport,notes_num_user,notes_type_user
		 from demandes_notes where num_action='".$idaction."' and num_note_parent=0  order by date_note desc ,id_note desc";
		$res = mysql_query($req,$dbh); 
		$liste ="";
		if(mysql_num_rows($res)){
			while(($note = mysql_fetch_object($res))){
				$createur = $this->getCreateur($note->notes_num_user,$note->notes_type_user);
				$contenu = "
					<div class='row'>
						<div class='left'>
							<input type='image' src='./images/email_go.png' alt='".htmlentities($msg['demandes_note_reply_icon'],ENT_QUOTES,$charset)."' title='".htmlentities($msg['demandes_note_reply_icon'],ENT_QUOTES,$charset)."' 
								onclick='document.forms[\"modif_notes\"].act.value=\"reponse\";document.forms[\"modif_notes\"].idnote.value=\"$note->id_note\";' />
							<input type='image' src='./images/b_edit.png' alt='".htmlentities($msg['demandes_note_modif_icon'],ENT_QUOTES,$charset)."' title='".htmlentities($msg['demandes_note_modif_icon'],ENT_QUOTES,$charset)."' 
								onclick='document.forms[\"modif_notes\"].act.value=\"modif_note\";document.forms[\"modif_notes\"].idnote.value=\"$note->id_note\";' />
							<input type='image' src='./images/cross.png' alt='".htmlentities($msg['demandes_note_suppression'],ENT_QUOTES,$charset)."' title='".htmlentities($msg['demandes_note_suppression'],ENT_QUOTES,$charset)."' 
								onclick='document.forms[\"modif_notes\"].act.value=\"suppr_note\";document.forms[\"modif_notes\"].idnote.value=\"$note->id_note\";' />
					</div>
					</div>
					<div class='row'>
						<label class='etiquette'>".$msg['demandes_note_privacy']." : </label>&nbsp;
						".( $note->prive ? $msg['40'] : $msg['39'])."
					</div>
					<div class='row'>
						<label class='etiquette'>".$msg['demandes_note_rapport']." : </label>&nbsp;
						".( $note->rapport ? $msg['40'] : $msg['39'])."
					</div>
					<div class='row'>
						<label class='etiquette'>".$msg['demandes_note_contenu']." : </label>&nbsp;
						".nl2br(htmlentities($note->contenu,ENT_QUOTES,$charset))."
					</div>
				";
				$contenu .= $this->getChilds($note->id_note);
				if(strlen($note->titre)<50){
					$note->titre = str_replace('...','',$note->titre);
				}
				$liste .= gen_plus("note_".$note->id_note,"[".formatdate($note->date_note)."] ".$note->titre.($createur ? " <i>".sprintf($msg['demandes_action_by'],$createur."</i>") : ""), $contenu);
			}
		} else {
			$liste .= htmlentities($msg['demandes_note_no_list'],ENT_QUOTES,$charset);
		}
		
		$form_table_note = str_replace('!!idaction!!',$this->num_action,$form_table_note);
		$form_table_note = str_replace('!!liste_notes!!',$liste,$form_table_note);
		print $form_table_note;
	}
	
	/*
	 * Affichage des notes enfants
	 */
	function getChilds($id_note){
		global $dbh, $charset, $msg;
		
		$req = "select id_note, CONCAT(SUBSTRING(contenu,1,50),'','...') as titre, contenu, date_note, prive, rapport, notes_num_user,notes_type_user 
		from demandes_notes where num_note_parent='".$id_note."' and num_action='".$this->num_action."' order by date_note desc, id_note desc";
		$res = mysql_query($req,$dbh);
		$display="";
		if(mysql_num_rows($res)){
			while(($fille = mysql_fetch_object($res))){
				$createur = $this->getCreateur($fille->notes_num_user,$fille->notes_type_user);
				$contenu = "
					<div class='row'>
						<div class='left'>
							<input type='image' src='./images/email_go.png' alt='".htmlentities($msg['demandes_note_reply_icon'],ENT_QUOTES,$charset)."' title='".htmlentities($msg['demandes_note_reply_icon'],ENT_QUOTES,$charset)."' 
										onclick='document.forms[\"modif_notes\"].act.value=\"reponse\";document.forms[\"modif_notes\"].idnote.value=\"$fille->id_note\";' />
							<input type='image' src='./images/b_edit.png' alt='".htmlentities($msg['demandes_note_modif_icon'],ENT_QUOTES,$charset)."' title='".htmlentities($msg['demandes_note_modif_icon'],ENT_QUOTES,$charset)."' 
										onclick='document.forms[\"modif_notes\"].act.value=\"modif_note\";document.forms[\"modif_notes\"].idnote.value=\"$fille->id_note\";' />
							<input type='image' src='./images/cross.png' alt='".htmlentities($msg['demandes_note_suppression'],ENT_QUOTES,$charset)."' title='".htmlentities($msg['demandes_note_suppression'],ENT_QUOTES,$charset)."' 
										onclick='document.forms[\"modif_notes\"].act.value=\"suppr_note\";document.forms[\"modif_notes\"].idnote.value=\"$fille->id_note\";' />
					</div>
					</div>
					<div class='row'>
						<label class='etiquette'>".$msg['demandes_note_privacy']." : </label>&nbsp;
						".( $fille->prive ? $msg['40'] : $msg['39'])."
					</div>
					<div class='row'>
						<label class='etiquette'>".$msg['demandes_note_rapport']." : </label>&nbsp;
						".( $fille->rapport ? $msg['40'] : $msg['39'])."
					</div>
					<div class='row'>
						<label class='etiquette'>".$msg['demandes_note_contenu']." : </label>&nbsp;
						".nl2br(htmlentities($fille->contenu,ENT_QUOTES,$charset))."
					</div>
				";
				$contenu .= $this->getChilds($fille->id_note);
				if(strlen($fille->titre)<50){
					$fille->titre = str_replace('...','',$fille->titre);
				}
				$display .= "<span style='margin-left:20px'>".gen_plus("note_".$fille->id_note,"[".formatdate($fille->date_note)."] ".$fille->titre.($createur ? " <i>".sprintf($msg['demandes_action_by'],$createur."</i>") : ""), $contenu)."</span>";
			}
		}
		return $display;
	}
	
	
	/*
	 * Alerte par mail
	 */	
	function send_alert_by_mail($idsender,$idparent=0){
		
		global $msg, $PMBusernom, $PMBuserprenom, $PMBuseremail, $dbh;
		
		if($idparent){	
			$nots = new demandes_notes($idparent);		
			$titre = (strlen($nots->contenu)<30 ? substr($nots->contenu,0,30) : substr($nots->contenu,0,30)."...");
			$objet = sprintf($msg['demandes_note_mail_reponse_object'], $titre);
			$contenu = $PMBuserprenom." ".$PMBusernom." ".sprintf($msg['demandes_note_mail_reponse'],$titre,$nots->libelle_action,$nots->libelle_demande);
		} else{
			$contenu = $PMBuserprenom." ".$PMBusernom." ".sprintf($msg['demandes_note_mail_new'],$this->libelle_action,$this->libelle_demande);
			$objet = $msg['demandes_note_mail_new_object'];
		}
		
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1";
		
		//Envoi du mail aux autres documentalistes concernés par la demande
		$req = "select user_email, concat(prenom,' ',nom) as nom from users
			join demandes_users on num_user=userid
			where num_demande='".$this->num_demande."' and num_user !='".$idsender."'";
		$res = mysql_query($req,$dbh);
		while(($user = mysql_fetch_object($res))){	
			if($user->user_email)
				$envoi_OK = mailpmb($user->nom,$user->user_email,$objet,$contenu,$PMBuserprenom." ".$PMBusernom,$PMBuseremail,$headers,"" );
				
		}
		
		//Envoi du mail au demandeur
		$req= "select concat(empr_nom,' ',empr_prenom) as nom, empr_mail
			from empr
			join demandes on id_empr=num_demandeur
			where id_demande='".$this->num_demande."'";
		$res = mysql_query($req,$dbh);
		$empr = mysql_fetch_object($res);		
		if($empr->empr_mail) 
			$envoi_OK = mailpmb($empr->nom,$empr->empr_mail,$objet,$contenu,$PMBuserprenom." ".$PMBusernom,$PMBuseremail,$headers,"");
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
			return (trim($createur->nom)  ? $createur->nom : $createur->username);
		}
		
		return "";
	}
}
?>