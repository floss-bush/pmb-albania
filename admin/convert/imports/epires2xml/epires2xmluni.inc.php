<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: epires2xmluni.inc.php,v 1.5 2009-05-16 11:13:21 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/marc_table.class.php");

function make_index($descr,$tete) {
	global $charset;
	$data="";
	if ($descr) {
		$d=explode(",",$descr);
		for ($i=0; $i<count($d); $i++) {
			if ($d[$i]) {
				$data.="  <f c='606' ind='  '>\n";
				$data.="    <s c='a'>".htmlspecialchars($tete,ENT_QUOTES,$charset)."</s>\n";
				$data.="    <s c='x'>".htmlspecialchars($d[$i],ENT_QUOTES,$charset)."</s>\n";
				$data.="  </f>\n";
			}
		}
	}
	return $data;
}

function convert_epires($notice, $s, $islast, $isfirst, $param_path) {
	global $cols;
	global $ty;
	global $intitules;
	global $base_path,$origine;
	global $tab_functions;
	global $charset;
	
	if (!$tab_functions) $tab_functions=new marc_list('function');
	
	if (!$cols) {
		//On lit les intitulés dans le fichier temporaire
		$fcols=fopen("$base_path/temp/".$origine."_cols.txt","r");
		if ($fcols) {
			$cols=fread($fcols,filesize("$base_path/temp/".$origine."_cols.txt"));
			fclose($fcols);
			$cols=unserialize($cols);
		}
	}
	
	if (!$ty) {
		$ty=array("REVUE"=>"a","LIVRE"=>"a","MEMOIRE"=>"b","DOCUMENT AUDIOVISUEL"=>"g","CDROM"=>"m","DOCUMENT EN LIGNE"=>"l");
	}
	
	//if (!$cols) {
	//	for ($i=0; $i<count($s["FIELDS"][0]["FIELD"]); $i++) {
	//		$cols[$s["FIELDS"][0]["FIELD"][$i]["ID"]]=$s["FIELDS"][0]["FIELD"][$i]["value"];
	//	}
	//}
	
	$fields=explode(";;",$notice);
	for ($i=0; $i<count($fields); $i++) {
		$ntable[$cols[$i]]=$fields[$i];
	}
	
	if (!$ntable["TI"]) {
		$data=""; 
		$error="Titre vide<br />".$notice;
	} else {
		$error="";
		$data="<notice>\n";
		
		//Entête
		$data.="  <rs>n</rs>\n";
		if ($ty[$ntable["TY"]]) $dt=$ty[$ntable["TY"]]; else $dt="a";
		$bl="m";
		$data.="  <dt>".$dt."</dt>\n";
		$data.="<bl>".$bl."</bl>\n";
		$data.="<hl>*</hl>\n<el>1</el>\n<ru>i</ru>\n";
		//Numéro d'enregistrement
		$data.="  <f c='001' ind='  '>".$ntable["REF"]."</f>\n";
		
		//Titre
		$titres=explode(":",$ntable["TI"]);
		$data.="  <f c='200' ind='  '>\n";
		//Titre principal
		$data.="    <s c='a'>".htmlspecialchars($titres[0],ENT_QUOTES,$charset)."</s>\n";
		//Titre parallèle
		if ($ntable["COL"]) {
			$data.="    <s c='d'>".htmlspecialchars($ntable["COL"],ENT_QUOTES,$charset)."</s>\n";
		} else if ($ntable["ND"]) {
			$diplome=explode(":",$ntable["ND"]);
			if ($diplome[0]) {
				$data.="    <s c='d'>".htmlspecialchars($diplome[0],ENT_QUOTES,$charset)."</s>\n";
			}
		}
		//Titre complémentaire
		if ($titres[1])
			$data.="    <s c='e'>".htmlspecialchars($titres[1],ENT_QUOTES,$charset)."</s>\n";
		$data.="  </f>\n";
		
		//Traitement des Auteurs principaux
		if ($ntable["AU"]) {
			$auteurs=explode(",",$ntable["AU"]);
			if (count($auteurs)>1) {
				$f_a="701";
			} else {
				$f_a="700";
			}
			
			$data_auteurs="";
			for ($i=0; $i<count($auteurs); $i++) {
				preg_match_all("/([^\(]*)(\((.*)\))*( (.*))?/",trim($auteurs[$i]),$matches);
				$entree=$matches[1][0];
				$rejete=$matches[3][0];
				$fonction=$matches[5][0];
				if ($entree) {
					$data_auteurs.="    <s c='a'>".htmlspecialchars($entree,ENT_QUOTES,$charset)."</s>\n";
					if ($rejete) {
						$data_auteurs.="    <s c='b'>".htmlspecialchars($rejete,ENT_QUOTES,$charset)."</s>\n";
					}
					$as=array_search($fonction,$tab_functions->table);
					if (($as!==false)&&($as!==null)) $fonction=$as; else $fonction="070";
					$data_auteurs.="    <s c='4'>".$fonction."</s>\n";
				}
			}
			if ($data_auteurs) {
				$data.="  <f c='".$f_a."' ind='  '>\n";
				$data.=$data_auteurs;
				$data.="  </f>\n";
			}
		}
		
		//Traitement des auteurs secondaires
		if ($ntable["AS"]) {
			$auteurs=explode(",",$ntable["AS"]);
			$f_a="702";

			$data_auteurs="";
			for ($i=0; $i<count($auteurs); $i++) {
				preg_match_all("/([^\(]*)(\((.*)\))*( (.*))?/",trim($auteurs[$i]),$matches);
				$entree=$matches[1][0];
				$rejete=$matches[3][0];
				$fonction=$matches[5][0];
				if ($entree) {
					$data_auteurs.="    <s c='a'>".htmlspecialchars($entree,ENT_QUOTES,$charset)."</s>\n";
					if ($rejete) {
						$data_auteurs.="    <s c='b'>".htmlspecialchars($rejete,ENT_QUOTES,$charset)."</s>\n";
					}
					//Recherche de la fonction
					$as=array_search($fonction,$tab_functions->table);
					if (($as!==false)&&($as!==null)) $fonction=$as; else $fonction="070";
					$data_auteurs.="    <s c='4'>".$fonction."</s>\n";
				}
			}
			if ($data_auteurs) {
				$data.="  <f c='".$f_a."' ind='  '>\n";
				$data.=$data_auteurs;
				$data.="  </f>\n";
			}
		}
		
		//Traitement des Auteurs collectif
		if ($ntable["AUCO"]) {
			$auteurs=explode(",",$ntable["AUCO"]);
			$f_a="702";
			
			$data_auteurs="";
			for ($i=0; $i<count($auteurs); $i++) {
				preg_match_all("/([^\(]*)(\((.*)\))*( (.*))?/",trim($auteurs[$i]),$matches);
				$entree=$matches[1][0];
				$rejete=$matches[3][0];
				$fonction=$matches[5][0];
				if ($entree) {
					$data_auteurs.="    <s c='a'>".htmlspecialchars($entree,ENT_QUOTES,$charset)."</s>\n";
					if ($rejete) {
						$data_auteurs.="    <s c='b'>".htmlspecialchars($rejete,ENT_QUOTES,$charset)."</s>\n";
					}
					$as=array_search($fonction,$tab_functions->table);
					if (($as!==false)&&($as!==null)) $fonction=$as; else $fonction="070";
					$data_auteurs.="    <s c='4'>".$fonction."</s>\n";
				}
			}
			if ($data_auteurs) {
				$data.="  <f c='".$f_a."' ind='  '>\n";
				$data.=$data_auteurs;
				$data.="  </f>\n";
			}
		}
		
		//Editeurs / collection
		if ($ntable["ED"]) {
			$editeur=explode(":",$ntable["ED"]);
			$lieu=$editeur[0];
			$nom=$editeur[1];
			preg_match_all("/([^\(]*)(\((.*)\))*?/",trim($editeur[2]),$matches);
			$annee=$matches[1][0];
			$collection=$matches[3][2];
			$collection=str_replace("COLL.","",$collection);
		} else if ($diplome[2]) {
			$lieu=$diplome[1];
			$nom=$diplome[2];
			$annee=$diplome[3];
		}
		$data_editeur="";
		if ($nom) {
			$data_editeur.="    <s c='c'>".htmlspecialchars($nom,ENT_QUOTES,$charset)."</s>\n";
			if ($lieu) $data_editeur.="    <s c='a'>".htmlspecialchars($lieu,ENT_QUOTES,$charset)."</s>\n";
			if ($annee) $ann=$annee; else $ann=$ntable["DP"];
			$data_editeur.="    <s c='d'>".htmlspecialchars($ann,ENT_QUOTES,$charset)."</s>\n";
		}
		$editeur_present=false;
		if ($data_editeur) {
			$data.="  <f c='210' ind='  '>\n";
			$data.=$data_editeur;
			$data.="  </f>\n";
			$editeur_present=true;
		}
		
		//Date de publication
		$dp=false;
		if ($ntable["DP"]) {
			if (!$editeur_present) {
				$data.="  <f c='210' ind='  '>\n";
				$data.="    <s c='d'>".htmlspecialchars($ntable["DP"],ENT_QUOTES,$charset)."</s>\n";
				$data.="  </f>\n";
				$dp=true;
			}
		}
		
		//Distributeur
		if ($ntable["DIST"]) {
			if ((!$dp)&&(!$editeur_present)) {
				$data.="  <f='210' ind=' '>\n";
				$data.="    <s c='a'> </s>\n";
				$data.="  </f>\n";
			}
			$distributeur=explode(":",$ntable["DIST"]);
			if ($distributeur[1]) {
				$nom=$dsitributeur[1];
				$lieu=$distributeur[0];
			} else {
				$nom=$ntable["DIST"];
				$lieu="";
			}
			$data_editeur="    <s c='c'>".htmlspecialchars($nom)."</s>\n";
			if ($lieu) $data_editeur.="    <s c='a'>".htmlspecialchars($lieu)."</s>\n";
			$data.="  <f c='210'>\n";
			$data.=$data_editeur;
			$data.="  </f>\n";
		}	
		
		if ($collection) {
			$data.="  <f c='225' ind='  '>\n";
			$data.="    <s c='a'>".htmlspecialchars($collection,ENT_QUOTES,$charset)."</s>\n";
			$data.="  </f>\n";
		}
		
		//Notes
		if (($ntable["NO"])&&($ntable["TY"]!="REVUE")) {
			$data.="  <f c='327' ind='  '>\n";
			$data.="    <s c='a'>".htmlspecialchars($ntable["NO"],ENT_QUOTES,$charset)."</s>\n";
			$data.="  </f>\n";
		}
		
		//Résumé
		if ($ntable["RESU"]) {
			$data.="  <f c='330' ind='  '>\n";
			$data.="    <s c='a'>".htmlspecialchars($ntable["RESU"],ENT_QUOTES,$charset)."</s>\n";
			$data.="  </f>\n";
		}
		
		//Périodiques
		if ($ntable["TP"]) {
			$data.="  <f c='464' ind='  '>\n";
			$data.="    <s c='t'>".htmlspecialchars($ntable["TP"],ENT_QUOTES,$charset)."</s>\n";
			$data.="    <s c='u'>".htmlspecialchars($ntable["TN"],ENT_QUOTES,$charset)."</s>\n";
			$so=explode(",",$ntable["SO"]);
			$data.="    <s c='d'>".htmlspecialchars($so[count($so)-1],ENT_QUOTES,$charset)."</s>\n";
			unset($so[count($so)-1]);
			$data.="    <s c='v'>".htmlspecialchars(implode(",",$so),ENT_QUOTES,$charset)."</s>\n";
			$data.="    <s c='p'>".htmlspecialchars($ntable["NO"],ENT_QUOTES,$charset)."</s>\n";
			$data.="  </f>";
		}
		
		//Indexations
		if ($ntable["GO"]||$ntable["HI"]||$ntable["DENP"]||$ntable["DE"]||$ntable["CD"]) {
			$data.=make_index($ntable["GO"],"Géo");
			$data.=make_index($ntable["HI"],"Hist");
			$data.=make_index($ntable["DENP"],"DENP");
			$data.=make_index($ntable["DE"],"Mots clés");
			$data.=make_index($ntable["CD"],"CD");
		}
		
		//Champs spéciaux
		$data.="  <f c='900'>\n";
		$data.="    <s c='a'>".htmlspecialchars($ntable["OP"],ENT_QUOTES,$charset)."</s>\n";
		$data.="  </f>\n";
		$data.="  <f c='901'>\n";
		$data.="    <s c='a'>".htmlspecialchars($ntable["GEN"],ENT_QUOTES,$charset)."</s>\n";
		$data.="  </f>\n";
		$data.="  <f c='902'>\n";
		$data.="    <s c='a'>".htmlspecialchars($ntable["DS"],ENT_QUOTES,$charset)."</s>\n";
		$data.="  </f>\n";
		$data.="</notice>\n";
	}
	
	if (!$error) $r['VALID'] = true; else $r['VALID']=false;
	$r['ERROR'] = $error;
	$r['DATA'] = $data;
	return $r;
}
?>