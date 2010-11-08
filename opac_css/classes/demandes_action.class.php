<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes_action.class.php,v 1.11 2010-03-04 15:18:21 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$class_path/marc_table.class.php");
require_once("$class_path/explnum_affichage.class.php");

class demandes_action{
	var $id_action = 0;
	var $num_demande = 0;
	var $titre_demande = '';
	var $sujet_demande = '';
	var $type_demande = '';
	var $theme_demande = '';
	var $etat_demande = 0;
	var $date_demande = '0000-00-00';
	var $date_prevue = '0000-00-00';
	var $deadline = '0000-00-00';
	var $libelle_action = '';
	var $createur_action = '';
	var $demande_affecte = 0;
	var $progression_demande = 0;
	var $liste_etat = array();
	
	function demandes_action($iddemande=0, $idaction=0){
		global $dbh;
		
		$this->id_action = $idaction;
		$this->num_demande = $iddemande;
		if($this->id_action){
			$req = "select sujet_demande, titre_demande, date_demande, date_prevue, deadline_demande,
			 libelle_type, libelle_theme, sujet_action, actions_num_user, actions_type_user,etat_demande,
			if(isnull(group_concat(distinct if(concat(prenom,' ',nom)!='',concat(prenom,' ',nom),username) separator '/ ' )),0,1) as affected, progression
			from demandes_actions
			join demandes on id_demande=num_demande
			join demandes_theme on theme_demande=id_theme 
			join demandes_type on type_demande=id_type	
			left join demandes_users du on du.num_demande=id_demande 
			left join users on userid=du.num_user
			where id_action='".$this->id_action."'
			group by id_demande";
		} else {
			$req = "select sujet_demande, titre_demande, date_demande, date_prevue, deadline_demande,
			 libelle_type, libelle_theme,etat_demande, if(isnull(group_concat( distinct if(concat(prenom,' ',nom)!='',concat(prenom,' ',nom),username) separator '/ ' )),0,1) as affected,
			 progression
			from demandes
			join demandes_theme on theme_demande=id_theme 
			join demandes_type on type_demande=id_type	
			left join demandes_users du on du.num_demande=id_demande 
			left join users on userid=du.num_user	
			where id_demande='".$this->num_demande."'
			group by id_demande
			";
		}
		$res = mysql_query($req,$dbh);
		
		$dmde = mysql_fetch_object($res);
		$this->titre_demande = $dmde->titre_demande; 
		$this->sujet_demande = $dmde->sujet_demande;
		$this->date_demande = $dmde->date_demande;
		$this->date_prevue = $dmde->date_prevue;
		$this->deadline = $dmde->deadline_demande;
		$this->type_demande = $dmde->libelle_type;
		$this->theme_demande = $dmde->libelle_theme;
		$this->libelle_action = $dmde->sujet_action;
		$this->etat_demande = $dmde->etat_demande;
		$this->demande_affecte = $dmde->affected;
		$this->progression_demande = $dmde->progression;
		
		$list_etat = new marc_list("etat_demandes");
		$this->liste_etat = $list_etat->table;
		
		if($dmde->actions_num_user)
			$this->createur_action = $this->getCreateur($dmde->actions_num_user,$dmde->actions_type_user);
	}
	
	/*
	 * Affichage de la liste des actions
	 */
	function show_list_actions(){
		
		global $form_liste_actions, $charset,$msg;
				
		$form_liste_actions = str_replace('!!id_dmde!!',$this->num_demande,$form_liste_actions);
		$form_liste_actions = str_replace('!!titre_dmde!!',htmlentities($this->titre_demande,ENT_QUOTES,$charset),$form_liste_actions);
		$form_liste_actions = str_replace('!!sujet_dmde!!',htmlentities($this->sujet_demande,ENT_QUOTES,$charset),$form_liste_actions);
		$form_liste_actions = str_replace('!!theme_dmde!!',htmlentities($this->theme_demande,ENT_QUOTES,$charset),$form_liste_actions);
		$form_liste_actions = str_replace('!!type_dmde!!',htmlentities($this->type_demande,ENT_QUOTES,$charset),$form_liste_actions);
		$form_liste_actions = str_replace('!!date_dmde!!',htmlentities(formatdate($this->date_demande),ENT_QUOTES,$charset),$form_liste_actions);
		$form_liste_actions = str_replace('!!date_prevue_dmde!!',htmlentities(formatdate($this->date_prevue),ENT_QUOTES,$charset),$form_liste_actions);
		$form_liste_actions = str_replace('!!deadline_dmde!!',htmlentities(formatdate($this->deadline),ENT_QUOTES,$charset),$form_liste_actions);
		$form_liste_actions = str_replace('!!etat_dmde!!',htmlentities($this->liste_etat[$this->etat_demande],ENT_QUOTES,$charset),$form_liste_actions);
		$form_liste_actions = str_replace('!!progression!!',htmlentities($this->progression_demande,ENT_QUOTES,$charset)."%",$form_liste_actions);
		
		if($this->demande_affecte && ($this->etat_demande != 4) && ($this->etat_demande != 5) && ($this->etat_demande != 6)){
			$liste = "<label class='etiq_champ'>".$msg['demandes_actions']." : </label>";
			$liste .= $this->getContenuForm();
			$btns_actions = "
				<input type='button' class='bouton' id='question' name='question' value='".htmlentities($msg['demandes_add_ask'],ENT_QUOTES,$charset)."' onclick=\"show_form(".$this->num_demande.",'ask');\" />
				<input type='button' class='bouton' id='rdv' name='rdv' value='".htmlentities($msg['demandes_add_rdv'],ENT_QUOTES,$charset)."' onclick=\"show_form(".$this->num_demande.",'rdv');\"/>
				<input type='button' class='bouton' id='info' name='info' value='".htmlentities($msg['demandes_add_infos'],ENT_QUOTES,$charset)."' onclick=\"show_form(".$this->num_demande.",'info');\"/>
			";
			$form_liste_actions = str_replace('!!liste_actions!!',$liste,$form_liste_actions);
			$form_liste_actions = str_replace('!!btns_actions!!',$btns_actions,$form_liste_actions);
			
		} else {
			$form_liste_actions = str_replace('!!liste_actions!!',"",$form_liste_actions);
			$form_liste_actions = str_replace('!!btns_actions!!',"",$form_liste_actions);
		}
		
		return $form_liste_actions;
	}
	
	
	/*
	 * Affichage de la liste des notes associées à une action
	 */
	function show_list_notes($idaction=0){
		
		global $dbh, $msg, $charset;
		
	
		$req = "select id_note, CONCAT(SUBSTRING(contenu,1,30),'','...') as titre, contenu, date_note, prive, rapport, notes_num_user,notes_type_user 
		from demandes_notes where num_action='".$idaction."' and num_note_parent=0  order by date_note desc";
		$res = mysql_query($req,$dbh); 
		$liste ="";
		if(mysql_num_rows($res)){
			while(($note = mysql_fetch_object($res))){
				$createur = $this->getCreateur($note->notes_num_user,$note->notes_type_user);
				$contenu = "
					<div class='row'>
						<img src='./images/email_go.png' alt='".$msg['demandes_note_reply_icon']."' title='".$msg['demandes_note_reply_icon']."' 
							onclick='addnote($idaction,$note->id_note,$this->num_demande);' />
					</div>
					<div class='row'>
						<label class='etiquette'>".$msg['demandes_notes_contenu']." : </label>&nbsp;
						".nl2br(htmlentities($note->contenu,ENT_QUOTES,$charset))."
					</div>
				";
				$contenu .= $this->getChilds($note->id_note,$idaction);
				if(strlen($note->titre)<30){
					$note->titre = str_replace('...','',$note->titre);
				} 
				$liste .= gen_plus_form("note_".$note->id_note,"[".formatdate($note->date_note)."] ".$note->titre.($createur ? " <i>".sprintf($msg['demandes_action_by'],$createur."</i>") : ""), $contenu);
			}
		} 		
		return $liste;
	}
	
	/*
	 * Affichage des notes enfants
	 */
	function getChilds($id_note,$idaction){
		global $dbh, $charset, $msg;
		
		$req = "select id_note, CONCAT(SUBSTRING(contenu,1,30),'','...') as titre, contenu, date_note, prive, rapport, notes_num_user,notes_type_user 
		from demandes_notes where num_note_parent='".$id_note."' and num_action='".$idaction."' order by date_note desc";
		$res = mysql_query($req,$dbh);
		$display="";
		if(mysql_num_rows($res)){
			while(($fille = mysql_fetch_object($res))){
				$createur = $this->getCreateur($fille->notes_num_user,$fille->notes_type_user);
				$contenu = "
					<div class='row'>
						<img src='./images/email_go.png' alt='".$msg['demandes_note_reply_icon']."' title='".$msg['demandes_note_reply_icon']."' 
							onclick='addnote($idaction,$fille->id_note,$this->num_demande);' />		
					</div>
					<div class='row'>
						<label class='etiquette'>".$msg['demandes_notes_contenu']." : </label>&nbsp;
						".nl2br(htmlentities($fille->contenu,ENT_QUOTES,$charset))."
					</div>
				";
				$contenu .= $this->getChilds($fille->id_note,$idaction);
				if(strlen($fille->titre)<30){
					$fille->titre = str_replace('...','',$fille->titre);
				}
				$display .= "<span style='margin-left:20px'>".gen_plus_form("note_".$fille->id_note,"[".formatdate($fille->date_note)."] ".$fille->titre.($createur ? " <i>".sprintf($msg['demandes_action_by'],$createur."</i>") : ""), $contenu)."</span>";
			}
		}
		return $display;
	}
	
	
	/*
	 * Contenu du formulaire d'actions
	 */
	function getContenuForm(){
		
		global $dbh, $base_path, $msg;
		
		$req="select id_action, sujet_action, date_action, statut_action, progression_action, detail_action, num_demande, type_action as type, actions_num_user, actions_type_user, actions_read
			from demandes_actions 
			join demandes on num_demande=id_demande
			where num_demande='".$this->num_demande."' and prive_action=0 order by date_action, id_action desc";
		$res=mysql_query($req,$dbh);
		
		$liste = "";
		$marc_table=new marc_list("type_actions");
		$liste_type = $marc_table->table;
		if(mysql_num_rows($res)){
			while(($action = mysql_fetch_object($res))){
				$btn_add_msg = $msg['demandes_add_note'];
				switch ($action->type){					
					case '1':
						$image_type = "<img src=\"$base_path/images/comments.png\" style=\"vertical-align:middle;\" alt='".$liste_type[$action->type]."' title='".$liste_type[$action->type]."' />";
						$btn_add_msg = $msg['demandes_add_answer'];
						break;
					case '2':
						$image_type = "<img src=\"$base_path/images/magnifier.png\" style=\"vertical-align:middle;\" alt='".$liste_type[$action->type]."' title='".$liste_type[$action->type]."' />";
						break;
					case '3':
						$image_type = "<img src=\"$base_path/images/information.png\" style=\"vertical-align:middle;\" alt='".$liste_type[$action->type]."' title='".$liste_type[$action->type]."' />";
						break;
					case '4':
						$image_type = "<img src=\"$base_path/images/date.png\" style=\"vertical-align:middle;\" alt='".$liste_type[$action->type]."' title='".$liste_type[$action->type]."' />";
						break;			
				}
				$img_new = "";
				if($action->actions_read){
					$img_new = "<img src=\"$base_path/images/asterisk_yellow.png\" style=\"width:12px;vertical-align:middle;\" alt='".$msg['demandes_actions_new']."' title='".$msg['demandes_actions_new']."' />";
				}
				
				$content = "<br />
					<div class='row'>
						<div style='width:50%' class='left'>".$msg['demandes_action_detail']." : </div>
						<div style='width:50%' class='right'>".$action->detail_action."</div>
					</div>
					<div class='row'>
						<div style='width:50%' class='left'>".$msg['demandes_action_progression']." : </div>
						<div style='width:50%;background-color:#F3F3F3;position:relative;left:0px;top:0px;' class='right'> <img src=\"$base_path/images/jauge.png\" height='15px' width=\"".$action->progression_action."%\" />
						<span style='position:absolute;left:50%;top:0%;'><b>".$action->progression_action."%</b></span></div>
					</div>".
						$this->show_list_notes($action->id_action)."
					<br />";
					$docnum_display = new explnum_affichage(array(0 => $action->id_action),DOCNUM_DMDE);
					if($docnum_display->display)
						$content .= $msg['explnum']." : ".$docnum_display->display;
				if($action->statut_action != 3){	
					$content .= "<div class='row'>						
						<input class='bouton' type='button' name='add_note' id='add_note' value='".$btn_add_msg."' onclick=\"addnote(".$action->id_action.",0,".$this->num_demande.");\" />
					</div>";
				}
				
				$createur = $this->getCreateur($action->actions_num_user,$action->actions_type_user);
				$liste .= gen_plus_form("act_".$action->id_action,$img_new."&nbsp;".$image_type."&nbsp[".formatdate($action->date_action)."] ".$action->sujet_action.($createur ? "&nbsp;<i>".sprintf($msg["demandes_action_by"],$createur)."</i>" : ""),$content);
				$content ="";
			}
		} else {
			$liste = "<div class='row'>".$msg['demandes_no_action']."</div>";
		}
		
		$req_up ="update demandes_actions set actions_read='0' where num_demande='".$this->num_demande."'";
		mysql_query($req_up,$dbh);
		
		return $liste;
	}
	
	/*
	 * Alerte par mail
	 */	
	function send_alert_by_mail($idsender,$idparent=0){
		
		global $msg, $dbh, $empr_nom, $empr_prenom, $empr_mail;
		
		if($idparent){	
			$req = "select contenu, sujet_action from demandes_notes
			join demandes_actions on num_action=id_action
			where id_note='".$idparent."'";
			$res = mysql_query($req,$dbh);
			$nots = mysql_fetch_object($res);		
			$titre = (strlen($nots->contenu)<30 ? substr($nots->contenu,0,30) : substr($nots->contenu,0,30)."...");
			$objet = sprintf($msg['demandes_note_mail_reponse_object'], $titre);
			$contenu = $empr_prenom." ".$empr_nom." ".sprintf($msg['demandes_note_mail_reponse'],$titre,$nots->sujet_action,$this->titre_demande);
		} else{
			$contenu = $empr_prenom." ".$empr_nom." ".sprintf($msg['demandes_note_mail_new'],$this->libelle_action,$this->titre_demande);
			$objet = $msg['demandes_note_mail_new_object'];
		}
		
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1";
		
		//Envoi du mail aux autres documentalistes concernés par la demande
		$req = "select user_email, concat(prenom,' ',nom) as nom from users
			join demandes_users on num_user=userid
			where num_demande='".$this->num_demande."'";
		$res = mysql_query($req,$dbh);
		while($user = mysql_fetch_object($res)){	
			if($user->user_email)
				$envoi_OK = mailpmb($user->nom,$user->user_email,$objet,$contenu,$empr_prenom." ".$empr_nom,$empr_mail,$headers,"" );
				
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
			return (trim($createur->nom)  ? $createur->nom : $createur->username);
		}
		
		return "";
	}
}
?>