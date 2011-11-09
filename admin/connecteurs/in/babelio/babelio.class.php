<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: babelio.class.php,v 1.1.2.1 2011-06-24 08:16:03 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");
require_once($class_path."/curl.class.php");

class babelio extends connector {
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
	
    function babelio($connector_path="") {
    	parent::connector($connector_path);
    }
    
    function get_id() {
    	return "babelio";
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
		return "";
    }
    
    function make_serialized_source_properties($source_id) {
    	$this->sources[$source_id]["PARAMETERS"]=serialize(array());
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
			$login= $keys['login'];
			$mdp=$keys['mdp'];
		} else {
			$login="";
			$mdp="";
		}	
		$r="<div class='row'>
				<div class='colonne3'><label for='login'>".$this->msg["babelio_login"]."</label></div>
				<div class='colonne-suite'><input type='text' name='login' value='".htmlentities($login,ENT_QUOTES,$charset)."'/></div>
			</div>
			<div class='row'>
				<div class='colonne3'><label for='mdp'>".$this->msg["babelio_mdp"]."</label></div>
				<div class='colonne-suite'><input type='text' class='saisie-50em' name='mdp' value='".htmlentities($mdp,ENT_QUOTES,$charset)."'/></div>
			</div>";
		return $r;
	}
    
    function make_serialized_properties() {
    	global $login, $mdp;
		//Mise en forme des paramètres à partir de variables globales (mettre le résultat dans $this->parameters)
		$keys = array();
    	
    	$keys['login']=$login;
		$keys['mdp']=$mdp;
		$this->parameters = serialize($keys);
	}

	function enrichment_is_allow(){
		return true;
	}
	
	function getEnrichmentHeader(){
		$header= array();
//		$header[]= "<!-- Script d'enrichissement pour Google Book-->";
//		$header[]= "<script type='text/javascript' src='https://www.google.com/jsapi'></script>";
//		$header[]= "<script type='text/javascript'>google.load('books','0');</script>";
		return $header;
	}
	
	function getTypeOfEnrichment($source_id){
		$type['type'] = array(
			"citation",
			"critique"
		);		
		$type['source_id'] = $source_id;
		return $type;
	}
	
	function getEnrichment($notice_id,$source_id,$type=""){
		$enrichment= array();
		//on renvoi ce qui est demandé... si on demande rien, on renvoi tout..
		$rqt="select code from notices where notice_id = '$notice_id'";
		$res=mysql_query($rqt);
		if(mysql_num_rows($res)){
			$code = mysql_result($res,0,0);
			$code = preg_replace('/-|\.| /', '', $code);
		}
		$this->typeOfEnrichment = $type;
		switch ($this->typeOfEnrichment){
			case "citation" :
				$enrichment['citation']['content'] = $this->getInfos(2,$code);
				break;
			case "critique" :
				$enrichment['critique']['content'] = $this->getInfos(1,$code);
				break;
			default :
				$enrichment['citation']['content'] = $this->getInfos(2,$code);
				$enrichment['critique']['content'] = $this->getInfos(1,$code);
				break;
		}		
		$enrichment['source_label']=$this->msg['babelio_enrichment_source'];
		return $enrichment;
	}
	
	function getInfos($type,$isbn){
		global $charset;
		if(!$isbn) return "";
		$return = "";
		$t = time();
		$url = "http://www.babelio.info/sxml/$isbn&type=$type&auth=".$this->getHash($t)."&timestamp=$t";
		$curl = new Curl();
		$xmlToParse = $curl->get($url);	
		file_put_contents("/home/nauno/test.xml",$xmlToParse);
		$xml = _parser_text_no_function_($xmlToParse,"URLSET");
		$return = $this->formatEnrichmentResult($xml);
		//$return.= $this->getEnrichmentPagin($xml);
		return $return;
	}
	
	function getHash($t){
		$keys = unserialize($this->parameters);
		return md5($keys['login'].md5($keys['mdp'])."PMB".$t);
	}
	
	function formatEnrichmentResult($xml){
		$result = "";
		foreach($xml['URL'] as $url){
			$d = explode("T",$url['DT'][0]['value']);
			$date = formatdate($d[0]);
			$result.="
			<div class='row'>
				<div class='row'> ".
				$this->msg['babelio_enrichment_publish_date']." ".$date;
			if($this->typeOfEnrichment == 'critique') $result.= "&nbsp;".$this->stars($url['NOTE'][0]['value']) ;
			$result.="
				</div>
				<blockquote>";
			foreach($url['SNIPPET'] as $content){
				$result.= $content['value'];
			}
			$result.="
					<br />
					<a href='".$url['LOC'][0]['value']."' target='_blank'>".$this->msg['babelio_enrichment_see_more']."</a>
				</blockquote>
			</div>";	
		}
		return $result;
	}
	
	// Gestion des étoiles pour les notes
	function stars($note) {
		$etoiles_moyenne="";
		$cpt_star = 5;
		
		for ($i = 1; $i <= $cpt_star; $i++) {
			if($note >= $i) $etoiles_moyenne.="<img border=0 src='images/star.png' align='absmiddle'>";
			else $etoiles_moyenne.="<img border=0 src='images/star_unlight.png' align='absmiddle'>";
		}
		return $etoiles_moyenne;
	} // fin stars()
}
?>