<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.7 2009-03-13 16:36:16 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//verification des droits de modification notice
$acces_m=1;
if ($notice_id!=0 && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$id,8);
}

if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_noti_error'), ENT_QUOTES, $charset), 1, '');

} else {

	//Enregistrement du type de recherche externe avant toute chose pour que le message soit bon dans le formulaire template
	if ($external_type) $_SESSION["ext_type"]=$external_type;
	if (!$_SESSION["ext_type"]) $_SESSION["ext_type"]="simple";
	
	require_once($class_path."/search.class.php");
	require_once($class_path."/searcher.class.php");
	require_once($class_path."/mono_display_unimarc.class.php");
	require_once($include_path."/external.inc.php");
	require_once($class_path."/z3950_notice.class.php");
	
	//Inclusion des librairies communes
	require_once($base_path."/catalog/notices/search/external/external_common.inc.php");
	
	//Supprimons les doublons dans le tableau des sources
	if ($source)
		$source = array_unique($source);
	
	switch ($sub) {
		case "launch":
			include_once($base_path."/catalog/notices/search/external/launch_search.inc.php");
			break;
		case "integre":
			include_once($base_path."/catalog/notices/search/external/integre.inc.php");
			break;
		default:
			include_once($base_path."/catalog/notices/search/external/show_form.inc.php");
			break;		
	}
}
?>
