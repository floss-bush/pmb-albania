<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lang.inc.php,v 1.14 2010-01-11 14:50:46 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// page de sélection langue

//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
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
	<label for='titre_select_lang' class='etiquette'>$msg[229]</label>
	</div>
<div class='row'>
";

//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";

$baseurl = "./select.php?what=lang&caller=$caller&p1=$p1&p2=$p2&no_display=$no_display&bt_ajouter=$bt_ajouter";

require_once("$class_path/marc_table.class.php");

// récupération des codes langues
if (!count($s_lang)) {
	$s_lang = new marc_list('lang');
	} 

$alang=$s_lang->table;

foreach($alang as $key => $val) {
	$alphabet[] = strtoupper(convert_diacrit(pmb_substr($val,0,1)));
	if ($s_lang->tablefav[$key]) $favorite=true;
}
$alphabet = array_unique($alphabet);

if(!$letter)
	if ($favorite)
		$letter = "Fav";
	else
		$letter = "a";

print $sel_header;
print $jscript;

// affichage d'un sommaire par lettres
print "<div class='row'>";
if ($favorite) {
	if ($letter!='Fav')
		print "<a href='$baseurl&letter=Fav'>".$msg['favoris']."</a> ";
	else
		print "<font size='+1'><strong><u>".$msg['favoris']."</u></strong></font> ";
}
foreach($alphabet as $dummykey=>$char) {
	$present = pmb_preg_grep("/^$char/i", $s_lang->table);
	if(sizeof($present) && strcasecmp($letter, $char))
		print "<a href='$baseurl&letter=$char'>$char</a> ";
	else if(!strcasecmp($letter, $char))
		print "<font size='+1'><strong><u>$char</u></strong></font> ";
}

print "</div><hr />";

foreach($s_lang->table as $index=>$value ) {
	if((preg_match("/^$letter/i", convert_diacrit($value))) ||(($letter=='Fav')&&($s_lang->tablefav[$index]))) {
		$display[] = "	
				<div class='row'>
						<div class='colonne2' style='width: 80%;'>
							<a href='#' onClick=\"top.set_parent('$index', '".htmlentities(addslashes($value),ENT_QUOTES,$charset)."')\">$value</a>
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