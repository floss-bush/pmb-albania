<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: valid_change_password.inc.php,v 1.12 2009-05-16 10:52:56 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if (!$allow_pwd) die();
print "
<h3>".$msg["empr_modify_password"]."</h3>\n";
// contrôle de l'ancien mot de passe ok 
if ($new_password==$confirm_new_password) {
	$update_query = "UPDATE empr SET empr_password='$new_password' WHERE empr_cb='$empr_cb'";
	$update_result = mysql_query($update_query) or die("Req failed");
	// contrôle du nouveau mot de passe par double ok
	// donc tout baigne, on lance la màj
	print $msg["empr_password_changed"]."<br /><br />";
} else {
	// contrôle du nouveau mot de passe par double non validé
	print $msg["empr_password_does_not_match"]."<br /><br />";
	}
