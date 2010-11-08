<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: analysis_main.inc.php,v 1.8 2008-10-02 12:03:34 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function abort() {
	echo "<script type=\"text/javascript\">
		alert(\"PMB navigation error. Please contact devel team...\");
		document.location=\"./catalog.php?categ=serials\";
		</script>";
}

switch($action) {
	case 'analysis_form':
		include('./catalog/serials/analysis/analysis_form.inc.php');
		break;
	case 'update':
		include('./catalog/serials/analysis/analysis_update.inc.php');
		break;
	case 'delete':
		include('./catalog/serials/analysis/analysis_delete.inc.php');
		break;
	case 'explnum_delete':
		include('./catalog/serials/analysis/ana_explnum_delete.inc.php');
		break;
	case 'explnum_update':
		include('./catalog/serials/analysis/ana_explnum_update.inc.php');
		break;	
	case 'explnum_form':
		include('./catalog/serials/analysis/ana_explnum_form.inc.php');
		break;
	default:
		abort();
		break;
}
?>

