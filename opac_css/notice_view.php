<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_view.php,v 1.8 2009-11-10 09:36:06 touraine37 Exp $

$base_path=".";
//Affichage d'une notice
require_once($base_path."/includes/init.inc.php");
require_once($base_path."/includes/error_report.inc.php") ;
require_once($base_path."/includes/global_vars.inc.php");
require_once($base_path.'/includes/opac_config.inc.php');

// récupération paramètres MySQL et connection à la base
require_once($base_path.'/includes/opac_db_param.inc.php');
require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

require_once($base_path."/includes/misc.inc.php");

//Sessions !! Attention, ce doit être impérativement le premer include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");
require_once($base_path.'/includes/start.inc.php');

require_once($base_path."/includes/notice_authors.inc.php");
require_once($base_path."/includes/notice_categories.inc.php");

require_once($base_path."/includes/check_session_time.inc.php");

// récupération localisation
require_once($base_path.'/includes/localisation.inc.php');

// version actuelle de l'opac
require_once($base_path.'/includes/opac_version.inc.php');

// fonctions de gestion de formulaire
require_once($base_path.'/includes/javascript/form.inc.php');

require_once($base_path.'/includes/templates/common.tpl.php');
require_once($base_path.'/includes/divers.inc.php');

// classe de gestion des catégories
require_once($base_path.'/classes/categorie.class.php');
require_once($base_path.'/classes/notice.class.php');
require_once($base_path.'/classes/notice_display.class.php');

// classe indexation interne
require_once($base_path.'/classes/indexint.class.php');

// classe d'affichage des tags
require_once($base_path.'/classes/tags.class.php');

// classe de gestion des réservations
require_once($base_path.'/classes/resa.class.php');

// pour l'affichage correct des notices
require_once($base_path."/includes/templates/notice.tpl.php");
require_once($base_path."/includes/navbar.inc.php");
require_once($base_path."/includes/explnum.inc.php");
require_once($base_path."/includes/notice_affichage.inc.php");

// paramétrage de base
$templates = <<<ENDOFFILE
	<html>
		<head>
			!!styles!!
		</head>
		<body>	
			<script type='text/javascript'>
			function show_what(quoi, id) {
				var whichISBD = document.getElementById('div_isbd' + id);
				var whichPUBLIC = document.getElementById('div_public' + id);
				var whichongletISBD = document.getElementById('onglet_isbd' + id);
				var whichongletPUBLIC = document.getElementById('onglet_public' + id);
				if (quoi == 'ISBD') {
					whichISBD.style.display  = 'block';
					whichPUBLIC.style.display = 'none';
					whichongletPUBLIC.className = 'isbd_public_inactive';
					whichongletISBD.className = 'isbd_public_active';
				} else {
					whichISBD.style.display = 'none';
					whichPUBLIC.style.display = 'block';
		  			whichongletPUBLIC.className = 'isbd_public_active';
					whichongletISBD.className = 'isbd_public_inactive';
				}
		  	}		  	
			</script>
			<!--<div id="bouton_fermer_notice_preview" class="right"><a href='#' onClick='parent.kill_frame();return false;'>X</a></div>//-->
			<div id="notice">
				#FILES
			</div>
		</body>
	</html>
ENDOFFILE;
$liens_opac=0;
$opac_notices_depliable=0;

// paramétrages avancés dans fichier si existe
if (file_exists($base_path."/includes/notice_view_param.inc.php")) 
	include($base_path."/includes/notice_view_param.inc.php");

$templates=str_replace("!!styles!!",$stylescsscodehtml,$templates);

$id= $_GET["id"];

//Affichage d'une notice
$notice=aff_notice($id,1);
print str_replace("#FILES",$notice,$templates);

?>