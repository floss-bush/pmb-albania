<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: amazon.class.php,v 1.20.2.2 2011-05-12 12:06:11 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");
require_once($class_path."/nusoap/nusoap.php");
require_once($include_path."/notice_affichage.inc.php");

class amazon extends connector {
	//Variables internes pour la progression de la récupération des notices
	var $del_old;				//Supression ou non des notices dejà existantes
	
	var $profile;				//Profil Amazon
	var $match;					//Tableau des critères UNIMARC / AMAZON
	var $current_site;			//Site courant du profile (n°)
	var $searchindexes;			//Liste des indexes de recherche possibles pour le site
	var $current_searchindex;	//Numéro de l'index de recherche de la classe
	var $match_index;			//Type de recherche (power ou simple)
	var $types;					//Types de documents pour la conversino des notices
	
	//Résultat de la synchro
	var $error;					//Y-a-t-il eu une erreur	
	var $error_message;			//Si oui, message correspondant
	
    function amazon($connector_path="") {
    	parent::connector($connector_path);
    	$xml=file_get_contents($connector_path."/profil.xml");
 		$this->profile=_parser_text_no_function_($xml,"AWSCONFIG");
    }
    
    function get_id() {
    	return "amazon";
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
    
    function get_libelle($message) {
    	if (substr($message,0,4)=="msg:") return $this->msg[substr($message,4)]; else return $message;
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
		$sites=$this->profile["SITES"][0]["SITE"];
		if (!$url) $url=$sites[0]["COUNTRY"];
		if (!$max_return) $max_return=100;
		
		$form="
		<script>var old_search_index='search_index_".$url."'</script>
		<div class='row'>
			<div class='colonne3'>
				<label for='url'>".$this->msg["amazon_site"]."</label>
			</div>
			<div class='colonne_suite'>
				<select name='url' id='url' onChange='if (old_search_index) document.getElementById(old_search_index).style.display=\"none\"; document.getElementById(\"search_index_\"+this.options[this.selectedIndex].value).style.display=\"block\"; old_search_index=\"search_index_\"+this.options[this.selectedIndex].value;'>";
		for ($i=0; $i<count($sites); $i++) {
			$form.="		<option value='".$sites[$i]["COUNTRY"]."' ".($url==$sites[$i]["COUNTRY"]?"selected":"").">".$this->get_libelle($sites[$i]["COMMENT"])."</option>\n";
		}
		$form.="
				</select>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='max_return'>".$this->msg["amazon_max_return"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='max_return' id='max_return' value='".htmlentities($max_return,ENT_QUOTES,$charset)."' size='10'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='search_index'>".$this->msg["amazon_search_in"]."</label>
			</div>
			<div class='colonne_suite'>";
		for ($i=0; $i<count($sites); $i++) {
			$country=$sites[$i]["COUNTRY"];
			$searchindexes=$sites[$i]["SEARCHINDEXES"][0]["SEARCHINDEX"];
			$form.="
					<select name='search_index[$country][]' id='search_index_$country' style='".((($url==$country)||(($url=="")&&($country==$sites[0]["COUNTRY"])))?"display:block":"display:none")."'>
						";
			for ($j=0; $j<count($searchindexes); $j++) {
				if ($search_index[$country]=="") $search_index[$country]=array();
				$form.="<option value='".htmlentities($searchindexes[$j]["TYPE"],ENT_QUOTES,$charset)."' ".(array_search($searchindexes[$j]["TYPE"],$search_index[$country])!==false?"selected":"").">".htmlentities($this->get_libelle($searchindexes[$j]["COMMENT"]),ENT_QUOTES,$charset)."</option>\n";
			}
			$form.="
				</select>";
		}
		$form.="
			</div>
		</div>
		<div class='row'></div>";
		return $form;
    }
    
    function make_serialized_source_properties($source_id) {
    	global $url,$response_group,$search_index,$max_return;
    	$t["url"]=stripslashes($url);
    	$t["response_group"]=$response_group;
  		$t["search_index"]=$search_index;
  		$t["max_return"]=$max_return;
		$this->sources[$source_id]["PARAMETERS"]=serialize($t);
	}
	
	//Récupération  des proriétés globales par défaut du connecteur (timeout, retry, repository, parameters)
	function fetch_default_global_values() {
		$this->timeout=5;
		$this->repository=2;
		$this->retry=3;
		$this->ttl=1800;
		$this->parameters="";
	}
	
	 //Formulaire des propriétés générales
	function get_property_form() {
		global $charset;
		$this->fetch_global_properties();
		//Affichage du formulaire en fonction de $this->parameters
		if ($this->parameters) {
			$keys = unserialize($this->parameters);
			$accesskey= $keys['accesskey'];
			$secretkey=$keys['secretkey'];
		} else {
			$accesskey="";
			$secretkey="";
		}	
		$r="<div class='row'>
				<div class='colonne3'><label for='accesskey'>".$this->msg["amazon_key"]."</label></div>
				<div class='colonne-suite'><input type='text' name='accesskey' value='".htmlentities($accesskey,ENT_QUOTES,$charset)."'/></div>
			</div>
			<div class='row'>
				<div class='colonne3'><label for='secretkey'>".$this->msg["amazon_secret_key"]."</label></div>
				<div class='colonne-suite'><input type='text' class='saisie-50em' name='secretkey' value='".htmlentities($secretkey,ENT_QUOTES,$charset)."'/></div>
			</div>";
		return $r;
	}
    
    function make_serialized_properties() {
    	global $accesskey, $secretkey;
		//Mise en forme des paramètres à partir de variables globales (mettre le résultat dans $this->parameters)
		$keys = array();
    	
    	$keys['accesskey']=$accesskey;
		$keys['secretkey']=$secretkey;
		$this->parameters = serialize($keys);
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
		
	function cancel_maj($source_id) {
		return false;
	}
	
	function break_maj($source_id) {
		return false;
	}
	
	function parse_profile() {
		global $url,$search_index;
		$matches=$this->profile["MATCHES"][0]["MATCH"];
		for ($j=0; $j<count($matches); $j++) {
			$this->match[$matches[$j]["TYPE"]]=$matches[$j]["CRITERIA"];
		}
		//Récupération des sites
		$this->current_site=false;
		for ($i=0; $i<count($this->profile["SITES"][0]["SITE"]); $i++) {
			if ($this->profile["SITES"][0]["SITE"][$i]["COUNTRY"]==$url) {
				$this->current_site=$i;
				break;
			}
		}
		$this->searchindexes=$this->profile["SITES"][0]["SITE"][$this->current_site]["SEARCHINDEXES"][0]["SEARCHINDEX"];
		//Recherche des critères
		for ($i=0; $i<count($this->searchindexes); $i++) {
			if ($this->searchindexes[$i]["TYPE"]==$search_index[$url][0]) {
				$this->current_searchindex=$i;
				break;
			}
		}
		//Est-ce une recherche POWER ?
		if ($this->searchindexes[$this->current_searchindex]["POWER"]=="yes")
			$this->match_index="power";
		else
			$this->match_index="simple";
		//Parse des types de document
		$types=$this->profile["TYPES"][0]["TYPE"];
		for ($i=0; $i<count($types); $i++) {
			$this->types[$types[$i]["NAME"]]=$types[$i]["DT"];
		}
	}
	
	function parse_query($query,$first_call=false) {
		global $url,$search_index;
		
		//Si c'est le premier appel du parser, on indique le type de recherche
		if ($first_call) $searchattr["TYPE"]=$this->match_index;
		
		//Affectation de la grillle des critères autorisés
		$criterias=$this->searchindexes[$this->current_searchindex]["CRITERIA"];
		$auth_criterias=array();
		for ($i=0; $i<count($criterias); $i++) {
			$auth_criterias[$criterias[$i]["NAME"]]=true;
		}
		
		//Transformation
		for ($i=0; $i<count($query); $i++) {
			if (!$query[$i]->sub) {
				
				//Enlevons tous les caractères rigolos des ISBNs
				if ($query[$i]->ufield == '010$a') {
					for($k=0, $acount=count($query[$i]->values); $k<$acount;$k++) {
						$query[$i]->values[$k] = preg_replace('/-|\.| /', '', $query[$i]->values[$k]);;
					}
				}
				
				$af=explode(":",$query[$i]->ufield);
				$isid=false;
				if (count($af)>1) {
					if ($af[0]=="id") $isid=true; 
					$amf=$af[1];
				} else $amf=$af[0];
				$amazon_field=array();
				$op=$query[$i]->op;
				for ($j=0; $j<count($this->match[$this->match_index]); $j++) {
					//Est-ce que l'opérateur est exclu ??
					$can_translate=false;
					if (($this->match[$this->match_index][$j]["EXCLUDEOPERATORS"])&&(in_array($op,explode(",",$this->match[$this->match_index][$j]["EXCLUDEOPERATORS"]))===false)) {
						$can_translate=true;
					} else if ($this->match[$this->match_index][$j]["OPERATORS"]) {
						if (in_array($op,explode(",",$this->match[$this->match_index][$j]["OPERATORS"]))!==false) $can_translate=true;
					} else {
						$can_translate=true;
					}
					//Si l'opérateur est autorisé alors on regarde si le critère correspond
					if ($can_translate) {
						if (in_array($amf,explode(",",$this->match[$this->match_index][$j]["UNIMARCFIELD"]))!==false) {
							$azf=$this->match[$this->match_index][$j]["NAME"];
							//Le champ est-il autorisé dans cette configuration ?
							if ((($this->searchindexes[$this->current_searchindex]["POWER"]!="yes")&&($auth_criterias[$azf]))||($this->searchindexes[$this->current_searchindex]["POWER"]=="yes")) {
								$amazon_field[]=$azf;
							}
						}
						if ($this->match[$this->match_index][$j]["UNIMARCFIELD"]=="ALLOTHERS") {
							$allothers=$this->match[$this->match_index][$j]["NAME"];
						}
					}
				}		
				if (!count($amazon_field)) $amazon_field[]=$allothers;
				$t=array();
				$t["FIELDS"]=$amazon_field;
				if ($isid) {
					$t["VALUE"][0]=$this->get_values_from_id($query[$i]->values[0],$amf);
				} else $t["VALUE"]=$query[$i]->values;
				if (($query[$i]->inter)&&($i)) $t["INTER"]=$query[$i]->inter;
				$searchattr[]=$t; 
			} else {
				$t["FIELDS"]="";
				$t["VALUE"]="";
				$t["SUB"]=$this->parse_query($query[$i]->sub);
				if (($query[$i]->inter)&&($i)) $t["INTER"]=$query[$i]->inter;
				$searchattr[]=$t;
			}
		}
		return $searchattr;
		//return $r;
	}
	
	function make_power_query($query) {
		$r="";
		for ($i=0; $i<count($query)-1; $i++) {
			if (($query[$i]["INTER"])&&($i>0)) {
				if ($query[$i]["INTER"]=="ex") $inter="and not"; else $inter=$query[$i]["INTER"];
				$r.=" ".$inter." ";
			}
			if ($query[$i]["SUB"]) 
				$r.="(".$this->make_power_query($query[$i]["SUB"]).")";
			else {
				$expr=array();
				for ($j=0; $j<count($query[$i]["VALUE"]); $j++) {
					$query[$i]["VALUE"][$j]=convert_diacrit($query[$i]["VALUE"][$j]);
				}
				for ($j=0; $j<count($query[$i]["FIELDS"]); $j++) {
					$expr[]=$query[$i]["FIELDS"][$j].":".implode(" or ",$query[$i]["VALUE"]);
				}
				$r.=implode(" or ",$expr);
			}
		}
		return array(0=>array("PARAM"=>array("Power"=>$r)));
	}
	
	function make_simple_search($query) {
		$r=array();
		for ($i=0; $i<count($query)-1; $i++) {
			$q=array();
			if (($query[$i]["INTER"])&&($i>0)) {
				$q["INTER"]=$query[$i]["INTER"];
			}
			if ($query[$i]["SUB"]) 
				$q["SUB"]=$this->make_simple_search($query[$i]["SUB"]);
			else {
				$expr=array();
				for ($j=0; $j<count($query[$i]["VALUE"]); $j++) {
					$query[$i]["VALUE"][$j]=convert_diacrit($query[$i]["VALUE"][$j]);
				}
				$req="";
				for ($j=0; $j<count($query[$i]["FIELDS"]); $j++) {
					for ($k=0; $k<count($query[$i]["VALUE"]); $k++) {
						$param="";
						$param[$query[$i]["FIELDS"][$j]]=$query[$i]["VALUE"][$k];
						$expr[]=$param;
					}
				}
				if (count($expr)>1) {
					$sub=array();
					for ($j=0; $j<count($expr); $j++) {
						$q1=array();
						if ($j>0) $q1["INTER"]="or";
						$q1["PARAM"]=$expr[$j];
						$sub[]=$q1;
					}
					$q["SUB"]=$sub;
				} else {
					$q["PARAM"]=$expr[0];
				}
			}
			$r[]=$q;
		}
		return $r;
	}
	
	function store_asins($result,$inter,$first=false,&$n) {
		//Lecture et stockage des Items
		$asins=array();
		$items=$this->soap2array($result["Items"],"Item");
		for ($j=0; $j<count($items); $j++) {
			$asins[]="('".$items[$j]["ASIN"]."')";
		}
		$n+=count($items);
		if ($first) {
			$requete="create temporary table amazon_items (asin varchar(50), primary key(asin))";
			mysql_query($requete);
			if (count($asins)) mysql_query("insert into amazon_items values ".implode(",",$asins));
		} else {
			if (($inter=="and")||($inter=="ex")) {
				$requete="create temporary table amazon_items_1 (asin1 varchar(50), primary key(asin1))";
				mysql_query($requete);
				//C'est un et ou sauf, et = supprimer tous les éléments de la 
				//table amazon_items qui ne sont pas dans amazon_items_1, sauf = supprimer
				//de la table amazon_items tous ceux qui sont aussi dans la table amazon_items_1
				if (count($asins)) {
					mysql_query("insert into amazon_items_1 values ".implode(",",$asins));
					if ($inter=="and") {
						$requete="delete amazon_items from amazon_items left join amazon_items_1 on asin1=asin where asin1 is null";
						mysql_query($requete);
					} else {
						$requete="delete amazon_items from amazon_items, amazon_items_1 where asin1=asin";
						mysql_query($requete);
					}
					mysql_query("drop table asin_items_1");
				}
			} else {
				//C'est un ou, on insère sans erreurs !!
				if (count($asins)) mysql_query("insert ignore into amazon_items values ".implode(",",$asins));		
			}
		}
	}
	
	function make_search($source_id,$q,$client) {
		global $url,$search_index,$max_return;
		
		$client->setHeaders($this->make_soap_headers('ItemSearch'));
		$proxy=$client->getProxy();
		
		$paws["Request"]=array(
			"SearchIndex"=>$search_index[$url][0],
			"ResponseGroup"=>"ItemIds"
		);
		$n=0;
		for ($i=0; $i<count($q); $i++) {
			if (!$q[$i]["SUB"]) {
				$pawsp=$paws;
				foreach($q[$i]["PARAM"] as $rparam=>$value)
					$pawsp["Request"][$rparam]=$value;
				$result=$proxy->ItemSearch($pawsp);
				if ($err=$client->getError()) {
					$this->error=true;
					$this->error_message=$err;
				} else {
					$this->store_asins($result,$q[$i]["INTER"],($i==0),$n);
					if ($result["Items"]["TotalPages"]>1) {
						$npages=$result["Items"]["TotalPages"];
						for ($j=2; $j<=$npages; $j++) {
							$pawsp["Request"]["ItemPage"]=$j;
							$result=$proxy->ItemSearch($pawsp);
							if ($err=$client->getError()) {
								$this->error=true;
								$this->error_message=$err;
								break;
							} else $this->store_asins($result,$q[$i]["INTER"],($i==0),$n);
							if ((count($q)==1) and ($n>=$max_return)) break;
						}
					}
				}
			} else {
				$this->make_search($source_id,$q[$i]["SUB"],$client);
			}
		}
	}
	
	function soap2array($t,$element) {
		$n=0;
		$r=array();
		if (is_array($t[$element])) {
			foreach ($t[$element] as $elt=>$val) {
				if ((string)$elt!=(string)$n) {
					$r=array();
					$r[0]=$t[$element];
					break;
				} else {
					$r[$n]=$val;
					$n++;
				}
			}
		} else if ($t[$element]) $r[0]=$t[$element];
		return $r;
	}
	
	function amazon_2_uni($item) {

		$nt=$item["ItemAttributes"];
		$unimarc=array();
		$unimarc["001"][0]=$item["ASIN"];
		if ($nt["EAN"]) $unimarc["010"][0]["a"][0]=$nt["EAN"];
		if ($nt["UPC"]) $unimarc["010"][0]["a"][0]=$nt["UPC"];
		if ($nt["ISBN"]) $unimarc["010"][0]["a"][0]=$nt["ISBN"];
		
		//Langue
		$langue=$this->soap2array($nt,"Language");
		if (count($langue)) {
			for ($i=0; $i<count($langue); $i++)
				$unimarc["101"][$i]["a"][0]=$langue[$i];
		}
		
		$unimarc["200"][0]["a"][0]=$nt["Title"];
		
		//Mention d'édition
		$edition=$this->soap2array($nt,"Edition");
		if (count($edition)) $unimarc["205"][0]["a"][0]=$edition[0];
		
		//Editeurs
		$publisher=$this->soap2array($nt,"Publisher");	
		$pubdate  =$this->soap2array($nt,"PublicationDate");
		$releasedate=$this->soap2array($nt,"ReleaseDate");
		
		$unimarc["210"][0]["c"][0]=$publisher[0];
		if (count($pubdate)) $unimarc["210"][0]["d"][]=substr($pubdate[0],0,4);
		if (count($releasedate)) $unimarc["210"][0]["d"][]=substr($releasedate[0],0,4);
		
		//Collation
		$numberofpages=$this->soap2array($nt,"NumberOfPages");
		$numberoftracks=$this->soap2array($nt,"NumberOfTracks");
		if (count($numberofpages)) $unimarc["215"][0]["a"][]=$numberofpages[0]." p.";
		if (count($numberoftracks)) $unimarc["215"][0]["a"][]=$numberoftracks[0]." p.";
		
		$aspectratio=$this->soap2array($nt,"AscpectRatio");
		$audioformat=$this->soap2array($nt,"AudioFormat");
		$c_215=array();
		if ($aspectratio[0]) $c_215[]=$aspectratio[0];
		if ($audioformat[0]) $c_215[]="Audio : ".$audioformat[0];
		$c_215=implode(" - ",$c_215);
		if ($c_215) $unimarc["215"][0]["c"][0]=$c_215;
		
		$numberofitems=$this->soap2array($nt,"NumberOfItems");
		$numberofdiscs=$this->soap2array($nt,"NumberOfDiscs");
		$e_215=array();
		if ($numberofitems[0]) $e_215[]="Nombre d'éléments : ".$numberofitems[0];
		if ($numberofdiscs[0]) $e_215[]="Nombre de disques : ".$numberofdiscs[0];
		$e_215=implode(" - ",$e_215);
		if ($e_215) $unimarc["215"][0]["e"][0]=$e_215;
		
		$binding=$this->soap2array($nt,"Binding");
		$runningtime=$this->soap2array($nt,"RunningTime");
		$d_215=array();
		if ($binding[0]) $d_215[]=$binding[0];
		if ($runningtime[0]) $d_215[]="Durée : ".$runningtime[0]["!"]." ".$runningtime[0]["!Units"];
		$d_215=implode(" - ",$d_215);
		if ($d_215) $unimarc["215"][0]["d"][0]=$d_215;
		
		//Notes
		$theatricaldate=$this->soap2array($nt,"TheatricalReleaseDate");
		$format=$this->soap2array($nt,"Format");
		$a_300=array();
		if ($theatricaldate[0]) $a_300[]="Enregistré le : ".$theatricaldate[0];
		if ($format[0]) $a_300[]="Format : ".$format[0];
		$a_300=implode("\n",$a_300);
		if ($a_300) $unimarc["300"][0]["a"][0]=$a_300;
		
		//Dewey
		$dewey=$this->soap2array($nt,"DeweyDecimalNumber");
		if ($dewey[0]) $unimarc["676"][0]["a"][0]=$dewey[0];
		
		//Auteurs
		$auttotal=array();
		$authors=$this->soap2array($nt,"Author");
		if (count($authors)) {
			if (count($authors)>1) $autf="701"; else $autf="700";
			for ($i=0; $i<count($authors); $i++) {
				$aut[$i]["a"][0]=$authors[$i];
				$aut[$i]["4"][0]="070";
				$auttotal[]=$authors[$i];
			}
			$unimarc[$autf]=$aut;
			$naut=count($authors);
		}
		$authors=$this->soap2array($nt,"Artist");
		if (count($authors)) {
			if (($naut+count($authors))>1) $autf="701"; else $autf="700";
			for ($i=0; $i<count($authors); $i++) {
				$autt=array();
				$autt["a"][0]=$authors[$i];
				$autt["4"][0]="040";
				$unimarc[$autf][]=$autt;
				$auttotal[]=$authors[$i];
			}
			$naut+=count($authors);
		}
		$authors=$this->soap2array($nt,"Actor");
		if (count($authors)) {
			if (($naut+count($authors))>1) $autf="701"; else $autf="700";
			for ($i=0; $i<count($authors); $i++) {
				$autt=array();
				$autt["a"][0]=$authors[$i];
				$autt["4"][0]="005";
				$unimarc[$autf][]=$autt;
				$auttotal[]=$authors[$i];
			}
			$naut+=count($authors);
		}
		$authors=$this->soap2array($nt,"Director");
		if (count($authors)) {
			if (($naut+count($authors))>1) $autf="701"; else $autf="700";
			for ($i=0; $i<count($authors); $i++) {
				$autt=array();
				$autt["a"][0]=$authors[$i];
				$autt["4"][0]="651";
				$unimarc[$autf][]=$autt;
				$auttotal[]=$authors[$i];
			}
			$naut+=count($authors);
		}
		$creator=$this->soap2array($nt,"Creator");
		$authors=array();
		if (count($creator)) {
			for ($i=0; $i<count($creator); $i++) {
				if (in_array($creator[$i]["!"],$auttotal)===false) {
					$autt=array();
					$autt["a"][0]=$creator[$i]["!"];
					$autt["b"][0]=$creator[$i]["!Role"];
					$authors[]=$autt;
				}
			}
			if (count($authors)) {
				if (($naut+count($authors))>1) $autf="701"; else $autf="700";
				for ($i=0; $i<count($authors); $i++) {
					$unimarc[$autf][]=$authors[$i];
				}
				$naut+=count($authors);
			}
		}
		$reviews=$this->soap2array($item["EditorialReviews"],"EditorialReview");
		if (count($reviews)) {
			for ($i=0; $i<count($reviews); $i++)
				$unimarc["330"][0]["a"][$i]=$reviews[$i]["Content"].($reviews[$i]["Source"]?" (".$reviews[$i]["Source"].")":"");
		}
		$discs=$this->soap2array($item["Tracks"],"Disc");
		if (count($discs)) {
			$tr="";
			for ($i=0; $i<count($discs); $i++) {
				$tr.="\nDisque ".($i+1)."\n";
				$tracks=$this->soap2array($discs[$i],"Track");
				for ($j=0; $j<count($tracks); $j++) {
					$tr.=" ".$tracks[$j]["!Number"]." ".$tracks[$j]["!"]."\n";
				}
			}	
			$unimarc["327"][0]["a"][0]=$tr;
		}
		
		//Images
		$image_count = 0;
		if (isset($item["LargeImage"]) && $item["LargeImage"]["URL"]) {
			$unimarc["897"][$image_count]["a"][0] = $item["LargeImage"]["URL"];
			$unimarc["897"][$image_count]["b"][0] = "LargeImage";
			$image_count++;
		}
		else if (isset($item["MediumImage"]) && $item["MediumImage"]["URL"]) {
			$unimarc["897"][$image_count]["a"][0] = $item["MediumImage"]["URL"];
			$unimarc["897"][$image_count]["b"][0] = "MediumImage";
			$image_count++;
		}
		else if (isset($item["TinyImage"]) && $item["TinyImage"]["URL"]) {
			$unimarc["897"][$image_count]["a"][0] = $item["TinyImage"]["URL"];
			$unimarc["897"][$image_count]["b"][0] = "TinyImage";
			$image_count++;
		}
		
		if (isset($item["MediumImage"]) && $item["MediumImage"]["URL"]) {
			$unimarc["896"][0]["a"][0] = $item["MediumImage"]["URL"];
		}
		else if (isset($item["SmallImage"]) && $item["SmallImage"]["URL"]) {
			$unimarc["896"][0]["a"][0] = $item["SmallImage"]["URL"];
		}
	
		if (isset($item["ImageSets"])) {
			foreach ($item["ImageSets"] as $ImageSet) {
				if (is_array($ImageSet)) {
					foreach ($ImageSet as $aitem) {
						if ($aitem["!Category"] == 'primary' && $image_count)
							continue;
						if (isset($aitem["LargeImage"]) && isset($aitem["LargeImage"]["URL"])) {
							$unimarc["897"][$image_count]["a"][0] = $aitem["LargeImage"]["URL"];
							$unimarc["897"][$image_count]["b"][0] = $aitem["!Category"]." - LargeImage";
							$image_count++;
						}
						else if (isset($aitem["MediumImage"]) && isset($aitem["MediumImage"]["URL"])) {
							$unimarc["897"][$image_count]["a"][0] = $aitem["MediumImage"]["URL"];
							$unimarc["897"][$image_count]["b"][0] = $aitem["!Category"]." - MediumImage";
							$image_count++;
						}
						else if (isset($aitem["ThumbnailImage"]) && isset($aitem["ThumbnailImage"]["URL"])) {
							$unimarc["897"][$image_count]["a"][0] = $aitem["ThumbnailImage"]["URL"];
							$unimarc["897"][$image_count]["b"][0] = $aitem["!Category"]." - ThumbnailImage";
							$image_count++;
						}
						else if (isset($aitem["SmallImage"]) && isset($aitem["SmallImage"]["URL"])) {
							$unimarc["897"][$image_count]["a"][0] = $aitem["SmallImage"]["URL"];
							$unimarc["897"][$image_count]["b"][0] = $aitem["!Category"]." - SmallImage";
							$image_count++;
						}
						else if (isset($aitem["TinyImage"]) && isset($aitem["TinyImage"]["URL"])) {
							$unimarc["897"][$image_count]["a"][0] = $aitem["TinyImage"]["URL"];
							$unimarc["897"][$image_count]["b"][0] = $aitem["!Category"]." - TinyImage";
							$image_count++;
						}					
					}
				}
			}
		}

		
		return $unimarc;
	}
	
	//Fonction de recherche
	function search($source_id,$query,$search_id) {
		global $charset;
		global $opac_curl_proxy;		
		$this->error=false;
		$this->error_message="";
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
		
		if (!$max_return) $max_return=100;
		
		//Tranformation de la recherche en requete amazon
		$this->parse_profile();
		$searchattr=array();
		$searchattr=$this->parse_query($query,true);
		if ($searchattr["TYPE"]=="power")
			$q=$this->make_power_query($searchattr);
		else {
			//Recherche simple
			$q=$this->make_simple_search($searchattr);
		}
		
		$adresse_proxy = false;
		$port_proxy = false;
		$user_proxy = false;
		$pwd_proxy = false;
		if($opac_curl_proxy!=''){
			$param_proxy = explode(',',$opac_curl_proxy);
			$adresse_proxy = $param_proxy[0];
			$port_proxy = $param_proxy[1];
			$user_proxy = $param_proxy[2];
			$pwd_proxy = $param_proxy[3];
		}
		
		$client=new nusoapclient("http://ecs.amazonaws.com/AWSECommerceService/2009-10-01/".$this->profile["SITES"][0]["SITE"][$this->current_site]["COUNTRY"]."/AWSECommerceService.wsdl",true,
		$adresse_proxy, $port_proxy, $user_proxy, $pwd_proxy);
		if ($err=$client->getError()) {
			if ($err) {
				$this->error=true;
				$this->error_message=$err;
			}
		} else {
			$client->timeout = $params["TIMEOUT"];
			$client->response_timeout = $params["TIMEOUT"];
			$this->make_search($search_id,$q,$client);
			$requete="select asin from amazon_items limit $max_return";
			$resultat=mysql_query($requete);
			if (@mysql_num_rows($resultat)) {
				//Récupération des résultats et import
				$n=0;
				while ($r=mysql_fetch_object($resultat)) {
					$asins[]=$r->asin;
					$n++;
					if ($n==10) {
						$tasins[]=$asins;
						$asins=array();
						$n=0;
					}
				}
				if (count($asins)) $tasins[]=$asins;
				mysql_query("drop table amazon_items");
				//Nouvelle requete amazon
				for ($k=0; $k<count($tasins); $k++) {
					$client->setHeaders($this->make_soap_headers('ItemLookup'));
					$proxy=$client->getProxy();					
					$paws["Request"]=array(
						"ResponseGroup"=>"Large,Tracks",
						"ItemId"=>implode(",",$tasins[$k])
					);
					$result=$proxy->ItemLookup($paws);
					if ($err=$client->getError()) {
						$this->error=true;
						$this->error_message=$err;
					} else {
						if ($result["Items"]["Item"]) {
							$items=$this->soap2array($result["Items"],"Item");
							for ($i=0; $i<count($items); $i++) {
								$this->rec_record($this->amazon_2_uni($items[$i]),$source_id,$search_id);
							}
						}
					}
				}
			}
		}
	}
	
	function maj_entrepot($source_id,$callback_progress="",$recover=false,$recover_env="") {
		return 0;
	}
	
	function make_soap_headers($action){
		
		$keys = unserialize($this->parameters);
		$time = gmdate("Y-m-d\TH:i:s\Z");
		$signature = base64_encode(hash_hmac("sha256",$action.$time,$keys['secretkey'],true));		
		
		$accessKey = new soapval('AWSAccessKeyId',false,$keys['accesskey'],'http://security.amazonaws.com/doc/2007-01-01/');
		$timestamp = new soapval('Timestamp',false,$time,'http://security.amazonaws.com/doc/2007-01-01/');
		$sign = new soapval('Signature',false,$signature,'http://security.amazonaws.com/doc/2007-01-01/');
		//$client_soap->setHeaders(array($accessKey,$timestamp,$sign));
		return array($accessKey,$timestamp,$sign);
		
	}
	
	function enrichment_is_allow(){
		return true;
	}
	
	function getEnrichmentHeader(){
		$header= array();
		//$header[]= "<!-- Script d'enrichissement pour Amazon-->";
		return $header;
	}
	
	function getTypeOfEnrichment($source_id){
		$type['type'] = array(
			"resume",
			"similarities"
		);		
		$type['source_id'] = $source_id;
		return $type;
	}
	
	function getEnrichment($notice_id,$source_id,$type=""){
		$enrichment= array();
		//on renvoi ce qui est demandé... si on demande rien, on renvoi tout..
		switch ($type){
			case "resume" :
				$info = $this->getNoticeInfos($notice_id,$source_id);
				if($info) $enrichment['resume']['content'] = $info['resume'];
				else $enrichment['resume']['content'] = $this->msg['amazon_enrichment_no_similarities'];
				break;			
			case "similarities" :
			default :
				$info = $this->getNoticeInfos($notice_id,$source_id);
				if($info) $enrichment['similarities']['content'] = $info['similarities'];
				else $enrichment['similarities']['content'] = $this->msg['amazon_enrichment_no_similarities'];
				break;
		}
		$enrichment['source_label']=$this->msg['amazon_enrichment_source'];
		return $enrichment;
	}
	
	function getNoticeInfos($notice_id,$source_id){
		global $search_index,$url;
		global $opac_curl_proxy;
		
		$info = "";
		$asin = 0;
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
			
		$client = $this->initAWS($source_id);
		
		$client->setHeaders($this->make_soap_headers('ItemSearch'));
		$proxy=$client->getProxy();			
		
		$rqt = "select code from notices where notice_id = '".$notice_id."'";
		$res = mysql_query($rqt);
		if(mysql_num_rows($res)){
			$code = mysql_result($res,0,0);
			if($code != ""){
				$code = preg_replace('/-|\.| /', '', $code);
				$paws["Request"]=array(
					"SearchIndex" => $search_index[$url][0],
					"ResponseGroup"=>"ItemIds",
					"Keywords" => "$code"
				);
						
				$result=$proxy->ItemSearch($paws);
				$items=$this->soap2array($result["Items"],"Item");
				$asin = $items[0][ASIN];
				if($asin){
					$client->setHeaders($this->make_soap_headers('ItemLookup'));
					$proxy=$client->getProxy();	
					$paws["Request"]=array(
						"ResponseGroup"=>"Similarities,Large",
						"IdType" => "ASIN",
						"ItemId" => $asin
					);
					$result=$proxy->ItemLookup($paws);
					$items=$this->soap2array($result["Items"],"Item");
					
					//récupération des résumés...
					$reviews=$this->soap2array($items[0]["EditorialReviews"],"EditorialReview");
					if (count($reviews)) {
						for ($i=0; $i<count($reviews); $i++){
							$infos['resume'] .=$reviews[$i]["Content"].($reviews[$i]["Source"]?" (".$reviews[$i]["Source"].")":"");
						}
					}
					
					//pour les similarités
					foreach($items[0][SimilarProducts][SimilarProduct] as $similar){
						if(isISBN($similar[ASIN])){
							$rqt= "select notice_id from notices where code = '".formatISBN($similar[ASIN],10)."' or code = '".formatISBN($similar[ASIN],13)."' limit 1";
							$res = mysql_query($rqt);
							if(mysql_num_rows($res)){
								$notice = mysql_result($res,0,0);
								if($notice)	$infos['similarities'].=aff_notice($notice,1,1,0, AFF_ETA_NOTICES_REDUIT, "no",0, 1);
							}
						}else {
							//si c'est pas un ISBN on cherche ce que ca peut être...
							$paws["Request"]=array(
								"ResponseGroup"=>"ItemAttributes",
								"IdType" => "ASIN",
								"ItemId" => $similar[ASIN]
							);
							$result=$proxy->ItemLookup($paws);	
							$items=$this->soap2array($result["Items"],"Item");
							$code = $items[0][ItemAttributes][UPC];
							if($code){
								$rqt= "select notice_id from notices where code = '".$code."' or code = '".$code."' limit 1";
								$res = mysql_query($rqt);
								if(mysql_num_rows($res)){
									$notice = mysql_result($res,0,0);
									if($notice)	$infos['similarities'].=aff_notice($notice,1,1,0, AFF_ETA_NOTICES_REDUIT, "no",0, 1);
								}
							}									
						}
					}
				}
			}
		}
		return $infos;
	}	
	
	function initAWS($source_id){
		global $search_index,$url;
		global $opac_curl_proxy;
		
		$this->parse_profile();
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
				
		$adresse_proxy = false;
		$port_proxy = false;
		$user_proxy = false;
		$pwd_proxy = false;
		if($opac_curl_proxy!=''){
			$param_proxy = explode(',',$opac_curl_proxy);
			$adresse_proxy = $param_proxy[0];
			$port_proxy = $param_proxy[1];
			$user_proxy = $param_proxy[2];
			$pwd_proxy = $param_proxy[3];
		}
		$client=new nusoapclient("http://ecs.amazonaws.com/AWSECommerceService/2009-10-01/".$this->profile["SITES"][0]["SITE"][$this->current_site]["COUNTRY"]."/AWSECommerceService.wsdl",true,
		$adresse_proxy, $port_proxy, $user_proxy, $pwd_proxy);
		if ($err=$client->getError()) {
			if ($err) {
				$this->error=true;
				$this->error_message=$err;
			}
		} else {
			$client->timeout = $params["TIMEOUT"];
			$client->response_timeout = $params["TIMEOUT"];
		}
		return $client;
	}
}
?>