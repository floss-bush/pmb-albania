<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: constitution.inc.php,v 1.6 2007-03-10 09:03:18 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if (!verif_droit_etagere($idetagere)) $action="aucune" ;
switch ($action) {
	case 'edit_etagere':
		$myEtagere = new etagere($idetagere);
		$etagere_constitution_form = str_replace('!!formulaire_titre!!', $msg['etagere_constitution_de']." ".$myEtagere->name, $etagere_constitution_form);
		$etagere_constitution_form = str_replace('!!idetagere!!', $idetagere, $etagere_constitution_form);
		$etagere_constitution_form = str_replace('!!constitution!!', $myEtagere->constitution(1), $etagere_constitution_form);
		print pmb_bidi($etagere_constitution_form) ;
		break;
	case 'save_etagere':
		$myEtagere = new etagere($idetagere);
		// suppression
		$rqt = "delete from etagere_caddie where etagere_id='".$idetagere."' ";
		$res = mysql_query ($rqt, $dbh) ;
		for ($i=0 ; $i < sizeof($idcaddie) ; $i++) {
			if (verif_droit_caddie($idcaddie[$i])) $myEtagere->add_panier($idcaddie[$i]) ;
			}
		aff_etagere("constitution",0);
		break;
	default:
		aff_etagere("constitution",0);
	}
