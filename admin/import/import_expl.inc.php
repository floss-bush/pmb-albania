<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import_expl.inc.php,v 1.9 2007-03-10 08:32:23 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

print "<iframe name='iimport_expl' frameborder='0' scrolling='yes' width='100%' height='700' src='./admin/import/iimport_expl.php?categ=import&sub=$sub'>
	<noframes>
	</noframes>" ;
