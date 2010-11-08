<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.tpl.php,v 1.5 2007-03-14 16:51:33 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// template pour la page d'accueil
// $main : contenu de la page d'accueil

// $main_layout : layout page main
$main_layout = "
	<div id='conteneur'>
		<div id='contenu'>
	";

//	----------------------------------
// $main_layout_end : layout page main (fin)
$main_layout_end = "
	</div>
	</div>
	";

