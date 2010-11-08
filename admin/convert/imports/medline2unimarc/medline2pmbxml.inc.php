<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: medline2pmbxml.inc.php,v 1.8 2009-12-31 15:45:07 dbellamy Exp $

require_once("$class_path/marc_table.class.php");
require_once("$include_path/isbn.inc.php");

function organize_line($tab_line){
$res = array();

for($i=0;$i<count($tab_line);$i++){
	if(preg_match("/([A-Z]{1,4}) *- (.*)/",$tab_line[$i],$matches)){
		$champ = $matches[1];
		if($res[$champ]) {
			$res[$champ] = $res[$champ].", ".trim($matches[2]);		
		} else $res[$champ] = trim($matches[2]);
	} else {
		$res[$champ] = $res[$champ]." ".trim($tab_line[$i]);
	}
}	
	return $res;
}

function convert_medline($notice, $s, $islast, $isfirst, $param_path) {
	global $cols;
	global $ty;
	global $intitules;
	global $base_path,$origine;
	global $tab_functions;
	global $lot;
	global $charset;
	
	if (!$tab_functions) $tab_functions=new marc_list('function');
	$fields=explode("\n",$notice);
	$error="";
	if($fields)
		$data="<notice>\n";
	$lignes = organize_line($fields);
	foreach($lignes as $champ=>$value) {		
		switch($champ){					
			case 'TI':
				//Titre principal
				$titre = $value;					
				break;
			case 'PL':
				//Editeur
				$editeur = $value;
				break;
			case 'AU':
				//Auteur principal
				$auteur = explode(", ",$value);
				break;
			case 'IS':
				//ISBN/ISSN
				$code = $value;
				$pos = strpos($code,"(");
				$endcode = substr($code,$pos);
				$code = trim(substr($code,0,$pos));				
				if(isISBN($code)){
					$infos_isbn=$code." ".$endcode;
				} elseif(isISSN($code)){
					$infos_issn=$code." ".$endcode;
				} else $error = "wrong ISBN/ISSN \n";
				break;
			case 'DP':
				//Date de publication
				$date = $value;
				if($date){	
					$date_elt = explode(' ',$date);	
					if($date_elt[0]) $year = $date_elt[0];
					if($date_elt[2]) $day = $date_elt[2];
					if($date_elt[1]) {
						$mois = $date_elt[1];
						switch($mois){
							case 'Jan':
								$month = "01";
							break;
							case 'Feb':
								$month = "02";
							break;
							case 'Mar':
								$month = "03";
							break;
							case 'Apr':
								$month = "04";
							break;
							case 'May':
								$month = "05";
							break;
							case 'Jun':
								$month = "06";
							break;
							case 'Jul':
								$month = "07";
							break;
							case 'Aug':
								$month = "08";
							break;
							case 'Sep':
								$month = "09";
							break;
							case 'Oct':
								$month = "10";
							break;
							case 'Nov':
								$month = "11";
							break;
							case 'Dec':
								$month = "12";
							break;
						}		
					}
					if($year && $month && $day) $date_sql = $year."-".$month."-".$day;
					else if($year && $month) $date_sql = $year."-".$month."-01";
					else if($year) $date_sql = $year."-01-01";
				}
				break;
			case 'IP':
				//Numéro de bulletin
				$bull_num = $value;
				break;
			case 'VI':
				//Volume
				$bull_vol = $value;
				break;
			case 'AB':
				//Résumé
				$resume = $value;
				break;
			case 'JT':
				//Titre du pério
				$perio_title = $value;
				break;
			case 'LA':
				//Langue
				$langue = $value;
				break;
			case 'TT':
				//Titre parallele
				$titre_parallele = $value;
				break;	
			case 'PG':
				//Pagination
				$pagination = $value;
				break;
			case 'PMID':
				//Pubmed ID
				$pubmed_id = $value;
				break;
			case 'PT':
				//Document Type
				$doc_type = $value;
				break;	
			case 'AID':
				//DOI
				$ids = explode(",",$value);
				if(is_array($ids)){
					for($i=0;$i<count($ids);$i++){
						if(strpos($ids[$i],"[doi]") !== false){
							$doi = trim(substr($ids[$i],0,strpos($ids[$i],"[doi]")));
						}
					}
				}
				break;
			case 'AD':
				$collectivite = $value;
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
	
	if($pubmed_id){
		$data.="<f c='001' ind='  '>\n";
		$data.=htmlspecialchars($pubmed_id,ENT_QUOTES,$charset);
		$data.="</f>\n";
	}
	if($infos_isbn || $pubmed_id){
		$data.=" <f c='010' ind='  '>\n";
		$data.="	<s c='a'>".htmlspecialchars(($pubmed_id ? $pubmed_id : $infos_isbn),ENT_QUOTES,$charset)."</s>";
		$data.="</f>\n";
	} 
	if($langue){
		$data.="<f c='101' ind='  '>\n";				
		$data.="	<s c='a'>".htmlspecialchars($langue,ENT_QUOTES,$charset)."</s>\n";			
		$data.="</f>\n";
	}	
	if($titre){
		$data.="<f c='200' ind='  '>\n";								
		$data.="	<s c='a'>".htmlspecialchars($titre,ENT_QUOTES,$charset)."</s>\n";
		if($titre_parallele) $data.="	<s c='d'>".htmlspecialchars($titre_parallele,ENT_QUOTES,$charset)."</s>\n";
		$data.="</f>\n";
	}
	if($editeur){
		$data.="<f c='210' ind='  '>\n";				
		if($editeur) $data.="	<s c='c'>".htmlspecialchars($editeur,ENT_QUOTES,$charset)."</s>\n";			
		$data.="</f>\n";
	}
	if($pagination){
		$data.="<f c='215' ind='  '>\n";				
		$data.="	<s c='a'>".htmlspecialchars($pagination,ENT_QUOTES,$charset)."</s>\n";			
		$data.="</f>\n";
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
	if($bull_num || $bull_vol || $date || $date_sql){
		$data.="<f c='463' ind='  '>\n";								
		if($bull_num && $bull_vol) 
			$data.="	<s c='v'>"."vol. ".htmlspecialchars($bull_vol,ENT_QUOTES,$charset).", no. ".htmlspecialchars($bull_num,ENT_QUOTES,$charset)."</s>\n";
		else if($bull_num && !$bull_vol)
			$data.="	<s c='v'>no. ".htmlspecialchars($bull_num,ENT_QUOTES,$charset)."</s>\n";
		else if(!$bull_num && $bull_vol)
			$data.="	<s c='v'>vol. ".htmlspecialchars($bull_vol,ENT_QUOTES,$charset)."</s>\n";
		if($date){
			$data.="	<s c='e'>".htmlspecialchars($date,ENT_QUOTES,$charset)."</s>\n";
		}
		if($date_sql){
			$data.="	<s c='d'>".htmlspecialchars($date_sql,ENT_QUOTES,$charset)."</s>\n";
		}
		$data.="	<s c='9'>lnk:bull</s>\n";		
		$data.="</f>\n";
	}
	
	if($collectivite){
		if($auteur){
			for($i=0;$i<count($auteur);$i++){
				$data.="<f c='701' ind='  '>\n";								
				$data.="	<s c='a'>".htmlspecialchars($auteur[$i],ENT_QUOTES,$charset)."</s>\n";
				$data.="</f>\n";
			}
		}
		$coll = explode(",",$collectivite,2);
		$data.="<f c='710' ind='0 '>\n";								
		$data.="	<s c='a'>".htmlspecialchars($coll[0],ENT_QUOTES,$charset)."</s>\n";
		$data.="	<s c='e'>".htmlspecialchars($coll[1],ENT_QUOTES,$charset)."</s>\n";
		$data.="</f>\n";
	} else if($auteur){		
		$data.="<f c='700' ind='  '>\n";								
		$data.="	<s c='a'>".htmlspecialchars($auteur[0],ENT_QUOTES,$charset)."</s>\n";
		$data.="</f>\n";
		if($auteur){
			for($i=1;$i<count($auteur);$i++){
				$data.="<f c='701' ind='  '>\n";								
				$data.="	<s c='a'>".htmlspecialchars($auteur[$i],ENT_QUOTES,$charset)."</s>\n";
				$data.="</f>\n";
			}
		}
	}
	
	if($doc_type){
		switch($doc_type){
			case 'Abstracts':
			case 'Meeting Abstracts':
				$doctype = "Abstract";
				break;
			case 'Academic Dissertations':
				$doctype = "Thesis";
				break;
			case 'Annual Reports':
			case 'Technical Report':
				$doctype = "Report";
				break;
			case 'Book Reviews':
			case 'Review':
				$doctype = "Review";
				break;	
			case 'Classical Article':
			case 'Corrected and Republished Article':
			case 'Journal Article':
			case 'Newspaper Article':
				$doctype = "Article";
				break;
			case 'Comment':		
			case 'Published Erratum':	
				$doctype = "Erratum";
				break;
			case 'Congresses':
				$doctype = "Conference Proceedings";
				break;
			case 'Database':
				$doctype = "Database";
				break;
			case 'Dictionary':
				$doctype = "Dictionary";
				break;
			case 'Directory':
				$doctype = "Directory";
				break;
			case 'Editorial':
				$doctype = "Editorial";
				break;
			case 'Encyclopedias':
				$doctype = "Encyclopedia";
				break;
			case 'Letter':
				$doctype = "Letter";
				break;
			case 'Unpublished Works':
				$doctype = "Preprint";
				break;
			default:
				$doctype = "Article";
				break;
		} 
		if($doctype){
			$data.="<f c='900' ind='  '>\n";
			$data.="	<s c='a'>".htmlspecialchars($doctype,ENT_QUOTES,$charset)."</s>\n";
			$data.="	<s c='l'>Sub-Type</s>\n";
			$data.="	<s c='n'>subtype</s>\n";
			$data.="</f>\n";
		}
	}
	if($doi){
		$data.="<f c='900' ind='  '>\n";
		$data.="	<s c='a'>".htmlspecialchars($doi,ENT_QUOTES,$charset)."</s>\n";
		$data.="	<s c='l'>DOI id</s>\n";
		$data.="	<s c='n'>pmi_doi_identifier</s>\n";
		$data.="</f>\n";
	}
	if($pubmed_id){		
		$data .="<f c='856' ind='  '>\n";
		$data.="	<s c='u'>http://www.ncbi.nlm.nih.gov/pubmed/$pubmed_id</s>\n";
		$data.="</f>\n";
		$data.="<f c='900' ind='  '>\n";
		$data.="	<s c='a'>".htmlspecialchars($pubmed_id,ENT_QUOTES,$charset)."</s>\n";
		$data.="	<s c='l'>Numéro PUBMED</s>\n";
		$data.="	<s c='n'>pmi_xref_dbase_id</s>\n";
		$data.="</f>\n";
	}
	$data .= "</notice>\n";
		
	if (!$error) {
		$r['VALID'] = true; 
	}else {
		$r['VALID']=false;
	}
	$r['ERROR'] = $error;
	$r['DATA'] = $data;
	return $r;
}
?>