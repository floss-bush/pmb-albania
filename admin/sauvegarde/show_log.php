<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: show_log.php,v 1.5 2009-05-16 11:11:53 dbellamy Exp $

$base_path="../..";
$base_auth="ADMINISTRATION_AUTH";
$base_title="Logs";
require($base_path."/includes/init.inc.php");

$requete="select sauv_log_file, sauv_log_messages from sauv_log where sauv_log_id=$logid";
$resultat=mysql_query($requete) or die(mysql_error());
$log_file=mysql_result($resultat,0,0);
$log_messages=mysql_result($resultat,0,1);

print "<div id=\"contenu-frame\">\n";
echo "<center><h1>".sprintf($msg["sauv_misc_logs"],$log_file)."</h1></center><br /><br />";
echo nl2br($log_messages);

echo "</div>";
?>