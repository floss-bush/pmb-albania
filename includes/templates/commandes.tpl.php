<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: commandes.tpl.php,v 1.51 2009-06-03 06:06:35 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");


$cdelist_form = "
<form class='form-$current_module' id='act_list_form' name='act_list_form' method='post' action=\"\" >
	<div class='form-contenu'>
		<table width='100%' ><tbody>
			<tr>
			<th>".htmlentities($msg['38'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_ach_fou2'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_cde_date_cde'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_cde_date_ech'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_statut'], ENT_QUOTES, $charset)."</th>	
			<th class='act_cell_chkbox' >&nbsp;</th>
			<!-- chk_th -->
			</tr>
			<!-- cde_list -->
		</tbody></table>
	</div>
	<div class='row'>
		<div class='left'></div>
		<div class='right'><!-- bt_chk --></div>
	</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='left'><!-- bt_list --></div>
		<div class='right'><!-- bt_sup --></div>
	</div>
	<div class='row'></div>
</form>
<!-- script -->
<br />
<div class='form' >
	<!-- nav_bar -->
</div>
";


$cdelist_bt_chk ="<input type='button' id='bt_chk' class='bouton_small' value='$msg[acquisition_sug_checkAll]' onClick=\"checkAll('act_list_form', 'chk', check); return false;\" />";
$cdelist_bt_supChk = "<input type='button' class='bouton_small' value='$msg[63]' onClick=\"supChk();\" />";

$cdelist_bt_valid="<input type='button' class='bouton_small' value='".$msg['acquisition_act_bt_val']."' onClick=\"cdelist_valid();\" />";
$cdelist_bt_sold="<input type='button' class='bouton_small' value='".$msg['acquisition_cde_bt_sol']."' onClick=\"cdelist_sold();\" />";
$cdelist_bt_arc="<input type='button' class='bouton_small' value='".$msg['acquisition_act_bt_arc']."' onClick=\"cdelist_arc();\" />";
$cdelist_bt_delete="<input type='button' class='bouton_small' value='".$msg['63']."' onClick=\"cdelist_delete();\" />";


$cdelist_script = "
<script type='text/javascript'>

	var check = true;

	//Coche et decoche les elements de la liste
	function checkAll(the_form, the_objet, do_check) {
	
		var elts = document.forms[the_form].elements[the_objet+'[]'];
		var elts_cnt  = (typeof(elts.length) != 'undefined')
	              ? elts.length
	              : 0;
	
		if (elts_cnt) {
			for (var i = 0; i < elts_cnt; i++) {
				elts[i].checked = do_check;
			} 
		} else {
			elts.checked = do_check;
		}
		if (check == true) {
			check = false;
			document.getElementById('bt_chk').value ='".$msg['acquisition_sug_uncheckAll']."';
		} else {
			check = true;
			document.getElementById('bt_chk').value ='".$msg['acquisition_sug_checkAll']."';	
		}
		return true;
	}
	
	
	function cdelist_valid() {
		r = confirm(\"".$msg['acquisition_cdelist_val']."\");
		if (r) {
			document.forms['act_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=cmde&action=list_valid&id_bibli='+document.getElementById('id_bibli').value);
			document.forms['act_list_form'].submit();
			return true;	
		}
		return false;
	}
	

	function cdelist_delete() {
		r = confirm(\"".$msg['acquisition_cdelist_sup']."\");
		if (r) {
			document.forms['act_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=cmde&action=list_delete&id_bibli='+document.getElementById('id_bibli').value);
			document.forms['act_list_form'].submit();
			return true;	
		}
		return false;
	}
	

	function cdelist_sold() {
		r = confirm(\"".$msg['acquisition_cdelist_sol']."\");
		if (r) {
			document.forms['act_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=cmde&action=list_sold&id_bibli='+document.getElementById('id_bibli').value);
			document.forms['act_list_form'].submit();
			return true;	
		}
		return false;
	}
	

	function cdelist_arc() {
		r = confirm(\"".$msg['acquisition_cdelist_arc']."\");
		if (r) {
			document.forms['act_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=cmde&action=list_arc&id_bibli='+document.getElementById('id_bibli').value);
			document.forms['act_list_form'].submit();
			return true;	
		}
		return false;
	}
	
	
</script>
";


//	------------------------------------------------------------------------------
//	$modif_cde_form : template de création/modification pour les commandes modifiables (non validées)
//	------------------------------------------------------------------------------
$modif_cde_form = "
<form class='form-".$current_module."' id='act_modif' name='act_modif' method='post' action=\"\">
	<h3>!!form_title!!</h3>
	<div class='row'></div>
	<!--    Contenu du form    -->
	<div class='form-contenu'>

		<div class='row'>
			<div class='colonne5'>
				<label class='etiquette'>".htmlentities($msg['acquisition_coord_lib'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne_suite'>!!lib_bibli!!</div>
		</div>
		
		<div class='row'>
			<div class='colonne5'>
				<label class='etiquette'>".htmlentities($msg['acquisition_budg_exer'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne_suite'>
				<input type='hidden' id='id_exer' name='id_exer' value='!!id_exer!!' /> 
				!!lib_exer!!
			</div>
		</div>
		
		<div class='row'></div>
		<hr />	
	
		<div class='row'>
			<div class='colonne2'>
				<div class='colonne3' >			
					<label class='etiquette'>".htmlentities($msg['653'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne3'>
					<label class='etiquette'>".htmlentities($msg['38'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne_suite'>
					<label class='etiquette'>".htmlentities($msg['acquisition_statut'], ENT_QUOTES, $charset)."</label>
				</div>
			</div>
			<div class='colonne2'>
				<div class='colonne5'>
		    		<label class='etiquette'>".htmlentities($msg['acquisition_ach_fou2'], ENT_QUOTES, $charset)."</label>&nbsp;
				</div>
				<div class='colonne_suite'>
					<input type='text' id='lib_fou' name='lib_fou' tabindex='1' value='!!lib_fou!!' class='saisie-30emr' onchange=\"openPopUp('./select.php?what=fournisseur&caller=act_modif&param1=id_fou&param2=lib_fou&param3=adr_fou&id_bibli=!!id_bibli!!&deb_rech='+escape(this.form.lib_fou.value), 'select_fournisseur', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes'); \" />
					<input type='button' class='bouton_small' style='width:20px;' tabindex='1' value='".$msg['parcourir']."' onclick=\"openPopUp('./select.php?what=fournisseur&caller=act_modif&param1=id_fou&param2=lib_fou&param3=adr_fou&id_bibli=!!id_bibli!!&deb_rech='+escape(this.form.lib_fou.value), 'select_fournisseur', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes'); \" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne2'>
				<div class='colonne3'>!!date_cre!!</div>
				<div class='colonne3'>
					<input type='text' id='num_cde' name='num_cde' value='!!numero!!' class='saisie-10em' />
				</div>
				<div class='colonne_suite'>
					<!-- sel_statut -->
				</div>
			</div>
			<div class='colonne2'>
				<img id='adr_fou_Img' name='adr_fou_Img' src='./images/plus.gif' class='img_plus' onclick=\"javascript:expandBase('adr_fou_', true);\"/>
		    	<label class='etiquette'>".htmlentities($msg['acquisition_adr_fou'], ENT_QUOTES, $charset)."</label>
			</div>
		</div>

		<div class='row' id='adr_fou_Child' style='display:none;'>
			<div class='colonne2'>&nbsp;</div>
			<div class='colonne2' >
				<div class='colonne2' style='margin-left:30px'>					
					<textarea id='adr_fou' name='adr_fou' class='saisie-30emd' readonly='readonly' cols='50' rows='4' wrap='virtual'>!!adr_fou!!</textarea>
				</div>
			</div>
		</div>

		<div class='row'></div>
		<hr />

		<div class='row'>
			<div class='colonne2'>
				<img id='adr_bib_Img' src='./images/plus.gif' class='img_plus' onclick=\"javascript:expandBase('adr_bib_', true);\" />
	    		<label class='etiquette'>".htmlentities($msg['acquisition_adr_liv'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne2'>
				<label class='etiquette'>".htmlentities($msg['acquisition_adr_fac'], ENT_QUOTES, $charset)."</label>
			</div>
		</div>

		<div class='row' id='adr_bib_Child' name='adr_bib_Child' style='display:none;'>
			<div class='colonne2'>
				<div class='colonne' style='margin-left:30px'>					
					<textarea id='adr_liv' name='adr_liv' class='saisie-30emr' readonly='readonly' cols='50' rows='4' wrap='virtual'>!!adr_liv!!</textarea>&nbsp;
				</div>
				<div class='colonne_suite'>
					<input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['parcourir']."' onclick=\"openPopUp('./select.php?what=coord&caller=act_modif&param1=id_adr_liv&param2=adr_liv&id_bibli=!!id_bibli!!', 'select_adresse', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes'); \" />
					<input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['raz']."' onclick=\"document.getElementById('id_adr_liv').value='0';document.getElementById('adr_liv').value='';\" />
				</div>
			</div>
			<div class='colonne2'>
				<div class='colonne' style='margin-left:30px'>
					<textarea id='adr_fac' name='adr_fac' class='saisie-30emr' readonly='readonly' cols='50' rows='4' wrap='virtual'>!!adr_fac!!</textarea>&nbsp;
				</div>
				<div class='colonne_suite'>
					<input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['parcourir']."' onclick=\"openPopUp('./select.php?what=coord&caller=act_modif&param1=id_adr_fac&param2=adr_fac&id_bibli=!!id_bibli!!', 'select_adresse', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes'); \" />
					<input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['raz']."' onclick=\"document.getElementById('id_adr_fac').value='0';document.getElementById('adr_fac').value='';\" />
				</div>
			</div>
		</div>

		<div class='row'>
			<img id='comment_Img' src='./images/plus.gif' class='img_plus' onclick=\"javascript:expandBase('comment_', true);\"/>
    		<label class='etiquette'>".htmlentities($msg['acquisition_commentaires'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row' style='margin-left:30px'>
			<textarea  id='comment_Child' name='comment' tabindex='1' class='saisie-80em' style='display:none;' cols='62' rows='4' wrap='virtual'>!!comment!!</textarea>
		</div>
		
		<div class='row'>
			<img id='comment_i_Img' src='./images/plus.gif' class='img_plus' onclick=\"javascript:expandBase('comment_i_', true);\"/>
    		<label class='etiquette'>".htmlentities($msg['acquisition_commentaires_i'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row' style='margin-left:30px'>
			<textarea  id='comment_i_Child' name='comment_i' tabindex='1' class='saisie-80em' style='display:none;' cols='62' rows='4' wrap='virtual'>!!comment_i!!</textarea>
		</div>
		
		<div class='row'></div>
		<hr />

		<div class='row'>
			<div class='colonne3'>
				<table style='background-color:transparent;top:0px;'>
					<tr>
						<td width='50%' >
							<label class='etiquette'>".htmlentities($msg['acquisition_act_num_dev'], ENT_QUOTES, $charset)."</label>
						</td>
						<td >
							<span class='current'>!!lien_dev!!</span>								
						</td>
					</tr>
				</table>
			</div>
			<div class='colonne3'>
				<table style='background-color:transparent;'>
					<tr>
						<td valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_dev_ref_fou'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'>
							<input type='hidden' id='id_dev' name='id_dev' value='!!id_dev!!' />
							<input type='text' id='ref' name='ref' tabindex='1' class='saisie-1Oem' value='!!ref!!' />
						</td>
					</tr>
					<tr>
						<td valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_fac_date_pay'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'><!-- sel_date_pay --></td>
					</tr>
					<tr>
						<td valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_fac_num_pay'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'>
							<input type='text' id='num_pay' name='num_pay' tabindex='1' class='saisie-10em' value='!!num_pay!!' />
						</td>
					</tr>
				</table>
			</div>
			<div class='colonne3'>
				<table style='background-color:transparent;'>
					<tr>
						<td width='50%' valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_cde_date_liv'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'>
							<!-- sel_date_liv -->
						</td>
					</tr>
				</table>
			</div>
			<input type='hidden' id='id_bibli' name='id_bibli' value='!!id_bibli!!' />
			<input type='hidden' id='act_type' name='act_type' value='".TYP_ACT_CDE."' />
			<input type='hidden' id='id_cde' name='id_cde' value='!!id_cde!!' /> 
			<input type='hidden' id='id_fou' name='id_fou' value='!!id_fou!!' />
			<input type='hidden' id='id_adr_fou' name='id_adr_fou' value='!!id_adr_fou!!' />
			<input type='hidden' id='id_adr_liv' name='id_adr_liv' value='!!id_adr_liv!!' />
			<input type='hidden' id='id_adr_fac' name='id_adr_fac' value='!!id_adr_fac!!' />
			<input type='hidden' id='gestion_tva' name='gestion_tva' value='".$acquisition_gestion_tva."' />
		</div>
		
		<div class='row'>
			<table class='act_cell' >
				<tbody id='act_tab' >
					<tr>
						<th width='12%'>".htmlentities($msg['acquisition_act_tab_code'], ENT_QUOTES, $charset)."</th>
						<th width='33%'>".htmlentities($msg['acquisition_act_tab_lib'], ENT_QUOTES, $charset)."</th>
						<th width='3%'>".htmlentities($msg['acquisition_act_tab_qte'], ENT_QUOTES, $charset)."</th>";				
switch ($acquisition_gestion_tva) {
	case '1' :
		$modif_cde_form.= "
						<th width='5%'>".htmlentities($msg['acquisition_act_tab_priht'], ENT_QUOTES, $charset)."</th>
						<th width='25%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset)."<br />".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)." / ".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
		break;
	case '2' :
		$modif_cde_form.= "
						<th width='5%'>".htmlentities($msg['acquisition_act_tab_prittc'], ENT_QUOTES, $charset)."</th>
						<th width='25%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset)."<br />".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)." / ".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
		break;	
	default :
		$modif_cde_form.= "
						<th width='5%'>".htmlentities($msg['acquisition_act_tab_prittc'], ENT_QUOTES, $charset)."</th>
						<th width='25%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset)."<br />".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
		break;
}
$modif_cde_form.="	
						<th width='20%'>".htmlentities($msg['acquisition_act_tab_bud'], ENT_QUOTES, $charset)."</th>
						<th width='2%'></th>
					</tr>
					<!-- lignes -->
				</tbody>
			</table>
		</div>
	
		<div class='row'>
			<div class='left' >
				<input type='button' id='bt_add_line' tabindex='1' class='bouton_small' value='".$msg['acquisition_act_add_lig']."' onclick=\"act_addLine();\" />
			</div>
			<div class='right'>
				<input type='button' tabindex='1' class='bouton_small' value='".$msg['acquisition_del_chk_lig']."' onclick=\"act_delLines();\" />
				<input type='button' class='bouton_small' style='width:20px;' tabindex='1' value='+' onclick='act_switchCheck();' />
			</div>
		</div>
		
		<div class='row'></div>
		<hr />
		
		<div class='row'>
			<div class='left'>
				<input type='button' tabindex='1' class='bouton_small' value='".$msg['acquisition_calc']."' onclick=\"act_calc();\" />";
if ($acquisition_gestion_tva) $modif_cde_form.= "
				<label class='etiquette'>".htmlentities($msg['acquisition_total_ht'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_ht' name='tot_ht' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='0.00' />
				<label class='etiquette'>".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_tva' name='tot_tva' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='0.00' />";

$modif_cde_form.= "	
				<label class='etiquette'>".htmlentities($msg['acquisition_total_ttc'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_ttc' name='tot_ttc' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='0.00' />
				<label class='etiquette'>".htmlentities($msg['acquisition_devise'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='devise' name='devise' class='saisie-5em' style='text-align:right;' value='!!devise!!' />
			</div>
			<div class='right'>
				<label class='etiquette'>".htmlentities($msg['acquisition_tot_expl'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_expl' name='tot_expl' class='saisie-5emd' style='text-align:right;' readonly='readonly' value='0' />
			</div>
		</div>
		
		<div class='row'></div>
		
	</div>
	
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg['76']."' onclick=\"document.location='./acquisition.php?categ=ach&sub=cmde&action=list&id_bibli=!!id_bibli!!' \" />
			<!-- bouton_enr -->
			<!-- bouton_val -->
			<!-- bouton_dup -->
			<!-- bouton_imp -->
			<!-- bouton_audit -->
		</div>
		<div class='right'>
			<!-- bouton_sup -->
		</div>
	</div>
	
	<div class='row'></div>

</form>
<br /><br />
<script type='text/javascript' src='./javascript/tablist.js'></script>
<script type='text/javascript' src='./javascript/actes.js'></script>
<script type='text/javascript'>	
	
	document.getElementById('statut').value='!!statut!!';
	
	var msg_parcourir='".addslashes($msg['parcourir'])."'; 
	var msg_raz='".addslashes($msg['raz'])."'; 
	var msg_no_fou = '".addslashes($msg['acquisition_cde_fou_err'])."';
	var msg_act_vide='".addslashes($msg['acquisition_cde_vid'])."';
	var acquisition_budget = '".$acquisition_budget."';
	var msg_no_bud = '".addslashes($msg['acquisition_act_bud_err'])."';
	var act_nblines='!!act_nblines!!';
	var act_curline='!!act_nblines!!';
	if(act_nblines>0) {
		act_calc();
	} else {
		act_addLine();
	}
	
</script>
<!-- jscript -->";


//	------------------------------------------------------------------------------
//	template de création/modification pour les lignes de commande
//	------------------------------------------------------------------------------
$modif_cde_row_form = "
<tr id='R_!!no!!'>
	<td>
		<input type='text' id='code[!!no!!]' name='code[!!no!!]' tabindex='1' class='in_cell' value='!!code!!' /><input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['parcourir']."' onclick=\"act_getCode(this);\" /><input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['raz']."' onclick=\"act_delCode(this);\" />
	</td>	
	<td>
		<textarea id='lib[!!no!!]' name='lib[!!no!!]' tabindex='1' class='in_cell' rows='3' wrap='virtual'>!!lib!!</textarea>
	</td>
	<td>
		<input type='text' id='qte[!!no!!]' name='qte[!!no!!]' tabindex='1' class='in_cell_nb' value='!!qte!!' />
	</td>
	<td>
		<input type='text' id='prix[!!no!!]' name='prix[!!no!!]' tabindex='1' class='in_cell_nb' value='!!prix!!' />
	</td>
	<td>
		<input type='hidden' id='typ[!!no!!]' name='typ[!!no!!]' value='!!typ!!' />
		<input type='text' id='lib_typ[!!no!!]' name='lib_typ[!!no!!]' tabindex='1' class='in_cell_ro' value='!!lib_typ!!' /><input type='button' tabindex='1' class='bouton_small' style='width:20px' value='".$msg['parcourir']."' onclick=\"act_getType(this);\" /><input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['raz']."' onclick=\"act_delType(this);\" />";
if ($acquisition_gestion_tva) {
	$modif_cde_row_form.= "&nbsp;<input type='text' id='tva[!!no!!]' name='tva[!!no!!]' tabindex='1' class='in_cell_nb' style='width:20%;' value='!!tva!!' />&nbsp;%";
} 	
$modif_cde_row_form.= "&nbsp;<input type='text' id='rem[!!no!!]' name='rem[!!no!!]' tabindex='1' class='in_cell_nb' style='width:20%;' value='!!rem!!' />&nbsp;%		
	</td>
	<td>
		<input type='hidden' id='rub[!!no!!]' name='rub[!!no!!]' value='!!rub!!' />
		<input type='text' id='lib_rub[!!no!!]' name='lib_rub[!!no!!]' tabindex='1' class='in_cell_ro' value='!!lib_rub!!' /><input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['parcourir']."' onclick=\"act_getRubrique(this);\" /><input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['raz']."' onclick=\"act_delRubrique(this);\" />
	</td>	
	<td>
		<input type='checkbox' id='chk[!!no!!]' name='chk[!!no!!]' tabindex='1' value='1' />
		<input type='hidden' id='id_sug[!!no!!]' name='id_sug[!!no!!]' value='!!id_sug!!' /> 
		<input type='hidden' id='id_lig[!!no!!]' name='id_lig[!!no!!]' value='!!id_lig!!' /> 
		<input type='hidden' id='typ_lig[!!no!!]' name='typ_lig[!!no!!]' value='!!typ_lig!!' /> 	
		<input type='hidden' id='id_prod[!!no!!]' name='id_prod[!!no!!]' value='!!id_prod!!' />
	</td>
</tr>";

//Date paiement modifiable
$sel_date_pay_mod ="<input type='hidden' id='date_pay' name='date_pay' value='!!date_pay!!' />
			<input type='button' id='date_pay_lib' class='bouton_small' value='!!date_pay_lib!!' onclick=\"openPopUp('./select.php?what=calendrier&caller='+this.form.name+'&date_caller=&param1=date_pay&param2=date_pay_lib&auto_submit=NO&date_anterieure=YES', 'date_date_test', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" />
			<input type='button' class='bouton_small' style='width:20px;' value='".$msg['raz']."' onclick=\"this.form.elements['date_pay_lib'].value='".$msg['parperso_nodate']."'; this.form.elements['date_pay'].value='';\" />";

//Date livraison modifiable
$sel_date_liv_mod ="<input type='hidden' id='date_liv' name='date_liv' value='!!date_liv!!' />
			<input type='button' id='date_liv_lib' class='bouton_small' value='!!date_liv_lib!!' onclick=\"openPopUp('./select.php?what=calendrier&caller='+this.form.name+'&date_caller=&param1=date_liv&param2=date_liv_lib&auto_submit=NO&date_anterieure=YES', 'date_date_test', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" />
			<input type='button' class='bouton_small' style='width:20px;' value='".$msg['raz']."' onclick=\"this.form.elements['date_liv_lib'].value='".$msg['parperso_nodate']."'; this.form.elements['date_liv'].value='';\" />";
//Date livraison non modifiable
$sel_date_liv_fix ="<input type='hidden' id='date_liv' name='date_liv' value='!!date_liv!!' />!!date_liv_lib!!";


$bt_enr = "<input type='button' class='bouton' value='".$msg['77']."' 
			onclick=\" 
				r=act_verif();
				if (!r) return false;
				act_calc(); 
				document.forms['act_modif'].setAttribute('action', 'acquisition.php?categ=ach&sub=cmde&action=update'); 
				document.forms['act_modif'].submit();  \" />";

$bt_val = "<input type='button' class='bouton' value='".$msg['acquisition_act_bt_val']."' 
			onclick=\"
				r=act_verif();
				if (!r) return false;
				act_calc(); 
				r=confirm('".addslashes($msg['acquisition_cde_val'])."');
				if (!r) return false; 
				document.forms['act_modif'].setAttribute('action', 'acquisition.php?categ=ach&sub=cmde&action=valid'); 
				document.forms['act_modif'].submit(); \" />";
			
$bt_dup = "<input type='button' class='bouton' value='".$msg['acquisition_dup']."' 
			onclick=\"document.forms['act_modif'].setAttribute('action', 'acquisition.php?categ=ach&sub=cmde&action=duplicate'); 
				document.forms['act_modif'].submit(); \" />";

$bt_sup = "<input type='button' class='bouton' value='".$msg['63']."' 
			onclick=\"if (document.getElementById('id_cde').value == 0) {return false; } 
				r = confirm('".addslashes($msg['acquisition_cde_sup'])."');
				if(r){
					document.forms['act_modif'].setAttribute('action', './acquisition.php?categ=ach&sub=cmde&action=delete'); 
					document.forms['act_modif'].submit();} \" />";
			 
$bt_arc = "<input type='button' class='bouton' value='".addslashes($msg['acquisition_act_bt_arc'])."' 
			onclick=\"document.forms['act_modif'].setAttribute('action', './acquisition.php?categ=ach&sub=cmde&action=arc'); 
				document.forms['act_modif'].submit(); \" />";

$bt_imp = "<input type='button' class='bouton' value='".$msg['imprimer']."' title='".$msg['imprimer']."' onclick=\"openPopUp('./pdf.php?pdfdoc=cmde&id_cde=".$id_cde."' ,'print_PDF', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" />"; 


//	------------------------------------------------------------------------------
//	$valid_cde_form : template de visualisation pour les commandes validées non modifiables
//	------------------------------------------------------------------------------
$valid_cde_form = "
<form class='form-".$current_module."' id='act_modif' name='act_modif' method='post' action=\"\">
	<h3>!!form_title!!</h3>
	<div class='row'></div>
	<!--    Contenu du form    -->
	<div class='form-contenu'>
	
		<div class='row'>
			<div class='colonne5'>
				<label class='etiquette'>".htmlentities($msg['acquisition_coord_lib'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne_suite'>!!lib_bibli!!</div>
		</div>
		
		<div class='row'>
			<div class='colonne5'>
				<label class='etiquette'>".htmlentities($msg['acquisition_budg_exer'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne_suite'>
				<input type='hidden' id='id_exer' name='id_exer' value='!!id_exer!!' /> 
				!!lib_exer!!
			</div>
		</div>
		
		<div class='row'></div>
		<hr />

		<div class='row'>
			<div class='colonne2'>
				<div class='colonne3' >			
					<label class='etiquette'>".htmlentities($msg['653'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne3'>
					<label class='etiquette'>".htmlentities($msg['38'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne_suite'>
					<label class='etiquette'>".$msg['acquisition_statut']."</label>
				</div>
			</div>
			<div class='colonne2'>
				<div class='colonne5'>
		    		<label class='etiquette' >".htmlentities($msg['acquisition_ach_fou2'], ENT_QUOTES, $charset)."</label>&nbsp;
				</div>
				<div class='colonne_suite'>
					!!lib_fou!!
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne2'>
				<div class='colonne3'>!!date_cre!!</div>
				<div class='colonne3'>
					!!numero!!
				</div>
				<div class='colonne_suite'>
					<!-- sel_statut -->
				</div>
			</div>
			<div class='colonne2'>
				<img id='adr_fou_Img' name='adr_fou_Img' src='./images/plus.gif' class='img_plus'  onclick=\"javascript:expandBase('adr_fou_', true);\"/>
		    	<label class='etiquette' >".htmlentities($msg['acquisition_adr_fou'], ENT_QUOTES, $charset)."</label>
			</div>
		</div>

		<div class='row' id='adr_fou_Child' style='display:none;'>
			<div class='colonne2'>&nbsp;</div>
			<div class='colonne_suite' >
				<div class='colonne2' style='margin-left:30px'>					
					<textarea id='adr_fou' name='adr_fou' class='saisie-30emd' readonly='readonly' cols='50' rows='4' wrap='virtual'>!!adr_fou!!</textarea>
				</div>
			</div>
		</div>

		<div class='row'></div>
		<hr />
		
		<div class='row'>
			<div class='colonne2'>
				<img id='adr_bib_Img' src='./images/plus.gif' class='img_plus' onclick=\"javascript:expandBase('adr_bib_', true);\" />
	    		<label class='etiquette'>".htmlentities($msg['acquisition_adr_liv'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne2'>
	    		<label class='etiquette'>".htmlentities($msg['acquisition_adr_fac'], ENT_QUOTES, $charset)."</label>
			</div>
		</div>

		<div class='row' id='adr_bib_Child' name='adr_bib_Child' style='display:none;'>
			<div class='colonne2'>
				<div class='colonne' style='margin-left:30px' >					
					<textarea  id='adr_liv' name='adr_liv' class='saisie-30emd' readonly='readonly' cols='50' rows='4' wrap='virtual'>!!adr_liv!!</textarea>
				</div>
				<div class='colonne_suite'>&nbsp;</div>
			</div>
			<div class='colonne2'>
				<div class='colonne' style='margin-left:30px' >
					<textarea id='adr_fac' name='adr_fac'  class='saisie-30emd' readonly='readonly' cols='50' rows='4' wrap='virtual'>!!adr_fac!!</textarea>
				</div>
				<div class='colonne_suite'>&nbsp;</div>
			</div>
		</div>

		<div class='row'>
			<img id='comment_Img' src='./images/plus.gif' class='img_plus' onclick=\"javascript:expandBase('comment_', true);\"/>
    		<label class='etiquette'>".htmlentities($msg['acquisition_commentaires'], ENT_QUOTES, $charset)."</label>
		</div>
		
		<div class='row' style='margin-left:30px'>
			<textarea  id='comment_Child' name='comment' class='saisie-80em' style='display:none;' cols='62' rows='4' wrap='virtual' >!!comment!!</textarea>
		</div>

		<div class='row'>
			<img id='comment_i_Img' src='./images/plus.gif' class='img_plus' onclick=\"javascript:expandBase('comment_i_', true);\"/>
    		<label class='etiquette'>".htmlentities($msg['acquisition_commentaires_i'], ENT_QUOTES, $charset)."</label>&nbsp;
		</div>
		
		<div class='row' style='margin-left:30px'>
			<textarea  id='comment_i_Child' name='comment_i' class='saisie-80emd' readonly='readonly' style='display:none;' cols='62' rows='4' wrap='virtual'>!!comment_i!!</textarea>
		</div>
		
		<div class='row'></div>
		<hr />

		<div class='row'>
			<div class='colonne3'>
				<table style='background-color:transparent;' >
					<tr>
						<td width='50%' valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_act_num_dev'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'>
							<span class='current'>!!lien_dev!!</span>								
						</td>
					</tr>
					<tr>
						<td valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_liv_liees'], ENT_QUOTES, $charset)."</label>
							<span class='current'>!!liens_liv!!</span>	
						</td>
						<td valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_fac_liees'], ENT_QUOTES, $charset)."</label>								
							<span class='current'>!!liens_fac!!</span>								
						</td>
					</tr>
				</table>
			</div>
			<div class='colonne3'>
				<table style='background-color:transparent;'>
					<tr>
						<td width='50%' valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_dev_ref_fou'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'>
							<input type='hidden' id='id_dev' name='id_dev' value='!!id_dev!!' />
							<input id='ref' name='ref' class='saisie-1Oem' type='text' value='!!ref!!' />
						</td>
					</tr>
					<tr>
						<td valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_fac_date_pay'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'><!-- sel_date_pay --></td>
					</tr>
					<tr>
						<td valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_fac_num_pay'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'>
							<input id='num_pay' name='num_pay' type='text' class='saisie-10em' value='!!num_pay!!' />
						</td>
					</tr>
				</table>
			</div>
			<div class='colonne3'>
				<table style='background-color:transparent;'>
					<tr>
						<td width='50%' valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_cde_date_liv'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'>
							<!-- sel_date_liv -->
						</td>
					</tr>
				</table>
			</div>
			<input type='hidden' id='id_bibli' name='id_bibli' value='!!id_bibli!!' />
			<input type='hidden' id='act_type' name='act_type' value='".TYP_ACT_CDE."' />
			<input type='hidden' id='id_cde' name='id_cde' value='!!id_cde!!' /> 
			<input type='hidden' id='id_fou' name='id_fou' value='!!id_fou!!' />
			<input type='hidden' id='id_adr_fou' name='id_adr_fou' value='!!id_adr_fou!!' />
			<input type='hidden' id='id_adr_liv' name='id_adr_liv' value='!!id_adr_liv!!' />
			<input type='hidden' id='id_adr_fac' name='id_adr_fac' value='!!id_adr_fac!!' />
			<input type='hidden' id='gestion_tva' name='gestion_tva' value='".$acquisition_gestion_tva."' />
		</div>
		
		<div class='row'>		
			<table class='act_cell' >
				<tbody id='act_tab'>
					<tr>
						<th width='12%'>".htmlentities($msg['acquisition_act_tab_code'], ENT_QUOTES, $charset)."</th>
						<th width='35%'>".htmlentities($msg['acquisition_act_tab_lib'], ENT_QUOTES, $charset)."</th>
						<th width='4%'>".htmlentities($msg['acquisition_act_tab_qte'], ENT_QUOTES, $charset)."</th>
						<th width='4%'>".htmlentities($msg['acquisition_act_tab_rec'], ENT_QUOTES, $charset)."</th>";
switch ($acquisition_gestion_tva) {
	case '1' :
		$valid_cde_form.= "
						<th width='5%'>".htmlentities($msg['acquisition_act_tab_priht'], ENT_QUOTES, $charset)."</th>
						<th width='20%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset)."<br />".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)." / ".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
		break;
	case '2' :
		$valid_cde_form.= "
						<th width='5%'>".htmlentities($msg['acquisition_act_tab_prittc'], ENT_QUOTES, $charset)."</th>
						<th width='20%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset)."<br />".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)." / ".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
		break;	
	default :
		$valid_cde_form.= "
						<th width='5%'>".htmlentities($msg['acquisition_act_tab_prittc'], ENT_QUOTES, $charset)."</th>
						<th width='20%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset)."<br />".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
		break;
}
$valid_cde_form.= "		<th width='20%'>".htmlentities($msg['acquisition_act_tab_bud'], ENT_QUOTES, $charset)."</th>
					</tr>
					<!-- lignes -->
				</tbody>
			</table>
		</div>
		
		<div class='row'>
			<div class='left'>";
if($acquisition_gestion_tva) $valid_cde_form.= " 							
				<label class='etiquette'>".htmlentities($msg['acquisition_total_ht'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_ht' name='tot_ht' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='0.00' />
				<label class='etiquette'>".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_tva' name='tot_tva' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='' />";
$valid_cde_form.= "														
				<label class='etiquette'>".htmlentities($msg['acquisition_total_ttc'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_ttc' name='tot_ttc' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='' />
				<label class='etiquette'>".htmlentities($msg['acquisition_devise'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='devise' name='devise' class='saisie-5emd' style='text-align:right;' readonly='readonly' value='!!devise!!' />
			</div>
			<div class='right'>
				<label class='etiquette'>".htmlentities($msg['acquisition_tot_expl'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_expl' name='tot_expl' class='saisie-5emd' style='text-align:right;' readonly='readonly' value='0' />
			</div>
		</div>
		
		<div class='row'></div>
		
	</div>		

	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg['76']."' onclick=\"document.location='./acquisition.php?categ=ach&sub=cmde&action=list&id_bibli=!!id_bibli!!' \" />
			<!-- bouton_enr_valid -->
			<!-- bouton_dup -->
			<!-- bouton_rec -->
			<!-- bouton_fac -->
			<!-- bouton_imp -->
			<!-- bouton_audit -->	
		</div>
		<div class='right'>
			<!-- bouton_sol -->
			<!-- bouton_arc -->
		</div>
	</div>	
	
	<div class='row'></div>

</form>
<br /><br />
<script type='text/javascript' src='./javascript/tablist.js'></script>
<script type='text/javascript' src='./javascript/actes.js'></script>
<script type='text/javascript'>	
	
	document.getElementById('statut').value='!!statut!!';
	
	var msg_parcourir='".addslashes($msg['parcourir'])."'; 
	var msg_raz='".addslashes($msg['raz'])."'; 
	var act_nblines='!!act_nblines!!';
	var act_curline='!!act_nblines!!';
	act_calc();
	
</script>
<!-- jscript -->";


$bt_enr_valid = "<input type='button' class='bouton' value='".$msg['77']."' 
				onclick=\"document.forms['act_modif'].setAttribute('action', './acquisition.php?categ=ach&sub=cmde&action=update'); 
					document.forms['act_modif'].submit(); \" />";

$bt_rec = "<input type='button' class='bouton' value='".$msg['acquisition_cde_bt_rec']."' 
				onclick=\"document.forms['act_modif'].setAttribute('action', './acquisition.php?categ=ach&sub=livr&action=from_cde');
					document.forms['act_modif'].submit(); \" />";
				
$bt_fac ="<input type='button' class='bouton' value='".$msg['acquisition_cde_bt_fac']."' 
				onclick=\"document.forms['act_modif'].setAttribute('action', './acquisition.php?categ=ach&sub=fact&action=from_cde');
					document.forms['act_modif'].submit(); \" />";
				
$bt_sol ="<input type='button' class='bouton' value='".$msg['acquisition_cde_bt_sol']."' 
			onclick=\"	r = confirm('".addslashes($msg['acquisition_cde_sol'])."'); 
						if(r) {
							document.forms['act_modif'].setAttribute('action', './acquisition.php?categ=ach&sub=cmde&action=sold');	
							document.forms['act_modif'].submit(); } \" />";
							
$bt_audit = "<input type='button' class='bouton' value='".$msg['audit_button']."' onClick=\"openPopUp('./audit.php?type_obj=4&object_id=".$id_cde."', 'audit_popup', 700, 500, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" title='".$msg['audit_button']."' />";


//	------------------------------------------------------------------------------
//	template de visualisation pour les lignes de commande non modifiables
//	------------------------------------------------------------------------------
$valid_cde_row_form = "
<tr id='R_!!no!!'>
	<td >
		<input type='text' title='!!code!!' class='saisie-10emd' style='width:100%;' readonly='readonly' value='!!code!!' />
	</td>
	<td>
		<textarea title='!!lib!!' class='saisie-10emd' style='width:100%;' readonly='readonly' rows='2' wrap='virtual'>!!lib!!</textarea>
	</td>
	<td>
		<input type='text' id='qte[!!no!!]' title='!!qte!!' class='saisie-10emd' style='width:100%;text-align:right;' readonly='readonly' value='!!qte!!' />
	</td>
	<td>
		<input type='text' title='!!rec!!' class='saisie-10emd' style='width:100%;text-align:right;' readonly='readonly' value='!!rec!!' />
	</td>
	<td>
		<input type='text' id='prix[!!no!!]' title='!!prix!!' class='saisie-10emd' style='width:100%;text-align:right;' readonly='readonly' value='!!prix!!' />
	</td>
	<td>
		<input type='text' title='!!lib_typ!!' class='saisie-10emd' style='width:100%;' readonly='readonly' value='!!lib_typ!!' />		
";
if ($acquisition_gestion_tva) {
	$valid_cde_row_form.= "
		&nbsp;<input type='text' id='tva[!!no!!]' title='!!tva!! %' class='saisie-10emd' style='width:20%;text-align:right;' readonly='readonly' value='!!tva!!'/>&nbsp;%";
}
$valid_cde_row_form.= "
		&nbsp;<input type='text' id='rem[!!no!!]' title='!!rem!! %'class='saisie-10emd' style='width:20%;text-align:right;margin-left:10px;' readonly='readonly' value='!!rem!!'  />&nbsp;%
	</td>
	<td>
		<input type='text' title='!!lib_rub!!' class='saisie-10emd' style='width:100%;' readonly='readonly' value='!!lib_rub!!' />
	</td>
</tr>";

?>
