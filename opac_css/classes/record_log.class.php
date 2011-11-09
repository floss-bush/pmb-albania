<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: record_log.class.php,v 1.9.2.1 2011-07-08 14:21:13 dgoron Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class record_log{
	
	var $date='';
	var $url_asked='';
	var $get_log = array();
	var $post_log = array();
	var $url_ref = '';
	var $num_session = 0;
	var $empr = array();
	var $serveur = array();
	var $doc = array();
	var $expl = array();
	var $nb_results = array();
	var $generique= "";
	
	
	function record_log(){
		$this->init_environnement();
	}
	
	//initialisation de l'environnement
	function init_environnement(){
		if($_GET) $this->get_log = $_GET;
		if ($_POST) $this->post_log = $_POST;
		if($_SERVER) $this->serveur = $_SERVER;
		if($_SERVER['REQUEST_URI']) $this->url_asked = $_SERVER['REQUEST_URI'];															
		if($_SERVER['HTTP_REFERER']) $this->url_ref = $_SERVER['HTTP_REFERER'];		
	}
	
	//Ajout d'un nouvel élément
	function add_log($nom='',$value=0){
		switch($nom){
			case 'num_session':
				if($value) $this->num_session = $value;
				break;
			case 'empr':
				if($value) $this->empr = $value;				 
				break;
			case 'docs':
				if($value) $this->doc = $value;
				break;
			case 'expl':
				if($value) $this->expl = $value;
				break;
			case 'nb_results':
				if($value) $this->nb_results = $value;
				break;
			default:
				if($value) $this->generique[$nom]=$value;
				break;
							
		}		
	}
	
	
	//Enregistrement dans la table de log
	function save(){
		global $pmb_perio_vidage_log, $pmb_perio_vidage_stat, $internal_emptylogstatopac;
		
		$rqt = "INSERT INTO logopac (url_demandee,url_referente,get_log,post_log,num_session,server_log,empr_carac,empr_doc,empr_expl,nb_result, gen_stat) VALUES ('";
		$rqt .= addslashes($this->url_asked)."','".addslashes($this->url_ref)."','".addslashes(serialize($this->get_log))."','".addslashes(serialize($this->post_log))."','".addslashes($this->num_session)."','".addslashes(serialize($this->serveur))."','".addslashes(serialize($this->empr))."','".addslashes(serialize($this->doc))."','".addslashes(serialize($this->expl))."','".addslashes(serialize($this->nb_results))."','".addslashes(serialize($this->generique))."')";
		$first_day = $this->sql_value("SELECT date_log FROM logopac order by date_log limit 1");
		$periodicite = $this->sql_value("SELECT DATEDIFF(CURRENT_DATE(),'".addslashes($first_day)."')");
		if($periodicite >= $pmb_perio_vidage_log){	
			//On copie la table log dans stat et on la vide
			mysql_query("INSERT INTO statopac (date_log, url_demandee, url_referente, get_log, post_log,num_session, server_log, empr_carac, empr_doc, empr_expl,nb_result, gen_stat) 
					SELECT date_log, url_demandee, url_referente, get_log, post_log, num_session, server_log, empr_carac, empr_doc, empr_expl, nb_result, gen_stat FROM logopac");
			mysql_query("TRUNCATE TABLE logopac");			
		} 
		mysql_query($rqt);
		
		if ($internal_emptylogstatopac) {
			$date_internal=explode(" ",$internal_emptylogstatopac);
			if ((time()-$date_internal[1])>86400) {
				mysql_query("update parametres set valeur_param=0 where type_param='internal' and sstype_param='emptylogstatopac'");
				$internal_emptylogstatopac=0;
			}
		}
		
		if (!$internal_emptylogstatopac) {
			$perio_stat = explode(",",$pmb_perio_vidage_stat);
			$mode=$perio_stat[0];
			$nb_jours=$perio_stat[1];
			$first_day_stat = $this->sql_value("SELECT date_log FROM statopac order by date_log limit 1");
			switch($mode){
				case '1' :
					//On vide tous les x jours
					$periodicite = $this->sql_value("SELECT DATEDIFF(CURRENT_DATE(),'".addslashes($first_day_stat)."')");
					if($periodicite >= $nb_jours){
						mysql_query("update parametres set valeur_param='1 ".(time())."' where type_param='internal' and sstype_param='emptylogstatopac'");
						mysql_query("TRUNCATE TABLE statopac");
						mysql_query("update parametres set valeur_param=0 where type_param='internal' and sstype_param='emptylogstatopac'");
					}
					break;
				case '2':
					//On vide tout ce qui a plus de x jours
					$periodicite = $this->sql_value("SELECT DATEDIFF(CURRENT_DATE(),'".addslashes($first_day_stat)."')");
					if($periodicite >= $nb_jours){
						mysql_query("update parametres set valeur_param='1 ".(time())."' where type_param='internal' and sstype_param='emptylogstatopac'");
						$rqt = "DELETE from statopac where date_log< DATE_SUB(CURRENT_DATE() , INTERVAL ".addslashes($nb_jours)." DAY)";
						mysql_query($rqt);
						mysql_query("update parametres set valeur_param=0 where type_param='internal' and sstype_param='emptylogstatopac'");
					}
					break;
			}
		}
	}
	
	//retourne le resultat d'une requete
	function sql_value($rqt) {
		$result=mysql_query($rqt);
		$row = mysql_fetch_row($result);
		return $row[0];
	}
}
?>