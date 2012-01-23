<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// |                                                                          |
// | Ces scripts sont bas�s sur le travail de Quentin CHEVILLON               |
// +-------------------------------------------------+
// $Id: z_progression_main.php,v 1.13 2008-12-11 09:50:10 kantin Exp $

// d�finition du minimum n�c�ssaire 
$base_path="../..";
$base_auth = "CATALOGAGE_AUTH";  
$base_title = "";    
$base_nobody = 1;    
require_once ("$base_path/includes/init.inc.php");  

// les requis par z_progression_main.php ou ses sous modules
require_once ("$include_path/isbn.inc.php");
require_once ("$include_path/marc_tables/$lang/empty_words");
require_once ("$class_path/iso2709.class.php");
require_once ("z3950_func.inc.php");


//
// On d�termine les Biblioth�ques s�lectionn�es
//
if ( ($clause=="")) {
	echo $msg[z3950_no_bib_selectetd];
	echo "<a href=\"#\" onclick='history.go(-1); return false;'>$msg[z3950_autre_rech]</a>&nbsp;";
	die();
	}

$selection_bib="where bib_id in (".$clause.") ";
//
// On r�cup�re ID_query et on met � jour la base pour une prochaine recherche
//

$sql = "insert into z_query (zquery_id, search_attr) values (0,'crit1=$crit1&val1=$val1&bool1=$bool1&crit2=$crit2&val2=$val2')";
mysql_query($sql);
$last_id_query = mysql_insert_id();

// DEBUG NOTE: expand size frame3 to 50% to show messages from z_progression_cache and children  

print "
<frameset rows=\"*,20%,50%\"  frameborder=\"NO\">

<frame name='droite' src='z_progression_visible.php?last_query_id=$last_id_query&tri1=$tri1&tri2=$tri2&selection_bib=$selection_bib&clause=$clause' frameborder=\"NO\">

<frame name='droite_but' src='z_progression_visible2.php?last_query_id=$last_id_query&tri1=$tri1&tri2=$tri2&selection_bib=$selection_bib&clause=$clause&id_notice=$id_notice' frameborder=\"NO\">

<frame name=\"zframe1\" src=\"z_progression_cache.php?last_query_id=$last_id_query&selection_bib=$selection_bib&clause=$clause&crit1=$crit1&val1=$val1&bool1=$bool1&crit2=$crit2&val2=$val2&limite=$limite\" frameborder=\"YES\">

</frameset><noframes><body bgcolor=\"#FFFFFF\"></body></noframes></html>" ;

?>