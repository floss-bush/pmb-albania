<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_affichage.class.php,v 1.8 2010-10-25 13:10:34 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$include_path/resa_func.inc.php");
require_once("$include_path/resa_planning_func.inc.php");
require_once($class_path."/comptes.class.php");
require_once($class_path."/amende.class.php");
require_once("$class_path/parametres_perso.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/serial_display.class.php");

$selector_prop_ajout_caddie_empr = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";
// définition de la classe d'affichage des emprunteurs
class empr_affichage {

//---------------------------------------------------------
//            Propriétés
//---------------------------------------------------------

var $id_empr	 = 0;	    // id MySQL emprunteur
var $empr_cb     = ''  ;    // code barre emprunteur
var $empr_nom    = ''  ;    // nom emprunteur
var $empr_prenom = ''  ;    // prénom emprunteur
var $empr_adr1   = ''  ;    // adresse ligne 1
var $empr_adr2   = ''  ;    // adresse ligne 2
var $empr_cp     = ''  ;    // code postal
var $empr_ville  = ''  ;    // ville
var $empr_pays   = ''  ;    // pays
var $empr_mail   = ''  ;    // adresse email
var $empr_tel1   = ''  ;    // téléphone 1
var $empr_tel2   = ''  ;    // téléphone 2
var $empr_prof   = ''  ;    // profession
var $empr_year   = ''  ;    // année de naissance
var $empr_categ  = 0;		// catégorie emprunteur
var $cat_l  = ''    ;  		// libellé catégorie emprunteur
var $empr_codestat  = 0;    // code statistique
var $cstat_l= 0     ;    	// libellé code statistique
var $empr_creation  = '';   // date de création
var $empr_modif  = ''  ;    // date de modification
var $empr_sexe   = 0   ;    // sexe de l'emprunteur
var $empr_login  = ''    ;  // login pour services OPAC
var $empr_password    = ''; // mot de passe OPAC
var $empr_date_adhesion= '';// début adhésion
var $empr_date_expiration='';// fin adhésion
var $aff_date_adhesion= ''; // début adhésion formatée
var $aff_date_expiration='';// Fin adhésion formatée
var $empr_msg     = ''  ;   // Message emprunteur
var $empr_lang     = '';    // Langue emprunteur
var $empr_ldap   = '';  	// flag pour AuthLdap
var $type_abt   = ''   	;  	// Type d'abonnement du lecteur
var $type_abt_l   = ''  ;  	// Libellé du type d'abonnement du lecteur
var $last_loan_date=""; 	// Date du dernier emprunt
var $empr_location = 0; 	// Localisation de l'emprunteur
var $empr_location_l = ""; 	// Localisation de l'emprunteur
var $date_fin_blocage=""; 	// Date de fin de blocage du lecteur
var $total_loans=0; 		// Nbre total d'emprunts
var $empr_statut=1; 		// Statut de l'emprunteur
var $empr_statut_l=""; 		// Libellé du statut de l'emprunteur
var $liens=array() ;        // Tableau des liens : !!id_empr!! et !!empr_cb!! sont remplacés par les valeurs si besoin
							//		-$lien[nom_prenom] = url du lien à mettre sur le nom+prénom
var $groupes=array() ;      // Tableau des groupes du lecteur
var $img_ajout_empr_caddie='';// Icône ajout panier si activé.
var $lien_nom_prenom = '' ; // NOM, Prénom avec lien vers ficher lecteur
var $blocage_active=false;  // Compte bloqué ou pas ?
var $perso = ""        ;    // Champs personalisés
var $compte = ""		;	//Comptes financiers

var $allow_loan=1;     		// Pret autorisé
var $allow_book=1;		    // Reservation autorisée
var $allow_opac=1;     		// OPAC autorisé
var $allow_dsi=1;      		// DSI autorisée
var $allow_dsi_priv=1; 		// DSI privée autorisée
var $allow_sugg=1;     		// Suggestions autorisées
var $allow_prol=1;     		// Demande de prolongation autorisée
var $prets        ;    		// array contenant les prêts de l'emprunteur
var $nb_reservations ; 		// nombre de réservations
var $retard = 0  ;     		// le lecteur a-t-il du retard
var $empr_header="";		// un raccourci du genre NOM Prénom

var $fiche = ''        ;    // code HTML de la fiche lecteur
var $fiche_affichage = ''        ;    // code HTML de la fiche lecteur, lecture seule, allégée, pas de bouton
var $serious_message=FALSE;    // niveau du message (sérieux si TRUE)
var $fiche_compte="";	// code HTML d'un compte


	
// constructeur------------------------------------------------------------
function empr_affichage($id_empr, $message=array(), $type_fiche=0, $liens=array()) {
  	// $id_empr  = id de la fiche à afficher
  	// $message[]= message à insérer dans la fiche pour alerte
  	//			-$message[message]=			texte du message
  	//			-$message[niveau_message]=	niveau d'alerte du message
  	// $type_fiche= pour faire une fiche plus ou moins longue

  	if(!$id_empr) return;
		else {
			$this->id_empr = $id_empr;
			$this->fetch_data();
			}
	$this->liens=$liens ;
}

// récupération des valeurs en table---------------------------------------
function fetch_data() {
	global $dbh;
	global $msg;
	global $charset;
	global $val_list_empr;
	global $pmb_gestion_financiere, $pmb_gestion_abonnement,$pmb_gestion_tarif_prets,$pmb_gestion_amende;
	global $deflt_docs_location ;
	
	if(!$this->id_empr || !$dbh)
		return FALSE;

	$requete = "SELECT e.*, c.libelle AS code1, s.libelle AS code2, es.statut_libelle AS empr_statut_libelle, allow_loan, allow_book, allow_opac, allow_dsi, allow_dsi_priv, allow_sugg, allow_prol, d.location_libelle as localisation, date_format(empr_date_adhesion, '".$msg["format_date"]."') as aff_empr_date_adhesion, date_format(empr_date_expiration, '".$msg["format_date"]."') as aff_empr_date_expiration FROM empr e left join docs_location as d on e.empr_location=d.idlocation, empr_categ c, empr_codestat s, empr_statut es ";
	$requete .= " WHERE e.id_empr='".$this->id_empr."' " ;
	$requete .= " AND c.id_categ_empr=e.empr_categ";
	$requete .= " AND s.idcode=e.empr_codestat";
	$requete .= " AND es.idstatut=e.empr_statut";
	$requete .= " LIMIT 1";
	$result = mysql_query($requete, $dbh) or die (mysql_error()." ".$requete) ;
	if(!mysql_num_rows($result))
		return FALSE;

	$empr = mysql_fetch_object($result);

	// affectation des propriétés
	$this->empr_cb        = $empr->empr_cb           ;    // code barre emprunteur
	$this->empr_nom       = $empr->empr_nom          ;    // nom emprunteur
	$this->empr_prenom    = $empr->empr_prenom       ;    // prénom mprunteur
	$this->empr_adr1      = $empr->empr_adr1         ;    // adresse ligne 1
	$this->empr_adr2      = $empr->empr_adr2         ;    // adresse ligne 2
	$this->empr_cp        = $empr->empr_cp           ;    // code postal
	$this->empr_ville     = $empr->empr_ville        ;    // ville
	$this->empr_pays      = $empr->empr_pays         ;    // ville
	$this->empr_mail      = $empr->empr_mail         ;    // adresse email
	$this->empr_tel1      = $empr->empr_tel1         ;    // téléphone 1
	$this->empr_tel2      = $empr->empr_tel2         ;    // téléphone 2
	$this->empr_prof      = $empr->empr_prof         ;    // profession
	$this->empr_year      = $empr->empr_year         ;    // année de naissance
	$this->empr_categ     = $empr->empr_categ        ;    // catégorie emprunteur
	$this->empr_codestat  = $empr->empr_codestat     ;    // code statistique
	$this->empr_creation  = $empr->empr_creation     ;    // date de création
	$this->empr_modif     = $empr->empr_modif        ;    // date de modification
	$this->empr_sexe      = $empr->empr_sexe         ;    // sexe de l'emprunteur
	$this->empr_login     = $empr->empr_login        ;    // login pour services OPAC
	$this->empr_password  = $empr->empr_password     ;    // mot de passe OPAC
	$this->empr_ldap	  =$empr->empr_ldap;
	$this->type_abt	 	  = $empr->type_abt			 ;				 // type d'abonnement
	$this->empr_location  = $empr->empr_location; // localisation
	$this->empr_location_l= $empr->localisation; // localisation
	$this->date_fin_blocage= $empr->date_fin_blocage; // Date de fin de blocage de l'emprunteur
	$this->empr_statut= $empr->empr_statut;
	$this->empr_statut_l  = $empr->empr_statut_libelle;
	$this->total_loans= $empr->total_loans;

	$this->date_adhesion     	= $empr->empr_date_adhesion        ;    // début adhésion
	$this->date_expiration     	= $empr->empr_date_expiration      ;    // fin adhésion
	$this->aff_date_adhesion    = $empr->aff_empr_date_adhesion    ;    // début adhésion
	$this->aff_date_expiration	= $empr->aff_empr_date_expiration  ;    // fin adhésion
	$this->empr_msg     		= $empr->empr_msg            ;    // message emprunteur
	$this->cat_l        		= $empr->code1               ;    // libellé catégorie emprunteur
	$this->cstat_l      		= $empr->code2               ;    // libellé code statistique. voir ce bug avec Eric



	$this->allow_loan        =$empr->allow_loan;   
	$this->allow_book        =$empr->allow_book;   
	$this->allow_opac        =$empr->allow_opac;   
	$this->allow_dsi         =$empr->allow_dsi;    
	$this->allow_dsi_priv    =$empr->allow_dsi_priv;
	$this->allow_sugg        =$empr->allow_sugg;    
	$this->allow_prol        =$empr->allow_prol;    

	
	global $selector_prop_ajout_caddie_empr, $empr_show_caddie ;
	if ($empr_show_caddie) {
		$this->img_ajout_empr_caddie="<img src='./images/basket_empr.gif' align='middle' alt='basket' title=\"${msg[400]}\" onClick=\"openPopUp('./cart.php?object_type=EMPR&item=".$this->id."', 'cart', 600, 700, -2, -2, '$selector_prop_ajout_caddie_empr')\">";
	} else 
		$this->img_ajout_empr_caddie="";
	$this->lien_nom_prenom="<a href='./circ.php?categ=pret&form_cb=".rawurlencode($this->cb)."'>$this->nom,&nbsp;$this->prenom</a>";
	
	$date_blocage=array();
	$date_blocage=explode("-",$this->date_fin_blocage);
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

	//Paramètres perso
	//Liste des champs
	$p_perso=new parametres_perso("empr");
	$perso_=$p_perso->show_fields($this->id_empr);
	$perso="";
	$class="colonne3";
	$c=0;
	if (count($perso_["FIELDS"])) {
		for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
			$p=$perso_["FIELDS"][$i];
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
	$this->perso=$perso;

	//Comptes si gestion financiere
	if ($pmb_gestion_financiere) {
		$compte="";
		$n_c=0;
		$neg="<span class='erreur'>%s</span>";
		$pos="%s";
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

	} // fin fetch_data
	
	
function fetch_emprunts() {
	global $dbh ;	
	// récupération du tableau des exemplaires empruntés
	// il nous faut : code barre exemplaire, titre/auteur, type doc, date de prêt, date de retour
	$requete = "select e.expl_cb, e.expl_id, e.expl_notice, e.expl_bulletin, p.pret_date, p.pret_retour, t.tdoc_libelle, date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, if (pret_retour< CURDATE(),1 ,0 ) as retard , date_format(retour_initial, '".$msg["format_date"]."') as aff_retour_initial, cpt_prolongation";
	$requete .= " from pret p, exemplaires e, docs_type t";
	$requete .= " where p.pret_idempr=".$this->id_empr;
	$requete .= " and p.pret_idexpl=e.expl_id";
	$requete .= " and t.idtyp_doc=e.expl_typdoc";
	$requete .= " order by p.pret_retour, p.pret_date, e.expl_cb";

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

} // fin fetch_emprunts


// génération du de l'affichage simple sans onglet ----------------------------------------------
//	si $depliable=1 alors inclusion du parent / child
function genere_simple($depliable=1) {
	global $msg; 
	global $dbh;
	
	if ($depliable) { 
		$template="
		<div id=\"el!!id_empr!!Parent\" class=\"notice-parent\">
			$case_a_cocher
    		<img class='img_plus' src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el!!id_empr!!Img\" title=\"".$msg["expandable_empr"]."\" border=\"0\" onClick=\"expandBase('el!!id_empr!!', true); return false;\" hspace=\"3\">";
		$template.="
    		<span class=\"notice-heada\">!!heada!!</span><br />
    		</div>			
		<div id=\"el!!id_empr!!Child\" class=\"notice-child\" style=\"margin-bottom:6px;display:none;\">".$basket."!!EMPR_DESC!!\n
			!!SUITE!!
			</div>";
	} else {
			$template="
			\n<div id=\"el!!id_empr!!Parent\" class=\"parent\">
    				$case_a_cocher";
			$template.="
    				<span class=\"heada\">!!heada!!</span><br />
	    			</div>			
			\n<div id='el!!id_empr!!Child' class='child' >".$basket."
			!!EMPR_DESC!!
			!!SUITE!!
			</div>";
	}
		
	
	$this->result = str_replace('!!id_empr!!', $this->id_empr, $template);
	$this->result = str_replace('!!heada!!', $this->empr_header, $this->result);
	
	$this->do_image($this->empr_desc,$depliable);
	$this->result = str_replace('!!EMPR_DESC!!', $this->empr_desc, $this->result);

	if ($this->affichage_resa_expl) 
		$this->result = str_replace('!!SUITE!!', $this->affichage_resa_expl, $this->result);
	else 
		$this->result = str_replace('!!SUITE!!', '', $this->result);
			
	}

// génération de l'isbd----------------------------------------------------
function do_empr_desc($short=0,$ex=1) {
	global $dbh;
	global $msg;
	global $charset;
	
	$this->empr_desc="ICI CE QUE JE VEUX DE L'EMPRUNTEUR";
	
	
	if (!$short) {
		$this->empr_desc .="<table>";
		$this->empr_desc .= $this->aff_suite() ;
		$this->empr_desc .="</table>";
	} 

	
	if ($ex) 
		$this->affichage_resa_expl = $this->aff_resa_expl() ;
}	


// génération du header----------------------------------------------------
function do_header() {
	
	$this->empr_header = $this->empr_nom." ".$this->empr_prenom;
}

// fonction d'affichage des exemplaires et réservations
function aff_resa_expl() {
	global $msg;
	global $dbh;
	
	// afin d'éviter de recalculer un truc déjà calculé...
	if ($this->affichage_resa_expl) 
		return $this->affichage_resa_expl ;
	
	$this->affichage_resa_expl = "<h3>Mettre ici la liste des emprunts et réservations";
} 


// fonction d'affichage de la suite
function aff_suite() {
	global $msg;
	global $charset;
	global $mode;
	
	// afin d'éviter de recalculer un truc déjà calculé...
	if ($this->affichage_suite) return $this->affichage_suite ;
	
	//Espace
	$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
	
	//Champs personalisés
	$perso_aff .="<tr><td align='right' class='bg-grey'><span class='etiq_champ'>NOM DU CHAMP PERSO</span></td><td>contenu du champ perso</td></tr>";	
	if ($perso_aff) {
		//Espace
		$ret.="<tr class='tr_spacer'><td colspan='2' class='td_spacer'>&nbsp;</td></tr>";
		$ret .= $perso_aff ;
	}
	
	$this->affichage_suite = $ret ;
	return $ret ;
} 

function do_image(&$entree,$depliable) {

	global $empr_pics_url ;

	if ($empr_pics_url) {
		$code_chiffre = pmb_preg_replace('/ /', '', $this->empr_cb);
		$url_image = $empr_pics_url ;
		$url_image = "./getimage.php?url_image=".urlencode($url_image)."&empr_pic=1" ;
			if ($depliable) 
				$image = "<img src='./images/vide.png' align='right' hspace='4' vspace='2' isbn='".$code_chiffre."' url_image='".$url_image."'>";
			else {
				$url_image_ok = str_replace("%21%21num_carte%21%21", $code_chiffre, $url_image) ;
				$image = "<img src='".$url_image_ok."' align='right' hspace='4' vspace='2'>";
			}
		} else 
			$image="" ;
		if ($image) {
			$entree = "<table width='100%'><tr><td>$entree</td><td valign=top align=right>$image</td></tr></table>" ;
		} else {
			$entree = "<table width='100%'><tr><td>$entree</td></tr></table>" ;
		}
			
	}

}
