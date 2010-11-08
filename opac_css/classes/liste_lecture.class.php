<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_lecture.class.php,v 1.19 2010-08-19 07:35:07 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($base_path."/includes/templates/liste_lecture.tpl.php");
require_once ($include_path."/mail.inc.php") ;

class liste_lecture {
	
	var $id_liste;
	var $num_empr;
	var $login;
	var $display='';
	var $notices=array();
	var $action='';
	var $nom_liste='';
	var $description='';
	var $public=0;
	var $readonly=0;
	var $confidential=0;
	
	/**
	 * Constructeur 
	 */
	function liste_lecture($login='',$act='',$id_liste=0){
		global $dbh;
		
		$this->login = login;
		$this->num_empr = $this->get_num_empr($login);
		$this->action = $act;
		$this->id_liste = $id_liste;
		if($this->id_liste){
			$req = "select * from opac_liste_lecture where id_liste='".$this->id_liste."'";
			$res = mysql_query($req,$dbh);
			if(mysql_num_rows($res)){
				$liste = mysql_fetch_object($res);
				$this->nom_liste = $liste->nom_liste;
				$this->description=$liste->description;
				$this->public=$liste->public;
				$this->readonly=$liste->read_only;
				$this->confidential=$liste->confidential;
				if($liste->notices_associees) 
					$this->notices = explode(",",$liste->notices_associees);
				else $this->notices = array();
			} else {
				$this->nom_liste = '';
				$this->description='';
				$this->public=0;
				$this->readonly=0;
				$this->notices = array();	
				$this->confidential=0;		
			}
		} else {
			$this->nom_liste = '';
			$this->description='';
			$this->public=0;
			$this->readonly=0;
			$this->notices = array();		
			$this->confidential=0;	
		}
			
		$this->proceed();
	}
	
	function proceed(){
		
		switch($this->action){
			case 'get_acces':
				$this->obtenir_acces($this->id_liste);
				break;
			case 'suppr_acces':
				$this->supprimer_acces($this->id_liste);
				break;
			case 'suppr_list':
				$this->supprimer_liste();
				break;
			case 'suppr_ck':
				$this->supprimer_coche($this->id_liste);
				break;	
			case 'share_list':
				$this->share_liste();
				break;
			case 'unshare_list':
				$this->unshare_liste();
				break;
			case 'save':
				$this->enregistrer($this->id_liste);
				break;
			case 'suppr':
				$this->supprimer_liste($this->id_liste);
				break;
			case 'list_in':
				$this->remplir_liste($this->id_liste);
				break;
			case 'list_out':
				$this->extraire_vers_panier();
				break;	
			case 'accept_acces':
				$this->accepter_acces_confidentiel();
				break;
			case 'refus_acces':
				$this->refuser_acces_confidentiel();
				break;	
			default:
				break;	
		}
	}
	
	/**
	 * Obtenir l'accès à une liste partagée
	 */
	function obtenir_acces($id_liste=0){
		
		global $list_ck, $dbh;
		
		if($list_ck){
			for($i=0;$i<sizeof($list_ck);$i++){
				$rqt = "insert into abo_liste_lecture (num_empr,num_liste, etat) values ('".$this->num_empr."', '".$list_ck[$i]."','2')";
				@mysql_query($rqt,$dbh);
			}
		} elseif($id_liste){
			$rqt = "insert into abo_liste_lecture (num_empr,num_liste, etat) values ('".$this->num_empr."', '".$id_liste."','2')";
			@mysql_query($rqt,$dbh);
		}
	}
	
	/**
	 * Supprime l'accès à une liste partagée
	 */
	function supprimer_acces($id_liste=0){
		
		global $list_ck, $dbh;
		
		if($list_ck){
			for($i=0;$i<sizeof($list_ck);$i++){
				$rqt = "delete from abo_liste_lecture where num_empr='".$this->num_empr."' and num_liste='".$list_ck[$i]."'";
				mysql_query($rqt,$dbh);
			}
		} elseif($id_liste){
			$rqt = "delete from abo_liste_lecture where num_empr='".$this->num_empr."' and num_liste='".$id_liste."'";
			mysql_query($rqt,$dbh);
		}
	}
	
	/**
	 * Accepte l'accès aux listes confidentielles
	 */
	function accepter_acces_confidentiel(){		
		global $cb_demande, $dbh,$opac_connexion_phrase ,$pmb_opac_url, $msg;
		
		for($i=0;$i<sizeof($cb_demande);$i++){
			$info = explode('-',$cb_demande[$i]);
			$req = " update abo_liste_lecture set etat=2 where num_empr='".$info[1]."' and num_liste='".$info[0]."'";
			mysql_query($req,$dbh);
			
			$req ="select concat(empr_prenom,' ',empr_nom) as nom, empr_mail, empr_login from empr where id_empr='".$info[1]."'";
			$res = mysql_query($req,$dbh);
			$destinataire = mysql_fetch_object($res);
			$req ="select concat(empr_prenom,' ',empr_nom) as nom, empr_mail, nom_liste from empr e, opac_liste_lecture oll where oll.num_empr=e.id_empr and id_liste='".$info[0]."'";
			$res = mysql_query($req,$dbh);
			$sender= mysql_fetch_object($res);
			
			$date = time();
			$login = $destinataire->empr_login;
			$code=md5($opac_connexion_phrase.$login.$date);			
			$corps = sprintf($msg['list_lecture_intro_mail'],$destinataire->nom,$sender->nom_liste).", <br />".sprintf($msg['list_lecture_confirm_mail'],$sender->nom,$sender->nom_liste);
			$corps .= "<br /><br /><a href='".$pmb_opac_url."empr.php?code=$code&emprlogin=$login&date_conex=$date&tab=lecture&lvl=private_list&sub=shared_list' >".sprintf($msg['list_lecture_confirm_redir_mail'],$sender->nom_liste)."</a>";
			
			mailpmb($destinataire->nom,$destinataire->empr_mail,sprintf($msg['list_lecture_objet_confirm_mail'],$sender->nom_liste),stripslashes($corps),$sender->nom,$sender->empr_mail);
			
		}
	}
	
	/**
	 * Refuse l'accès aux listes confidentielles
	 */
	function refuser_acces_confidentiel(){
		global $cb_demande, $dbh, $msg, $com,$pmb_opac_url,$opac_connexion_phrase;
		
		for($i=0;$i<sizeof($cb_demande);$i++){
			$info = explode('-',$cb_demande[$i]);
			$req = " update abo_liste_lecture set etat=0 where num_empr='".$info[1]."' and num_liste='".$info[0]."'";
			mysql_query($req,$dbh);
			$req ="select concat(empr_prenom,' ',empr_nom) as nom, empr_mail, empr_login from empr where id_empr='".$info[1]."'";
			$res = mysql_query($req,$dbh);
			$destinataire = mysql_fetch_object($res);
			$req ="select concat(empr_prenom,' ',empr_nom) as nom, empr_mail, nom_liste from empr e, opac_liste_lecture oll where oll.num_empr=e.id_empr and id_liste='".$info[0]."'";
			$res = mysql_query($req,$dbh);
			$sender= mysql_fetch_object($res);
			
			$date = time();
			$login = $destinataire->empr_login;
			$code=md5($opac_connexion_phrase.$login.$date);			
			$corps = sprintf($msg['list_lecture_intro_mail'],$destinataire->nom,$sender->nom_liste).", <br />".sprintf($msg['list_lecture_refus_corps_mail'],$sender->nom,$sender->nom_liste);
			if($com) $corps .= sprintf("<br />".$msg['list_lecture_corps_com_mail'],$sender->nom," <br />".$com);
			$corps .= "<br /><br /><a href='".$pmb_opac_url."empr.php?code=$code&emprlogin=$login&date_conex=$date&tab=lecture&lvl=private_list&sub=my_list' >".$msg['redirection_mail_link']."</a>";
			
			mailpmb($destinataire->nom,$destinataire->empr_mail,sprintf($msg['list_lecture_refus_mail'],$sender->nom_liste),stripslashes($corps),$sender->nom,$sender->empr_mail);
		}
	}
	
	/**
	 * Supprime la ou les listes sélectionnée(s)
	 */
	function supprimer_liste($id_liste=0){
		
		global $list_ck, $dbh;
		
		if($list_ck){
			for($i=0;$i<sizeof($list_ck);$i++){
				$rqt = "delete from opac_liste_lecture where id_liste='".$list_ck[$i]."'";
				mysql_query($rqt,$dbh);
				$rqt = "delete from abo_liste_lecture where num_liste='".$list_ck[$i]."'";
				mysql_query($rqt,$dbh);
			}
		} elseif($id_liste) {
			$rqt = "delete from opac_liste_lecture where id_liste='".$id_liste."'";
			mysql_query($rqt,$dbh);
			$rqt = "delete from abo_liste_lecture where num_liste='".$id_liste."'";
			mysql_query($rqt,$dbh);
		}
	}
	
	/**
	 * Supprime les notices cochées de la liste
	 */
	function supprimer_coche($id_liste){
		global $notice, $dbh;
		
		for ($i=0; $i<count($notice); $i++) {
			$as=array_search($notice[$i],$this->notices);
			if (($as!==null)&&($as!==false)) {
				//Décalage
				for ($j=$as+1; $j<count($this->notices); $j++) {
					$this->notices[$j-1]=$this->notices[$j];
				}
				unset($this->notices[count($this->notices)-1]);
			}
		}
		$rqt = "update opac_liste_lecture set notices_associees='".implode(',',$this->notices)."' where id_liste='".$id_liste."'"; 
		mysql_query($rqt,$dbh);

	}
	
	/**
	 * Partager la ou les listes sélectionnée(s)
	 */
	function share_liste(){
		
		global $list_ck, $dbh;
		
		for($i=0;$i<sizeof($list_ck);$i++){
			$rqt = "update opac_liste_lecture set public=1 where num_empr='".$this->num_empr."' and id_liste='".$list_ck[$i]."' ";
			mysql_query($rqt,$dbh);
		}
	}
	
	/**
	 * Ne plus partager la ou les listes sélectionnée(s)
	 */
	function unshare_liste(){
		
		global $list_ck, $dbh;
		
		for($i=0;$i<sizeof($list_ck);$i++){
			$rqt = "update opac_liste_lecture set public=0 where num_empr='".$this->num_empr."' and id_liste='".$list_ck[$i]."'";
			mysql_query($rqt,$dbh);
		}
	}
	
		
	/**
	 * récupération de l'id selon le login
	 */
	function get_num_empr($login){
		if($login){
			$rqt = "select id_empr from empr where empr_login='".addslashes($login)."'";
			$res = mysql_query($rqt);
			return mysql_result($res,0,0);
		}
		
		return 0;		
	}
	
	/**
	 * Enregistre une liste de lecture 
	 */
	function enregistrer($id_liste=0){
		global $dbh, $list_name, $list_comment, $notice_filtre, $cb_share, $cb_readonly, $cb_confidential;
		
		if(!$id_liste){
			$rqt="insert into opac_liste_lecture (notices_associees,description, public, num_empr, nom_liste, read_only, confidential) 
				values ('".$notice_filtre."', '".$list_comment."','".($cb_share ? 1 : 0)."', '".$this->num_empr."', '".$list_name."', '".($cb_readonly ? 1 : 0)."', '".($cb_confidential ? 1 : 0)."')";
			mysql_query($rqt,$dbh);
		} elseif($id_liste) {
			$rqt="update opac_liste_lecture set notices_associees='".$notice_filtre."', description='".$list_comment."', public='".($cb_share ? 1 : 0)."', 
				nom_liste='".$list_name."', read_only='".($cb_readonly ? 1 : 0)."', confidential='".($cb_confidential ? 1 : 0)."' where id_liste='".$id_liste."'";
			mysql_query($rqt,$dbh);
		}
	}
	
	/**
	 * Remplir la liste de lecture avec le panier
	 */
	function remplir_liste($id_liste=0){
				
		$notices = $this->notices;
		$cart = array();		
		for($i=0;$i<sizeof($_SESSION['cart']);$i++){
			if(array_search($_SESSION['cart'][$i],$notices) === false)
				$cart[] = $_SESSION['cart'][$i];
		}
		
		$notice_liste = array_merge($notices,$cart);
		
		$rqt = "update opac_liste_lecture set notices_associees='".implode(',',$notice_liste)."' where id_liste='".$id_liste."'";
		mysql_query($rqt);
		
		$this->notices = $notice_liste;
	}
	
	/**
	 * Extraire la liste dans le panier
	 */
	function extraire_vers_panier(){
		$cart = array();		
		$notices = $this->notices;
		for($i=0;$i<sizeof($notices);$i++){
			if(array_search($notices[$i],$_SESSION['cart']) === false)
				$cart[] = $notices[$i];
		}
		
		$notice_liste = array_merge($_SESSION['cart'],$cart);
		
		$_SESSION['cart'] = $notice_liste;
	}
	
	
	/****************************************************
	 * 													*
	 *			  Fonctions d'affichage		 			* 			
	 * 													*		
	 ****************************************************/
	
	/**
	 * Génère le formulaire pour les listes de l'utilisateur 
	 */
	function generate_mylist(){
		
		global $liste_lecture_prive, $charset, $msg, $opac_url_base;
		
		if ($this->num_empr){
			$rqt="select id_liste, nom_liste, description, public, read_only, confidential from opac_liste_lecture where num_empr='".$this->num_empr."' order by nom_liste";
			$res = mysql_query($rqt);
			$affichage_liste = "
			<form name='my_list' method='post' action='empr.php' >	
				<input type='hidden' id='lvl' name='lvl' />
				<input type='hidden' id='act' name='act' />
				<div id='list_cadre' style='border: 1px solid rgb(204, 204, 204); overflow: auto; height: 200px;padding:2px;'>";
			
			if(mysql_num_rows($res) == 0){
				//si l'on a aucune liste de lecture crée
				$affichage_liste .= "
					<div class='row'>
						<label>".$msg['list_lecture_no_mylist']."</label>
					</div>
				</div></form>";
				$liste_lecture_prive = str_replace('!!current_shared!!','',$liste_lecture_prive);
				$liste_lecture_prive = str_replace('!!my_current!!','current',$liste_lecture_prive);
				$liste_lecture_prive = str_replace('!!listes!!',$affichage_liste,$liste_lecture_prive);				
				$this->display=$liste_lecture_prive;
			}
			while(($liste=mysql_fetch_object($res))){
				$div_description = "";
				$div_action = "";
				if($liste->description){
					$div_description = "<div id='desc$liste->id_liste' class='listedescription'>$liste->description</div>"; 
					$div_action = " onmouseout=\"document.getElementById('desc$liste->id_liste').style.visibility='hidden';\" onmouseover=\"document.getElementById('desc$liste->id_liste').style.visibility='visible';\"";
				}
				$affichage_liste .= "
				<div id='liste_$liste->id_liste'>
					<input type='checkbox' class='checkbox' id='cb$liste->id_liste' name='list_ck[]' value='$liste->id_liste'/>
						<span>
							<a href='./index.php?lvl=show_list&sub=view&id_liste=$liste->id_liste' $div_action>".htmlentities($liste->nom_liste,ENT_QUOTES,$charset)."</a>
						</span>
						$div_description";
				if($liste->public) 
					$affichage_liste .= " 
					&nbsp;<span>
							<img border=0 align='middle' src='".$opac_url_base."images/group.png' title=\"".$msg['list_lecture_partagee']."\"/>
						</span>";	
				if($liste->read_only)
					$affichage_liste .= " 
					&nbsp;<span>
							<img border=0 align='middle' src='".$opac_url_base."images/b_no_edit.png' title=\"".$msg['list_lecture_readonly']."\"/>
						</span>";	
				if($liste->confidential)
					$affichage_liste .= " 
					&nbsp;<span>
							<img border=0 align='middle' src='".$opac_url_base."images/lock.png' title=\"".$msg['list_lecture_confidential']."\"/>
						</span>";		
				$affichage_liste .= "</div>";
			}
			$affichage_liste .= "</div><br />						
				<div class='row'>
					<input type='submit' class='bouton' name='share_btn' value=\"$msg[list_lecture_share]\" onclick='this.form.lvl.value=\"private_list\"; this.form.act.value=\"share_list\";'/>
					<input type='submit' class='bouton' name='unshare_btn' value=\"$msg[list_lecture_unshare]\" onclick='this.form.lvl.value=\"private_list\"; this.form.act.value=\"unshare_list\";'/>
					<input type='submit' class='bouton' name='suppr_btn' value=\"$msg[list_lecture_suppr]\" onclick='this.form.lvl.value=\"private_list\"; if(confirm_delete())this.form.act.value=\"suppr_list\";'/>
				</div>
			</form>";
			
			$liste_lecture_prive = str_replace('!!current_shared!!','',$liste_lecture_prive);
			$liste_lecture_prive = str_replace('!!my_current!!','current',$liste_lecture_prive);
			$liste_lecture_prive = str_replace('!!listes!!',$affichage_liste,$liste_lecture_prive);
		} 
		
		$this->display = $liste_lecture_prive;
		
	}
	
	/**
	 * Génère le formulaire pour les listes partagées 
	 */
	function generate_sharedlist(){
		
		global $liste_lecture_prive, $msg, $charset;
		
		$rqt="select id_liste, nom_liste, description, empr_login, empr_nom, empr_prenom 
				from opac_liste_lecture op, empr e, abo_liste_lecture abo 
				where e.id_empr=op.num_empr 
				and num_liste=id_liste 
				and abo.num_empr='".$this->num_empr."' 
				and abo.etat=2
				order by nom_liste";
		$res = mysql_query($rqt) ;
		$affichage_liste .= "<form name='myshared_list' method='post' action='empr.php' >	
				<input type='hidden' id='lvl' name='lvl' />
				<input type='hidden' id='sub' name='sub' value='shared_list' />
				<input type='hidden' id='act' name='act' />
				<div id='list_cadre' style='border: 1px solid rgb(204, 204, 204); overflow: auto; height: 200px;padding:2px;'>";
		
		if(mysql_num_rows($res) == 0){
			//Si l'on a aucune liste partagée dispo
			$affichage_liste .= "<div class='row'><label>".$msg['list_lecture_no_myshared']."</label></div></div></form>";
			$liste_lecture_prive = str_replace('!!current_shared!!','current',$liste_lecture_prive);
			$liste_lecture_prive = str_replace('!!my_current!!','',$liste_lecture_prive);
			$liste_lecture_prive = str_replace('!!listes!!',$affichage_liste,$liste_lecture_prive);		
			$this->display = $liste_lecture_prive;
			return;
		}
		while(($liste=mysql_fetch_object($res))){
			$div_description = "";
			$div_action = "";
			if($liste->description){
				$div_description = "<div id='desc$liste->id_liste' class='listedescription'>$liste->description</div>"; 
				$div_action = " onmouseout=\"document.getElementById('desc$liste->id_liste').style.visibility='hidden';\" onmouseover=\"document.getElementById('desc$liste->id_liste').style.visibility='visible';\"";
			}
			$affichage_liste .= "
			<div id='liste_$liste->id_liste'>
					<input type='checkbox' class='checkbox' id='cb$liste->id_liste' name='list_ck[]' value='$liste->id_liste'/>				
			";
			$titre_liste = " ( $liste->empr_prenom $liste->empr_nom ) ";	
			$affichage_liste .=	"&nbsp;<span><a $div_action href='./index.php?lvl=show_list&sub=consultation&id_liste=$liste->id_liste'>".htmlentities($liste->nom_liste,ENT_QUOTES,$charset)."</a><label for='cb$liste->id_liste' >".htmlentities($titre_liste,ENT_QUOTES,$charset)."</label></span>$div_description";
			
			$affichage_liste .= "</div>";
		}
		
		$affichage_liste .= "</div><br />						
				<div class='row'>
					<input type='submit' class='bouton' name='unshare_btn' value=\"$msg[list_lecture_quit_acces]\" onclick='this.form.lvl.value=\"private_list\"; this.form.act.value=\"suppr_acces\";'/>
				</div>
			</form>";
		
		$liste_lecture_prive = str_replace('!!current_shared!!','current',$liste_lecture_prive);
		$liste_lecture_prive = str_replace('!!my_current!!','',$liste_lecture_prive);
		$liste_lecture_prive = str_replace('!!listes!!',$affichage_liste,$liste_lecture_prive);
			
		$this->display = $liste_lecture_prive;
	}
	
	/**
	 * Génère le formulaire pour les listes publiques 
	 */
	function generate_publiclist(){
		
		global $liste_lecture_public, $charset, $msg, $opac_url_base;
		$rqt="select id_liste, nom_liste, description, confidential, read_only, empr_login, empr_nom, empr_prenom, if(etat is null,0,etat) as etat
				from opac_liste_lecture op
				left join empr e on e.id_empr=op.num_empr 
				left join abo_liste_lecture abo on (abo.num_liste=id_liste and abo.num_empr='".$this->num_empr."')
				where public=1 
				and op.num_empr !='".$this->num_empr."' 
				order by nom_liste";
		$res = mysql_query($rqt) ;
		if(mysql_num_rows($res) == 0){
			//Si on a aucune liste partagée dispo
			$affichage_liste .= "<div class='row'><label>".$msg['list_lecture_no_publiclist']."</label></div></form>";		
			$liste_lecture_public = str_replace('!!inscrire_btn!!','',$liste_lecture_public);
			$liste_lecture_public = str_replace('!!desinscrire_btn!!','',$liste_lecture_public);
			$liste_lecture_public = str_replace('!!public_list!!',$affichage_liste,$liste_lecture_public);
			$this->display = $liste_lecture_public;
			return;
		}
		$affichage_liste .= "<script src='./includes/javascript/liste_lecture.js' type='text/javascript'></script>
				<script src='./includes/javascript/http_request.js' type='text/javascript'></script>";
		while(($liste=mysql_fetch_object($res))){
			$font='';
			$font_end='';
			$check='';
			$nblistes = array();
			if($liste->etat == 2) {
				$font = "<font color=\"green\">";
				$font_end = "</font>";
				$check = 'checked';	
			}
			//Ajout de script pour la gestion de la confidentialité et l'ajax			
			$confidential = false;
			$icone="";
			$disable="";
			$ajax="";
			$title="";
			if($liste->confidential && !$liste->etat){				
				$ajax= "onclick=\"make_mail_form('".$liste->id_liste."')\" style=\"cursor:pointer\"";
				$disable='disabled';
				$icone ="lock.png";
				$title =$msg['list_lecture_confidential'];
				$confidential = true;
			} elseif($liste->confidential && $liste->etat==1){
				$ajax= "onclick=\"demandeEnCours();\"";
				$disable='disabled';
				$icone ="hourglass.png";
				$title = $msg['list_lecture_encours_demande'];
				$confidential = true;
			} elseif($liste->confidential){
				$icone ="lock_open.png"; 
				$title = $msg['list_lecture_accessible'];
			} 
			if($liste->description){		
				$div_description = "<div id='desc$liste->id_liste' class='listedescription'>$liste->description</div>"; 
				$div_action = " onmouseout=\"document.getElementById('desc$liste->id_liste').style.visibility='hidden';\" onmouseover=\"document.getElementById('desc$liste->id_liste').style.visibility='visible';\"";
			}
			$affichage_liste .= "<div id='liste_$liste->id_liste' $ajax>
				<input type='checkbox' class='checkbox' id='cb$liste->id_liste' name='list_ck[]' value='$liste->id_liste' $check $disable />";
				$titre_liste = " ( $liste->empr_prenom $liste->empr_nom  )";
			if($liste->read_only){
				$img_ro = "&nbsp;<span><img border=0 align='top' src='".$opac_url_base."images/b_no_edit.png' title=\"".$msg['list_lecture_readonly']."\" id='img_ro_$liste->id_liste' /></span>";
			} else $img_ro = "";	
			if($confidential)
				$affichage_liste .= "&nbsp;<span><a $div_action href='#' onclick='return false;'>".htmlentities($liste->nom_liste,ENT_QUOTES,$charset)."</a>$font<label for='cb$liste->id_liste' >".htmlentities($titre_liste,ENT_QUOTES,$charset)."</label>$font_end</span>$div_description";
			else 
				$affichage_liste .=	"&nbsp;<span><a $div_action href='./index.php?lvl=show_list&sub=consultation&id_liste=$liste->id_liste'>".htmlentities($liste->nom_liste,ENT_QUOTES,$charset)."</a>$font<label for='cb$liste->id_liste' >".htmlentities($titre_liste,ENT_QUOTES,$charset)." </label>$font_end</span>$div_description";
			
			if($icone) $affichage_liste .= "<span><img border=0 align='top' src='".$opac_url_base."images/$icone' title=\"$title\" id='img_confi_$liste->id_liste' /></span>";
			$affichage_liste .= $img_ro;
			$affichage_liste .= "</div>";
			$affichage_liste .= "<div id='maillist_$liste->id_liste'></div>";
			
			$nblistes[] = $liste->id_liste;
		}
		$btn_insc = "<input type='submit' class='bouton' name='acces_btn' value=\"$msg[list_lecture_acces]\" onclick='this.form.lvl.value=\"public_list\"; this.form.act.value=\"get_acces\";' />";
		$btn_desins = "<input type='submit' class='bouton' name='no_acces_btn' value=\"$msg[list_lecture_quit_acces]\" onclick='this.form.lvl.value=\"public_list\"; this.form.act.value=\"suppr_acces\";' />";
		
		$liste_lecture_public = str_replace('!!inscrire_btn!!',$btn_insc,$liste_lecture_public);
		$liste_lecture_public = str_replace('!!desinscrire_btn!!',$btn_desins,$liste_lecture_public);
		$liste_lecture_public = str_replace('!!public_list!!',$affichage_liste,$liste_lecture_public);		
		
		 
		$this->display = $liste_lecture_public;
	}
	
	/*
	 * Fonction qui génère la liste des demandes
	 */
	function generate_demandes(){
		global $dbh,$msg, $liste_demande, $emprlogin;
		
		$req = "select id_liste,nom_liste, id_empr, empr_nom, empr_prenom 
		from opac_liste_lecture oll, abo_liste_lecture abo, empr 
		where oll.id_liste=abo.num_liste 
		and abo.num_empr=id_empr
		and oll.num_empr='".($this->num_empr ? $this->num_empr : $this->get_num_empr($emprlogin))."'
		and oll.confidential=1
		and etat=1
		order by nom_liste";
		$res=mysql_query($req,$dbh);
		if(!mysql_num_rows($res)){		
			$affichage_liste .= "<div class='row'><label>".$msg['list_lecture_no_demande']."</label></div></form>";	
			$liste_demande =  str_replace("!!accepter_btn!!",'',$liste_demande);
			$liste_demande =  str_replace("!!refuser_btn!!",'',$liste_demande);
			$liste_demande =  str_replace("!!demande_list!!",$affichage_liste,$liste_demande);
			$this->display = $liste_demande;
			return;
		} 
		
		$noms_listes = array();
		$aff_liste = "<script src='./includes/javascript/liste_lecture.js' type='text/javascript'></script>
				<script src='./includes/javascript/http_request.js' type='text/javascript'></script>";
		$aff_liste .= "<ul>";
		while(($liste = mysql_fetch_object($res))){			
			if(!$noms_listes[$liste->nom_liste]) {
				$aff_liste .= "<li><u>".$liste->nom_liste."</u></li>";
				$noms_listes[$liste->nom_liste] = $liste->nom_liste;
			}
			$aff_liste .= "<blockquote><div class='row'><input type='checkbox' name='cb_demande[]' value=\"".$liste->id_liste."-".$liste->id_empr."\"><label>".$liste->empr_prenom.' '.$liste->empr_nom."</label></div></blockquote>";
		}		
		$aff_liste .= "</ul>";
		$accept_btn = "<input type='submit' class='bouton' id='accept' name='accept' value=\"$msg[list_lecture_accept_demande]\" onclick='this.form.lvl.value=\"demande_list\"; this.form.act.value=\"accept_acces\";'/>";
		$refus_btn = "<input type='button' class='bouton' id='refus' name='refus' value=\"$msg[list_lecture_refus_demande]\"  onclick='make_refus_form(); '/>";
		$liste_demande =  str_replace("!!accepter_btn!!",$accept_btn,$liste_demande);
		$liste_demande =  str_replace("!!refuser_btn!!",$refus_btn,$liste_demande);
		$liste_demande =  str_replace("!!demande_list!!",$aff_liste,$liste_demande);
	
		
		$this->display = $liste_demande;	
		
	}
	
	/**
	 * Génère le formulaire de gestion d'une liste 
	 */
	function affichage_saveform($notice_asso=array()){
		
		global $liste_gestion, $dbh, $charset, $msg, $opac_search_results_per_page, $cart_aff_case_traitement, $page, $opac_shared_lists_readonly, $opac_show_suggest,$opac_allow_multiple_sugg;
		
		$affich='';
		
		if(!$this->id_liste){
			for($i=0;$i<sizeof($notice_asso);$i++){
				if (substr($notice_asso[$i],0,2)!="es") 
						$affich.= aff_notice($notice_asso[$i],1); 
				else $affich.=aff_notice_unimarc(substr($notice_asso[$i],2),1);
			}
			$liste_gestion = str_replace('!!titre_liste!!',htmlentities($msg['list_lecture_create'],ENT_QUOTES,$charset),$liste_gestion);
			$liste_gestion = str_replace('!!notice_filtre!!',htmlentities(implode(',',$notice_asso),ENT_QUOTES,$charset),$liste_gestion);
			$liste_gestion = str_replace('!!name_list!!','',$liste_gestion);
			$liste_gestion = str_replace('!!list_comment!!','',$liste_gestion);
			if($opac_shared_lists_readonly)
				$liste_gestion = str_replace('!!checked_only!!','checked',$liste_gestion);			
			else $liste_gestion = str_replace('!!checked_only!!','',$liste_gestion);
			$liste_gestion = str_replace('!!disabled_conf!!','disabled',$liste_gestion);
			$liste_gestion = str_replace('!!checked_conf!!','',$liste_gestion);
			$liste_gestion = str_replace('!!color_conf!!','gray',$liste_gestion);
			$liste_gestion = str_replace('!!checked!!','',$liste_gestion);
			$liste_gestion = str_replace('!!id_liste!!','',$liste_gestion);		
			$liste_gestion = str_replace('!!liste_btn!!','',$liste_gestion);		
			$liste_gestion = str_replace('!!print_btn!!','',$liste_gestion);	
			$liste_gestion = str_replace('!!inscrit_list!!','',$liste_gestion);	
		} else {
				$liste_noti = array();
				$print_btn="<input type='button' class='bouton' name='mail' 
					onclick=\"w=window.open('print.php?lvl=list&id_liste=$this->id_liste','print_window','width=500, height=750,scrollbars=yes,resizable=1'); w.focus();\" value='".$msg['list_lecture_mail']."' />";

				$liste_gestion = str_replace('!!titre_liste!!',htmlentities($msg['list_lecture_modify'],ENT_QUOTES,$charset),$liste_gestion);
				$liste_gestion = str_replace('!!name_list!!',htmlentities($this->nom_liste,ENT_QUOTES,$charset),$liste_gestion);
				$liste_gestion = str_replace('!!list_comment!!',htmlentities($this->description,ENT_QUOTES,$charset),$liste_gestion);
				$liste_gestion = str_replace('!!id_liste!!',$this->id_liste,$liste_gestion);
				$liste_gestion = str_replace('!!print_btn!!',$print_btn,$liste_gestion);	
				if($this->notices) $liste_noti = $this->notices;
				
				//Gestion de la liste des notices et de la pagination
				if($page=="") $page=1;
				$affich .= "<span><b>".sprintf($msg["show_cart_n_notices"],count($liste_noti))."</b></span>";
				$affich.= "<blockquote>";
				// case à cocher de suppression transférée dans la classe notice_affichage				
				$cart_aff_case_traitement = 1 ; 
				$affich.= "<form action='./index.php?lvl=show_list&sub=view&id_liste=$this->id_liste&page=$page' method='post' name='list_form'>\n";
				for ($i=(($page-1)*$opac_search_results_per_page); (($i<count($liste_noti))&&($i<($page*$opac_search_results_per_page))); $i++) {
					if (substr($liste_noti[$i],0,2)!="es") 
						$affich.= aff_notice($liste_noti[$i],1); 
					else $affich.=aff_notice_unimarc(substr($liste_noti[$i],2),1);
				}
				$affich.= "</form>";
				$affich.= "</blockquote>";
				$affich.= $this->aff_navigation_notices($liste_noti, $this->id_liste, 'view');
				
				//Gestion des checkbox
				if($this->public) {
					$liste_gestion = str_replace('!!checked!!','checked',$liste_gestion);
					if($this->confidential){
						$liste_gestion = str_replace('!!checked_conf!!','checked',$liste_gestion);						
					} else {
						$liste_gestion = str_replace('!!checked_conf!!','',$liste_gestion);	
					}
					$liste_gestion = str_replace('!!disabled_conf!!','',$liste_gestion);
					$liste_gestion = str_replace('!!color_conf!!','black',$liste_gestion);								
				} else {					
					$liste_gestion = str_replace('!!checked!!','',$liste_gestion);
					$liste_gestion = str_replace('!!checked_conf!!','',$liste_gestion);
					$liste_gestion = str_replace('!!disabled_conf!!','disabled',$liste_gestion);
					$liste_gestion = str_replace('!!color_conf!!','gray',$liste_gestion);
				}				
				if($this->readonly) 
					$liste_gestion = str_replace('!!checked_only!!','checked',$liste_gestion);
				else $liste_gestion = str_replace('!!checked_only!!','',$liste_gestion);
				$liste_gestion = str_replace('!!notice_filtre!!', htmlentities(implode(',',$liste_noti),ENT_QUOTES,$charset),$liste_gestion);
				
				//Gestion de la liste d'inscrit
				$list_inscrit = "<div class='row'>
							<label class='etiquette'>$msg[list_lecture_inscrits] &nbsp;</label>
						</div>	
						<br />
						<div style='height:150px ; overflow:auto ; border:1px solid #CCCCCC' id='inscrit_list'>
							!!list_inscrit!!
						</div>	";
				$req = "select id_empr, trim(concat(empr_prenom,' ',empr_nom)) as nom, confidential 
				from empr e, abo_liste_lecture abo, opac_liste_lecture oll 
				where abo.num_empr=e.id_empr and oll.id_liste=abo.num_liste 
				and etat=2 and num_liste='".$this->id_liste."'
				order by nom";
				$res=mysql_query($req,$dbh);
				if(!mysql_num_rows($res)){
					$aff_empr = $msg[list_lecture_no_user_inscrit];
				}
				$aff_empr .= "<script src='./includes/javascript/liste_lecture.js' type='text/javascript'></script>
				<script src='./includes/javascript/http_request.js' type='text/javascript'></script>";
				while(($empr=mysql_fetch_object($res))){
					if($empr->confidential) $aff_empr .= "<img border=0 align='top' src='".$opac_url_base."images/cross.png'  onclick=\"delete_from_liste('".$this->id_liste."','".$empr->id_empr."');\">";
					$aff_empr .= $empr->nom."<br />";
				}
				$list_inscrit = str_replace('!!list_inscrit!!',$aff_empr,$list_inscrit);
				$liste_gestion = str_replace('!!inscrit_list!!',$list_inscrit,$liste_gestion);
		}
		
		$liste_gestion = str_replace('!!liste_notice!!',$affich,$liste_gestion);
		print $liste_gestion;
	}
	
	/**
	 * Consultation d'une liste statique
	 */
	function consulter_liste(){
		
		global $liste_lecture_consultation, $dbh, $charset, $msg, $opac_search_results_per_page, $page;
		
		$rqt="select id_liste, nom_liste, description, read_only, empr_nom, empr_prenom, notices_associees, public, if(abo.num_empr is null,0,1) as abo 
			from opac_liste_lecture op
			left join empr e on op.num_empr=e.id_empr 
            left join abo_liste_lecture abo on (num_liste=id_liste and abo.num_empr='".$this->num_empr."')
		 	where id_liste='".$this->id_liste."'
		 	";
		$res = mysql_query($rqt,$dbh);
		$liste_noti = array();
		while(($liste = mysql_fetch_object($res))){
			$liste_lecture_consultation = str_replace('!!nom_liste!!',sprintf($msg['list_lecture_view'],htmlentities($liste->nom_liste,ENT_QUOTES,$charset)),$liste_lecture_consultation);
			$liste_lecture_consultation = str_replace('!!liste_comment!!',htmlentities($liste->description,ENT_QUOTES,$charset),$liste_lecture_consultation);
			$liste_lecture_consultation = str_replace('!!id_liste!!',$this->id_liste,$liste_lecture_consultation);
			
			$proprio = "(".sprintf($msg[list_lecture_owner],$liste->empr_prenom." ".$liste->empr_nom).")";
			$liste_lecture_consultation = str_replace('!!proprio!!',$proprio,$liste_lecture_consultation);
			if($liste->notices_associees) $liste_noti = explode(',',$liste->notices_associees);
			
			$abo_btn = "<input type='submit' class='bouton' name='abo' onclick='this.form.act.value=\"get_acces\";this.form.action=\"empr.php?tab=lecture&lvl=public_list\";' value=\"".$msg['list_lecture_abo']."\" />";
			$desabo_btn = "<input type='submit'  class='bouton' name='desabo' onclick='this.form.act.value=\"suppr_acces\";this.form.action=\"empr.php?tab=lecture&lvl=public_list\";' value=\"".$msg['list_lecture_desabo']."\" />";
			if(!$liste->read_only) 
				$add_noti_btn = "<input type='submit' class='bouton' name='list_in' onclick='this.form.act.value=\"list_in\";' value='".$msg['list_lecture_list_in']."' />";
			else $add_noti_btn ='';
			if($liste->abo){
				$liste_lecture_consultation = str_replace('!!abo_btn!!',$desabo_btn,$liste_lecture_consultation);
				$liste_lecture_consultation = str_replace('!!add_noti_btn!!',$add_noti_btn,$liste_lecture_consultation);
			}else{
				$liste_lecture_consultation = str_replace('!!abo_btn!!',$abo_btn,$liste_lecture_consultation);
				$liste_lecture_consultation = str_replace('!!add_noti_btn!!','',$liste_lecture_consultation);
			}
			
			//Gestion de la liste des notices et de la pagination
			if($page=="")$page=1;
			$affich .= "<span><b>".sprintf($msg["show_cart_n_notices"],count($liste_noti))."</b></span>";
			$affich.= "<blockquote>";
			// case à cocher de suppression transférée dans la classe notice_affichage				
			$affich.= "<form action='./index.php?lvl=show_list&sub=view&id_liste=$this->id_liste&page=$page' method='post' name='list_form'>\n";
			for ($i=(($page-1)*$opac_search_results_per_page); (($i<count($liste_noti))&&($i<($page*$opac_search_results_per_page))); $i++) {
				if (substr($liste_noti,0,2)!="es") 
					$affich.= aff_notice($liste_noti[$i],1); 
				else 
					$affich.=aff_notice_unimarc(substr($liste_noti[$i],2),1);
			}
			$affich.= "</form>";
			$affich.= "</blockquote>";
			$affich.= $this->aff_navigation_notices($liste_noti, $this->id_liste, 'consultation');
		}
		$liste_lecture_consultation = str_replace('!!notice_filtre!!', htmlentities(explode(',',$liste_noti),ENT_QUOTES,$charset),$liste_lecture_consultation);
		$liste_lecture_consultation = str_replace('!!liste_notice!!',$affich,$liste_lecture_consultation);
		
		print $liste_lecture_consultation;
	}
	
	/**
	 * Affiche la barre de navigation des notices
	 */
	function aff_navigation_notices($notices=array(),$id_liste, $sub){
		global $opac_search_results_per_page, $msg, $page;
		
		$affichage ='';
		$nbepages = ceil(count($notices)/$opac_search_results_per_page);
		$suivante = $page+1;
		$precedente = $page-1;
	
		// affichage du lien précédent si nécéssaire
		$affichage .= "<hr /><table border='0' summary='navigation bar' align='center'><tr>";
	
		// affichage du lien pour retour au début
		if($precedente > 1) {
			$affichage .= "<td width=\"14\" align=\"center\"><a href=\"index.php?lvl=show_list&sub=$sub&id_liste=$id_liste&page=1\"><img src=\"./images/first.gif\"";
			$affichage .= " border=\"0\" alt=\"$msg[start]\"";
			$affichage .= " title=\"$msg[first_page]\"></a></td>";
		} else {
			$affichage .= "<td width=\"14\" align=\"center\"><img src=\"./images/first-grey.gif\">";
		}
	
		if($precedente > 0) {
			$affichage .= "<td width=\"14\" align=\"center\"><a href=\"index.php?lvl=show_list&sub=$sub&id_liste=$id_liste&page=$precedente\"><img src=\"./images/prev.gif\"";
			$affichage .= " border=\"0\" alt=\"$msg[prec]\"";
			$affichage .= " title=\"$msg[prec]\"></a></td>";
		} else {
			$affichage .= "<td width=\"14\" align=\"center\"><img src=\"./images/prev-grey.gif\">";
		}
	
		$affichage .= "<td align='center'>$msg[page] $page/$nbepages</td>";
	
		// lien suivant
		if($suivante<=$nbepages) {
			$affichage .= "<td width=\"14\" align=\"center\"><a href=\"index.php?lvl=show_list&sub=$sub&id_liste=$id_liste&page=$suivante\"><img src=\"./images/next.gif\"";
			$affichage .= " border=\"0\" alt=\"$msg[next]\"";
			$affichage .= " title=\"$msg[next]\"></a></td>";
		} else {
			$affichage .= "<td width=\"14\" align=\"center\"><img src=\"./images/next-grey.gif\">";
		}
	
		// affichage du lien vers la fin
		if($suivante < $nbepages) {
			$affichage .= "<td width=\"14\" align=\"center\"><a href=\"index.php?lvl=show_list&sub=$sub&id_liste=$id_liste&page=$nbepages\"><img src=\"./images/last.gif\"";
			$affichage .= " border=\"0\" alt=\"$msg[end]\"";
			$affichage .= " title=\"$msg[end]\"></a></td>";
		} else {
			$affichage .= "<td width=\"14\" align=\"center\"><img src=\"./images/last-grey.gif\">";
		}
	
		$affichage .= "</tr></table><br />";
		
		return $affichage;
	}
	
}
?>