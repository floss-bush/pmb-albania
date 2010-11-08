<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sort.tpl.php,v 1.6 2009-11-12 13:45:55 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

$ligne_tableau_tris = "
<tr class='!!pair_impair!!' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!pair_impair!!'\" style='cursor: pointer'>
<td><input type=checkbox name='cases_suppr[]' value='!!id_tri!!'></td>
<td alt='".$msg['appliq_tri']."' title='".$msg['appliq_tri']."'><a href='./index.php?!!page_en_cours1!!&get_last_query=".$_SESSION["last_query"]."&sort=!!id_tri!!'>!!nom_tri!!</a></td> 
</tr>
";	    		


$show_tris_form ="<script>
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
	</script><div id='tris'><br /><h3><span>".$msg['tris_dispos']."</span></h3>
		   <table width='100%'>
				<tr>
					<td valign='top'><form name='cases_a_cocher' method='post' action='./index.php?lvl=sort&raz_sort=1&page_en_cours=!!page_en_cours!!'>
						<table>
						!!liste_tris!!
						</table>
					</form></td>
				</tr>
				<tr>
					<td><input type='button' class='bouton' value='".$msg['tri_inactif']."' alt='".$msg['tri_inactif']."' title='".$msg['tri_inactif']."' onClick='parent.location=\"./index.php?!!page_en_cours1!!&get_last_query=".$_SESSION["last_query"]."&sort=\";return false;'></td>
				</tr>
				<tr>
					<td><br /><input type='button' class='bouton' value=\"".$msg["suppr_elts_sort_coch"]."\" onClick=\"if (verifCheckboxes('cases_a_cocher','cases_suppr')){ document.cases_a_cocher.submit(); return false;}\">&nbsp;
					<input type='button' class='bouton' value=\"".$msg["coch_cases"]."\" onClick=\"unSetCheckboxes('cases_a_cocher','cases_suppr'); return false;\">
					</td>
				</tr>
				</table>
		</div>
";


$liste_criteres_tri ="
	<tr>
		<td><h4><span>".$msg['puis_par_tri']."</span></td>
		<td><select name='liste_critere!!idLigne!!'><option value='' selected>&nbsp;</option>!!liste_criteres!!</select></h4></td>
		<td><select name='croit_decroit!!idLigne!!'><option value='c'>".$msg['tri_croissant']."</option><option value='d'>".$msg['tri_decroissant']."</option></select></td>
		<td><select name='num_text!!idLigne!!'><option value='text'>".$msg['tri_alpha']."</option><option value='num'>".$msg['tri_num']."</option></select></td>
	</tr>
";



$show_sel_form ="
	<div id='creer_tri'><h3><span>".$msg['definir_tri']."</span></h3>
		<form name='creer_sort_form' method='post' action='./index.php?lvl=sort&modif_sort=1&page_en_cours=!!page_en_cours!!'><table><tr><td>
		   		<h4><span>".$msg['tri_par']."</span></td><td><select name='liste_critere0'><option value='' selected>&nbsp;</option>!!liste_criteres!!</select></h4></td>
				<td><select name='croit_decroit0'><option value='c'>".$msg['tri_croissant']."</option><option value='d'>".$msg['tri_decroissant']."</option></select></td>
				<td><select name='num_text0'><option value='text'>".$msg['tri_alpha']."</option><option value='num'>".$msg['tri_num']."</option></select></td>
				</tr>
				!!liste_criteres_tri!!
				<tr><td colspan=4><input type='submit' class='bouton' value='".$msg['appliq_enreg_tri']."' alt='".$msg['appliq_enreg_tri']."' title='".$msg['appliq_enreg_tri']."'></td></tr>
			</table>
				</form>	
			</div>
";
?>
