<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_show.php,v 1.22 2009-02-11 21:41:55 touraine37 Exp $
$base_path=".";                            
$base_auth = ""; 

require_once ("$base_path/includes/init.inc.php"); 
require_once($base_path."/includes/error_report.inc.php") ;
require_once($base_path."/includes/global_vars.inc.php");
require_once($base_path.'/includes/opac_config.inc.php');
	
// récupération paramètres MySQL et connection á la base
require_once($base_path.'/includes/opac_db_param.inc.php');
require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

require_once($base_path."/includes/misc.inc.php");

//Sessions !! Attention, ce doit être impérativement le premier include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");

require_once($base_path.'/includes/start.inc.php');
require_once($base_path."/includes/check_session_time.inc.php");

// récupération localisation
require_once($base_path.'/includes/localisation.inc.php');

// version actuelle de l'opac
require_once($base_path.'/includes/opac_version.inc.php');

// fonctions de gestion de formulaire
require_once($base_path.'/includes/javascript/form.inc.php');

require_once($base_path.'/includes/templates/common.tpl.php');
require_once($base_path.'/includes/divers.inc.php');

require_once("$class_path/term_show.class.php"); 
//require_once ("$javascript_path/misc.inc.php");
require_once($base_path."/includes/marc_tables/".$lang."/empty_words");

//Récupération des paramètres du formulaire appellant
$base_query = "history=".rawurlencode(stripslashes($term));

// RSS
require_once($base_path."/includes/includes_rss.inc.php");
$short_header= str_replace("!!liens_rss!!","",$short_header);

echo $short_header;

echo $jscript_term;


function parent_link($categ_id,$categ_see) {
	global $charset;
	global $base_path;
	global $opac_show_empty_categ;
	global $css;
	global $msg;
	
	if ($categ_see) $categ=$categ_see; else $categ=$categ_id;
	$tcateg =  new category($categ);
	if ($opac_show_empty_categ) 
		$visible=true;
	else
		$visible=false;
		
	if ($tcateg->has_notices()) {
		$link="<a href='#' onClick=\"parent.parent.document.term_search_form.action='".$base_path."/index.php?lvl=categ_see&id=$categ&rec_history=1'; parent.parent.document.term_search_form.submit(); return false;\" title='".$msg["categ_see_alt"]."'><img src='./images/search.gif' border=0 align='absmiddle'></a>";
		$visible=true;	
	}
	$r=array("VISIBLE"=>$visible,"LINK"=>$link);
	return $r;
}

if ($term!="") {
	$ts=new term_show(stripslashes($term),"term_show.php",$base_query,"parent_link", 0, $id_thes);
	echo $ts->show_notice();
	echo "<script>
	parent.parent.document.term_search_form.term_click.value='".$term."';
	</script>
	";
}

print $short_footer;

?>
