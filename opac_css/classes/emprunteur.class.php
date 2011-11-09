<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: emprunteur.class.php,v 1.18 2011-02-02 20:08:44 gueluneau Exp $

// classe emprunteur
//	inclure :
//	./classes/notice_display.class.php

if ( ! defined( 'EMPR_CLASS' ) ) {
  define( 'EMPR_CLASS', 1 );

class emprunteur {

//---------------------------------------------------------
//			Propriétés
//---------------------------------------------------------
var $id		= 0		;	// id MySQL emprunteur
var $cb		= ''	;	// code barre emprunteur
var $nom	= ''	;	// nom emprunteur
var $prenom	= ''	;	// prénom emprunteur
var $adr1	= ''	;	// adresse ligne 1
var $adr2	= ''	;	// adresse ligne 2
var $cp		= ''	;	// code postal
var $ville	= ''	;	// ville
var $mail	= ''	;	// adresse email
var $tel1	= ''	;	// téléphone 1
var $tel2	= ''	;	// téléphone 2
var $prof	= ''	;	// profession
var $birth	= ''	;	// année de naissance
var $categ 	= 0		;	// catégorie emprunteur
var $cat_l	= ''	;	// libellé catégorie emprunteur
var $cstat	= 0		;	// code statistique
var $cstat_l= 0		;	// libellé code statistique
var $cdate	= ''	;	// date de création
var $mdate	= ''	;	// date de modification
var $adate	= ''	;	// date d'abonnement
var $rdate	= ''	;	// date de réabonnement
var $sexe	= 0		;	// sexe de l'emprunteur
var $login	= ''	;	// login pour services OPAC
var $pwd 	= ''	;	// mot de passe OPAC
var $date_adhesion   = ''	;	// début adhésion
var $date_expiration = ''	;	// fin adhésion
var $aff_date_adhesion   = '';	// début adhésion formatée
var $aff_date_expiration = '';	// fin adhésion formatée
var $prets			;	// array contenant les prêts de l'emprunteur
var $reservations	;	// array contenant les réservations pour l'emprunteur
var $message = ''	;	// chaîne contenant les messages emprunteurs
var $fiche = ''		;	// code HTML de la fiche lecteur
var $serious_message=FALSE;	// niveau du message (sérieux si TRUE)

// <----------------- constructeur ------------------>
function emprunteur($id=0, $message='', $niveau_message=FALSE) {

	// initialisation des propriétés si l'id est défini
	if($id) {
		$this->id = $id;
		$this->serious_message = $niveau_message;
		$this->prets = array();
		$this->reservations = array();
		$this->fetch_info();
		$this->message = $message;
		$this->do_fiche();
		}
	}

//   renseignement des propriétés avec requête MySQL
function fetch_info() {
	global $msg ;
	global $dbh;

	if(!$this->id || !$dbh)
		return FALSE;

	$requete = "SELECT e.*, c.libelle AS code1, s.libelle AS code2, date_format(empr_date_adhesion, '".$msg["format_date_sql"]."') as aff_empr_date_adhesion, date_format(empr_date_expiration, '".$msg["format_date_sql"]."') as aff_empr_date_expiration  FROM empr e, empr_categ c, empr_codestat s";
	$requete .= " WHERE e.id_empr='".addslashes($this->id);
	$requete .= "' AND c.id_categ_empr=e.empr_categ";
	$requete .= " AND s.idcode=e.empr_codestat";
	$requete .= " LIMIT 1";
	$result = mysql_query($requete, $dbh);

	$empr = mysql_fetch_object($result);

	// affectation des propriétés
	$this->cb		= $empr->empr_cb			;	// code barre emprunteur
	$this->nom		= $empr->empr_nom			;	// nom emprunteur
	print pmb_bidi($this->nom);
	$this->prenom	= $empr->empr_prenom		;	// prénom emprunteur
	$this->adr1		= $empr->empr_adr1			;	// adresse ligne 1
	$this->adr2		= $empr->empr_adr2			;	// adresse ligne 2
	$this->cp		= $empr->empr_cp			;	// code postal
	$this->ville	= $empr->empr_ville			;	// ville
	$this->mail		= $empr->empr_mail			;	// adresse email
	$this->tel1		= $empr->empr_tel1			;	// téléphone 1
 	$this->tel2		= $empr->empr_tel2			;	// téléphone 2
	$this->prof		= $empr->empr_prof			;	// profession
	$this->birth	= $empr->empr_year			;	// année de naissance
	$this->categ 	= $empr->empr_categ			;	// catégorie emprunteur
	$this->cstat	= $empr->empr_codestat		;	// code statistique
	$this->cdate	= $empr->empr_creation		;	// date de création
	$this->mdate	= $empr->empr_modif			;	// date de modification
	$this->sexe		= $empr->empr_sexe			;	// sexe de l'emprunteur
	$this->login	= $empr->empr_login			;	// login pour services OPAC
	$this->pwd 		= $empr->empr_password		;	// mot de passe OPAC
	$this->date_adhesion 	= $empr->empr_date_adhesion		;	// début adhésion
	$this->date_expiration 	= $empr->empr_date_expiration		;	// fin adhésion
	$this->aff_date_adhesion 	= $empr->aff_empr_date_adhesion		;	// début adhésion
	$this->aff_date_expiration 	= $empr->aff_empr_date_expiration		;	// fin adhésion
	$this->cat_l	= $empr->code1				;	// libellé catégorie emprunteur
	$this->cstat_l	= $empr->code2				;	// libellé code statistique. voir ce bug avec Eric

	// ces propriétés sont absentes de la table emprunteurs pour le moment
	//	$this->message	= $empr->empr_???	;	// chaîne contenant les messages emprunteurs
	//	$this->adate	= $empr->empr_???	;	// date d'abonnement
	//	$this->rdate	= $empr->empr_???	;	// date de réabonnement
	if($this->message)
		$this->message	= $empr->message.'<hr />'.$this->message;
	else
		$this->message = $empr->message;

	// récupération du tableau des exemplaires empruntés
	// il nous faut : code barre exemplaire, titre/auteur, type doc, date de prêt, date de retour
	$requete = "select e.expl_cb, e.expl_notice, p.pret_date, p.pret_retour, t.tdoc_libelle, date_format(pret_date, '".$msg["format_date_sql"]."') as aff_pret_date, date_format(pret_retour, '".$msg["format_date_sql"]."') as aff_pret_retour, if (pret_retour< CURDATE(),1 ,0 ) as retard ";
	$requete .= " from pret p, exemplaires e, docs_type t";
	$requete .= " where p.pret_idempr=".$this->id;
	$requete .= " and p.pret_idexpl=e.expl_id";
	$requete .= " and t.idtyp_doc=e.expl_typdoc";
	$requete .= " order by p.pret_date";

	$result = mysql_query($requete, $dbh);
	while($pret = mysql_fetch_object($result)) {
		$notice = new notice_affichage($pret->expl_notice,0,0,0);
		$notice->do_header();
		$this->prets[] = array(
					cb => $pret->expl_cb,
					libelle => $notice->notice_header,
					typdoc => $pret->tdoc_libelle,
					date_pret => $pret->aff_pret_date,
					date_retour => $pret->aff_pret_retour,
					org_ret_date => str_replace('-', '', $pret->pret_retour)
					);

	}

	return TRUE;

}

// fabrication de la fiche lecteur
function do_fiche() {
	global $empr_tmpl;
	global $msg;

	$this->fiche = $empr_tmpl;
	$this->fiche = str_replace('!!cb!!'		, $this->cb		, $this->fiche);
	$this->fiche = str_replace('!!nom!!'	, pmb_strtoupper($this->nom)	, $this->fiche);
	$this->fiche = str_replace('!!prenom!!'	, $this->prenom	, $this->fiche);
	$this->fiche = str_replace('!!id!!'		, $this->id		, $this->fiche);
	$this->fiche = str_replace('!!adr1!!'	, $this->adr1	, $this->fiche);
	$this->fiche = str_replace('!!adr2!!'	, $this->adr2	, $this->fiche);
	$this->fiche = str_replace('!!tel1!!'	, $this->tel1	, $this->fiche);
	$this->fiche = str_replace('!!tel2!!'	, $this->tel2	, $this->fiche);
	$this->fiche = str_replace('!!cp!!'		, $this->cp		, $this->fiche);
	$this->fiche = str_replace('!!ville!!'	, $this->ville	, $this->fiche);
	$emails=array();
	$email_final=array();
	$emails = explode(';',$this->mail);
	for ($i=0;$i<count($emails);$i++) $email_final[] ="<a href='mailto:".$emails[$i]."'>".$emails[$i]."</a>";
	
	$this->fiche = str_replace('!!mail_all!!'	, $this->mail	, $this->fiche);
	$this->fiche = str_replace('!!prof!!'	, $this->prof	, $this->fiche);
	$this->fiche = str_replace('!!date!!'	, $this->birth	, $this->fiche);
	$this->fiche = str_replace('!!categ!!'	, $this->categ.'-'.$this->cat_l	, $this->fiche);
	$this->fiche = str_replace('!!codestat!!'	, $this->cstat.'-'.$this->cstat_l	, $this->fiche);
	$this->fiche = str_replace('!!adhesion!!'	, $this->aff_date_adhesion, $this->fiche);
	$this->fiche = str_replace('!!expiration!!'	, $this->aff_date_expiration, $this->fiche);

	if($this->serious_message) $this->fiche = str_replace('!!class_msg!!'	, 'empr-serious-msg', $this->fiche);
		else $this->fiche = str_replace('!!class_msg!!'	, 'empr-msg', $this->fiche);
	if(!$this->message) $this->message = $msg["empr_no_message_for"];
	
	$this->fiche = str_replace('!!empr_msg!!'	, $this->message	, $this->fiche);

	$fsexe[0] = $msg[128];
	$fsexe[1] = $msg[126];
	$fsexe[2] = $msg[127];

	$this->fiche = str_replace('!!sexe!!'	, $fsexe[$this->sexe], $this->fiche);

	// valeur pour les champ hidden du prêt. L'id empr est pris en charge plus haut (voir Eric)
	$this->fiche = str_replace('!!cb!!'	, $this->cb	, $this->fiche);

	// traitement liste exemplaires en prêt
	if(!sizeof($this->prets))
		// dans ce cas, le lecteur n'a rien en prêt
		$prets_list = "<tr><td class='ex-strip' colspan='5'>".$msg["empr_no_expl"]."</td></tr>";
		// voir la localisation retenue par Eric
	else {
		// constitution du code HTML
		while(list($cle, $valeur) = each($this->prets)) {
			$prets_list .= "
			<tr>
			<form name=prolong${valeur['cb']} action='circ.php'>
				<td class='strip'>
					${valeur['cb']}
				</td>
				<td class='empr-msg'>
					${valeur['libelle']}
				</td>
				<td class='strip'>
					${valeur['typdoc']}
				</td>
				<td class='strip'>
					${valeur['date_pret']}
				</td>
				<td class='strip'>
					<input type='hidden' name='categ' value='pret'>\n
					<input type='hidden' name='sub' value='pret_prolongation'>\n
					<input type='hidden' name='form_cb' value='$this->cb'>\n
					<input type='hidden' name='cb_doc' value='${valeur['cb']}'>\n
					<input type='hidden' name='date_retour' value=\"\">\n
				";
				$prets_list .="	</td>
						</form></tr>
						";
				// ouf, c'est fini ;-)
		}
	}
	$this->fiche = str_replace('!!pret_list!!'	, $prets_list	, $this->fiche);
	// mise à jour de la liste des réservations

	$this->fiche = str_replace('!!resa_list!!', $this->fetch_resa(), $this->fiche);
}

// récupération de la liste des réservations pour l'emprunteur
function fetch_resa() {
	global $dbh;
	global $msg ;

	// on commence par vérifier si l'emprunteur a des réservations
	$query = "select count(1) from resa where resa_idempr=".$this->id;
	$result = mysql_query($query, $dbh);
	if(!@mysql_result($result, 0, 0))
		return $msg["empr_no_resa"];

	// si le lecteur a réservé un ou des documents, on récupère tout
	$query = "select * from resa ";
	$query .= " where resa.resa_idempr=".$this->id;

	$result = mysql_query($query, $dbh);

	while($resa = mysql_fetch_object($result)) {
		// constitution du tableau des réservations
		// on récupère le rang du réservataire
		$rang = $this->get_rank($this->id, $resa->resa_idnotice);

		// maintenant, on s'accroche : détermination de la date à afficher dans la case retour :
		// disponible, réservé ou date de retour du premier exemplaire

		// on compte le nombre total d'exemplaires pour la notice
		$query = "select count(1) from exemplaires, docs_statut where expl_notice=".$resa->resa_idnotice;
		$query .= " and expl_statut=idstatut and pret_flag=1";
		$tresult = @mysql_query($query, $dbh);
		$total_ex = @mysql_result($tresult, 0, 0);

		// on compte le nombre total de réservations sur la notice
		$query = "select count(1) from resa where resa_idnotice=".$resa->resa_idnotice;
		$tresult = @mysql_query($query, $dbh);
		$total_resa = @mysql_result($tresult, 0, 0);

		// on compte le nombre d'exemplaires sortis
		$query = "select count(1) from exemplaires e, pret p";
		$query .= " where e.expl_notice=".$resa->resa_idnotice;
		$query .= " and p.pret_idexpl=e.expl_id";
		$tresult = @mysql_query($query, $dbh);
		$total_sortis = @mysql_result($tresult, 0, 0);

		// on en déduit le nombre d'exemplaires disponibles
		$total_dispo = $total_ex - $total_sortis;

		if($rang <= $total_dispo) {
			// un exemplaire est disponible pour le réservataire (affichage : disponible)
			$situation = "<font color='#ff0000'><strong>".$msg["available"]."</strong></font>";
		} else {
			if($total_dispo) {
				// un ou des exemplaires sont disponibles, mais pas pour ce réservataire (affichage : reservé)
				$situation = $msg["expl_reserve"];
			} else {
				// rien n'est disponible, on trouve la date du premier retour
				$query = 'select (pret_retour) from pret p, exemplaires e';
				$query .= ' where e.expl_notice='.$resa->resa_idnotice;
				$query .= ' and e.expl_id=p.pret_idexpl';
				$query .= ' order by p.pret_retour limit 1';
				$tresult = mysql_query($query, $dbh);
				$first_ret = mysql_fetch_object($tresult);
				$situation = formatdate($first_ret->pret_retour);
			}
		}

		$notice = new notice_affichage($resa->resa_idnotice,0,0,0);
		$notice->do_header();
		$affiche .= "<tr><td class='strip'>".$notice->notice_header;
		$affiche .= "<td class='strip'>$rang/$total_resa</td>";
		$affiche .= "<td class='strip'>$situation</td>";
		$del_link = "<a href='./circ.php?categ=resa&id_empr=".$this->id.'&id_notice='.$resa->resa_idnotice."&delete=1'>";
		$affiche .= "<td align='center'>$del_link<img border='0' src='./images/trash.gif'></a></td></tr>";
	}
	return $affiche;

}

// <----------------- get_rank() ------------------>
//   calcul du rang d'un emprunteur sur une réservation

function get_rank($id_empr, $id_notice) {
	global $dbh;
	$rank = 1;
	$query = "select * from resa where resa_idnotice=".$id_notice." order by resa_date";
	$result = mysql_query($query, $dbh);
	while($resa=mysql_fetch_object($result)) {
		if($resa->resa_idempr == $id_empr)
			break;
		$rank++;
	}
	return $rank;
}

} # fin de déclaration classe emprunteur

} # fin de définition
?>
