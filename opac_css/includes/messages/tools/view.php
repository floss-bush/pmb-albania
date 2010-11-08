<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
error_reporting (E_ERROR | E_PARSE | E_WARNING);

?>
<html>
<head>
<META http-equiv="Content-Type" Content="text/html; charset=utf-8">
</head>
<body>
<?php

print "<h2>PMB Translation Viewer</h2><hr />";
$charset="UTF-8";

require_once('../../../classes/XMLlist.class.php');

// on d�finit les langues existantes

$languages = new XMLlist("../languages.xml", 0);
$languages->analyser();
$avail_lang = $languages->table;

print "<table border='1'>";
$entete_colonne="<tr><th bgcolor=#00AA00>&nbsp;</th>";
$nb_lang = 0;
$messages_list=array();
while(list($cle, $valeur) = each($avail_lang)) {
	$entete_colonne .= "<th>$valeur</th>";
	$obj_lang = new XMLlist("../$cle.xml", 0);
	$obj_lang->analyser();
	$lang = $obj_lang->table;
	while (list($key,$val) = each($lang)) {
		$messages_list[$key][$nb_lang]=$val;
	}
	$nb_lang++;
}

$entete_colonne .= "</tr>\n";

echo $entete_colonne;
while (list($cle,$valeur)=each($messages_list)) {
	echo "<tr>";
	echo "<td>".$cle."</td>";
	for ($i=0; $i<$nb_lang; $i++) {
		$valeur1=$valeur;
		$valeur1[$i]="";
		$as=array_search($valeur[$i],$valeur1);
		if (($as!==null)&&($as!==false)) $color="#dddddd"; else $color="#ffffff";
		if ($valeur[$i]!="") echo "<td bgcolor=$color>".$valeur[$i]."</td>"; 
		else {
			echo "<td bgcolor=#ff0000>&nbsp;</td>";
			$err = true ;
			}
	}
	echo "</tr>";
	if ($err) {
		echo $entete_colonne ;
		$err = false ;
	}
}

print "</table>";
?>
</body>