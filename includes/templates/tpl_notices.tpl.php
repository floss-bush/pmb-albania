<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: tpl_notices.tpl.php,v 1.1 2009-08-11 12:32:36 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

global $abonnement_view,$abonnement_list,$abonnement_form;

$abonnement_view = "
<div id='abts_abonnement!!id_abonnement!!' class='notice-parent'>
	<img src='./images/plus.gif' class='img_plus' name='imEx' id='abts_abonnement!!id_abonnement!!Img' title='détail' border='0' onClick=\"expandBase('abts_abonnement!!id_abonnement!!', true); return false;\" hspace='3'>
	<span class='notice-heada'>
    	<small>
    		<span  class='statutnot1'  style='margin-right: 3px;'>
    			<img src='./images/spacer.gif' width='10' height='10' />
    		</span>
    	</small>
    	<a href='!!view_id_abonnement!!'>!!abonnement_header!!</a>
    </span>
    <br />
</div>
<div id='abts_abonnement!!id_abonnement!!Child' class='notice-child' style='margin-bottom:6px;display:none;'>
	<table width='100%'>
		<tr>
			<td>
				".$msg["abonnements_modele_lie"].": !!modele_lie!!
			</td>
		</tr>		
		<tr>
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

$abonnement_list ="
<script type='text/javascript' src='./javascript/tablist.js'></script>
<div class='form-contenu'>
<a href='javascript:expandAll()'><img src='./images/expand_all.gif' border='0' id='expandall'></a>
<a href='javascript:collapseAll()'><img src='./images/collapse_all.gif' border='0' id='collapseall'></a>
!!abonnement_list!!
</div>
<div class='row'>
   !!abts_abonnements_add_button!!
</div>";

$script1 = "
<script type='text/javascript'>
function confirm_delete()
{
	phrase = \"{$msg[abonnements_confirm_suppr_abonnement]}\";
	result = confirm(phrase);
	if(result)
		form.submit();
}
function test_form(form)
{
	if(form.abt_name.value.length == 0)
	{
		alert(\"$msg[326]\");
		form.abt_name.focus();
		return false;
	}
	!!test_liste_modele!!
	return true;
} 
</script>
";


$creation_abonnement_form = "
<script type='text/javascript' src='./javascript/tablist.js'></script>
$script1
<form class='form-$current_module' id='form_abonnement' name='form_abonnement' method='post' action=!!action!!>
	<h3>!!num_notice_libelle!!: !!libelle_form!!</h3>
	<div class='form-contenu'>
		
		<div class='colonne2'>
			<div class='row'>
				<label for='abonnement_name' class='etiquette'>".$msg["abonnements_nom_abonnement"]."</label>
			</div>
			<div class='row'>
				<input type='text' size='40' name='abt_name' id='abt_name' value='!!abt_name!!'/>
			</div>
		</div>
		<input type='hidden' name='num_notice' id='num_notice' value='!!num_notice!!'/>
		<div class='row'>
			
		</div>
		<div class='colonne2'>
			<div class='row'>
				<label for='abonnement_name' class='etiquette'>".$msg["abonnements_liste_modele"]."</label>
			</div>
			<div class='row'>
				!!liste_modele!!
			</div>
		</div>
		<div class='row'></div>
			!!abonnement_form1!!
	</div> <!-- Fin du contenu -->
	<div class='row'>
		<input type='hidden' id='act' name='act' value='' />
		<div class='left'><input type=\"submit\" class='bouton' value='".$msg["77"]."' onClick=\"document.getElementById('act').value='update';if(test_form(this.form)==true) this.form.submit();else return false;\"/>&nbsp;
			<input type='button' class='bouton' value='".$msg["bt_retour"]."' onClick=\"document.location='./catalog.php?categ=serials&sub=view&serial_id=!!serial_id!!&view=abon';\"/>&nbsp;
		</div>
			
	</div>
	<div class='row'></div>
</form>
";

$edition_abonnement_form="
<script type='text/javascript' src='./javascript/tablist.js'></script>
<script type='text/javascript'>
<!--

	function calcule_section(selectBox) {
		for (i=0; i<selectBox.options.length; i++) {
			id=selectBox.options[i].value;
		    list=document.getElementById(\"docloc_section\"+id);
		    list.style.display=\"none\";
			}
	
		id=selectBox.options[selectBox.selectedIndex].value;
		list=document.getElementById(\"docloc_section\"+id);
		list.style.display=\"block\";
		}

		function gere_statut(obj) {	
			var obj_check=document.getElementById(obj+'_check');	
			
			if(obj_check.checked == true){
				document.getElementById(obj).disabled = false;
			}else{
				document.getElementById(obj).disabled = true;
			}
		}
-->
</script>
$script1
<form class='form-$current_module' id='form_abonnement' name='form_abonnement' method='post' action=!!action!!>
	<h3>!!num_notice_libelle!!: !!libelle_form!!</h3>
	<div class='form-contenu'>
		
		<div class='colonne2'>
			<div class='row'>
				<label for='abonnement_name' class='etiquette'>".$msg["abonnements_nom_abonnement"]."</label>
			</div>
			<div class='row'>
				<input type='text' size='40' name='abt_name' id='abt_name' value='!!abt_name!!'/>
			</div>
		</div>
		<input type='hidden' name='num_notice' id='num_notice' value='!!num_notice!!'/>
		<div class='colonne2'>
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
				<input class='bouton' type='button' name='date_debut_lib' value='!!date_debut_lib!!' onClick=\"openPopUp('./select.php?what=calendrier&caller=form_abonnement&date_caller=!!date_debut!!&param1=date_debut&param2=date_debut_lib&auto_submit=NO&date_anterieure=YES', 'date_debut', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"   />
			</div>
		</div>
		<div class='colonne_suite'>
			<div class='row'>
				<label for='date_fin_lib' class='etiquette'>".$msg["abonnements_date_fin"]."</label>
			</div>
			<div class='row'>
				<input type='hidden' name='date_fin' value='!!date_fin!!' />
				<input class='bouton' type='button' name='date_fin_lib' value='!!date_fin_lib!!' onClick=\"openPopUp('./select.php?what=calendrier&caller=form_abonnement&date_caller=!!date_fin!!&param1=date_fin&param2=date_fin_lib&auto_submit=NO&date_anterieure=YES', 'date_fin', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"   />
			</div>
		</div>
		<div class='colonne2'>
			<div class='row'>
				<label for='fournisseur' class='etiquette'>".$msg["abonnements_fournisseur"]."</label>
			</div>	
			<div class='row'>
				<input id='id_fou' id='id_fou' name='id_fou' value='!!id_fou!!' type='hidden'>
				<input id='lib_fou' id='lib_fou' name='lib_fou' tabindex='1' value='!!lib_fou!!' class='saisie-30emr' onchange=\"openPopUp('./select.php?what=fournisseur&caller=form_abonnement&param1=id_fou&param2=lib_fou&id_bibli=0&deb_rech='+escape(this.form.lib_fou.value), 'select_fournisseur', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes'); \" type='text'>
				<input type='button' name='fournisseur' class='bouton' value='...'  
				onClick=\"openPopUp('./select.php?what=fournisseur&caller=form_abonnement&param1=id_fou&param2=lib_fou&id_bibli=0&deb_rech='+escape(this.form.lib_fou.value), 'select_fournisseur', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes');\"   />
				<input type='button' tabindex='1' class='bouton' value='".$msg['raz']."' onclick=\"document.getElementById('id_fou').value='0';document.getElementById('lib_fou').value='';\" />
			</div>
		</div>
		<div class='colonne_suite'>
			<div class='row'>
				<label for='destinataire' class='etiquette'>".$msg["abonnements_destinataire"]."</label>
			</div>
			<div class='row'>
				<TEXTAREA name='destinataire' rows='6' cols='50'>!!destinataire!!</TEXTAREA>
			</div>
		</div>
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<div class='colonne3'>
				<!-- cote -->
					<label class='etiquette' for='cote'>$msg[296]</label>
				<div class='row'>
					<input type='text' class='saisie-20em' id=\"cote\" name='cote' value='!!cote!!' />
					</div>
				</div>
			<div class='colonne3'>
				<!-- type document -->
				<label class='etiquette' for='f_ex_typdoc'>$msg[294]</label>
				<div class='row'>
					!!type_doc!!
					</div>
				</div>
			<div class='colonne3'>
				<!-- type document -->
				<label class='etiquette' for='f_ex_typdoc'>$msg[exemplarisation_automatique]</label>
				<div class='row'>
					!!exemplarisation_automatique!!
					</div>
				</div>
			</div>
		<div class='row'>
			<div class='colonne3'>
				<!-- localisation -->
				<label class='etiquette' for='f_ex_location'>$msg[298]</label>
				<div class='row'>
					!!localisation!!
					</div>
				</div>
			<div class='colonne3'>
				<!-- section -->
				<label class='etiquette' for='f_ex_section'>$msg[295]</label>
				<div class='row'>
					!!section!!
					</div>
				</div>
			<div class='colonne3'>
				<!-- propri?taire -->
				<label class='etiquette' for='f_ex_owner'>$msg[651]</label> 
				<div class='row'>
					!!owner!!
					</div>
				</div>
			</div>
			<div class='row'>
			<div class='colonne3'>
				<!-- statut -->
				<label class='etiquette' for='f_ex_statut'>$msg[297]</label>
				<div class='row'>
					!!statut!!
					</div>
				</div>
			<div class='colonne3'>
				<!-- code stat -->
				<label class='etiquette' for='f_ex_cstat'>$msg[299]</label>
				<div class='row'>
					!!codestat!!
					</div>
				</div>
			".$antivol_form."
			</div>
		<div class='row'>&nbsp;</div>
		<div class='row'>
			!!modele_list!!
		</div>
	</div> <!-- Fin du contenu -->
	<div class='row'>
		<input type='hidden' id='act' name='act' value='' />
		<div class='left'>
			<input type=\"submit\" class='bouton' value='".$msg["77"]."' onClick=\"document.getElementById('act').value='update';if(test_form(this.form)==true) this.form.submit();else return false;\"/>&nbsp;
			<input type='button' class='bouton' value='".$msg["bt_retour"]."' onClick=\"document.location='./catalog.php?categ=serials&sub=view&serial_id=!!serial_id!!&view=abon';\"/>&nbsp;
			<input type='button' class='bouton' value='".$msg["abts_abonnements_copy_abonnement"]."'  onClick=\"duplique('act',event);\" />
			<input type=\"submit\" class='bouton' value='".$msg["abonnement_generer_la_grille"]."' onClick=\"document.getElementById('act').value='gen';if(test_form(this.form)==true) this.form.submit();else return false;\"/>
			!!bouton_prolonge!!
		</div>
		<div class='right'><input type=\"submit\" class='bouton' value='".$msg["63"]."' onClick=\"document.getElementById('act').value='del';confirm_delete();return false;\"/></div>			
	</div>
	<div class='row'></div>
</form>
";

$tpl_calendrier = "
<form class='form-$current_module' id='form_abonnement' name='form_abonnement' method='post' action=!!action!!>
	<h3>!!libelle_form!!</h3>
	<div class='form-contenu'>
	<input type='hidden' name='abonnement_id' value='!!abonnement_id!!'/>
	!!calendrier!!
	</div> <!-- Fin du contenu -->
	<div class='row'>
		<input type='hidden' id='act' name='act' value='' />
		<div class='left'><input type=\"submit\" class='bouton' value='".$msg["77"]."' onClick=\"document.getElementById('act').value='update';this.form.submit();\"/>&nbsp;<input type='button' class='bouton' value='".$msg["76"]."' onClick=\"document.location='./catalog.php?categ=serials&sub=view&serial_id=!!serial_id!!&view=abonnement';\"/>&nbsp;<input type='button' class='bouton' value='".$msg["abts_abonnements_copy_abonnement"]."'/></div><div class='right'>!!del_button!!</div>
	</div>
	<div class='row'></div>
</form>
";		
?>
