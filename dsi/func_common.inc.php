<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_common.inc.php,v 1.5 2007-03-10 09:25:49 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// récupération des classements.
function show_classement_utilise ($type='BAN', $classement_objet=1, $utilise=1) {
	global $dbh ;
	global $msg, $charset ;
	
	$query = "SELECT nom_classement FROM classements where id_classement=1 ";
	$result = mysql_query($query, $dbh);
	$temp = mysql_fetch_object($result) ;
	$classements = "<select id='num_classement' name='num_classement'>";

	$classements .= "<option value='1'";
	if ($classement_objet==1) $classements.=" selected";
	$classements .= ">".htmlentities($temp->nom_classement,ENT_QUOTES, $charset)."</option>\n";
	
	if ($utilise) {
		$compte = "num_classement" ; 
		if ($type=="BAN") {
			$from = " classements, bannettes " ;
			$clause = " where id_classement=num_classement and type_classement='BAN' " ; 
			} else {
				$from = " classements, equations " ;
				$clause = " where id_classement=num_classement and type_classement='EQU' " ; 
				}
		} else {
			$compte = "id_classement" ;
			if ($type=="BAN") {
				$from = " classements " ;
				$clause = " where type_classement='BAN' " ; 
				} else {
					$from = " classements " ;
					$clause = " where type_classement='EQU' " ; 
					}
			}


	$query = "SELECT count($compte) as util, id_classement, nom_classement from $from $clause group by id_classement, nom_classement order by nom_classement, id_classement ";
	$result = mysql_query($query, $dbh);
	while ($temp = @mysql_fetch_object($result)) {
		$classements .= "<option value='$temp->id_classement'";
		if ($classement_objet==$temp->id_classement) $classements .= " selected ";
		$classements .=">".htmlentities($temp->nom_classement,ENT_QUOTES, $charset);
		if ($utilise) $classements .=" ($temp->util)";
		$classements .="</OPTION>\n";
		}
	$classements .= "</select>" ;
	return $classements ; 
	}

function gen_liste_classement($type_classement="BAN", $id_classement=0, $onchange="") {
	global $msg ;
	return gen_liste ("select id_classement, nom_classement from classements where id_classement=1 UNION select id_classement, nom_classement from classements where type_classement='$type_classement' order by nom_classement", "id_classement", "nom_classement", "id_classement", $onchange, $id_classement, "", "",0,$msg['dsi_all_classements'],0) ;
	}