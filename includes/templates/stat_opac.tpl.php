<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: stat_opac.tpl.php,v 1.5 2009-08-25 22:51:13 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");


$stat_opac_view_form ="
	<form class='form_view' id='view' name='view' method='post' action='./admin.php?categ=opac&sub=stat&section=view_gestion' >	
	<input type='hidden' id='act' name='act' />
	<input type='hidden' id='id_view' name='id_view' />
	<input type='hidden' id='id_req' name='id_req' />
		<h3>$msg[stat_view_list]</h3>
		<div class='form-contenu'>
			!!liste_vues!!
			<br>
			!!options_conso!!
			<br>
		</div>
		<div class='row'>
			<input class='bouton' type='submit' value='$msg[stat_add_view]' onClick=\"this.form.act.value='add_view';\"/>
			!!btn_consolide!!
		</div>
	</form>

";


$stat_view_addview_form="
	<form class='form-addview' id='addview' name='addview' method='post' action='./admin.php?categ=opac&sub=stat&section=view_list'>	
	<input type='hidden' id='act' name='act'/>
	<input type='hidden' id='id_view' name='id_view' value='!!id_view!!'/>
	<h3>!!view_title!!</h3>
		<div class='form-contenu'>
			<script>
				function test_form_view(form){
					if(form.view_name.value.length == 0){
						alert(\"$msg[stat_field_not_filled]\");
						return false;
					} 
					return true;
				}
				
				function confirm_delete() {
	       			result = confirm(\"${msg[confirm_suppr]}\");
	       			if(result) {
	       				return true;
					} else
	           			return false;
    			}
			</script>
			<div class='colonne2'>
				<div class='row'>
					<label class='etiquette'>$msg[stat_view_name] &nbsp;</label>
				</div>
				<div class='row'>
					<input type='text' class='saisie-20em' name='view_name' value='!!name_view!!' />
				</div>
			</div>
			<div class='colonne_suite'>
				<div class='row'>
					<label class='etiquette'>$msg[stat_view_comment] &nbsp;</label>
				</div>
				<div class='row'>	
					<textarea name='view_comment' rows='2' cols='50'/>!!view_comment!!</textarea>
				</div>
			</div>
			!!table_colonne!!
			
			<div class='row'></div>
			
		</div>
		<div class='row'>
			<div class='left'>
				<input class='bouton' type='button'  value='$msg[76]' onClick='document.location=\"./admin.php?categ=opac&sub=stat&section=view_list\"'; />				
				<input class='bouton' type='submit'  value='$msg[77]' onClick='this.form.act.value=\"save_view\";return test_form_view(this.form);'/>
				!!bouton_add_col!! 
				!!bouton_reinit_view!!
			</div>
			<div class='right'>
				!!btn_suppr!!
			</div>
			<div class='row'></div>
		</div>
		</form>
";
	
$stat_view_addcol_form="

	<form class='form-addview' id='addview' name='addview' method='post' action='./admin.php?categ=opac&sub=stat&section=view_gestion'>	
	<input type='hidden' id='act' name='act'/>
	<input type='hidden' id='id_view' name='id_view' value='!!id_view!!'/>
	<input type='hidden' id='id_col' name='id_col' value='!!id_col!!'/>
		<h3>!!col_title!!</h3>
		<div class='form-contenu'>
			<script>
				function test_form_col(form){
					if(form.col_name.value.length == 0){
						alert(\"$msg[stat_field_not_filled]\");
						return false;
					} 
					if(form.expr_col.value.length == 0){
						alert(\"$msg[stat_field_not_filled]\");
						return false;
					}
					return true;
				}
				
				function confirm_delete() {
	       			result = confirm(\"${msg[confirm_suppr]}\");
	       			if(result) {
	       				return true;
					} else
	           			return false;
    			}
			</script>
			<div class='row'>
				<label class='etiquette'>$msg[stat_col_name] &nbsp;</label>
			</div>	
			<div class='row'>
				<input type='text' class='saisie-20em' name='col_name' value='!!col_name!!' />
			</div>
			<div class='row'>
				<label class='etiquette'>$msg[stat_col_expr] &nbsp;</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-50em' name='expr_col' value='!!expr_col!!' />
			</div>
			<div class='row'>
				<label class='etiquette'>$msg[stat_col_filtre] &nbsp;</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-50em' name='expr_filtre' value='!!expr_filtre!!' />
			</div>
			<div class='row'>
				<label class='etiquette'>$msg[stat_col_type] &nbsp;</label>
			</div>
			<div class='row'>
				!!datatype!!
			</div>			
		</div>
		<div class='row'>
				<div class='left'>
					<input class='bouton' type='submit'  value='$msg[76]' onClick='document.location=\"./admin.php?categ=opac&sub=stat&section=view_gestion\";'/>
					<input class='bouton' type='submit'  value='$msg[77]' onClick='this.form.act.value=\"save_col\"; return test_form_col(this.form);'/>
				</div>
				<div class='right'>
					!!btn_suppr!!
				</div>
				<div class='row'></div>
			</div>
	</form>

";

	
$stat_view_request_form = "
<form class='form-request' name='request_form' method='post' action='./admin.php?categ=opac&sub=stat&section=view_list'>
	<input type='hidden' id='act' name='act'/>
	<input type='hidden' id='id_req' name='id_req' value='!!id_req!!'/>
	<input type='hidden' id='id_view' name='id_view' value='!!id_view!!'/>
	<h3>!!request_title!!</h3>
	<!--    Contenu du form    -->
	<div class='form-contenu'>
	
		<script>
			function right_to_left() {
				left=document.request_form.f_request_code;
				right=document.request_form.elements['nom_col[]'];
				for (i=0; i<right.length; i++) {
					if (right.options[i].selected) {
						left.value =  left.value +' '+ right.options[i].text;
					}
				}
			}
			
			function test_form_req(form){
				if(form.f_request_name.value.length == 0){
					alert(\"$msg[stat_field_not_filled]\");
					return false;
				} 
				if(form.f_request_code.value.length == 0){
					alert(\"$msg[stat_field_not_filled]\");
					return false;
				}
	
				return true;
			}
			
			function confirm_delete() {
       			result = confirm(\"${msg[confirm_suppr]}\");
       			if(result) {
       				return true;
				} else
           			return false;
    		}
		</script>
		
		<div class='row'>
			<label class='etiquette' for='form_name'>$msg[705]</label>
		</div>
		<div class='row'>
			<input type='text' name='f_request_name' value='!!name_request!!' maxlength='255' class='saisie-50em' />
		</div>
		<table height='100%' width='100%'>		
			<tbody>
				<tr>
					<td width='40%'><label class='etiquette' for='form_code'>$msg[706]</label></td>
					<td width='20%'></td>
					<td width='40%'><label class='etiquette' for='form_code'>$msg[stat_associate_col]</label></td>
				</tr>
				<tr>
					<td height='100%' width='40%'><textarea cols='55' rows='8' name='f_request_code'>!!code!!</textarea></td>
					<td width='20%' style='text-align:center'><input type='button' class='bouton' value='<<' onClick=\"right_to_left()\" />&nbsp;</td>
					<td height='100%' width='40%'>!!liste_cols!!</td>
				</tr>
			</tbody>
		</table>	

		<div class='row'>
			<label class='etiquette' for='form_comment'>$msg[707]</label>
			</div>
		<div class='row'>
			<input type='text' name='f_request_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
			</div>
		</div>
		<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./admin.php?categ=opac&sub=stat&section=view_list\"' />&nbsp;
		<input type='submit' class='bouton' value='$msg[77]' onClick=\"this.form.act.value='save_request';return test_form_req(this.form);\" />&nbsp;
		<input type='submit' class='bouton' value='$msg[708]' onClick=\"this.form.act.value='exec_req';return test_form_req(this.form);\" />&nbsp;
		</div>
	<div class='right'>
		<input type='submit' class='bouton' value=' $msg[supprimer] ' onClick=\"if(confirm_delete()) this.form.act.value='suppr_request';\" />
	</div>
</div>
<div class='row'></div>
</form>
";

?>