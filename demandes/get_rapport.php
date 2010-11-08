<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Yves PRATTER                                                   |
// +-------------------------------------------------+
// $Id: get_rapport.php,v 1.2 2009-10-06 13:52:03 kantin Exp $

$base_path="./..";                            
$base_auth = "DEMANDES_AUTH";  
$base_title = "\$msg[demandes_menu_title]";
$base_noheader = 1;
$base_nobody   = 1;   
require_once ("$base_path/includes/init.inc.php"); 

//require_once($class_path."/demandes.class.php");
require_once("$base_path/classes/rapport.class.php");
require_once("./export_format/report_to_rtf.class.php");

$rap = new rapport_demandes($iddemande);

$rap->generer_intro();
$rap->create_rapport();
$rtf = new $act($rap->rapport_xml);


?>