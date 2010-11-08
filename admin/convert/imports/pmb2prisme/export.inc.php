<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.inc.php,v 1.9 2008-11-21 13:20:47 gautier Exp $

require_once("$class_path/marc_table.class.php");
require_once("$class_path/category.class.php");

/*
function trouve_champ_perso($nom) {
	$rqt = "SELECT idchamp FROM notices_custom WHERE name='" . addslashes($nom) . "'";
	$res = mysql_query($rqt);
	if (mysql_num_rows($res)>0)
		return mysql_result($res,0);
	else
		return 0;
}
*/

function _export_($id,$keep_expl) {
	global $ty;
	global $tab_functions;
	global $mois, $mois_enrichis;
	
	if (!$ty) $ty=array_flip(array("REVUE"=>"v","LIVRE"=>"a","MEMOIRE"=>"b","DOCUMENT AUDIOVISUEL"=>"g","CDROM"=>"m","DOCUMENT EN LIGNE"=>"l"));
	if (!$tab_functions) $tab_functions=new marc_list('function');

	if (!$mois) {
		$mois=array(
			0=>"",
			1=>"janvier",
			2=>"fevrier",
			3=>"mars",
			4=>"avril",
			5=>"mai",
			6=>"juin",
			7=>"juillet",
			8=>"aout",
			9=>"septembre",
			10=>"octobre",
			11=>"novembre",
			12=>"decembre"
		);
		$mois_enrichis=array(
			0=>"",
			1=>"janvier",
			2=>"février",
			3=>"mars",
			4=>"avril",
			5=>"mai",
			6=>"juin",
			7=>"juillet",
			8=>"aout",
			9=>"septembre",
			10=>"octobre",
			11=>"novembre",
			12=>"décembre"
		);
	}
	
	if (!$m_thess) {
		$rqt = "SELECT count(1) FROM thesaurus WHERE active=1";
	 	$m_thess = mysql_result(mysql_query($rqt),0,0);
	}
	
	$notice="<notice>\n";
	$requete="SELECT * FROM notices WHERE notice_id=$id";
	$resultat=mysql_query($requete);
	
	$rn=mysql_fetch_object($resultat);
	
	//Référence
	$notice.="  <REF>".htmlspecialchars($id)."</REF>\n";
	
	//Organisme (OP)
	$no_champ = trouve_champ_perso("op");
	if ($no_champ>0) {
		$requete=	"SELECT notices_custom_list_lib ".
					"FROM notices_custom_lists, notices_custom_values ".
					"WHERE notices_custom_lists.notices_custom_champ=$no_champ ". 
						"AND notices_custom_values.notices_custom_champ=$no_champ ".
						"AND notices_custom_integer=notices_custom_list_value ".
						"AND notices_custom_origine=$id";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$op=mysql_result($resultat,0,0);
			$notice.="  <OP>".htmlspecialchars(strtoupper($op))."</OP>\n";
		}
	}
	
	//Date saisie (DS)
	$no_champ = trouve_champ_perso("ds");
	if ($no_champ>0) {
		$requete="SELECT notices_custom_date FROM notices_custom_values WHERE notices_custom_champ=$no_champ AND notices_custom_origine=$id";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat))
			$date=mysql_result($resultat,0,0);
		else 
			$date=date("Y")."-".date("m")."-".date("d");
		$notice.="<DS>".$date."</DS>\n";
	}
		
	//Type document (TY)
	if (($rn->niveau_biblio!='a')&&($rn->niveau_biblio!='s'))
		$tyd=$ty[$rn->typdoc];
	else
		if ($rn->niveau_biblio=='a')
			$tyd="REVUE";
		else
			$tyd="CHAPEAU";
			
	if ($tyd=="") $tyd="LIVRE";
	$notice.="<TY>".htmlspecialchars($tyd)."</TY>\n";
	
	//Genre (GEN)
	$no_champ = trouve_champ_perso("gen");
	if ($no_champ>0) {
		$requete = 	"SELECT notices_custom_list_lib ".
					"FROM notices_custom_lists, notices_custom_values ".
					"WHERE notices_custom_lists.notices_custom_champ=$no_champ ".
						"AND notices_custom_values.notices_custom_champ=$no_champ ".
						"AND notices_custom_integer=notices_custom_list_value ".
						"AND notices_custom_origine=$id";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$notice.="<GEN>".htmlspecialchars(strtoupper(mysql_result($resultat,0,0)))."</GEN>\n";
		}
	}
		
	//Auteurs
	$requete=	"SELECT author_name, author_rejete, author_type, responsability_fonction, responsability_type ". 
				"FROM authors, responsability ".
				"WHERE responsability_notice=$id AND responsability_author=author_id ".
				"ORDER BY author_type, responsability_type, responsability_ordre";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
		$au=array();
		$auco=array();
		$as=array();
	
		while ($ra=mysql_fetch_object($resultat)) {
			$a=$ra->author_name;
			if ($ra->author_rejete) $a.=" (".$ra->author_rejete.")";
			if ($ra->author_type=='70') {
				//C'est une personne, est-ce un auteur principal ou secondaire ?
				if ($ra->responsability_type==2) {
					if ($ra->responsability_fonction>=900) {
						$a.=" ".$tab_functions->table[$ra->responsability_fonction];
					}
					$as[]=$a; 
				} else $au[]=$a;
			} else {
				//C'est un auteur collectif
				$auco[]=$a;
			}
		}
		//Auteurs / Réalisateurs (AU)
		$au_=implode(", ",$au);
		if ($au_) {
			$notice.="<AU>".htmlspecialchars(strtoupper($au_))."</AU>\n";
		}
		//Auteurs collectifs (AUCO)
		$auco_=implode(", ",$auco);
		if ($auco_) {
			$notice.="<AUCO>".htmlspecialchars(strtoupper($auco_))."</AUCO>\n";
		}
		//Auteurs secondaires (AS)
		$as_=implode(", ",$as);
		if ($as_) {
			$notice.="<AS>".htmlspecialchars(strtoupper($as_))."</AS>\n";
		}
	}
	
	//Distributeur (DIST)
	if ($rn->ed2_id) {
		$requete="SELECT ed_ville,ed_name FROM publishers WHERE ed_id=".$rn->ed2_id;
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$re=mysql_fetch_object($resultat);
			$ed="";
			if ($re->ed_ville) $ed=$re->ed_ville.":";
			$ed.=$re->ed_name;
			$notice.="<DIST>".htmlspecialchars(strtoupper($ed))."</DIST>\n";
		}
	}
	
	//Titre (TI)
	$serie="";
	if ($rn->tparent_id) {
		$requete="SELECT serie_name FROM series WHERE serie_id=".$rn->tparent_id;
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) $serie=mysql_result($resultat,0,0);
	}
	if ($rn->tnvol) $serie.=($serie?" ":"").$rn->tnvol;
	if ($serie) $serie.=". ";
	// ajout GM 15/12/2006 pour export sous-titre dans TI
	if ($rn->tit4!="") $soustitre=" : ".$rn->tit4;
	// fin ajout GM
	// modif GM 15/12/2006 ajout du sous-titre pour l'export
	// $notice.="  <TI>".htmlspecialchars(strtoupper($serie.$rn->tit1))."</TI>\n";
	$notice.="  <TI>".htmlspecialchars(strtoupper($serie.$rn->tit1.$soustitre))."</TI>\n";
		
	//Si c'est un article
	if ($rn->niveau_biblio=='a') {
		//Recherche des informations du bulletin
		$requete="SELECT * FROM bulletins, analysis WHERE bulletin_id=analysis_bulletin AND analysis_notice=$id";
		$resultat=mysql_query($requete);
		$rb=mysql_fetch_object($resultat);
	}
	
	//Titre du numéro (TN)
	if (($rb->bulletin_titre)&&(substr($rb->bulletin_titre,0,9)!="Bulletin ")) {
		$notice.="<TN>".htmlspecialchars(strtoupper($rb->bulletin_titre))."</TN>\n";
	}
	
	//Colloques (COL)
	if ($tyd!="MEMOIRE") {
		if ($rn->tit3) $notice.="<COL>".htmlspecialchars(strtoupper($rn->tit3))."</COL>\n";
	}
	
	//Titre de revue (TP)
	if ($rb) {
		$requete="SELECT tit1 FROM notices WHERE notice_id=".$rb->bulletin_notice;
		$resultat=mysql_query($requete);
		$notice.="<TP>".htmlspecialchars(strtoupper(mysql_result($resultat,0,0)))."</TP>\n";
	}
	
	//Souces (SO)
	if ($rb) {
		$so="";
		if ($rb->bulletin_numero) $so=$rb->bulletin_numero;
		if ($rb->mention_date) {
			if ($so) $so.=", ";
			$so.=$rb->mention_date;
		}
	} else
		$so = $rn->n_gen; 
	$notice.="<SO>".htmlspecialchars(strtoupper($so))."</SO>";
	
	//Editeur / Collection (ED)
	if ($rn->ed1_id) {
		$requete="SELECT ed_ville,ed_name FROM publishers WHERE ed_id=".$rn->ed1_id;
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$red=mysql_fetch_object($resultat);
			$ed="";
			if ($red->ed_ville) $ed=$red->ed_ville.":";
			$ed.=$red->ed_name;
		}
		//Collection
		if ($rn->coll_id) {
			$requete="SELECT collection_name FROM collections WHERE collection_id=".$rn->coll_id;
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$coll_name=mysql_result($resultat,0,0);
				$ed.=" (".$coll_name.")";
			}
		}
		$notice.="<ED>".htmlspecialchars(strtoupper($ed))."</ED>\n";
	}
	
	//Date de publication (DP)
	$annee="";
	if (($rn->year)&&($rn->niveau_biblio!='a')) {
		$annee=$rn->year;
	} else if ($rn->niveau_biblio=='a') {
		$req_mention_date="SELECT YEAR(date_date) FROM bulletins, analysis WHERE bulletin_id=analysis_bulletin AND analysis_notice=$id";
		$res_mention_date=mysql_query($req_mention_date);
		if ($res_mention_date) {
			$annee=mysql_result($res_mention_date,0,0);
		} else if ($rn->year) {
			$annee=$rn->year;
		}
	}
	if ($annee!="") {
		//on essaie d'enlever les mois
		for($bcl_an=1;$bcl_an<13;$bcl_an++) {
			$annee = str_replace($mois[$bcl_an],"",strtolower($annee));
			$annee = str_replace($mois_enrichis[$bcl_an],"",strtolower($annee));
		}
		$annee = str_replace("-","",$annee);
		$annee = str_replace(",","",$annee);
		$annee = substr($annee,0,4);
		$notice.="<DP>".htmlspecialchars(strtoupper(trim($annee)))."</DP>\n";
	}
	
	//Diplome (ND)
	if (($tyd=="MEMOIRE")&&($rn->tit3)) {
		$notice.="<ND>".htmlspecialchars(strtoupper($rn->tit3))."</ND>\n";
	}
	//Notes (NO)
	if ($tyd=="REVUE")
		$no=$rn->npages;
	else
		$no=$rn->n_contenu;

	if ($no)
		$notice.="<NO>".htmlspecialchars(strtoupper($no))."</NO>\n";
	
	$requete="SELECT num_noeud FROM notices_categories WHERE notcateg_notice=$id ORDER BY ordre_categorie";
	$resultat=mysql_query($requete);
	$go=array();
	$hi=array();
	$denp=array();
	$de=array();
	$cd=array();
	
	if ($m_thess>1) {
		while (list($categ_id)=mysql_fetch_row($resultat)) {
			$categ=new category($categ_id);
			if (trouve_thesaurus("GO")==$categ->thes->id_thesaurus) {
				$go[]=$categ->libelle;
			} elseif (trouve_thesaurus("HI")==$categ->thes->id_thesaurus) {
				$hi[]=$categ->libelle;
			} elseif (trouve_thesaurus("DENP")==$categ->thes->id_thesaurus) {
				$denp[]=$categ->libelle;
			} elseif (trouve_thesaurus("DE")==$categ->thes->id_thesaurus) {
				$de[]=$categ->libelle;
			} elseif (trouve_thesaurus("CD")==$categ->thes->id_thesaurus) {
				$cd[]=$categ->libelle;
			}
		}
	} else {
		
		while (list($categ_id)=mysql_fetch_row($resultat)) {
			$categ=new categories($categ_id,'fr_FR');
			$list_categ=$categ->listAncestors();
			reset($list_categ);
			list($id,$libelle)=each($list_categ);
			switch ($libelle["autorite"]) {
				case "GO":
					$go[]=$categ->libelle_categorie;
					break;
				case "HI":
					$hi[]=$categ->libelle_categorie;
					break;
				case "DENP":
					$denp[]=$categ->libelle_categorie;
					break;
				case "DE":
					$de[]=$categ->libelle_categorie;
					break;
				case "CD":
					$cd[]=$categ->libelle_categorie;
					break;
			}
		}
	}
	
	//Zone (GO)
	if (count($go)) {
		//sort($go);
		$notice.="<GO>".htmlspecialchars(strtoupper(implode(", ",$go)))."</GO>\n";
	}
	
	//Période historique (HI)
	if (count($hi)) {
		//sort($hi);
		$notice.="<HI>".htmlspecialchars(strtoupper(implode(", ",$hi)))."</HI>\n";
	}
	
	//Descripteurs noms propres (DENP)
	if (count($denp)) {
		//sort($denp);
		$notice.="<DENP>".htmlspecialchars(strtoupper(implode(", ",$denp)))."</DENP>\n";
	}
	
	//Descripteurs (DE)
	if (count($de)) {
		//sort($de);
		$notice.="<DE>".htmlspecialchars(strtoupper(implode(", ",$de)))."</DE>\n";
	}
	
	//Candidats descripteurs (CD)
	if (count($cd)) {
		//sort($cd);
		$notice.="<CD>".htmlspecialchars(strtoupper(implode(", ",$cd)))."</CD>\n";
	}

	//Resumé (RESU)
	if ($rn->n_resume) {
		$notice.="<RESU>".htmlspecialchars($rn->n_resume)."</RESU>\n";
	}
	
	//date de tri (DATRI)
	if ($rb->date_date) {
		$notice.="<DATRI>".htmlspecialchars($rb->date_date)."</DATRI>\n";
	}
	
	//url (URL)
	if ($rn->lien) {
		$notice.="<URL>".htmlspecialchars($rn->lien)."</URL>\n";
	}
	
	//isbn (ISBN)
	if ($rn->code) {
		$notice.="<ISBN>".htmlspecialchars(str_replace("-","",$rn->code))."</ISBN>\n";
	}
	
	$notice.="</notice>";
	
	return $notice;
}

?>
