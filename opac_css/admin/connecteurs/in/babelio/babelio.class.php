<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: babelio.class.php,v 1.1.2.2 2011-09-09 07:21:41 arenou Exp $

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
		$header[]= "<!-- Script d'enrichissement Babélio-->";
		$header[]= "<script type='text/javascript'>
		function switchPage(notice_id,type,page,action){
			var pagin= new http_request();
			var content = document.getElementById('div_'+type+notice_id);
			var patience= document.createElement('img');
			patience.setAttribute('src','images/patience.gif');
			patience.setAttribute('align','middle');
			patience.setAttribute('id','patience'+notice_id);
			content.innerHTML = '';
			document.getElementById('onglet_'+type+notice_id).appendChild(patience);
			page = page*1;
			switch (action){
				case 'next' :
					page++;
					break;
				case 'previous' :
					page--;
					break;
			}
			pagin.request('./ajax.php?module=ajax&categ=enrichment&action=enrichment&type='+type+'&id='+notice_id+'&enrichPage='+page,false,'',true,gotEnrichment);
		} 
		</script>";
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
	
	function getEnrichment($notice_id,$source_id,$type="",$page=1){
		$enrichment= array();
		$this->noticeToEnrich = $notice_id;
		$this->enrichPage = $page;
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
		$url = "http://www.babelio.info/sxml/$isbn&type=$type&page=".$this->enrichPage."&auth=".$this->getHash($t)."&timestamp=$t";
		$curl = new Curl();
		$xmlToParse = $curl->get($url);
		$xmlToParse = utf8_decode($xmlToParse);	
		$xmlToParse=$this->cp1252Toiso88591($xmlToParse);
		$xmlToParse = str_replace("utf-8","iso-8859-1",$xmlToParse);
		$xml = _parser_text_no_function_($xmlToParse,"URLSET");
		$return = $this->formatEnrichmentResult($xml);
		$return.= $this->getEnrichmentPagin($xml['SOMMAIRE'][0]);
		return $return;
	}
	
	function getHash($t){
		$keys = unserialize($this->parameters);
		return md5($keys['login'].md5($keys['mdp'])."PMB".$t);
	}
	
	function getEnrichmentPagin($sommaire){
		$current = $sommaire['PAGE'][0]['value'];
		$nb_page = ceil($sommaire['NB_RESULTATS'][0]['value']/$sommaire['RESULTATS_PAR_PAGE'][0]['value']);
		$ret = "";
		if($current > 1) $ret .= "<img src='images/prev.gif' onclick='switchPage(\"".$this->noticeToEnrich."\",\"".$this->typeOfEnrichment."\",\"".$current."\",\"previous\");'/>";
		else $ret .= "<img src='images/prev-grey.gif'/>";
		$ret .="&nbsp;".$current."/$nb_page&nbsp;";
		if($current < $nb_page) $ret .= "<img src='images/next.gif' onclick='switchPage(\"".$this->noticeToEnrich."\",\"".$this->typeOfEnrichment."\",\"".$current."\",\"next\");'/>";
		else $ret .= "<img src='images/next-grey.gif'/>";
		return "<div class='row'><center>$ret</center></div>";
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
	
	function cp1252Toiso88591($str){
		$cp1252_map = array(
			"\x80" => "EUR", /* EURO SIGN */
			"\x82" => "\xab", /* SINGLE LOW-9 QUOTATION MARK */
			"\x83" => "\x66",     /* LATIN SMALL LETTER F WITH HOOK */
			"\x84" => "\xab", /* DOUBLE LOW-9 QUOTATION MARK */
			"\x85" => "...", /* HORIZONTAL ELLIPSIS */
			"\x86" => "?", /* DAGGER */
			"\x87" => "?", /* DOUBLE DAGGER */
			"\x88" => "?",     /* MODIFIER LETTER CIRCUMFLEX ACCENT */
			"\x89" => "?", /* PER MILLE SIGN */
			"\x8a" => "S",   /* LATIN CAPITAL LETTER S WITH CARON */
			"\x8b" => "\x3c", /* SINGLE LEFT-POINTING ANGLE QUOTATION */
			"\x8c" => "OE",   /* LATIN CAPITAL LIGATURE OE */
			"\x8e" => "Z",   /* LATIN CAPITAL LETTER Z WITH CARON */
			"\x91" => "\x27", /* LEFT SINGLE QUOTATION MARK */
			"\x92" => "\x27", /* RIGHT SINGLE QUOTATION MARK */
			"\x93" => "\x22", /* LEFT DOUBLE QUOTATION MARK */
			"\x94" => "\x22", /* RIGHT DOUBLE QUOTATION MARK */
			"\x95" => "\b7", /* BULLET */
			"\x96" => "\x20", /* EN DASH */
			"\x97" => "\x20\x20", /* EM DASH */
			"\x98" => "\x7e",   /* SMALL TILDE */
			"\x99" => "?", /* TRADE MARK SIGN */
			"\x9a" => "S",   /* LATIN SMALL LETTER S WITH CARON */
			"\x9b" => "\x3e;", /* SINGLE RIGHT-POINTING ANGLE QUOTATION*/
			"\x9c" => "oe",   /* LATIN SMALL LIGATURE OE */
			"\x9e" => "Z",   /* LATIN SMALL LETTER Z WITH CARON */
			"\x9f" => "Y"    /* LATIN CAPITAL LETTER Y WITH DIAERESIS*/
		);
		return strtr($str, $cp1252_map);
	}
}
?>