<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: launch_search.inc.php,v 1.5 2008-10-02 12:01:25 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if ($_SESSION["ext_type"]=="simple") {
	//Deblocage des sources si demande
	for ($i=0; $i<count($source); $i++) {
		$debloque="debloque_source_".$source[$i];
		if ($$debloque) mysql_query("delete from source_sync where source_id=".$source[$i]);
	}
	
	//Recherche du champ source, s'il n'est pas present, on decale tout et on l'ajoute
	$flag_found=false;
	for ($i=0; $i<count($search); $i++) {
		if ($search[$i]=="s_2") { $flag_found=true; break; }
	}
	if (!$flag_found) {
		//Pas trouve, on verifie qu'il y a au moins une source
		if (!count($source)) {
			print "<script>alert(\"".$msg["connecteurs_no_source"]."\"); history.go(-1);</script>";
			exit();
		}
		//Pas trouve, on décale tout !!
		for ($i=count($search)-1; $i>=0; $i--) {
			$search[$i+1]=$search[$i];
			decale("field_".$i."_".$search[$i],"field_".($i+1)."_".$search[$i]);
			decale("op_".$i."_".$search[$i],"op_".($i+1)."_".$search[$i]);
			decale("inter_".$i."_".$search[$i],"inter_".($i+1)."_".$search[$i]);
			decale("fieldvar_".$i."_".$search[$i],"fieldvar_".($i+1)."_".$search[$i]);
		}
		
		$search[0]="s_2";
		$op_0_s_2="EQ";
		$field_0_s_2=$source;
		$inter="inter_1_".$search[1];
		global $$inter;
		$$inter="and";
	}
}

if (isset($notice_id)) {
	$notice_id_info = "&notice_id=".$notice_id;
} else {
	$notice_id_info = "";
}	
//Effectue la recherche et l'affiche
$sc->show_results_unimarc("./catalog.php?categ=search&mode=7&sub=launch".$notice_id_info,"./catalog.php?categ=search&mode=7".$notice_id_info,true);
?>
