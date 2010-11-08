<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: requests.tpl.php,v 1.6 2009-06-25 16:33:21 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");


// $req_add_form : template creation requete
$req_add_form = "
<form class='form-".$current_module."' id='req_modif' name='req_modif' method='post' action='./admin.php?categ=proc&sub=req&action=update'>
	<h3>!!form_title!!</h3>
	<!--    Contenu du form    -->
	<div class='form-contenu'>
		<div class='row'>
			<div class='left'>
				<label class='etiquette' for='req_name'>".htmlentities($msg['req_name_lbl'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='right'>
				<label class='etiquette' for='req_name'>".htmlentities($msg['proc_clas_proc'], ENT_QUOTES, $charset)."</label>
			</div>
		</div>
		<div class='row'>
			<div class='left'>
				<input type='text' id='req_name' name='req_name' value='!!req_name!!' maxlength='255' class='saisie-80em'/>
			</div>
			<div class='right'>
				!!classement!!
			</div>
		</div>
		
		<div class='row'>
			<div class='colonne5'>
				<label class='etiquette' for='req_type'>".htmlentities($msg['req_type_lbl'], ENT_QUOTES, $charset)."</label>
			</div>
			<div id='req_univ1' class='colonne_suite' style='display:none';>
				<label class='etiquette' for='req_univ'>".htmlentities($msg['req_univ_lbl'], ENT_QUOTES, $charset)."</label>
			</div>
		</div>
		<div class='row'>
			<div class='colonne5'>
				!!req_type!!		
			</div>
			<div id='req_univ2' class='colonne_suite' style='display:none';>
				!!req_univ!!
				<input type='button' id='req_bt_sui' name='req_bt_sui' class='bouton_small' value='".htmlentities($msg['req_bt_sui'],ENT_QUOTES,$charset)."' onClick=\"req_openFrame(event);\"/>
			</div>
		</div>
		<div class='row'>
			<label class='etiquette' for='req_code'>".htmlentities($msg['req_code_lbl'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<textarea id='req_code' name='req_code' cols='80' rows='8'>!!req_code!!</textarea>
		</div>
		
		<div class='row'>
			<label class='etiquette' for='req_comm'>".htmlentities($msg['req_comm_lbl'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<input type='text' id='req_comm' name='req_comm' class='saisie-80em' value='!!req_comm!!' maxlength='255'/>
		</div>
		
		<div class='row'>
			<label class='etiquette'>".htmlentities($msg['req_auth_lbl'], ENT_QUOTES, $charset)."</label>
			<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
			<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
		</div>
		<div class='row'>
			!!req_auth!!
		</div>
	</div>
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg['76']."' onclick=\"document.location='./admin.php?categ=proc'; \"/>
			<input type='submit' class='bouton' value='".$msg['77']."' onclick=\"return test_form(this.form); \"/>
		</div>
		<div class='right'>
			<!-- bt_sup -->
		</div>
	</div>
	<div class='row'></div>
</form>
<script type='text/javascript' src='./javascript/select.js'></script>
<script type='text/javascript' src='./javascript/requests.js'></script>
<script type='text/javascript'>
function test_form(form) {
	if(form.req_name.value.length == 0) {
		alert('".addslashes($msg[702])."');
		form.req_name.focus();
		return false;
	}
	if(form.req_code.value.length == 0) {
		alert('".addslashes($msg[703])."');
		form.req_code.focus();
		return false;
	}
	return true;
}
</script>";


$req_auth = "
	<span class='usercheckbox'>
		<input type='checkbox' class='checkbox' id='user_aut[!!user_id!!]' name='user_aut[!!user_id!!]' !!checked!! value='!!user_id!!' />
		<label for='user_aut[!!user_id!!]' >!!user_name!!</label>
	</span>&nbsp;
";


$frame_req_add_form="
<form class='form-".$current_module."' id='req_frame_modif' name='req_frame_modif' method='post'>
	<div class='row' id='row_2'>
		<div id='req_tree_div' name='req_tree_div' style='float:left;width:20%;height:99%;border:1px #000 solid;overflow:scroll;position:fixed;'>
			<!--<div id='req_free_tree' style='width:100%'></div>-->
			<div id='req_fiel_tree' style='width:100%'></div>
			<div id='req_func_tree' style='width:100%'></div>
			<div id='req_subr_tree' style='width:100%'></div>
		</div>
		<div style='float:right;width:79%;'>
			<div class='left'>
				<h3>!!form_title!!</h3>
			</div>
			<div class='right'>
				<a href='#' onclick=\"parent.req_hideFrame();return false;\">X</a>
			</div>
			<br /><br />
			<!--    Contenu du form    -->
			<div class='form-contenu'>

<!-- cadre requete -->		
				<div class='row'>
					<table class='req_cell'>
						<tbody id='req_tab'>
							<!-- req_tab_header -->
							<!-- req_tab_lines -->
						</tbody>
					</table>
				</div>
<!--
				<div class='row'>
					<input type='button' id='req_bt_add_line' class='bouton_small' value='".htmlentities($msg['req_bt_add_line'],ENT_QUOTES,$charset)."' onClick=\"req_addLine();return false;\"/>
				</div>
-->				
				<div class='row'>&nbsp;</div>
		
				<div class='row'>
					<div class='colonne60'>
						<table class='req_cell'>
							<tbody id='req_joi'>
								<!-- joi_tab_header -->
								<!-- joi_tab_lines -->
								<tr></tr>
							</tbody>
						</table>
					</div>
					<div class='colonne40'>
						<div class='colonne2'>&nbsp;</div>
						<div class='colonne2'>
							<table class='req_cell' >
								<tbody id='req_lim' >
									<!-- lim_tab_header -->
									<!-- lim_tab_lines -->
								</tbody>
							</table>
						</div>						
					</div>
				</div>

<!-- fin cadre requete -->

				<div id='spy' class='row'>&nbsp;</row>
			</div>
		</div>
		<div class='left'>
			<input type='button' class='bouton_small' onclick=\"req_submitFrame(0);\" value='".htmlentities($msg['req_bt_view'],ENT_QUOTES,$charset)."'/>
			<input type='button' class='bouton_small' onclick=\"req_submitFrame(1);\" value='".htmlentities($msg['77'],ENT_QUOTES,$charset)."'/>
		</div>
		<div class='row'>&nbsp;</div>
	</div>
</form>
<script type='text/javascript'>
	var msg_move='".addslashes($msg['req_move'])."'; 
</script>
<script type='text/javascript' src='../../javascript/dtree.js'></script>
<script type='text/javascript' src='../../javascript/select.js'></script>
<script type='text/javascript' src='../../javascript/requests_frame.js'></script>
<!-- req_tree_script -->
<script type='text/javascript'>window.onload=function(){init_drag();}</script>";


$req_tab_header_select="
<tr>
	<th id='dz_DA' recept='yes' recepttype='celldropzone' highlight='cell_highlight' downlight='cell_downlight'>
		<img src='../../images/add.png' class='add_bt' onClick=\"req_addLine();\"/>".htmlentities($msg['req_th_don'], ENT_QUOTES, $charset)."
	</th>
	<th id='dz_FI' recept='yes' recepttype='celldropzone' highlight='cell_highlight' downlight='cell_downlight'>
		<img src='../../images/add.png' class='add_bt' onClick=\"req_addLine();\"/>".htmlentities($msg['req_th_fil'], ENT_QUOTES, $charset)."
	</th>
	<th class='col_ali'>".htmlentities($msg['req_th_ali'], ENT_QUOTES, $charset)."</th>			
	<th class='col_vis'>".htmlentities($msg['req_th_vis'], ENT_QUOTES, $charset)."</th>			
	<th class='col_grp'>".htmlentities($msg['req_th_grp'], ENT_QUOTES, $charset)."</th>			
	<th class='col_tri'>".htmlentities($msg['req_th_tri'], ENT_QUOTES, $charset)."</th>			
	<th class='col_act'>".htmlentities($msg['req_th_act'], ENT_QUOTES, $charset)."</th>			
</tr>";

$joi_tab_header_select="
<tr>		
	<th>".htmlentities($msg['req_th_tabg'], ENT_QUOTES, $charset)."</th>
	<th>".htmlentities($msg['req_th_tabd'], ENT_QUOTES, $charset)."</th>
	<th>".htmlentities($msg['req_th_joi'], ENT_QUOTES, $charset)."</th>
</tr>";

$joi_tab_line_select="
<tr id='!!R_rel!!' style='display:none;' >		
	<td>!!tg_desc!!</td>
	<td>!!td_desc!!</td>
	<td>
		<input type='radio' id='!!N_rel!!_L' name='!!N_rel!!' value='L' !!L_sel!! title='".htmlentities($msg['req_joi_ll'], ENT_QUOTES, $charset)."'/>
		<label for='!!N_rel!!_L' title='".htmlentities($msg['req_joi_ll'], ENT_QUOTES, $charset)."'>".htmlentities($msg['req_joi_l'], ENT_QUOTES, $charset)."</label>
		<input type='radio' id='!!N_rel!!_S' name='!!N_rel!!' value='S' !!S_sel!! title='".htmlentities($msg['req_joi_ls'], ENT_QUOTES, $charset)."'/>
		<label for='!!N_rel!!_S' title='".htmlentities($msg['req_joi_ls'], ENT_QUOTES, $charset)."'>".htmlentities($msg['req_joi_s'], ENT_QUOTES, $charset)."</label>
		<input type='radio' id='!!N_rel!!_R' name='!!N_rel!!' value='R' !!R_sel!! title='".htmlentities($msg['req_joi_lr'], ENT_QUOTES, $charset)."'/>
		<label for='!!N_rel!!_R' title='".htmlentities($msg['req_joi_lr'], ENT_QUOTES, $charset)."'>".htmlentities($msg['req_joi_r'], ENT_QUOTES, $charset)."</label>
		&nbsp;&nbsp;
		<input type='radio' id='!!N_rel!!_N' name='!!N_rel!!' value='N' !!N_sel!! title='".htmlentities($msg['req_joi_ln'], ENT_QUOTES, $charset)."'/>
		<label for='!!N_rel!!_N' title='".htmlentities($msg['req_joi_ln'], ENT_QUOTES, $charset)."'>".htmlentities($msg['req_joi_n'], ENT_QUOTES, $charset)."</label>
		</td>
</tr>";

$lim_tab_header_select="
<tr>		
	<th>".htmlentities($msg['req_th_deb'], ENT_QUOTES, $charset)."</th>
	<th>".htmlentities($msg['req_th_qte'], ENT_QUOTES, $charset)."</th>
</tr>";

$lim_tab_line_select="
<tr>
	<td>
		<input type='text' id='LI_B' name='LI_B' class='in_cell_nb' value=''/>
	</td>
	<td>
		<input type='text' id='LI_Q' name='LI_Q' class='in_cell_nb' value=''/>
	</td>
<tr>";


$req_tab_header_insert="
<tr>
	<th id='dz_DA' recept='yes' recepttype='celldropzone' highlight='cell_highlight' downlight='cell_downlight'>
		<img src='../../images/add.png' class='add_bt' onClick=\"req_addLine();\"/>".htmlentities($msg['req_th_don'], ENT_QUOTES, $charset)."</th>
	<th id='dz_VA' recept='yes' recepttype='celldropzone' highlight='cell_highlight' downlight='cell_downlight'>
		<img src='../../images/add.png' class='add_bt' onClick=\"req_addLine();\"/>".htmlentities($msg['req_th_val'], ENT_QUOTES, $charset)."</th>			
	<th class='col_obl'>".htmlentities($msg['req_th_obl'], ENT_QUOTES, $charset)."</th>			
	<th class='col_act'>".htmlentities($msg['req_th_act'], ENT_QUOTES, $charset)."</th>			
</tr>";

$req_tab_header_update="
<tr>
	<th id='dz_DA' recept='yes' recepttype='celldropzone' highlight='cell_highlight' downlight='cell_downlight'>
		<img src='../../images/add.png' class='add_bt' onClick=\"req_addLine();\"/>".htmlentities($msg['req_th_don'], ENT_QUOTES, $charset)."</th>
	<th id='dz_VA' recept='yes' recepttype='celldropzone' highlight='cell_highlight' downlight='cell_downlight'>
		<img src='../../images/add.png' class='add_bt' onClick=\"req_addLine();\"/>".htmlentities($msg['req_th_val'], ENT_QUOTES, $charset)."</th>			
	<th id='dz_FI' recept='yes' recepttype='celldropzone' highlight='cell_highlight' downlight='cell_downlight'>
		<img src='../../images/add.png' class='add_bt' onClick=\"req_addLine();\"/>".htmlentities($msg['req_th_fil'], ENT_QUOTES, $charset)."</th>
	<th class='col_act'>".htmlentities($msg['req_th_act'], ENT_QUOTES, $charset)."</th>			
</tr>";

$req_tab_header_delete="
<tr>
	<th id='dz_DA' recept='yes' recepttype='celldropzone' highlight='cell_highlight' downlight='cell_downlight'>
		<img src='../../images/add.png' class='add_bt' onClick=\"req_addLine();\"/>".htmlentities($msg['req_th_don'], ENT_QUOTES, $charset)."</th>
	<th id='dz_FI' recept='yes' recepttype='celldropzone' highlight='cell_highlight' downlight='cell_downlight'>
		<img src='../../images/add.png' class='add_bt' onClick=\"req_addLine();\"/>".htmlentities($msg['req_th_fil'], ENT_QUOTES, $charset)."</th>
	<th class='col_act'>".htmlentities($msg['req_th_act'], ENT_QUOTES, $charset)."</th>			
</tr>";
		

/*
$joi_tab_header_insert="
<tr>		
	<th>".htmlentities($msg['req_th_tab'], ENT_QUOTES, $charset)."</th>
</tr>";

$joi_tab_header_update="
<tr>		
	<th>".htmlentities($msg['req_th_tabg'], ENT_QUOTES, $charset)."</th>
	<th>".htmlentities($msg['req_th_tabd'], ENT_QUOTES, $charset)."</th>
	<th>".htmlentities($msg['req_th_joi'], ENT_QUOTES, $charset)."</th>
</tr>";

$joi_tab_header_delete="
<tr>		
	<th>".htmlentities($msg['req_th_tab'], ENT_QUOTES, $charset)."</th>
</tr>";

$lim_tab_header_delete="
<tr>		
	<th>".htmlentities($msg['req_th_qte'], ENT_QUOTES, $charset)."</th>
</tr>";

$lim_tab_line_delete="
<tr>
	<td>
		<input type='text' name='LI_Q' class='in_cell_nb' value='1'/>
	</td>
<tr>";

?>