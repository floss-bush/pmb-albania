<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: parameters_subst.class.php,v 1.2 2009-07-12 08:43:52 erwanmartin Exp $

require_once($include_path."/parser.inc.php");

//Cette classe va chercher dans un fichier une liste de paramètres et peut les extraire au besoin
//Exemple de fichier:
/*
<parameters_list>
	<parameters id="1">
		<parameter name="param1">value1</parameter>
		<parameter name="param2">value2</parameter>
		<parameter name="param3">value3</parameter>
	</parameters>
	<parameters id="2">
		<parameter name="param11">value11</parameter>
		<parameter name="param21">value12</parameter>
		<parameter name="param31">value13</parameter>
	</parameters>
</parameters_list>
* */
/*
//Utilisation:
require_once("$class_path/parameters_subst.class.php");
$parameter_subst = new parameters_subst('fichier.xml', $id);
$parameter_subst->extract();
 */
class parameters_subst {
	var $values = array();
	
	function parameters_subst($fichier, $id) {
		if (!file_exists($fichier))
			return;
		$file_content = file_get_contents($fichier);
		$parsed_file = _parser_text_no_function_($file_content);
		if (!isset($parsed_file["PARAMETERS_LIST"][0]["PARAMETERS"]))
			return;
		foreach ($parsed_file["PARAMETERS_LIST"][0]["PARAMETERS"] as $aparamlist) {
			if ($aparamlist['ID'] != $id)
				continue;
			if (!isset($aparamlist["PARAMETER"]))
				continue;
			foreach ($aparamlist["PARAMETER"] as $aparam) {
				$this->values[$aparam["NAME"]] = $aparam["value"];
			}
		}
	}
	
	function extract() {
		//Globalisons les valeurs
		foreach ($this->values as $value_name => $value_content)
			global $$value_name;
		//Affectons les
		extract($this->values, EXTR_OVERWRITE);
	}
}

?>