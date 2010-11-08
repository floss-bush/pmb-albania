<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_browse.php,v 1.17 2009-02-11 21:41:55 touraine37 Exp $
//
// Frames pour naviguer par terme

$base_path=".";                            
$base_auth = ""; 
require_once ($base_path.'/includes/init.inc.php');  
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

require_once($base_path."/includes/marc_tables/".$lang."/empty_words");


?>
<frameset rows="<?php echo $opac_term_search_height_bottom;?>,*">
	<frame name="term_search" src="term_search.php?user_input=<?php echo rawurlencode(stripslashes($search_term)); ?>&f_user_input=<?php echo rawurlencode(stripslashes($search_term)); if ($page_search) echo "&page=$page_search"; echo '&id_thes='.$id_thes; ?>">
	<frame name="term_show" src="<?php echo "term_show.php?term=".rawurlencode(stripslashes($term_click)); echo '&id_thes='.$id_thes;?>">
</frameset>
