<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_expl_supagro.inc.php,v 1.1.6.2 2011-07-08 12:38:43 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


function calculer_cote($text=''){
	global $f_ex_location;
	
	switch($f_ex_location){
		case '8':
		//Bartoli
		$result = calculer_cote_bartoli($text);
		break;
		case '9':
		//La Gaillarde	
		$result = calculer_cote_gaillarde($text);
		break;
		case '12':	
		//UMR	
		$result = calculer_cote_bartoli($text);
		break;
		case '19':
		//IRC	
		$result = calculer_cote_irc($text);
		break;
		
	}
	
	return $result;
}


/*
 * Fonction de calcul de cote pour Bartoli et l'UMR Innovation
 */
function calculer_cote_bartoli($text=""){
	
	global $f_ex_location, $dbh;
	
	$section = 'f_ex_section'.$f_ex_location;
	global $$section;
	
	$array_result = array();
	
	$req_section = " select section_libelle from docs_section where idsection='".$$section."'";
	$res_sect = mysql_query($req_section,$dbh);
	$sec_libelle = mysql_result($res_sect,0,0);
	
	if($$section != 27) 
		$array_sec = explode(' ',strip_empty_words($sec_libelle));
	else $array_sec = array();
	
	$req_index = "select indexint_id as id, indexint_name as name from indexint where index_indexint REGEXP '(".implode('|',$array_sec).")'
	  and indexint_name like '".addslashes($text)."%' and num_pclass in('2','3') order by indexint_name, num_pclass limit 20";
	$res_index = mysql_query($req_index,$dbh);
	if(mysql_num_rows($res_index) >= 1) {		
		while(($cote = mysql_fetch_object($res_index))){
			$req_cote = "
				select  if(isnull(expl_cote),concat(indexint_name,' ','1'),expl_cote) as cote, if(isnull(expl_cote),0,1) as exist
				from exemplaires
				join notices on expl_notice=notice_id
				left join indexint on indexint=indexint_id 
				where expl_location='".$f_ex_location."' 
			"; 
			
			if($cote->name == "BR" || $cote->name == "S BR"){
				$req_cote.="and (expl_cote like 'BR %' OR expl_cote like 'S BR %')";
			}else{
				$req_cote.="and expl_cote like '".addslashes($cote->name)." %'";
			}
			$res_cote = mysql_query($req_cote,$dbh);
			if(mysql_num_rows($res_cote)){
				$liste_cotes=array();
				while(($cotes = mysql_fetch_object($res_cote))){
					$liste_cotes[] = $cotes->cote;											 											
				} 
				if($cote->name == "BR" || $cote->name == "S BR"){
					$val_1 = str_replace("BR ","",clean_cote_bartoli("BR",$liste_cotes));
					$val_2 = str_replace("S BR ","",clean_cote_bartoli("S BR",$liste_cotes));
					if($val_1*1 > $val_2*1){
						$array_result[] =$cote->name." ".$val_1;
					}else{
						$array_result[] =$cote->name." ".$val_2;
					}
				}else{
					$array_result[] = clean_cote_bartoli($cote->name,$liste_cotes);
				}
			} else $array_result[] = $cote->name." 1";
		}
	} else {
		$req_index = "select indexint_id as id, indexint_name as name from indexint where num_pclass in('2','3') 
		  and indexint_name like '".addslashes($text)."%'  order by indexint_name, num_pclass limit 20";
		$res_index = mysql_query($req_index,$dbh);
		
		while(($cote = mysql_fetch_object($res_index))){
			$req_cote = "
				select  if(isnull(expl_cote),concat(indexint_name,' ','1'),expl_cote) as cote, if(isnull(expl_cote),0,1) as exist
				from exemplaires
				join notices on expl_notice=notice_id
				left join indexint on indexint=indexint_id 
				where expl_location='".$f_ex_location."' 
			"; 
			
			if($cote->name == "BR" || $cote->name == "S BR"){
				$req_cote.="and (expl_cote like 'BR %' OR expl_cote like 'S BR %')";
			}else{
				$req_cote.="and expl_cote like '".addslashes($cote->name)." %'";
			}
			$res_cote = mysql_query($req_cote,$dbh);
			if(mysql_num_rows($res_cote)){
				$liste_cote=array();
				while(($cotes = mysql_fetch_object($res_cote))){
					$liste_cote[] = $cotes->cote;			 											
				}
				if($cote->name == "BR" || $cote->name == "S BR"){
					$val_1 = str_replace("BR ","",clean_cote_bartoli("BR",$liste_cote));
					$val_2 = str_replace("S BR ","",clean_cote_bartoli("S BR",$liste_cote));
					if($val_1*1 > $val_2*1){
						$array_result[] =$cote->name." ".$val_1;
					}else{
						$array_result[] =$cote->name." ".$val_2;
					}
				}else{
					$array_result[] = clean_cote_bartoli($cote->name,$liste_cote);
				}
				
			} else $array_result[] = $cote->name." 1";
		}
	}
	
	return $array_result;	
}


/*
 * Fonction de calcul de cote pour l'IRC
 */
function calculer_cote_irc($text=""){
	
	global $f_ex_location, $dbh;
	
	$section = 'f_ex_section'.$f_ex_location;
	global $$section;
	
	$array_result = array();
	
	$req_section = " select section_libelle from docs_section where idsection='".$$section."'";
	$res_sect = mysql_query($req_section,$dbh);
	$sec_libelle = mysql_result($res_sect,0,0);
	
	if($$section != 27) 
		$array_sec = explode(' ',strip_empty_words($sec_libelle));
	else $array_sec = array();
		$req_index = "select indexint_id as id, indexint_name as name from indexint where num_pclass in('4','6')
		 and indexint_name like '".addslashes($text)."%'  order by indexint_name, num_pclass limit 20";
		$res_index = mysql_query($req_index,$dbh);
		while(($cote = mysql_fetch_object($res_index))){
			$nom=""; 
			if(strpos($cote->name,'-')!==false){ 
				$nom = $cote->name;
				$clause = " and expl_cote like '".addslashes($nom)."%'";
			} else {
				$nom = $cote->name;
				$clause = " and expl_cote like '".addslashes($nom)." %'";
			}
			$req_cote = "
				select  if(isnull(expl_cote),concat(indexint_name,' ','1'),expl_cote) as cote, if(isnull(expl_cote),0,1) as exist, substring_index(expl_cote,' ',1) as name
				from exemplaires
				join notices on expl_notice=notice_id
				left join indexint on indexint=indexint_id 
				where expl_location='".$f_ex_location."' ".$clause;	
			$res_cote = mysql_query($req_cote,$dbh);
			if(mysql_num_rows($res_cote)){
				$liste_cotes=array();
				while(($cotes = mysql_fetch_object($res_cote))){
					$liste_cotes[] = $cotes->cote;											 											
				}
				$array_result[] = clean_cote_bartoli($nom,$liste_cotes);
			} else {
				$array_result[] = $cote->name." 1";
			}
		}
	return $array_result;
}

/*
 * Fonction de calcul de cote pour Supagro La Gaillarde
 */
function calculer_cote_gaillarde($text){
	global $f_ex_location, $dbh;
	
	$section = 'f_ex_section'.$f_ex_location;
	global $$section;
	
	$array_result = array();
	
	$req_section = " select section_libelle from docs_section where idsection='".$$section."'";
	$res_sect = mysql_query($req_section,$dbh);
	$sec_libelle = mysql_result($res_sect,0,0);
	
	$tab_section_auto = array("Congrès"=>"20","Accueil"=>"Accueil","ANL"=>"ANL","ECO"=>"ECO",
		"ENV"=>"ENV","F"=>"F","GES"=>"GES","IAA"=>"IAA","Langues"=>"LANG","MAGASIN"=>"MAGASIN","MVV"=>"MVV",
	    "PROF"=>"PROF","S"=>"S", "SCH"=>"SCH","SVI"=>"SVI","U"=>"U","VEG"=>"VEG","VIDEO"=>"VIDEO");
	
	if($tab_section_auto[$sec_libelle]){
		//Si on est dans un section d'incrément automatique		
		//On récupère le maximum de toutes les cotes
		$req_max = "select expl_cote
			from exemplaires 
			where expl_location='".$f_ex_location."' 
			and expl_cote REGEXP '(".implode('/|',$tab_section_auto).")'
		";
		$res_max = mysql_query($req_max,$dbh);
		$regexp = $cotes = array();

		while (($expl = mysql_fetch_object($res_max))){
			if(preg_match("/([0-9]*)$/",$expl->expl_cote,$regexp))
				$cotes [] = $regexp[1];
		}
		$max=0;
		for($i=0;$i<count($cotes);$i++){
			$max = ($max < $cotes[$i] ? $cotes [$i] : $max );
		}
		//On calcule les cotes dispo en fonction de la section et de la saisi de l'user
		$req_cote = "select indexint_name as name from indexint 
			where (indexint_name like '".addslashes($tab_section_auto[$sec_libelle])."%') 
			and (indexint_name like '".addslashes($text)."%') 
			and num_pclass ='1'
			order by indexint_name, num_pclass limit 20";
		$res_cote = mysql_query($req_cote,$dbh);
		while(($cote = mysql_fetch_object($res_cote))){
			$array_result[] = $cote->name." ".($max+1);
		}
	} else {
		//Catalogage manuelle
		if($$section != 27) 
			$array_sec = explode(' ',strip_empty_words($sec_libelle));
		else $array_sec = array();
		$req_index = "select indexint_id as id, indexint_name as name from indexint 
			where index_indexint like '%".addslashes(strip_empty_words($sec_libelle))."%' and num_pclass='1' 
			and index_indexint like '".addslashes($text)."'
			order by indexint_name, num_pclass
			limit 20
			";
		$res_index = mysql_query($req_index,$dbh);
		if(mysql_num_rows($res_index) == 0){
			$req_index = "select indexint_id as id, indexint_name as name from indexint 
				where index_indexint REGEXP '(".implode('|',$array_sec).")'  and num_pclass='1' 
				and indexint_name like '".addslashes($text)."%'
				order by indexint_name, num_pclass
				limit 20
				";
			$res_index = mysql_query($req_index,$dbh);
		}
		if(mysql_num_rows($res_index) >=1){
			while(($cote = mysql_fetch_object($res_index))){			
				$array_result[] = $cote->name;
			}
		} else $array_result[] = $sec_libelle;
	}
	return $array_result;
}


/*
 * Fonction de nettoyage et de construction de cote
 */
function clean_cote($indexation,$cotes){
	
	$tab_cote = array();
		
	$tab_cote = explode('|',$cotes);
	$nb_max = 0;
	for($i=0;$i<count($tab_cote);$i++){
		$regexp = "/".$indexation." ([0-9]*)[^0-9]*/";
		if(preg_match($regexp,$tab_cote[$i],$result)){
			  $nb_max = ($result[1] > $nb_max ?  $result[1] : $nb_max );
		}
	}
	return $indexation." ".($nb_max+1);
	
}

function clean_cote_bartoli($indexation,$cotes){
	
	$nb_max = 0;
	for($i=0;$i<count($cotes);$i++){
		$tiret=false;
		if(strpos($indexation,"-")!== false){
			$tiret = true;
			$regexp = "/".$indexation."([0-9]*)[^0-9]*/";
		} else {
			$regexp = "/".$indexation." ([0-9]*)[^0-9]*/";
		}
		if(preg_match($regexp,$cotes[$i],$result)){
			 $nb_max = ($result[1] > $nb_max ?  $result[1] : $nb_max );
		}
	}
	return ($tiret ? $indexation.($nb_max+1) : $indexation." ".($nb_max+1));
	
}
?>