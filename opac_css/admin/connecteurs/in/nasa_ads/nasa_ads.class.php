<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: nasa_ads.class.php,v 1.2 2011-03-28 09:24:03 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");
require_once($class_path."/search.class.php");

//
// Scandir for PHP4
//
if(!function_exists('scandir'))
{
    function scandir($dir, $sortorder = 0)
    {
        if(is_dir($dir))
        {
            $dirlist = opendir($dir);
           
            while( ($file = readdir($dirlist)) !== false)
            {
                if(!is_dir($file))
                {
                    $files[] = $file;
                }
            }
           
            ($sortorder == 0) ? asort($files) : arsort($files);
           
            return $files;
        }
        else
        {
        return FALSE;
        break;
        }
    }
}

class nasa_ads extends connector {
	//Variables internes pour la progression de la rï¿½cupï¿½ration des notices
	var $callback_progress;		//Nom de la fonction de callback progression passï¿½e par l'appellant
	var $current_set;			//Set en cours de synchronisation
	var $total_sets;			//Nombre total de sets sï¿½lectionnï¿½s
	var $metadata_prefix;		//Prï¿½fixe du format de donnï¿½es courant
	var $source_id;				//Numï¿½ro de la source en cours de synchro
	var $n_recu;				//Nombre de notices reï¿½ues
	var $xslt_transform;		//Feuille xslt transmise
	var $sets_names;			//Nom des sets pour faire plus joli !!
	var $del_old;				//Supression ou non des notices dejï¿½ existantes
	var $schema_config;
	
	//Rï¿½sultat de la synchro
	var $error;					//Y-a-t-il eu une erreur	
	var $error_message;			//Si oui, message correspondant
	
    function nasa_ads($connector_path="") {
    	parent::connector($connector_path);
    }
    
    function get_id() {
    	return "nasa_ads";
    }
    
    //Est-ce un entrepot ?
	function is_repository() {
		return 2;
	}
    
    function unserialize_source_params($source_id) {
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			$params["PARAMETERS"]=$vars;
		}
		return $params;
    }
    
   function source_get_property_form($source_id) {
    	global $charset;
    	
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		//URL
		if (!isset($url))
			$url = "http://adsabs.harvard.edu";
		$form="<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["nasa_ads_url"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='url' id='url' class='saisie-60em' value='".htmlentities($url,ENT_QUOTES,$charset)."'/>
			</div>
		</div>";
   	
		return $form;
    }
    
    function make_serialized_source_properties($source_id) {
    	global $url;
    	$t = array();
    	$t["url"]=stripslashes($url);
		$this->sources[$source_id]["PARAMETERS"]=serialize($t);
	}
	
	//Récupération  des proriétés globales par défaut du connecteur (timeout, retry, repository, parameters)
	function fetch_default_global_values() {
		$this->timeout=40;
		$this->repository=1;
		$this->retry=3;
		$this->ttl=60000;
		$this->parameters="";
	}
	
	//Formulaire des propriétés générales
	function get_property_form() {
		$this->fetch_global_properties();
		return "";
	}
	
	function make_serialized_properties() {
		$this->parameters="";
	}
	
		
	function cancel_maj($source_id) {
		return true;
	}
	
	function break_maj($source_id) {
		return true;
	}
	
	function form_pour_maj_entrepot($source_id) {
		return false;
	}
	
	//Nécessaire pour passer les valeurs obtenues dans form_pour_maj_entrepot au javascript asynchrone
	function get_maj_environnement($source_id) {
		return array();
	}
	
	function sync_custom_page($source_id) {
		return '';
	}
	
	function maj_entrepot($source_id,$callback_progress="",$recover=false,$recover_env="") {
		return 0;
	}
    function parse_xml($ch,$data) {
		$notices=explode("6",$data);
		print $notices[1];  
    	return strlen($data);
	}
	
	function search($source_id,$query,$search_id) {
		global $base_path,$charset;
		
		$params=$this->get_source_params($source_id);
		$this->fetch_global_properties();
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		if (!isset($url)) {
			$this->error_message = $this->msg["nasa_ads_unconfigured"];
			$this->error = 1;
			return;
		}
		$boolsearch="";
		foreach ($query as $element) {
			$boolsearch.= implode(" ", $element->values)." ";
		}
		$boolsearch=str_replace(" ", "+", $boolsearch);
		$addr=$url."/cgi-bin/basic_connect?version=1&qsearch=".rawurlencode($boolsearch);
		$ch = curl_init();
		// configuration des options CURL
		curl_setopt($ch, CURLOPT_URL, $addr);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	 	
		configurer_proxy_curl($ch);	
	 	$cexec=curl_exec($ch);	 	
 		if (!$cexec) {
 			$this->error=true;
 			$this->error_message="Can't get Lob answer : ".curl_error($ch);
 			
 		} else {
 			if (strtoupper($charset)!="UTF-8") $cexec=utf8_decode($cexec);	
 			
	 		$sep_notices="<tr><td colspan=6><HR></td></tr>";
	 		$query=get_field_betwen_2sep($cexec,$sep_notices,"</table>");
	 		$notices_html=explode($sep_notices,$query);
	 		foreach($notices_html as $notice_html){
	 			$data_notice=array();
		 		$tab_tr=explode("</tr>",$notice_html);	
		 		$tab_td_0=explode("</td>",$tab_tr[0]);	
		 		$tab_td_1=explode("</td>",$tab_tr[1]);	
		 		
		 		$id=get_field_betwen_2sep($notice_html,"value=\"","\"");
		 		$data_notice["id"]=$id;
		 		
		 		$titre=get_field_from_sep($tab_td_1[3],">"); 		
		 		$authors=get_field_from_sep($tab_td_1[1],">");
		 		$date=get_field_from_sep($tab_td_0[4],">");
		 		
		 		$links=explode("</a>",$tab_td_0[5]);
		 		foreach($links as $link){ 			
		 			$doc_type=substr($link,strlen($link)-1);
			 		$doc_link=get_field_betwen_2sep($link,"href=\"","\"");
			 		if($doc_link){	
			 			$data_notice["doc_links"][$doc_type]["link"]=$doc_link;
			 			$data_notice["doc_links"][$doc_type]["label"]=$this->msg["nasa_ads_doc_".strtolower($doc_type)];
		 			}	
		 		}
		 		
		 		$url_notice="http://adsabs.harvard.edu/abs/$id";
		 		$data_notice["Url_notice"]=	$url_notice;	 		
		 		$ch_notice = curl_init();
				// configuration des options CURL
				curl_setopt($ch_notice, CURLOPT_URL, $url_notice);	
				curl_setopt($ch_notice, CURLOPT_RETURNTRANSFER, true);	 	
				configurer_proxy_curl($ch_notice);	
			 	$cexec_notice=curl_exec($ch_notice);	 	
		 		if (!$cexec_notice) {
		 			$this->error=true;
		 			$this->error_message="Can't get Lob answer : ".curl_error($ch_notice);
		 			
		 		} else {
		 			if (strtoupper($charset)!="UTF-8") $cexec_notice=utf8_decode($cexec_notice);	
		 			$cexec_notice=get_field_betwen_2sep($cexec_notice,"Translate This Page</a></strong>","<form method"); 		
		 			
		 			$data_notice["Title"]=get_field_betwen_2sep($cexec_notice,"Title:</b></td><td><br></td><td align=\"left\" valign=\"top\">","</td></tr>",1);
		 			$Authors=get_field_betwen_2sep($cexec_notice,"Authors:</b></td><td><br></td><td align=\"left\" valign=\"top\">","</td></tr>");
		 			$aut_list=explode("</a>",$Authors);
		 			foreach($aut_list as $aut){ 
		 				$autor=get_field_from_sep($aut,">",1);
		 				if($autor)$data_notice["Authors"][]=$autor;
		 			}
		 			$data_notice["Affiliation"]=get_field_betwen_2sep($cexec_notice,"Affiliation:</b></td><td><br></td><td align=\"left\" valign=\"top\">","</td></tr>",1);
		 			$data_notice["Publication"]=get_field_betwen_2sep($cexec_notice,"Publication:</b></td><td><br></td><td align=\"left\" valign=\"top\">","</td></tr>");
		 			$data_notice["Publication_Date"]=get_field_betwen_2sep($cexec_notice,"Publication Date:</b></td><td><br></td><td align=\"left\" valign=\"top\">","</td></tr>");
		 			$Origin=get_field_betwen_2sep($cexec_notice,"Origin:</b></td><td><br></td><td align=\"left\" valign=\"top\">","</td></tr>",1);
		 			$data_notice["Origin"]=get_field_betwen_2sep($Origin,"\">","</a>");
		 			$data_notice["Keywords"]=get_field_betwen_2sep($cexec_notice,"Keywords:</b></td><td><br></td><td align=\"left\" valign=\"top\">","</td></tr>");
		 			$DOI=get_field_betwen_2sep($cexec_notice,"DOI:</b></td><td><br></td><td align=\"left\" valign=\"top\">","</td></tr>");
		 			$data_notice["DOI"]=get_field_betwen_2sep($DOI,"\">","</a>",1);
		 			$Bibliographic_Code=get_field_betwen_2sep($cexec_notice,"Bibliographic Code:</b></td><td><br></td><td align=\"left\" valign=\"top\">","</td></tr>");
		 			$data_notice["Bibliographic_Code"]=get_field_betwen_2sep($Bibliographic_Code,"href=\"","\"");
		 			$data_notice["Abstract"]=get_field_betwen_2sep($cexec_notice,"Abstract</h3>","<hr>",1,"<p><sup><sub>");
		 			$order   = array("\r\n", "\n", "\r");
		 			$data_notice["Abstract"]=str_replace($order, " ", $data_notice["Abstract"]);
		 			//	print"<pre>";print_r ($data_notice);print"</pre>";
		 			$this->rec_record($this->notice_2_uni($data_notice),$source_id,$search_id);
		 			if($nb++>20)		 break;
		 		}
 				curl_close($ch_notice);			 		
 			}	
 		}
 		curl_close($ch);	
		
	}	
	function notice_2_uni($nt) {

		$unimarc=array();
		$unimarc["001"][0]=$nt["id"];

		$unimarc["200"][0]["a"][0]=$nt["Title"];
		
		//Editeurs
		if ($nt["Publication"]) $unimarc["210"][0]["c"][0]=$nt["Publication"];
		if ($nt["Publication_Date"]) $unimarc["210"][0]["d"][]=$nt["Publication_Date"];
		
		// DOI
		if ($nt["DOI"]){
			$unimarc["014"][0]["a"][0]=$nt["DOI"];
			$unimarc["014"][0]["b"][0]="DOI";
		} 
		
		if ($nt["Affiliation"])  $unimarc["300"][0]["a"][0]=$nt["Affiliation"];
		
		//Résumé
		if ($nt["Abstract"])  $unimarc["330"][0]["a"][0]=$nt["Abstract"];
		
		//Auteurs
		$aut=array();
		if (count($nt["Authors"])) {			
			if (count($nt["Authors"])>1) $autf="701"; else $autf="700";
			for ($i=0; $i<count($nt["Authors"]); $i++) {
					
				$name_surname=explode(",",$nt["Authors"][$i]);	
				if(count($name_surname)==2) {
					$aut[$i]["a"][0]=$name_surname[0];
					$aut[$i]["b"][0]=$name_surname[1];
				} else{					
					$aut[$i]["a"][0]=$nt["Authors"][$i];
				} 
			//	$aut[$i]["4"][0]="070";
			}
			$unimarc[$autf]=$aut;			
		}
		if ($nt["Url_notice"]){
			$unimarc["856"][0]["u"][0]=$nt["Url_notice"];
		} 
		
		// Link doc num
		if(count($nt["doc_links"])) {
			$i=0;
			foreach($nt["doc_links"] as $key =>$val){
				$unimarc["897"][$i]["a"][0]=$val["link"];
				if($val["label"])
					$unimarc["897"][$i]["b"][0]=$val["label"];
				else
					$unimarc["897"][$i]["b"][0]=$key;
				$i++;
			}
		}
		// Keywords
		if ($nt["Keywords"])  $unimarc["610"][0]["a"][0]=$nt["Keywords"];			
		// Origin
		if ($nt["Origin"])  $unimarc["801"][0]["b"][0]=$nt["Origin"];
				
		return $unimarc;
	}	
	
	function rec_record($record,$source_id,$search_id) {
		global $charset,$base_path,$url,$search_index;
		
		$date_import=date("Y-m-d H:i:s",time());
		
		//Recherche du 001
		$ref=$record["001"][0];
		//Mise à jour 
		if ($ref) {
			//Si conservation des anciennes notices, on regarde si elle existe
			if (!$this->del_old) {
				$requete="select count(*) from entrepot_source_".$source_id." where ref='".addslashes($ref)."' and search_id='".addslashes($search_id)."'";
				$rref=mysql_query($requete);
				if ($rref) $ref_exists=mysql_result($rref,0,0);
			}
			//Si pas de conservation des anciennes notices, on supprime
			if ($this->del_old) {
				$requete="delete from entrepot_source_".$source_id." where ref='".addslashes($ref)."' and search_id='".addslashes($search_id)."'";
				mysql_query($requete);
			}
			//Si pas de conservation ou reférence inexistante
			if (($this->del_old)||((!$this->del_old)&&(!$ref_exists))) {
				//Insertion de l'entête
				$n_header["rs"]="*";
				$n_header["ru"]="*";
				$n_header["el"]="1";
				$n_header["bl"]="m";
				$n_header["hl"]="0";
				$n_header["dt"]=$this->types[$search_index[$url][0]];
				if (!$n_header["dt"]) $n_header["dt"]="a";
				
				$n_header["001"]=$record["001"][0];
				//Récupération d'un ID
				$requete="insert into external_count (recid, source_id) values('".addslashes($this->get_id()." ".$source_id." ".$ref)."', ".$source_id.")";
				$rid=mysql_query($requete);
				if ($rid) $recid=mysql_insert_id();
				
				foreach($n_header as $hc=>$code) {
					$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values(
					'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".$date_import."',
					'".$hc."','',-1,0,'".addslashes($code)."','',$recid,'".addslashes($search_id)."')";
					mysql_query($requete);
				}
				$field_order=0;
				foreach ($record as $field=>$val) {
					for ($i=0; $i<count($val); $i++) {
						if (is_array($val[$i])) {
							foreach ($val[$i] as $sfield=>$vals) {
								for ($j=0; $j<count($vals); $j++) {
									//if ($charset!="utf-8")  $vals[$j]=utf8_decode($vals[$j]);
									$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values(
									'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".$date_import."',
									'".addslashes($field)."','".addslashes($sfield)."',".$field_order.",".$j.",'".addslashes($vals[$j])."',
									' ".addslashes(strip_empty_words($vals[$j]))." ',$recid,'".addslashes($search_id)."')";
									mysql_query($requete);
								}
							}
						} else {
							//if ($charset!="utf-8")  $vals[$i]=utf8_decode($vals[$i]);
							$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid) values(
							'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".$date_import."',
							'".addslashes($field)."','',".$field_order.",0,'".addslashes($val[$i])."',
							' ".addslashes(strip_empty_words($val[$i]))." ',$recid,'".addslashes($search_id)."')";
							mysql_query($requete);
						}
						$field_order++;
					}
				}
			}
		}
	}
}


function get_field_from_sep($chaine, $deb,$html_decode=0,$keep_tags=""){
	global $charset;
	$i_deb=strpos($chaine,$deb);
	if ($i_deb === false) return "";
	$i_deb+=strlen($deb);
	if($html_decode){
		//return html_entity_decode(substr($chaine,$i_deb),ENT_QUOTES,$charset);	
		return html_entity_decode(strip_tags(substr($chaine,$i_deb),$keep_tags),ENT_QUOTES,$charset); 
	}else
		return substr($chaine,$i_deb);	
	
}
function get_field_betwen_2sep($chaine, $deb,$end,$html_decode=0,$keep_tags=""){
	global $charset;
	$i_deb=strpos($chaine,$deb);
	if ($i_deb === false) return "";
	$i_deb+=strlen($deb);
	$chaine_deb=substr($chaine,$i_deb);
	$i_end=strpos($chaine_deb,$end);
	if ($i_end === false) return "";
	if($html_decode){
		// return html_entity_decode(substr($chaine_deb,0,$i_end),ENT_QUOTES,$charset);
		return html_entity_decode(strip_tags(substr($chaine_deb,0,$i_end),$keep_tags),ENT_QUOTES,$charset); 
	}else
		return substr($chaine_deb,0,$i_end);	
}
