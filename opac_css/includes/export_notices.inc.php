<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export_notices.inc.php,v 1.5 2009-12-09 14:30:30 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Exécution de l'export
require_once("$base_path/admin/convert/start_export.class.php");

function cree_export_notices($liste=array(), $typeexport='pmbxml2marciso', $expl=1) {
	global $base_path;
	global $keep_expl, $dbh;
	$keep_expl = $expl ;
	// Récupération des notices
	$n_notices=count($liste);

	if ($n_notices == 0) {
		return "" ;
	} else {
		$_SESSION["param_export"]["notice_exporte"]=array();
		// Export ! 
		$z = 0;
		$e_notice = "" ;
		while ($z<count($liste)) {
			$id=$liste[$z];			
			// Exclure de l'export (opac, panier) les fiches interdites de diffusion dans administration, Notices > Origines des notices NG72
			$sql="select orinot_diffusion from origine_notice,notices where notice_id = '$id' and origine_catalogage = orinot_id";	
			$res=mysql_query($sql,$dbh);
			$diffusable = mysql_result($res,0,0);
			if($diffusable){
				$export= new start_export($id,$typeexport) ;
				$e_notice.=$export->output_notice;
				$z ++;
			} else $z++;
		}
	}
	return $e_notice ;
}
?>