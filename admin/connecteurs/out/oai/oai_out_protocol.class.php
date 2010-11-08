<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: oai_out_protocol.class.php,v 1.6 2010-09-07 08:57:40 gueluneau Exp $
//There be komodo dragons

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

/*
=========================================================================================================================
Comment ça marche toutes ces classes?

                              Départ
                                |
                                |
                                v
  .----------------------------------------------------------.
  |                      oai_out_server                      |
  |----------------------------------------------------------|--------------------------------.
  | partie connecteur: fait le lien entre toutes les classes |                                |
  '----------------------------------------------------------'                                |
                                |                                                             |
                                |                                                             |
                                |                                                             |
                                v Instancie et utilise                                        |
   .--------------------------------------------------------.                                 |
   |                    oai_out_protocol                    |                                 |
   |--------------------------------------------------------|                                 |
   | Gêre les différents verbes OAI et génêre les pages XML |                                 |
   '--------------------------------------------------------'                                 |
                              .--|                                                            |
                              |                                                               |
                              |                                                      Instancie|
                      Utilise v                                                               v
    .---------------------------------------------------.             .-----------------------------------------------.
    |           abstraite:oai_out_get_records           |             |          oai_out_get_records_notice           |
    |---------------------------------------------------|hérite de    |-----------------------------------------------|
    | S'occupe de récupêrer les enregistrements et les  |<------------| Gère les infos et les enregistrements pour un |
    | infos relatives au contenu de l'entrepot          |             | entrepot de notices                           |
    '---------------------------------------------------'             '-----------------------------------------------'
                                                                                              |
                                                                                              |
                                                                                              |
                                                                         Instancie et utilise v
                                                                        .------------------------------------------.
                   .......................................              |  external_services_converter_oairecord   |
                   .     external_services_converter     . hérite de    |------------------------------------------|
                   .......................................<-------------| S'occupe de convertir les notices        |
                   . Gère le cache des formats convertis .              | en enregistrements OAI                   |
                   .......................................              '------------------------------------------'



=========================================================================================================================
* */

global $class_path, $include_path;
require_once($class_path."/connecteurs_out.class.php");
require_once ($include_path."/connecteurs_out_common.inc.php");
require_once($class_path."/connecteurs_out_sets.class.php");
require_once($class_path."/external_services_converters.class.php");

//Gestion des dates
/**
 * \brief Gestion simplifiée des dates selon la norme iso8601
 * 
 * Conversion réciproque des dates format unix en dates au format iso8601 
 * @author Florent TETART
 */
class iso8601 {
	var $granularity; /*!< \brief Granularité courante des dates en format iso8601 : YYYY-MM-DD ou YYYY-MM-DDThh:mm:ssZ */
	
	/**
	 * \brief Constructeur
	 * @param string $granularity Granularité des dates manipulées : YYYY-MM-DD ou YYYY-MM-DDThh:mm:ssZ
	 */
	function iso8601($granularity="YYYY-MM-DD") {
		$this->granularity=$granularity;
	}
	
	/**
	 * \brief Conversion d'une date unix (nomnbres de secondes depuis le 01/01/1970) en date au format iso8601 selon la granularité
	 * @param integer $time date au format unix (nombres de secondes depuis le 01/01/1970)
	 * @return string date au format YYYY-MM-DD ou YYYY-MM-DDThh:mm:ssZ selon la granularité
	 */
	function unixtime_to_iso8601($time) {
		$granularity=str_replace("T","\\T",$this->granularity);
		$granularity=str_replace("Z","\\Z",$granularity);
		$granularity=str_replace("YYYY","Y",$granularity);
		$granularity=str_replace("DD","d",$granularity);
		$granularity=str_replace("hh","H",$granularity);
		$granularity=str_replace("mm","i",$granularity);
		$granularity=str_replace("MM","m",$granularity);
		$granularity=str_replace("ss","s",$granularity);
		$date=date($granularity,$time);
		return $date;
	}
	
	/**
	 * \brief Conversion d'une date au format iso8601 en date au format unix (nomnbres de secondes depuis le 01/01/1970) selon la granularité
	 * @param string $date date au format iso8601 YYYY-MM-DD ou YYYY-MM-DDThh:mm:ssZ selon la granularité
	 * @return integer date au format unix (nombres de secondes depuis le 01/01/1970)
	 */
	function iso8601_to_unixtime($date) {
		$parts=explode("T",$date);
		if (count($parts)==2) {
			$day=$parts[0]; 
			$time=$parts[1];
		} else {
			$day=$parts[0];
		}
		$days=explode("-",$day);
		if ($this->granularity=="YYYY-MM-DDThh:mm:ssZ") {
			if ($time) $times=explode(":",$time);
			if ($times[2]) {
				if (substr($times[2],strlen($times[2])-1,1)=="Z") $times[2]=substr($times[2],0,strlen($times[2])-1);
			}
		}
		$unixtime=mktime($times[0]+0,$times[1]+0,$time[2]+0,$days[1]+0,$days[2]+0,$days[0]+0);
		return $unixtime;
	}
}

/*
 * oai_out_protocol
 * \brief Cette classe gère toute l'entrée sortie http et le protocol oai 
 * Cette classe ne connait pas ses enregistrement ni leur types, elle les récupère grace à une instance d'une classe fille de oai_out_get_records
 * Norme du protocole: http://www.openarchives.org/OAI/openarchivesprotocol.html
 */
class oai_out_protocol {
	private $msg=array();
	private $repositoryName="";
	private $adminEmail;
	private $sets=array();
	private $repositoryIdentifier="";
	private $oai_out_get_records_object=NULL;
	private $known_metadata_formats=array(
		/*"notice_id" => array(
			"metadataPrefix" => "notice_id",
			"metadataNamespace" => "http://sigb.net/pmb/es/oai/notice_id",
			"schema" => "http://sigb.net/pmb/es/oai/notice_id.xsd"
		),*/
		"pmb_xml_unimarc" => array(
			"metadataPrefix" => "pmb_xml_unimarc",
			"metadataNamespace" => "http://sigb.net/pmb/es/oai/pmb_xml_marc",
			"schema" => "http://sigb.net/pmb/es/oai/pmb_xml_marc.xsd"
		),
		"oai_dc" => array(
			"metadataPrefix" => "oai_dc",
			"metadataNamespace" => "http://www.openarchives.org/OAI/2.0/oai_dc/",
			"schema" => "http://www.openarchives.org/OAI/2.0/oai_dc.xsd"
		)
	);
	private $nb_results=100;
	private $token_life_expectancy=600;
	private $compression=true;
	private $deletion_support="no";
	private $errored=false;
	private $xmlheader_sent=false;
	private $base_url="";

	//Constructeur
	function oai_out_protocol($oai_out_get_records_object, &$msg, $repositoryName, $adminEmail, $sets, $repositoryIdentifier, $nb_results, $token_life_expectancy, $compression, $deletion_support, $additional_metadataformats, $base_url) {
		$this->msg = $msg;
		$this->oai_out_get_records_object=$oai_out_get_records_object;
		$this->repositoryName = $repositoryName;
		$this->adminEmail = $adminEmail;
		$this->sets = $sets;
		$this->repositoryIdentifier = $repositoryIdentifier;
		$this->nb_results = $nb_results;
		$this->token_life_expectancy = $token_life_expectancy;
		$this->compression = $compression;
		$this->deletion_support = $deletion_support;
		$this->known_metadata_formats = array_merge($this->known_metadata_formats, $additional_metadataformats);
		$this->base_url = $base_url;
	}
	
	//Renvoie l'entête
	function oai_header() {
		global $verb;
		$page_url = $this->base_url;
		$iso8601 = new iso8601("YYYY-MM-DDThh:mm:ssZ");
		$curdate = $iso8601->unixtime_to_iso8601(time());
		$this->xmlheader_sent = true;
		return '<?xml version="1.0" encoding="UTF-8" ?>
				<?xml-stylesheet type="text/xsl" href="connecteurs/out/oai/oai2.xsl" ?>
					<OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
						<responseDate>'.$curdate.'</responseDate>
						<request '.($this->errored ? '' : 'verb="'.$verb.'"').'>'.XMLEntities($page_url).'</request>';
	}

	//Renvoie le pied de page
	function oai_footer() {
		return '</OAI-PMH>';
	}

	//Renvoie une erreur
	function oai_error($error_code, $error_string) {
		$this->errored = true;
		$buffer = XMLEntities($error_string);
		$buffer = $charset != "utf-8" ? utf8_encode($buffer) : $buffer;
		$result = "";
		$result .= '<error code="'.XMLEntities($error_code).'">'.$buffer.'</error>';
		return $result;
	}

	//Renvoie le résultat du verb Identify
	function oai_identify() {
		global $charset;
		global $pmb_version_brut;
		$result = "";
		$result .= "<Identify>";
		
		$buffer = XMLEntities($this->repositoryName);
		$buffer = $charset != "utf-8" ? utf8_encode($buffer) : $buffer;
		$result .= '<repositoryName>'.$buffer.'</repositoryName>';
		$result .= '<baseURL>'.XMLEntities($this->base_url).'</baseURL>';
		$result .= '<protocolVersion>2.0</protocolVersion>';
		
		$unix_earliestdate = $this->oai_out_get_records_object->get_earliest_datestamp();
		$iso8601 = new iso8601("YYYY-MM-DDThh:mm:ssZ");
		$earliestdate = $iso8601->unixtime_to_iso8601($unix_earliestdate);
		$result .= '<earliestDatestamp>'.$earliestdate.'</earliestDatestamp>';

		$result .= '<deletedRecord>'.$this->deletion_support.'</deletedRecord>';
		$result .= '<granularity>YYYY-MM-DDThh:mm:ssZ</granularity>';
		$buffer = XMLEntities($this->adminEmail);
		$buffer = $charset != "utf-8" ? utf8_encode($buffer) : $buffer;
		$result .= '<adminEmail>'.$buffer.'</adminEmail>';
		
		$buffer = XMLEntities($this->oai_out_get_records_object->get_sample_oai_identifier());
		$buffer = $charset != "utf-8" ? utf8_encode($buffer) : $buffer;
		$buffer_ri = XMLEntities($this->oai_out_get_records_object->repository_identifier);
		$buffer_ri = $charset !="utf-8" ? utf8_encode($buffer_ri) : $buffer_ri;
		$result .= '<description>
						<oai-identifier xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai-identifier http://www.openarchives.org/OAI/2.0/oai-identifier.xsd" xmlns="http://www.openarchives.org/OAI/2.0/oai-identifier">
						<scheme>oai</scheme>
						<repositoryIdentifier>'.$buffer_ri.'</repositoryIdentifier>
						<delimiter>:</delimiter>
						<sampleIdentifier>'.$buffer.'</sampleIdentifier>
						</oai-identifier>
					</description>';
		
		$result .= '<description>
						<toolkit xsi:schemaLocation="http://oai.dlib.vt.edu/OAI/metadata/toolkit http://oai.dlib.vt.edu/OAI/metadata/toolkit.xsd">
							<title>PMB OAI Connector</title>
							<author>
								<name>Erwan Martin</name>
								<email>emartin@sigb.net</email>
								<institution>PMB Services</institution>
							</author>
							<version>'.$pmb_version_brut.'</version>
							<toolkitIcon></toolkitIcon>
							<URL>http://sigb.net</URL>
						</toolkit>
					</description>';
		
		$result .= "</Identify>";
		
		return $result;
	}
	
	//Renvoie le résultat du verb ListSets
	function oai_list_sets() {
		$result = '';
		$result .= '<ListSets>';
		foreach ($this->sets as $aset) {
			$buffer = XMLEntities($aset["caption"]);
			$buffer = $charset != "utf-8" ? utf8_encode($buffer) : $buffer;
			
			$result .= '<set>
    						<setSpec>set_'.XMLEntities($aset["id"]).'</setSpec>
    						<setName>'.$buffer.'</setName>
  						</set>';
		}
		
		$result .= '</ListSets>';
		return $result;
	}
	
	//Renvoie le résultat du verb ListRecords
	function oai_list_records($root_tag="ListRecords") {
		global $charset, $dbh, $set, $resumptionToken, $from, $until;
		//Vérifications préhalables
		global $metadataPrefix;
		if (!$metadataPrefix)
			$metadataPrefix = "oai_dc";
		if (substr($metadataPrefix, 0, 8) == "convert:") {
			
		}
		else if (($metadataPrefix != "__oai_identifier") && !in_array($metadataPrefix, array_keys($this->known_metadata_formats))) {
			$erreur_message = $charset != "utf-8" ? utf8_encode(sprintf($this->msg["cannotDisseminateFormat"], XMLEntities($metadataPrefix))) : sprintf($this->msg["cannotDisseminateFormat"], XMLEntities($metadataPrefix));
			return $this->oai_error("cannotDisseminateFormat", $erreur_message);
		}

		//Un peu de ménage dans les tokens
		$sql = "DELETE FROM connectors_out_oai_tokens WHERE NOW() >= connectors_out_oai_token_expirationdate";
		mysql_query($sql, $dbh);

		//On aura besoin d'un objet date iso magique
		$iso8601 = new iso8601("YYYY-MM-DDThh:mm:ssZ");

		$result = "";

		$max_records = $this->nb_results;
		$total_number_of_records=0;
		$datefrom=false;
		$dateuntil=false;
		
		//Un token? Cherchons le dans la base de donnée et restaurons son environnement
		if ($resumptionToken) {
			$sql = "SELECT connectors_out_oai_token_environnement FROM connectors_out_oai_tokens WHERE connectors_out_oai_token_token = '".addslashes($resumptionToken)."'";
			$res = mysql_query($sql, $dbh);
			if (!mysql_num_rows($res))
				return $this->oai_error("badResumptionToken", $this->msg["badResumptionToken"]);
			$row = mysql_fetch_assoc($res);
			$config = unserialize($row["connectors_out_oai_token_environnement"]);
			$set_id_list = $config["sets"];
			$datefrom = $config["datefrom"];
			$dateuntil = $config["dateuntil"];
			$metadataPrefix = $config["metadataprefix"];
		}
		//Sinon config de début de recherche
		else {
			//Vérifions si on souhaite un set précis
			if (isset($set) && $set) {
				$the_set_id = substr($set, 4);
				//On a un id, vérifions qu'il existe dans la liste
				$found=false;
				foreach ($this->sets as $aset) {
					if ($aset["id"] == $the_set_id) {
						$found = true;
						break;
					}
				}
				//Non? Erreur!
				if (!$found) {
					$buffer = $charset != 'utf-8' ? utf8_encode($this->msg["unknown_set"]) : $this->msg["unknown_set"];
					return $this->oai_error("unknown_set", $buffer);
				}
				//Oui? On génère la "liste" des sets
				else {
					$set_id_list = array(0=>array("id" => $the_set_id));
				}
			}
			//Sinon on fouille dans tous les sets
			else 
				$set_id_list = $this->sets;
				
			if (isset($from) && $from)
				$datefrom = $iso8601->iso8601_to_unixtime($from);
			if (isset($until) && $until)
				$dateuntil = $iso8601->iso8601_to_unixtime($until);
		}

		//Allons chercher les enregistrements grace à la classe associée
		$records=array();
		foreach($set_id_list as &$aset) {
			if (!isset($aset["fetched_count"]))
				$aset["fetched_count"] = 0;

			//Si on en a déjà assez, on ne fait que compter (pour le total)
			if (count($records) >= $max_records) {
				$total_number_of_records += $this->oai_out_get_records_object->get_record_count($aset["id"], $datefrom, $dateuntil);
				continue;
			}
			
			//Si on a déjà tout extrait dans ce set, on compte et on continue
			if ($aset["fetched_count"]) {
				 $current_set_count = $this->oai_out_get_records_object->get_record_count($aset["id"], $datefrom, $dateuntil);
				 if ($aset["fetched_count"] == $current_set_count) {
				    $total_number_of_records += $current_set_count;
				 	continue;
				 }
			}

			//Allons chercher les enregistrements du set
			$number_to_fetch = $max_records - count($records);
			$set_records = $this->oai_out_get_records_object->get_records($aset["id"], $metadataPrefix, $aset["fetched_count"], $number_to_fetch, $datefrom, $dateuntil);
			$aset["fetched_count"] += count($set_records);
			$current_set_count = $this->oai_out_get_records_object->get_record_count($aset["id"], $datefrom, $dateuntil);
			$total_number_of_records += $current_set_count;
			foreach($set_records as $notice_id => $record_content) {
				if (!isset($records[$notice_id])) {
					$records[$notice_id] = $record_content;
				}
			}
		}

		//Si pas d'enregistrement, le protocol veut qu'on renvoie une erreur
		if (!$records) {
			$erreur_message = $charset != "utf-8" ? utf8_encode($this->msg["noRecordsMatch"]) : $this->msg["noRecordsMatch"];
			return $this->oai_error("noRecordsMatch", $erreur_message);
		}
		
		//Affichons les enregistrements
		$result .= " <".$root_tag.">";
		foreach ($records as $arecords) {
			$result .= $arecords;
		}

		//Calculons le curseur
		$cursor = 0;
		foreach($set_id_list as $aset_c) {
			if (!isset($aset_c["fetched_count"]))
				continue;
			$cursor += $aset_c["fetched_count"];
		}

		if ($cursor < $total_number_of_records) {
			//Enregistrons l'environnement
			$env=array(
				"sets" => $set_id_list,
				"datefrom" => $datefrom,
				"dateuntil" => $dateuntil,
				"metadataprefix" => $metadataPrefix
			);
			$token = md5(microtime());
			$sql = "INSERT INTO connectors_out_oai_tokens (connectors_out_oai_token_token, connectors_out_oai_token_environnement, connectors_out_oai_token_expirationdate) VALUES ('".$token."', '".addslashes(serialize($env))."', NOW() + INTERVAL ".$this->token_life_expectancy." SECOND)";
			mysql_query($sql, $dbh);
			
			$token_expiration_date = time() + $this->token_life_expectancy;
			$result .= '<resumptionToken expirationDate="'.$iso8601->unixtime_to_iso8601($token_expiration_date).'" completeListSize="'.$total_number_of_records.'" cursor="'.$cursor.'">'.$token.'</resumptionToken>';
		}
		
		$result .= " </".$root_tag.">";
		return $result;
	}
	
	function oai_list_identifier() {
		global $metadataPrefix;
		$metadataPrefix = "__oai_identifier";
		return $this->oai_list_records('ListIdentifiers');
	}
	
	function oai_get_record() {
		global $identifier;
		global $metadataPrefix;
		global $charset;
		if (!$metadataPrefix)
			$metadataPrefix = "oai_dc";
			
		$record = $this->oai_out_get_records_object->get_record($identifier, $metadataPrefix);
		if ($record === false) {
			$error_message = $charset != 'utf-8' ? utf8_encode($this->msg["idDoesNotExist"]) : $this->msg["idDoesNotExist"];
			return $this->oai_error("idDoesNotExist", $error_message);
		}

		$result = "<GetRecord>";
		$result .= $record;
		$result .= "</GetRecord>";
		return $result;
	}
	
	//Renvoie le résultat du verb ListMetadataFormat
	function oai_list_metadata_formats() {
		$result = '';
		$result .= '<ListMetadataFormats>';

		foreach ($this->known_metadata_formats as $aformat) {
			$result .= '<metadataFormat>
	     					<metadataPrefix>'.$aformat["metadataPrefix"].'</metadataPrefix>
	     					<schema>'.$aformat["schema"].'</schema>
	     					<metadataNamespace>'.$aformat["metadataNamespace"].'</metadataNamespace>
	   					</metadataFormat>';			
		}

		$result .= '</ListMetadataFormats>';
		return $result;		
	}
}

/*
 * oai_out_get_records
 * \brief Cette classe utilisée par la classe oai_out_protocol permet de récupérer les metadatas des enregistrements (en UTF-8)
 * 
 */
abstract class oai_out_get_records {
	public $error_code="";
	public $error_string="";
	private $msg=array();
	protected $total_record_count_per_set=array();
	
	//Constructeur
	public function oai_out_get_records(&$msg) {
		$this->msg=$msg;
	}

	//Renvoi un exemple d'identifier
	abstract public function get_sample_oai_identifier();
	//Renvoi le datestamp du plus viel enregistrement
	abstract public function get_earliest_datestamp();
	//Retourne le nombre d'enregistrements
	abstract public function get_record_count($set_id, $datefrom=false, $dateuntil=false);
	//Retrouve un enregistrement
    abstract public function get_record($rec_id, $format);
    //Liste les enregistrements
    abstract public function get_records($set_id="", $format, $first=false, $count=false, $datefrom=false, $dateuntil=false);
}

/*
 * oai_out_get_records_notice
 * \brief Cette classe récupère les enregistrements pour un entrepot oai de notices 
 * 
 */
class oai_out_get_records_notice extends oai_out_get_records {
	public $oai_cache_duration = 84000;
	public $source_set_ids=array();
	public $repository_identifier="";
	public $notice_statut_deletion=0;
	
	public function get_sample_oai_identifier() {
		$result = 123456789;
		//Allons chercher un notice_id dans un set
		foreach ($this->source_set_ids as $asetid) {
			$co_set = new_connector_out_set_typed($asetid);
			$co_set->update_if_expired();
			$values = $co_set->get_values();
			//Set vide? On cherche dans un autre
			if (!$values)
				continue;
			//On en a un? On le prend
			$result = $values[0];
			break;
		}
		$result = "oai:".$this->repository_identifier.":".$result;
		return $result;
	}
	
	public function get_earliest_datestamp() {
		//Allons chercher la date la plus vieille pour chaque set, et ensuite on prendra la plus vieille
		$current_min_unix_timestamp = time();
		foreach ($this->source_set_ids as $asetid) {
			$co_set = new_connector_out_set_typed($asetid);
			$co_set->update_if_expired();
			$set_min_date = $co_set->get_earliest_updatedate();
			$current_min_unix_timestamp = $set_min_date < $current_min_unix_timestamp ? $set_min_date : $current_min_unix_timestamp; 
		}
		return $current_min_unix_timestamp;
	}
	
	public function get_record($rec_id, $format) {
		global $charset;
		//Extractons l'id de la notice
		$notice_id = substr(strrchr($rec_id, ":"), 1);
		if (!$notice_id)
			return false;

		//Vérifions que la notice est bien dans les sets de la source
		$notice_sets = connector_out_set_noticecaddie::get_notice_setlist($notice_id);
		$notice_sets = array_intersect($notice_sets, $this->source_set_ids);
		if (!$notice_sets)
			return false;

		$co_set = new_connector_out_set_typed($notice_sets[0]);
		$co_set->update_if_expired();
		$oai_cache = new external_services_converter_oairecord(1, $this->oai_cache_duration, $co_set->cache->cache_duration_in_seconds(), $this->source_set_ids, $this->repository_identifier, $this->notice_statut_deletion);
		$records = $oai_cache->convert_batch(array($notice_id), $format, 'utf-8');

/*		if ($records && $records[$notice_id])
			if (($charset != 'utf-8') && !in_array($format, $this->utf8_formats))
				$records[$notice_id] = utf8_encode($records[$notice_id]);*/

		return $records ? $records[$notice_id] : false;
	}
	
	public function get_records($set_id=0, $format, $first=false, $count=false, $datefrom=false, $dateuntil=false) {
		global $charset;
		//Récupérons du cache les ids des notices
		$co_set = new_connector_out_set_typed($set_id);
		$co_set->update_if_expired();
		$notice_ids = $co_set->get_values($first, $count, $datefrom, $dateuntil);
		$this->total_record_count[$set_id] = $co_set->get_value_count($datefrom, $dateuntil);

		//Récupérons les enregistrements (avec gestion du cache)
		$oai_cache = new external_services_converter_oairecord(1, $this->oai_cache_duration, $co_set->cache->cache_duration_in_seconds(), $this->source_set_ids, $this->repository_identifier, $this->notice_statut_deletion);
		$records = $oai_cache->convert_batch($notice_ids, $format, 'utf-8');

/*		if ($records) {
			if (($charset != 'utf-8') && !in_array($format, $this->utf8_formats)) {
				foreach ($records as $rnotice_id => $rrecord_content) {
					$records[$rnotice_id] = utf8_encode($rrecord_content);
				}
			}
		}*/
		
		return $records;
	}
	
	public function get_record_count($set_id, $datefrom=false, $dateuntil=false) {
		if (!isset($this->total_record_count[$set_id])) {
			$co_set = new_connector_out_set_typed($set_id);
			$co_set->update_if_expired();
			$this->total_record_count[$set_id] = $co_set->get_value_count($datefrom, $dateuntil);
		}
		return $this->total_record_count[$set_id];
	}
}

/*
 * external_services_converter_oairecord
 * \brief Cette classe génère les enregistrement oai de notices complets et les met en cache 
 * 
 */
class external_services_converter_oairecord extends external_services_converter {
	private $set_life_duration;
	private $source_set_ids=array();
	private $repository_identifier="";
	private $deleted_record_statut=0;
	
	function external_services_converter_oairecord($object_type, $life_duration, $set_life_duration, $source_set_ids, $repository_identifier, $deleted_record_statut) {
		parent::external_services_converter($object_type, $life_duration);
		$this->set_life_duration = $set_life_duration+0;
		$this->source_set_ids = $source_set_ids;
		$this->repository_identifier = $repository_identifier;
		$this->deleted_record_statut = $deleted_record_statut;
	}
	
	function convert_batch($objects, $format, $target_charset='utf-8') {
		//Va chercher dans le cache les notices encore bonnes
		parent::convert_batch($objects, "oai_".$format, $target_charset);
		//Converti les notices qui doivent l'être
		$this->convert_uncachedoairecords($format, $target_charset);
		return $this->results;
	}
	
	function convert_batch_to_oairecords($notices_to_convert, $format, $target_charset) {
		global $dbh;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		//Allons chercher les dates et les statuts des notices
		$notice_datestamps=array();
		$notice_statuts=array();
		$notice_ids = $notices_to_convert;
		//Par paquets de 100 pour ne pas brusquer mysql
		$notice_idsz = array_chunk($notice_ids, 100);
		$iso8601 = new iso8601("YYYY-MM-DDThh:mm:ssZ");
		foreach ($notice_idsz as $anotice_ids) {
			$sql = "SELECT notice_id, UNIX_TIMESTAMP(update_date) AS datestamp, statut FROM notices WHERE notice_id IN (".implode(",", $anotice_ids).")";
			$res = mysql_query($sql, $dbh);
			while($row=mysql_fetch_assoc($res)) {
				$notice_datestamps[$row["notice_id"]] = $iso8601->unixtime_to_iso8601($row["datestamp"]);
				$notice_statuts[$row["notice_id"]] = $row["statut"];
			}
		}

		//Si il existe un status correspondant à la suppression, on génère ces enregistrements et on les supprime de la liste à générer.
		if ($this->deleted_record_statut) {
			foreach ($notice_statuts as $notice_id => $anotice_statut)
				if ($anotice_statut == $this->deleted_record_statut) {
					$notice_sets = connector_out_set_noticecaddie::get_notice_setlist($notice_id);
					$notice_sets = array_intersect($notice_sets, $this->source_set_ids);
					$oai_record = "";
					$oai_record .= "<record>";
					$oai_record .= '<header status="deleted">
									<identifier>oai:'.XMLEntities($this->repository_identifier).':'.$notice_id.'</identifier>
									<datestamp>'.$notice_datestamps[$notice_id].'</datestamp>';
					foreach ($notice_sets as $aset_id) {
						$oai_record .= "<setSpec>set_".$aset_id."</setSpec>";				
					}
					$oai_record .= '</header>';
					$oai_record .= "</record>";
					$this->results[$notice_id] = $oai_record;

					unset($notices_to_convert[array_search($notice_id, $notices_to_convert)]);
				}
		}
		
		//Convertissons les notices au format demandé si on ne souhaite pas uniquement les entêtes		
		$only_identifier = $format == "__oai_identifier";
		if (!$only_identifier) {
			$converter = new external_services_converter_notices(1, $this->set_life_duration);
			$metadatas = $converter->convert_batch($notices_to_convert, $format, $target_charset);
		}
		
		//Fabriquons les enregistrements
		foreach ($notices_to_convert as $notice_id) {
			$notice_sets = connector_out_set_noticecaddie::get_notice_setlist($notice_id);
			$notice_sets = array_intersect($notice_sets, $this->source_set_ids);
			$oai_record = "";
			
			if (!$only_identifier)
				$oai_record .= "<record>";
			$oai_record .= '<header>
							<identifier>oai:'.XMLEntities($this->repository_identifier).':'.$notice_id.'</identifier>
							<datestamp>'.$notice_datestamps[$notice_id].'</datestamp>';
			foreach ($notice_sets as $aset_id) {
				$oai_record .= "<setSpec>set_".$aset_id."</setSpec>";				
			}
			$oai_record .= '</header>';
			if (!$only_identifier) {
				$oai_record .= "<metadata>";
				$oai_record .= $metadatas[$notice_id];
				$oai_record .= "</metadata>";
			}
			if (!$only_identifier)
				$oai_record .= "</record>";
			
			$this->results[$notice_id] = $oai_record;
		}

	}
	
	function convert_uncachedoairecords($format, $target_charset='utf-8') {
		$notices_to_convert=array();
		foreach ($this->results as $notice_id => $aresult) {
			if (!$aresult) {
				$notices_to_convert[] = $notice_id;
			}
		}

		$this->convert_batch_to_oairecords($notices_to_convert, $format, $target_charset);
		
		//Cachons les notices converties maintenant.
		foreach ($notices_to_convert as $anotice_id) {
			if ($this->results[$anotice_id])
				$this->encache_value($anotice_id, $this->results[$anotice_id], "oai_".$format);			
		}
	}
}

/*
 * oai_out_server
 * \brief Cette classe fait le lien entre toutes les autres et fait tourner le bouzin 
 * 
 */
class oai_out_server {
	private $msg=array();
	private $oai_source_object = NULL;
	private $sets=array();
	
	//Constructeur
	function oai_out_server(&$msg, &$oai_source_object) {
		$this->msg = $msg;
		$this->oai_source_object = $oai_source_object;
	}
	
	//Fait tourner le serveur
	function process() {
		global $verb;
		
		//Pour ne pas avoir les entêtes définissant le fichier comme du xml, placer un &nx dans l'url (pour pouvoir utiliser le debugger zend par exemple)
		global $nx;
		if (!isset($nx))
			header('Content-Type: text/xml');
		
		$outsets = new connector_out_sets();
		foreach ($outsets->sets as &$aset) {
			if (in_array($aset->id, $this->oai_source_object->included_sets))
				$this->sets[] = array(
					"id" => $aset->id,
					"caption" => $aset->caption
				);
		}

		//Créons l'object que le serveur va utiliser pour récupérer les enregistrements
		$get_records_objects = new oai_out_get_records_notice($this->msg);
		$get_records_objects->oai_cache_duration = $this->oai_source_object->cache_complete_records_seconds;
		$get_records_objects->source_set_ids = $this->oai_source_object->included_sets;
		$get_records_objects->repository_identifier = $this->oai_source_object->repositoryIdentifier;
		$get_records_objects->notice_statut_deletion = $this->oai_source_object->link_status_to_deletion ? $this->oai_source_object->linked_status_to_deletion : 0;
		
		$additional_metadataformat = array();
		foreach ($this->oai_source_object->allowed_admin_convert_paths as $convert_path) {
			$additional_metadataformat["convert:".$convert_path] = array(
				"metadataPrefix" => "convert:".$convert_path,
				"metadataNamespace" => "http://sigb.net/pmb/es/oai/"."convert:".$convert_path,
				"schema" => "http://sigb.net/pmb/es/oai/unknown.xsd"
			);
		}
		
		//Créons l'objet protocol
		$deletion = ($this->oai_source_object->link_status_to_deletion ? 'transient' : 'no');
		$base_url = $this->oai_source_object->baseURL;
		if (!$base_url) {
			$base_url = curPageBaseURL();
			$base_url = substr($default_base_url, 0, strrpos($default_base_url, '/')+1);
			$base_url .= 'ws/connector_out.php?source_id='.$this->oai_source_object->id;
		}
		$oai_out_protocol = new oai_out_protocol($get_records_objects, $this->msg, $this->oai_source_object->repository_name, $this->oai_source_object->admin_email, $this->sets, $this->oai_source_object->repositoryIdentifier, $this->oai_source_object->chunksize, $this->oai_source_object->token_lifeduration, $this->oai_source_object->allow_gzip_compression, $deletion, $additional_metadataformat, $base_url);

		//Si on peut compresser, on compresse
		if ($this->oai_source_object->allow_gzip_compression)
			$test = ob_start("ob_gzhandler");

		$response = "";
		
		//Si la source n'est pas bien configurée
		if (!$this->oai_source_object->repository_name || !$this->oai_source_object->admin_email || !$this->oai_source_object->repositoryIdentifier) {
			echo $oai_out_protocol->oai_header();
			echo $oai_out_protocol->oai_error('unconfigured', $this->msg["unconfigured_source"]);
			echo $oai_out_protocol->oai_footer();
			return;
		}
		//Sinon c'est parti
		else
			switch($verb) {
				case 'Identify':
					$response .= $oai_out_protocol->oai_identify();
					break;
				case 'ListRecords':
					$response .= $oai_out_protocol->oai_list_records();
					break;
				case 'GetRecord':
					$response .= $oai_out_protocol->oai_get_record();
					break;
				case 'ListSets':
					$response .= $oai_out_protocol->oai_list_sets();
					break;
				case 'ListIdentifiers':
					$response .= $oai_out_protocol->oai_list_identifier();
					break;
				case 'ListMetadataFormats':
					$response .= $oai_out_protocol->oai_list_metadata_formats();
					break;
				default:
					$response .= $oai_out_protocol->oai_error('badVerb', $this->msg["illegal_verb"]);
					break;
			}

		//Header
		$response = $oai_out_protocol->oai_header() . $response;

		//Footer
		$response .= $oai_out_protocol->oai_footer();

		echo $response;
	}
}

?>