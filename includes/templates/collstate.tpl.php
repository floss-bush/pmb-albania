<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collstate.tpl.php,v 1.4 2010-08-11 10:25:38 arenou Exp $

// templates pour gestion des autorités collections

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

require_once($base_path."/javascript/misc.inc.php");

$selector_prop = "toolbar=no, dependent=yes, width=$selector_x_size, height=$selector_y_size, resizable=yes, scrollbars=yes";

//	----------------------------------
// $collection_form : form saisie collection

$collstate_form = jscript_unload_question()."
<script type='text/javascript'>
	function test_form(form)
	{
		unload_off();
		!!return_form!!
	}
	function confirm_delete() {
        result = confirm(\"".$msg["confirm_suppr"]."\");
        if(result) {
        	unload_off();
          	document.location='./catalog.php?categ=serials&sub=collstate_delete&id=!!id!!&serial_id=!!serial_id!!&location=!!location_id!!';
		}
    }
    function calculate_collections_state() {
		var url= \"./ajax.php?module=catalog&categ=collections_state&fname=calculate_collections_state\";
		var state_col = new http_request();
		var separator = '';
	
		if(state_col.request(url,1,\"&id_serial=!!serial_id!!&id_location=\"+document.getElementById('location_id').value)) alert(state_col.get_text());
		else {
			if (document.getElementById('state_collections').value) separator=' ';
			temp=state_col.get_text();
			document.getElementById('state_collections').value=document.getElementById('state_collections').value + separator + temp;
		}
	}				
</script>
<script src='javascript/ajax.js'></script>

<form class='form-$current_module' id='saisie_collstate' name='saisie_collstate' method='post' action='!!action!!' onSubmit=\"return false\" >
<h3>!!libelle!!</h3>
<div class='form-contenu'>

!!location_field!!
!!emplacement_field!!

<!-- state_collections -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='state_collections'>".$msg["collstate_form_collections"]."</label>
		<input id='btn_calc_1' class='bouton_small' value='Calculer' style='visibility: visible;' onclick=\"calculate_collections_state();\" type='button'>
		</div>
	<div class='row'>
		<textarea rows='5' class='saisie-80em' id='state_collections' name='state_collections'>!!state_collections!!</textarea>
	</div>
</div>

<!-- cote -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='cote'>".$msg["collstate_form_cote"]."</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-80em' size='80' id='cote' name='cote' value=\"!!cote!!\" />
	</div>
</div>

<!-- archive -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='archive'>".$msg["collstate_form_archive"]."</label>
		</div>
	<div class='row'>
	<input type='text' class='saisie-80em' size='80' id='archive' name='archive' value=\"!!archive!!\" />
	</div>
</div>

<!-- origine -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='origine'>".$msg["collstate_form_origine"]."</label>
	</div>
	<div class='row'>
		<input type='text' class='saisie-80em' size='80' id='origine' name='origine' value=\"!!origine!!\" />
	</div>
</div>

<!-- note -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='note'>".$msg["collstate_form_note"]."</label>
		</div>
	<div class='row'>
		<textarea rows='2' class='saisie-80em' id='note' name='note'>!!note!!</textarea>
	</div>
</div>

<!-- lacune -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='lacune'>".$msg["collstate_form_lacune"]."</label>
		</div>
	<div class='row'>
		<textarea rows='2' class='saisie-80em' id='lacune' name='lacune'>!!lacune!!</textarea>
	</div>
</div>
!!support_field!!
!!statut_field!!

!!parametres_perso!!

</div>

<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' !!annul!! />
		<input type='button' value='$msg[77]' class='bouton' id='btsubmit' onClick=\"if (test_form(this.form)) this.form.submit();\" />
	</div>
	<div class='right'>
		!!delete!!
		</div>
	</div>
<div class='row'></div>
</form>
";

$location_field="
<!-- localisation -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='location'>".$msg["collstate_form_localisation"]."</label>
	</div>
	<div class='row'>
		!!location!!
	</div>
</div>";

$statut_field="
<!-- statut -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='statut'>".$msg["collstate_form_statut"]."</label>
	</div>
	<div class='row'>
		!!statut!!
	</div>
</div>";

$emplacement_field="
<!-- emplacement -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='emplacement'>".$msg["collstate_form_emplacement"]."</label>
	</div>
	<div class='row'>
		!!emplacement!!
	</div>
</div>";

$support_field="
<!-- support -->
<div class='row'>
	<div class='row'>
		<label class='etiquette' for='support'>".$msg["collstate_form_support"]."</label>
	</div>
	<div class='row'>
		!!support!!
	</div>
</div>";
$tpl_collstate_liste_script="
<script>
	function show_collstate(id) {
		if (document.getElementById(id).style.display=='none') {
			document.getElementById(id).style.display='';		
		} else {
			document.getElementById(id).style.display='none';
		}
	} 
</script>";
	
$tpl_collstate_liste_form="
<form action='!!base_url!!' method='post' name='filter_form'><input type='hidden' name='location' value='!!location!!'/>
	!!collstate_table!!
</form>";

$tpl_collstate_liste[0]="
<table>	
	<tr>		
		<th>".$msg["collstate_form_emplacement"]."</th>		
		<th>".$msg["collstate_form_cote"]."</th>
		<th>".$msg["collstate_form_support"]."</th>
		<th>".$msg["collstate_form_statut"]."</th>		
		<th>".$msg["collstate_form_origine"]."</th>		
		<th>".$msg["collstate_form_collections"]."</th>
		<th>".$msg["collstate_form_lacune"]."</th>		
	</tr>
	!!collstate_liste!!
</table>";

$tpl_collstate_liste_line[0].="
<tr class='!!pair_impair!!' !!tr_surbrillance!! style='cursor: pointer'>
	<td !!tr_javascript!! >!!emplacement_libelle!!</td>
	<td !!tr_javascript!! >!!cote!!</td>
	<td !!tr_javascript!! >!!type_libelle!!</td>
	<td !!tr_javascript!! >!!statut_libelle!!</td>	
	<td !!tr_javascript!! >!!origine!!</td>
	<td !!tr_javascript!! >!!state_collections!!</td>
	<td !!tr_javascript!! >!!lacune!!</td>
</tr>";

$tpl_collstate_liste[1]="
$tpl_collstate_liste_script
<table>	
	<tr>
		<th>".$msg["collstate_form_localisation"]."</th>		
		<th>".$msg["collstate_form_emplacement"]."</th>		
		<th>".$msg["collstate_form_cote"]."</th>
		<th>".$msg["collstate_form_support"]."</th>
		<th>".$msg["collstate_form_statut"]."</th>		
		<th>".$msg["collstate_form_origine"]."</th>		
		<th>".$msg["collstate_form_collections"]."</th>
		<th>".$msg["collstate_form_lacune"]."</th>		
	</tr>
	!!collstate_liste!!
</table>
";

$tpl_collstate_liste_line[1].="
<tr class='!!pair_impair!!' !!tr_surbrillance!! style='cursor: pointer'>	
	<td !!tr_javascript!! >!!localisation!!</td>
	<td !!tr_javascript!! >!!emplacement_libelle!!</td>
	<td !!tr_javascript!! >!!cote!!</td>
	<td !!tr_javascript!! >!!type_libelle!!</td>
	<td !!tr_javascript!! >!!statut_libelle!!</td>
	<td !!tr_javascript!! >!!origine!!</td>
	<td !!tr_javascript!! >!!state_collections!!</td>
	<td !!tr_javascript!! >!!lacune!!</td>
</tr>";

