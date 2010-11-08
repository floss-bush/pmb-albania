<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: factures.tpl.php,v 1.24 2009-06-03 06:06:35 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");


$faclist_form = "
<form class='form-$current_module' id='act_list_form' name='act_list_form' method='post' action=\"\" >
	<div class='form-contenu'>
		<table width='100%' ><tbody>
			<tr>
			<th>".htmlentities($msg['38'],ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_act_num_cde'],ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_ach_fou2'],ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_fac_date_rec'],ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_statut'],ENT_QUOTES, $charset)."</th>
			<th class='act_cell_chkbox' >&nbsp;</th>
			<!-- chk_th -->
			</tr>
			<!-- fac_list -->
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


$faclist_bt_chk ="<input type='button' id='bt_chk' class='bouton_small' value='$msg[acquisition_sug_checkAll]' onClick=\"checkAll('act_list_form', 'chk', check); return false;\" />";
$faclist_bt_supChk = "<input type='button' class='bouton_small' value='$msg[63]' onClick=\"supChk();\" />";

$faclist_bt_pay="<input type='button' class='bouton_small' value='".$msg['acquisition_fac_bt_pay']."' onClick=\"faclist_pay();\" />";

$faclist_script = "
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
	
	
	function faclist_pay() {
		r = confirm(\"".$msg['acquisition_faclist_pay']."\");
		if (r) {
			document.forms['act_list_form'].setAttribute('action', './acquisition.php?categ=ach&sub=fact&action=list_pay&id_bibli='+document.getElementById('id_bibli').value);
			document.forms['act_list_form'].submit();
			return true;	
		}
		return false;
	}
		
</script>
";


//	------------------------------------------------------------------------------
//	$fact_modif_form : template de création/modification pour les factures 
//	------------------------------------------------------------------------------
$fact_modif_form = "
<form class='form-".$current_module."' id='fact_modif' name='fact_modif' method='post' action=\"\" 
	onsubmit=\"	if(document.getElementById('cb').value=='') return false;
						list_lig.complete_form(); 
						list_lig.document.forms['frame_modif'].setAttribute('action', 'frame_facture.php?action=search'); 
						list_lig.document.forms['frame_modif'].submit(); 
						return false; \">

	<h3>!!form_title!!</h3>
	<div class='row'></div>
	<!--    Contenu du form    -->
	<div class='form-contenu'>
		<div class='row'>
			<div class='colonne2'>
				<div class='colonne2' >			
					<label class='etiquette'>".htmlentities($msg['acquisition_coord_lib'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne_suite'>
					!!lib_bibli!!
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='colonne2'>
				<div class='colonne2' >			
					<label class='etiquette'>".htmlentities($msg['acquisition_budg_exer'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne_suite'>
					<input type='hidden' id='id_exer' name='id_exer' value='!!id_exer!!' /> 
					!!lib_exer!!
				</div>
			</div>
		</div>
		<div class='row'>
			<hr />
		</div>	


		<div class='row'>
			<div class='colonne2'>
				<div class='colonne2' >			
					<label class='etiquette'>".htmlentities($msg['653'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne_suite'>
					<div class='left'>!!date_cre!!</div>
				</div>
			</div>
			<div class='colonne2'>
				<div class='colonne5'>
		    		<label class='etiquette'>".htmlentities($msg['acquisition_ach_fou2'], ENT_QUOTES, $charset)."</label>&nbsp;
				</div>
				<div class='colonne_suite'>
					<input type='hidden' id='id_fou' name='id_fou' value='!!id_fou!!' />
					!!lib_fou!!
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne2'>
				<div class='colonne2'>
		    		<label class='etiquette'>".htmlentities($msg['38'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne2'>
					<input type='hidden' id='id_fac' name='id_fac' value='!!id_fac!!' /> 
					<input type='text' id='num_fac' name='num_fac' value='!!numero!!' class='saisie-10emd' readonly='readonly' />
				</div>
			</div>
			<div class='colonne2'>
			</div>
		</div>

		<div class='row'></div>

		<br /> 

		<div class='row'>
			<img id='comment_Img' src='./images/plus.gif' class='img_plus' onclick=\"javascript:expandBase('comment_', true);\"/>
    		<label class='etiquette'>".htmlentities($msg['acquisition_commentaires'], ENT_QUOTES, $charset)."</label>&nbsp;
		</div>
		<div class='row' style='margin-left:30px'>
			<textarea  id='comment_Child' name='comment_Child' class='saisie-80em' style='display:none;' cols='62' rows='6' wrap='virtual'>!!comment!!</textarea>
		</div>
		<hr />

		<div class='row'>
			<div class='colonne3'>
				<div class='colonne2'>
					<label class='etiquette'>".htmlentities($msg['acquisition_act_num_cde'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne2'>
					<input type='hidden' id='id_cde' name='id_cde' value = '!!id_cde!!' />
					<span class='current'>!!num_cde!!</span>\n
				</div>
			</div>
			<div class = 'colonne3'>
				<div class='colonne2'>
					<label class='etiquette'>".htmlentities($msg['acquisition_fac_ref_fou'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne2'>
					<input id='ref' name='ref' class='saisie-10em' type='text' value='!!ref!!' />\n
				</div>
			</div>
			<div class='colonne3'>";			
if ($acquisition_gestion_tva) {
	$fact_modif_form.= "
				<div class='colonne2'>
					<label class='etiquette'>".htmlentities($msg['acquisition_total_ht'], ENT_QUOTES, $charset)."</label>&nbsp;
				</div>
				<div class='colonne_suite'>
					<input id='tot_ht' name='tot_ht' type='text' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='' />
				</div>";
} else {
	$fact_modif_form.= "
				<div class='colonne2'>
					&nbsp;
				</div>
				<div class='colonne_suite'>
					&nbsp;
				</div>";
}
$fact_modif_form.= "
			</div>
			<div class='row'>
				<div class='colonne3'>
					<div class='colonne2'>
						<label class='etiquette'>".htmlentities($msg['acquisition_cde_pay'], ENT_QUOTES, $charset)."</label>
					</div>
					<div class='colonne2'>
						!!date_pay_cde!!&nbsp;
					</div>
				</div>
				<div class='colonne3'>
					<div class='colonne2'>
						<label class='etiquette'>".htmlentities($msg['acquisition_fac_date_pay'], ENT_QUOTES, $charset)."</label>
					</div>
					<div class='colonne2'>
						<input id='date_pay' name='date_pay' type='text' class='saisie-10em' value='!!date_pay!!' />
					</div>
				</div>
				<div class='colonne3'>";
if ($acquisition_gestion_tva) {
	$fact_modif_form.= "
					<div class='colonne2'>
						<label class='etiquette'>".htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset)."</label>&nbsp;
					</div>
					<div class='colonne_suite'>
						<input id='tot_tva' name='tot_tva' type='text' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='' />
					</div>";
} else {
	$fact_modif_form.= "
					<div class='colonne2'>
						&nbsp;
					</div>
					<div class='colonne_suite'>
						&nbsp;
					</div>";
}
$fact_modif_form.= "
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<div class = 'colonne2'>&nbsp;</div>
				<div class = 'colonne2'>
					!!num_pay_cde!!&nbsp;
				</div>
			</div>
			<div class = 'colonne3'>
				<div class = 'colonne2'>
					<label class='etiquette'>".htmlentities($msg['acquisition_fac_num_pay'], ENT_QUOTES, $charset)."</label>&nbsp;
				</div>
				<div class = 'colonne2'>
					<input id='num_pay' name='num_pay' type='text' class='saisie-10em' value='!!num_pay!!' />
				</div>
			</div>
			<div class='colonne3'>
				<div class='colonne2'>
					<label class='etiquette'>".htmlentities($msg['acquisition_total_ttc'], ENT_QUOTES, $charset)."</label>&nbsp;
				</div>
				<div class='colonne_suite'>
					<input id='tot_ttc' name='tot_ttc' type='text' class='saisie-10emd' style='text-align:right;' readonly='readonly' value='' />
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>&nbsp;
			</div>
			<div class='colonne3'>&nbsp;
			</div>
			<div class='colonne3'>
				<div class='colonne2'>
					<label class='etiquette'>".htmlentities($msg['acquisition_devise'], ENT_QUOTES, $charset)."</label>&nbsp;
				</div>
				<div class='colonne_suite'>
					<input id='devise' name='devise' type='text' class='saisie-10em' style='text-align:right;' value='!!devise!!' />
				</div>
			</div>
		</div>		

		<div class='row'>&nbsp;</div>
		
		<!-- form_search -->
	<div>
		<table width='100%' frame='all' style='table-layout:fixed;'>
			<tr>
				<th width='3%'>&nbsp;</th>
				<th width='12%'>".htmlentities($msg['acquisition_act_tab_code'], ENT_QUOTES, $charset)."</th>
				<th width='25%'>".htmlentities($msg['acquisition_act_tab_lib'], ENT_QUOTES, $charset)."</th>";
if ($acquisition_gestion_tva){
	$fact_modif_form.= "<th width='8%'>".htmlentities($msg['acquisition_act_tab_priht'], ENT_QUOTES, $charset)."</th>
						<th width='20%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset).'<br />'.htmlentities($msg['acquisition_tva'], ENT_QUOTES, $charset).' / '.htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
} else {
	$fact_modif_form.= "<th width='8%'>".htmlentities($msg['acquisition_act_tab_prittc'], ENT_QUOTES, $charset)."</th>
						<th width='20%'>".htmlentities($msg['acquisition_act_tab_typ'], ENT_QUOTES, $charset).'<br />'.htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</th>";
}
$fact_modif_form.= "
				<th width='18%'>".htmlentities($msg['acquisition_act_tab_bud'], ENT_QUOTES, $charset)."</th>
				<th width='7%'>".htmlentities($msg['acquisition_act_tab_solfac'], ENT_QUOTES, $charset)."</th>
				<th width='7%'>".htmlentities($msg['acquisition_act_tab_fac'], ENT_QUOTES, $charset)."</th>
			</tr>
		</table>
	</div>

	<iframe class='acquisition' name='list_lig' id='list_lig' width='100%' height='350'></iframe>		
		
	<div class='row'></div>

	<div class='right'>	
		<!-- bouton_sup_lig -->
		<input type='button' id='bt_sup_lig' name='bt_sup_lig' class='bouton' style='display:none;' value='".addslashes($msg['acquisition_del_chk_lig'])."'  
				onclick=\"list_lig.complete_form(); 
				list_lig.document.forms['frame_modif'].setAttribute('action', 'frame_facture.php?action=sup_lig&id_bibli=".$id_bibli."'); 
				list_lig.document.forms['frame_modif'].submit(); \" />
	</div>

	<div class='row'></div>
	
</div>

<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='".$msg['76']."' onclick=\"document.location='./acquisition.php?categ=ach&sub=fact&action=list&id_bibli=$id_bibli' \" />
		<!-- bouton_enr -->
		<!-- bouton_pay -->
		<!-- bouton_audit -->
	</div>
	<div class='right'>
		<!-- bouton_sup -->
	</div>
	<div class='row'></div>

</form>
<br /><br />
<script type='text/javascript' src='./javascript/tablist.js'></script>
<!-- frame_show -->
<script type='text/javascript'>
try {
	document.forms['fact_modif'].elements['cb'].focus();	
} catch (e) {}
</script>
";


//	------------------------------------------------------------------------------
//	template de création/modification pour les lignes de factures
//	------------------------------------------------------------------------------
$frame_modif = " 
<table width='100%' frame='all' style='table-layout:fixed;background-color:transparent;'>

	<form id='frame_modif' name='frame_modif' method='post' action=\"\" >
		<input type='hidden' id='max_lig' name='max_lig' value='!!max_lig!!' />
		<input type='hidden' id='id_cde' name='id_cde' value='0' />
		<input type='hidden' id='id_fac' name='id_fac' value='!!id_fac!!' />
		<input type='hidden' id='comment' name='comment' value='' />
		<input type='hidden' id='ref' name='ref' value='' />
		<input type='hidden' id='tot_ht' name='tot_ht' value='!!tot_ht!!' />
		<input type='hidden' id='tot_tva' name='tot_tva' value='!!tot_tva!!' />
		<input type='hidden' id='tot_ttc' name='tot_ttc' value='!!tot_ttc!!' />
		<input type='hidden' id='cb' name='cb' value='' />
		<input type='hidden' id='max_lig_fac' name='max_lig_fac' value='!!max_lig_fac!!' />
		<input type='hidden' id='date_pay' name='date_pay' value='' />
		<input type='hidden' id='num_pay' name='num_pay' value='' />
		<input type='hidden' id='devise' name='devise' value='' />
		<input type='hidden' id='id_exer' name='id_exer' value='!!id_exer!!' />
		<!-- lignes -->
	</form>
</table>
<div class='row'></div>			

<!-- error -->
<!-- warning -->

<script type='text/javascript' >

//Mise à jour de la fenetre parent
maj();

function complete_form() {
	document.forms['frame_modif'].elements['id_fac'].value = window.parent.document.forms['fact_modif'].elements['id_fac'].value;
	document.forms['frame_modif'].elements['id_cde'].value = window.parent.document.forms['fact_modif'].elements['id_cde'].value;
	document.forms['frame_modif'].elements['comment'].value = window.parent.document.forms['fact_modif'].elements['comment_Child'].value;
	document.forms['frame_modif'].elements['ref'].value = window.parent.document.forms['fact_modif'].elements['ref'].value;
	document.forms['frame_modif'].elements['date_pay'].value = window.parent.document.forms['fact_modif'].elements['date_pay'].value;
	document.forms['frame_modif'].elements['num_pay'].value = window.parent.document.forms['fact_modif'].elements['num_pay'].value;
	document.forms['frame_modif'].elements['devise'].value = window.parent.document.forms['fact_modif'].elements['devise'].value;
	document.forms['frame_modif'].elements['id_exer'].value = window.parent.document.forms['fact_modif'].elements['id_exer'].value;
	try{
		document.forms['frame_modif'].elements['cb'].value = window.parent.document.forms['fact_modif'].elements['cb'].value;
	} catch(e) {}
}

function maj () {
	try {
		window.parent.document.forms['fact_modif'].elements['cb'].value = '';
	} catch (e) {}
	document.forms['frame_modif'].elements['id_exer'].value = window.parent.document.forms['fact_modif'].elements['id_exer'].value;
	window.parent.document.forms['fact_modif'].elements['tot_ttc'].value = document.forms['frame_modif'].elements['tot_ttc'].value;";
if ($acquisition_gestion_tva) {
	$frame_modif.= "
	window.parent.document.forms['fact_modif'].elements['tot_ht'].value = document.forms['frame_modif'].elements['tot_ht'].value;
	window.parent.document.forms['fact_modif'].elements['tot_tva'].value = document.forms['frame_modif'].elements['tot_tva'].value;";
}
$frame_modif.="
}
<!-- bouton_sup_lig -->	

</script>
<!-- focus -->
</body></html>
";


$frame_row = "
<tr>
	<td width='3%'>
		<label class='etiquette' >!!no!!</label>
		<input type='hidden' id='id_lig[!!no!!]' name='id_lig[!!no!!]' value='!!id_lig!!' /> 
		<input type='hidden' id='id_prod[!!no!!]' name='id_prod[!!no!!]' value='!!id_prod!!' />
	</td>
	<td width='12%' valign='top'>
		<input id='code[!!no!!]' name='code[!!no!!]' class='saisie-10emd' style='width:95%' type='text'   readonly='readonly' tabindex='-1' value='!!code!!' />\n
	</td>
	<td width='25%' valign='top'>
		<textarea id='lib[!!no!!]' name='lib[!!no!!]' class='saisie-10emd' style='width:95%' rows='2' wrap='virtual' readonly='readonly' tabindex='-1' >!!lib!!</textarea>
	</td>
	<td width='8%' valign='top'>
		<input id='prix[!!no!!]' name='prix[!!no!!]' style='width:95%;text-align:right;' type='text' value='!!prix!!' />
	</td>
	<td width='20%' valign='top'>
		<!-- select_typ -->
	</td>
	<td width='18%' valign='top'>
		<!-- select_bud -->
	</td>
	<td width='7%'>
		<input id='sol[!!no!!]' name='sol[!!no!!]' class='saisie-10emd' style='text-align:right;width:95%' type='text'  readonly='readonly' tabindex='-1' value='!!sol!!' />
	</td>
	<td width='7%'>
		<a name='ancre[!!no!!]'></a>
		<input id='fac[!!no!!]' name='fac[!!no!!]' class='saisie-10em' style='text-align:right;width:95%' type='text' tabindex='-1' value='!!fac!!' />
	</td>	
</tr>			
";


$frame_row_fa_header="<tr class='tab_sep'><td width='3%'>&nbsp;</td><td width='12%'>&nbsp;</td><td width='25%'><strong>".htmlentities($msg['acquisition_fac_sai'], ENT_QUOTES, $charset)."</strong></td><td width='8%'>&nbsp;</td><td width='20%'>&nbsp;</td><td width='18%'>&nbsp;</td><td width='7%'>&nbsp;</td><td width='7%'>&nbsp;</td></tr>";

$frame_row_fa = "
<tr>
	<td width='3%'>
		<input type='checkbox' tabindex='-1' id='chk[!!no!!]' name='chk[!!no!!]' value='1' />			
		<input type='hidden' id='id_lig[!!no!!]' name='id_lig[!!no!!]' value='!!id_lig!!' /> 
		<input type='hidden' id='id_prod[!!no!!]' name='id_prod[!!no!!]' value='!!id_prod!!' />
	</td>
	<td width='12%' valign='top'>
		<input id='code[!!no!!]' name='code[!!no!!]' class='saisie-10emd' style='width:95%' type='text'   readonly='readonly' tabindex='-1' value='!!code!!' />\n
	</td>
	<td width='25%' valign='top'>
		<textarea id='lib[!!no!!]' name='lib[!!no!!]' class='saisie-10emd' style='width:95%' rows='2' wrap='virtual' readonly='readonly' tabindex='-1' >!!lib!!</textarea>
	</td>
	<td width='8%' valign='top'>
		<input id='prix[!!no!!]' name='prix[!!no!!]' class='saisie-10emd' style='width:95%;text-align:right;' type='text' value='!!prix!!' readonly='readonly' />
	</td>
	<td width='20%' valign='top'>
		<!-- select_typ -->
	</td>
	<td width='18%' valign='top'>
		<!-- select_bud -->
	</td>
	<td width='7%' valign='top'>
		&nbsp;
	</td>
	<td width='7%' valign='top'>
		<input type='hidden' id='fac[!!no!!]' name='fac[!!no!!]' value='!!fac!!' />
		<input id='afffac[!!no!!]' name='afffac[!!no!!]' class='saisie-10emd' style='text-align:right;width:95%' type='text' value='!!fac!!' readonly='readonly' tabindex='-1' />
	</td>
</tr>			
";


$frame_row_fa_arc="
<tr>
	<td width='3%'>
		<label class='etiquette' >!!no!!</label>
	</td>
	<td width='12%' valign='top'>
		<input id='code[!!no!!]' name='code[!!no!!]' class='saisie-10emd' style='width:95%' type='text'   readonly='readonly' tabindex='-1' value='!!code!!' />\n
	</td>
	<td width='25%' valign='top'>
		<textarea id='lib[!!no!!]' name='lib[!!no!!]' class='saisie-10emd' style='width:95%' rows='2' wrap='virtual' readonly='readonly' tabindex='-1' >!!lib!!</textarea>
	</td>
	<td width='8%' valign='top'>
		<input id='prix[!!no!!]' name='prix[!!no!!]' class='saisie-10emd' style='width:95%;text-align:right;' type='text' value='!!prix!!' readonly='readonly' />
	</td>
	<td width='20%' valign='top'>
		<!-- select_typ -->
	</td>
	<td width='18%' valign='top'>
		<!-- select_bud -->
	</td>
	<td width='7%' valign='top'>
		&nbsp;
	</td>
	<td width='7%' valign='top'>
		<input id='afffac[!!no!!]' name='afffac[!!no!!]' class='saisie-10emd' style='text-align:right;width:95%' type='text' value='!!fac!!' readonly='readonly' tabindex='-1' />
	</td>
</tr>			
";

//types modifiables
$select_typ[0] = "<input type='hidden' id='typ[!!no!!]' name='typ[!!no!!]' value='!!typ!!' />
				<input  type='text' id='lib_typ[!!no!!]' name='lib_typ[!!no!!]' class='saisie-10emr' style='width:70%;' value='!!lib_typ!!' title='!!lib_typ!!' onchange=\"openPopUp('../../../select.php?what=types_produits&caller=frame_modif&param1=typ[!!no!!]&param2=lib_typ[!!no!!]&param3=rem[!!no!!]&param4=tva[!!no!!]&id_fou=!!id_fou!!&close=1', 'select_type', 400, 400, -2, -2,'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes');\" />&nbsp;
				<input type='button' tabindex='-1' class='bouton' value='".$msg['parcourir']."' onclick=\"openPopUp('../../../select.php?what=types_produits&caller=frame_modif&param1=typ[!!no!!]&param2=lib_typ[!!no!!]&param3=rem[!!no!!]&param4=tva[!!no!!]&id_fou=!!id_fou!!&close=1', 'select_type', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes');\" />
				<br />";
if ($acquisition_gestion_tva) {
	$select_typ[0].= "<input type='text' id='tva[!!no!!]' name='tva[!!no!!]' value='!!tva!!' class='saisie-10emd' style='width:35%;text-align:right;' readonly='readonly' />%&nbsp;";
} 
$select_typ[0].= "		
		<input type='text' id='rem[!!no!!]' name='rem[!!no!!]' value='!!rem!!' class='saisie-10em' style='width:35%;text-align:right;' />%";

//types non modifiables
$select_typ[1] = "<input type='hidden' id='typ[!!no!!]' name='typ[!!no!!]' value='!!typ!!' />
				<input  type='text' id='lib_typ[!!no!!]' name='lib_typ[!!no!!]' class='saisie-10emd' style='width:70%;' value='!!lib_typ!!' title='!!lib_typ!!' readonly='readonly' />
				<br />";
if ($acquisition_gestion_tva) {
	$select_typ[1].= "<input type='text' id='tva[!!no!!]' name='tva[!!no!!]' value='!!tva!!' class='saisie-10emd' style='width:35%;text-align:right;' readonly='readonly' />%&nbsp;";
} 
$select_typ[1].= "		
		<input type='text' id='rem[!!no!!]' name='rem[!!no!!]' value='!!rem!!' class='saisie-10emd' style='width:35%;text-align:right;' readonly='readonly'/>%";


//rubriques modifiables
$select_rub[0] = "
			<input type='hidden' id='rub[!!no!!]' name='rub[!!no!!]' value='!!id_rub!!' />
			<input  type='text' id='lib_rub[!!no!!]' name='lib_rub[!!no!!]' class='saisie-10emr' style='width:95%;' value='!!lib_rub!!' title='!!lib_rub!!' onchange=\"openPopUp('../../../select.php?what=budgets&caller=frame_modif&param1=rub[!!no!!]&param2=lib_rub[!!no!!]&id_bibli=$id_bibli&id_exer='+document.getElementById('id_exer').value+'&close=1', 'select_rubriques', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes');\" />
			<br />
			<input type='button' tabindex='-1' class='bouton' value='".$msg['parcourir']."' onclick=\"openPopUp('../../../select.php?what=rubriques&caller=frame_modif&param1=rub[!!no!!]&param2=lib_rub[!!no!!]&id_bibli=$id_bibli&id_exer='+document.getElementById('id_exer').value+'&close=1', 'select_rubriques', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes');\" />&nbsp;";

//rubriques non modifiables
$select_rub[1] = "
			<input type='hidden' id='rub[!!no!!]' name='rub[!!no!!]' value='!!id_rub!!' />
			<input  type='text' id='lib_rub[!!no!!]' name='lib_rub[!!no!!]' class='saisie-10emd' style='width:95%;' value='!!lib_rub!!' title='!!lib_rub!!' readonly='readonly' />
			<br />			
			";


$frame_show_from_cde = "<script type='text/javascript'>document.getElementById('list_lig').src= './acquisition/achats/factures/frame_facture.php?action=from_cde&id_bibli=$id_bibli&id_cde=$id_cde' </script>";
$frame_show = "<script type='text/javascript'>document.getElementById('list_lig').src= './acquisition/achats/factures/frame_facture.php?action=show_lig&id_bibli=$id_bibli&id_cde=!!id_cde!!&id_fac=$id_fac' </script>";


$bt_sup_lig = "	bt = window.parent.document.getElementById('bt_sup_lig');
				bt.setAttribute('style','display:block;'); ";

$no_bt_sup_lig = " bt = window.parent.document.getElementById('bt_sup_lig');
				   bt.setAttribute('style','display:none;'); ";

$bt_sup = "<input type='button' class='bouton' value='".$msg['63']."' 
			onclick=\"if (document.forms['fact_modif'].elements['id_fac'].value == 0) {return false; } 
				r = confirm('".addslashes($msg['acquisition_fac_sup'])."');
				if(r){
					document.forms['fact_modif'].setAttribute('action', './acquisition.php?categ=ach&sub=fact&action=delete&id_bibli=".$id_bibli."'); 
					document.forms['fact_modif'].submit();
				} return false; \" />";			 

$bt_enr = "<input type='button' class='bouton' value='".$msg['77']."' onclick=\"list_lig.complete_form(); 
				list_lig.document.forms['frame_modif'].setAttribute('action', 'frame_facture.php?action=update&id_bibli=$id_bibli'); 
				list_lig.document.forms['frame_modif'].submit(); 
				return false;\" />";

$bt_pay = "<input type='button' class='bouton' value='".$msg['acquisition_fac_bt_pay']."' 
			onclick=\"r = confirm('".addslashes($msg['acquisition_fac_msg_pay'])."');
				if(r){
					list_lig.complete_form(); 
					list_lig.document.forms['frame_modif'].setAttribute('action', 'frame_facture.php?action=pay&id_bibli=$id_bibli'); 
					list_lig.document.forms['frame_modif'].submit(); 
				} return false; \" />";

$bt_audit = "<input type='button' class='bouton' value='".$msg['audit_button']."' onClick=\"openPopUp('./audit.php?type_obj=4&object_id=".$id_fac."', 'audit_popup', 700, 500, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" title='".$msg['audit_button']."' />";

$form_search = "	<hr />
			<div class='row'>
				<div class = 'colonne2'>
					<label class='etiquette'>".htmlentities($msg['663'], ENT_QUOTES, $charset)."</label>
					<input type='text' id='cb' name='cb' class='saisie-1Oem' value='' />
					<input type='submit' class='bouton' value='".$msg['142']."' />
				</div>
				<div class='colonne2'>&nbsp;</div>
			</div>
			<div class='row'>&nbsp;</div>";

$retour_liste = "<script type='text/javascript'>window.parent.location='../../../acquisition.php?categ=ach&sub=fact&action=list&id_bibli=".$id_bibli."'; </script></body></html>"; 

?>
