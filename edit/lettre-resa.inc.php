<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lettre-resa.inc.php,v 1.5 2009-02-09 11:09:39 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// popup d'impression PDF pour lettre de confirmation de résa
/* reçoit : id_resa */
// la formule de politesse du bas (le signataire)
$var = "pdflettreresa_fdp";
eval ("\$fdp=\"".$$var."\";");

// le texte après la liste des ouvrages en résa
$var = "pdflettreresa_after_list";
eval ("\$after_list=\"".$$var."\";");

// la position verticale limite du texte after_liste (si >, saut de page et impression)
$var = "pdflettreresa_limite_after_list";
$limite_after_list = $$var;
		
// le texte avant la liste des ouvrges en réservation
$var = "pdflettreresa_before_list";
eval ("\$before_list=\"".$$var."\";");

// le "Madame, Monsieur," ou tout autre truc du genre "Cher adhérent,"
$var = "pdflettreresa_madame_monsieur";
eval ("\$madame_monsieur=\"".$$var."\";");

// le nombre de blocs notices à imprimer sur la première page
$var = "pdflettreresa_nb_1ere_page";
$nb_1ere_page = $$var;

// le nombre de blocs notices à imprimer sur les pages suivantes
$var = "pdflettreresa_nb_par_page";
$nb_par_page = $$var;

// la taille d'un bloc notices 
$var = "pdflettreresa_taille_bloc_expl";
$taille_bloc_expl = $$var;

// la position verticale du premier bloc notice sur la première page
$var = "pdflettreresa_debut_expl_1er_page";
$debut_expl_1er_page = $$var;

// la position verticale du premier bloc notice sur les pages suivantes
$var = "pdflettreresa_debut_expl_page";
$debut_expl_page = $$var;

// la marge gauche des pages
$var = "pdflettreresa_marge_page_gauche";
$marge_page_gauche = $$var;

// la marge droite des pages
$var = "pdflettreresa_marge_page_droite";
$marge_page_droite = $$var;

// la largeur des pages
$var = "pdflettreresa_largeur_page";
$largeur_page = $$var;

// la hauteur des pages
$var = "pdflettreresa_hauteur_page";
$hauteur_page = $$var;

// le format des pages
$var = "pdflettreresa_format_page";
$format_page = $$var;

$taille_doc=array($largeur_page,$hauteur_page);

$ourPDF = new $fpdf($format_page, 'mm', $taille_doc);
$ourPDF->Open();

switch($pdfdoc) {
	case "lettre_resa" :
	default :
		// chercher id_empr validé
		$rqt = "select resa_idempr from resa where id_resa in ($id_resa) ";
		$res = mysql_query ($rqt, $dbh) ;
		while ($resa_validee=mysql_fetch_object($res)){
			if($resa_validee->resa_idempr != $id_empr_tmp){
				lettre_resa_par_lecteur($resa_validee->resa_idempr) ;
				$id_empr_tmp=$resa_validee->resa_idempr;	
			}
		}
		$ourPDF->SetMargins($marge_page_gauche,$marge_page_gauche);
		break;
	}

if ($probleme) echo "<script> self.close(); </script>" ;
	else $ourPDF->OutPut();
