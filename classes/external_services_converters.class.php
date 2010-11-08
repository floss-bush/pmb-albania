<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: external_services_converters.class.php,v 1.12 2010-08-13 08:33:00 erwanmartin Exp $

//
//Convertisseurs et cacheur de formats des résultats des services externes
//

require_once("$base_path/admin/convert/export.class.php");
require_once("$base_path/admin/convert/convert.class.php");
require_once("$class_path/external_services_caches.class.php");

if (version_compare(PHP_VERSION,'5','>=') && extension_loaded('xsl')) {
	if (substr(phpversion(), 0, 1) == "5") @ini_set("zend.ze1_compatibility_mode", "0");
	require_once($include_path.'/xslt-php4-to-php5.inc.php');
}

class external_services_converter {
	var $object_type=0; //Type d'objet
	var $life_duration=600; //Durée de vie de l'objet converti, en secondes
	var $results=array();
	var $cache=NULL;
	var $params=array();
	
	function external_services_converter($object_type, $life_duration) {
		$this->object_type = $object_type+0;
		$this->life_duration = $life_duration+0;
		$this->cache = new external_services_cache('es_cache_blob', $life_duration);
	}
	
	function set_params($new_params) {
		$this->params = $new_params;
	}
	
	function convert_batch($objects, $format, $target_charset='iso-8859-1') {
		//Cette fonction va chercher les valeurs dans le cache si elle existent.
		
		global $dbh;
		//Si aucun résultat, pas de traitement
		if (!is_array($objects)) {
			$this->results = array();
			return;
		}
		array_walk($objects, create_function('&$a', '$a+=0;'));//Soyons sûr de ne stocker que des entiers dans le tableau.
		$objects = array_unique($objects);
		
		if (!$objects) {
			$this->results = array();
			return;
		}

		//Initialisons tous avec des zéros
		$this->results = array_combine($objects, array_fill(0, count($objects), 0));
		
		//Allons chercher dans le cache ce qui est encore bon
		$in_cache = $this->cache->get_objectref_contents($this->object_type, '', $format, $objects);
		$rawed = substr($format, 0, 9) == "raw_array";
		foreach ($in_cache as $object_ref => $object_content) {
			if ($rawed)
				$this->results[$object_ref] = unserialize($object_content);
			else
				$this->results[$object_ref] = $object_content;
		}
		
	}
	
	function encache_value($object_id, $value, $format) {
		//Mise en cache d'une valeur
		global $dbh;
		$rawed = substr($format, 0, 9) == "raw_array";
		if ($rawed)
			$value = serialize($value);
		$this->cache->encache_objectref_contents($this->object_type, '', $format, array($object_id => $value));
	}
	
}

class external_services_converter_notices extends external_services_converter {
	
	function convert_batch($objects, $format, $target_charset='iso-8859-1') {
		if (!$objects)
			return array();
		//Va chercher dans le cache les notices encore bonnes
		$format_ref = $format.'_C_'.$target_charset;
		if ($this->params["include_links"])
			$format_ref .= "_withlinks";
		if ($this->params["include_items"])
			$format_ref .= "_withitems";
		parent::convert_batch($objects, $format_ref, $target_charset);
		//Converti les notices qui 
		$this->convert_uncachednotices($format, $format_ref, $target_charset);
		return $this->results;
	}

	function convert_batch_to_pmb_xml($notices_to_convert, $target_charset='iso-8859-1') {
		global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		$xmlexport = new export($notices_to_convert);
		$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
		$parametres = array();
		if ($this->params["include_links"]) {
			$parametres["genere_lien"]=1;
			$parametres["mere"]=1;
			$parametres["fille"]=1;
			$parametres["notice_art_link"]=1;
			$parametres["notice_perio_link"]=1;
			$parametres["bull_link"]=1;
			$parametres["perio_link"]=1;
			$parametres["art_link"]=1;
			$parametres["notice_mere_link"]=1;
			$parametres["notice_fille_link"]=1;
		}
		if ($this->params["include_authorite_ids"]) {
			$parametres["include_authorite_ids"] = true;
		}
		$keep_expl = isset($this->params["include_items"]) && $this->params["include_items"];
		while($xmlexport->get_next_notice("", array(), array(), $keep_expl, $parametres)) {
			$xmlexport->toxml();
			if ($current_notice_id != -1) {
				$this->results[$current_notice_id] = $xmlexport->notice;
				//La classe export exporte ses données dans la charset de la base.
				//Convertissons si besoin
				if ($charset!='utf-8' && $target_charset == 'utf-8')
					$this->results[$current_notice_id] = utf8_encode($this->results[$current_notice_id]);
				else if ($charset=='utf-8' && $target_charset != 'utf-8')
					$this->results[$current_notice_id] = utf8_decode($this->results[$current_notice_id]);
				$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
			}
		}
	}

	function convert_batch_to_json($notices_to_convert, $target_charset='iso-8859-1') {
			global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		$xmlexport = new export($notices_to_convert);
		$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
		$parametres = array();
		if ($this->params["include_links"]) {
			$parametres["genere_lien"]=1;
			$parametres["mere"]=1;
			$parametres["fille"]=1;
			$parametres["notice_art_link"]=1;
			$parametres["notice_perio_link"]=1;
			$parametres["bull_link"]=1;
			$parametres["perio_link"]=1;
			$parametres["art_link"]=1;
			$parametres["notice_mere_link"]=1;
			$parametres["notice_fille_link"]=1;
		}
		if ($this->params["include_authorite_ids"]) {
			$parametres["include_authorite_ids"] = true;
		}
		$keep_expl = isset($this->params["include_items"]) && $this->params["include_items"];
		while($xmlexport->get_next_notice("", array(), array(), $keep_expl, $parametres)) {
			$xmlexport->tojson();
			if ($current_notice_id != -1) {
				$this->results[$current_notice_id] = $xmlexport->notice;
				//La classe export exporte ses données dans la charset de la base.
				//Convertissons si besoin
				if ($charset!='utf-8' && $target_charset == 'utf-8')
					$this->results[$current_notice_id] = utf8_encode($this->results[$current_notice_id]);
				else if ($charset=='utf-8' && $target_charset != 'utf-8')
					$this->results[$current_notice_id] = utf8_decode($this->results[$current_notice_id]);
				$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
			}
		}
	}

	function convert_batch_to_json_assoc($notices_to_convert, $target_charset='iso-8859-1') {
		$this->convert_batch_to_php_array_assoc($notices_to_convert, $target_charset);
		foreach ($notices_to_convert as $anotice_id)
			$this->results[$anotice_id] = json_encode($this->results[$anotice_id]);
	}
	
	function convert_batch_to_serialized($notices_to_convert, $target_charset='iso-8859-1') {
		global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		$xmlexport = new export($notices_to_convert);
		$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
		$parametres = array();
		if ($this->params["include_links"]) {
			$parametres["genere_lien"]=1;
			$parametres["mere"]=1;
			$parametres["fille"]=1;
			$parametres["notice_art_link"]=1;
			$parametres["notice_perio_link"]=1;
			$parametres["bull_link"]=1;
			$parametres["perio_link"]=1;
			$parametres["art_link"]=1;
			$parametres["notice_mere_link"]=1;
			$parametres["notice_fille_link"]=1;
		}
		if ($this->params["include_authorite_ids"]) {
			$parametres["include_authorite_ids"] = true;
		}
		$keep_expl = isset($this->params["include_items"]) && $this->params["include_items"];
		while($xmlexport->get_next_notice("", array(), array(), $keep_expl, $parametres)) {
			$xmlexport->toserialized();
			if ($current_notice_id != -1) {
				$this->results[$current_notice_id] = $xmlexport->notice;
				//La classe export exporte ses données dans la charset de la base.
				//Convertissons si besoin
				if ($charset!='utf-8' && $target_charset == 'utf-8')
					$this->results[$current_notice_id] = utf8_encode($this->results[$current_notice_id]);
				else if ($charset=='utf-8' && $target_charset != 'utf-8')
					$this->results[$current_notice_id] = utf8_decode($this->results[$current_notice_id]);
				$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
			}
		}
	}
	
	function convert_batch_to_serialized_assoc($notices_to_convert, $target_charset='iso-8859-1') {
		$this->convert_batch_to_php_array_assoc($notices_to_convert, $target_charset);
		foreach ($notices_to_convert as $anotice_id) {
			$this->results[$anotice_id] = serialize($this->results[$anotice_id]);
		}
	}	
	
	function convert_batch_to_php_array($notices_to_convert, $target_charset='iso-8859-1') {
		global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		$xmlexport = new export($notices_to_convert);
		$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
		$parametres = array();
		if ($this->params["include_links"]) {
			$parametres["genere_lien"]=1;
			$parametres["mere"]=1;
			$parametres["fille"]=1;
			$parametres["notice_art_link"]=1;
			$parametres["notice_perio_link"]=1;
			$parametres["bull_link"]=1;
			$parametres["perio_link"]=1;
			$parametres["art_link"]=1;
			$parametres["notice_mere_link"]=1;
			$parametres["notice_fille_link"]=1;
		}
		if ($this->params["include_authorite_ids"]) {
			$parametres["include_authorite_ids"] = true;
		}
		$keep_expl = isset($this->params["include_items"]) && $this->params["include_items"];
		while($xmlexport->get_next_notice("", array(), array(), $keep_expl, $parametres)) {
			$xmlexport->to_raw_array();
			if ($current_notice_id != -1) {
				$xmlexport_notice = $xmlexport->notice;
				$aresult = $xmlexport_notice;
				$aresult = array();
				$headers = array();
				if (isset($xmlexport_notice['rs']["value"]))
					$headers[] = array("name" => "rs", "value" => $xmlexport_notice['rs']["value"]);
				if (isset($xmlexport_notice['dt']["value"]))
					$headers[] = array("name" => "dt", "value" => $xmlexport_notice['dt']["value"]);
				if (isset($xmlexport_notice['bl']["value"]))
					$headers[] = array("name" => "bl", "value" => $xmlexport_notice['bl']["value"]);
				if (isset($xmlexport_notice['hl']["value"]))
					$headers[] = array("name" => "hl", "value" => $xmlexport_notice['hl']["value"]);
				if (isset($xmlexport_notice['el']["value"]))
					$headers[] = array("name" => "el", "value" => $xmlexport_notice['el']["value"]);
				if (isset($xmlexport_notice['ru']["value"]))
					$headers[] = array("name" => "ru", "value" => $xmlexport_notice['ru']["value"]);
				$aresult["id"] = $current_notice_id;
				$aresult["header"] = $headers;
				$aresult["f"] = $xmlexport_notice['f'];
				foreach ($aresult["f"] as &$af) {
					$af["ind"] = isset($af["ind"]) ? $af["ind"] : "";
					$af["id"] = isset($af["id"]) ? $af["id"] : "";
					$af["s"] = isset($af["s"]) ? $af["s"] : array();
					foreach ($af["s"] as &$as) {
						$as["value"] = isset($as["value"]) ? $as["value"] : "";
						$as["c"] = isset($as["c"]) ? $as["c"] : "";
						//La classe export exporte ses données dans la charset de la base.
						//Convertissons si besoin
						if ($charset!='utf-8' && $target_charset == 'utf-8')
							$as["value"] = utf8_encode($as["value"]);
						else if ($charset=='utf-8' && $target_charset != 'utf-8')
							$as["value"] = utf8_decode($as["value"]);
					}

				}
				$this->results[$current_notice_id] = $aresult;
				$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
			}
		}
	}
	
	function convert_batch_to_php_array_assoc($notices_to_convert, $target_charset='iso-8859-1') {
		global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		$xmlexport = new export($notices_to_convert);
		$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
		$parametres = array();
		if ($this->params["include_links"]) {
			$parametres["genere_lien"]=1;
			$parametres["mere"]=1;
			$parametres["fille"]=1;
			$parametres["notice_art_link"]=1;
			$parametres["notice_perio_link"]=1;
			$parametres["bull_link"]=1;
			$parametres["perio_link"]=1;
			$parametres["art_link"]=1;
			$parametres["notice_mere_link"]=1;
			$parametres["notice_fille_link"]=1;
		}
		if ($this->params["include_authorite_ids"]) {
			$parametres["include_authorite_ids"] = true;
		}
		$keep_expl = isset($this->params["include_items"]) && $this->params["include_items"];
		while($xmlexport->get_next_notice("", array(), array(), $keep_expl, $parametres)) {
			$xmlexport->to_raw_array();
			if ($current_notice_id != -1) {
				$xmlexport_notice = $xmlexport->notice;
				$aresult = array();
				$headers = array();
				if (isset($xmlexport_notice['rs']["value"]))
					$headers["rs"] = $xmlexport_notice['rs']["value"];
				if (isset($xmlexport_notice['dt']["value"]))
					$headers["dt"] = $xmlexport_notice['dt']["value"];
				if (isset($xmlexport_notice['bl']["value"]))
					$headers["bl"] = $xmlexport_notice['bl']["value"];
				if (isset($xmlexport_notice['hl']["value"]))
					$headers["hl"] = $xmlexport_notice['hl']["value"];
				if (isset($xmlexport_notice['el']["value"]))
					$headers["el"] = $xmlexport_notice['el']["value"];
				if (isset($xmlexport_notice['ru']["value"]))
					$headers["ru"] = $xmlexport_notice['ru']["value"];
				$aresult["id"] = $current_notice_id;
				$aresult["header"] = $headers;
				$aresult["f"] = array();
				foreach ($xmlexport_notice['f'] as &$af) {
					if (!isset($af["c"]))
						continue;
					if (!isset($aresult["f"][$af["c"]]))
						$aresult["f"][$af["c"]] = array();
					$arf = array();
					$arf["ind"] = isset($af["ind"]) ? $af["ind"] : "";
					$arf["id"] = isset($af["id"]) ? $af["id"] : "";
					if (isset($af["s"])) {
						foreach ($af["s"] as &$as) {
							//La classe export exporte ses données dans la charset de la base.
							//Convertissons si besoin
							$value = $as["value"];
							if ($charset!='utf-8' && $target_charset == 'utf-8')
								$value = utf8_encode($value);
							else if ($charset=='utf-8' && $target_charset != 'utf-8')
								$value = utf8_decode($value);
							if (isset($arf[$as["c"]]) && !is_array($arf[$as["c"]]))
								$arf[$as["c"]] = array($arf[$as["c"]]);
							if (isset($arf[$as["c"]]) && is_array($arf[$as["c"]]))
								$arf[$as["c"]][] = $value;
							else
								$arf[$as["c"]] = $value;
						}
					}
					else if (isset($af["value"])) {
						$arf["value"] = $af["value"];
					}

					$aresult["f"][$af["c"]][] = $arf;
				}
				$this->results[$current_notice_id] = $aresult;
				$current_notice_id = $xmlexport->notice_list[$xmlexport->current_notice];
			}
		}
	}
	
	
	function apply_xsl_to_xml($xml, $xsl, $params) {
		global $charset;
		$xh = xslt_create();
		xslt_set_encoding($xh, $charset);
		$arguments = array(
	   	  '/_xml' => $xml,
	   	  '/_xsl' => $xsl
		);
		$result = xslt_process($xh, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments, $params);
		xslt_free($xh);
		return $result;		
	}

	function convert_batch_to_dublin_core($notices_to_convert, $target_charset) {
		global $base_path, $charset, $opac_url_base;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		//Un petit tour en xml et après on converti par xsl
		$this->convert_batch_to_pmb_xml($notices_to_convert);
		
		//Allons chercher la feuille de style
		$xsl_pmbxmlunimarc_to_dc = file_get_contents($base_path."/admin/convert/imports/pmbxml2dc/pmbxmlunimarc2dc.xsl");
		
		foreach ($notices_to_convert as $anotice_id) {
			if (!$this->results[$anotice_id])
				continue;
			$pmbxmlunimarc_version = '<?xml version="1.0" encoding="'.$charset.'"?><unimarc>'.$this->results[$anotice_id]."</unimarc>";
			$converted_version = $this->apply_xsl_to_xml($pmbxmlunimarc_version, $xsl_pmbxmlunimarc_to_dc, array("notice_url_base" => $opac_url_base));
			$converted_version = preg_replace('/^<\?xml[^>]*\?>/', "", $converted_version);

			//Cette conversion sort de l'utf-8
			if ($target_charset != 'utf-8')
				$converted_version = utf8_decode($converted_version);

			$this->results[$anotice_id] = $converted_version;
		}
	}
	
	//Utilise les fonction de admin/convert pour faire une conversion perso
	function convert_batch_to_adminconvert_script($notices_to_convert, $the_conversion, $target_charset) {
		global $base_path, $charset, $opac_url_base;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		//Un petit tour en xml dans le charset de la base et après on invoque la classe de conversion
		$this->convert_batch_to_pmb_xml($notices_to_convert, $charset);

		$conv = new convert("", $the_conversion["position"], true);

		foreach ($notices_to_convert as $anotice_id) {
			if (!$this->results[$anotice_id])
				continue;
			$conv->prepared_notice = $xml_header.$this->results[$anotice_id];
			$converted_version = $conv->transform(true);
			
			if ($the_conversion["output_charset"] == 'utf-8' && $target_charset != 'utf-8')
				$converted_version = utf8_decode($converted_version);
			if ($the_conversion["output_charset"] != 'utf-8' && $target_charset == 'utf-8')
				$converted_version = utf8_encode($converted_version);
			$this->results[$anotice_id] = $converted_version;
		}
	}

	function convert_batch_to_header($notices_to_convert, $target_charset) {
		global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		global $class_path;
		require_once("$class_path/mono_display.class.php");
			
		foreach ($notices_to_convert as $anotice_id) {
			$monod = new mono_display($anotice_id, 0, '', 0, '', '', '', 0, 1, 0, 0, '', 0, true, false, 0);
			$this->results[$anotice_id] = $monod->header;
			
			if ($charset!='utf-8' && $target_charset == 'utf-8')
				$this->results[$anotice_id] = utf8_encode($this->results[$anotice_id]);
			else if ($charset=='utf-8' && $target_charset != 'utf-8')
				$this->results[$anotice_id] = utf8_decode($this->results[$anotice_id]);
		}
	}
	
	function convert_batch_to_isbd($notices_to_convert, $target_charset) {
		global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		foreach ($notices_to_convert as $anotice_id) {
			$monod = new mono_display($anotice_id, 1, '', 0, '', '', '', 0, 1, 0, 0, '', 0, true, false, 0);
			$this->results[$anotice_id] = $monod->isbd;
			
			if ($charset!='utf-8' && $target_charset == 'utf-8')
				$this->results[$anotice_id] = utf8_encode($this->results[$anotice_id]);
			else if ($charset=='utf-8' && $target_charset != 'utf-8')
				$this->results[$anotice_id] = utf8_decode($this->results[$anotice_id]);
		}
	}
	
	function convert_batch_to_isbd_suite($notices_to_convert, $target_charset) {
		global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		foreach ($notices_to_convert as $anotice_id) {
			$monod = new mono_display($anotice_id, 6, '', 0, '', '', '', 0, 1, 0, 0, '', 0, true, false, 0);
			$this->results[$anotice_id] = $monod->isbd;
			
			if ($charset!='utf-8' && $target_charset == 'utf-8')
				$this->results[$anotice_id] = utf8_encode($this->results[$anotice_id]);
			else if ($charset=='utf-8' && $target_charset != 'utf-8')
				$this->results[$anotice_id] = utf8_decode($this->results[$anotice_id]);
		}
	}
	
	function convert_uncachednotices($format, $format_ref, $target_charset='iso-8859-1') {
		$notices_to_convert=array();
		foreach ($this->results as $notice_id => $aresult) {
			if (!$aresult && $notice_id) {
				$notices_to_convert[] = $notice_id;
			}
		}

		if (substr($format, 0, 8) == "convert:") {
			//C'est une conversion par script admin/convert
			$convert_path = substr($format, 8);
			
			//Trouvons la position de la conversion pour invoquer la classe de conversion
			$the_conversion = NULL;
			$catalog = $this->get_export_possibilities(false);
			foreach ($catalog as $aconvert) {
				if ($aconvert["path"] == $convert_path) {
					$the_conversion = $aconvert;
				}
			}
			
			if (!$the_conversion) {
				//Oups! pas trouvé
				//Renvoyons des strings vides.
				foreach($notices_to_convert as $anotice_id) {
					$this->results[$anotice_id] = "";
				}
			}
			else {
				//C'est parti!
				$this->convert_batch_to_adminconvert_script($notices_to_convert, $the_conversion, $target_charset);
			}
			
		}
		else {
			//Conversion builtin
			switch ($format) {
				case "pmb_xml_unimarc":
					$this->convert_batch_to_pmb_xml($notices_to_convert, $target_charset);
					break;
				case "json_unimarc":
					$this->convert_batch_to_json($notices_to_convert, $target_charset);
					break;
				case "json_unimarc_assoc":
					$this->convert_batch_to_json_assoc($notices_to_convert, $target_charset);
					break;
				case "serialized_unimarc":
					$this->convert_batch_to_serialized($notices_to_convert, $target_charset);
					break;
				case "serialized_unimarc_assoc":
					$this->convert_batch_to_serialized_assoc($notices_to_convert, $target_charset);
					break;
				case "raw_array":
					$this->convert_batch_to_php_array($notices_to_convert, $target_charset);
					break;
				case "raw_array_assoc":
					$this->convert_batch_to_php_array_assoc($notices_to_convert, $target_charset);
					break;
				case "header":
					$this->convert_batch_to_header($notices_to_convert, $target_charset);
					break;
				case "isbd":
					$this->convert_batch_to_isbd($notices_to_convert, $target_charset);
					break;
				case "isbd_suite":
					$this->convert_batch_to_isbd_suite($notices_to_convert, $target_charset);
					break;
				case "dc":
				case "oai_dc":
					$this->convert_batch_to_dublin_core($notices_to_convert, $target_charset);
					break;
				default:
					//Par défaut on renvoi juste le notice_id
					foreach($notices_to_convert as $anotice_id) {
						$this->results[$anotice_id] = $anotice_id;
					}
					break;
			}
		}

		//Cachons les notices converties maintenant.
		foreach ($notices_to_convert as $anotice_id) {
			if ($this->results[$anotice_id])
				$this->encache_value($anotice_id, $this->results[$anotice_id], $format_ref);
		}
	}
	
	//Cette fonction parse les différents catalogues de admin/convert et liste les conversions qui exportent en xml
	static function get_export_possibilities($only_xml=true) {
		global $base_path;
		$result = array();
		$catalog_xml = file_get_contents($base_path."/admin/convert/imports/catalog.xml");
		$catalog = _parser_text_no_function_($catalog_xml);
		$count = 0;
		//Parsons le catalogue
		if (isset($catalog["CATALOG"][0]["ITEM"]))
			foreach ($catalog["CATALOG"][0]["ITEM"] as $aconverttype) {
				if ($aconverttype["EXPORT"] == "yes") {
					$path = $aconverttype["PATH"];
					if ($path) {
						//Regardons si cette conversion sort du xml
						$export_xml = file_get_contents($base_path."/admin/convert/imports/$path/params.xml");
						$params = _parser_text_no_function_($export_xml);
						if (isset($params["PARAMS"][0]["OUTPUT"][0]["TYPE"])) {
							$output_type = $params["PARAMS"][0]["OUTPUT"][0]["TYPE"];
							if (!$only_xml || (strtolower($output_type) == 'xml')) {
								//Oui? on l'ajoute au resultat
								$conv_charset = isset($params["PARAMS"][0]["OUTPUT"][0]["CHARSET"]) ? $params["PARAMS"][0]["OUTPUT"][0]["CHARSET"] : 'iso-8859-1';
								$result[] = array(
									"position" => $count,
									"caption" => $aconverttype["EXPORTNAME"],
									"path" => $path,
									"output_charset" => $conv_charset
								);
							}
						}
					}
				}
				$count++;
			}
		return $result;
	}
}

class external_services_converter_external_notices extends external_services_converter {
	
	function convert_batch($objects, $format, $target_charset='iso-8859-1') {
		if (!$objects)
			return array();
		//Va chercher dans le cache les notices encore bonnes
		$format_ref = $format.'_C_'.$target_charset;
		parent::convert_batch($objects, $format_ref, $target_charset);
		//Converti les notices qui 
		$this->convert_uncachednotices($format, $format_ref, $target_charset);
		return $this->results;
	}

	function get_notice_unimarc_array($notice_id) {
		global $dbh;
		$requete = "SELECT source_id FROM external_count WHERE rid=".addslashes($notice_id);
		$myQuery = mysql_query($requete, $dbh);
		if (!mysql_num_rows($myQuery))
			return FALSE;
		$source_id = mysql_result($myQuery, 0, 0);
		if (!$source_id)
			return FALSE;

		$requete="select * from entrepot_source_".$source_id." where recid='".addslashes($notice_id)."' order by ufield,field_order,usubfield,subfield_order,value";
		$myQuery = mysql_query($requete, $dbh);
		$unimarc = array('f' => array());
		if(mysql_num_rows($myQuery)) {
			while ($l=mysql_fetch_object($myQuery)) {
				if (in_array($l->ufield, array('bl', 'rs', 'dt', 'el', 'hl', 'ru'))) {
					$unimarc[$l->ufield]['value'] = $l->value;
					continue;
				}
				$unimarc['f'][$l->field_order]['c'] = $l->ufield;
				$unimarc['f'][$l->field_order]['ind'] = '';
				$unimarc['f'][$l->field_order]['id'] = '';
				$unimarc['f'][$l->field_order]['s'][$l->subfield_order] = array('c' => $l->usubfield, 'value' => $l->value);
			}
		}
		$unimarc['f'] = array_values($unimarc['f']);
		foreach($unimarc['f'] as &$afield) {
			$afield['s'] = array_values($afield['s']);
			unset($afield);
		}
		
		return $unimarc;
	}
		
	function convert_batch_to_php_array($notices_to_convert, $target_charset='iso-8859-1') {
		global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		foreach($notices_to_convert as $anotice_id)  {
			$xmlexport_notice = $this->get_notice_unimarc_array($anotice_id);
			if (!$xmlexport_notice)
				continue;
			$aresult = $xmlexport_notice;
			$aresult = array();
			$headers = array();
			if (isset($xmlexport_notice['rs']["value"]))
				$headers[] = array("name" => "rs", "value" => $xmlexport_notice['rs']["value"]);
			if (isset($xmlexport_notice['dt']["value"]))
				$headers[] = array("name" => "dt", "value" => $xmlexport_notice['dt']["value"]);
			if (isset($xmlexport_notice['bl']["value"]))
				$headers[] = array("name" => "bl", "value" => $xmlexport_notice['bl']["value"]);
			if (isset($xmlexport_notice['hl']["value"]))
				$headers[] = array("name" => "hl", "value" => $xmlexport_notice['hl']["value"]);
			if (isset($xmlexport_notice['el']["value"]))
				$headers[] = array("name" => "el", "value" => $xmlexport_notice['el']["value"]);
			if (isset($xmlexport_notice['ru']["value"]))
				$headers[] = array("name" => "ru", "value" => $xmlexport_notice['ru']["value"]);
			$aresult["id"] = $anotice_id;
			$aresult["header"] = $headers;
			$aresult["f"] = $xmlexport_notice['f'];
			foreach ($aresult["f"] as &$af) {
				$af["ind"] = isset($af["ind"]) ? $af["ind"] : "";
				$af["id"] = isset($af["id"]) ? $af["id"] : "";
				$af["s"] = isset($af["s"]) ? $af["s"] : array();
				foreach ($af["s"] as &$as) {
					$as["value"] = isset($as["value"]) ? $as["value"] : "";
					$as["c"] = isset($as["c"]) ? $as["c"] : "";
					//La classe export exporte ses données dans la charset de la base.
					//Convertissons si besoin
					if ($charset!='utf-8' && $target_charset == 'utf-8')
						$as["value"] = utf8_encode($as["value"]);
					else if ($charset=='utf-8' && $target_charset != 'utf-8')
						$as["value"] = utf8_decode($as["value"]);
				}

			}
			$this->results[$anotice_id] = $aresult;
		}
	}
	
	function convert_batch_to_php_array_assoc($notices_to_convert, $target_charset='iso-8859-1') {
		global $charset;
		if (!$notices_to_convert) //Rien à faire? On fait rien
			return;

		foreach($notices_to_convert as $anotice_id)  {
			$xmlexport_notice = $this->get_notice_unimarc_array($anotice_id);
			if (!$xmlexport_notice)
				continue;
			$aresult = array();
			$headers = array();
			if (isset($xmlexport_notice['rs']["value"]))
				$headers["rs"] = $xmlexport_notice['rs']["value"];
			if (isset($xmlexport_notice['dt']["value"]))
				$headers["dt"] = $xmlexport_notice['dt']["value"];
			if (isset($xmlexport_notice['bl']["value"]))
				$headers["bl"] = $xmlexport_notice['bl']["value"];
			if (isset($xmlexport_notice['hl']["value"]))
				$headers["hl"] = $xmlexport_notice['hl']["value"];
			if (isset($xmlexport_notice['el']["value"]))
				$headers["el"] = $xmlexport_notice['el']["value"];
			if (isset($xmlexport_notice['ru']["value"]))
				$headers["ru"] = $xmlexport_notice['ru']["value"];
			$aresult["id"] = $anotice_id;
			$aresult["header"] = $headers;
			$aresult["f"] = array();
			foreach ($xmlexport_notice['f'] as &$af) {
				if (!isset($af["c"]))
					continue;
				if (!isset($aresult["f"][$af["c"]]))
					$aresult["f"][$af["c"]] = array();
				$arf = array();
				$arf["ind"] = isset($af["ind"]) ? $af["ind"] : "";
				$arf["id"] = isset($af["id"]) ? $af["id"] : "";
				if (isset($af["s"])) {
					foreach ($af["s"] as &$as) {
						//La classe export exporte ses données dans la charset de la base.
						//Convertissons si besoin
						$value = $as["value"];
						if ($charset!='utf-8' && $target_charset == 'utf-8')
							$value = utf8_encode($value);
						else if ($charset=='utf-8' && $target_charset != 'utf-8')
							$value = utf8_decode($value);
						if (isset($arf[$as["c"]]) && !is_array($arf[$as["c"]]))
							$arf[$as["c"]] = array($arf[$as["c"]]);
						if (isset($arf[$as["c"]]) && is_array($arf[$as["c"]]))
							$arf[$as["c"]][] = $value;
						else
							$arf[$as["c"]] = $value;
					}
				}
				else if (isset($af["value"])) {
					$arf["value"] = $af["value"];
				}

				$aresult["f"][$af["c"]][] = $arf;
			}
			$this->results[$anotice_id] = $aresult;
		}
	}
	
	function convert_batch_to_serialized($notices_to_convert, $target_charset='iso-8859-1') {
			$this->convert_batch_to_php_array($notices_to_convert, $target_charset);
		foreach ($notices_to_convert as $anotice_id) {
			$this->results[$anotice_id] = serialize($this->results[$anotice_id]);
		}
	}
	
	function convert_batch_to_serialized_assoc($notices_to_convert, $target_charset='iso-8859-1') {
		$this->convert_batch_to_php_array_assoc($notices_to_convert, $target_charset);
		foreach ($notices_to_convert as $anotice_id) {
			$this->results[$anotice_id] = serialize($this->results[$anotice_id]);
		}
	}	
	
	function convert_batch_to_json($notices_to_convert, $target_charset='iso-8859-1') {
		$this->convert_batch_to_php_array($notices_to_convert, $target_charset);
		foreach ($notices_to_convert as $anotice_id)
			$this->results[$anotice_id] = json_encode($this->results[$anotice_id]);
	}

	function convert_batch_to_json_assoc($notices_to_convert, $target_charset='iso-8859-1') {
		$this->convert_batch_to_php_array_assoc($notices_to_convert, $target_charset);
		foreach ($notices_to_convert as $anotice_id)
			$this->results[$anotice_id] = json_encode($this->results[$anotice_id]);
	}
	
	function convert_uncachednotices($format, $format_ref, $target_charset='iso-8859-1') {
		$notices_to_convert=array();
		foreach ($this->results as $notice_id => $aresult) {
			if (!$aresult && $notice_id) {
				$notices_to_convert[] = $notice_id;
			}
		}
		
		//Conversion builtin
		switch ($format) {
			case "json_unimarc":
				$this->convert_batch_to_json($notices_to_convert, $target_charset);
				break;
			case "json_unimarc_assoc":
				$this->convert_batch_to_json_assoc($notices_to_convert, $target_charset);
				break;
			case "serialized_unimarc":
				$this->convert_batch_to_serialized($notices_to_convert, $target_charset);
				break;
			case "serialized_unimarc_assoc":
				$this->convert_batch_to_serialized_assoc($notices_to_convert, $target_charset);
				break;
			case "raw_array":
				$this->convert_batch_to_php_array($notices_to_convert, $target_charset);
				break;
			case "raw_array_assoc":
				$this->convert_batch_to_php_array_assoc($notices_to_convert, $target_charset);
				break;
			default:
				//Par défaut on renvoi juste le notice_id
				foreach($notices_to_convert as $anotice_id) {
					$this->results[$anotice_id] = $anotice_id;
				}
				break;
		}

		//Cachons les notices converties maintenant.
		foreach ($notices_to_convert as $anotice_id) {
			if ($this->results[$anotice_id])
				$this->encache_value($anotice_id, $this->results[$anotice_id], $format_ref);
		}
	}
	
}


?>