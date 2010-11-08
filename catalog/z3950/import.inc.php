<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: import.inc.php,v 1.31 2009-11-30 10:39:25 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// définition du minimum nécéssaire 
include("$include_path/marc_tables/$lang/empty_words");
include("$class_path/iso2709.class.php");
include("$class_path/author.class.php");
include("$class_path/serie.class.php");
include("$class_path/editor.class.php");
include("$class_path/collection.class.php");
include("$class_path/subcollection.class.php");
include("$class_path/origine_notice.class.php");
include("$class_path/audit.class.php");
require ("notice.inc.php");
include("$class_path/expl.class.php");
include("$include_path/templates/expl.tpl.php");

include ("$class_path/z3950_notice.class.php");

if ($notice_org) {
	$requete="select z_marc,fichier_func from z_notices, z_bib where znotices_id='".$notice_org."' and znotices_bib_id=bib_id";
	$resultat=mysql_query($requete);
	$notice_org=@mysql_result($resultat,0,0);
	$modele=@mysql_result($resultat,0,1);
}

if (!$modele) {
	if ($z3950_import_modele) {
		require_once($base_path."/catalog/z3950/".$z3950_import_modele);
	} else {
		require_once("func_other.inc.php");
	}
} else {
	require_once($base_path."/catalog/z3950/".$modele);
}

if (!$id_notice) {
	print "<h1>$msg[z3950_integr_catal]</h1>";
} else {
	print "<h1>$msg[notice_z3950_remplace_catal]</h1>";
}

$resultat=mysql_query("select znotices_id, znotices_bib_id, isbd, isbn, titre, auteur, z_marc from z_notices where znotices_id='$znotices_id' AND znotices_query_id='$last_query_id'");

$integration_OK="";
$integrationexpl_OK="";

while (($ligne=mysql_fetch_array($resultat))) {
	//$id_notice=$ligne["znotices_id"];	
	$znotices_id=$ligne["znotices_id"];
	
	/* récupération du format des notices retournées par la bib */
	$znotices_bib_id=$ligne["znotices_bib_id"];
	$rqt_bib_id=mysql_query("select format from z_bib where bib_id='$znotices_bib_id'");
	while (($ligne_format=mysql_fetch_array($rqt_bib_id))) {
		$format=$ligne_format["format"];
	}

	$resultat_titre=$ligne["titre"];
	$resultat_auteur=$ligne["auteur"];
	$resultat_isbd=$ligne["isbd"];
	$test_resultat++;
	$lien = $resultat_titre." / ".$resultat_auteur;
	print pmb_bidi(zshow_isbd($resultat_isbd, $lien));

	if ($action != "integrerexpl") { 
		if ($source == 'form') {
			$notice = new z3950_notice ('form');
		} else {
			// avant affichage du formulaire : détecter si notice déjà présente pour proposer MAJ
			$isbn_verif = traite_code_isbn($ligne['isbn']) ;
			$suite_rqt="";
			if (isISBN($isbn_verif)) {
				if (strlen($isbn_verif)==13)
					$suite_rqt=" or code='".formatISBN($isbn_verif,13)."' ";
				else $suite_rqt="or code='".formatISBN($isbn_verif,10)."' ";
			}
			if ($isbn_verif) {
				$requete = "SELECT notice_id FROM notices WHERE code='$isbn_verif' ".$suite_rqt;
				$myQuery = mysql_query($requete, $dbh);
				$temp_nb_notice = mysql_num_rows($myQuery) ;
				if ($temp_nb_notice) $not_id = mysql_result($myQuery, 0 ,0) ;
					else $not_id=0 ;
			}
			// if ($not_id) METTRE ICI TRAITEMENT DU CHOIX DU DOUBLON echo "<script> alert('Existe déjà'); </script>" ;
			$notice = new z3950_notice ($format, $ligne['z_marc']);
		}
	}
	
	$integration_OK="PASFAIT";
	$integrationexpl_OK="PASFAIT";
	switch ($action) {
		case "integrer" :
			if (!$id_notice) {
				$res_integration = $notice->insert_in_database();
			} else {
				$res_integration = $notice->update_in_database($id_notice);
			}
			$new_notice=$res_integration[0];
			$num_notice=$res_integration[1];
			if (($new_notice==0) && ($num_notice==0)) $integration_OK="ECHEC"; 
			if (($new_notice==0) && ($num_notice!=0)) $integration_OK="EXISTAIT"; 
			if (($new_notice==1) && ($num_notice!=0)) $integration_OK="OK"; 
			if (($new_notice==2) && ($num_notice!=0)) $integration_OK="UPDATE_OK"; 
			if (($new_notice==1) && ($num_notice==0)) $integration_OK="NEWRATEE"; 
			break;

		case "integrerexpl" :
			if ($notice_nbr == 0) {
				$integration_OK = "ECHEC";
			} else {
				$integration_OK = "OK";
				$num_notice = $notice_nbr;
				$formlocid="f_ex_section".$f_ex_location ;
				$f_ex_section=$$formlocid;
				$res_integrationexpl = create_expl($f_ex_cb, $num_notice, $f_ex_typdoc, $f_ex_cote, $f_ex_section, $f_ex_statut, $f_ex_location, $f_ex_cstat, $f_ex_note, $f_ex_prix, $f_ex_owner, $f_ex_comment );
				$new_expl=$res_integrationexpl[0];
				$num_expl=$res_integrationexpl[1];
				if (($new_expl==0) && ($num_expl==0)) $integrationexpl_OK="ECHEC"; 
				if (($new_expl==0) && ($num_expl!=0)) $integrationexpl_OK="EXISTAIT"; 
				if (($new_expl==1) && ($num_expl!=0)) $integrationexpl_OK="OK"; 
				if (($new_expl==1) && ($num_expl==0)) $integrationexpl_OK="NEWRATEE"; 
			}
			break;
		}
		/* ----------------------------------- */

	$msg[z3950_integr_expl_ok]       = str_replace ("!!f_ex_cb!!", $f_ex_cb, $msg[z3950_integr_expl_ok]      );
	$msg[z3950_integr_expl_existait] = str_replace ("!!f_ex_cb!!", $f_ex_cb, $msg[z3950_integr_expl_existait]);
	$msg[z3950_integr_expl_newrate]  = str_replace ("!!f_ex_cb!!", $f_ex_cb, $msg[z3950_integr_expl_newrate] );
	$msg[z3950_integr_expl_echec]    = str_replace ("!!f_ex_cb!!", $f_ex_cb, $msg[z3950_integr_expl_echec]   );

	switch ($integrationexpl_OK) {
		case "OK" :
			print "<hr /><strong>$msg[z3950_integr_expl_ok]</strong>&nbsp;<a id='liensuite' href=\"javascript:top.document.location='./catalog.php?categ=edit_expl&id=$num_notice&cb=$f_ex_cb'\">$msg[z3950_integr_expl_levoir]</a>";
			print "<script type='text/javascript'>document.getElementById('liensuite').focus();</script>" ;
			break;
		case "EXISTAIT" :
			print "<hr /><strong>$msg[z3950_integr_expl_existait]</strong>&nbsp;<a id='liensuite' href=\"javascript:top.document.location='./catalog.php?categ=edit_expl&id=$num_notice&cb=$f_ex_cb'\">$msg[z3950_integr_expl_levoir]</a>";
			print "<script type='text/javascript'>document.getElementById('liensuite').focus();</script>" ;
			break;
		case "NEWRATE" :
			print "<hr /><strong>$msg[z3950_integr_expl_newrate]</strong>";
			break;
		case "ECHEC" :
			print "<hr /><strong>$msg[z3950_integr_expl_echec]</strong>";
			break;
	}
	
	switch ($integration_OK) {
		case "OK" :
			if($notice->perio_id && $notice->bull_id)
				$url_view = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$notice->bull_id&art_to_show=$num_notice";
			else $url_view = "./catalog.php?categ=isbd&id=".$num_notice;
			print "<hr />
					<span class='msg-perio'>".$msg[z3950_integr_not_ok]."</span>
					&nbsp;<a id='liensuite' href=\"javascript:top.document.location='$url_view'\">$msg[z3950_integr_not_lavoir]</a>";
			print "<script type='text/javascript'>document.getElementById('liensuite').focus();</script>" ;
			break;
		case "UPDATE_OK" :
			print "<hr />
					<span class='msg-perio'>".$msg[z3950_update_not_ok]."</span>
					&nbsp;<a id='liensuite' href=\"javascript:top.document.location='./catalog.php?categ=isbd&id=$num_notice'\">$msg[z3950_integr_not_lavoir]</a>";
			print "<script type='text/javascript'>document.getElementById('liensuite').focus();</script>" ;
			break;
		case "EXISTAIT" :
			if ($action=="integrer") {
				print "<hr />
					<span class='msg-perio'>".$msg[z3950_integr_not_existait]."</span>
					&nbsp;<a id='liensuite' href=\"javascript:top.document.location='./catalog.php?categ=isbd&id=$num_notice'\">$msg[z3950_integr_not_lavoir]</a>";
				print "<script type='text/javascript'>document.getElementById('liensuite').focus();</script>" ;
			}
			break;
		case "NEWRATE" :
			if ($action=="integrer") print "<hr />
					<span class='msg-perio'>".$msg[z3950_integr_not_newrate]."</span>";
			break;
		case "ECHEC" :
			if ($action=="integrer") print "<hr />
					<span class='msg-perio'>".$msg[z3950_integr_not_echec]."</span>";
			break;
	}

	if ($integration_OK == "PASFAIT") {
		echo $notice->get_form ("./catalog.php?categ=z3950&".
			"znotices_id=$znotices_id&last_query_id=$last_query_id&action=integrer&source=form&".
			"tri1=$tri1&tri2=$tri2", $id_notice);
	}
	if (($integration_OK == "OK") | ($integration_OK == "EXISTAIT") | ($integration_OK == "UPDATE_OK")) {
		print "<hr />
					<span class='right'><a id='liensuite' href='./catalog.php?categ=z3950&action=display&last_query_id=".$last_query_id."&tri1=auteur&tri2=auteur'>".$msg[z3950_retour_a_resultats]."</a></span>";
		print "<script type='text/javascript'>document.getElementById('liensuite').focus();</script>" ;
					
		//$nex = new exemplaire('', 0, $num_notice);
		//$nex->zexpl_form ('./catalog.php?categ=z3950&znotices_id='.$znotices_id.'&last_query_id='.$last_query_id.'&action=integrerexpl&notice_nbr='.$num_notice.'&tri1='.$tri1.'&tri2='.$tri2);
	}
	
} /* fin while */
