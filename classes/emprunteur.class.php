<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: emprunteur.class.php,v 1.116 2010-12-02 17:02:11 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$include_path/resa_func.inc.php");
require_once("$include_path/resa_planning_func.inc.php");
require_once($class_path."/comptes.class.php");
require_once($class_path."/amende.class.php");
require_once("$class_path/parametres_perso.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/docs_location.class.php");
require_once("$include_path/misc.inc.php");
require_once("$class_path/expl.class.php");

$selector_prop_ajout_caddie_empr = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";

// classe emprunteur
class emprunteur {

//---------------------------------------------------------
//            Propriétés
//---------------------------------------------------------

var $id		= 0		;    // id MySQL emprunteur
var $cb     = ''    ;    // code barre emprunteur
var $nom    = ''    ;    // nom emprunteur
var $prenom = ''    ;    // prénom emprunteur
var $adr1   = ''    ;    // adresse ligne 1
var $adr2   = ''    ;    // adresse ligne 2
var $cp     = ''    ;    // code postal
var $ville  = ''    ;    // ville
var $pays   = ''    ;    // pays
var $mail   = ''    ;    // adresse email
var $tel1   = ''    ;    // téléphone 1
var $sms   = ''    ;    // sms activation
var $tel2   = ''    ;    // téléphone 2
var $prof   = ''    ;    // profession
var $birth  = ''    ;    // année de naissance
var $categ  = 0    	;    // catégorie emprunteur
var $cat_l  = ''    ;    // libellé catégorie emprunteur
var $cstat  = 0    	;    // code statistique
var $cstat_l= 0     ;    // libellé code statistique
var $cdate  = ''    ;    // date de création
var $mdate  = ''    ;    // date de modification
var $adate  = ''    ;    // date d'abonnement
var $rdate  = ''    ;    // date de réabonnement
var $sexe   = 0    	;    // sexe de l'emprunteur
var $login  = ''    ;    // login pour services OPAC
var $pwd    = ''    ;    // mot de passe OPAC
var $ldap   = ''   	;  	 // flag pour AuthLdap
var $date_adhesion   = '';    // début adhésion
var $date_expiration = '';    // fin adhésion
var $aff_date_adhesion   = '';    // début adhésion formatée
var $aff_date_expiration = '';    // fin adhésion formatée
var $empr_msg     = ''    ;    // Message emprunteur
var $prets        ;    // array contenant les prêts de l'emprunteur
// var $reservations    ;    // array contenant les réservations pour l'emprunteur
// supprimé par ER le 29/12 : ne semble jamais utilisé
var $nb_reservations ;
var $message = ''    ;    // chaîne contenant les messages emprunteurs
var $fiche = ''        ;    // code HTML de la fiche lecteur
var $fiche_affichage = ''        ;    // code HTML de la fiche lecteur, lecture seule, allégée, pas de bouton
var $lien_nom_prenom = '' ; 		// NOM, Prénom avec lien vers ficher lecteur
var $img_ajout_empr_caddie = '' ;	// Icône ajout panier si activé.
var $serious_message=FALSE;    // niveau du message (sérieux si TRUE)
var $retard = 0        ;     // le lecteur a-t-il du retard
var $perso = ""        ;     // Champs personalisés
var $header_format = "" ;	// Champs personnalisés en entête
var $compte = ""		;	//Comptes financiers
var $fiche_compte="";	// code HTML d'un compte
var $fiche_consultation=""; 	// code HTML d'un compte en visu
var $type_abt=0;	//Type d'abonnement
var $groupes = array(); //Groupes de l'emprunteur
var $empr_location = 0; //Localisation de l'emprunteur
var $empr_location_l = ""; //Localisation de l'emprunteur
var $date_blocage=""; //Date de fin de blocage du lecteur
var $blocage_active=false; //Le blocage est-il actif ?

var $empr_statut=1; // Statut de l'emprunteur
var $empr_statut_libelle=""; // Statut de l'emprunteur

var $allow_loan=1;     // Pret autorisé
var $allow_book=1;     // Reservation autorisée
var $allow_opac=1;     // OPAC autorisé
var $allow_dsi=1;      // DSI autorisée
var $allow_dsi_priv=1; // DSI privée autorisée
var $allow_sugg=1;     // Suggestions autorisées
var $allow_prol=1;     // Demande de prolongation autorisée

var $blocage_abt=0;			//Le compte est bloqué à cause de l'abonnement négatif
var $compte_abt=0;			//Montant dû en abonnements
var $blocage_tarifs=0;		//Le compte est bloqué à cause d'un dû de prets payants
var $compte_tarifs=0;		//Montant dû en prets payants
var $blocage_amendes=0;		//Le compte est bloqué à cause d'amendes en cours ou dûes
var $compte_amendes=0;		//Montant dû en amendes
var $amendes_en_cours=0;	//Montant des amendes en cours
var $blocage_retard=0;		//Blocage du compte pour retard
var $nb_amendes=0;			//Nombre d'exempalires avec amende en cours
var $nb_pret=0;			//Nombre de prêts
var $total_loans=0; // Nb total de ses emprunts
var $type_fiche=0;  // Type de fiche
var $last_loan_date='';//Date du dernier emprunt
var $empr_lang="fr_FR";//Langue de l'emprunteur
var $niveau_relance=0;//niveau de relance dans lequel se trouve l'emprunteur
var $fiche_retard = "";

// <----------------- constructeur ------------------>
function emprunteur ($id=0, $message='', $niveau_message=FALSE, $type_fiche=0) {

	// initialisation des proprétés si l'id est défini
	if($id) {
		$this->id = $id;
		$this->type_fiche = $type_fiche;
		$this->serious_message = $niveau_message;
		$this->prets = array();
		$this->nb_pret =0;
		$this->nb_reservations = 0;
		$this->fetch_info();
		if ($type_fiche>0) $this->fetch_info_suite();
		$this->message = $message;
		if ($type_fiche==1) $this->do_fiche();
		elseif ($type_fiche==2) $this->do_fiche_affichage() ;
		elseif ($type_fiche==3) $this->do_fiche_consultation() ;
	}
}

//   renseignement des propriétés avec requête MySQL
function fetch_info() {

	global $dbh;
	global $msg;
	global $charset;
	global $val_list_empr;
	global $pmb_gestion_financiere, $pmb_gestion_abonnement,$pmb_gestion_tarif_prets,$pmb_gestion_amende,$empr_header_format;
	global $deflt_docs_location ;
	
	if(!$this->id || !$dbh)
		return FALSE;

	$requete = "SELECT e.*, c.libelle AS code1, s.libelle AS code2, es.statut_libelle AS empr_statut_libelle, allow_loan, allow_book, allow_opac, allow_dsi, allow_dsi_priv, allow_sugg, allow_prol, d.location_libelle as localisation, date_format(empr_date_adhesion, '".$msg["format_date"]."') as aff_empr_date_adhesion, date_format(empr_date_expiration, '".$msg["format_date"]."') as aff_empr_date_expiration FROM empr e left join docs_location as d on e.empr_location=d.idlocation, empr_categ c, empr_codestat s, empr_statut es ";
	$requete .= " WHERE e.id_empr='".$this->id."' " ;
	$requete .= " AND c.id_categ_empr=e.empr_categ";
	$requete .= " AND s.idcode=e.empr_codestat";
	$requete .= " AND es.idstatut=e.empr_statut";
	$requete .= " LIMIT 1";
	$result = mysql_query($requete, $dbh) or die (mysql_error()." ".$requete) ;
	if(!mysql_num_rows($result))
		return FALSE;

	$empr = mysql_fetch_object($result);

	// affectation des propriétés
	$this->cb        = $empr->empr_cb           ;    // code barre emprunteur
	$this->nom       = $empr->empr_nom          ;    // nom emprunteur
	$this->prenom    = $empr->empr_prenom       ;    // prénom mprunteur
	$this->adr1      = $empr->empr_adr1         ;    // adresse ligne 1
	$this->adr2      = $empr->empr_adr2         ;    // adresse ligne 2
	$this->cp        = $empr->empr_cp           ;    // code postal
	$this->ville     = $empr->empr_ville        ;    // ville
	$this->pays      = $empr->empr_pays         ;    // ville
	$this->mail      = $empr->empr_mail         ;    // adresse email
	$this->tel1      = $empr->empr_tel1         ;    // téléphone 1
	$this->sms       = $empr->empr_sms         ;    // sms activation
	$this->tel2      = $empr->empr_tel2         ;    // téléphone 2
	$this->prof      = $empr->empr_prof         ;    // profession
	$this->birth     = $empr->empr_year         ;    // année de naissance
	$this->categ     = $empr->empr_categ        ;    // catégorie emprunteur
	$this->cstat     = $empr->empr_codestat     ;    // code statistique
	$this->cdate     = $empr->empr_creation     ;    // date de création
	$this->mdate     = $empr->empr_modif        ;    // date de modification
	$this->sexe      = $empr->empr_sexe         ;    // sexe de l'emprunteur
	$this->login     = $empr->empr_login        ;    // login pour services OPAC
	$this->pwd       = $empr->empr_password     ;    // mot de passe OPAC
	$this->type_abt	 = $empr->type_abt;				 // type d'abonnement
	$this->empr_location	 = $empr->empr_location; // localisation
	$this->empr_location_l	 = $empr->localisation; // localisation
	$this->date_blocage= $empr->date_fin_blocage; // Date de fin de blocage de l'emprunteur
	$this->empr_statut= $empr->empr_statut;
	$this->empr_statut_libelle= $empr->empr_statut_libelle;
	$this->total_loans= $empr->total_loans;
	$this->allow_loan        =$empr->allow_loan;   
	$this->allow_book        =$empr->allow_book;   
	$this->allow_opac        =$empr->allow_opac;   
	$this->allow_dsi         =$empr->allow_dsi;    
	$this->allow_dsi_priv    =$empr->allow_dsi_priv;
	$this->allow_sugg        =$empr->allow_sugg;    
	$this->allow_prol        =$empr->allow_prol;    
	
	global $selector_prop_ajout_caddie_empr, $empr_show_caddie ;
	if ($empr_show_caddie) {
		$this->img_ajout_empr_caddie = "<img src='./images/basket_empr.gif' align='middle' alt='basket' title=\"${msg[400]}\" ";
		$this->img_ajout_empr_caddie .= "onClick=\"openPopUp('./cart.php?object_type=EMPR&item=".$this->id."', 'cart', 600, 700, -2, -2, '$selector_prop_ajout_caddie_empr');\">";
	} else 
		$this->img_ajout_empr_caddie="";
	
	$this->lien_nom_prenom="<a href='./circ.php?categ=pret&form_cb=".rawurlencode($this->cb)."'>$this->nom,&nbsp;$this->prenom</a>";
	
	
	$date_blocage=array();
	$date_blocage=explode("-",$this->date_blocage);
	if (mktime(0,0,0,$date_blocage[1],$date_blocage[2],$date_blocage[0])>time()) {
		$this->blocage_active=true;
	}
	
	//Groupes
	$requete="select id_groupe, libelle_groupe from groupe, empr_groupe where empr_id='".$this->id."' and id_groupe=groupe_id";
	$result=mysql_query($requete);
	if (mysql_num_rows($result)) {
		while ($grp_temp=mysql_fetch_object($result)) {
			$this->groupes[] = "<a href='./circ.php?categ=groups&action=showgroup&groupID=".$grp_temp->id_groupe."'>".htmlentities($grp_temp->libelle_groupe,ENT_QUOTES,$charset)."</a>";
		}
	} else 
		$this->groupes=array();

	if ($empr->empr_ldap){
		$this->ldap='LDAP';    // flag AuthLdap
	} else {
		$this->ldap='MYSQL';
	}

	$this->date_adhesion     	= $empr->empr_date_adhesion        ;    // début adhésion
	$this->date_expiration     	= $empr->empr_date_expiration      ;    // fin adhésion
	$this->aff_date_adhesion    = $empr->aff_empr_date_adhesion    ;    // début adhésion
	$this->aff_date_expiration	= $empr->aff_empr_date_expiration  ;    // fin adhésion
	$this->empr_msg     		= $empr->empr_msg            ;    // message emprunteur
	$this->cat_l        		= $empr->code1               ;    // libellé catégorie emprunteur
	$this->cstat_l      		= $empr->code2               ;    // libellé code statistique. voir ce bug avec Eric

	//Paramètres perso
	//Liste des champs
	$p_perso = new parametres_perso("empr");
	$perso_ = $p_perso->show_fields($this->id);

	$perso="";
	$header_format="";
	$class="colonne3";
	$c=0;
	if (count($perso_["FIELDS"])) {
		for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
			$p=$perso_["FIELDS"][$i];
			if ($empr_header_format) {
				$s=explode(",",$empr_header_format);
				if (array_search($p["ID"],$s)!==FALSE) 
					$header_format.=$p["TITRE"].$p["AFF"]."&nbsp;";
			}
			$perso.="<div class='$class'>";
			$perso.="<div class='row'>".$p["TITRE"];
			$perso.=$p["AFF"]."</div>";
			$perso.="</div>";
			if ($c==0) {
				$c=1;
			} else {
				if ($c==1) {
					$class="colonne_suite"; 
					$c=2; 
				} else {
					if ($c==2) {
						$class="colonne3"; 
						$c=0; 
					}
				}
			}
		}

		$reste=2-$c;
		if ($c!=0) {
			for ($i=0; $i<$reste; $i++) {
				$perso.="<div class='colonne3'>&nbsp;</div>";
				$c++;
			}
			$perso.="<div class='colonne_suite'>&nbsp;</div>";
		}
	}
	$this->header_format=$header_format;
	$this->perso=$perso;

}
	
	
// histoire de ne pas aller chercher tout le reste
function fetch_info_suite() {

	global $dbh;
	global $msg;
	global $charset;
	global $val_list_empr;
	global $pmb_gestion_financiere, $pmb_gestion_abonnement,$pmb_gestion_tarif_prets,$pmb_gestion_amende,$empr_header_format;
	global $deflt_docs_location ;
	
	//Comptes si gestion financiere
	$compte="";
	$n_c=0;
	$neg="<span class='erreur'>%s</span>";
	$pos="%s";
	if ($pmb_gestion_financiere) {
		$compte.="<div class='row'><hr /></div><div class='row'>";
		if ($pmb_gestion_abonnement) {
			$cpt_id=comptes::get_compte_id_from_empr($this->id,1);
			$cpt=new comptes($cpt_id);
			$solde=$cpt->update_solde();
			$novalid=$cpt->summarize_transactions("","",0,0);
			if ($cpt_id) {
				$compte.="<div class='colonne3'><div><strong><a href='./circ.php?categ=pret&sub=compte&id=".$this->id."&typ_compte=1'>".$msg["finance_solde_abt"]."</a></strong> ".comptes::format($solde)."</div>";
				if ($novalid) 
					$compte.="<div>".$msg["finance_not_validated"]." : ".comptes::format($novalid)."</div>";
				
				$compte.="</div>";
			}
			$n_c++;
		}
		if ($pmb_gestion_tarif_prets) {
			$cpt_id=comptes::get_compte_id_from_empr($this->id,3);
			$cpt=new comptes($cpt_id);
			$solde=$cpt->update_solde();
			$novalid=$cpt->summarize_transactions("","",0,0);
			if ($cpt_id) {
				$compte.="<div class='colonne3'><div><strong><a href='./circ.php?categ=pret&sub=compte&id=".$this->id."&typ_compte=3'>".$msg["finance_solde_pret"]."</a></strong> ".comptes::format($solde)."</div>";
				if ($novalid) 
					$compte.="<div>".$msg["finance_not_validated"]." : ".comptes::format($novalid)."</div>";
				$compte.="</div>";
			}
			$n_c++;
		}
		if ($pmb_gestion_amende) {
			$cpt_id=comptes::get_compte_id_from_empr($this->id,2);
			$cpt=new comptes($cpt_id);
			$solde=$cpt->update_solde();
			$novalid=$cpt->summarize_transactions("","",0,0);
			if ($cpt_id) {
				//Calcul des amendes
				$amende=new amende($this->id);
				$total_amende=$amende->get_total_amendes();
				$this->nb_amendes=$amende->nb_amendes;
				$compte.="<div class='colonne3'><div><strong><a href='./circ.php?categ=pret&sub=compte&id=".$this->id."&typ_compte=2'>".$msg["finance_solde_amende"]."</a></strong> ".comptes::format($solde)."</div>";
				if ($novalid) 
					$compte.="<div>".$msg["finance_not_validated"]." : ".comptes::format($novalid)."</div>";
				if ($total_amende) 
					$compte.="<div> ".$msg["finance_pret_amende_en_cours"]." : ".comptes::format($total_amende)."</div>";
				$compte.="</div>";
			}
			$n_c++;
		}
		if ($n_c<2) { 
			for ($i=$n_c; $i<3; $i++) 
				$compte.="<div class='colonne3'>&nbsp;</div>";
		}
		$compte.="</div><div class='row'></div>";
	}

	$this->compte=$compte;	
	$this->relance.= $this->do_tablo_relance();
	// ces propriétés sont absentes de la table emprunteurs pour le moment
	//    $this->adate    = $empr->empr_???    ;    // date d'abonnement
	//    $this->rdate    = $empr->empr_???    ;    // date de réabonnement
	if($this->message) 
		$this->message = $empr->message.'<hr />'.$this->message;
	else 
		$this->message = $empr->message;

	// Gestion de limoitation de la visualisation de la liste de pret.
	// traitement de l'ordre d'affichage en mode visualisation limitée		
	global $pmb_pret_aff_limitation;	
	global $see_all_pret;
	global $pmb_pret_aff_nombre;	
	$order = " order by p.pret_retour, p.pret_date, e.expl_cb";
	if(	$pmb_pret_aff_limitation==1) {
		if (!$see_all_pret) {
			// le bouton 'Voir tous les prets' n'a pas été posté
			$order = " order by p.pret_date desc, e.expl_cb limit ".$pmb_pret_aff_nombre;
		}	
	}		
	$requete_nb_pret = "select count(1) as nb_pret  from pret where pret_idempr=".$this->id;		
	$result_nb_pret = mysql_query($requete_nb_pret, $dbh);
	$r_nb_pret = mysql_fetch_object($result_nb_pret);
	$this->nb_pret = $r_nb_pret->nb_pret ;
	
	// récupération du tableau des exemplaires empruntés
	// il nous faut : code barre exemplaire, titre/auteur, type doc, date de prï¿½t, date de retour
	$requete = "select e.expl_cb, e.expl_id, e.expl_notice, docs_location.location_libelle, docs_section.section_libelle, e.expl_bulletin, p.pret_date, p.pret_retour, t.tdoc_libelle, date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, if (pret_retour< CURDATE(),1 ,0 ) as retard , date_format(retour_initial, '".$msg["format_date"]."') as aff_retour_initial, cpt_prolongation";
	$requete .= " from pret p, exemplaires e, docs_type t, docs_location, docs_section";
	$requete .= " where p.pret_idempr=".$this->id;
	$requete .= " and p.pret_idexpl=e.expl_id";
	$requete .= " and e.expl_section=docs_section.idsection";
	$requete .= " and e.expl_location=docs_location.idlocation";
	$requete .= " and t.idtyp_doc=e.expl_typdoc";
	$requete .= " ".$order;

	$result = mysql_query($requete, $dbh);
	$this->retard=0;
	while($pret = mysql_fetch_object($result)) {
		if ($pret->expl_notice) {
			$notice = new mono_display($pret->expl_notice, 0);
			$this->prets[] = array(
					'cb' => $pret->expl_cb,
					'id' => $pret->expl_id,
					'libelle' => $notice->header,
					'typdoc' => $pret->tdoc_libelle,
					'section' => $pret->section_libelle,
					'location' => $pret->location_libelle,
					'date_pret' => $pret->aff_pret_date,
					'date_retour' => $pret->aff_pret_retour,
					'sql_date_retour' => $pret->pret_retour,
					'org_ret_date' => str_replace('-', '', $pret->pret_retour),
					'pret_retard' => $pret->retard,	
					'retour_initial' => $pret->aff_retour_initial,		
					'cpt_prolongation' => $pret->cpt_prolongation		
					);
		}
		if ($pret->expl_bulletin) {
			$bulletin = new bulletinage_display($pret->expl_bulletin);
			$this->prets[] = array(
					'cb' => $pret->expl_cb,
					'id' => $pret->expl_id,
					'libelle' => $bulletin->display,
					'typdoc' => $pret->tdoc_libelle,
					'section' => $pret->section_libelle,
					'location' => $pret->location_libelle,
					'date_pret' => $pret->aff_pret_date,
					'date_retour' => $pret->aff_pret_retour,
					'sql_date_retour' => $pret->pret_retour,
					'org_ret_date' => str_replace('-', '', $pret->pret_retour),
					'pret_retard' => $pret->retard,			
					'retour_initial' => $pret->aff_retour_initial,		
					'cpt_prolongation' => $pret->cpt_prolongation									
					);
		}
		$this->retard = $this->retard+$pret->retard;
		

	}
	$requete_resa = "select count(1) as nb_reservations ";
	$requete_resa .= " from resa ";
	$requete_resa .= " where resa_idempr=".$this->id;

	$result_resa = mysql_query($requete_resa, $dbh);
	$resa = mysql_fetch_object($result_resa);
	$this->nb_reservations = $resa->nb_reservations ;
	return TRUE;

}

// fabrication de la fiche lecteur
function do_fiche() {

	global $empr_tmpl, $empr_pret_allowed;
	global $msg,$charset;
	global $groupID;
	global $biblio_email;
	global $pmb_lecteurs_localises ;
	global $pmb_gestion_abonnement,$pmb_gestion_financiere, $pmb_gestion_tarif_prets, $pmb_gestion_amende;
	global $finance_blocage_abt,$finance_blocage_amende,$finance_blocage_pret,$pmb_blocage_retard,$pmb_blocage_retard_force;
	global $force_finance;
	global $pmb_resa_planning;
	global $dbh;
	global $pmb_blocage_retard,$pmb_blocage_coef,$pmb_blocage_max,$pmb_blocage_delai;
	global $empr_fiche_depliee;
	
	//global $cb_inpret;
	global $alert_sound_list; // l'utilisateur veut-il les sons d'alerte

	$this->fiche = $empr_tmpl;	
	$this->fiche = str_replace('!!cb!!'        , $this->cb    , $this->fiche);
	$this->fiche = str_replace('!!nom!!'    , pmb_strtoupper($this->nom)    , $this->fiche);
	$this->fiche = str_replace('!!prenom!!'    , $this->prenom    , $this->fiche);
	$this->fiche = str_replace('!!image_caddie_empr!!', $this->img_ajout_empr_caddie, $this->fiche);
	$this->fiche = str_replace('!!info_nb_pret!!'    , sizeof($this->prets)    , $this->fiche);
	$this->fiche = str_replace('!!info_nb_resa!!'    , $this->nb_reservations    , $this->fiche);
	$this->fiche = str_replace('!!info_authldap!!'    , $this->ldap, $this->fiche);
	$this->fiche = str_replace('!!id!!'        , $this->id    , $this->fiche);
	$this->fiche = str_replace('!!adr1!!'    , $this->adr1    , $this->fiche);
	$this->fiche = str_replace('!!adr2!!'    , $this->adr2    , $this->fiche);
	$this->fiche = str_replace('!!tel1!!'    , $this->tel1    , $this->fiche);
	$this->fiche = str_replace('!!sms!!'    , $this->sms    , $this->fiche);
	$this->fiche = str_replace('!!tel2!!'    , $this->tel2    , $this->fiche);
	$this->fiche = str_replace('!!cp!!'        , $this->cp    , $this->fiche);
	$this->fiche = str_replace('!!ville!!'    , $this->ville    , $this->fiche);
	$this->fiche = str_replace('!!pays!!'    , $this->pays    , $this->fiche);
	
	$emails=array();
	$email_final=array();
	$emails = explode(';',$this->mail);
	for ($i=0;$i<count($emails);$i++) 
		$email_final[] ="<a href='mailto:".$emails[$i]."'>".$emails[$i]."</a>";
	
	$this->fiche = str_replace('!!mail_all!!'    , implode("&nbsp;",$email_final)    , $this->fiche);
	$this->fiche = str_replace('!!prof!!'    , $this->prof    , $this->fiche);
	$this->fiche = str_replace('!!date!!'    , $this->birth    , $this->fiche);
	$this->fiche = str_replace('!!categ!!'    , $this->cat_l    , $this->fiche);
	$this->fiche = str_replace('!!codestat!!'    , $this->cstat_l, $this->fiche);
	$this->fiche = str_replace('!!adhesion!!'    , $this->aff_date_adhesion, $this->fiche);
	$this->fiche = str_replace('!!expiration!!'    , $this->aff_date_expiration, $this->fiche);
	$this->fiche = str_replace('!!perso!!'    , $this->perso, $this->fiche);
	$this->fiche = str_replace('!!header_format!!'    , $this->header_format, $this->fiche);
	$this->fiche = str_replace('!!empr_login!!'    , $this->login, $this->fiche);
	if ($this->pwd) 
		$this->fiche = str_replace('!!empr_pwd!!',"<i><strong>".$msg["empr_pwd_opac_affected"]."</strong</i>",$this->fiche);
	else 
		$this->fiche = str_replace('!!empr_pwd!!',"",$this->fiche);
	$this->fiche = str_replace('!!comptes!!'    , $this->compte, $this->fiche);
	$this->fiche = str_replace('!!empr_statut_libelle!!', $this->empr_statut_libelle, $this->fiche);
	$this->fiche = str_replace('!!empr_picture!!', $this->picture_empr($this->cb), $this->fiche);
	if ($empr_fiche_depliee=="1") 
		$this->fiche = str_replace('!!depliee!!'," startOpen=\"Yes\"", $this->fiche);
	else 
		$this->fiche = str_replace('!!depliee!!',"", $this->fiche);
	
	if ($pmb_lecteurs_localises) {
		$this->fiche = str_replace("<!-- !!localisation!! -->", "<div class='row'><strong>$msg[empr_location] : </strong>".$this->empr_location_l."</div>", $this->fiche);
		$resume_localisation=$this->empr_location_l;
	}	
	//Groupes
	if (count($this->groupes)) {
		$this->fiche = str_replace('!!groupes!!',"<strong>".$msg[groupes_empr]." : </strong>".implode(" / ",$this->groupes)."\n",$this->fiche);
		$resume_groupe=implode(" / ",$this->groupes);
	} else {
		$this->fiche = str_replace('!!groupes!!',"&nbsp;",$this->fiche);
	}
	
	// Ajout d'infos complémentaires lorsque la fiche lecteur est repliée par défaut
	$empr_resume='';
	if ($empr_fiche_depliee=="0") { 
		//localisation
		if($resume_localisation) $empr_resume=$resume_localisation." - ";
		//categ
		if($this->cat_l) $empr_resume.=$this->cat_l." - ";
		//groupe
		if($resume_groupe) $empr_resume.=$resume_groupe." - ";
	}	
	$this->fiche = str_replace('!!empr_resume!!',$empr_resume,$this->fiche);
	
	//Pret autorisé ou non ?
	$pret_ok=0;
	if (($pmb_gestion_financiere)&&($force_finance==0)) {
		$message_pret="";
		if ($pmb_gestion_abonnement) {
			//Vérification du compte
			$cpte_abt_id=comptes::get_compte_id_from_empr($this->id,1);
			if ($cpte_abt_id) {
				$cpte_abt=new comptes($cpte_abt_id);
				$solde_neg=$cpte_abt->get_solde();
				if (($finance_blocage_abt)&&($solde_neg*1<0)) {
					if ($pret_ok<2) $pret_ok=$finance_blocage_abt;
					$message_pret.=sprintf($msg["finance_pret_solde_abt"],comptes::format($solde_neg))."<br />";
					$this->blocage_abt=$finance_blocage_abt;
				}
				if ($solde_neg*1<0) $this->compte_abt=abs($solde_neg);
			}
		}
		
		if ($pmb_gestion_tarif_prets) {
			//Vérification du compte
			$cpte_pret_id=comptes::get_compte_id_from_empr($this->id,3);
			if ($cpte_pret_id) {
				$cpte_pret=new comptes($cpte_pret_id);
				$solde_neg=$cpte_pret->get_solde();
				if (($finance_blocage_pret)&&($solde_neg*1<0)) {
					if ($pret_ok<2) 
						$pret_ok=$finance_blocage_pret;
					$message_pret.=sprintf($msg["finance_pret_solde_pret"],comptes::format($solde_neg))."<br />";
					$this->blocage_tarifs=$finance_blocage_pret;
				}
				if ($solde_neg*1<0) $this->compte_tarifs=abs($solde_neg);
			}
		}
		
		if ($pmb_gestion_amende) {
			//Vérification du compte
			$cpte_amende_id=comptes::get_compte_id_from_empr($this->id,2);
			if ($cpte_amende_id) {
				$cpte_amende=new comptes($cpte_amende_id);
				$solde_neg=$cpte_amende->get_solde();
				$amende=new amende($this->id);
				$amende_neg=$amende->get_total_amendes();
				if (($finance_blocage_amende)&&(($solde_neg*1<0)||($amende_neg*1))) {
					$this->blocage_amendes=$finance_blocage_amende;
					if ($pret_ok<2) $pret_ok=$finance_blocage_amende;
					if ($solde_neg*1<0)
						$message_pret.=sprintf($msg["finance_pret_solde_amende"],comptes::format($solde_neg))."<br />";
					if ($amende_neg*1)
						$message_pret.=sprintf($msg["finance_pret_amende_en_cours_blocage"],comptes::format($amende_neg))."<br />";
				}
				if ($solde_neg*1<0) $this->compte_amendes=abs($solde_neg);
				if ($amende_neg*1)  $this->amendes_en_cours=abs($amende_neg);
			}
		}
	}
	if (($pmb_blocage_retard)&&($force_finance==0)) {
			if (($this->date_blocage)&&($this->blocage_active)) {
				$this->blocage_retard=$pmb_blocage_retard_force;
				$message_pret.=sprintf($msg["blocage_retard_pret"],formatdate($this->date_blocage))."&nbsp;<input type='button' value='".$msg["blocage_params"]."' class='bouton' onClick=\"openPopUp('./circ/blocage.php?id_empr=".$this->id."','blocage_params',400,200,-2,-2,'toolbar=no, dependent=yes,resizable=yes');\"/><br />";
				if ($pret_ok<2) $pret_ok=$pmb_blocage_retard_force;
			}
	}

	
	// Ajout de l'impossibilité d'effectuer un prêt si un document n'est pas rendu
	// alors qu'il a dépassé le délai de blocage (NG72) .   
	//if ($pmb_blocage_retard){
	if (($pmb_blocage_retard)&&($force_finance==0)) {
		// Recherche la date de retour du document la plus petite, soit le plus gros retard potentiel

		$requete = "select MIN(pret_retour)as pret_retour";
		$requete .= " from pret p";
		$requete .= " where p.pret_idempr=".$this->id;
		$result = mysql_query($requete, $dbh);
		
		while($bloca = mysql_fetch_object($result)) {
			if ($bloca->pret_retour){
				$pret_retour=$bloca->pret_retour;
						
				$date_debut=explode("-",$pret_retour);
				$ndays=calendar::get_open_days($date_debut[2],$date_debut[1],$date_debut[0],date("d"),date("m"),date("Y"));
			
				if ($ndays>$pmb_blocage_delai) {
					$ndays=$ndays*$pmb_blocage_coef;
						if (($ndays>$pmb_blocage_max)&&($pmb_blocage_max!=0)) {
							$ndays=$pmb_blocage_max;
						}
				} else $ndays=0;
			
				if ($ndays) {
					// Interdire alors de nouveau prï¿½t
					if ($pret_ok<2) $pret_ok=$pmb_blocage_retard_force;
					$this->blocage_retard=$pmb_blocage_retard_force;
				}
			}
		}
	}
	if (!$pret_ok && $this->allow_loan) {
		$this->fiche = str_replace("!!empr_case_pret!!", $empr_pret_allowed,$this->fiche);
		$this->fiche = str_replace('!!id!!'        , $this->id    , $this->fiche);
	} else {
		if ($pret_ok==1 && $this->allow_loan) {
			$message_pret.="<input type='button' class='bouton' value=\"".$msg["finance_pret_force_pret"]."\" onClick=\"this.form.force_finance.value=1; this.form.submit();\">";
		} elseif($this->allow_loan) {
			$message_pret.="<div class='erreur'>".$msg["finance_pret_bloque"]."</div>";
		} else $message_pret.="<div class='erreur'>".$msg["empr_no_allow_loan"]."</div>";
		$this->fiche = str_replace("!!empr_case_pret!!", $message_pret,$this->fiche);
	}
	$abonnement="";
	if (($pmb_gestion_financiere)&&($pmb_gestion_abonnement==2)) {
		if ($this->type_abt) {
			$requete="select type_abt_libelle from type_abts where id_type_abt='".$this->type_abt."'";
			$resultat_type_abt=mysql_query($requete);
			if (@mysql_num_rows($resultat_type_abt)) {
				$abonnement=mysql_result($resultat_type_abt,0,0);
			}
		}
	}
	
	if ($abonnement) {
		$this->fiche = str_replace("!!abonnement!!", "<div class='row'><strong>".$msg["finance_type_abt"]." : </strong>".htmlentities($abonnement,ENT_QUOTES,$charset)."</div>\n",$this->fiche);
	} else {
		$this->fiche = str_replace("!!abonnement!!","",$this->fiche);
	}
	
	// message
	if ($this->empr_msg) {
		$message_fiche_empr= "
				<hr />
				<div class='row'>
					<div class='colonne10'><img src='./images/info.png' /></div>
					<div class='colonne-suite'><span class='erreur'>$this->empr_msg</span></div>
					</div><br />";
		$alert_sound_list[]="information";
		$this->fiche = str_replace('!!empr_msg!!'    ,$message_fiche_empr , $this->fiche);
	} else 
		$this->fiche = str_replace('!!empr_msg!!', "", $this->fiche);
	
	// on distingue les messages de prêts du message sur l'emprunteur
	$this->fiche = str_replace('!!pret_msg!!'    , $this->message    , $this->fiche);

	if ($this->adhesion_renouv_proche()) {
		$message_date_depassee = $msg[empr_date_renouv_proche];
	} elseif ($this->adhesion_depassee()) {
			$message_date_depassee = $msg[empr_date_depassee];
		} else {
			$message_date_depassee="";
		}
	if ($message_date_depassee) $alert_sound_list[]="critique";
	$this->fiche = str_replace('!!empr_date_depassee!!', $message_date_depassee, $this->fiche);

	$group_zone = "<a href='./circ.php'>$msg[64]</a>";
	if($groupID)
		$group_zone .= "&nbsp;&nbsp;&nbsp;<a href='./circ.php?categ=groups&action=showgroup&groupID=$groupID'>$msg[grp_autre_lecteur]</a>" ;

	$this->fiche = str_replace('!!group_zone!!', $group_zone, $this->fiche);

	$fsexe[0] = $msg[128];
	$fsexe[1] = $msg[126];
	$fsexe[2] = $msg[127];

	$this->fiche = str_replace('!!sexe!!'    , $fsexe[$this->sexe], $this->fiche);
	
	// valeur pour les champ hidden du prêt. L'id empr est pris en charge plus haut (voir Eric)
	$this->fiche = str_replace('!!cb!!'    , $this->cb    , $this->fiche);

	// traitement liste exemplaires en prêt
	$this->fiche = str_replace('!!nb_prets_encours!!'    , $this->nb_pret    , $this->fiche);

	//Si retard sur un document, proposer la lettre de retard ou l'email de retard
	if ($this->retard>=1) {
		$imprime_click = "onclick=\"openPopUp('./pdf.php?pdfdoc=lettre_retard&id_empr=".$this->id."', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) \"";
		$bouton_lettre_retard=$msg['retard']."&nbsp;<input type=\"button\" class=\"bouton\" value=\"".$msg["lettre_retard"]."\" ".$imprime_click.">";
		if (($this->mail)&&($biblio_email)) {
			$mail_click = "onclick=\"if (confirm('".$msg["mail_retard_confirm"]."')) { openPopUp('./mail.php?type_mail=mail_retard&id_empr=".$this->id."', 'mail', 600, 500, -2,- 2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');} return(false) \"";
			$bouton_mail_retard="<input type=\"button\" class=\"bouton\" value=\"".$msg["mail_retard"]."\" ".$mail_click.">";
			} else {
				$bouton_mail_retard="";
			}
		} else {
			$bouton_lettre_retard="";
			$bouton_mail_retard="";
		}
	$this->fiche=str_replace("!!lettre_retard!!",$bouton_lettre_retard,$this->fiche);
	$this->fiche=str_replace("!!mail_retard!!",$bouton_mail_retard,$this->fiche);
	$voir_tout_pret="";
	if(!count($this->prets))
		// dans ce cas, le lecteur n'a rien en prêt
		$prets_list = "<tr><td colspan='9'>$msg[650]</td></tr>";
	else {
		// constitution du code HTML
		$vdr=0;
		$id_bloc=$id_inpret='';
		$odd_even = 0 ;
		
		// Gestion de limitation de la visualisation de la liste de pret.
		global $pmb_pret_aff_limitation;
		global $pmb_pret_aff_nombre;	
		global $see_all_pret;
		global $current_module;						
		
		while(list($cle, $valeur) = each($this->prets)) {
			$id_inpret .= $valeur['id'].'|';
			if ($valeur['pret_retard']==1) $tit_color="color='RED'";				
				else $tit_color="";				
			
			//Affichage des prolongation
			global $pmb_pret_restriction_prolongation,$pmb_pret_nombre_prolongation;
			$pret_nombre_prolongation=0;
			$forcage_prolongation=TRUE;
			$duree_prolongation=0;
			// Limitation simple du pret
			if($pmb_pret_restriction_prolongation==1) {
				$pret_nombre_prolongation=$pmb_pret_nombre_prolongation;
			} elseif($pmb_pret_restriction_prolongation==2) {
				// Limitation du pret par les quotas
				//Initialisation des quotas pour nombre de prolongations
				$qt = new quota("PROLONG_NMBR_QUOTA");
				//Tableau de passage des paramètres
				$struct["READER"] = $this->id;
				$struct["EXPL"] = $valeur['id'];			
				$pret_nombre_prolongation=$qt -> get_quota_value($struct);		
				$forcage_prolongation=$qt -> get_force_value($struct);				
			
			
				//Initialisation des quotas la durée de prolongations
				$qt = new quota("PROLONG_TIME_QUOTA");
				$struct["READER"] = $this->id;
				$struct["EXPL"] = $valeur['id'];	
				$duree_prolongation=$qt -> get_quota_value($struct);	
			
			}
			//$forcage_prolongation=FALSE;
			/* on prépare la date de début*/
			$pret_date = $valeur['sql_date_retour'];
			$rqt_date = "select date_add('".$pret_date."', INTERVAL '$duree_prolongation' DAY) as date_prolongation ";
			$resultatdate = mysql_query($rqt_date);
			$res = mysql_fetch_object($resultatdate) ;
			$date_prolongation=str_replace('-'    , ""    , $res->date_prolongation);		
				
			
				
			if ($odd_even==0) {
				$pair_impair = "odd";
				$odd_even=1;
			} else if ($odd_even==1) {
				$pair_impair = "even";
				$odd_even=0;
			}
					
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"";			
			$prets_list .= "
				<tr class='$pair_impair' $tr_javascript>
				<form class='form-$current_module' name=prolong".$valeur['id']." action='circ.php'>
					<td class='empr-expl'><a href='./circ.php?categ=visu_ex&form_cb_expl=".rawurlencode($valeur['cb'])."'>
						${valeur['cb']}</a>
					</td>
					<td size='70%'>
						<font $tit_color>".strip_tags($valeur['libelle'])."</font>
					</td>
					<td>
						<center>${valeur['typdoc']}</center>
					</td>
					<td>
						<center>${valeur['location']}<br />${valeur['section']}</center>
					</td>
					<td>
						<center>${valeur['date_pret']}</center>
					</td>				
					<td>
						<center>${valeur['retour_initial']}</center>
					</td>
					<td>";
					if($pmb_pret_restriction_prolongation == 0)
						$prets_list .= "<center>${valeur['cpt_prolongation']}</center>";
					else 
						$prets_list .= "<center>${valeur['cpt_prolongation']}/$pret_nombre_prolongation</center>";
												
			  $prets_list .= "</td>
						<td>
						<input type='hidden' name='categ' value='pret'>
						<input type='hidden' name='sub' value='pret_prolongation'>
						<input type='hidden' name='form_cb' value='$this->cb'>
						<input type='hidden' name='cb_doc' value='${valeur['cb']}'>
						<input type='hidden' name='id_doc' value='${valeur['id']}'>
						<input type='hidden' name='date_retour' value=\"\">";
			
			$vdr=max($vdr,$valeur['org_ret_date']);
			
			if($forcage_prolongation== FALSE && $valeur['cpt_prolongation']>=$pret_nombre_prolongation ) {
				
				$prets_list .= "<center>${valeur['date_retour']}</center>" .
						"</td>" .
						"</form><td>&nbsp;</td></tr>";								
			} else {
				$date_clic   = " onClick=\"openPopUp('./select.php?what=calendrier";
				$date_clic  .= "&caller=prolong".$valeur['id'];
				//$date_clic  .= "&date_caller=${valeur['org_ret_date']}";
				$date_clic  .= "&date_caller=$date_prolongation";
				$date_clic  .= "&param1=date_retour&param2=date_retour_lib&auto_submit=YES',";
				$date_clic  .= " 'date_retour', 250, 300, -2, -2,";
				$date_clic  .= " 'toolbar=no, dependent=yes, resizable=yes')\"";
				$prets_list .= "
					<input type='button' name='date_retour_lib' class='bouton' value='${valeur['date_retour']}' ".$date_clic." sorttable_customkey='${valeur['date_retour']}' />
					</td><td><center>";
				$prets_list .= "<input type='checkbox' id='prol_".$valeur['id']."' name='cbox_prol'  onClick='check_cb(this.form)'/>";								
				$prets_list .= "</center></td>
					</form></tr>";
			}			
		}
		// Gestion de limitation de la visualisation de la liste de pret.
		if(	$pmb_pret_aff_limitation==1) {
			if($pmb_pret_aff_nombre) {
				if (!$see_all_pret && $this->nb_pret > $pmb_pret_aff_nombre) {
					// le bouton 'Voir tous les prets' n'a pas été posté et on arrive à la limite imposée
					//Affichage du bouton 'Voir tous les prets' 
					$tout_voir_click = "onclick=\"document.location='circ.php?categ=pret&see_all_pret=1&form_cb=".$this->cb."'\"";
					$voir_tout_pret="<input type='button' name='see_all_pret' class='bouton' value='".$msg['pret_liste_voir_tout']."'  $tout_voir_click/>";
					//sortir de la boucle liste des prets
					
				}
			}		
		}					
	}
	$id_inpret=substr($id_inpret,0,-1);

	$date_format_SQL = substr($vdr,0,4).'-'.substr($vdr,4,2).'-'.substr($vdr,6,2);
	$svdr=formatdate($date_format_SQL);
	
	$date_prol   = " onClick=\"openPopUp('./select.php?what=calendrier";
	$date_prol  .= "&caller=prolong_bloc";
	$date_prol  .= "&date_caller=$vdr";
	$date_prol  .= "&param1=date_retbloc&param2=date_retbloc_lib&auto_submit=YES',";
	$date_prol  .= " 'date_retbloc',";
	//$date_prol  .= " 'toolbar=no, dependent=yes, width=250, height=260, resizable=yes')\"";
	$date_prol  .= " 250,260,-2,-2,'toolbar=no, dependent=yes, resizable=yes')\"";
	
	$butt_prol   = "
		<input type='button' name='date_retbloc_lib' class='bouton' value='$svdr' ".$date_prol." />
		<input type='hidden' name='categ' value='pret'>
		<input type='hidden' name='sub' value='pret_prolongation_bloc'>
		<input type='hidden' name='form_cb' value='$this->cb'>
		<input type='hidden' name='date_retbloc' value=\"\">
		<input type='hidden' name='id_bloc' value=\"\">";
	
	$this->fiche = str_replace('!!id_inpret!!'    , $id_inpret    , $this->fiche);
	if ($vdr) {
		$this->fiche = str_replace('!!prol_date!!'    , $butt_prol    , $this->fiche);
		$this->fiche = str_replace('!!bouton_cocher_prolong!!'    , "<input type='button' name='bloc_all' value='+' class='bouton' title='$msg[resa_tout_cocher]'  onClick='check_allcb(this.form)'/>", $this->fiche); 
	} else {
		$this->fiche = str_replace('!!prol_date!!'    , "", $this->fiche);
		$this->fiche = str_replace('!!bouton_cocher_prolong!!'    , "&nbsp;", $this->fiche);
	}
	
	$this->fiche = str_replace('!!voir_tout_pret!!'    , $voir_tout_pret    , $this->fiche);
	$this->fiche = str_replace('!!pret_list!!'    , $prets_list    , $this->fiche);
	
	//tableau des relances
	$this->fiche = str_replace('!!relance!!', $this->relance, $this->fiche);
	
	if($this->relance)	$bt_histo_relance="&nbsp;<input type='button' class='bouton' id='see_late' name='see_late' value=\"".$msg['empr_see_late']."\" onclick=\"document.location='./circ.php?categ=pret&sub=show_late&id=$this->id' \" />";
	$this->fiche = str_replace('!!bt_histo_relance!!',$bt_histo_relance, $this->fiche);
	
	// mise à jour de la liste des réservations
	$this->fiche = str_replace('!!resa_list!!', $this->fetch_resa(), $this->fiche);

	if($pmb_resa_planning) {
		// mise à jour de la liste des réservations plannifiées
		$this->fiche = str_replace('!!resa_planning_list!!', $this->fetch_resa_planning(), $this->fiche);
	} else {
		$this->fiche = str_replace('!!resa_planning_list!!', '', $this->fiche);
	}
	
	if($this->allow_sugg && (SESSrights & ACQUISITION_AUTH)){
		$req = "select count(id_suggestion) as nb from suggestions, suggestions_origine where num_suggestion=id_suggestion and origine='".$this->id."' and type_origine='1'  ";
		$res=mysql_query($req,$dbh);
		$btn_sug = "";
		$sug = mysql_fetch_object($res);
		if($sug->nb){
			$btn_sug = "<input type='button' class='bouton' id='see_sug' name='see_sug' value='".$msg['acquisition_lecteur_see_sugg']."' onclick=\"document.location='./acquisition.php?categ=sug&action=list&user_id=$this->id&user_statut=1' \" />";
		} 
		$this->fiche = str_replace('!!voir_sugg!!',$btn_sug,$this->fiche);
	}else{
		$this->fiche = str_replace('!!voir_sugg!!',"",$this->fiche);
	}	
	
}

function do_fiche_compte($typ_compte) {
	global $msg,$charset;
	global $empr_comptes_tmpl;
	global $show_transactions,$date_debut;
		
	$this->fiche_compte="";
	
	$empr_comptes_tmpl=str_replace("!!nom!!",$this->nom,$empr_comptes_tmpl);
	$empr_comptes_tmpl=str_replace("!!prenom!!",$this->prenom,$empr_comptes_tmpl);
	$empr_comptes_tmpl=str_replace("!!info_nb_pret!!",sizeof($this->prets),$empr_comptes_tmpl);
	$empr_comptes_tmpl=str_replace("!!info_nb_resa!!",$this->nb_reservations,$empr_comptes_tmpl);
	
	$id_compte=comptes::get_compte_id_from_empr($this->id,$typ_compte);
	if ($id_compte) {
		$cpte=new comptes($id_compte);
		if (!$show_transactions) $show_transactions=2;
		$empr_comptes_tmpl=str_replace("!!id_compte!!",$id_compte,$empr_comptes_tmpl);
		$empr_comptes_tmpl=str_replace("!!type_compte!!",$cpte->get_typ_compte_lib($typ_compte),$empr_comptes_tmpl);
		$empr_comptes_tmpl=str_replace("!!typ_compte!!",$typ_compte,$empr_comptes_tmpl);
		$empr_comptes_tmpl=str_replace("!!solde!!",comptes::format($cpte->get_solde()),$empr_comptes_tmpl);
		$empr_comptes_tmpl=str_replace("!!non_valide!!",comptes::format($cpte->summarize_transactions("","",0,0)),$empr_comptes_tmpl);
		$empr_comptes_tmpl=str_replace("!!show_transactions!!",$show_transactions,$empr_comptes_tmpl);
		$empr_comptes_tmpl=str_replace("!!date_debut!!",htmlentities(stripslashes($date_debut),ENT_QUOTES,$charset),$empr_comptes_tmpl);
		if (!$show_transactions) $show_transactions=1;
		for ($i=1; $i<=3; $i++) {
			if ($i==$show_transactions) 
				$empr_comptes_tmpl=str_replace("!!checked$i!!","checked",$empr_comptes_tmpl);
			else
				$empr_comptes_tmpl=str_replace("!!checked$i!!","",$empr_comptes_tmpl);
		}
	}
	$this->fiche_compte=$empr_comptes_tmpl;
}

// fabrication de la fiche lecteur pour affichage uniquement, pas de bouton, allégé
function do_fiche_affichage() {

	global $empr_tmpl_fiche_affichage;
	global $msg;
	global $groupID;
	global $biblio_email;

	global $alert_sound_list; // l'utilisateur veut-il les sons d'alerte
	
	$this->fiche_affichage = $empr_tmpl_fiche_affichage;
	$this->fiche_affichage = str_replace('!!cb!!'        , $this->cb    , $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!nom!!'    , pmb_strtoupper($this->nom)    , $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!prenom!!'    , $this->prenom    , $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!info_nb_pret!!'    , sizeof($this->prets)    , $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!info_nb_resa!!'    , $this->nb_reservations    , $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!info_authldap!!'    , $this->ldap, $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!id!!'        , $this->id    , $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!date!!'    , $this->birth    , $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!adhesion!!'    , $this->aff_date_adhesion, $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!expiration!!'    , $this->aff_date_expiration, $this->fiche_affichage);
	$this->fiche_affichage = str_replace('!!empr_statut_libelle!!'    , $this->empr_statut_libelle    , $this->fiche_affichage);
	
	if ($this->empr_msg) {
		$message_fiche_empr= "
				<hr />
				<div class='row'>
					<div class='colonne10'><img src='./images/info.png' /></div>
					<div class='colonne-suite'><span class='erreur'>$this->empr_msg</span></div>
					</div><br />";
		$alert_sound_list[]="information";
		
		$this->fiche_affichage = str_replace('!!empr_msg!!'    ,$message_fiche_empr , $this->fiche_affichage);
	} else 
		$this->fiche_affichage = str_replace('!!empr_msg!!', "", $this->fiche_affichage);
	
	// on distingue les messages de prêts du message sur l'emprunteur
	$this->fiche_affichage = str_replace('!!pret_msg!!'    , $this->message    , $this->fiche_affichage);

	if ($this->adhesion_renouv_proche()) {
		$message_date_depassee = $msg[empr_date_renouv_proche];
		} elseif ($this->adhesion_depassee()) {
			$message_date_depassee = $msg[empr_date_depassee];
			} else {
				$message_date_depassee="";
			}
	if ($message_date_depassee) $alert_sound_list[]="critique";
	$this->fiche_affichage = str_replace('!!empr_date_depassee!!', $message_date_depassee, $this->fiche_affichage);

	$fsexe[0] = $msg[128];
	$fsexe[1] = $msg[126];
	$fsexe[2] = $msg[127];

	$this->fiche_affichage = str_replace('!!sexe!!'    , $fsexe[$this->sexe], $this->fiche_affichage);

	// valeur pour les champ hidden du prêt. L'id empr est pris en charge plus haut 
	$this->fiche_affichage = str_replace('!!cb!!'    , $this->cb    , $this->fiche_affichage);

	// traitement liste exemplaires en prêt
	$this->fiche_affichage = str_replace('!!nb_prets_encours!!'    , $this->nb_pret    , $this->fiche_affichage);

	if(!count($this->prets))
		// dans ce cas, le lecteur n'a rien en prêt
		$prets_list = "<tr><td class='ex-strip' colspan='7'>$msg[650]</td></tr>";
	else {
		// constitution du code HTML
		$odd_even = 0 ;
		
		// Gestion de limitation de la visualisation de la liste de pret.
		global $pmb_pret_aff_limitation;
		global $pmb_pret_aff_nombre;	
		global $see_all_pret;
		global $current_module;						
		
		while(list($cle, $valeur) = each($this->prets)) {

			if ($valeur['pret_retard']==1){
				$tit_color="color='RED'";				
			}else{
				$tit_color="";				
			}
				
			if ($odd_even==0) {
				$pair_impair = "odd";
				$odd_even=1;
			} else if ($odd_even==1) {
					$pair_impair = "even";
					$odd_even=0;
			}
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"";			
			
			$prets_list .= "
				<tr class='$pair_impair' $tr_javascript>
				<form class='form-$current_module' name='prolong".$valeur['id']."' action='circ.php'>
					<td class='empr-expl'><a href='./circ.php?categ=visu_ex&form_cb_expl=".$valeur['cb']."'>
						${valeur['cb']}</a>
					</td>
					<td size='70%'>
						<font $tit_color>${valeur['libelle']}</font>
					</td>
					<td class='empr-expl'>
						${valeur['typdoc']}
					</td>
					<td class='empr-expl'>
						${valeur['date_pret']}
					</td>				
					<td>
						<center>${valeur['retour_initial']}</center>
					</td>
					<td>
						<center>${valeur['cpt_prolongation']}</center>
					</td>
					<td class='date-retour'>
						<input type='hidden' name='categ' value='pret'>
						<input type='hidden' name='sub' value='pret_prolongation'>
						<input type='hidden' name='form_cb' value='$this->cb'>
						<input type='hidden' name='cb_doc' value='${valeur['cb']}'>
						<input type='hidden' name='id_doc' value='${valeur['id']}'>
						<input type='hidden' name='date_retour' value=\"\">
					";
					$date_clic   = " onClick=\"openPopUp('./select.php?what=calendrier";
					$date_clic  .= "&caller=prolong".$valeur['id'];
					$date_clic  .= "&date_caller=${valeur['org_ret_date']}";
					$date_clic  .= "&param1=date_retour&param2=date_retour_lib&auto_submit=YES',";
					$date_clic  .= " 'date_retour',";
					//$date_clic  .= " 'toolbar=no, dependent=yes, width=250, height=260, resizable=yes')\"";
					$date_clic  .= " 250,260,-2,-2,'toolbar=no, dependent=yes, resizable=yes')\"";
					$prets_list .= "
								<center><input type='button' name='date_retour_lib' class='bouton' value='${valeur['date_retour']}' ".$date_clic." /></center>
								";
					$prets_list .="    </td>
								</form></tr>
								";
					// ouf, c'est fini ;-)
		}
					
		// Gestion de limitation de la visualisation de la liste de pret.
		if(	$pmb_pret_aff_limitation==1) {				
			if($pmb_pret_aff_nombre) {
				if (!$see_all_pret && ($this->nb_pret > $pmb_pret_aff_nombre)) {
					// le bouton 'Voir tous les prets' n'a pas été posté et on arrive à la limite imposée
					//Affichage du bouton 'Voir tous les prets' 
					$tout_voir_click = "onclick=\"document.location='circ.php?categ=pret&see_all_pret=1&form_cb=".$this->cb."'\"";
					$prets_list .= "
					<tr><td>
						<input type='button' name='see_all_pret' class='bouton' value='".$msg['pret_liste_voir_tout']."'  $tout_voir_click/>
					</td></tr>";
					//sortir de la boucle liste des prets
				}
			}		
		}			
	} //else
	$this->fiche_affichage = str_replace('!!pret_list!!'    , $prets_list    , $this->fiche_affichage);
	// mise à jour de la liste des réservations
	$this->fiche_affichage = str_replace('!!resa_list!!', $this->fetch_resa(), $this->fiche_affichage);
} // fin do_fiche_affichage


//   récupération de la liste des réservations pour l'emprunteur
function fetch_resa() {
	return resa_list (0, 0, $this->id,"","",LECTEUR_INFO_GESTION,"") ;
	// resa_list ($idnotice=0, $idbulletin=0, $idempr=0, $order, $where = "", $info_gestion=0, $url_gestion="")
}


//   récupération de la liste des réservations planifiées pour l'emprunteur
function fetch_resa_planning() {
	return empr_planning_list ($this->id) ;
}



// fonction de vérification que la date d'adhésion est dépassée ou pas
function adhesion_depassee() {
	global $dbh ;

	$rqt_date = "select case when empr_date_expiration < now() then 1 ELSE 0 END as test_date ";
	$rqt_date .=" from empr where id_empr='".$this->id."' ";
	$resultatdate=mysql_query($rqt_date);
	$resdate=mysql_fetch_object($resultatdate);
	
	return $resdate->test_date;
}

// fonction de vérification que la date d'adhésion est proche ou pas
function adhesion_renouv_proche() {
	global $pmb_relance_adhesion ;
	global $dbh ;

	$rqt_date = "select case when (((to_days(empr_date_expiration)-to_days(now()))<=$pmb_relance_adhesion) and empr_date_expiration>=now()) then 1 ELSE 0 END as test_date ";
	$rqt_date .=" from empr where id_empr='".$this->id."' ";
	$resultatdate=mysql_query($rqt_date);
	$resdate=mysql_fetch_object($resultatdate);
	return $resdate->test_date;
}

// fonction de suppression
function del_empr($id=0) {
	global $dbh ;

	if (is_object($this) && !$id) $id=$this->id;
	
	$rqt_prets = "select 1 from pret where pret_idempr=$id ";
	$resultat_prets=mysql_query($rqt_prets);
	$resprets=mysql_num_rows($resultat_prets);
	if (mysql_num_rows($resultat_prets)) 
		return false;
	else {
		$rqt_del = "delete from empr_caddie_content where object_id=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "delete from empr where id_empr=$id ";
		$resultat_del=mysql_query($rqt_del);

		$p_perso=new parametres_perso("empr");
		$p_perso->delete_values($id);

		$rqt_del = "delete from empr_groupe where empr_id=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "update groupe set resp_groupe=0 where resp_groupe=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "delete from recouvrements where empr_id=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "delete from resa where resa_idempr=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "delete from resa_planning where resa_idempr=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "delete from opac_sessions where empr_id=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "update suggestions_origine set origine='', type_origine=2 where origine=$id and type_origine=1 ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "delete from bannette_abon where num_empr=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "delete from bannette where proprio_bannette=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "delete from equations where proprio_equation=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "delete from compte where proprio_id=$id ";
		$resultat_del=mysql_query($rqt_del);

		$rqt_del = "update avis set num_empr=0 where num_empr=$id ";
		$resultat_del=mysql_query($rqt_del);

		$query = mysql_query("DELETE bannettes FROM bannettes LEFT JOIN empr ON proprio_bannette = id_empr WHERE id_empr IS NULL AND proprio_bannette !=0");
		$query = mysql_query("DELETE equations FROM equations LEFT JOIN empr ON proprio_equation = id_empr WHERE id_empr IS NULL AND proprio_equation !=0 ");
		$query = mysql_query("DELETE bannette_equation FROM bannette_equation LEFT JOIN bannettes ON num_bannette = id_bannette WHERE id_bannette IS NULL ");
		$query = mysql_query("DELETE bannette_equation FROM bannette_equation LEFT JOIN equations on num_equation=id_equation WHERE id_equation is null");
		$query = mysql_query("DELETE bannette_abon FROM bannette_abon LEFT JOIN empr on num_empr=id_empr WHERE id_empr is null");
		$query = mysql_query("DELETE bannette_abon FROM bannette_abon LEFT JOIN bannettes ON num_bannette=id_bannette WHERE id_bannette IS NULL ");
		
		//listes de lecture partagées
		$rqt_del = "delete opac_liste_lecture where num_empr=$id ";
		$resultat_del=mysql_query($rqt_del);		
		$rqt_del = "delete abo_liste_lecture where num_empr=$id ";
		$resultat_del=mysql_query($rqt_del);
		$query = mysql_query("delete abo_liste_lecture from abo_liste_lecture left join empr on num_empr=id_empr where id_empr is null");
		$query = mysql_query("delete abo_liste_lecture from abo_liste_lecture left join opac_liste_lecture on num_liste=id_liste where id_liste is null");
		$query = mysql_query("delete opac_liste_lecture from opac_liste_lecture left join empr on num_empr=id_empr where id_empr is null");
		
		//Historique des relances
		
		$del_histo = "delete lr, ler from log_retard lr join log_expl_retard ler on lr.id_log=ler.num_log_retard where lr.idempr=$id";
		mysql_query($del_histo);
		return true;
	}
}

// méthode qui retourne un objet <img> avec l'url de la photo de l'emprunteur
function picture_empr($empr_cb) {
	global $charset;
	global $empr_pics_url, $empr_pics_max_size ;
	if ($empr_pics_url) {
		$code_chiffre = pmb_preg_replace('/ /', '', $empr_cb);
		$url_image = $empr_pics_url ;
		$url_image = "./getimage.php?url_image=".urlencode($url_image)."&empr_pic=1" ;
		$url_image_ok = str_replace("%21%21num_carte%21%21", $code_chiffre, $url_image) ;
		$image = "<img src='".$url_image_ok."' $maxsize />";
	} else 
		$image="" ;
	
	return $image;
} 


// fabrication de la fiche lecteur
function do_fiche_consultation() {

	global $empr_tmpl_consultation, $empr_pret_allowed;
	global $msg,$charset;
	global $groupID;
	global $biblio_email;
	global $pmb_lecteurs_localises ;
	global $pmb_gestion_abonnement,$pmb_gestion_financiere, $pmb_gestion_tarif_prets, $pmb_gestion_amende;
	global $finance_blocage_abt,$finance_blocage_amende,$finance_blocage_pret,$pmb_blocage_retard,$pmb_blocage_retard_force;
	global $force_finance;
	global $pmb_resa_planning;
	global $dbh;
	global $pmb_blocage_retard,$pmb_blocage_coef,$pmb_blocage_max,$pmb_blocage_delai;
	global $empr_fiche_depliee;
	
	//global $cb_inpret;

	global $alert_sound_list; // l'utilisateur veut-il les sons d'alerte

	$this->fiche_consultation = $empr_tmpl_consultation;	
	$this->fiche_consultation = str_replace('!!cb!!'        , $this->cb    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!nom!!'    , pmb_strtoupper($this->nom)    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!prenom!!'    , $this->prenom    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!image_caddie_empr!! ', $this->img_ajout_empr_caddie, $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!info_nb_pret!!'    , sizeof($this->prets)    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!info_nb_resa!!'    , $this->nb_reservations    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!info_authldap!!'    , $this->ldap, $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!id!!'        , $this->id    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!adr1!!'    , $this->adr1    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!adr2!!'    , $this->adr2    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!tel1!!'    , $this->tel1    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!sms!!'    , $this->sms    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!tel2!!'    , $this->tel2    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!cp!!'        , $this->cp    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!ville!!'    , $this->ville    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!pays!!'    , $this->pays    , $this->fiche_consultation);
	
	$emails=array();
	$email_final=array();
	$emails = explode(';',$this->mail);
	for ($i=0;$i<count($emails);$i++) $email_final[] ="<a href='mailto:".$emails[$i]."'>".$emails[$i]."</a>";
	
	$this->fiche_consultation = str_replace('!!mail_all!!'    , implode("&nbsp;",$email_final)    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!prof!!'    , $this->prof    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!date!!'    , $this->birth    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!categ!!'    , $this->cat_l    , $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!codestat!!'    , $this->cstat_l, $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!adhesion!!'    , $this->aff_date_adhesion, $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!expiration!!'    , $this->aff_date_expiration, $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!perso!!'    , $this->perso, $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!header_format!!'    , $this->header_format, $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!empr_login!!'    , $this->login, $this->fiche_consultation);
	if ($this->pwd) $this->fiche_consultation = str_replace('!!empr_pwd!!',"<i><strong>".$msg["empr_pwd_opac_affected"]."</strong</i>",$this->fiche_consultation);
		else $this->fiche_consultation = str_replace('!!empr_pwd!!',"",$this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!comptes!!'    , $this->compte, $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!empr_statut_libelle!!', $this->empr_statut_libelle, $this->fiche_consultation);
	$this->fiche_consultation = str_replace('!!empr_picture!!', $this->picture_empr($this->cb), $this->fiche_consultation);
	if ($empr_fiche_depliee=="1") $this->fiche_consultation = str_replace('!!depliee!!'," startOpen=\"Yes\"", $this->fiche_consultation);
		else $this->fiche_consultation = str_replace('!!depliee!!',"", $this->fiche_consultation);
	
	if ($pmb_lecteurs_localises) $this->fiche_consultation = str_replace("<!-- !!localisation!! -->", "<div class='row'><strong>$msg[empr_location] : </strong>".$this->empr_location_l."</div>", $this->fiche_consultation);
	
	//Groupes
	if (count($this->groupes)) {
		$this->fiche_consultation = str_replace('!!groupes!!',"<strong>".$msg[groupes_empr]." : </strong>".implode(" / ",$this->groupes)."\n",$this->fiche_consultation);
	} else {
			$this->fiche_consultation = str_replace('!!groupes!!',"&nbsp;",$this->fiche_consultation);
	}
	
	if (($pmb_gestion_financiere)&&($pmb_gestion_abonnement==2)) {
		if ($this->type_abt) {
			$requete="select type_abt_libelle from type_abts where id_type_abt='".$this->type_abt."'";
			$resultat_type_abt=mysql_query($requete);
			if (@mysql_num_rows($resultat_type_abt)) {
				$abonnement=mysql_result($resultat_type_abt,0,0);
			}
		}
	}
	
	if ($abonnement) {
		$this->fiche_consultation = str_replace("!!abonnement!!", "<div class='row'><strong>".$msg["finance_type_abt"]." : </strong>".htmlentities($abonnement,ENT_QUOTES,$charset)."</div>\n",$this->fiche_consultation);
	} else {
		$this->fiche_consultation = str_replace("!!abonnement!!","",$this->fiche_consultation);
	}
	

	if ($this->empr_msg) {
		$message_fiche_empr= "
				<hr />
				<div class='row'>
					<div class='colonne10'><img src='./images/info.png' /></div>
					<div class='colonne-suite'><span class='erreur'>$this->empr_msg</span></div>
					</div><br />";
		$this->fiche_consultation = str_replace('!!empr_msg!!'    ,$message_fiche_empr , $this->fiche_consultation);
	} else 
		$this->fiche_consultation = str_replace('!!empr_msg!!', "", $this->fiche_consultation);

	$fsexe[0] = $msg[128];
	$fsexe[1] = $msg[126];
	$fsexe[2] = $msg[127];
	$this->fiche_consultation = str_replace('!!sexe!!'    , $fsexe[$this->sexe], $this->fiche_consultation);

	
}

function import($data){
		//champs de data : nom, prenom, cb, adr1, adr2,cp, ville, pays, mail, tel1, sms, tel2, year, sexe, login, password, date_adhesion, date_fin_blocage, date_expiration, date_creation
		//date_modif, prof, total_loans,last_loan_date, lang, msg, type_abt,
		//Pour la localisation : location, location_libelle, location_libelle_create, locdoc_owner 
		//Pour la categorie : categ, categ_libelle, categ_libelle_create;
		//Pour le codestat: codestat, codestat_libelle, codestat_libelle_create;
		//Pour le statut: statut, statut_libelle, statut_libelle_create;
	
		global $dbh, $lang;
	
		// check sur le type de  la variable passée en paramètre
		if(!sizeof($data) || !is_array($data)) {
			// si ce n'est pas un tableau ou un tableau vide, on retourne 0
			return 0;
		}
		//Check si le lecteur a au moin un nom ou un prenom
		if(!$data['nom'])
			return 0;
		
		//Check si le code barre n'est pas déja utilisé
		$this->cb=addslashes($data['cb']);
		
		$query = "SELECT id_empr FROM empr WHERE empr_cb='".$this->cb."' LIMIT 1 ";
		$result = @mysql_query($query, $dbh);
		if(!$result) die("can't SELECT in database");
		//On prepare les paramètres
		$this->empr_location=0;
		if(!$data['location'] and !$data['location_libelle'] and $data['location_libelle_create'] != ''){
			//Dans la cas ou l'on veut creer la location
			$data2=array();
			$data2['location_libelle'] = $data['location_libelle_create'];	
			$data2['locdoc_codage_import'] = $data['location_libelle_create'];
			$data2['locdoc_owner'] = $data['locdoc_owner'];
			$this->empr_location = docs_location::import($data2);
		}elseif($data['location_libelle'] != ''){
			$q="select idlocation from docs_location where location_libelle='".addslashes($data['location_libelle'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->empr_location =mysql_result($r,0,0);	
			}
		}else{
			$q="select idlocation from docs_location where idlocation='".addslashes($data['location'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->empr_location =mysql_result($r,0,0);	
			}
		}
		
		if(!$this->empr_location) return 0;
		
		$this->categ =0;
		if(!$data['categ'] and !$data['categ_libelle'] and $data['categ_libelle_create'] != ''){
			//Dans la cas ou l'on veut creer la location
			$q="select id_categ_empr from empr_categ where libelle='".addslashes($data['categ_libelle_create'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->categ =mysql_result($r,0,0);	
			} else {
				$q= "insert into empr_categ (libelle) values ('".addslashes($data['categ_libelle_create'])."') ";
				$r = mysql_query($q, $dbh);
				$this->categ =mysql_insert_id($dbh);
			}
		}elseif($data['categ_libelle'] != ''){
			$q="select id_categ_empr from empr_categ where libelle='".addslashes($data['categ_libelle'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->categ =mysql_result($r,0,0);	
			}
		}else{
			$q="select id_categ_empr from empr_categ where id_categ_empr='".addslashes($data['categ'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->categ =mysql_result($r,0,0);	
			}
		}
		if(!$this->categ) return 0;
		
		$this->cstat=0;
		if(!$data['codestat'] and !$data['codestat_libelle'] and $data['codestat_libelle_create'] != ''){
			//Dans la cas ou l'on veut creer la location
			$q="select idcode from empr_codestat where libelle='".addslashes($data['codestat_libelle_create'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->cstat =mysql_result($r,0,0);	
			} else {
				$q= "insert into empr_codestat (libelle) values ('".addslashes($data['codestat_libelle_create'])."') ";
				$r = mysql_query($q, $dbh);
				$this->cstat =mysql_insert_id($dbh);
			}
		}elseif($data['codestat_libelle'] != ''){
			$q="select idcode from empr_codestat where libelle='".addslashes($data['codestat_libelle'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->cstat =mysql_result($r,0,0);	
			}
		}else{
			$q="select idcode from empr_codestat where idcode='".addslashes($data['codestat'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->cstat =mysql_result($r,0,0);	
			}
		}
		if(!$this->cstat) return 0;
		
		$this->empr_statut=0;
		if(!$data['statut'] and !$data['statut_libelle'] and $data['statut_libelle_create'] != ''){
			//Dans la cas ou l'on veut creer la location
			$q="select idstatut from empr_statut where statut_libelle='".addslashes($data['statut_libelle_create'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->empr_statut =mysql_result($r,0,0);	
			} else {
				$q= "insert into empr_statut (statut_libelle) values ('".addslashes($data['statut_libelle_create'])."') ";
				$r = mysql_query($q, $dbh);
				$this->empr_statut =mysql_insert_id($dbh);
			}
		}elseif($data['statut_libelle'] != ''){
			$q="select idstatut from empr_statut where statut_libelle='".addslashes($data['statut_libelle'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->empr_statut =mysql_result($r,0,0);	
			}
		}else{
			$q="select idstatut from empr_statut where idstatut='".addslashes($data['statut'])."' limit 1";
			$r = mysql_query($q, $dbh);
			if (mysql_num_rows($r)) {
				$this->empr_statut =mysql_result($r,0,0);	
			}
		}
		if(!$this->empr_statut) return 0;
		
		$this->nom=addslashes($data['nom']);
		$this->prenom=addslashes($data['prenom']);
		$this->adr1=addslashes($data['adr1']);
		$this->adr2=addslashes($data['adr2']);
		$this->cp=addslashes($data['cp']);
		$this->ville=addslashes($data['ville']);
		$this->pays=addslashes($data['pays']);
		$this->mail=addslashes($data['mail']);
		$this->tel1=addslashes($data['tel1']);
		$this->sms=addslashes($data['sms']);
		$this->tel2=addslashes($data['tel2']);
		if($data['sexe'] === 0 or $data['sexe'] == 1 or $data['sexe'] == 2){
			$this->sexe=$data['sexe'];
		}else{
			$this->sexe=0;
		}
		$this->birth=addslashes($data['year']);
		$this->date_adhesion=addslashes($data['date_adhesion']);
		$this->date_blocage=addslashes($data['date_fin_blocage']);
		$this->date_expiration=addslashes($data['date_expiration']);
		if(!$data['date_creation']){
			$this->cdate=today();
		}else{
			$this->cdate=addslashes($data['date_creation']);
		}
		if(!$data['date_modif']){
			$this->mdate=today();
		}else{
			$this->mdate=addslashes($data['date_modif']);
		}	
		$this->pwd=addslashes($data['password']);
		$this->prof=addslashes($data['prof']);
		$this->total_loans=addslashes($data['total_loans']);
		$this->last_loan_date=addslashes($data['last_loan_date']);
		if(!$data['lang']){
			$this->empr_lang=$lang;
		}else{
			$this->empr_lang=addslashes($data['lang']);
		}
		$this->empr_msg=addslashes($data['msg']);	
		$this->type_abt=addslashes($data['type_abt']);
		$this->login=addslashes($data['login']);
		
		$q = "insert into empr (empr_cb, empr_nom, empr_prenom, empr_adr1, empr_cp, empr_ville, empr_pays, ";
		$q.= "empr_mail, empr_tel1, empr_sms, empr_categ, empr_codestat, empr_sexe, empr_login, empr_date_adhesion, ";
		$q.= "empr_date_expiration, empr_lang, empr_location,empr_msg,empr_year,empr_creation,empr_adr2,empr_tel2, empr_modif,empr_password,empr_prof,type_abt,empr_statut,total_loans,last_loan_date,date_fin_blocage) ";
		$q.= "values ('".$this->cb."', '".$this->nom."', '".$this->prenom."', '".$this->adr1."', '".$this->cp."', '".$this->ville."', '".$this->pays."', ";
		$q.= "'".$this->mail."', '".$this->tel1."', '".$this->sms."', '".$this->categ."', '".$this->cstat."', '".$this->sexe."', '".$this->login."', '".$this->date_adhesion."', ";
		$q.= "'".$this->date_expiration."', '".$this->empr_lang."', '".$this->empr_location."', '".$this->empr_msg."', '".$this->birth."', '".$this->cdate."', '".$this->adr2."', '".$this->tel2."', '".$this->mdate."', '".$this->pwd."', '".$this->prof."','".$this->type_abt."','".$this->empr_statut."','".$this->total_loans."', '".$this->last_loan_date."', '".$this->date_blocage."') ";
		$r=mysql_query($q, $dbh);
	
	return mysql_insert_id($dbh);	
}

function do_login($nom,$prenom) {
	global $dbh;
	
	$nom_forate=str_replace(' ','',strtolower(strip_empty_chars($nom)));
	$prenom_forate=str_replace(' ','',strtolower(strip_empty_chars($prenom)));
	$empr_login = substr($prenom_forate,0,1).$nom_forate;
	$pb = 1 ;
	$num_login=1 ;
	$empr_login2=$empr_login;
	while ($pb==1) {
		$q = "SELECT empr_login FROM empr WHERE empr_login='$empr_login2' LIMIT 1 ";
		$r = mysql_query($q, $dbh);
		$nb = mysql_num_rows($r);
		if ($nb) {
			$empr_login2 =$empr_login.$num_login ;
			$num_login++;
		} else $pb = 0 ;
	}
	
	return $empr_login2;
}

function do_fiche_retard(){	
	global $charset, $empr_retard_tpl, $dbh, $msg;
	global $opac_empr_hist_nb_jour_max;
	
	$empr_retard_tpl = str_replace("!!prenom!!",htmlentities($this->prenom,ENT_QUOTES,$charset),$empr_retard_tpl);
	$empr_retard_tpl = str_replace("!!nom!!",htmlentities($this->nom,ENT_QUOTES,$charset),$empr_retard_tpl);
	
	if ($opac_empr_hist_nb_jour_max){ 
		$req_retards = "select id_log from log_retard where idempr='".$this->id."' and date_add(date_log, INTERVAL $opac_empr_hist_nb_jour_max day)<sysdate()";
		$res_ret = mysql_query($req_retards);
		while(($retard = mysql_fetch_object($res_ret))){
			$req_del="delete from log_expl_retard where num_log_retard =".$retard->id_log;
			mysql_query($req_del);
			$req_del="delete from log_retard where id_log =".$retard->id_log;
			mysql_query($req_del);
		}		
	}
	
	$req_retards = "select * from log_retard where idempr='".$this->id."' order by date_log desc";
	$res_ret = mysql_query($req_retards);
	while(($retard = mysql_fetch_object($res_ret))){
		$empr_retard_tpl = str_replace("!!nivo_relance!!",$retard->niveau_reel,$empr_retard_tpl);
		$titre_relance= "<b>".$msg['empr_nivo_relance']." : ".$retard->niveau_reel." ".$msg['empr_late_relance']." ".formatdate($retard->date_log)."</b> (".$msg['empr_late_amende']." : ".comptes::format($retard->amende_totale)." ".$msg['empr_late_frais']." : ".comptes::format($retard->frais).")";
		$liste= "
		<table class='sortable'>
		<th>".$msg['empr_late_titre']."</th>
		<th>".$msg['empr_late_expl_cb']."</th>
		<th>".$msg['empr_late_date_pret']."</th>
		<th>".$msg['empr_late_date_retour']."</th>
		<th>".$msg['empr_late_amende']."</th>
		";		
		$req_expl = "select * from log_expl_retard where num_log_retard='".$retard->id_log."'";
		$res = mysql_query($req_expl);
		$content="";
		while($expl = mysql_fetch_object($res)){
			if($tr_class=='odd') $tr_class='even'; else $tr_class='odd';
			$content.= "
			<tr class='$tr_class'>
				<td>".$expl->titre."</td>
				<td>".$expl->expl_cb."</td>
				<td>".formatdate($expl->date_pret)."</td>
				<td>".formatdate($expl->date_retour)."</td>
				<td>".comptes::format($expl->amende)."</td>
			</tr>";
		}
		$liste.= $content;
		$liste.= "</table>";
		$result.= gen_plus("relance_".$retard->id_log,$titre_relance,$liste,1);		
	}
	$empr_retard_tpl = str_replace("!!id!!",$this->id,$empr_retard_tpl);	
	$empr_retard_tpl = str_replace("!!liste_retard!!",$result,$empr_retard_tpl);	
	$empr_retard_tpl = str_replace("!!nivo_relance!!",0,$empr_retard_tpl);// si aucun imprimé
	$this->fiche_retard = $empr_retard_tpl;
}

function do_tablo_relance(){
	global $msg, $dbh;	
	
	$tableau ="";
	$amende = new amende($this->id);
	$level = $amende->get_max_level();
	$niveau=$level["level"];
	$niveau_min=$level["level_min"];
	$niveau_normal=$level["level_normal"];
	$printed=$level["printed"];
	$date_relance=$level["level_min_date_relance"];
	$list_dates[$date_relance]=format_date($date_relance);
	
	if($niveau_min || $niveau_normal){
		$requete ="select count(pret_idexpl) as empr_nb from empr, pret, exemplaires where 
		pret_retour<now() and pret_idempr=id_empr and pret_idexpl=expl_id and id_empr='".$this->id."'";
		$res = mysql_query($requete,$dbh);
		$empr =mysql_fetch_object($res);
		
		$tableau = "<table width='100%' >";
//		$tableau .= "<tr><th>".$msg["relance_nb_retard"]."</th><th>".$msg["relance_dernier_niveau"]."</th><th>".$msg["relance_date_derniere"]."</th><th>".$msg["relance_imprime"]."</th><th>".$msg["relance_niveau_suppose"]."</th></tr>";
		$tableau .= "<tr>
		<td>".$msg["relance_nb_retard"].": $empr->empr_nb</td>
		<td>".$msg["relance_dernier_niveau"].": $niveau_min</td>
		<td>".$msg["relance_date_derniere"].": ".$list_dates[$date_relance]."</td>
		<td>".$msg["relance_imprime"].": ".($printed?"".$msg[40]."":"".$msg[39]."")."</td>
		<td>".$msg["relance_niveau_suppose"].": $niveau_normal</td>
		</tr>";
		$tableau .= "</table>";
	}
	
	return $tableau;
}

} # fin de déclaration classe emprunteur


