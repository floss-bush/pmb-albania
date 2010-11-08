<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: last_records.tpl.php,v 1.12 2009-05-16 10:52:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// template for PMB OPAC

$last_records_header = "
		<div id='last_entries'>
		<h3><span>$msg[last_entries]</span></h3>
		<span>$msg[last_records_intro]<br />";
$last_records_footer ="			
</span>
		</div>";
