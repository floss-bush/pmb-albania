<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: quota_test.inc.php,v 1.9 2009-05-16 11:11:52 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$struct=array("READER"=>"7","BULL"=>"1"); 
echo "Quota=<pre>";
print_r($qt->get_quota_value_with_id($struct));
echo "</pre>";
$ok=$qt->check_quota($struct);
if ($ok==-1) echo $qt->error_message."<br />Force = ".$qt->force;
?>