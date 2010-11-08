<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_achats.inc.php,v 1.17 2009-02-05 13:21:40 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Recuperation du statut session d'affichage des devis
function getSessionDevState() {
	global $deflt3dev_statut;
	if (!$_SESSION['dev_statut'] && $deflt3dev_statut) {
		$_SESSION['dev_statut']=$deflt3dev_statut;
	}
	return $_SESSION['dev_statut'];
}
//Definition du statut session d'affichage des devis
function setSessionDevState($statut) {
	$_SESSION['dev_statut']=$statut;
	return;
}

//Recuperation du statut session d'affichage des bons de commande
function getSessionCdeState() {
	global $deflt3cde_statut;
	if (!$_SESSION['cde_statut'] && $deflt3cde_statut) {
		$_SESSION['cde_statut']=$deflt3cde_statut;
	}
	return $_SESSION['cde_statut'];
}
//Definition du statut session d'affichage des bons de commande
function setSessionCdeState($statut) {
	$_SESSION['cde_statut']=$statut;
	return;
}


//Recuperation du statut session d'affichage des bons de livraison
function getSessionLivState() {
	global $deflt3liv_statut;
	if (!$_SESSION['liv_statut'] && $deflt3liv_statut) {
		$_SESSION['liv_statut']=$deflt3liv_statut;
	}
	return $_SESSION['liv_statut'];
}
//Definition du statut session d'affichage des bons de livraison
function setSessionLivState($statut) {
	$_SESSION['liv_statut']=$statut;
	return;
}


//Recuperation du statut session d'affichage des factures
function getSessionFacState() {
	global $deflt3fac_statut;
	if (!$_SESSION['fac_statut'] && $deflt3fac_statut) {
		$_SESSION['fac_statut']=$deflt3fac_statut;
	}
	return $_SESSION['fac_statut'];
}
//Definition du statut session d'affichage des bons de livraison
function setSessionFacState($statut) {
	$_SESSION['fac_statut']=$statut;
	return;
}


if ($acquisition_custom_calc_numero) {
	require_once($acquisition_custom_calc_numero);
} else {
	
	//Calcul du numero d'acte
	function calcNumero($id_entite, $type_acte) {
		
		global $dbh;
		global $acquisition_format;
		
		$p = array();
		$p = explode(",",$acquisition_format);
		$prefix = $p[$type_acte+1];
		
		//recuperation du dernier numero pour le type d'acte concerné et l'entité en cours
		$q = "select max(substring(numero,".(strlen($prefix)+1).")*1) from actes where type_acte = '".$type_acte."' ";
		$q.= "and num_entite = '".$id_entite."' ";
		$r = mysql_query($q, $dbh); 
	
		$res = mysql_result($r,0,0);
		if (!$res) $res = '0';
		
		//creation du numéro avec prefixe et padding
		$res++; 
		$numero = $prefix;
		if ($p[0] != 0 ) {
			$numero = str_pad($numero, $p[0]-strlen($res)+strlen($prefix),'0').$res;
		} else {
			$numero = $numero.$res;
		}
		return $numero;
	}
}


//Calcule les montants ht, ttc et tva
//a partir d'un tableau
//[index]['q']=qte
//[index]['p']=prix
//[index]['r']=remise %
//[index]['t']=tva % 
//
//et retourne un tableau
//['ht']=montant ht
//['ttc']=montant ttc
//['tva']=montant tva
//
//precision = nb decimales
function calc($tab, $precision=0) {
	
	global $acquisition_gestion_tva;
	
	$mnt_ht=0;
	$mnt_tva=0;
	$mnt_ttc=0;
	
	foreach($tab as $v) {
		switch($acquisition_gestion_tva) {
			case '1' :	//saisie des prix ht
				$mnt_ht=$mnt_ht+($v['q']*$v['p']*((100-$v['r'])/100));
				$mnt_tva=$mnt_tva+($v['q']*$v['p']*((100-$v['r'])/100)*($v['t']/100));
				break;
			case '2' :	//saisie des prix ttc
				$mnt_ttc=$mnt_ttc+($v['q']*$v['p']*((100-$v['r'])/100));
				$mnt_ht=$mnt_ht+(($v['q']*$v['p']*((100-$v['r'])/100))/(1+($v['t']/100))) ;
				break;
			default:	//pas de gestion de tva
				$mnt_ttc=$mnt_ttc+($v['q']*$v['p']*((100-$v['r'])/100));
				break;
		}
	}
	
	switch($acquisition_gestion_tva) {
		case '1' :
			$tot_ht=$mnt_ht;
			$tot_tva=$mnt_tva;
			$tot_ttc=($mnt_ht+$mnt_tva);
			break;
		case '2' :
			$tot_ht=$mnt_ht;
			$tot_tva=($mnt_ttc-$mnt_ht);
			$tot_ttc=$mnt_ttc;
			break;
		default :
			$tot_ttc=$mnt_ttc;
			break;
	}	
	if ($precision) {
		$tot['ttc']=round($tot_ttc,$precision);
		$tot['ht']=round($tot_ht,$precision);
		$tot['tva']=round($tot_tva,$precision);
	} else {
		$tot['ttc']=$tot_ttc;
		$tot['ht']=$tot_ht;
		$tot['tva']=$tot_tva;
	}
	return $tot;
}
?>