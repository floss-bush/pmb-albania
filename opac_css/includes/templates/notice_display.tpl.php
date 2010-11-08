<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_display.tpl.php,v 1.6 2007-03-14 16:58:00 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// template for PMB OPAC

$notice_display_header = "
<div id='notice'><span>
";

$notice_display_footer ="
</span></div>
";
