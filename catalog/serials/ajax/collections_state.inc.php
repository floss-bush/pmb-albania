<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collections_state.inc.php,v 1.8 2009-12-21 11:39:05 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function ajax_calculate_collections_state() {
	global $msg,$id_location,$id_serial;
	
	$rqt="select bulletin_id,bulletin_numero,mention_date from bulletins where bulletin_notice=$id_serial order by date_date";
	
	$execute_query=mysql_query($rqt);
	$compt=mysql_num_rows($execute_query);
	$temp="";
	$i=0;
	$debut="";
	$t=array();
	
	//est-ce que l'état des collections est localisé
	if ($id_location){
		$restrict_location=" and expl_location=$id_location";
	}else{
		$restrict_location="";
	}
	
	//parcours des bulletins de la notice de périodique
	while ($r=mysql_fetch_object($execute_query)) {
		
		$rqt1="select expl_id from exemplaires where expl_bulletin=".$r->bulletin_id.$restrict_location;
		$compt1=mysql_num_rows(mysql_query($rqt1));
		$temp=mysql_error();
		//remplissage d'un tableau avec des trous si le bulletin n'a aucun exemplaire associé
		if ($compt1==0) {
			$t[]="";
		} else {
			$item=$r->bulletin_numero;
			if ($r->mention_date) $item.=" (".$r->mention_date.")";
			$t[]=$item;
			//détermination du premier bulletin de la liste qui a des exemplaires associés 
			if ($debut === "") $debut=count($t)-1;
			//comptage des bulletins avec des exemplaires associés
			$i++;
		}	
	}
	//si tous les bulletins ont des exemplaires associés, on prend l'intégralité de la liste
	if ($i==$compt) {
		$all="";
		$all.=$t[$debut];
		$all.=" - ";
		$j=count($t)-1;
		$all.=$t[$j];
		$temp=$all;
	} else {
		$tableau_final=array();
		//parcours du tableau final
		for ($j=0;$j<count($t);$j++) {
			//si l'élément n'est pas un trou
			if ($t[$j]!="") {
				$temp1=$t[$j];
				$bool=false;
				//parcours du tableau à partir de l'élément jusqu'au premier trou existant
				for ($x=$j;$x<count($t);$x++) {
					if ($t[$x]=="") {
						if ($t[$x-1]!=$t[$j]) $temp1.=" - ".$t[$x-1];
						$j=$x;
						$x=count($t);
						$bool=true;					
					}	
				}
				//si aucun trou jusqu'à la fin n'est trouvé, on finit la borne par le dernier
				//numéro et on quitte la boucle de parcours
				if ($bool==false) {
					$temp1.=" - ".$t[count($t)-1];
					$j=count($t);
				}
				//on remplit un tableau avec les intervalles trouvés
				$tableau_final[]=$temp1;
			} else {
				//on remplit un tableau avec l'élément trouvé
				if ($t[$j-1]!="") $tableau_final[]=$t[$j-1];
			}
		}
		$temp=implode(";",$tableau_final);
	}
	ajax_http_send_response($temp,"text/text");
	
	return;
}

function ajax_modify_collections_state() {
	global $id_serial,$id_location,$texte_coll_state,$charset;
	if ($id_location) $restrict_location=" and location_id=$id_location";
	$rqt1="select state_collections from collections_state where id_serial=$id_serial $restrict_location";
	$execute_query1=mysql_query($rqt1);
	if (mysql_num_rows($execute_query1)) $rqt2="update collections_state set state_collections='".$texte_coll_state."' where id_serial=$id_serial $restrict_location";	
		else $rqt2="insert into collections_state (id_serial,location_id,state_collections) values ('$id_serial','$id_location','".$texte_coll_state."')";
	@mysql_query($rqt2);
	if (mysql_error()) $texte_coll_state=mysql_error();
	
	ajax_http_send_response($texte_coll_state,"text/text");
	return;
}

switch ($fname) {
	case "calculate_collections_state":
		ajax_calculate_collections_state();
		break;
	case "modify_collections_state":
		ajax_modify_collections_state();
		break;
	default:
		ajax_http_send_error("404 Not Found","Invalid command : ".$fname);
		break;
}

?>
