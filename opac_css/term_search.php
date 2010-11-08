<?php
// +-------------------------------------------------
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_search.php,v 1.15 2009-02-11 21:41:55 touraine37 Exp $
//
// Recherche des termes correspondants à la saisie

$base_path=".";                            
$base_auth = ""; 
$base_title="Recherche par termes";

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

require_once ("$class_path/term_search.class.php");

require_once("./includes/marc_tables/".$lang."/empty_words");

// RSS
require_once($base_path."/includes/includes_rss.inc.php");
$short_header= str_replace("!!liens_rss!!",genere_link_rss(),$short_header);

echo $short_header;

//Récupération des paramètres du formulaire appellant
$base_query = "";

//Page en cours d'affichage
$n_per_page=$opac_term_search_n_per_page;

$ts=new term_search("user_input","f_user_input",$n_per_page,$base_query,"term_show.php","term_search.php", 0, $id_thes);
echo "<table width='80%'><tr><td>";
echo $ts->show_list_of_terms();
echo "</td></tr></table>";

echo "<script>
parent.parent.document.term_search_form.page_search.value='".$page."';
</script>
";

print $short_footer;
?>
