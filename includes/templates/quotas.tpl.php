<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: quotas.tpl.php,v 1.8 2007-03-14 16:51:33 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$typ_quota_form="<div class='row'>".$msg["quotas_elements_list"]."</div>
<div class='row'>
!!list_elements!!
</div>
!!recorded!!
<form class='form-$current_module' name='quotas_typ_form' action='./admin.php?categ=$categ&sub=$sub$query_compl' method='post'>
	<h3>".$msg["quotas_elements_parameters"]."</h3>
	<div class='form-contenu'>
		<div class='row'><label class='etiquette' for='default_value'>!!short_type_comment!!</label></div>
		<div class='row'><input type='text' class='saisie-5em' size='10' name='default_value' id='default_value' value='!!default_value!!'></div>
		!!max_value!!
		!!min_value!!
		<div class='row'><label class='etiquette' for='conflict_value'>".$msg["quotas_elements_conflicts"]."</label></div>
		<div class='row'><input type='radio' name='conflict_value' id='conflict_value' value='1' !!checked_1!! onClick=\"document.getElementById('conflict_order').style.display='none';\"> ".$msg['quotas_plus_grand']."</div>
		<div class='row'><input type='radio' name='conflict_value' value='2' !!checked_2!! onClick=\"document.getElementById('conflict_order').style.display='none';\"> ".$msg['quotas_plus_petit']."</div>
		<div class='row'><input type='radio' name='conflict_value' value='3' !!checked_3!! onClick=\"document.getElementById('conflict_order').style.display='none';\"> ".$msg['quotas_defaut']."</div>
		<div class='row'><input type='radio' name='conflict_value' value='4' !!checked_4!! onClick=\"document.getElementById('conflict_order').style.display='';\"> ".$msg['quotas_ordre']."</div>
		<div class='row' id='conflict_order' style='display:none'><blockquote>!!conflict_list_elements!!</blockquote></div>
		!!force_lend!!
	</div>
	<div class='row'>
		<input type='submit' value='".$msg[77]."' class='bouton'/>
	</div>
	<input type='hidden' name='first' value='1'/>
</form>
";

$elements_quota_form="
<form class='form-$current_module' name='quotas_typ_form' action='./admin.php?categ=$categ&sub=$sub&elements=$elements$query_compl' method='post'>
	<div class='form-contenu'>
		!!quota_table!!
	</div>
	<div class='row'>
		<input type='submit' value='".$msg[77]."' class='bouton'/>
	</div>
	<input type='hidden' name='first' value='1'/>
	<input type='hidden' name='ids_order' value='!!ids_order!!'/>
</form>
";
?>