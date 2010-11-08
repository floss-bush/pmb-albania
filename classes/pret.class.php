<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pret.class.php,v 1.14 2009-05-16 11:22:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des 'prêts'

if ( ! defined( 'PRET_CLASS' ) ) {
  define( 'PRET_CLASS', 1 );

class pret {

/*---------------------------------------------------------------
		propriétés de la classe
  ---------------------------------------------------------------

	var $id_empr;				id emprunteur
	var $id_expl;				id exemplaire
	var $pret_date;				timestamp du début du pret
	var $pret_retour;			timestamp du retour prévu
	var $cb_expl;				code barre exemplaire
	var $type_doc;				type de doc de l'exemplaire
	var $titre_auteur;			titre/auteur de l'exemplaire
	var $owner				propriétaire de l'exemplaire
	var $date_pret_display;			date début du prêt en format affichable
	var $date_retour_display;		date retour prévu du prêt en format affichable
	var $resultat_action;			booléen de résultat de l'action
	var $display;				reste dispo pour l'instant

  ---------------------------------------------------------------
		pret($id_empr, $id_expl, $cb_expl, $pret_date, $pret_retour) : constructeur
			id_empr = id de l'emprunteur
			id_expl = id de l'exemplaire
			cb_expl = code barre de l'exemplaire, au choix avec l'id
			pret_date = date du début du pret
			pret_retour = date du retour prévu
  --------------------------------------------------------------*/
	var $id_empr;
	var $id_expl;
	var $pret_date;
	var $pret_retour;
	var $cb_expl;
	var $type_doc;
	var $statut_doc;
	var $titre_auteur;
	var $date_pret_display;
	var $date_retour_display;
	var $etat;
	var $display;

function pret( $id_empr, $id_expl, $cb_expl, $pret_date, $pret_retour) {

$this->id_empr=$id_empr;

if (($id_expl!=0) || ($cb_expl!="")){
	// on cherche à atteindre un prêt existant
	$this->id_expl = $id_expl;
	$this->cb_expl = $cb_expl;
	$this->getData();
	} else {
		// on n'a pas de quoi chercher le pret
		$this->id_expl = 0;
		$this->cb_expl = "";
		$this->getData();
		}
}


//	récupération infos du prêt
function getData() {
global $dbh;
global $msg;
if(($this->id_expl==0) && ($this->cb_expl=="")) {
	// aucun identifiant. on retourne un tableau vide
	$this->id_empr = 0;
	$this->id_expl = 0;
	$this->pret_date = "";
	$this->pret_retour = "";
	$this->cb_expl = "";
	$this->type_doc="";
	$this->statut_doc="";
	$this->titre_auteur="";
	$this->owner="";
	$this->date_pret_display="";
	$this->date_retour_display="";
	$this->etat=0;
	$this->display = $msg[4052];	
	} else {
		$sql_dates = " date_format(pret_date, '".$msg["format_date"]."') as aff_pret_date, ";
		$sql_dates .= " date_format(pret_retour, '".$msg["format_date"]."') as aff_pret_retour, ";
		$sql_dates .= " IF(pret_retour>sysdate(),0,1) as retard " ; 
		if ($this->id_expl!=0) $requete = "SELECT pret_idempr, pret_idexpl, pret_date, pret_retour, expl_cb, expl_typdoc, expl_statut, tit1, expl_owner, $sql_dates FROM pret, exemplaires, notices WHERE pret_idexpl='".$this->id_expl."' and pret_idexpl=expl_id and expl_notice=notice_id LIMIT 1 ";
			else $requete = "SELECT pret_idempr, pret_idexpl, pret_date, pret_retour, expl_cb, expl_typdoc, tit1, expl_owner, $sql_dates FROM pret, exemplaires, notices, authors WHERE expl_cb='".$this->cb_expl."' and pret_idexpl=expl_id and expl_notice=notice_id LIMIT 1 ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->id_empr = $temp->pret_idempr;
			$this->id_expl = $temp->pret_idexpl;
			$this->pret_date = $temp->pret_date;
			$this->pret_retour = $temp->pret_retour;
			$this->cb_expl = $temp->expl_cb;
			
			$requete = "select tdoc_libelle from docs_type where idtyp_doc='".$temp->expl_typdoc."' ";
			$result = @mysql_query($requete, $dbh);
			$typdoc = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->type_doc = $typdoc->tdoc_libelle;
			
			$requete = "select statut_libelle from docs_statut where idstatut='".$temp->expl_statut."' ";
			$result = @mysql_query($requete, $dbh);
			$statdoc = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->statut_doc = $statdoc->statut_libelle;
					
			$this->titre_auteur = $temp->tit1;

			$requete = "select lender_libelle from lenders where idlender='".$temp->expl_owner."' ";
			$result = @mysql_query($requete, $dbh);
			$lender = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->owner = $lender->lender_libelle;
			
			$this->date_pret_display=$temp->aff_pret_date;
			$this->date_retour_display=$temp->aff_pret_retour;
			$this->etat=1;
			$this->display = "Prêt existant";
			} else {
				// pas de prêt avec cette clé : on va aller chercher le expl_cb avec l'id ou l'inverse
				$long_maxi_cb_expl = mysql_field_len(mysql_query("SELECT expl_cb FROM exemplaires limit 1"),0);
				$this->cb_expl = rtrim(substr(pmb_preg_replace('/\[|\]/', '', rtrim(ltrim($this->cb_expl))),0,$long_maxi_cb_expl));

				if ($this->id_expl==0) {
					/* ici la recherche de l'id_expl */
					$query = "SELECT expl_id, expl_cb FROM exemplaires WHERE expl_cb='${key_cb_expl}' LIMIT 1 ";
					} else {
						/* ici la recherche du cb à partir de l'id */
						$query = "SELECT expl_id, expl_cb FROM exemplaires WHERE expl_id='".$this->id_expl."' LIMIT 1 ";
						}
				$result = @mysql_query($query, $dbh) or die("can't SELECT exemplaires ".$query);
				if (mysql_num_rows($result)==0) { /* on n'a trouvé aucun exemplaire */
					$this->id_empr = 0;
					$this->id_expl = 0;
					$this->pret_date = "";
					$this->pret_retour = "";
					$this->cb_expl = "";
					$this->type_doc="";
					$this->statut_doc="";
					$this->titre_auteur="";
					$this->owner = "";
					$this->date_pret_display="";
					$this->date_retour_display="";
					$this->etat=3;
					$this->display = "Exemplaire introuvable";	
					} else {
						$expl  = mysql_fetch_object($result);
						$this->id_expl = $expl->expl_id;
						$this->cb_expl = $expl->expl_cb;
						$this->pret_retour = "";
						$this->type_doc="";
						$this->statut_doc="";
						$this->titre_auteur="";
						$this->owner = "";
						$this->date_pret_display="";
						$this->date_retour_display="";
						$this->etat=2;
						$this->display = "Prêt possible, inexistant avec cette clé";	
						}
				}
		}
}


// retour prêt
function retour($retour_effectif) {
global $dbh;
global $msg;

// check sur le type de  la variable passée en paramètre
if ($retour_effectif=="") $retour_effectif=time();         

/* on a tout ce qu'il faut, on peut supprimer le prêt */

/* on va d'abord transférer tout ce que l'on connait dans la table des archives pour les stats */
$query = "SELECT pret_date debut, empr_cp, empr_ville, empr_prof, empr_year, empr_categ, empr_codestat, empr_sexe, ";
$query.= "expl_typdoc, expl_cote, expl_statut, expl_location, expl_codestat, expl_section, expl_owner FROM pret, empr, exemplaires WHERE pret_idexpl='".$this->id_expl."' and id_empr=pret_idempr and expl_id=pret_idexpl ";
$res_stat = @mysql_query($query, $dbh) or die(mysql_error()."<br />can't SELECT pret & co for stats <br />".$query."<br />");
$temp = mysql_fetch_object($res_stat);
$query = "insert into pret_archive set ";
$query.="arc_debut          ='".$temp->debut         ."', ";
$query.="arc_fin            ='".date("Y-m-d",$retour_effectif) ."', ";
$query.="arc_empr_cp        ='".addslashes($temp->empr_cp       )."', ";
$query.="arc_empr_ville     ='".addslashes($temp->empr_ville    )."', ";
$query.="arc_empr_prof      ='".addslashes($temp->empr_prof     )."', ";
$query.="arc_empr_year      ='".$temp->empr_year     ."', ";
$query.="arc_empr_categ     ='".$temp->empr_categ    ."', ";
$query.="arc_empr_codestat  ='".$temp->empr_codestat ."', ";
$query.="arc_empr_sexe      ='".$temp->empr_sexe     ."', ";
$query.="arc_expl_typdoc    ='".$temp->expl_typdoc   ."', ";
$query.="arc_expl_cote      ='".addslashes($temp->expl_cote     )."', ";
$query.="arc_expl_statut    ='".$temp->expl_statut   ."', ";
$query.="arc_expl_location  ='".$temp->expl_location ."', ";
$query.="arc_expl_section  ='".$temp->expl_section ."', ";
$query.="arc_expl_codestat  ='".$temp->expl_codestat ."', ";
$query.="arc_expl_owner     ='".$temp->expl_owner    ."', ";		
$query.="arc_niveau_relance='".	$temp->niveau_relance ."', ";
$query.="arc_date_relance='".	$temp->date_relance		."', ";
$query.="arc_printed='".		$temp->printed    		."', ";
$query.="arc_cpt_prolongation='".$temp->cpt_prolongation."' ";
@mysql_query($query, $dbh) or die(mysql_error()."<br />can't insert in pret_archive <br />".$query."<br />");

$query = "delete from pret where pret_idexpl = '".$this->id_expl."' ";
@mysql_query($query, $dbh) or die("can't delete from pret ".$query."<br />".mysql_error());
return 0;
}

// ---------------------------------------------------------------
//		annulation() : annulation violente d'un prêt
// ---------------------------------------------------------------
function annulation() {

global $dbh;
global $msg;

$query = "delete from pret where ";
$query .= "pret_idexpl = '".$this->id_expl."' ";
$result = @mysql_query($query, $dbh) or die("can't delete from pret ".$query."<br />".mysql_error());
return 0;
}
	
// ---------------------------------------------------------------
//		prolongation() : prolongation d'un prêt
// ---------------------------------------------------------------
function prolongation($nouvelle_date) {

global $dbh;
global $msg;

$query = "update pret set pret_retour = '".$nouvelle_date."' where ";
$query .= "pret_idexpl = '".$this->id_expl."' ";
$result = @mysql_query($query, $dbh) or die("can't update pret ".$query."<br />".mysql_error());
return 0;
}


} # fin de définition de la classe pret

} # fin de délaration
