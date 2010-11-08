<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: webepires2xmluni.inc.php,v 1.7 2010-08-27 14:01:25 mbertin Exp $

require_once("$class_path/marc_table.class.php");

function make_index($descr,$tete) {
	$data="";
	if ($descr) {
		$d=explode(",",$descr);
		for ($i=0; $i<count($d); $i++) {
			if ($d[$i]) {
				$data.="  <f c='606' ind='  '>\n";
				$data.="    <s c='a'>".htmlspecialchars($tete,ENT_QUOTES)."</s>\n";
				$data.="    <s c='x'>".htmlspecialchars($d[$i],ENT_QUOTES)."</s>\n";
				$data.="  </f>\n";
			}
		}
	}
	return $data;
}

function convert_webepires($notice, $s, $islast, $isfirst, $param_path) {
	global $cols;
	global $intitules;
	global $base_path,$origine;
	global $tab_functions;
	
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
	
	$fields=explode(";;",$notice);
	for ($i=0; $i<count($fields); $i++) {
		$ntable[$cols[$i]]=$fields[$i];
	}
	if ((!$ntable["NOM"])||(!$ntable["SITE"])) {
		$data=""; 
		$error="Titre vide<br />".$notice;
	} else {
		$error="";
		$data="<notice>\n";
		
		//Entête
		if ($s["LOCALBASE"][0]["value"]==DATA_BASE) $rs="c"; else $rs="n";
		$data.="  <rs>".$rs."</rs>\n";
		$dt="w";
		$bl="s";
		$data.="  <dt>".$dt."</dt>\n";
		$data.="<bl>".$bl."</bl>\n";
		$data.="<hl>*</hl>\n<el>1</el>\n<ru>i</ru>\n";
		//Numéro d'enregistrement
		//$data.="  <f c='001' ind='  '>".$ntable["REF"]."</f>\n";
		
		//Titre
		$data.="  <f c='200' ind='  '>\n";
		$data.="    <s c='a'>".htmlspecialchars($ntable["NOM"],ENT_QUOTES)."</s>\n";
		$data.="  </f>\n";
		
		//Site web
		$data.="  <f c='856'>\n";
		$data.="    <s c='u'>".htmlspecialchars($ntable["SITE"],ENT_QUOTES)."</s>\n";
		$data.="  </f>\n";
	
		//Adresse mail : note générale
		if ($ntable["MEL"]) {
			$data.="  <f c='300'>\n";
			$data.="    <s c='a'>".htmlspecialchars($ntable["MEL"],ENT_QUOTES)."</s>\n";
			$data.="  </f>\n";	
		}
	
		//LI : Note de contenu
		if ($ntable["LI"]) {
			$data.="  <f c='327'>\n";
			$data.="    <s c='a'>".htmlspecialchars($ntable["LI"],ENT_QUOTES)."</s>\n";
			$data.="  </f>\n";
		} 
		
		//COMMENT : Résumé
		if ($ntable["COMMENT"]) {
			$data.="  <f c='330'>\n";
			$data.="    <s c='a'>".htmlspecialchars($ntable["COMMENT"],ENT_QUOTES)."</s>\n";
			$data.="  </f>\n";
		} 
		
		//DOC : Indexation Web
		 if ($ntable["DOC"]) {
		 	$data.=make_index($ntable["DOC"],"DOC");
		} 
		
		//Indexations
		if ($ntable["DE"]) {
			$data.=make_index($ntable["DE"],"DE");
		}
		
		if ($ntable["DO"]) {
			$data.="  <f c='676'>\n";
			$data.="    <s c='a'>".htmlspecialchars($ntable["DO"],ENT_QUOTES)."</s>\n";
			$data.="  </f>\n";
		}
		
		//Champs spéciaux
		if (trim($ntable["OP"])) {
			$data.="  <f c='900'>\n";
			$data.="    <s c='a'>".htmlspecialchars($ntable["OP"],ENT_QUOTES)."</s>\n";
			$data.="  </f>\n";
		}else{
			$data.="  <f c='900'>\n";
			$data.="    <s c='a'>".htmlspecialchars("PRISME",ENT_QUOTES)."</s>\n";
			$data.="  </f>\n";
		}
		$data.="  <f c='902'>\n";
		$data.="    <s c='a'>".htmlspecialchars(date("Y")."-".date("m")."-".date("d"),ENT_QUOTES)."</s>\n";
		$data.="  </f>\n";
		$data.="</notice>\n";
	}
	
	if (!$error) $r['VALID'] = true; else $r['VALID']=false;
	$r['ERROR'] = $error;
	$r['DATA'] = $data;
	return $r;
}
?>