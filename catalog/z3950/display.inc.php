<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: display.inc.php,v 1.10 2009-05-16 11:12:03 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// les requis par display.inc.php
include("$include_path/marc_tables/$lang/empty_words");
include("$class_path/iso2709.class.php");

$rqsql="select * from z_notices, z_bib where znotices_query_id ='$last_query_id' and bib_id=znotices_bib_id order by $tri1, $tri2";
$resultat3=mysql_query($rqsql);
$test_resultat=0;
$retour_affichage="";
$i=0;
while ($ligne3=mysql_fetch_array($resultat3)) {
	$znotices_id=$ligne3["znotices_id"];
	$resultat_titre=$ligne3["titre"];
	$resultat_auteur=$ligne3["auteur"];
	$resultat_isbd=$ligne3["isbd"];
	if (isISBN($ligne3["isbn"])) $resultat_isbn=formatISBN($ligne3["isbn"]);
		else $resultat_isbn=$ligne3["isbn"];
	$resultat_bib_name=$ligne3["bib_nom"];
	$resultat_bib_format=$ligne3["format"];
	$test_resultat++;
	$lien = "<a ";
	if ($i==0) $lien .= " id='premierresultat' " ;
	$i++;
	$lien .= " href='./catalog.php?categ=z3950&action=import&id_notice=$id_notice&znotices_id=".$znotices_id.
		"&last_query_id=".$last_query_id.
		"&tri1=".$tri1.
		"&tri2=".$tri2."' >".$resultat_titre." / ".$resultat_auteur."</a>";
	$retour_affichage.=zshow_isbd($resultat_isbd, $lien);
	$retour_affichage.="<small><strong>( $resultat_bib_name / $resultat_bib_format )<br /></strong></small><br />";
	}

$opt_tri[0][0] = "auteur";   $opt_tri[0][1] = $msg[z3950_auteur];
$opt_tri[1][0] = "isbn";     $opt_tri[1][1] = $msg[z3950_isbn];
$opt_tri[2][0] = "bib_nom";  $opt_tri[2][1] = $msg[z3950_serveur];
$opt_tri[3][0] = "titre";    $opt_tri[3][1] = $msg[z3950_titre];

print "<h1>$msg[z3950_result_rech]</h1>";

$msg[z3950_nb_result] = str_replace('!!test_resultat!!', $test_resultat, $msg[z3950_nb_result]);
print "<b><i>$msg[z3950_nb_result]</i></b>&nbsp;&nbsp;&nbsp;&nbsp;";

print "<a href=\"javascript:top.document.location='./catalog.php?categ=z3950&id_notice=$id_notice'\">$msg[z3950_autre_rech]</a>";
print "<br /><br /><form class='form-$current_module' method='post' action='./catalog.php?categ=z3950&action=display&id_notice=$id_notice' name='affiche' target='_top'>$msg[z3950_tri1]:<select name='tri1'>" ;
for ($i = 0; $i < 4 ; $i++) {
	if ($tri1 == $opt_tri[$i][0]) echo "<option value=".$opt_tri[$i][0]." selected>".$opt_tri[$i][1]."</option>";
		else echo "<option value=".$opt_tri[$i][0].">".$opt_tri[$i][1]."</option>";
	}         
print "</select>&nbsp;$msg[z3950_tri2]:<select name='tri2'>";
for ($i = 0; $i < 4 ; $i++) {
	if ($tri2 == $opt_tri[$i][0]) echo "<option value=".$opt_tri[$i][0]." selected>".$opt_tri[$i][1]."</option>";
		else echo "<option value=".$opt_tri[$i][0].">".$opt_tri[$i][1]."</option>";
	}
	
print "</select><input type=\"hidden\" name=\"last_query_id\" value=\"$last_query_id\">\n";
print "&nbsp;<input type='submit' name='submit' class='bouton' value='$msg[z3950_trier]'>";
print "</form>";
print "<br />$retour_affichage";
if ($test_resultat==0) print "<p align='center'>$msg[z3950_no_result_rech]</p>\n" ;
	else print "<script type='text/javascript'>document.getElementById('premierresultat').focus();</script>" ;

