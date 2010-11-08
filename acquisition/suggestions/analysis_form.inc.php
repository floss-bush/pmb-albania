<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: analysis_form.inc.php,v 1.1 2009-11-04 14:37:54 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($include_path."/templates/serials.tpl.php");
require_once($class_path."/serials.class.php");
require_once($class_path."/suggestions.class.php");

$sug = new suggestions($id_sug);


$myAnalysis = new analysis($id_analysis);
if(!$myAnalysis->analysis_id){
	$myAnalysis->analysis_tit1 = $sug->titre;
	$myAnalysis->analysis_lien = $sug->url_suggestion;
	$myAnalysis->analysis_n_gen = $sug->commentaires;
	$myAnalysis->analysis_typdoc = "a";
}

$analysis_type_form = str_replace('!!id_sug!!',$sug->id_suggestion,$analysis_type_form);
print "<div class='row'>".$myAnalysis->analysis_form(true)."</div>";
	
?>