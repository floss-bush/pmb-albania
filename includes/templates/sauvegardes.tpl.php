<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sauvegardes.tpl.php,v 1.6 2007-03-14 16:51:33 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$container='
<h1>'.$msg["sauv_sauvegardes_titre"].'</h1>
<table class="nobrd"><tr>
<td valign=top>!!sauvegardes_tree!!</td>
<td>
!!sauvegardes_form!!
</td>
</tr></table>';
?>