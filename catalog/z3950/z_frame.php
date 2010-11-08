<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: z_frame.php,v 1.8 2009-05-16 11:12:03 dbellamy Exp $

if ( ($clause=="") && (count($bibli)==0) ) {
	echo "$msg[z3950_no_bib_selectetd]<br />";
	echo "<a href=\"#\" onclick='history.go(-1); return false;'>$msg[z3950_autre_rech]</a>&nbsp;";
	die();
	}

if ($clause=="") {
	for ($i=0; $i<count($bibli); $i++) {
		if ($clause=="") {
			$clause.=$bibli[$i];
			} else {
				$clause.=",".$bibli[$i];
				}
		}
	}

if ($clause!="") 
	$selection_bib="where bib_id in (".$clause.") "; 
else 
	$selection_bib;

print "
<br />
<iframe name=\"framedepartz3950\" frameborder=\"0\" scrolling=\"yes\" width=\"800\" height=\"550\" src=\"./catalog/z3950/z_progression_main.php?clause=$clause&crit1=$crit1&val1=$val1&crit2=$crit2&val2=$val2&bool1=$bool1&selection_bib=$selection_bib&limite=$limite&id_notice=$id_notice\">";
