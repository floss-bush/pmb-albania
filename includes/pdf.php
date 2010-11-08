<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pdf.php,v 1.33 2010-05-21 15:20:46 mbertin Exp $

// définition du minimum nécéssaire 
$base_path=".";                            
$base_auth = "CATALOGAGE_AUTH|CIRCULATION_AUTH|EDIT_AUTH|ACQUISITION_AUTH";  
$base_title = "PDF";
$base_noheader=1;
$base_nosession = 0 ; // pas d'envoi de cookie avant l'entête PDF
require_once ("$base_path/includes/init.inc.php");  

//Appliquons un eventuel fichier de substitution de paramètres en fonction de la localisation de l'utilisateur courant
require_once("$class_path/parameters_subst.class.php");
$subst_filename = $include_path.'/parameters_subst/per_localisations.xml';
$parameter_subst = new parameters_subst($subst_filename, $deflt2docs_location);
$parameter_subst->extract();

// modules propres à pdf.php ou à ses sous-modules
require_once("$include_path/fpdf.inc.php");

require_once("$include_path/misc.inc.php");
require_once("$class_path/author.class.php");
require_once("$include_path/notice_authors.inc.php");
require_once("$include_path/notice_categories.inc.php");
require_once("$base_path/circ/pret_func.inc.php");

// pour les champs perso
require_once("$include_path/fields_empr.inc.php");
require_once("$include_path/datatype.inc.php");
require_once("$include_path/parser.inc.php");

// inclusion de la classe de gestion des impressions PDF
// Definition de la police si pas définie dans les paramètres
if (!$pmb_pdf_font) $pmb_pdf_font = 'pmb'; 
if (!$pmb_pdf_fontfixed) $pmb_pdf_fontfixed = 'pmbmono'; 
define('FPDF_FONTPATH',"$class_path/font/");
require_once("$class_path/fpdf.class.php");
require_once("$class_path/ufpdf.class.php");

switch ($pdfdoc) {
	case 'ticket_pret':
		if($pmb_printer_ticket_script && $cb_doc) $script_perso_file=$pmb_printer_ticket_script;
		else $script_perso_file	= "./circ/ticket-pret.inc.php";
		if(SESSrights & CIRCULATION_AUTH) include($script_perso_file);
			else echo "<script> self.close(); </script>" ;
		break;
	case 'liste_pret':
		if(SESSrights & CIRCULATION_AUTH) include("./edit/liste_pret.inc.php");
			else echo "<script> self.close(); </script>" ;
		break;
	case 'lettre_retard':
		if ($niveau) $relance=$niveau; else $relance=1;
		if((SESSrights & EDIT_AUTH) || (SESSrights & CIRCULATION_AUTH))  include("./edit/lettre-retard.inc.php");
			else echo "<script> self.close(); </script>" ;
		break;
	case 'lettre_resa':
		if(SESSrights & CIRCULATION_AUTH) include("./edit/lettre-resa.inc.php");
			else echo "<script> self.close(); </script>" ;
		break;
	case 'lettre_resa_planning':
		if(SESSrights & CIRCULATION_AUTH) include("./edit/lettre-resa_planning.inc.php");
			else echo "<script> self.close(); </script>" ;
		break;
	case 'lettre_retard_groupe':
		$relance=1;
		if(SESSrights & EDIT_AUTH) include("./edit/lettre-retard.inc.php");
			else echo "<script> self.close(); </script>" ;
		break;
	case 'liste_pret_groupe':
		if(SESSrights & EDIT_AUTH) include("./edit/liste_prets.inc.php");
			else echo "<script> self.close(); </script>" ;
		break;
	case 'lettre_relance_adhesion':
		if(SESSrights & EDIT_AUTH) include("./edit/lettre-relance-adhesion.inc.php");
			else echo "<script> self.close(); </script>" ;
		break;
	case 'fiche_catalographique':
		if((SESSrights & CATALOGAGE_AUTH) || (SESSrights & CIRCULATION_AUTH) ) include("./edit/fiche_catalographique.inc.php");
			else echo "<script> self.close(); </script>" ;
		break;
	case 'carte-lecteur':
		if(SESSrights & CIRCULATION_AUTH) {
			require("$class_path/fpdf_carte_lecteur.class.php");
			include("./circ/carte-lecteur.inc.php");
			} else echo "<script> self.close(); </script>" ;
		break;
	case 'cmde':
		if(SESSrights & ACQUISITION_AUTH) {
			include("./acquisition/achats/commandes/lettre-commande.inc.php");
			} else echo "<script> self.close(); </script>" ;	
		break;		
	case 'devi':
		if(SESSrights & ACQUISITION_AUTH) {
			include("./acquisition/achats/devis/lettre-devis.inc.php");
			} else echo "<script> self.close(); </script>" ;	
		break;		
	case 'livr':
		if(SESSrights & ACQUISITION_AUTH) {
			include("./acquisition/achats/livraisons/lettre-livraison.inc.php");
			}	
		break;		
	case 'fact':
		if(SESSrights & ACQUISITION_AUTH) {
			include("./acquisition/achats/factures/lettre-facture.inc.php");
			}	
		break;		
	case 'listsug':
		if(SESSrights & ACQUISITION_AUTH) {
			include("./acquisition/suggestions/liste-suggestions.inc.php");
			}	
		break;		
	case 'liste_bulletinage':
		if(SESSrights & CIRCULATION_AUTH) include("./edit/liste_bulletinage.inc.php");
			else echo "<script> self.close(); </script>" ;
		break;
	case 'lettre_retard_fournisseur':
		if ($niveau) $relance=$niveau; else $relance=1;
		if(SESSrights & EDIT_AUTH) include("./edit/lettre_retard_fournisseur.php");
			else echo "<script> self.close(); </script>" ;
		break;		
	default:
		echo "<script> self.close(); </script>" ;
		break;
}

mysql_close($dbh);
