<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: create_form.inc.php,v 1.11 2009-03-13 16:36:14 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de création d'une notice

// affichage du form de création/modification d'une notice

// dédoublonnage par le code-barre ou l'ISBN
if($saisieISBN) {
	if(isEAN($saisieISBN)) {
		// la saisie est un EAN -> on tente de le formater en ISBN
		$code = EANtoISBN($saisieISBN);
		// si échec, on prend l'EAN comme il vient
		if(!$code) {
			$code = $saisieISBN;
		} else {
			//On calcule l'ISBN 10
			$code10=EANtoISBN10($saisieISBN);
		}
	} else {
		//C'est un ISBN 10 !!
		if(isISBN($saisieISBN)) {
			// si la saisie est un ISBN
			$code10 = formatISBN($saisieISBN);
			// si échec, ISBN erroné on le prend sous cette forme
			if(!$code10) $code = $saisieISBN;
			else $code = formatISBN($code10,13);
		} else {
			// ce n'est rien de tout ça, on prend la saisie telle quelle
			$code = $saisieISBN;
		}
	}
	$requete = "SELECT notice_id FROM notices WHERE (".($code?"code='$code'":"").(($code&&$code10)?" or ":"").($code10?"code='$code10'":"").")";
	$myQuery = mysql_query($requete, $dbh);
	$temp_nb_notice = mysql_num_rows($myQuery) ;
}


if(!$temp_nb_notice) {
	
	// isbn inconnu -> affichage form de creation
	$myNotice = new notice($id, $code);
	print "<h1>".$msg[4057].$msg[1003].$msg[270]."</h1>";
	print $myNotice->show_form();
	
} else {
	
	// isbn connu 
	$notice = mysql_fetch_object($myQuery);
	
	//verification des droits de modification notice
	$acces_m=1;
	if ( $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
		require_once("$class_path/acces.class.php");
		$ac= new acces();
		$dom_1= $ac->setDomain(1);
		$acces_m = $dom_1->getRights($PMBuserid,$id,8);	
	}
	
	if ($acces_m==0) {
	
		// isbn connu et droits ko -> affichage form de creation
		$myNotice = new notice($id, $code);
		print "<h1>".$msg[4057].$msg[1003].$msg[270]."</h1>";
		print $myNotice->show_form();
			
	} else {
		
		// isbn connu et droits ok -> on redirige vers la page de la notice
		print "<script type=\"text/javascript\">
				document.location = './catalog.php?categ=isbd&id=$notice->notice_id';
				</script>";
	}
}
?>