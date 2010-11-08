<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: parser.inc.php,v 1.12 2008-08-05 09:03:45 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

/*----------------------------------------------------------------------------------------
 Fonctions pour parser un fichier XML
 La fonction à appeler est _parser_ avec comme arguments :
     $nom_fichier : le nom du fichier XML
     $fonction : la lise des fonctions associées aux tags de niveau 2
     $rootelement : l'élément root du fichier XML
----------------------------------------------------------------------------------------*/

// Lecture récursive de la structure et stockage des paramètres

function _recursive_($indice, $niveau, $param, $tag_count, $vals) {
	if ($indice > count($vals))
		exit;
	while ($indice < count($vals)) {
		list ($key, $val) = each($vals);
		$indice ++;
		if (!isset($tag_count[$val["tag"]]))
			$tag_count[$val["tag"]] = 0;
		else {
			$tag_count[$val["tag"]]++;
		}
		if (isset($val["attributes"])) {
			$attributs = $val["attributes"];
			for ($k = 0; $k < count($attributs); $k ++) {
				list ($key_att, $val_att) = each($attributs);
				$param[$val["tag"]][$tag_count[$val["tag"]]][$key_att] = $val_att;
			}
		}
		if ($val[type] == "open") {
			$tag_count_next = array();
			_recursive_(& $indice, $niveau +1, & $param[$val["tag"]][$tag_count[$val["tag"]]], & $tag_count_next, & $vals);
		}
		if ($val[type] == "close") {
			if ($niveau > 2)
				break;
		}
		if ($val[type] == "complete") {
			$param[$val["tag"]][$tag_count[$val["tag"]]][value] = $val["value"];
		}
	}
}

//Parse le fichier [nom_fichier] et exécute les fonctions liées aux tags

function _parser_($nom_fichier, $fonction, $rootelement) {
	global $charset;
	$vals = array();
	$index = array();
	if ($file = fopen($nom_fichier, "r")) {
		$simple = fread($file, filesize($nom_fichier));
		fclose($file);
		$rx = "/<?xml.*encoding=[\'\"](.*?)[\'\"].*?>/m";
		if (preg_match($rx, $simple, $m)) $encoding = strtoupper($m[1]);
			else $encoding = "ISO-8859-1";
		$p = xml_parser_create($encoding);
		xml_parser_set_option($p, XML_OPTION_TARGET_ENCODING, $charset);		
		xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
		if (xml_parse_into_struct($p, $simple, & $vals, & $index) == 1) {
			xml_parser_free($p);
			$param = array();
			$tag_count = array();
			_recursive_(0, 1, & $param, & $tag_count, & $vals);
		}
		unset($vals, $index);
		if (is_array($param)) {
			if (count($param[$rootelement]) != 1) {
				echo "Erreur, ceci, n'est pas un fichier $rootelement !";
				exit;
			}
			$param_var = $param[$rootelement][0];
			for ($i = 0; $i < count($param_var); $i ++) {
				list ($key, $val) = each($param_var);
				if (isset($fonction[$key])) {
					for ($j = 0; $j < count($val); $j ++) {
						$param_fonction = $val[$j];
						eval($fonction[$key]."(\$param_fonction);");
					}
				}
			}
		}
	}
}

function _parser_text_($xml, $fonction, $rootelement) {
	global $charset;
	$vals = array();
	$index = array();
	if ($xml) {
		$simple = $xml;
		$rx = "/<?xml.*encoding=[\'\"](.*?)[\'\"].*?>/m";
		if (preg_match($rx, $simple, $m)) $encoding = strtoupper($m[1]);
			else $encoding = "ISO-8859-1";
		$p = xml_parser_create($encoding);
		xml_parser_set_option($p, XML_OPTION_TARGET_ENCODING, $charset);		
		xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
		if (xml_parse_into_struct($p, $simple, & $vals, & $index) == 1) {
			xml_parser_free($p);
			$param = array();
			$tag_count = array();
			_recursive_(0, 1, & $param, & $tag_count, & $vals);
		}
		unset($vals, $index);
		if (is_array($param)) {
			if (count($param[$rootelement]) != 1) {
				echo "Erreur, ceci, n'est pas un fichier $rootelement !";
				exit;
			}
			$param_var = $param[$rootelement][0];
			for ($i = 0; $i < count($param_var); $i ++) {
				list ($key, $val) = each($param_var);
				if (isset($fonction[$key])) {
					for ($j = 0; $j < count($val); $j ++) {
						$param_fonction = $val[$j];
						eval($fonction[$key]."(\$param_fonction);");
					}
				}
			}
		}
	}
}

function _parser_text_no_function_($xml, $rootelement="") {
	global $charset;
	$vals = array();
	$index = array();
	if ($xml) {
		$simple = $xml;
		
		$rx = "/<?xml.*encoding=[\'\"](.*?)[\'\"].*?>/m";
		if (preg_match($rx, $simple, $m)) $encoding = strtoupper($m[1]);
			else $encoding = "ISO-8859-1";
		$p = xml_parser_create($encoding);
		xml_parser_set_option($p, XML_OPTION_TARGET_ENCODING, $charset);		
		xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
		if (xml_parse_into_struct($p, $simple, & $vals, & $index) == 1) {
			xml_parser_free($p);
			$param = array();
			$tag_count = array();
			_recursive_(0, 1, & $param, & $tag_count, & $vals);
		}
		unset($vals, $index);
		if (is_array($param)) {
			if ($rootelement) {
				if (count($param[$rootelement]) != 1) {
					echo "Erreur, ceci n'est pas un fichier $rootelement !";
					exit;
				}
				$param_var = $param[$rootelement][0];
			} else $param_var = $param;
			return $param_var;
		}
	}
}

function recurse_xml($param, $level,$tagname) {
		$ret=str_repeat(" ",$level)."<".$tagname;
		$ret_sub="";
		$value="";
		if ($param=="") $param=array();
		while (list($key,$val)=each($param)) {
			if (is_array($val)) {
				for ($i=0; $i<count($val); $i++) {
					$ret_sub.=recurse_xml($val[$i],$level+1,$key)."</".$key.">\n";
				}
			} else {
				if ($key!="value")
					$ret.=" ".$key."=\"".$val."\"";
				else
					$value=$val;	
			}
		}
		$ret.=">".$value;
		if ($ret_sub!="") $ret.="\n".$ret_sub.str_repeat(" ",$level);
		return $ret;
	}
	
	function array_to_xml($param,$rootelement) {
		return recurse_xml($param,0,$rootelement)."</$rootelement>";	
	}
?>