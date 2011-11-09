<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search_history.inc.php,v 1.16.4.2 2011-07-21 13:58:07 trenon Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($include_path."/rec_history.inc.php");
if ($_SESSION["nb_queries"]) {
	print "<script>
		function unSetCheckboxes(the_form, the_objet) {
		var elts = document.forms[the_form].elements[the_objet+'[]'] ;
		var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

		if (elts_cnt) {
			for (var i = 0; i < elts_cnt; i++) { 
				if (elts[i].checked==0)
				{
					elts[i].checked = 1;
				}
				} // end for
			} else {
				if (elts.checked==0)
				{
					elts.checked = 1;
				}
				} // end if... else
		return true;
	} // end of the 'unSetCheckboxes()' function
	
	function verifCheckboxes(the_form, the_objet) {
		var bool=false;
		var elts = document.forms[the_form].elements[the_objet+'[]'] ;
		var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

		if (elts_cnt) {
				
			for (var i = 0; i < elts_cnt; i++) { 		
				if (elts[i].checked)
				{
					bool = true;
				}
			}
		} else {
				if (elts.checked)
				{
					bool = true;
				}
		}
		return bool;
	} 
	</script>";

	print "<div id='history_action'>";
	print "<input type='button' class='bouton' value=\"".$msg["suppr_elts_coch"]."\" onClick=\"if (verifCheckboxes('cases_a_cocher','cases_suppr')){ document.cases_a_cocher.submit(); return false;}\" />&nbsp;";
	print "<input type='button' class='bouton' value=\"".$msg["coch_cases"]."\" onClick=\"unSetCheckboxes('cases_a_cocher','cases_suppr'); return false;\" />&nbsp;";
	print "</div>";
}


print "<h3><span>".$msg["history_title"]."</span></h3>";

print "<form name='cases_a_cocher' method='post' action='./index.php?lvl=search_history&raz_history=1'>";

if ($_SESSION["nb_queries"]!=0) {
	for ($i=$_SESSION["nb_queries"]; $i>=1; $i--) {
		print "<input type=checkbox name='cases_suppr[]' value='$i'><b>$i)</b> ";
		if ($_SESSION["search_type".$i]!="module") {
			print "<a href=\"./index.php?lvl=search_result&get_query=$i\">".get_human_query($i)."</a><br /><br />";
		} else {
			print get_human_query($i)."<br /><br />";	
		}
	}
} else {
	print "<b>".$msg["histo_empty"]."</b>";	
}

print "</form>";
?>