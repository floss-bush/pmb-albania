<?php

// +-------------------------------------------------+
// | PMB                                                                      |
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cbgen.tpl.php,v 1.5 2009-05-16 11:19:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

global $msg;


//	----------------------------------

// $cb_gen_menu : menu page génération de codes-barres

$cbgen_menu = "
<div><table class=\"menu\" border=\"0\">
	<tr>
		<td>
	<tr>
		<td>
			<a href=\"./cbgen.php\">$msg[804]</a><br />
		</td>
	</tr>
</table></div>
";


//	----------------------------------

// $cbgen_layout : layout page génération de codes-barres

$cbgen_layout = "
<div id='contenu'><table class='document' border='0'>
	<tr>
	<td valign='top'>
	<!-- side-bar -->
		<table border='0'>
			<tr>
				<td class='formtitle'>
					$msg[805]
				</td>
			</tr>
			<tr>
				<td>
					$cbgen_menu
				</td>
			</tr>
		</table>
	</td>
	<td valign='top'>
";

//	----------------------------------

// $cb_layout_end : layout page génération de codes-barres (fin)

$cbgen_layout_end = '
	</td>
	</tr>
</table></div>
';


