<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cnl.inc.php,v 1.5 2008-10-08 15:20:05 ngantier Exp $

function get_field_dateparution() {
	global $field_dateparution;
	if(!$field_dateparution) {
		$q = "select idchamp from notices_custom where name='dateparution' limit 1 "; 
		$result = mysql_query ($q);
		if (mysql_num_rows($result)) $field_dateparution = mysql_result($result,0,0);
	}
	if(!$field_dateparution) $field_dateparution=0;
	return $field_dateparution;
}

function search_other_function_filters() {
	global $cnl_comission,$cnl_annee,$cnl_mois;
	global $charset,$msg;
	$r="<select name='cnl_comission'>";
	$r.="<option value=''>Toutes les commissions</option>";
	$requete="select * from notices_custom_lists where notices_custom_champ=1 order by notices_custom_list_lib";
	$resultat=mysql_query($requete);
	while (($res=mysql_fetch_object($resultat))) {
		$r.="<option value='".htmlentities($res->notices_custom_list_value,ENT_QUOTES,$charset)."' ";
		if ($res->notices_custom_list_value==$cnl_comission) $r.="selected";
		$r.=">".$res->notices_custom_list_lib;
		$r.="</option>";
	}
	$r.="</select>";
 	
	$r.="<select name='cnl_mois'>";
	$r.="<option value=''>Toutes les mois</option>";
	for($i=1;$i<=12;$i++) {
		$r.="<option value='".sprintf("%02d",$i)."' ";
		if ($i==$cnl_mois) $r.="selected";
		$r.=">".$msg[1005+$i];
		$r.="</option>";
	}
	$r.="</select>";
	
	$r.="<select name='cnl_annee'>";
	$r.="<option value=''>Toutes les années</option>";
	$requete="select distinct DATE_FORMAT(notices_custom_date,'%Y') as annee from notices_custom_values where notices_custom_champ=".get_field_dateparution()." and notices_custom_date!='' order by annee desc";
	$resultat=mysql_query($requete);
	while (($res=mysql_fetch_object($resultat))) {
		if (strlen($res->annee)==4) {
			$r.="<option value='".htmlentities($res->annee,ENT_QUOTES,$charset)."' ";
			if ($res->annee==$cnl_annee) $r.="selected";
			$r.=">".$res->annee;
			$r.="</option>";
		}
	}
	$r.="</select>";
	return $r;
}

function search_other_function_clause(&$clause) {
	global $cnl_comission,$cnl_annee,$cnl_mois;
	if ($cnl_comission) {
		$r=", notices_custom_values as a0 ".$clause." and a0.notices_custom_small_text='".$cnl_comission."' and a0.notices_custom_champ=1 and a0.notices_custom_origine=notice_id";
	} else $r=$clause;

	if ($cnl_annee || $cnl_mois) {
		if ($cnl_annee && $cnl_mois) $filtre_date=$cnl_annee."-".$cnl_mois."%";
		elseif (!$cnl_annee && $cnl_mois) $filtre_date="%-".$cnl_mois."-%";
		else $filtre_date=$cnl_annee."-%";
		$r=", notices_custom_values as a1 ".$r." and a1.notices_custom_date like '".$filtre_date."' and a1.notices_custom_champ=".get_field_dateparution()." ";
		if ($cnl_comission) $r.=" and a0.notices_custom_origine=a1.notices_custom_origine"; else $r.=" and a1.notices_custom_origine=notice_id";
	}
	
	
	if ($r=="") $r=$clause;
	if ($clause==$r) return false; else {
		$clause=$r;
		return true;
	}
}

function search_other_function_has_values() {
	global $cnl_comission,$cnl_annee;
	if (($cnl_comission)||($cnl_annee)) return true; else return false;
}

function search_other_function_rec_history($n) {
	global $cnl_comission,$cnl_annee,$cnl_mois;
	$_SESSION["cnl_comission".$n]=$cnl_comission;
	$_SESSION["cnl_annee".$n]=$cnl_annee;
	$_SESSION["cnl_mois".$n]=$cnl_mois;
}

function search_other_function_get_history($n) {
	global $cnl_comission,$cnl_annee,$cnl_mois;
	$cnl_comission=$_SESSION["cnl_comission".$n];
	$cnl_annee=$_SESSION["cnl_annee".$n];
	$cnl_mois=$_SESSION["cnl_mois".$n];
}

function search_other_function_human_query($n) {
	global $msg;
	$r="";
	$cnl_comission=$_SESSION["cnl_comission".$n];
	$cnl_annee=$_SESSION["cnl_annee".$n];
	$cnl_mois=$_SESSION["cnl_mois".$n];
	if ($cnl_comission) {
		$r="commission : ";
		$requete="select notices_custom_list_lib from notices_custom_lists where notices_custom_champ=1 and notices_custom_list_value='".$cnl_comission."' limit 1";
		$res=mysql_query($requete);
		$r.=@mysql_result($res,0,0);
	}		
	if ($cnl_annee || $cnl_mois) {
		if ($r) $r.=", ";
		if ($cnl_annee && $cnl_mois) $r.=$msg[1005+$cnl_mois]." ".$cnl_annee;
		elseif (!$cnl_annee && $cnl_mois) $r.=$msg[1005+$cnl_mois];
		else $r.="année : ".$cnl_annee;
	}
	return $r;
}
?>