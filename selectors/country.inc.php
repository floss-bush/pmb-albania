<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: country.inc.php,v 1.8 2009-05-16 10:52:44 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de sélection d'un pays

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(id_value, libelle_value)
{
	window.opener.document.forms['$caller'].elements['$p1'].value = id_value;
	window.opener.document.forms['$caller'].elements['$p2'].value = libelle_value;
	window.close();
}
-->
</script>
";

$sel_header = "
<div class='row'>
	<label for='titre_select_pays' class='etiquette'>$msg[230]</label>
	</div>
<div class='row'>
";

$sel_footer = "
</div>
";

$baseurl = "./select.php?what=country&caller=$caller&p1=$p1&p2=$p2&no_display=$no_display&bt_ajouter=$bt_ajouter";
require_once("$class_path/marc_table.class.php");

$pays = new marc_list('country');

if(!$letter)
	$letter = "a";

print $sel_header;
print $jscript;

// affichage d'un sommaire par lettres
print "<div class='row'>";
for($i = 65; $i <= 90; $i++) {
	$char = chr($i);
	$present = preg_grep("/^$char/i", $s_func->table);
	if(sizeof($present) && strcasecmp($letter, $char))
		print "<a href='$baseurl&letter=$char'>$char</a> ";
	else if(!strcasecmp($letter, $char))
		print "<font size='+1'><strong><u>$char</u></strong></font> ";
}

print "</div><hr />";

foreach($pays->table as $index=>$value) {
	if(preg_match("/^$letter/i", $value)) {
		$display[] = "	
				<div class='row'>
						<div class='colonne2' style='width: 80%;'>
							<a href='#' onClick=\"top.set_parent('$index', '".htmlentities(addslashes($value),ENT_QUOTES,  $charset)."')\">$value</a>
							</div>
						<div class='colonne2'  style='width: 20%;'>
							$index
							</div>
						</div>
				";
	}
}

print "<div class='row'>";
foreach($display as $dummykey=>$link)
	print $link;
print "</div>";

print $sel_footer;
