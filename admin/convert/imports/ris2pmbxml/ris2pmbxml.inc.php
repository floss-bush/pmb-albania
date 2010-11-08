<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ris2pmbxml.inc.php,v 1.6 2010-05-31 12:55:42 gueluneau Exp $

require_once("$class_path/marc_table.class.php");
require_once("$include_path/isbn.inc.php");

function organize_line($tab_line){
	$res = array();
	
	for($i=0;$i<count($tab_line);$i++){
		if(preg_match("/([A-Z0-9]{1,4}) *- (.*)/",$tab_line[$i],$matches)){
			$champ = $matches[1];
			if($res[$champ]) {
				$res[$champ] = $res[$champ]."###".trim($matches[2]);		
			} else $res[$champ] = trim($matches[2]);
		} else {
			$res[$champ] = $res[$champ]." ".trim($tab_line[$i]);
		}
	}	
	return $res;
}


function convert_ris($notice, $s, $islast, $isfirst, $param_path) {
	global $cols;
	global $ty;
	global $intitules;
	global $base_path,$origine;
	global $tab_functions;
	global $lot;
	global $charset;
	
	if(mb_detect_encoding($notice) =='UTF-8' && $charset == "iso-8859-1")
		$notice = utf8_decode($notice);
		
	if (!$tab_functions) $tab_functions=new marc_list('function');
	$fields=explode("\n",$notice);
	$error="";
	if($fields)
		$data="<notice>\n";
	$lignes = organize_line($fields);
	foreach($lignes as $champ=>$value) {		
		switch($champ){						
			case 'CT':
			case 'TI':
			case 'T1':
				//Titre principal
				$titre = $value;				
			break;
			case 'T2':
				//Autre info sur titre
				$titre_other = $value;
			break;
			case 'PB':
				//Editeur
				$editeur_nom = $value;
			break;
			case 'CY':
				//Editeur
				$editeur_ville = $value;
			break;
			case 'AU':
				//Autres auteurs
				$autres_auteurs = $value;
			break;
			case 'A1':
				//Auteur principal
				$auteur_principal = $value;
			break;
			case 'A2':
				//Auteur secondaire
				$auteur_secondaire = $value;
			break;			
			case 'SN':
				//ISBN/ISSN
				$code = $value;
				$pos = strpos($code,"(");
				$code = trim(substr($code,0,$pos));
				if(isISBN($code)){
					$infos_isbn=$code;
				} elseif(isISSN($code)){
					$infos_issn=$code;
				} else $error = "wrong ISBN/ISSN \n";
			break;
			case 'UR':
				//URL
				$url = $value;
			break;
			case 'PY':
				//Date de publication (YYYY/MM/DD)
				$dates = explode("/",$value);
				if($dates[0]) $year = $dates[0];
				if($dates[1]) $month = $dates[1];
				if($dates[2]) $day = $dates[2];
				$publication_date = $year;
				if($year && $month && $day){
					$date_sql = str_replace("/","-",$value);
					$mention_date = $value;
				} else if($year && $month && !$day){
					$date_sql = $year."-".$month."-01";
					$mention_date = $year."/".$month;
				} else if($year && !$month && !$day){
					$date_sql = $year."-01-01";
					$mention_date = $year;
				}			
			break;
			case 'TY':
				//Document type
				switch($value){
					case 'ABST':
						$subtype='Abstract';
					break;
					case 'BOOK':
						$subtype='Book';
					break;
					case 'CHAP':
						$subtype='Chapter';
					break;
					case 'COMP':
						$subtype='Computing Program';
					break;
					case 'CONF':
						$subtype='Conference Proceedings';
					break;
					case 'INPR':
						$subtype='Preprint';
					break;
					case 'NEWS':
					case 'JFULL':
						$subtype='Journal';
					break;
					case 'MGZN':
					case 'JOUR':
						$subtype='Article';
					break;
					case 'MAP':
						$subtype='Map';
					break;
					case 'UNPB':
					case 'RPRT':
						$subtype='Report';
					break;
					case 'SLIDE':
						$subtype='Presentation';
					break;
					case 'THES':
						$subtype='Thesis';
					break;
					default :
						$subtype='Article';
					break;
				}				
			break;
			case 'N1':
				//Notes
				$notes = $value;
			break;	
			case 'SP':
				//Start page
				$start_page = $value;
			break;
			case 'EP':
				//End page
				$end_page = $value;
			break;
			case 'KW':
				//Mots cles
				$keywords = $value;	
			break;
			case 'AD':
				//Collectivite
				$collectivite = $value;
			break;
			case 'IS':
				//Numéro de bulletin
				$bull_num = $value;
				break;
			case 'VL':
				//Volume
				$bull_vol = $value;
				break;
			case 'AB':
				//Résumé
				$resume = $value;
				break;
			case 'JF':
				//Titre du pério
				$perio_title = $value;
				break;
			default:
				$data .= '';
			break;
		}		
	}
	
	//Construction du fichier
	
	$data.= "<rs>n</rs>
		  <dt>a</dt>
		  <bl>a</bl>
		  <hl>2</hl>
		  <el>1</el>
		  <ru>i</ru>\n";	
	
	$data.="<f c='001' ind='  '>\n";
	$data.=htmlspecialchars(microtime(),ENT_QUOTES,$charset);
	$data.="</f>\n";

	if($titre){
		$data.="<f c='200' ind='  '>\n";								
		$data.="	<s c='a'>".htmlspecialchars($titre,ENT_QUOTES,$charset)."</s>";
		if($titre_other) $data.="	<s c='e'>".htmlspecialchars($titre_other,ENT_QUOTES,$charset)."</s>";
		$data.="</f>\n";
	}
	if($editeur_nom || $publication_date || $editeur_ville){
		$data.="<f c='210' ind='  '>\n";				
		if($editeur_nom) $data.="	<s c='a'>".htmlspecialchars($editeur_nom,ENT_QUOTES,$charset)."</s>\n";	
		if($editeur_ville) $data.="	<s c='c'>".htmlspecialchars($editeur_ville,ENT_QUOTES,$charset)."</s>\n";			
		if($publication_date) $data.="	<s c='d'>".htmlspecialchars($publication_date,ENT_QUOTES,$charset)."</s>";	
		$data.="</f>\n";
	}	
	if($start_page || $end_page){
		$data.="<f c='215' ind='  '>\n";				
		if($start_page && $end_page) $data.="	<s c='a'>".htmlspecialchars($start_page."-".$end_page,ENT_QUOTES,$charset)."</s>\n";
		if(!$start_page && $end_page) $data.="	<s c='a'>".htmlspecialchars($end_page,ENT_QUOTES,$charset)."</s>\n";
		if($start_page && !$end_page) $data.="	<s c='a'>".htmlspecialchars($start_page,ENT_QUOTES,$charset)."</s>\n";			
		$data.="</f>\n";
	}	
	if($notes){
		$note = explode('###',$notes);
		$doi ="";
		$pubmedid = "";
		for($i=0;$i<count($note);$i++){
			if(strpos($note[$i],"doi:")!== false) {
				$doi = $note[$i];
			} else if (strpos($note[$i],"PubMed ID:")!== false){
				$pubmedid =  $note[$i];
			} else {				
				if(strlen($note[$i]) > 9000){
					$word =wordwrap($note[$i],9000,"####");
					$words = explode("####",$word);
					for($j=0;$j<count($words);$j++){						
						$data.="<f c='300' ind='  '>\n";
						$data.="	<s c='a'>".htmlspecialchars($words[$j],ENT_QUOTES,$charset)."</s>\n";
						$data.="</f>\n";						
					}
				} else {
					$data.="<f c='300' ind='  '>\n";
					$data.="	<s c='a'>".htmlspecialchars($note[$i],ENT_QUOTES,$charset)."</s>\n";
					$data.="</f>\n";
				}
			}
		}	
	}
	if($resume){
		$data.="<f c='330' ind='  '>\n";				
		$data.="	<s c='a'>".htmlspecialchars($resume,ENT_QUOTES,$charset)."</s>\n";			
		$data.="</f>\n";
	}		
	if($perio_title){
		$data.="<f c='461' ind='  '>\n";				
		$data.="	<s c='t'>".htmlspecialchars($perio_title,ENT_QUOTES,$charset)."</s>\n";	
		if($infos_issn) $data.="	<s c='x'>".htmlspecialchars($infos_issn,ENT_QUOTES,$charset)."</s>\n";	
		$data.="	<s c='9'>lnk:perio</s>\n";		
		$data.="</f>\n";
	}	
	if($bull_num || $bull_vol){
		$data.="<f c='463' ind='  '>\n";								
		if($bull_num && $bull_vol) 
			$data.="	<s c='v'>"."vol. ".htmlspecialchars($bull_vol,ENT_QUOTES,$charset).", no. ".htmlspecialchars($bull_num,ENT_QUOTES,$charset)."</s>\n";
		else if($bull_num && !$bull_vol)
			$data.="	<s c='v'>no. ".htmlspecialchars($bull_num,ENT_QUOTES,$charset)."</s>\n";
		else if(!$bull_num && $bull_vol)
			$data.="	<s c='v'>vol. ".htmlspecialchars($bull_vol,ENT_QUOTES,$charset)."</s>\n";
		if($date_sql)
			$data.="	<s c='d'>".htmlspecialchars($date_sql,ENT_QUOTES,$charset)."</s>\n";
		if($mention_date)
			$data.="	<s c='e'>".htmlspecialchars($mention_date,ENT_QUOTES,$charset)."</s>\n";
		$data.="	<s c='9'>lnk:bull</s>\n";		
		$data.="</f>\n";
	}
	if($keywords){
		$mots = explode('###',$keywords);
		for($i=0;$i<count($mots);$i++){
			$data.="<f c='610' ind='0 '>\n";
			$data.="	<s c='a'>".htmlspecialchars($mots[$i],ENT_QUOTES,$charset)."</s>\n";
			$data.="</f>\n";
		}
	}
	
	if($auteur_principal){
		$aut = explode(", ",$auteur_principal);
		$data.="<f c='700' ind='  '>\n";								
		$data.="	<s c='a'>".htmlspecialchars($aut[0],ENT_QUOTES,$charset)."</s>\n";
		$data.="	<s c='b'>".htmlspecialchars($aut[1],ENT_QUOTES,$charset)."</s>\n";
		if($aut[2]) $data.="	<s c='c'>".htmlspecialchars($aut[2],ENT_QUOTES,$charset)."</s>\n";
		$data.="</f>\n";
	}
	
	if($collectivite){
		$collectivites = explode("###",$collectivite);
		if((count($collectivites) == 1) && !$auteur_principal) {
			$coll_elt = explode(", ",$collectivites[0],2);
			$coll_nom = $coll_elt[0];
			$coll_pays = trim(substr($coll_elt[1],(strrpos($coll_elt[1],", ")+1)));
			$coll_lieu = trim(substr($coll_elt[1],0,-(strlen($coll_pays)+2)));
			$data.="<f c='710' ind='0 '>\n";								
			$data.="	<s c='a'>".htmlspecialchars($coll_nom,ENT_QUOTES,$charset)."</s>\n";
			$data.="	<s c='e'>".htmlspecialchars($coll_lieu,ENT_QUOTES,$charset)."</s>\n";
			$data.="	<s c='m'>".htmlspecialchars($coll_pays,ENT_QUOTES,$charset)."</s>\n";
			$data.="</f>\n";
		} else {
			for($i=0;$i<count($collectivites);$i++){
				$coll_elt = explode(", ",$collectivites[$i],2);
				$coll_nom = $coll_elt[0];
				$coll_pays = trim(substr($coll_elt[1],(strrpos($coll_elt[1],", ")+1)));
				$coll_lieu = trim(substr($coll_elt[1],0,-(strlen($coll_pays)+2)));
				
				$data.="<f c='711' ind='0 '>\n";								
				$data.="	<s c='a'>".htmlspecialchars($coll_nom,ENT_QUOTES,$charset)."</s>\n";
				$data.="	<s c='e'>".htmlspecialchars($coll_lieu,ENT_QUOTES,$charset)."</s>\n";
				$data.="	<s c='m'>".htmlspecialchars($coll_pays,ENT_QUOTES,$charset)."</s>\n";
				$data.="</f>\n";
			}
		} 
	}
	if($autres_auteurs){
		$others = explode("###",$autres_auteurs);
		for($i=0;$i<count($others);$i++){
			$aut = explode(", ",$others[$i]);
			$data.="<f c='701' ind='  '>\n";								
			$data.="	<s c='a'>".htmlspecialchars($aut[0],ENT_QUOTES,$charset)."</s>\n";
			$data.="	<s c='b'>".htmlspecialchars($aut[1],ENT_QUOTES,$charset)."</s>\n";
			if($aut[2]) $data.="	<s c='c'>".htmlspecialchars($aut[2],ENT_QUOTES,$charset)."</s>\n";
			$data.="</f>\n";
		}
	}
	if($auteur_secondaire){
		$secs = explode("###",$auteur_secondaire);
		for($i=0;$i<count($secs);$i++){
			$aut = explode(", ",$secs);
			$data.="<f c='702' ind='  '>\n";								
			$data.="	<s c='a'>".htmlspecialchars($aut[0],ENT_QUOTES,$charset)."</s>\n";
			$data.="	<s c='b'>".htmlspecialchars($aut[1],ENT_QUOTES,$charset)."</s>\n";
			if($aut[2]) $data.="	<s c='c'>".htmlspecialchars($aut[2],ENT_QUOTES,$charset)."</s>\n";
			$data.="</f>\n";
		}
	}
	
	if($url){
		$data.="<f c='856' ind='  '>\n";
		$data.="	<s c='u'>".htmlspecialchars($url,ENT_QUOTES,$charset)."</s>";
		$data.="</f>\n";
	}	
	if($subtype){
		$data.="<f c='900' ind='  '>\n";
		$data.="	<s c='a'>".htmlspecialchars($subtype,ENT_QUOTES,$charset)."</s>\n";
		$data.="	<s c='l'>Sub-Type</s>\n";
		$data.="	<s c='n'>subtype</s>\n";
		$data.="</f>\n";
	}
	if($doi){
		$doi = trim(str_replace("doi:","",$doi));
		if($doi){
			$data.="<f c='900' ind='  '>\n";
			$data.="	<s c='a'>".htmlspecialchars($doi,ENT_QUOTES,$charset)."</s>\n";
			$data.="	<s c='l'>DOI id</s>\n";
			$data.="	<s c='n'>pmi_doi_identifier</s>\n";
			$data.="</f>\n";
		}
	}
	if($pubmedid){	
		$pubmedid = trim(str_replace("PubMed ID:","",$pubmedid));
		if($pubmedid){	
			$data.="<f c='900' ind='  '>\n";
			$data.="	<s c='a'>".htmlspecialchars($pubmedid,ENT_QUOTES,$charset)."</s>\n";
			$data.="	<s c='l'>Numéro PUBMED</s>\n";
			$data.="	<s c='n'>pmi_xref_dbase_id</s>\n";
			$data.="</f>\n";
		}
	}
	$data .= "</notice>\n";

	if (!$error) $r['VALID'] = true; else $r['VALID']=false;
	$r['ERROR'] = $error;
	$r['DATA'] = $data;
	return $r;
}
?>