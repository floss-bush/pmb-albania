<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes.class.php,v 1.10 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/demandes_actions.class.php");
require_once($class_path."/liste_simple.class.php");
require_once($class_path."/workflow.class.php");

require_once("$include_path/templates/catalog.tpl.php");
require_once("$class_path/notice.class.php");
require_once("$class_path/tu_notice.class.php");
require_once("$class_path/explnum.class.php");

/*
 * Classe de gestion des demandes
 */
class demandes {
	
	var $id_demande = 0;
	var $etat_demande = 0;
	var $date_demande = '0000-00-00';
	var $deadline_demande = '0000-00-00';
	var $sujet_demande = '';
	var $num_demandeur = 0;
	var $users = array();
	var $progression = 0;
	var $theme_demande = 0;
	var $type_demande = 0;
	var $theme_libelle = '';
	var $type_libelle = '';
	var $date_prevue = '0000-00-00';
	var $titre_demande = '';	
	var $liste_etat = array();
	var $workflow = array();
	var $num_notice = 0;
	
	/*
	 * Constructeur
	 */
	function demandes($id=0){
		
		global $base_path, $dbh;
		
		$this->workflow = new workflow('DEMANDES','INITIAL');
				
		$this->id_demande = $id;
		if($this->id_demande){
			$req = "select etat_demande, date_demande, deadline_demande, sujet_demande, num_demandeur, progression, num_notice,
			date_prevue, theme_demande, type_demande, titre_demande, libelle_theme,libelle_type from demandes d, demandes_theme dt, demandes_type dy 
			where dy.id_type=d.type_demande and dt.id_theme=d.theme_demande and id_demande='".$this->id_demande."'"; 
			$res=mysql_query($req,$dbh); 
			if(mysql_num_rows($res)){
				$dmde = mysql_fetch_object($res);
				$this->etat_demande = $dmde->etat_demande;
				$this->date_demande = $dmde->date_demande;
				$this->deadline_demande = $dmde->deadline_demande;
				$this->sujet_demande = $dmde->sujet_demande;
				$this->num_demandeur = $dmde->num_demandeur;
				$this->progression = $dmde->progression;
				$this->date_prevue = $dmde->date_prevue;
				$this->theme_demande = $dmde->theme_demande;
				$this->type_demande = $dmde->type_demande;
				$this->titre_demande = $dmde->titre_demande;
				$this->theme_libelle = $dmde->libelle_theme;
				$this->type_libelle = $dmde->libelle_type;
				$this->num_notice = $dmde->num_notice;
			} else{
				$this->id_demande = 0;
				$this->etat_demande = 0;
				$this->date_demande = '0000-00-00';
				$this->deadline_demande = '0000-00-00';
				$this->sujet_demande = '';
				$this->num_demandeur = 0;
				$this->progression = 0;
				$this->date_prevue = '0000-00-00';
				$this->theme_demande = 0;
				$this->type_demande = 0;
				$this->titre_demande = '';
				$this->num_notice = 0; 
			}
			$req = "select num_user, concat(prenom,' ',nom) as nom, username from demandes_users, users where num_user=userid and num_demande='".$this->id_demande."' and users_statut=1";
			$res = mysql_query($req,$dbh);		
			$i=0;
			while($user = mysql_fetch_object($res)){
				$this->users[$i]['nom'] = (trim($user->nom) ? $user->nom : $user->username);
				$this->users[$i]['id'] = $user->num_user;
				$i++;
			}
		} else {
			$this->id_demande = 0;
			$this->etat_demande = 0;
			$this->date_demande = '0000-00-00';
			$this->deadline_demande = '0000-00-00';
			$this->sujet_demande = '';
			$this->num_demandeur = 0;
			$this->num_user = array();
			$this->progression = 0;
			$this->date_prevue = '0000-00-00';
			$this->theme_demande = 0;
			$this->type_demande = 0;
			$this->titre_demande = '';
			$this->num_notice = 0;
		}
		
		$this->liste_etat = $this->workflow->getStateList();
	}
	
	/*
	 * Formulaire de création d'une demande
	 */
	function show_modif_form(){
		
		global $form_modif_demande, $msg, $charset;
		
		$themes = new demandes_themes('demandes_theme','id_theme','libelle_theme',$this->theme_demande);
		$types = new demandes_types('demandes_type','id_type','libelle_type',$this->type_demande);
		
		if(!$this->id_demande){
			$form_modif_demande = str_replace('!!form_title!!',htmlentities($msg['demandes_creation'],ENT_QUOTES,$charset),$form_modif_demande);
			$form_modif_demande = str_replace('!!sujet!!','',$form_modif_demande);
			$form_modif_demande = str_replace('!!progression!!','',$form_modif_demande);
			$form_modif_demande = str_replace('!!empr_txt!!','',$form_modif_demande);
			$form_modif_demande = str_replace('!!id_empr!!','',$form_modif_demande);
			$form_modif_demande = str_replace('!!titre!!','',$form_modif_demande);
			$form_modif_demande = str_replace('!!select_etat!!',$this->getStateSelector(),$form_modif_demande);
			$form_modif_demande = str_replace('!!select_user!!',$this->getUsersSelector('',false,true),$form_modif_demande);
			$form_modif_demande = str_replace('!!select_theme!!',$themes->getListSelector(),$form_modif_demande);
			$form_modif_demande = str_replace('!!select_type!!',$types->getListSelector(),$form_modif_demande);
			
			$date = formatdate(today());
			$date_debut=date("Y-m-d",time());
			$date_dmde = "<input type='button' class='bouton' id='date_debut_btn' name='date_debut_btn' value='!!date_debut_btn!!' 
				onClick=\"openPopUp('./select.php?what=calendrier&caller=modif_dmde&date_caller=!!date_debut!!&param1=date_debut&param2=date_debut_btn&auto_submit=NO&date_anterieure=YES', 'date_debut', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"/>";
			$form_modif_demande = str_replace('!!date_demande!!',$date_dmde,$form_modif_demande);
			
			$form_modif_demande = str_replace('!!date_fin_btn!!',$date,$form_modif_demande);
			$form_modif_demande = str_replace('!!date_debut_btn!!',$date,$form_modif_demande);
			$form_modif_demande = str_replace('!!date_debut!!',$date_debut,$form_modif_demande);
			$form_modif_demande = str_replace('!!date_fin!!',$date_debut,$form_modif_demande);
			$form_modif_demande = str_replace('!!date_prevue!!',$date_debut,$form_modif_demande);
			$form_modif_demande = str_replace('!!date_prevue_btn!!',$date,$form_modif_demande);
			
			
			$form_modif_demande = str_replace('!!btn_suppr!!','',$form_modif_demande);		
			$form_modif_demande = str_replace('!!iddemande!!','',$form_modif_demande);	
			$act_cancel = "document.location='./demandes.php?categ=list'";
			$act_form = "./demandes.php?categ=list";

		} else {
			$btn_suppr = "<input type='submit' class='bouton' value='$msg[63]' onclick='this.form.act.value=\"suppr\"; return confirm_delete();' />";			
			$form_modif_demande = str_replace('!!form_title!!',htmlentities(sprintf($msg['demandes_modification'],' : '.$this->titre_demande),ENT_QUOTES,$charset),$form_modif_demande);
			$form_modif_demande = str_replace('!!btn_suppr!!',$btn_suppr,$form_modif_demande);
			
			$form_modif_demande = str_replace('!!titre!!',htmlentities($this->titre_demande,ENT_QUOTES,$charset),$form_modif_demande);
			$form_modif_demande = str_replace('!!sujet!!',htmlentities($this->sujet_demande,ENT_QUOTES,$charset),$form_modif_demande);
			$form_modif_demande = str_replace('!!progression!!',htmlentities($this->progression,ENT_QUOTES,$charset),$form_modif_demande);
			$carac_empr = $this->getCaracEmpr($this->num_demandeur);
			$nom = $carac_empr['nom'];
			$form_modif_demande = str_replace('!!empr_txt!!',htmlentities($nom,ENT_QUOTES,$charset),$form_modif_demande);
			$form_modif_demande = str_replace('!!idempr!!',$this->num_demandeur,$form_modif_demande);
			$form_modif_demande = str_replace('!!titre!!',$this->titre_demande,$form_modif_demande);
			$form_modif_demande = str_replace('!!select_etat!!',$this->workflow->getStateCommentById($this->etat_demande),$form_modif_demande);
			$form_modif_demande = str_replace('!!select_user!!',$this->getUsersSelector('',false,true),$form_modif_demande);
			$form_modif_demande = str_replace('!!select_theme!!',$themes->getListSelector($this->theme_demande),$form_modif_demande);
			$form_modif_demande = str_replace('!!select_type!!',$types->getListSelector($this->type_demande),$form_modif_demande);
			
			$form_modif_demande = str_replace('!!date_fin_btn!!',formatdate($this->deadline_demande),$form_modif_demande);
			$form_modif_demande = str_replace('!!date_demande!!',formatdate($this->date_demande),$form_modif_demande);
			$form_modif_demande = str_replace('!!date_debut!!',htmlentities($this->date_demande,ENT_QUOTES,$charset),$form_modif_demande);
			$form_modif_demande = str_replace('!!date_fin!!',htmlentities($this->deadline_demande,ENT_QUOTES,$charset),$form_modif_demande);
			$form_modif_demande = str_replace('!!date_prevue_btn!!',formatdate($this->date_prevue),$form_modif_demande);
			$form_modif_demande = str_replace('!!date_prevue!!',htmlentities($this->date_prevue,ENT_QUOTES,$charset),$form_modif_demande);
			$form_modif_demande = str_replace('!!iddemande!!',$this->id_demande,$form_modif_demande);
			$act_cancel = "document.location='./demandes.php?categ=gestion&act=see_dmde&iddemande=$this->id_demande'";
			$act_form = "./demandes.php?categ=gestion";

		}
		$form_modif_demande = str_replace('!!form_action!!',$act_form,$form_modif_demande);
		$form_modif_demande = str_replace('!!cancel_action!!',$act_cancel,$form_modif_demande);
		print $form_modif_demande;
	}
	
	/*
	 * Formulaire de création de la liste des demandes
	 */
	function show_list_form(){
		global $form_filtre_demande, $form_liste_demande;
		global $dbh, $charset, $msg;
		global $idetat,$iduser,$idempr,$user_input;
		global $date_debut,$date_fin, $id_type, $id_theme, $dmde_loc;
		
		//Formulaire des filtres
		$date_deb="
			<input type='hidden' id='date_debut' name='date_debut' value='!!date_debut!!' />
			<input type='button' class='bouton' id='date_debut_btn' name='date_debut_btn' value='!!date_debut_btn!!' onClick=\"openPopUp('./select.php?what=calendrier&caller=search&date_caller=!!date_debut!!&param1=date_debut&param2=date_debut_btn&auto_submit=NO&date_anterieure=YES', 'date_debut', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"/>
		";
		$date_but="
			<input type='hidden' id='date_fin' name='date_fin' value='!!date_fin!!' />
			<input type='button' class='bouton' id='date_fin_btn' name='date_fin_btn' value='!!date_fin_btn!!' onClick=\"openPopUp('./select.php?what=calendrier&caller=search&date_caller=!!date_fin!!&param1=date_fin&param2=date_fin_btn&auto_submit=NO&date_anterieure=YES', 'date_fin', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"/>
		";
		
		if($date_debut && $date_fin){
			$date_deb = str_replace('!!date_debut_btn!!',formatdate($date_debut),$date_deb);
			$date_but = str_replace('!!date_fin_btn!!',formatdate($date_fin),$date_but);
			$date_deb = str_replace('!!date_debut!!',$date_debut,$date_deb);
			$date_but = str_replace('!!date_fin!!',$date_fin,$date_but);
		} else {
			$date_lib = formatdate(today());
			$date_sql = date("Y-m-d",time());		
			$date_deb = str_replace('!!date_debut_btn!!',$date_lib,$date_deb);
			$date_but = str_replace('!!date_fin_btn!!',$date_lib,$date_but);
			$date_deb = str_replace('!!date_debut!!',$date_sql,$date_deb);
			$date_but = str_replace('!!date_fin!!',$date_sql,$date_but);
		}
		if($idempr){
			$form_filtre_demande = str_replace('!!idempr!!',$idempr,$form_filtre_demande);
			$carac_empr = $this->getCaracEmpr($idempr);
			$nom = $carac_empr['nom'];
			$form_filtre_demande = str_replace('!!empr_txt!!',$nom,$form_filtre_demande);
		} else {
			$form_filtre_demande = str_replace('!!idempr!!','',$form_filtre_demande);
			$form_filtre_demande = str_replace('!!empr_txt!!','',$form_filtre_demande);
		}
		
		$form_filtre_demande = str_replace('!!user_input!!',htmlentities(stripslashes($user_input),ENT_QUOTES, $charset),$form_filtre_demande);
		$form_filtre_demande = str_replace('!!periode!!',sprintf($msg['demandes_filtre_periode_lib'],$date_deb,$date_but),$form_filtre_demande);
		$onchange = "onchange='this.form.act.value=\"search\";submit()'";
		$form_filtre_demande = str_replace('!!affectation!!',$this->getUsersSelector($onchange,true,false,true),$form_filtre_demande);
		$form_filtre_demande = str_replace('!!state!!',$this->getStateSelector($idetat,$onchange,true),$form_filtre_demande);
		
		$themes = new demandes_themes('demandes_theme','id_theme','libelle_theme',$id_type);
		$types = new demandes_types('demandes_type','id_type','libelle_type',$id_theme);
		
		$form_filtre_demande = str_replace('!!theme!!',$themes->getListSelector($id_theme,$onchange,true),$form_filtre_demande);
		$form_filtre_demande = str_replace('!!type!!',$types->getListSelector($id_type,$onchange,true),$form_filtre_demande);
		
		$req_loc = "select idlocation, location_libelle from docs_location";
		$res_loc = mysql_query($req_loc,$dbh);
		$sel_loc = "<select id='dmde_loc' name='dmde_loc' onchange='this.form.act.value=\"search\";submit()' >";
		$sel_loc .= "<option value='0' ".(!$dmde_loc ? 'selected' : '').">".htmlentities($msg['demandes_localisation_all'],ENT_QUOTES,$charset)."</option>";
		while($loc = mysql_fetch_object($res_loc)){
			$sel_loc .= "<option value='".$loc->idlocation."' ".(($dmde_loc==$loc->idlocation) ? 'selected' : '').">".htmlentities($loc->location_libelle,ENT_QUOTES,$charset)."</option>";
		}
		$sel_loc.= "</select>";
		$form_filtre_demande = str_replace('!!localisation!!',$sel_loc,$form_filtre_demande);
		
		print $form_filtre_demande;
		
		//Formulaire de la liste
		$req = $this->getQueryFilter($idetat,$iduser,$idempr,$user_input,$date_debut,$date_fin, $id_theme, $id_type,$dmde_loc);
		$res = mysql_query($req,$dbh);
		
		$liste ="";
		if(mysql_num_rows($res)){
			$parity=1;						
			while(($dmde = mysql_fetch_object($res))){
				if ($parity % 2) {
					$pair_impair = "even";
				} else {
					$pair_impair = "odd";
				}
				$parity += 1;
				$tr_javascript = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
				$action = "onclick=document.location='./demandes.php?categ=gestion&act=see_dmde&iddemande=".$dmde->id_demande."'";
				$liste .= "<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer'  >";
				
				$carac_empr = $this->getCaracEmpr($dmde->num_demandeur);
				$nom_empr = $carac_empr['nom'];
				$liste .= "
					<td $action>".htmlentities($dmde->libelle_theme,ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities($dmde->libelle_type,ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities($dmde->titre_demande,ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities($this->workflow->getStateCommentById($dmde->etat_demande),ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities(formatdate($dmde->date_demande),ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities(formatdate($dmde->date_prevue),ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities(formatdate($dmde->deadline_demande),ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities($nom_empr,ENT_QUOTES,$charset)."</td>
					<td $action>".htmlentities($dmde->nom,ENT_QUOTES,$charset)."</td>
					<td><span id='progressiondemande_".$dmde->id_demande."'  dynamics='demandes,progressiondemande' dynamics_params='img/img' >
						<img src=\"./images/jauge.png\" height='15px' width=\"".$dmde->progression."%\" title='".$dmde->progression."%' />
						</span>
					</td>";
					if($dmde->num_notice)
						$liste .= "<td><a href='./catalog.php?categ=isbd&id=$dmde->num_notice'><img border='0' align='middle' src='./images/notice.gif' alt='".htmlentities($msg['demandes_see_notice'],ENT_QUOTES,$charset)."' title='".htmlentities($msg['demandes_see_notice'],ENT_QUOTES,$charset)."'></a></td>";
					else $liste .= "<td></td>";
					$liste .= "<td ><input type='checkbox' id='chk[".$dmde->id_demande."]' name='chk[]' value='".$dmde->id_demande."'></td>";
				
				$liste .= "</tr>";				
			}
			$btn_suppr = "<input type='submit' class='bouton' value='$msg[63]' onclick='this.form.act.value=\"suppr_noti\"; return verifChk(\"suppr\");'/>";

			//afficher la liste des boutons de changement d'état
			if($idetat){
				$states = $this->workflow->getStateList($idetat);
				$states_btn = $this->getDisplayStateBtn($states,1);
			}
			$affectation_btn ="";
			if($iduser==-1){
				$affectation_btn = "<input type='submit' class='bouton' name='affect_btn' id='affect_btn' onclick='this.form.act.value=\"affecter\";return verifChk();' value='".htmlentities($msg['demandes_attribution_checked'],ENT_QUOTES,$charset)."' />&nbsp;".$this->getUsersSelector();
			}
			
		} else {
			$liste .= "<tr><td>".$msg['demandes_liste_vide']."</td></tr>";
		}
		$form_liste_demande = str_replace('!!btn_etat!!',$states_btn,$form_liste_demande);
		$form_liste_demande = str_replace('!!btn_attribue!!',$affectation_btn,$form_liste_demande);
		$form_liste_demande = str_replace('!!btn_suppr!!',$btn_suppr,$form_liste_demande);
		$form_liste_demande = str_replace('!!liste_dmde!!',$liste,$form_liste_demande);
		
		
		print $form_liste_demande;
	}
	
	/*
	 * Création/Modification d'une demande
	 */
	function save(){
		
		global $dbh, $sujet, $idetat, $titre, $id_theme, $id_type;
		global $date_debut, $date_fin, $date_prevue, $idempr;
		global $iduser, $progression, $demandes_statut_notice, $pmb_type_audit;
		
		
		if($this->id_demande){
			//MODIFICATION
			$req = "update demandes set sujet_demande='".$sujet."',  
				num_demandeur='".$idempr."', 
				date_demande='".$date_debut."', 
				deadline_demande='".$date_fin."',
				date_prevue='".$date_prevue."', 
				progression='".$progression."',
				titre_demande='".$titre."',
				type_demande='".$id_type."',
				theme_demande='".$id_theme."'";
				if($idetat == 4 || $idetat == 5 ) $req .= " ,num_user_cloture='".SESSuserid."'";	
				$req .= " where id_demande='".$this->id_demande."'";
				
				mysql_query($req,$dbh);
				
				$this->titre_demande = stripslashes($titre);
				$this->sujet_demande = stripslashes($sujet);
				$this->date_demande = $date_debut;
				$this->date_prevue = $date_prevue;
				$this->deadline_demande = $date_fin;
				$this->num_user = $iduser;
				$this->progression = $progression;
				$this->num_demandeur = $idempr;
				$this->type_demande = $id_type;
				$this->theme_demande = $id_theme;
		} else {
				//CREATION de la notice associée	
				$index_wew = $titre;
				$index_sew = strip_empty_words($index_wew);
				$index_ncontenu =  strip_empty_words($sujet);					
				$req = "insert into notices set 
				tit1='".$titre."',
				n_contenu='".$sujet."',
				statut ='".$demandes_statut_notice."',
				index_sew ='".$index_sew."',
				index_wew ='".$index_wew."',
				index_n_contenu = '".$index_ncontenu."'
				";
				mysql_query($req,$dbh);
				$id_notice = mysql_insert_id();
				if($pmb_type_audit) audit::insert_creation(AUDIT_NOTICE,$id_notice);
				
				//CREATION de la demande
				$req = "insert into demandes set sujet_demande='".$sujet."', 
				etat_demande='".$idetat."', 
				num_demandeur='".$idempr."', 
				date_demande='".$date_debut."', 
				date_prevue='".$date_prevue."', 
				deadline_demande='".$date_fin."', 
				progression='".$progression."',
				titre_demande='".$titre."',
				type_demande='".$id_type."',
				theme_demande='".$id_theme."',
				num_notice='".$id_notice."'" ;
				mysql_query($req,$dbh);
		}
		
		//Affectation du libellé du thème et du type
		$this->id_demande ? $id = $this->id_demande : $id = mysql_insert_id(); 
		$req = "select libelle_theme, libelle_type from demandes d, demandes_type dy , demandes_theme dt where dt.id_theme=d.theme_demande and dy.id_type=d.type_demande and id_demande='".$id."'";
		$res = mysql_query($req,$dbh);
		$row = mysql_fetch_object($res);
		$this->type_libelle = $row->libelle_type;
		$this->theme_libelle = $row->libelle_theme;
		
		//Enregistrement dans demandes_users
		$date_creation=date("Y-m-d",time());
		if($this->id_demande && $iduser){
			$req = "update demandes_users set users_statut=0 where num_user not in (".implode(',',$iduser).") and num_demande='".$this->id_demande."'";
			mysql_query($req,$dbh);
			$req = "update demandes_users set users_statut=1 where num_user in (".implode(',',$iduser).") and num_demande='".$this->id_demande."'";
			mysql_query($req,$dbh);
			for($i=0;$i<sizeof($iduser);$i++){
				$req = "insert into demandes_users set num_user='".$iduser[$i]."', num_demande='".$this->id_demande."', date_creation='".$date_creation."', users_statut=1";
				mysql_query($req,$dbh);
			}
		} else if($iduser) {
			for($i=0;$i<sizeof($iduser);$i++){
				$req = "insert into demandes_users set num_user='".$iduser[$i]."', num_demande='".$id."', date_creation='".$date_creation."', users_statut=1";
				mysql_query($req,$dbh);
			}
		}
		$req = "select num_user, concat(prenom,' ',nom) as nom, username from demandes_users, users where num_user=userid and num_demande='".$id."' and users_statut=1";
		$res = mysql_query($req,$dbh);		
		$i=0;
		if(mysql_num_rows($res)){
			$this->users = array();
			while(($user=mysql_fetch_object($res))){
				$this->users[$i]['nom'] = (trim($user->nom)  ? $user->nom : $user->username);
				$this->users[$i]['id'] = $user->num_user;
				
				$i++;
			}		
		}
	}
	
	/*
	 * Suppression d'une demande
	 */
	function delete(){
		global $dbh,$chk, $delnoti;
		
				
		if($this->id_demande){
			
			if($delnoti){
				//Si on supprime la notice associée
				$req = "delete n from notices n 
					join demandes d on n.notice_id=d.num_notice
					where d.id_demande=$this->id_demande";
				mysql_query($req, $dbh);
			} 				
			
			$req = "delete from demandes_users where num_demande='".$this->id_demande."'";
			mysql_query($req,$dbh);
			$req = "delete ed,eda from explnum_doc ed 
			join explnum_doc_actions eda on ed.id_explnum_doc=eda.num_explnum_doc 
			join demandes_actions da on eda.num_action=da.id_action
			where da.num_demande=$this->id_demande";
			mysql_query($req, $dbh);
			$req = "delete from demandes where id_demande='".$this->id_demande."'"; 
			mysql_query($req,$dbh);
			$req = "delete from demandes_actions where num_demande='".$this->id_demande."'";
			mysql_query($req,$dbh);

		} elseif($chk){
			$chk = explode(",",$chk);
			for($i=0;$i<count($chk);$i++){

				if($delnoti){
					//Si on supprime la notice associée
					$req = "delete n from notices n 
					join demandes d on n.notice_id=d.num_notice
					where d.id_demande=$chk[$i]";
					mysql_query($req, $dbh); 
				}
				
				$req = "delete from demandes_users where num_demande='".$chk[$i]."'";
				mysql_query($req,$dbh);	
				$req = "delete ed,eda from explnum_doc ed 
					join explnum_doc_actions eda on ed.id_explnum_doc=eda.num_explnum_doc 
					join demandes_actions da on eda.num_action=da.id_action
					where da.num_demande='".$chk[$i]."'";
				mysql_query($req, $dbh);
				$req = "delete from demandes where id_demande='".$chk[$i]."'"; 
				mysql_query($req,$dbh);
				$req = "delete from demandes_actions where num_demande='".$chk[$i]."'";
				mysql_query($req,$dbh);	
				
			}
		}

	}
	

	/*
	 * Retourne le sélecteur des états de la demandes
	 */
	function getStateSelector($idetat=0,$action='',$default=false){
		global $charset, $msg;
		
		$selector = "<select name='idetat' $action>";
		$select="";
		if($default) $selector .= "<option value='0'>".htmlentities($msg['demandes_all_states'],ENT_QUOTES,$charset)."</option>";
		for($i=1;$i<=count($this->liste_etat);$i++){
			if($idetat == $i) $select = "selected";
			$selector .= "<option value='".$this->liste_etat[$i]['id']."' $select>".htmlentities($this->liste_etat[$i]['comment'],ENT_QUOTES,$charset)."</option>";
			$select = "";
		}
		$selector .= "</select>";
		
		return $selector;
	}
	
	/*
	 * Retourne le sélecteur des utilisateurs ayant le droit aux demandes
	 */
	function getUsersSelector($action='',$default=false,$multiple=false,$nonassign=false){
		global $dbh,$charset,$msg, $iduser;
		
		if($multiple)
			$mul = " name='iduser[]' multiple ";
		else $mul = " name='iduser' ";
		
		if(!$this->id_demande){
			$req="select concat(prenom,' ',nom) as nom, userid, username
			from users 
			where rights>=16384";
		} else {
			$req="select concat(prenom,' ',nom) as nom, userid , if(isnull(num_demande),0,if((users_statut),1,0)) as actif, username
			from users
			left join demandes_users on (num_user=userid and num_demande='".$this->id_demande."') 
			where rights>=16384";
		}
		 
		$res = mysql_query($req,$dbh);
		$select = "";
		$selector = "<select  $mul $action >";
		if($default) $selector .= "<option value='0'>".htmlentities($msg['demandes_all_users'],ENT_QUOTES,$charset)."</option>";
		if($nonassign) $selector .=  "<option value='-1' ".($iduser == -1 ?'selected' :'').">".htmlentities($msg['demandes_not_assigned'],ENT_QUOTES,$charset)."</option>";
		while(($user=mysql_fetch_object($res))){			
			if($user->actif) $select="selected";
			$name = (trim($user->nom) ? $user->nom :$user->username);
			if($iduser == $user->userid) $select="selected";						
			$selector .= "<option value='".$user->userid."' $select>".htmlentities($name,ENT_QUOTES,$charset)."</option>";
			$select = "";
		}
		$selector .= "</select>";
		
		return $selector;
	}
	
	/*
	 * Retourne le nom de l'utilisateur (celui qui traitera la demande)
	 */
	function getUserLib($iduser){
		global $dbh;

		$req = "select concat(prenom,' ',nom) as nom, userid, username from users where userid='".$iduser."'";
		$res = mysql_query($req,$dbh);
		$user = mysql_fetch_object($res);
		
		return ( trim($user->nom) ? $user->nom : $user->username );		
	}
	
	/*
	 * Retourne les caractéristiques de l'emprunteur qui effectue la demande
	 */
	function getCaracEmpr($idempr){
		global $dbh;

		$req = "select concat(empr_prenom,' ',empr_nom) as nom, id_empr,empr_cb from empr where id_empr='".$idempr."'";
		$res = mysql_query($req,$dbh);
		$empr = mysql_fetch_array($res);

		return $empr;		
	}
	
	
	/*
	 * Fonction qui retourne la requete de filtre
	 */
	function getQueryFilter($idetat,$iduser,$idempr,$user_input,$date_dmde,$date_but,$id_theme,$id_type,$dmde_loc){
		
		$date_deb = str_replace('-','',$date_dmde);
		$date_fin = str_replace('-','',$date_but);
		
		
		$params = array();
		
		//Filtre d'etat
		if($idetat){
			$etat = " etat_demande = '".$idetat."'";
			$params[] = $etat;
		}
		//Filtre d'utilisateur
		$join_filtre_user="";
		if($iduser){
			if($iduser == -1)
				$user = " nom is null ";
			else $user = " duf.num_user = '".(is_array($iduser) ? $iduser[0] : $iduser)."' and duf.users_statut=1";
			$join_filtre_user = "left join demandes_users duf on (duf.num_demande=d.id_demande )"; 
			$params[] = $user;
		}
		
		//Filtre de demandeur
		if($idempr){
			$empr = " num_demandeur = '".$idempr."'";	
			$params[] = $empr;
		}
		
		//Filtre de recherche
		if($user_input){
			$user_input = str_replace('*','%',$user_input);
			$saisie = " titre_demande like '%".$user_input."%'";
			$params[] = $saisie;
		}
		
		//Filtre date
		if($date_deb<$date_fin){
			$date = " (date_demande >= '".$date_dmde."' and deadline_demande <= '".$date_but."' )"; 
			$params[] = $date;		
		}
		//Filtre theme
		if($id_theme){
			$theme = " theme_demande = '".$id_theme."'";
			$params[] = $theme;
		}
		
		//Filtre type
		if($id_type){
			$type = " type_demande = '".$id_type."'";
			$params[] = $type;		
		}
		
		//Filtre localisation
		$join_loc="";
		if($dmde_loc){
			$join_loc = "left join empr on (num_demandeur=id_empr)";
			$loc =  " empr_location = '".$dmde_loc."'";
			$params[] = $loc;		
		}
		
		if($params) $clause = "where ".implode(" and ",$params);
				
		$req = "select id_demande, etat_demande, titre_demande, sujet_demande, deadline_demande, date_demande, date_prevue, 
				type_demande, theme_demande, progression, num_demandeur, num_notice, libelle_theme, libelle_type, 
				group_concat(distinct if(concat(prenom,' ',nom) !='',concat(prenom,' ',nom),username) separator '/ ') as nom
				from demandes d 
				join demandes_type dy on d.type_demande=dy.id_type
				join demandes_theme dt on d.theme_demande=dt.id_theme				
				left join demandes_users du on du.num_demande=d.id_demande
				left join users on (du.num_user=userid and du.users_statut=1)
				$join_filtre_user
				$join_loc
				$clause
				group by id_demande
				order by date_demande desc";
		return $req;
		
	}
	
	/*
	 * Affichage du formulaire de consultation d'une demande
	 */
	function show_consult_form(){
		global $form_consult_dmde, $charset, $msg, $dbh;
		
		$form_consult_dmde = str_replace('!!form_title!!',htmlentities($this->titre_demande,ENT_QUOTES,$charset),$form_consult_dmde);
		$form_consult_dmde = str_replace('!!sujet_dmde!!',htmlentities($this->sujet_demande,ENT_QUOTES,$charset),$form_consult_dmde);
		$form_consult_dmde = str_replace('!!etat_dmde!!',htmlentities($this->workflow->getStateCommentById($this->etat_demande),ENT_QUOTES,$charset),$form_consult_dmde);
		$form_consult_dmde = str_replace('!!date_dmde!!',htmlentities(formatdate($this->date_demande),ENT_QUOTES,$charset),$form_consult_dmde);
		$form_consult_dmde = str_replace('!!date_butoir_dmde!!',htmlentities(formatdate($this->deadline_demande),ENT_QUOTES,$charset),$form_consult_dmde);
		$form_consult_dmde = str_replace('!!date_prevue_dmde!!',htmlentities(formatdate($this->date_prevue),ENT_QUOTES,$charset),$form_consult_dmde);
		$form_consult_dmde = str_replace('!!progression_dmde!!',htmlentities($this->progression.'%',ENT_QUOTES,$charset),$form_consult_dmde);
		
		for($i=0;$i<sizeof($this->users);$i++){
			if($i == sizeof($this->users)-1)
				$users .= htmlentities($this->users[$i]['nom'],ENT_QUOTES,$charset);
			else $users .= htmlentities($this->users[$i]['nom'],ENT_QUOTES,$charset)." / ";	
		}
	
		$carac_empr = $this->getCaracEmpr($this->num_demandeur);
		$nom = $carac_empr['nom'];
		$cb = $carac_empr['empr_cb'];
		$nom_emprunteur ="";
		if(SESSrights & CIRCULATION_AUTH)
			$nom_emprunteur = "<a href=\"circ.php?categ=pret&form_cb=$cb\" >".htmlentities($nom,ENT_QUOTES,$charset)."</a>";
		
		$form_consult_dmde = str_replace('!!demandeur!!',($nom_emprunteur ? $nom_emprunteur :$nom),$form_consult_dmde);
		$form_consult_dmde = str_replace('!!attribution!!',$users,$form_consult_dmde);
		$form_consult_dmde = str_replace('!!iddemande!!',$this->id_demande,$form_consult_dmde);
		$form_consult_dmde = str_replace('!!theme_dmde!!',htmlentities($this->theme_libelle,ENT_QUOTES,$charset),$form_consult_dmde);
		$form_consult_dmde = str_replace('!!type_dmde!!',htmlentities($this->type_libelle,ENT_QUOTES,$charset),$form_consult_dmde);
		
		//afficher la liste des boutons de changement d'état
		if($this->etat_demande && $this->users){
			$states = $this->workflow->getStateList($this->etat_demande);
			$states_btn = $this->getDisplayStateBtn($states);		
			$form_consult_dmde = str_replace('!!btn_etat!!',$states_btn,$form_consult_dmde);
		} else {
			$form_consult_dmde = str_replace('!!btn_etat!!',"",$form_consult_dmde);
		}
		
		$notice = "<a onclick=\"show_notice('".$this->num_notice."')\" href='#'><img border='0' align='top' src='./images/search.gif' alt='".htmlentities($msg['demandes_see_notice'],ENT_QUOTES,$charset)."' title='".htmlentities($msg['demandes_see_notice'],ENT_QUOTES,$charset)."' /></a>";
		$form_consult_dmde = str_replace('!!icone!!',$notice,$form_consult_dmde);
		
		if($this->users){
			$req = "select count(1) as nb from demandes join demandes_actions on id_demande=num_demande join explnum_doc_actions on num_action=id_action where id_demande='".$this->id_demande."'";
			$res = mysql_query($req, $dbh);
			$docnum = mysql_fetch_object($res);
			if($docnum->nb){
				$btn_attach = "&nbsp;<input type='submit' class='bouton' value='".$msg['demandes_attach_docnum']."' onClick='this.form.act.value=\"attach\" ; ' />";
			} else $btn_attach = "";
			$btn_notices = "<input type='submit' class='bouton' value='".$msg['demandes_complete_notice']."' onClick='this.form.act.value=\"notice\" ; ' />".			
			$btn_attach."&nbsp;<input type='submit' class='bouton' value='".$msg['demandes_generate_rapport']."' onClick='this.form.act.value=\"rapport\" ; ' />";
			$form_consult_dmde = str_replace('!!btns_notice!!',$btn_notices,$form_consult_dmde);
		} else {
			$form_consult_dmde = str_replace('!!btns_notice!!',"",$form_consult_dmde);
		}
		
		print $form_consult_dmde;
		
		if($this->users){
			//Liste des actions
			$actions = new demandes_actions();
			$actions->show_list_actions($this->id_demande);
		}	
	}
	
	/*
	 * Affiche la liste des boutons correspondants à l'état en cours
	 */
	function getDisplayStateBtn($list_etat=array(),$multi=0){
		global $charset,$msg;
		
		if($multi){
			$message = $msg['demandes_change_checked_states'];
		} else $message = $msg['demandes_change_state'];
		$display = "<label class='etiquette'>".$message." : </label>";
		for($i=0;$i<count($list_etat);$i++){
			$display .= "&nbsp;<input class='bouton' type='submit' name='btn_".$list_etat[$i]['id']."' value='".htmlentities($list_etat[$i]['comment'],ENT_QUOTES,$charset)."' onclick='this.form.state.value=\"".$list_etat[$i]['id']."\"; this.form.act.value=\"change_state\";'/>";
		}
		
		return $display;
	}
	
	/*
	 * Changement d'etat d'une demande
	 */
	function change_state($state){
		global $chk, $dbh;
		
		if($chk){
			for($i=0;$i<count($chk);$i++){
				$req = "update demandes set etat_demande=$state where id_demande='".$chk[$i]."'";
				mysql_query($req,$dbh);
			}
		} else {
			$req = "update demandes set etat_demande=$state where id_demande='".$this->id_demande."'";
			mysql_query($req,$dbh);
			$this->etat_demande = $state;
		}
	}
	
	/*
	 * Montre la liste des documents pouvant etre inclus dans le document
	 */
	function show_docnum_to_attach(){
		
		global $dbh, $form_liste_docnum, $msg, $charset, $base_path, $pmb_indexation_docnum_default;
		
		$req="select id_explnum_doc as id, explnum_doc_nomfichier as nom, num_explnum, 
			concat(explnum_index_sew,'',explnum_index_wew) as indexer
			from explnum_doc 
			join explnum_doc_actions on (id_explnum_doc=num_explnum_doc and rapport=1)
			join demandes_actions on num_action=id_action
			left join explnum on explnum_id=num_explnum
			where num_demande='".$this->id_demande."'";
		$res = mysql_query($req,$dbh);
		$liste="";
		if(mysql_num_rows($res)){
			while(($doc = mysql_fetch_object($res))){
				if($doc->num_explnum) {
					$check = 'checked';
				}
				if($pmb_indexation_docnum_default || $doc->indexer){
					$check_index = 'checked';
				}
				$liste .= "				
				<div class='row'>
					<div class='colonne3'>
						<input type='checkbox' id='chk[$doc->id]' value='$doc->id' name='chk[]' $check /><label for='chk[$doc->id]' class='etiquette'>".htmlentities($doc->nom,ENT_QUOTES,$charset)."</label>&nbsp;
						<a href=\"$base_path/explnum_doc.php?explnumdoc_id=".$doc->id."'\" target=\"_LINK_\"><img src='$base_path/images/globe_orange.png' /></a>
					</div>
					<div class='colonne3'>	
						<input type='checkbox' id='ck_index[$doc->id]' value='$doc->id' name='ck_index[]' $check_index/><label for='ck_index[$doc->id]' class='etiquette'>".htmlentities($msg['demandes_docnum_indexer'],ENT_QUOTES,$charset)."</label>&nbsp;	
					</div>
				</div>
				<div class='row'></div>";
				$check = "";	
				$check_index = "";
			}
			$btn_attach = "<input type='submit' class='bouton' value='".$msg['demandes_attach_checked_docnum']."' onClick='this.form.act.value=\"save_attach\" ; return verifChk();' />";
			$form_liste_docnum = str_replace('!!btn_attach!!',$btn_attach,$form_liste_docnum);
		} else {
			$liste = htmlentities($msg['demandes_no_docnum'],ENT_QUOTES,$charset);
			$form_liste_docnum = str_replace('!!btn_attach!!','',$form_liste_docnum);
		}
		
		$form_liste_docnum = str_replace('!!liste_docnum!!',$liste,$form_liste_docnum);
		$form_liste_docnum = str_replace('!!iddemande!!',$this->id_demande,$form_liste_docnum);
		
		print $form_liste_docnum;
	}
	
	/*
	 * Attache les documents numériques à la notice
	 */
	function attach_docnum(){
		
		global $dbh, $chk, $ck_index, $pmb_indexation_docnum;

		for($i=0;$i<count($chk);$i++){
			//On attache les documents numériques cochés
			$req = "select explnum_doc_nomfichier as nom ,explnum_doc_mimetype as mime,explnum_doc_data as data,explnum_doc_extfichier as ext
			from explnum_doc 
			join explnum_doc_actions on num_explnum_doc=id_explnum_doc
			join demandes_actions on num_action=id_action
			where id_explnum_doc='".$chk[$i]."'
			and num_explnum = 0
			and num_demande='".$this->id_demande."'
			"; 
			$res = mysql_query($req,$dbh);
			if(mysql_num_rows($res)){
				$expl = mysql_fetch_object($res);			
				$req = "insert into explnum(explnum_notice,explnum_nom,explnum_nomfichier,explnum_mimetype,explnum_data,explnum_extfichier) values 
					('".$this->num_notice."','".addslashes($expl->nom)."','".addslashes($expl->nom)."','".addslashes($expl->mime)."','".addslashes($expl->data)."','".addslashes($expl->ext)."')";
				mysql_query($req,$dbh);
				$id_explnum = mysql_insert_id();			
				$req = "update explnum_doc_actions set num_explnum='".$id_explnum."' where num_explnum_doc='".$chk[$i]."'";
				mysql_query($req,$dbh);
				if($ck_index[$i] && $pmb_indexation_docnum){
					$expl = new explnum($id_explnum);
					$expl->indexer_docnum();
				}
			}
		}	
			//On désattache les autres
			if($chk){
				$req = "select id_explnum_doc from explnum_doc where id_explnum_doc not in ('".implode('\',\'',$chk)."')"; 
				$res = mysql_query($req,$dbh);
				while(($expl = mysql_fetch_object($res))){
					$req = "delete e from explnum e 
					join explnum_doc_actions on num_explnum=explnum_id 
					where num_explnum_doc='".$expl->id_explnum_doc."'";
					mysql_query($req,$dbh);
					$req = "update explnum_doc_actions set num_explnum='0' where num_explnum_doc='".$expl->id_explnum_doc."'";
					mysql_query($req,$dbh);
				}
			} else {
				$req ="select id_explnum_doc
					from explnum_doc 
					join explnum_doc_actions on num_explnum_doc=id_explnum_doc
					join demandes_actions on num_action=id_action
					where num_explnum != 0
					and num_demande='".$this->id_demande."'";
				$res = mysql_query($req,$dbh);
				while(($expl = mysql_fetch_object($res))){
					$req = "delete e from explnum e 
					join explnum_doc_actions on num_explnum=explnum_id 
					where num_explnum_doc='".$expl->id_explnum_doc."'";
					mysql_query($req,$dbh);
					$req = "update explnum_doc_actions set num_explnum='0' where num_explnum_doc='".$expl->id_explnum_doc."'";
					mysql_query($req,$dbh);
				}
			}
	}
	
		
	/*
	 * Affiche le formulaire de création/modification d'une notice 
	 */
	function show_notice_form(){
		
		// affichage du form de création/modification d'une notice
		$myNotice = new notice($this->num_notice);
		if(!$myNotice->id) {
			$myNotice->tit1 = $this->titre_demande;
		}
		
		$myNotice->action = "./demandes.php?categ=gestion&act=upd_notice&iddemande=".$this->id_demande."&id=";
		$myNotice->link_annul = "./demandes.php?categ=gestion&act=see_dmde&iddemande=".$this->id_demande;
		
		print $myNotice->show_form();
	}
	
	/*
	 * Formulaire de validation de la suppression de notice
	 */
	function suppr_notice_form(){
		global $msg, $chk, $iddemande;
		
		$display = "
		<form class='form-$current_module' name='suppr_noti'  method='post' action='./demandes.php?categ=list'>
			<h3>".$msg["demandes_del_notice"]."</h3>
			<div class='form-contenu'>
				<div class='row'>
					<div>
						<img src='./images/error.gif'  >
						<strong>".$msg["demandes_del_linked_notice"]."</strong>
					</div>
				</div>
			</div>
			<div></div>
			<div class='row'>
				<input type='hidden' name='delnoti' id='delnoti'>
				<input type='hidden' name='act' value='suppr'>
				<input type='hidden' name='iddemande' value='$iddemande'>";
				if($chk) 
					$display .= "<input type='hidden' name='chk' value='".implode(',',$chk)."'";
				$display .= 
					"<input type='submit' name='non_btn' class='bouton' value='$msg[39]' onclick='this.form.delnoti.value=\"0\";'>
					<input type='submit' class='bouton' name='ok_btn' value='$msg[40]' onclick='this.form.delnoti.value=\"1\";'>					
			</div>
			
		</form>		
		";
					
		print $display;
	}
	
	
	function attribuer(){
		global $chk, $iduser,$dbh;
		
		for($i=0;$i<count($chk);$i++){
			$req = "insert into demandes_users set num_user=$iduser, num_demande=$chk[$i], date_creation='".today()."', users_statut=1";
			mysql_query($req,$dbh);
		}
	}
}
?>