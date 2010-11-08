<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: history_functions.inc.php,v 1.1 2007-07-31 13:26:41 jlesaint Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// ----------------------------------------------------------------------------
//	fonctions de suppression en cascade de l'historique de recherches
// ----------------------------------------------------------------------------


function suppr_histo($id_suppr,$tableau_suppr) {
	if (!$tableau_suppr[$id_suppr]) {
		for ($i=0;$i<=count($_SESSION["session_history"])-1;$i++) {
			if ($_SESSION["session_history"][$i]["NOTI"]["SEARCH_TYPE"]=="extended") {
				$bool=false;
				for ($j=0;$j<=count($_SESSION["session_history"][$i]["NOTI"]["POST"]["search"])-1;$j++) {
					if ($_SESSION["session_history"][$i]["NOTI"]["POST"]["search"][$j]=="s_1") {
    					if ($_SESSION["session_history"][$i]["NOTI"]["POST"]["field_".$j."_".$_SESSION["session_history"][$i]["NOTI"]["POST"]["search"][$j]][0]==$id_suppr) {
							$tableau_suppr[$i]=0;
							suppr_histo($i,$tableau_suppr);
							$bool=true;
						}		
					}	
				}
				if ($bool==false) {
					$tableau_suppr[$i]=1;
				}		
			} else {
				$tableau_suppr[$i]=1;
			} 	
		}
		$tableau_suppr[$id_suppr]=0;
	} else {
		$tableau_suppr[$id_suppr]=0;
	} 
	return $tableau_suppr;			
}

function reorg_tableau_suppr($tableau_suppr) {
	$t=array();
	$j=0;
	for ($i=0;$i<=count($tableau_suppr)-1;$i++) {
		if ($tableau_suppr[$i]!=0) {
			$t[$i]=$j;
			$j++;	
		} else {
			$_SESSION["session_history"][$i]=array();
			unset ($_SESSION["session_history"][$i]);
		} 	
	}
	return $t;	
}
?>
