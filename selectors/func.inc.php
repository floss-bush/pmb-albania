<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func.inc.php,v 1.17 2009-05-16 10:52:45 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de sélection fonction responsable

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
if ($dyn!=1) {
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(id_value, libelle_value)
{
	window.opener.document.forms['$caller'].elements['$p1'].value = id_value;
	window.opener.document.forms['$caller'].elements['$p2'].value = reverse_html_entities(libelle_value);
	window.close();
}
-->
</script>
";

$sel_header = "
<div class='row'>
	<label for='titre_select_func' class='etiquette'>$msg[273]</label>
	</div>
<div class='row'>
";
} else {
	$jscript = "
<script type='text/javascript'>
<!--
function set_parent(id_value, libelle_value)
{
	window.opener.document.getElementById('$param1').value = id_value;
	window.opener.document.getElementById('$param2').value = reverse_html_entities(libelle_value);
	window.close();
}
-->
</script>";
}

//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";

if ($dyn!=1) {
	$baseurl = "./select.php?what=function&caller=$caller&p1=$p1&p2=$p2&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn";
} else {
	$baseurl = "./select.php?what=function&caller=$caller&param1=$param1&param2=$param2&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn";
}

require_once("$class_path/marc_table.class.php");

// récupération des codes de fonction
if (!count($s_func )) {
	$s_func = new marc_list('function');
	}

print $sel_header;
print $jscript;

$afunc=$s_func->table;

$special = false;
$favorite = false;
asort($afunc);
foreach($afunc as $key => $val) {
	if ($key>=900) $special=true;
	else $alphabet[] = pmb_substr($val,0,1);
	if ($s_func->tablefav[$key]) $favorite=true;
}
$alphabet = array_unique($alphabet);
if(!$letter)
	if ($favorite)
		$letter = "Fav";
	elseif ($special) 
		$letter="My";
	else
		$letter = "a";
// affichage d'un sommaire par lettres
print "<div class='row'>";
if ($favorite) {
	if ($letter!='Fav')
		print "<a href='$baseurl&letter=Fav'>".$msg['favoris']."</a> ";
	else
		print "<font size='+1'><strong><u>".$msg['favoris']."</u></strong></font> ";
}
if ($special) {
	if ($letter!='My')
		print "<a href='$baseurl&letter=My'>#</a> ";
	else
		print "<font size='+1'><strong><u>#</u></strong></font> ";
}

foreach($alphabet as $dummykey=>$char) {
	//$char = chr($i);
	$present = pmb_preg_grep("/^$char/i", $s_func->table);
	if(sizeof($present) && strcasecmp($letter, $char))
		print "<a href='$baseurl&letter=$char'>$char</a> ";
	else if(!strcasecmp($letter, $char))
		print "<font size='+1'><strong><u>$char</u></strong></font> ";
}

print "</div><hr />";
$display= "";
foreach($s_func->table as $index=>$value ) {
	if((preg_match("/^$letter/i", $value))||(($letter=='My')&&($index>=900)) ||(($letter=='Fav')&&($s_func->tablefav[$index]))) {
		if ((($letter!='My')&&($index<900))||($letter=='My')||($letter=='Fav')) {
			$display.= "
					<div class='row'>
							<div class='colonne2' style='width: 80%;'>
 								<a href='#' onClick=\"top.set_parent('$index', '".htmlentities(addslashes($value),ENT_QUOTES, $charset)."')\">$value</a>
								</div>
							<div class='colonne2'  style='width: 20%;'>
								$index
								</div>
							</div>
					";
		}
	}
}
print "<div class='row'>";
print $display;
print "</div>";

print $sel_footer;