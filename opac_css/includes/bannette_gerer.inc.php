<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bannette_gerer.inc.php,v 1.8 2009-11-10 14:54:42 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if (!$opac_show_categ_bannette && !$opac_allow_bannette_priv) die ("") ; 

// affichage du contenu d'une bannette
require_once($base_path."/includes/bannette_func.inc.php");

// afin de résoudre un pb d'effacement de la variable $id_empr par empr_included, bug à trouver
if (!$id_empr) $id_empr=$_SESSION["id_empr_session"] ;

if ($enregistrer=='PUB') {
	$tableau_bannettes = tableau_gerer_bannette("PUB") ; 
	for ($i=0; $i<sizeof($tableau_bannettes); $i++ ) {
		$id_bannette = $tableau_bannettes[$i]['id_bannette'] ;
		if ($opac_allow_resiliation) mysql_query("delete from bannette_abon where num_empr='$id_empr' and num_bannette='$id_bannette' ") ;
		if ($bannette_abon[$id_bannette]) 
			mysql_query("replace into bannette_abon (num_empr, num_bannette) values('$id_empr', '".$id_bannette."')", $dbh) ;
		}
	}


if ($enregistrer=='PRI') {
	$tableau_bannettes = tableau_gerer_bannette("PRI") ; 
	for ($i=0; $i<sizeof($tableau_bannettes); $i++ ) {
		$id_bannette = $tableau_bannettes[$i]['id_bannette'] ;
		if ($bannette_abon[$id_bannette]) { 
			mysql_query("delete from bannette_abon where num_empr='$id_empr' and num_bannette='$id_bannette' ") ;
			mysql_query("delete from bannette_contenu where num_bannette='$id_bannette' ") ;
			$req_eq = mysql_query("select num_equation from bannette_equation where num_bannette='$id_bannette' ") ;
			$eq = mysql_fetch_object($req_eq) ;
			mysql_query("delete from equations where id_equation='".$eq->num_equation."' ") ;
			mysql_query("delete from bannette_equation where num_bannette='$id_bannette' ") ;
			mysql_query("delete from bannettes where id_bannette='$id_bannette' ") ;
			}
		}
	}


print "<div id='aut_details'>\n";

if ($opac_allow_resiliation) {
	$aff = gerer_abon_bannette ("PUB", "./empr.php?lvl=bannette&id_bannette=!!id_bannette!!" ) ;
	if ($aff) 
		print "<h3><span>".$msg['dsi_bannette_gerer']."</span></h3>\n".$aff;
	else 
		print "<h3><span>".$msg['dsi_bannette_gerer']."</span></h3><br />".$msg['empr_no_alerts'];
	}
	 
if ($opac_allow_bannette_priv) {
	$aff = gerer_abon_bannette ("PRI", "./empr.php?lvl=bannette&id_bannette=!!id_bannette!!" ) ;
	if ($aff)
		 print "<h3><span>".$msg['dsi_bannette_gerer_priv']."</span></h3>\n".$aff ;
	else 
		print "<h3><span>".$msg['dsi_bannette_gerer_priv']."</span></h3><br />".$msg['empr_no_alerts'];
	} 

print "</div><!-- fermeture #aut_details -->\n";	
?>