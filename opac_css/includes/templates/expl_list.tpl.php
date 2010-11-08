<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl_list.tpl.php,v 1.13 2009-03-13 08:40:01 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// template for PMB OPAC

$expl_list_header = "
<h3><span id='titre_exemplaires'>".$msg["exemplaries"]."</span></h3>
<table cellpadding='2' class='exemplaires' width='100%'>
";

$expl_list_footer ="
</table>";

$expl_list_header_loc_tpl.="
<h3><span id='titre_exemplaires'>".$msg["exemplaries"]."</span></h3>
<ul id='onglets_isbd_public'>  
  	<li id='onglet_expl_loc!!id!!' class='isbd_public_active'><a href='#' onclick=\"show_what('EXPL_LOC', '!!id!!'); return false;\">!!mylocation!!</a></li>
	<li id='onglet_expl!!id!!' class='isbd_public_inactive'><a href='#' onclick=\"show_what('EXPL', '!!id!!'); return false;\">".$msg['onglet_expl_alllocation']."</a></li>
</ul>
<div id='div_expl_loc!!id!!' style='display:block;'><table cellpadding='2' class='exemplaires' width='100%'>!!EXPL_LOC!!</table></div>
<div id='div_expl!!id!!' style='display:none;'><table cellpadding='2' class='exemplaires' width='100%'>!!EXPL!!</table></div>
";
