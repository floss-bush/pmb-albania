<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pmbesAutLinks.class.php,v 1.1 2010-10-15 11:41:28 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/external_services.class.php");

class pmbesAutLinks extends external_services_api_class {
	var $error=false;		//Y-a-t-il eu une erreur
	var $error_message="";	//Message correspondant à l'erreur
	var $es;				//Classe mère qui implémente celle-ci !
	var $msg;
	
	function getLinks($autTable, $id) {
		global $dbh;
		if ($autTable <= 0 || $autTable > 8)
			return false;
		$autlink = new aut_link($autTable, $id);
		$results = array();
		
		foreach($autlink->aut_list as $alink) {
			$aresult = array(
				'autlink_to' => utf8_normalize($alink['to']),
				'autlink_to_id' => utf8_normalize($alink['to_num']),
				'autlink_to_libelle' => utf8_normalize($alink['libelle']),
				'autlink_type' => utf8_normalize($alink['type']),
				'autlink_reciproc' => utf8_normalize($alink['reciproc']),
				'autlink_comment' => utf8_normalize($alink['comment']),
			);
			$results[] = $aresult;
		}
		
		return $results;
	}
	
}




?>