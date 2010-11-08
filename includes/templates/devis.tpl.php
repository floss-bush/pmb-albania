<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: devis.tpl.php,v 1.36 2009-06-03 06:06:35 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");


$devlist_form = "
<form class='form-$current_module' id='act_list_form' name='act_list_form' method='post' action=\"\" >
	<div class='form-contenu'>
		<table width='100%' ><tbody>
			<tr>
			<th>".htmlentities($msg['38'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_ach_fou2'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_dev_date_dem'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_statut'], ENT_QUOTES, $charset)."</th>	
			<th class='act_cell_chkbox' >&nbsp;</th>
			<!-- chk_th -->
			</tr>
			<!-- dev_list -->
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


$devlist_bt_chk ="<input type='button' id='bt_chk' class='bouton_small' value='$msg[acquisition_sug_checkAll]' onClick=\"checkAll('act_list_form', 'chk', check); return false;\" />";
$devlist_bt_supChk = "<input type='button' class='bouton_small' value='$msg[63]' onClick=\"supChk();\" />";

$devlist_bt_rec="<input type='button' class='bouton_small' value='".$msg['acquisition_dev_bt_rec']."' onClick=\"devlist_rec();\" />";
$devlist_bt_arc="<input type='button' class='bouton_small' value='".$msg['acquisition_act_bt_arc']."' onClick=\"devlist_arc();\" />";
$devlist_bt_delete="<input type='button' class='bouton_small' value='".$msg['63']."' onClick=\"devlist_delete();\" />";


$devlist_script = "
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
	
	
	function devlist_delete() {
		r = confirm(\"".$msg['acquisition_devlist_sup']."\");
		if (r) {
			document.forms['act_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=devi&action=list_delete&id_bibli='+document.getElementById('id_bibli').value);
			document.forms['act_list_form'].submit();
			return true;	
		}
		return false;
	}
	

	function devlist_rec() {
		r = confirm(\"".$msg['acquisition_devlist_rec']."\");
		if (r) {
			document.forms['act_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=devi&action=list_rec&id_bibli='+document.getElementById('id_bibli').value);
			document.forms['act_list_form'].submit();
			return true;	
		}
		return false;
	}
	

	function devlist_arc() {
		r = confirm(\"".$msg['acquisition_devlist_arc']."\");
		if (r) {
			document.forms['act_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=devi&action=list_arc&id_bibli='+document.getElementById('id_bibli').value);
			document.forms['act_list_form'].submit();
			return true;	
		}
		return false;
	}
	
</script>
";


//	------------------------------------------------------------------------------
//	$modif_dev_form : template de création/modification pour les devis
//	------------------------------------------------------------------------------
$modif_dev_form = "
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
					<input type='text' id='num_dev' name='num_dev' value='!!numero!!' class='saisie-10em' />
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
				<table style='background-color:transparent;' >
					<tr>
						<td valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_cde_liees'], ENT_QUOTES, $charset)."</label>
							<span class='current'>!!liens_cde!!</span>
						</td valign='top'>
						<td>&nbsp;</td>
					</tr>
				</table>		
			</div>
			<div class='colonne3'>					
				<table style='background-color:transparent;' >
					<tr>
						<td valign='top'>
							<label class='etiquette'>".htmlentities($msg['acquisition_dev_ref_fou'], ENT_QUOTES, $charset)."</label>
						</td>
						<td valign='top'>
							<input type='text' id='ref' name='ref' tabindex='1' class='saisie-1Oem' value='!!ref!!' />
						</td>
					</tr>
				</table>
			</div>
			<input type='hidden' id='id_bibli' name='id_bibli' value='!!id_bibli!!' />
			<input type='hidden' id='act_type' name='act_type' value='".TYP_ACT_DEV."' />
			<input type='hidden' id='id_dev' name='id_dev' value='!!id_dev!!' /> 
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
						<th width='53%'>".htmlentities($msg['acquisition_act_tab_lib'], ENT_QUOTES, $charset)."</th>
						<th width='3%'>".htmlentities($msg['acquisition_act_tab_qte'], ENT_QUOTES, $charset)."</th>";				
switch ($acquisition_gestion_tva) {
	case '1' :
		$modif_dev_form.= "
						<th width='5%'>".htmlentities($msg['acquisition_act_tab_priht'], ENT_QUOTES, $charset)."</th>
						<th width='25%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset)."<br />".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)." / ".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
		break;
	case '2' :
		$modif_dev_form.= "
						<th width='5%'>".htmlentities($msg['acquisition_act_tab_prittc'], ENT_QUOTES, $charset)."</th>
						<th width='25%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset)."<br />".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)." / ".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
		break;	
	default :
		$modif_dev_form.= "
						<th width='5%'>".htmlentities($msg['acquisition_act_tab_prittc'], ENT_QUOTES, $charset)."</th>
						<th width='25%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset)."<br />".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
		break;
}
$modif_dev_form.="		<th width='2%'></th>
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
if ($acquisition_gestion_tva) {
	$modif_dev_form.= "
				<label class='etiquette'>".htmlentities($msg['acquisition_total_ht'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_ht' name='tot_ht' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='0.00' />
				<label class='etiquette'>".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)."</label>
				<input type='text' id='tot_tva' name='tot_tva' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='0.00' />";
} 
$modif_dev_form.= "	
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
			<input type='button' class='bouton' value='".$msg['76']."' onclick=\"document.location='./acquisition.php?categ=ach&sub=devi&action=list&id_bibli=!!id_bibli!!' \" />
			<!-- bouton_enr -->
			<!-- bouton_dup -->
			<!-- bouton_cde -->
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
	var msg_act_vide='".addslashes($msg['acquisition_dev_vid'])."';
	var acquisition_budget='0';
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
//	template de création/modification pour les lignes de devis
//	------------------------------------------------------------------------------
$modif_dev_row_form = "
<tr id='R_!!no!!'>
	<td>
		<input type='text' id='code[!!no!!]' name='code[!!no!!]' tabindex='1' class='in_cell' value='!!code!!' /><input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['parcourir']."' onclick=\"act_getCode(this);\" /><input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['raz']."' onclick=\"act_delCode(this);\" />
	</td>	
	<td>
		<textarea id='lib[!!no!!]' name='lib[!!no!!]' tabindex='1' class='in_cell' rows='2' wrap='virtual'>!!lib!!</textarea>
	</td>
	<td>
		<input type='text' id='qte[!!no!!]' name='qte[!!no!!]' tabindex='1' class='in_cell_nb' value='!!qte!!' />
	</td>
	<td>
		<input type='text' id='prix[!!no!!]' name='prix[!!no!!]' tabindex='1' class='in_cell_nb' value='!!prix!!' />
	</td>
	<td>
		<input type='hidden' id='typ[!!no!!]' name='typ[!!no!!]' value='!!typ!!' />
		<input type='text' id='lib_typ[!!no!!]' name='lib_typ[!!no!!]' tabindex='1' class='in_cell' value='!!lib_typ!!' /><input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['parcourir']."' onclick=\"act_getType(this);\" /><input type='button' tabindex='1' class='bouton_small' style='width:20px;' value='".$msg['raz']."' onclick=\"act_delType(this);\" />";
if ($acquisition_gestion_tva) {
	$modif_dev_row_form.= "&nbsp;<input type='text' id='tva[!!no!!]' name='tva[!!no!!]' tabindex='1' class='in_cell_nb' style='width:20%;' value='!!tva!!' />&nbsp;%";
} 	
$modif_dev_row_form.= "&nbsp;<input type='text' id='rem[!!no!!]' name='rem[!!no!!]' tabindex='1' class='in_cell_nb' style='width:20%;' value='!!rem!!' />&nbsp;%		
	</td>
	<td>
		<input type='checkbox' id='chk[!!no!!]' name='chk[!!no!!]' tabindex='1' value='1' />
		<input type='hidden' id='id_sug[!!no!!]' name='id_sug[!!no!!]' value='!!id_sug!!' /> 
		<input type='hidden' id='id_lig[!!no!!]' name='id_lig[!!no!!]' value='!!id_lig!!' /> 
		<input type='hidden' id='typ_lig[!!no!!]' name='typ_lig[!!no!!]' value='!!typ_lig!!' /> 	
		<input type='hidden' id='id_prod[!!no!!]' name='id_prod[!!no!!]' value='!!id_prod!!' />
	</td>
</tr>";


$bt_enr = "<input type='button' class='bouton' value='".$msg['77']."' 
			onclick=\"
				r=act_verif();
				if (!r) return false;
				act_calc(); 
				document.forms['act_modif'].setAttribute('action', 'acquisition.php?categ=ach&sub=devi&action=update'); 
				document.forms['act_modif'].submit(); \" />";

$bt_dup = "<input type='button' class='bouton' value='".$msg['acquisition_dup']."' 
			onclick=\"document.forms['act_modif'].setAttribute('action', 'acquisition.php?categ=ach&sub=devi&action=duplicate'); 
				document.forms['act_modif'].submit(); \" />";

$bt_sup = "<input type='button' class='bouton' value='".$msg['63']."' 
			onclick=\"if (document.getElementById('id_dev').value == 0) {return false; } 
				r = confirm('".addslashes($msg['acquisition_dev_sup'])."');
				if(r){
					document.forms['act_modif'].setAttribute('action', './acquisition.php?categ=ach&sub=devi&action=delete'); 
					document.forms['act_modif'].submit();} \" />";

$bt_cde = "<input type='button' class='bouton' value='".$msg['acquisition_dev_bt_cde']."' 
			onclick=\"if (document.getElementById('id_fou').value == 0) {alert('".addslashes($msg['acquisition_cde_fou_err'])."'); return false;} 
				if (act_nblines<1) {alert('".addslashes($msg['acquisition_dev_vid'])."'); return false;} 		
				act_calc(); 
				document.forms['act_modif'].setAttribute('action', 'acquisition.php?categ=ach&sub=cmde&action=from_devis'); 
				document.forms['act_modif'].submit(); \" />";

$bt_audit = "<input type='button' class='bouton' value='".$msg['audit_button']."' title='".$msg['audit_button']."' onclick=\"openPopUp('./audit.php?type_obj=4&object_id=".$id_dev."', 'audit_popup', 700, 500, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />";

$bt_imp = "<input type='button' class='bouton' value='".$msg['imprimer']."' title='".$msg['imprimer']."' onclick=\"openPopUp('./pdf.php?pdfdoc=devi&id_dev=".$id_dev."' ,'print_PDF', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" />"; 
?>
