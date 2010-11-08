<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abts_modeles.tpl.php,v 1.19 2009-05-16 11:19:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

global $modele_view,$modele_list,$modele_form;

$modele_view = "
<div id='abts_modele!!id_modele!!' class='notice-parent'>
	<img src='./images/plus.gif' class='img_plus' name='imEx' id='abts_modele!!id_modele!!Img' title='détail' border='0' onClick=\"expandBase('abts_modele!!id_modele!!', true); return false;\" hspace='3'>
	<span class='notice-heada'>
    	<small>
    		<span  class='statutnot1'  style='margin-right: 3px;'>
    			<img src='./images/spacer.gif' width='10' height='10' />
    		</span>
    	</small>
    	<a href='!!view_id_modele!!'>!!modele_header!!</a>
    </span>
    <br />
</div>
<div id='abts_modele!!id_modele!!Child' class='notice-child' style='margin-bottom:6px;display:none;'>
	<table width='100%'>
		<tr>
			<td>
				".$msg["abonnements_periodicite"].": !!num_periodicite!!
			</td>

			<td>
				".$msg["abonnements_duree_abonnement"].": !!duree_abonnement!!
			</td>
		</tr>
		<tr>
			<td>
				".$msg["abonnements_date_debut"].": !!date_debut!!
			</td>
			<td>
				".$msg["abonnements_date_fin"].": !!date_fin!!
			</td>
		</tr>
		<tr>
			<td>
				".$msg["abonnements_nombre_de_series"].": !!nombre_de_series!!
			</td>
		</tr>
		<tr>
			<td>
				".$msg["abonnements_nombre_de_horsseries"].": !!nombre_de_horsseries!!
			</td>
		</tr>
	</table>
</div>
";

$modele_list ="
<script type='text/javascript' src='./javascript/tablist.js'></script>
<div class='form-contenu'>
<a href='javascript:expandAll()'><img src='./images/expand_all.gif' border='0' id='expandall'></a>
<a href='javascript:collapseAll()'><img src='./images/collapse_all.gif' border='0' id='collapseall'></a>
!!modele_list!!
</div>
<div class='row'>
	<input type='button' class='bouton' value='".$msg["abts_modeles_add_button"]."' onClick='document.location=\"catalog.php?categ=serials&sub=modele&serial_id=$serial_id\"'/>
</div>";

$script1 = "
<script type='text/javascript'>
function confirm_delete()
{
	phrase = \"{$msg[abonnements_confirm_suppr_modele]}\";
	result = confirm(phrase);
	if(result)
		form.submit();
}
function test_form(form)
{
	if(form.modele_name.value.length == 0)
	{
		alert(\"$msg[326]\");
		form.modele_name.focus();
		return false;
	}
	return true;
} 
function gere_num(obj)
{	
	var obj_id=document.getElementById(obj);	
	var vol_cycle_id=document.getElementById('vol_cycle');
	var tom_cycle_id=document.getElementById('tom_cycle');	
	if(obj== 'num_cycle'){
		if(obj_id.checked == true){
			document.getElementById('num_depart').disabled = false;
			document.getElementById('num_combien').disabled = false;
			document.getElementById('num_increment_date').disabled = false;
			document.getElementById('num_date_unite').disabled = false;
			document.getElementById('num_increment').disabled = false;
			document.getElementById('num_increment1').disabled = false;
		}else{
			document.getElementById('num_depart').disabled = true;
			document.getElementById('num_combien').disabled = true;
			document.getElementById('num_increment_date').disabled = true;
			document.getElementById('num_date_unite').disabled = true;
			document.getElementById('num_increment').disabled = true;
			document.getElementById('num_increment1').disabled = true;
		}
	}
	if(obj== 'vol_actif'){
		if(obj_id.checked == true){
			document.getElementById('vol_increment_numero').disabled = false;
			document.getElementById('vol_increment_date').disabled = false;
			document.getElementById('vol_date_unite').disabled = false;
			document.getElementById('vol_cycle').disabled = false;
			document.getElementById('vol_increment').disabled = false;
			document.getElementById('vol_increment1').disabled = false;
			if(vol_cycle_id.checked == true){
				document.getElementById('vol_depart').disabled = false;
				document.getElementById('vol_combien').disabled = false;
			}
		}else{
			document.getElementById('vol_increment_numero').disabled = true;
			document.getElementById('vol_increment_date').disabled = true;
			document.getElementById('vol_date_unite').disabled = true;
			document.getElementById('vol_cycle').disabled = true;		
			document.getElementById('vol_increment').disabled = true;
			document.getElementById('vol_increment1').disabled = true;
			document.getElementById('vol_depart').disabled = true;
			document.getElementById('vol_combien').disabled = true;
		}
	}	
	if(obj== 'vol_cycle'){
		if(obj_id.checked == true){
			document.getElementById('vol_depart').disabled = false;
			document.getElementById('vol_combien').disabled = false;
		}else{
			document.getElementById('vol_depart').disabled = true;
			document.getElementById('vol_combien').disabled = true;
		}
	}	
	if(obj== 'tom_actif'){		
		if(obj_id.checked == true){
			document.getElementById('tom_increment_numero').disabled = false;
			document.getElementById('tom_increment_date').disabled = false;
			document.getElementById('tom_date_unite').disabled = false;
			document.getElementById('tom_cycle').disabled = false;
			
			document.getElementById('tom_increment').disabled = false;
			document.getElementById('tom_increment1').disabled = false;
			if(tom_cycle_id.checked == true){
				document.getElementById('tom_depart').disabled = false;
				document.getElementById('tom_combien').disabled = false;
			}
		}else{
			document.getElementById('tom_increment_numero').disabled = true;
			document.getElementById('tom_increment_date').disabled = true;
			document.getElementById('tom_date_unite').disabled = true;
			document.getElementById('tom_cycle').disabled = true;
			document.getElementById('tom_increment').disabled = true;
			document.getElementById('tom_increment1').disabled = true;
			document.getElementById('tom_depart').disabled = true;
			document.getElementById('tom_combien').disabled = true;
		}
	}
	if(obj== 'tom_cycle'){
		if(obj_id.checked == true){
			document.getElementById('tom_depart').disabled = false;
			document.getElementById('tom_combien').disabled = false;
		}else{
			document.getElementById('tom_depart').disabled = true;
			document.getElementById('tom_combien').disabled = true;
		}
	}	

}		
window.onload = function()
{
	gere_num(\"vol_cycle\");
	gere_num(\"tom_cycle\");
	gere_num(\"num_cycle\");
	gere_num(\"vol_actif\");
	gere_num(\"tom_actif\");
}
</script>
";

$modele_form = "
<script type='text/javascript' src='./javascript/tablist.js'></script>
$script1
<form class='form-$current_module' id='form_modele' name='form_modele' method='post' action=!!action!!>
	<h3>!!num_notice_libelle!!: !!libelle_form!!</h3>
	<div class='form-contenu'>
		<input type='hidden' name='modele_id' value='!!modele_id!!'/>
		<div class='colonne2'>
			<div class='row'>
				<label for='modele_name' class='etiquette'>".$msg["abonnements_nom_modele"]."</label>
			</div>
			<div class='row'>
				<input type='text' size='40' name='modele_name' id='modele_name' value='!!modele_name!!'/>
			</div>
		</div>
				<input type='hidden' name='num_notice' id='num_notice' value='!!num_notice!!'/>
		<div class='row'></div>
		<div class='colonne2'>
			<div class='row'>
				<label for='num_periodicite' class='etiquette'>".$msg["abonnements_periodicite"]."</label>
			</div>
			<div class='row'>
				!!num_periodicite!!
			</div>
		</div>
		<div class='colonne_suite'>
			<div class='row'>
				<label for='duree_abonnement' class='etiquette'>".$msg["abonnements_duree_abonnement"]."</label>
			</div>
			<div class='row'>
				<input type='text' size='5' name='duree_abonnement' id='duree_abonnement' value='!!duree_abonnement!!'/>
			</div>
		</div>
		<div class='row'></div>
		<div class='colonne2'>
			<div class='row'>
				<label for='date_debut_lib' class='etiquette'>".$msg["abonnements_date_debut"]."</label>
			</div>
			<div class='row'>
				<input type='hidden' name='date_debut' value='!!date_debut!!' />
				<input class='bouton' type='button' name='date_debut_lib' value='!!date_debut_lib!!' onClick=\"openPopUp('./select.php?what=calendrier&caller=form_modele&date_caller=!!date_debut!!&param1=date_debut&param2=date_debut_lib&auto_submit=NO&date_anterieure=YES', 'date_debut', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"   />
			</div>
		</div>
		<div class='colonne_suite'>
			<div class='row'>
				<label for='date_fin_lib' class='etiquette'>".$msg["abonnements_date_fin"]."</label>
			</div>
			<div class='row'>
				<input type='hidden' name='date_fin' value='!!date_fin!!' />
				<input class='bouton' type='button' name='date_fin_lib' value='!!date_fin_lib!!' onClick=\"openPopUp('./select.php?what=calendrier&caller=form_modele&date_caller=!!date_fin!!&param1=date_fin&param2=date_fin_lib&auto_submit=NO&date_anterieure=YES', 'date_fin', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"   />
			</div>
		</div>
		<div class='row'>&nbsp;</div>
		<div id='abts_exclusion' class='notice-parent'>
			<img src='./images/plus.gif' class='img_plus' name='imEx' id='abts_exclusionImg' title='détail' border='0' onClick=\"expandBase('abts_exclusion', true); return false;\" hspace='3'>
			<span class='notice-heada'>
					".$msg["abonnements_titre_exclusion_date"]."
    		</span>
		</div>
		<div id='abts_exclusionChild' class='notice-child' style='margin-bottom:6px;display:none;width:94%'>
			<div class='row'>
				<label class='etiquette'>".$msg["abonnements_periodicite_jours_semaine_exclus"]."</label>
			</div>
			<div class='row'>
				!!days!!
			</div>
			<div class='row'>
				<label class='etiquette'>".$msg["abonnements_periodicite_jours_mois_exclus"]."</label>
			</div>
			<div class='row'>
				!!days_month!!
			</div>
			<div class='row'>
				<label class='etiquette'>".$msg["abonnements_periodicite_semaines_mois_exclus"]."</label>
			</div>
			<div class='row'>
				!!week_month!!
			</div>
			<div class='row'>
				<label class='etiquette'>".$msg["abonnements_periodicite_semaines_annee_exclus"]."</label>
			</div>
			<div class='row'>
				!!week_year!!
			</div>
			<div class='row'>
				<label class='etiquette'>".$msg["abonnements_periodicite_mois_annee_exclus"]."</label>
			</div>
			<div class='row'>
				!!month_year!!
			</div>
			<div class='row'></div>
		</div>
		<div id='abts_numerotation' class=.'notice-parent'>
			<img src='./images/plus.gif' class='img_plus' name='imEx' id='abts_numerotationImg' title='détail' border='0' onClick=\"expandBase('abts_numerotation', true); return false;\" hspace='3'>
			<span class='notice-heada'>
					".$msg["abonnements_titre_numerotation"]."
    		</span>
		</div>
		<div id='abts_numerotationChild' class='notice-child' style='margin-bottom:6px;display:none;width:94%'>
			<div class='row separateur'>
				<label class='etiquette'>".$msg["abonnements_titre_numero"]."</label>
			</div>
			<div class='row'>
				!!numero!!
			</div>
			<div class='row separateur'>
				<label class='etiquette'>".$msg["abonnements_titre_volume"]."</label>
			</div>
			<div class='row'>
				!!volume!!
			</div>
			<div class='row separateur'>
				<label class='etiquette'>".$msg["abonnements_titre_tome"]."</label>
			</div>
			<div class='row'>
				!!tome!!
			</div>
			<div class='row separateur'>
				<label class='etiquette'>".$msg["abonnements_titre_format"]."</label>
			</div>
			<div class='row'>
				!!format!!
				!!format_periode!!
			</div>
		</div>
	</div> <!-- Fin du contenu -->
	<div class='row'>
		<input type='hidden' id='act' name='act' value='' />
		<div class='left'><input type=\"submit\" class='bouton' value='".$msg["77"]."' onClick=\"document.getElementById('act').value='update';if(test_form(this.form)==true) this.form.submit();else return false;\"/>&nbsp;
			<input type='button' class='bouton' value='".$msg["bt_retour"]."' onClick=\"document.location='./catalog.php?categ=serials&sub=view&serial_id=!!serial_id!!&view=modele';\"/>&nbsp;
			!!copy_bouton!!
			<input type=\"submit\" class='bouton' value='".$msg["abonnement_generer_la_grille"]."' onClick=\"document.getElementById('act').value='gen';if(test_form(this.form)==true) this.form.submit();else return false;\"/>
		</div>
		<div class='right'>!!del_button!!</div>			
	</div>
	<div class='row'></div>

</form>
";

$tpl_calendrier = "
<form class='form-$current_module' id='form_modele' name='form_modele' method='post' action=!!action!!>
	<h3>!!libelle_form!!</h3>
	<div class='form-contenu'>
	<input type='hidden' name='modele_id' value='!!modele_id!!'/>
	!!calendrier!!
	</div> <!-- Fin du contenu -->
	<div class='row'>
		<input type='hidden' id='act' name='act' value='' />
		<div class='left'><input type=\"submit\" class='bouton' value='".$msg["77"]."' onClick=\"document.getElementById('act').value='update';this.form.submit();\"/>&nbsp;<input type='button' class='bouton' value='".$msg["76"]."' onClick=\"document.location='./catalog.php?categ=serials&sub=view&serial_id=!!serial_id!!&view=modele';\"/>&nbsp;<input type='button' class='bouton' value='".$msg["abts_modeles_copy_modele"]."'/></div><div class='right'>!!del_button!!</div>
	</div>
	<div class='row'></div>
</form>
";
		
$tpl_del_bouton="<input type=\"submit\" class='bouton' value='".$msg["63"]."' onClick=\"document.getElementById('act').value='del';confirm_delete();return false;\"/>";
$tpl_copy_bouton="<input type='button' class='bouton' value='".$msg["abts_modeles_copy_modele"]."' onclick=\"openPopUp('./select.php?what=notice&niveau_biblio=S&modele_id=!!modele_id!!&serial_id=!!serial_id!!&caller=notice&param1=f_rel_id_0&param2=f_rel_0&no_display=0', 'select_notice', 700, 500, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />";			
?>
