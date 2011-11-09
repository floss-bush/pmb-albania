<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: infopages.inc.php,v 1.3.2.1 2011-10-07 09:59:08 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$retaff = "";
for ($ip=0; $ip<count($idpages); $ip++) {
	$idpages[$ip]+=0;
	$requete="select id_infopage, content_infopage, restrict_infopage from infopages where id_infopage=".$idpages[$ip]." and valid_infopage=1";
	$resultat=mysql_query($requete) or die(mysql_error().$requete);
	while ($res=mysql_fetch_object($resultat)) {
		//seulement si l'infopage est accessible...
		if(!$res->restrict_infopage || ($res->restrict_infopage && $_SESSION['id_empr_session'])){
			$lu=$res->content_infopage ;
			
			// modif pour inclusion etagere dans infopages
			//  syntaxe : !!etagere_seeN,B,M,D,I!!
			//         N = id etagere
			//         B = nomBre maxi de notices à afficher, mettre 99999 pour illimiter
			//         M = 1,2,4 ou 8 mode d'affichage, comme dans le paramètre opac_etagere_notices_format  
			//         D = 0 ou 1 pour affichage dépliable ou pas
			//         I = 0 ou  1 pour insérer le lien ... si nb notices > nb max notices
			$oldpos = 0 ; 
			while (($pos=strpos($lu, "!!etagere_see", $oldpos)) > 0) {
				// demande aff etagere trouvée
				$pos_fin = strpos($lu, "!!", $pos+2);
				$info_etagere_str=substr($lu,$pos+13,$pos_fin-$pos-13);
				$info_etagere = array();
				$info_etagere = explode(",",$info_etagere_str);
	
				// $info_etagere[0] = id
				// $info_etagere[1] = nb max notices affichées
				// $info_etagere[2] = mode d'affichage
				// $info_etagere[3] = dépliable ou pas
				// $info_etagere[4] = lien ou pas quand plus de notices que NB max
				
				// paramètres :
				//	$idetagere : l'id de l'étagère
				//	$aff_notices_nb : nombres de notices affichées : toutes = 0 
				//	$mode_aff_notice : mode d'affichage des notices, REDUIT (titre+auteur principal) ou ISBD ou PMB ou les deux : dans ce cas : (titre + auteur) en entête du truc, à faire dans notice_display.class.php
				//	$depliable : affichage des notices une par ligne avec le bouton de dépliable
				//	$link_to_etagere : 0 ou 1
				//  $link : lien pour afficher le contenu de l'étagère "./index.php?lvl=etagere_see&id=!!id!!"
				$etagere = contenu_etagere($info_etagere[0], $info_etagere[1], $info_etagere[2], $info_etagere[3], $info_etagere[4], "./index.php?lvl=etagere_see&id=!!id!!");
				$lu = str_replace("!!etagere_see".$info_etagere_str."!!", $etagere, $lu);
				$oldpos = $pos+strlen($etagere);
			}
			
			$retaff.=$lu;
		}
	}
}

print $retaff;
?>