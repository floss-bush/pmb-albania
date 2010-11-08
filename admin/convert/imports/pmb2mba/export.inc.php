<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.inc.php,v 1.2 2009-11-12 11:26:08 ngantier Exp $

require_once($class_path."/serial_display.class.php");
require_once($class_path."/mono_display.class.php");
require_once($class_path."/parametres_perso.class.php");

function _export_($id,$keep_expl) {
	global $charset,$msg;
	if(!$id) return;
	$requete = "select * from notices where notice_id=".$id;
	$resultat = mysql_query($requete);
	$res = mysql_fetch_object($resultat);
	
	$environement["short"] = 1;
	$environement["ex"] = 0;
	$environement["exnum"] = 0;	
	$environement["link"] = "" ;
	$environement["link_analysis"] = "" ;
	$environement["link_explnum"] = "" ;
	$environement["link_bulletin"] = "" ;
	
	if($res->niveau_biblio != 's' && $res->niveau_biblio != 'a') {
		$display = new mono_display($id, $environement["short"], $environement["link"], $environement["ex"], $environement["link_expl"], '', $environement["link_explnum"],0,1);
		//récup des infos bulletins: bulletin_cb
		$requete = "select * from bulletins where num_notice=".$id;
		$resultat_bul = mysql_query($requete);	
		if(mysql_num_rows($resultat_bul)){
			$res_bul = mysql_fetch_object($resultat_bul);
			$bulletin_cb=$res_bul->bulletin_cb;
		}
	} else {
		// on a affaire à un périodique
		$display = new serial_display($id, $environement["short"], $environement["link_serial"], $environement["link_analysis"], $environement["link_bulletin"], "", $environement["link_explnum"], 0, 0, 1, 1, true, 1);
	}	

	//Champs personalisés
	$p_perso=new parametres_perso("notices");
	$perso_aff = $titre = $loc = $etablissement = $date = "" ;
	if (!$p_perso->no_special_fields) {
		$perso_=$p_perso->show_fields($id);
		for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
			$p=$perso_["FIELDS"][$i];
			if ($p['OPAC_SHOW'] && $p["AFF"]) {
				if($p["NAME"] == "t_d_f_titre")$titre=$p["AFF"];	
				elseif($p["NAME"] == "t_d_f_localisation")$loc=$p["AFF"];									
				elseif($p["NAME"] == "t_d_f_etablissement")$etablissement=$p["AFF"];					
				elseif($p["NAME"] == "t_d_f_date")$date=$p["AFF"];	
				
			}
		}
	}
	
	if($titre)$perso_aff=$titre;
	if($perso_aff && $loc)$perso_aff.=" ";
	$perso_aff.=$loc;
	if($perso_aff && $etablissement)$perso_aff.=", ";
	$perso_aff.=$etablissement;
	if($perso_aff && $date)$perso_aff.=", ";
	$perso_aff.=$date;
	
	if ($perso_aff) {
		$titre_de_forme = $msg["n_titre_de_forme"]."[".$perso_aff."]\n" ;
	}
	
	// langues
	$langues="";
	if(count($display->langues)) {
		$langues = $msg[537]." : ".construit_liste_langues($display->langues);
	}
	if(count($display->languesorg)) {
		$langues .= $msg[711]." : ".construit_liste_langues($display->languesorg);
	}
	if($langues)	$langues="\n".$langues;

	
	$notice="<notice>\n";	
	
	//notice (ID)
	$notice.="<ID>$id</ID>\n";	
		
	//isbn (ISBN)
	if ($display->isbn) {
		$notice.="<ISBN>".htmlspecialchars($display->isbn,ENT_QUOTES,$charset)."</ISBN>\n";
	} elseif($bulletin_cb){
		$notice.="<ISBN>".htmlspecialchars($bulletin_cb,ENT_QUOTES,$charset)."</ISBN>\n";
	}
	//Année publication(YEAR)
	if ($display->notice->year) {
		$notice.="<YEAR>".htmlspecialchars($display->notice->year,ENT_QUOTES,$charset)."</YEAR>\n";
	}
	//isbd(ISBD)
	if ($display->isbd) {
		$isbd=str_replace("<br />","\n",$titre_de_forme.$display->isbd.$langues);
		$isbd=strip_tags($isbd);
		$notice.="<ISBD>".htmlspecialchars(html_entity_decode($isbd,ENT_QUOTES,$charset),ENT_QUOTES,$charset)."</ISBD>\n";
	}
			
	$notice.="</notice>\n";
	
	return $notice;
}


?>
