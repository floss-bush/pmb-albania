<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: enrichment.class.php,v 1.2 2011-04-15 15:52:23 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/connecteurs.class.php");
require_once($class_path."/marc_table.class.php");
require_once($include_path."/templates/enrichment.tpl.php");

class enrichment {
	var $enhancer = array();
	var $active = array();
	var $catalog;

    function enrichment() {
    	global $base_path;
    	$this->fetch_sources();
    	$this->fetch_data();
    }
    
	//On récupère la liste des sources dispos pour enrichir
    function fetch_sources(){
  		global $base_path;
 		
  		$connectors = new connecteurs();
  		$this->catalog = $connectors->catalog;
    	foreach ($this->catalog as $id=>$prop) {
			$comment=$prop['COMMENT'];
			//Recherche du nombre de sources
			$n_sources=0;
			if($prop['ENRICHMENT'] == "yes"){
				if (is_file($base_path."/admin/connecteurs/in/".$prop['PATH']."/".$prop['NAME'].".class.php")) {
					require_once($base_path."/admin/connecteurs/in/".$prop['PATH']."/".$prop['NAME'].".class.php");
					eval("\$conn=new ".$prop['NAME']."(\"".$base_path."/admin/connecteurs/in/".$prop['PATH']."\");");
					$conn->get_sources();
					foreach($conn->sources as $source_id=>$s) {
						if($s['ENRICHMENT'] == 1){
	   						$this->enhancer[] = array(
	   							'id' =>$s['SOURCE_ID'],
	   							'name' =>$s['NAME']
	   						);
						}
					}
	    		}
			}
    	}  	
    }
    
     //Récupération des données existantes
	function fetch_data(){
    	$rqt = "select * from sources_enrichment";
    	$res = mysql_query($rqt);
    	if(mysql_num_rows($res)){
    		while($r= mysql_fetch_object($res)){
    			$this->active[$r->source_enrichment_typnotice.$r->source_enrichment_typdoc][] = $r->source_enrichment_num;
    		}
    	}
    }

     //Affichage du formulaire
	function show_form(){
    	global $msg;
    	global $admin_enrichment_form;
    	
    	if(count($this->enhancer)){
    		//création du sélecteur...
    		$select="<select name='enrichment_select_source[!!key!!][]' id='enrichment_select_source_!!key!!' multiple>";
    		foreach($this->enhancer as $source){
				$select.="<option value='".$source['id']."'>".$source['name']."</option>";
    		}  		

    		$typnoti = array('m'=>$msg['type_mono'],'s'=>$msg['type_serial'],'a'=>$msg['type_art'],'b'=>$msg['type_bull']);
    		//pour chaque type de document...
    		$typdoc = new marc_list("doctype");
    		$form_content="";
    		foreach($typnoti as $tnoti => $notice){
    			$content ="
				<div class='row'>
					<table class='quadrille'>
						<tr>
							<th colspan='2'>".$msg['admin_connecteurs_enrichment_default_value_form']."</th>
						</tr>
						<tr>
							<td colspan='2'>
							 ".$this->generateSelect($tnoti)."
							</td>
						</tr>
						<tr><td colspan=2>&nbsp;</td></tr>
						<tr>
							<th>".$msg['admin_connecteurs_enrichment_type_form']."</th>
							<th>".$msg['admin_connecteurs_enrichment_enhancer_form']."</th>
						</tr>"; 
    			$parity_source=0;
    			foreach($typdoc->table as $tdoc => $document){
		    		if ($parity_source % 2) $pair_impair_type = "even";
					else $pair_impair_type = "odd";
					$parity_source ++;
    				$content.="
    				<tr class='$pair_impair_type'>
							<td>".$typdoc->table[$tdoc]."</td>
							<td>".$this->generateSelect($tnoti.$tdoc)."</td>
						</tr>";  
    			}
    			$content.="
					</table>
				</div>";
    			$form_content .= gen_plus("enrichment_".$tnoti,$typnoti[$tnoti],$content);
    		}
			$form = str_replace("!!table!!",$form_content,$admin_enrichment_form);
    	}else{
    		$form = str_replace("!!table!!",$msg['admin_connecteurs_enrichment_no_sources'],$admin_enrichment_form);
    	}
    	print $form;
    }


	//Sauvegarde dans la BDD 
	function update(){
   		global $msg; 		
 		global $enrichment_select_source;
 		
    	$typnoti = array('m'=>$msg['type_mono'],'s'=>$msg['type_serial'],'a'=>$msg['type_art'],'b'=>$msg['type_bull']);
    	$typdoc = new marc_list("doctype");
    	//on commence par vider la table...
    	mysql_query("truncate table sources_enrichment");
    	$this->active = array();
    	//et on remet tout...
    	foreach($typnoti as $tnoti => $notice){
			//les valeurs par défaut
			if($enrichment_select_source[$tnoti]){
				foreach($enrichment_select_source[$tnoti] as $source){
					$rqt = "insert into sources_enrichment set source_enrichment_num = '$source', source_enrichment_typnotice = '$tnoti' ";
					mysql_query($rqt);
					$this->active[$tnoti][]=$source;
				}
			}
    		foreach($typdoc->table as $tdoc => $document){
 				//les spécifiques
 				if($enrichment_select_source[$tnoti.$tdoc]){
 					foreach($enrichment_select_source[$tnoti.$tdoc] as $source){
 						$rqt = "insert into sources_enrichment set source_enrichment_num = '$source', source_enrichment_typnotice = '$tnoti', source_enrichment_typdoc = '$tdoc' ";
						mysql_query($rqt);
						$this->active[$tnoti.$tdoc][]=$source;
 					}
 				}  			
    		}
    	}
    	$this->generateHeaders();
	}
	
	//juste pour pas se taper la manip plusieurs fois...
	function generateSelect($type){
		$select ="<select name='enrichment_select_source[$type][]' id='enrichment_select_source_$type' multiple>";
		foreach($this->enhancer as $source){
			$select.="<option value='".$source['id']."' ".($this->active[$type] && in_array($source['id'],$this->active[$type]) ? "selected" : "").">".$source['name']."</option>";
    	} 			
		$select.="</select>";
		return $select;	
	}	
	
	//retourne les éléments à rajouter dans le head, les calculs aux besoins;
	function getHeaders(){
		if(!$this->enrichmentsTabHeaders) $this->generateHeaders();
		return implode("\n",$this->enrichmentsTabHeaders);
	}
	
	//Méthode qui génère les éléments à insérer dans le header pour le bon fonctionnement des enrichissements
	function generateHeaders(){
		global $base_path;

		$this->enrichmentsTabHeaders =array();
		$alreadyIncluded = array();
		foreach($this->active as $type => $sources){
			foreach($sources as $source_id){
				if(!in_array($source_id,$alreadyIncluded)){
					//on récupère les infos de la source nécessaires pour l'instancier
					$name = connecteurs::get_class_name($source_id);
					foreach($this->catalog as $connector){
						if($connector['NAME'] == $name){
							if (is_file($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php")){
								require_once($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php");
								$conn = new $name($base_path."/admin/connecteurs/in/".$connector['PATH']);
								$this->enrichmentsTabHeaders = array_merge($this->enrichmentsTabHeaders,$conn->getEnrichmentHeader());
								$this->enrichmentsTabHeaders = array_unique($this->enrichmentsTabHeaders);
							}
						}
					}
					$alreadyIncluded[]=$source_id;
				}
			}
		}
	}
	
	function getEnrichment($notice_id,$tnoti,$tdoc){
		global $base_path;
		$infos = array();
		if($this->active[$tnoti.$tdoc]) $type = $tnoti.$tdoc;
		else $type = $tnoti;
		if($this->active[$type]){
			foreach($this->active[$type] as $source_id){
				//on récupère les infos de la source nécessaires pour l'instancier
				$name = connecteurs::get_class_name($source_id);	
				foreach($this->catalog as $connector){
					if($connector['NAME'] == $name){
						if (is_file($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php")){
							require_once($base_path."/admin/connecteurs/in/".$connector['PATH']."/".$name.".class.php");
							$conn = new $name($base_path."/admin/connecteurs/in/".$connector['PATH']);
							$infos[] = $conn->getEnrichment($notice_id);
						}
					}
				}			
			}
		}
		highlight_string(print_r($infos,true));
		return $infos;
	}	
}
?>