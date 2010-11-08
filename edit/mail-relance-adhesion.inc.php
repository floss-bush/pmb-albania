<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mail-relance-adhesion.inc.php,v 1.19 2009-11-06 16:17:21 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// popup de mail de relance d'adhésion
/* reçoit : id_empr et éventuellement cb_doc */

require_once("$class_path/emprunteur.class.php");
require_once($include_path."/mail.inc.php") ;

// l'objet du mail
$var = "mailrelanceadhesion_objet";
eval ("\$objet=\"".$$var."\";");

// la formule de politesse du bas (le signataire)
$var = "mailrelanceadhesion_fdp";
eval ("\$fdp=\"".$$var."\";");

// le "Madame, Monsieur," ou tout autre truc du genre "Cher adhérent,"
$var = "mailrelanceadhesion_madame_monsieur";
eval ("\$madame_monsieur=\"".$$var."\";");

// le texte
$var = "mailrelanceadhesion_texte";
eval ("\$texte=\"".$$var."\";");

if ($action=="print_all") {
	// restriction localisation le cas échéant
	if ($pmb_lecteurs_localises) {
		if ($empr_location_id=="") $empr_location_id = $deflt2docs_location ;
		if ($empr_location_id!=0) $restrict_localisation = " AND empr_location='$empr_location_id' ";
			else $restrict_localisation = "";
	}

	// filtré par un statut sélectionné
	if ($empr_statut_edit) {
		if ($empr_statut_edit!=0) $restrict_statut = " AND empr_statut='$empr_statut_edit' ";
			else $restrict_statut="";
	} 
	$requete = "SELECT empr.id_empr  FROM empr, empr_statut ";
	$restrict_empr = " WHERE 1 ";
	$restrict_requete = $restrict_empr.$restrict_localisation.$restrict_statut." and ".$restricts;
	$requete .= $restrict_requete;
	$requete.=" and empr_mail!=''";
	$requete .= " and empr_statut=idstatut";
	$requete .= " ORDER BY empr_nom, empr_prenom ";
	
	$res = @mysql_query($requete, $dbh);
	
	while(($empr=mysql_fetch_object($res))) {
		// mettre ici le texte 
		$coords = new emprunteur($empr->id_empr,'', FALSE, 0);
		$texte_mail='';
		if($madame_monsieur) $texte_mail = $madame_monsieur.'\r\n\r\n';
		$texte_mail.=$texte.'\r\n';
		if($fdp) $texte_mail.= $fdp."\r\n\r\n";
		$texte_mail.=mail_bloc_adresse();

		$texte_mail = str_replace("!!date_fin_adhesion!!", $coords->aff_date_expiration, $texte_mail);
		
		//remplacement nom et prenom
		$texte_mail=str_replace("!!empr_name!!", $coords->nom,$texte_mail); 
		$texte_mail=str_replace("!!empr_first_name!!", $coords->prenom,$texte_mail); 
		
		$headers .= "Content-type: text/plain; charset=".$charset."\n";
	
		$res_envoi=mailpmb($coords->prenom." ".$coords->nom, $coords->mail, $objet, $texte_mail, $biblio_name, $biblio_email, $headers, "", $PMBuseremailbcc,1);

		if ($res_envoi) echo "<center><h3>".sprintf($msg["mail_retard_succeed"],$coords->mail)."</h3><br /><a href=\"\" onClick=\"self.close(); return false;\">".$msg["mail_retard_close"]."</a></center><br /><br />".nl2br($texte);
			else echo "<center><h3>".sprintf($msg["mail_retard_failed"],$coords->mail)."</h3><br /><a href=\"\" onClick=\"self.close(); return false;\">".$msg["mail_retard_close"]."</a></center>";
	}
	mysql_free_result($res);
} else {
	// mettre ici le texte 
	$coords = new emprunteur($id_empr,'', FALSE, 0);
	if($madame_monsieur) $texte_mail = $madame_monsieur.'\r\n\r\n';
	$texte_mail.=$texte.'\r\n';
	if($fdp) $texte_mail.= $fdp."\r\n\r\n";
	$texte_mail.=mail_bloc_adresse() ;

	$texte_mail = str_replace("!!date_fin_adhesion!!", $coords->aff_date_expiration, $texte_mail);
	
	//remplacement nom et prenom
	$texte_mail=str_replace("!!empr_name!!", $coords->nom,$texte_mail); 
	$texte_mail=str_replace("!!empr_first_name!!", $coords->prenom,$texte_mail); 
	
	$headers .= "Content-type: text/plain; charset=".$charset."\n";
	
	$res_envoi=mailpmb($coords->prenom." ".$coords->nom, $coords->mail, $objet, $texte_mail, $biblio_name, $biblio_email, $headers, "", $PMBuseremailbcc,1);

	if ($res_envoi) echo "<center><h3>".sprintf($msg["mail_retard_succeed"],$coords->mail)."</h3><br /><a href=\"\" onClick=\"self.close(); return false;\">".$msg["mail_retard_close"]."</a></center><br /><br />".nl2br($texte);
		else echo "<center><h3>".sprintf($msg["mail_retard_failed"],$coords->mail)."</h3><br /><a href=\"\" onClick=\"self.close(); return false;\">".$msg["mail_retard_close"]."</a></center>";
}