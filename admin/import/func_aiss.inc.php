<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_aiss.inc.php,v 1.2 2011-01-19 08:21:00 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// DEBUT paramétrage propre à la base de données d'importation :
require_once($class_path."/serials.class.php");
require_once($class_path."/categories.class.php");
require_once($class_path."/noeuds.class.php");
require_once($class_path."/upload_folder.class.php");
require_once($include_path."/explnum.inc.php");
//require_once("func_aide_import.php");

function recup_noticeunimarc_suite($notice) {
	global $info_461,$info_463,$info_530;
	global $info_900,$info_901,$titre_ppal_200,$champ_210,$titre_perio_530a,$info_897;
	global $bl,$hl;
	global $serie,$serie_200,$npages;
	
	$info_461 = array();
	$info_463 = array();
	$info_530 = array();
	$info_900 = array();
	$info_901 = array();
		
	$record = new iso2709_record($notice, AUTO_UPDATE); 
	
	$bl=$record->inner_guide['bl'];
	$hl=$record->inner_guide['hl'];	
	
	$info_461=$record->get_subfield("461","t","x");
	$info_463=$record->get_subfield("463","t","v","d","e");
	$info_530=$record->get_subfield("530","a");
	$info_897=$record->get_subfield("897","a","f","k","m","p","s","u");
	$info_900=$record->get_subfield("900","a","l","n");
	$info_901=$record->get_subfield("901","a");
	$titre_ppal_200 = $record->get_subfield('200','h','i');
	$champ_210 = $record->get_subfield('210','d','h');
	$titre_perio_530a = $record->get_subfield('530','a');
	
	//on vide certains champ en fonction du type de notice...
	if ($bl == "a" && $hl == "2"){
		$serie= array();
		$serie_200= array();
	}else if ($bl == "s" && $hl == "2") {
		$serie= array();
		$serie_200= array();
	}else{
	}

} // fin recup_noticeunimarc_suite = fin récupération des variables propres à la bretagne
	
function import_new_notice_suite() {
	global $info_461,$info_463,$info_530;
	global $info_900,$info_901,$tit_200a;
	global $info_215,$titre_ppal_200,$champ_210,$titre_perio_530a;
	global $info_606_a,$info_897;
	
	global $notice_id ;
	global $bull_id;
	global $bl,$hl;
	
	echo "<pre>";
	$bull_id = 0;
	
	//cas d'un article
	if ($bl == "a" && $hl == "2"){
		//on peut pas découper une date, on a pas de date mais une mention
		if(decoupe_date($info_463[0]["d"]) == 0 && clean_string($info_463[0]["e"]) == ""){
			$info_463[0]["e"] = $info_463[0]["d"];
			$info_463[0]["d"] = "";
		}
		$bulletin = array(
			'titre' => clean_string($info_463[0]["t"]),
			'date' => decoupe_date($info_463[0]["d"]),
			'mention' => clean_string($info_463[0]["e"]),
			'num' => clean_string($info_463[0]["v"])
		);
		$perio = array(
			'titre' => $info_461[0]['t'],
			'code' => $info_461[0]['x']
		);
		notice_to_article($perio,$bulletin);
		$update =" update notices set typdoc='t' where notice_id = $notice_id";
		mysql_query($update);
	//cas d'un bulletin
	}else if ($bl == "s" && $hl == "2") {
		if(decoupe_date($champ_210[0]['h']) == 0 && clean_string($titre_ppal_200[0]['h']) == ""){
			$titre_ppal_200[0]['h'] = $champ_210[0]['h'];
			$champ_210[0]['h'] = "";
		}
		$bulletin = array(
			'titre' => clean_string($titre_ppal_200[0]['i']),
			'date' => decoupe_date($champ_210[0]['h']),
			'mention' => clean_string($champ_210[0]['d']),
			'num' => clean_string($titre_ppal_200[0]['h'])		
		);
		$perio = array(
			'titre' => $info_461[0]['t'],
			'code' => $info_461[0]['x']
		);	
		$bull_id = genere_bulletin($perio,$bulletin);
		$update =" update notices set typdoc='t' where notice_id = $notice_id";
		mysql_query($update);
	}	
	
	//on s'occupe des descripteurs;
	$id_thesaurus = 1;
	$non_classes = 3;
	$lang="fr_FR";
	$ordre_categ = 0 ;
	
	foreach ($info_606_a as $terms){
		foreach($terms as $term){
			$categ_id = categories::searchLibelle(addslashes($term),$id_thesaurus,$lang);
			if($categ_id){
				//le terme existe
				$noeud = new noeuds($categ_id);
				if($noeud->num_renvoi_voir){
					$categ_to_index = $noeud->num_renvoi_voir;
				}else{
					$categ_to_index = $categ_id;
				}
			}else{
				//le terme est à créé
				$n = new noeuds();
				$n->num_thesaurus = $id_thesaurus;
				$n->num_parent = $non_classes;
				$n->save();
				$c = new categories($n->id_noeud, $lang);
				$c->libelle_categorie = $term;
				$c->index_categorie = ' '.strip_empty_words($term).' ';
				$c->save();
				
				$categ_to_index = $n->id_noeud;
			}
			$requete = "INSERT INTO notices_categories (notcateg_notice,num_noeud,ordre_categorie) VALUES($notice_id,$categ_to_index,$ordre_categ)";
			mysql_query($requete);
			$ordre_categ++;	
		}
	}
	
	//on traite le commentaire de gestion
	$up = "update notices set commentaire_gestion = '".addslashes($info_901[0][0])."' where notice_id = $notice_id";
	mysql_query($up);
	
	//traitement des Champs perso
	//classique on commence par cherché l'id
	foreach ($info_900 as $champperso){ 
		$champ = array(
			'libelle' => $champperso['l'],
			'nom' => $champperso['n'],
			'value' => $champperso['a']
		);
		recup_champ_perso($champ,"notices",$notice_id);
	}
	
	//gestion des 897$...
	foreach($info_897 as $docnum){
		//si on a pas d'url, on traite pas
		if($docnum['u']){
			//on reprend pas les site web...
			if($docnum['m'] != "text/html"){
				$doc = array(
					'titre' => clean_string($docnum['a']),
					'mimetype' => $docnum['m'],
					'nom_fic' => clean_string($docnum['f']),
					'url' => $docnum['u'],
				);
				create_docnum($doc);
			}
			
		}
	}
	echo "</pre>";
} // fin import_new_notice_suite
		
	
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {} // fin traite_exemplaires	TRAITEMENT DES EXEMPLAIRES JUSQU'ICI

// fonction spécifique d'export de la zone 995
function export_traite_exemplaires ($ex=array()) {}	


function update_notice($bl,$hl){
	global $notice_id;
	$update =" update notices set niveau_biblio = '$bl', niveau_hierar ='$hl' where notice_id = $notice_id";
	mysql_query($update);
}
function notice_to_article($perio_info,$bull_info){
	global $notice_id;
	$bull_id = genere_bulletin($perio_info,$bull_info);
	update_notice("a","2");
	$insert = "insert into analysis set analysis_bulletin = $bull_id, analysis_notice = $notice_id";
	mysql_query($insert);
	
}

function genere_perio($perio_info){
	$search = "select notice_id from notices where tit1 LIKE '".addslashes($perio_info['titre'])."' and niveau_biblio = 's' and niveau_hierar = '1'";
	$res = mysql_query($search);
	if(mysql_num_rows($res) == 0){
		//il existe pas, faut le créer
		$insert = "insert into notices set tit1 = '".addslashes($perio_info['titre'])."', code = '".$perio_info['code']."', niveau_biblio = 's', niveau_hierar = '1'";
		$result = mysql_query($insert);
		$perio_id = mysql_insert_id();
	}else $perio_id = mysql_result($res,0,0);
	return $perio_id;
}

function genere_bulletin($perio_info,$bull_info,$isbull=true){
	global $bl,$hl,$notice_id;
	//on récup et/ou génère le pério
	$perio_id = genere_perio($perio_info);
	//on s'occupe du cas ou on a pas de titre pour le bulletin
	// num (mention) [date]
	if($bull_info['titre'] == ""){
		$bull_info['titre'] = $bull_info['num'].($bull_info['mention'] ? " (".$bull_info['mention'].") ": " ").($bull_info['date'] ? "[".$bull_info['date']."]" : "");
	}
	$search = "select bulletin_id from bulletins where bulletin_titre LIKE '".addslashes($bull_info['titre'])."' and mention_date LIKE '".$bull_info['mention']."' and bulletin_numero LIKE '".$bull_info['num']."' and bulletin_notice = $perio_id";
	$res = mysql_query($search);
	if(mysql_num_rows($res) == 0){
		$insert = "insert into bulletins set bulletin_titre = '".$bull_info['titre']."', date_date  = '".$bull_info['date']."', mention_date = '".$bull_info['mention']."', bulletin_numero = '".$bull_info['num']."', bulletin_notice = $perio_id";
		if($bl == "s" && $hl == "2") {
			$insert .=", num_notice = $notice_id";
			update_notice("b","2");
		}
		$result = mysql_query($insert);
		$bull_id = mysql_insert_id();
	}else {
		$bull_id = mysql_result($res,0,0);
		//on regarde si une notice n'existe pas déjà pour ce bulletin
		$req = "select num_notice from bulletins where bulletin_id = $bull_id and num_notice != 0";
		$res = mysql_query($req);
		//si oui on retire l'enregistrement en cours, et on continue sur la notice existante...
		if(mysql_num_rows($res)>0) {
			notice::del_notice($notice_id);
			$notice_id = mysql_result($res,0,0);
		}
	}
	return $bull_id;
}

function create_docnum($doc){
	global $bull_id;
	global $notice_id;
	
	$id_rep = 1;
	$rep = new upload_folder($id_rep);


	$name = ($doc['nom_fic'] ? $doc['nom_fic'] : $doc['titre']);
	$filename = strtolower(implode("_",explode(" ",$name)));

	$filename = checkIfExist($rep->repertoire_path,$filename,$filename);
	file_put_contents($rep->repertoire_path.$filename,file_get_contents($doc['url']));
	
	$ext_fichier = extension_fichier($filename);
	if($doc['mimetype'] == ""){
		create_tableau_mimetype();
		$mimetype = trouve_mimetype($filename,$ext_fichier);	
	}else $mimetype = $doc['mimetype'];
	
	
	$insert = "insert into explnum set ";
	if($bull_id != 0) $insert.= "explnum_bulletin = $bull_id, ";
	else $insert.= "explnum_notice = $notice_id, ";
	$insert.= "explnum_nom = '".$doc['titre']."', ";
	$insert.= "explnum_mimetype = '$mimetype', ";  
	$insert.= "explnum_nomfichier = '".$filename."', ";
	$insert.= "explnum_extfichier = '$ext_fichier', ";
	$insert.= "explnum_repertoire = $id_rep, ";
	$insert.= "explnum_path = '/'";
	$result = mysql_query($insert);
	$explnum_id = mysql_insert_id();
	return $explnum_id;
}

function checkIfExist($path,$file){
	$ind = 0;
	do {
		if($ind == 0) $fileName = $file;
		else $fileName = substr($file,0,strripos($file,"."))."_$ind".substr($file,strripos($file,"."));
		if(file_exists($path.$fileName) == false) break;
		else $ind++;
	}while (true);
	return $fileName;
}

function recup_champ_perso($champ,$table,$notice_id,$create=false){
	$id_champ = trouve_champ_perso($champ['nom'],$table);
	if ($id_champ){
		//on récup de le type de champ
		$type = "select type from ".$table."_custom where idchamp=$id_champ";
		$res = mysql_query($type);
		$type = mysql_result($res,0,0);
		renseigne_champ_perso($champ['nom'],$type,$champ['value'],$notice_id,$table);
	}else if($create){
		//on l'a pas, on crée
		//enfin on verra ca plus tard...
	}
}

//trouve un champ perso et renvoi son id
function trouve_champ_perso($nom,$table="notices") {
	$rqt = "SELECT idchamp FROM ".$table."_custom WHERE name='" . addslashes($nom) . "'";
	$res = mysql_query($rqt);
	
	if (mysql_num_rows($res)>0)
		return mysql_result($res,0);
	else
		return 0;
}

//Pour renseigner les champs perso
function renseigne_champ_perso($nom,$type,$value,$notice_id,$table="notices") {
	if(!trim($value) or !trim($nom) or !trim($notice_id)  )return false; // On sort si la valeur ou le nom du champ sont vide 
	$mon_champ=trouve_champ_perso($nom,$table);
	if ($mon_champ){
		switch ($type) {
			case "small_text":
				$requete="insert into ".$table."_custom_values (".$table."_custom_champ,".$table."_custom_origine,".$table."_custom_small_text) values('".$mon_champ."','".$notice_id."','".addslashes(trim($value))."')";
				if(!mysql_query($requete)) return false;
				break;
			case "integer":
				$requete="insert into ".$table."_custom_values (".$table."_custom_champ,".$table."_custom_origine,".$table."_custom_integer) values('".$mon_champ."','".$notice_id."','".addslashes(trim($value))."')";
				if(!mysql_query($requete)) return false;
				break;
			case "text":
				$rqt = "select datatype from ".$table."_custom where idchamp = $mon_champ";
				$res = mysql_query($rqt);
				$datatype = @mysql_result($res,0,0);
				if($datatype == "small_text"){
					$requete="insert into ".$table."_custom_values (".$table."_custom_champ,".$table."_custom_origine,".$table."_custom_small_text) values('".$mon_champ."','".$notice_id."','".addslashes(trim($value))."')";			
				}else{
					$requete="insert into ".$table."_custom_values (".$table."_custom_champ,".$table."_custom_origine,".$table."_custom_text) values('".$mon_champ."','".$notice_id."','".addslashes(trim($value))."')";
				}
				if(!mysql_query($requete)) return false;
				break;
			case "date":
				$requete="insert into ".$table."_custom_values (".$table."_custom_champ,".$table."_custom_origine,".$table."_custom_date) values('".$mon_champ."','".$notice_id."','".addslashes(trim($value))."')";
				if(!mysql_query($requete)){
					echo "requete : ".$requete."<br>";
					 return false;
				}
				break;
			case "list":
				$requete="select ".$table."_custom_list_value from ".$table."_custom_lists where ".$table."_custom_list_lib='".addslashes(trim($value))."' and ".$table."_custom_champ='".$mon_champ."' ";
				$resultat=mysql_query($requete);
				if (mysql_num_rows($resultat)) {
					$value2=mysql_result($resultat,0,0);
				} else {
					$requete="select max(".$table."_custom_list_value*1) from ".$table."_custom_lists where ".$table."_custom_champ='".$mon_champ."' ";
					$resultat=mysql_query($requete);
					$max=@mysql_result($resultat,0,0);
					$n=$max+1;
					$requete="insert into ".$table."_custom_lists (".$table."_custom_champ,".$table."_custom_list_value,".$table."_custom_list_lib) values('".$mon_champ."',$n,'".addslashes(trim($value))."')";
					if(!mysql_query($requete)) return false;
					$value2=$n;
				}
				$requete="insert into ".$table."_custom_values (".$table."_custom_champ,".$table."_custom_origine,".$table."_custom_integer) values('".$mon_champ."',$notice_id,$value2)";
				if(!mysql_query($requete)) return false;
				break;
			default:
				return false;
				break;
		}
	}else{
		mysql_query("insert into error_log (error_origin, error_text) values ('aide.import', '".addslashes("Impossible d'inserrer dans le champ : ".$nom)."') ") ;
		return false;
	}
	return true;
		
}	

//Pour le formatage de la date
function decoupe_date($date_nom_formate,$annee_seule=false){
	$date="";
	$tab=preg_split("/\D/",$date_nom_formate);
	
	switch(count($tab)){
		case 3 :
			if(strlen($tab[0]) == 4){
				$date=$tab[0]."-".$tab[1]."-".$tab[2];
			}elseif(strlen($tab[2]) == 4){
				$date=$tab[2]."-".$tab[1]."-".$tab[0];
			}elseif($tab[0] > 31){
				$date="19".$tab[0]."-".$tab[1]."-".$tab[2];
			}elseif($tab[2] > 31){
				$date="19".$tab[2]."-".$tab[1]."-".$tab[0];
			}
			break;
		case 2 :
			if(strlen($tab[0]) == 4){
				$date=$tab[0]."-".$tab[1]."-01";
			}elseif(strlen($tab[1]) == 4){
				$date=$tab[1]."-".$tab[0]."-01";
			}elseif($tab[0] > 31){
				$date="19".$tab[0]."-".$tab[1]."-01";
			}elseif($tab[1] > 31){
				$date="19".$tab[1]."-".$tab[0]."-01";
			}
			break;
		case 1 :
			if(strlen($tab[0]) == 8){
				$date=substr($tab[0],0,4)."-".substr($tab[0],4,2)."-".substr($tab[0],6,2);
			}elseif(strlen($tab[0]) == 6){
				$date=substr($tab[0],0,4)."-".substr($tab[0],4,2)."-01";
			}elseif(strlen($tab[0]) == 4){
				$date=substr($tab[0],0,4)."-01-01";
			}
	}
	
	if($annee_seule){
		return substr($date,0,4);
	}else{
		return $date;
	}
	
}