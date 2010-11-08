<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mail.php,v 1.7 2007-05-24 10:49:00 jlesaint Exp $

// définition du minimum nécéssaire 
$base_path=".";                            
$base_auth = "";  
$base_title = "Mail";

require_once ("$base_path/includes/init.inc.php");  

switch($type_mail) {
	case 'mail_relance_adhesion':
		if(checkUser('PhpMyBibli', EDIT_AUTH)) include("./edit/mail-relance-adhesion.inc.php");
		break;
	case 'mail_retard':
		if(checkUser('PhpMyBibli', EDIT_AUTH)) include("./edit/mail-retard.inc.php");
		break;
	case 'mail_prets':
		if(checkUser('PhpMyBibli', EDIT_AUTH)) include("./edit/mail-prets.inc.php");
		break;
	case 'mail_retard_groupe':
		if(checkUser('PhpMyBibli', EDIT_AUTH)) include("./edit/mail_retard_groupe.inc.php");
		break;
	default:
		break;
}

mysql_close($dbh);
