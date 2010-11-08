<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: alter.inc.php,v 1.11 2009-05-16 11:11:52 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$admin_layout = str_replace('!!menu_sous_rub!!', $msg[1800], $admin_layout);
print $admin_layout;
print "
<div class='row'>".
	$msg[alter_version_pmb]." ".$pmb_version_brut;

if ($pmb_verif_on_line) {
	$fp=@fopen($pmb_version_web, "rb");
	if ($fp) { 
		$buffer = fgets($fp, 4096);
		fclose($fp) ;
		if ($buffer!=$pmb_version_brut) {
			$mess_version_web = str_replace("!!version_web!!", $buffer, $msg[alter_version_pmb_dispo]) ;
			print " <br /><label class='etiquette'>$mess_version_web</label>";
			}
		}
	}

print "
	</div>
<div class='row'>
	<iframe name='alter' frameborder='0' scrolling='yes' width='800' height='600' src='./admin/misc/alter.php'>
	</div>
<noframes></noframes>" ;
