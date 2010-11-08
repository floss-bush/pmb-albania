<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes.tpl.php,v 1.9 2010-08-19 07:35:07 touraine37 Exp $

// templates pour gestion des autorités collections

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$form_do_demande ="
	<form class='form-".$current_module."' id='do_dmde' name='do_dmde' method='post' action=\"empr.php?tab=request&lvl=do_dmde\">
	<h3><span>".$msg['demandes_do_search']."</span></h3>
	<input type='hidden' id='act' name='act' />
	<div class='form-contenu'>
		<div class='row'>	
			<label class='etiquette'>".$msg['demandes_theme']."</label>
		</div>
		<div class='row'>	
			!!select_theme!!
		</div>
		<div class='row'>
			<label class='etiquette'>".$msg['demandes_type']."</label>
		</div>
		<div class='row'>	
			!!select_type!!
		</div>	
		<div class='row'>
			<label class='etiquette'>".$msg['demandes_titre']."</label>
		</div>
		<div class='row'>
			<input class='saisie-50em' type='texte' id='titre' name='titre' />
		</div>
		<div class='row'>
			<label class='etiquette'>".$msg['demandes_sujet']."</label>
		</div>
		<div class='row'>
			<textarea id='sujet' name='sujet' cols='55' rows='4' wrap='virtual'></textarea>
		</div>
		<div class='row'>
			<label class='etiquette'>".$msg['demandes_date_butoir']."</label>
		</div>
		<div class='row'>
			<input type='hidden' id='date_fin' name='date_fin' value='!!date_fin!!' />
			<input type='button' class='bouton' id='date_fin_btn' name='date_fin_btn' value='!!date_fin_btn!!' onClick=\"window.open('./select.php?what=calendrier&caller=do_dmde&date_caller=!!date_fin!!&param1=date_fin&param2=date_fin_btn&auto_submit=NO&date_anterieure=YES', 'date_fin', 'width=250,height=300,toolbar=no,dependent=yes,resizable=yes')\"/>
		</div>
		<div class='row'></div>	
	</div>
	<br />
	<div class='row'>
		<div class='left'>
			<input type='submit' class='bouton' value='".$msg['demandes_do']."' onClick='this.form.act.value=\"save\" ; return test_form(this.form); ' />
		</div>
	</div>
	<div class='row'></div>
</form>

<script type='text/javascript'>
	function test_form(form) {	

		if((form.titre.value.length == 0) || (form.date_fin.value.length == 0)){
			alert(\"$msg[demandes_create_ko]\");
			return false;
	    } 
	    
		return true;
			
	}
</script>
";

$form_liste_demande ="
	<form class='form-".$current_module."' id='liste' name='liste' method='post' action=\"./empr.php?tab=request&lvl=list_dmde\">
	<input type='hidden' name='act' id='act' />
	<h3><span>".$msg['demandes_list']."</span></h3>
	<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette'>".$msg['demandes_etat']."</label>
	</div>
	<div class='row'>
		!!select_etat!!
	</div>
		<table>
			<tbody>
				<tr>
					<th>".$msg['demandes_titre']."</th>
					!!entete_etat!!
					<th>".$msg['demandes_date_dmde']."</th>
					<th>".$msg['demandes_date_butoir']."</th>
					<th>".$msg['demandes_user']."</th>
					<th>".$msg['demandes_progression']."</th>
					<th>".$msg['demandes_notice']."</th>				
					<th></th>
				</tr>
				!!liste_dmde!!				
			</tbody>
		</table>
	</div>
	<div class='row'></div>
</form>	
";

$form_liste_actions="
<script>
	function refuser_enregistrement(){
		var suj = document.getElementById('sujet').value;
		if(!suj){
			alert('".$msg['demandes_no_subject']."');
			return false;
		}		
		return true;
	}
</script>
<form class='form-".$current_module."' id='liste_action' name='liste_action' method='post' action=\"\">	
<h3 id='htitle'><span>".$msg['demandes_list_action']." : !!titre_dmde!!</span></h3>
	<div class='form-contenu' >
		<table id='info_dmde' style='border: 1px solid #CCCCCC ; padding: 5px 5px 5px 5px;'>
			<tr>
				<td class='bg-grey'><span class='etiq_champ'>".$msg['demandes_theme']." : </span></td>
				<td>!!theme_dmde!!</td>
			</tr>
			<tr>
				<td class='bg-grey'><span class='etiq_champ'>".$msg['demandes_type']." : </span></td>
				<td>!!type_dmde!!</td>
			</tr>
			<tr>
				<td class='bg-grey'><span class='etiq_champ'>".$msg['demandes_etat']." : </span></td>
				<td>!!etat_dmde!!</td>
			</tr>
			<tr>
				<td class='bg-grey'><span class='etiq_champ'>".$msg['demandes_sujet']." : </span></td>
				<td>!!sujet_dmde!!</td>
			</tr>
			<tr>
				<td class='bg-grey'><span class='etiq_champ'>".$msg['demandes_date_dmde']." : </span></td>
				<td>!!date_dmde!!</td>
			</tr>
			<tr>	
				<td class='bg-grey'><span class='etiq_champ'>".$msg['demandes_date_prevue']." : </span></td>
				<td>!!date_prevue_dmde!!</td>
			</tr>
			<tr>	
				<td class='bg-grey'><span class='etiq_champ'>".$msg['demandes_date_butoir']." : </span></td>
				<td>!!deadline_dmde!!</td>
			</tr>
			<tr>	
				<td class='bg-grey'><span class='etiq_champ'>".$msg['demandes_progression']." : </span></td>
				<td>!!progression!!</td>
			</tr>
		</table>
		<div class='row' id='act_list'>
			!!liste_actions!!	
		</div>	
		<a name='anchor_form'></a>
		<div class='row' id='saisie_form' style='display:none'></div>
	</div>
	<br/>	
	<div class='row' id='btn_grp'>
		!!btns_actions!!
	</div>
<form>

";


?>